<?php

namespace App\Http\Controllers;

use App\Models\Part;
use App\Models\PartIssuance;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PartController extends Controller
{
    public function index(Request $request): View
    {
        $parts = Part::withCount('issuances')->orderBy('category')->orderBy('part_name')->paginate(12);
        $issuances = PartIssuance::with('part')->latest('issuance_date')->latest('id')->paginate(15);

        // Stats
        $totalItemsCount = Part::count();
        $totalStockUnits = Part::sum('stock_quantity');
        $lowStockParts = Part::whereColumn('stock_quantity', '<=', 'min_alert_threshold')->get();
        $totalInventoryValuation = Part::selectRaw('SUM(stock_quantity * unit_price) as total')->value('total') ?? 0;
        $totalIssuancesCount = PartIssuance::count();
        $totalIssuanceCost = PartIssuance::sum('total_cost');

        $categories = ['Gripper', 'Rapier', 'Heald & Dropper', 'Sensor', 'Mechanical', 'Electrical', 'Consumables'];
        $looms = collect(range(1, 24))->map(fn ($i) => sprintf('Loom-%02d', $i));

        return view('parts.index', compact(
            'parts',
            'issuances',
            'totalItemsCount',
            'totalStockUnits',
            'lowStockParts',
            'totalInventoryValuation',
            'totalIssuancesCount',
            'totalIssuanceCost',
            'categories',
            'looms'
        ));
    }

    public function storePart(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'part_name' => 'required|string|max:150',
            'part_code' => 'required|string|max:50|unique:parts,part_code',
            'category' => 'required|string|max:100',
            'stock_quantity' => 'required|integer|min:0',
            'min_alert_threshold' => 'required|integer|min:0',
            'unit' => 'required|string|max:20',
            'unit_price' => 'required|numeric|min:0',
            'rack_location' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
        ]);

        $part = Part::create($validated);

        return redirect()->route('parts.index')
            ->with('success', "Part '{$part->part_name}' [{$part->part_code}] added to store inventory successfully!");
    }

    public function issuePart(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'part_id' => 'required|exists:parts,id',
            'loom_no' => 'required|string|max:50',
            'issued_to_person' => 'required|string|max:150',
            'quantity' => 'required|integer|min:1',
            'issuance_date' => 'required|date',
            'remarks' => 'nullable|string',
        ]);

        $part = Part::findOrFail($validated['part_id']);

        if ($part->stock_quantity < $validated['quantity']) {
            return back()->withInput()->with('error', "Insufficient stock for '{$part->part_name}'. In stock: {$part->stock_quantity} {$part->unit}, requested: {$validated['quantity']} {$part->unit}.");
        }

        $totalCost = $validated['quantity'] * $part->unit_price;

        $issuance = PartIssuance::create([
            'part_id' => $part->id,
            'loom_no' => $validated['loom_no'],
            'issued_to_person' => $validated['issued_to_person'],
            'quantity' => $validated['quantity'],
            'unit_price' => $part->unit_price,
            'total_cost' => $totalCost,
            'issuance_date' => $validated['issuance_date'],
            'remarks' => $validated['remarks'],
        ]);

        // Decrement part stock
        $part->decrement('stock_quantity', $validated['quantity']);

        return redirect()->route('parts.index')
            ->with('success', "Issued {$validated['quantity']} {$part->unit} of '{$part->part_name}' to {$validated['loom_no']} ({$validated['issued_to_person']}) successfully! Remaining stock: {$part->stock_quantity} {$part->unit}.");
    }
}
