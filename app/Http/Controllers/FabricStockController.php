<?php

namespace App\Http\Controllers;

use App\Models\FabricStock;
use App\Models\FabricStockMovement;
use App\Models\SalesContract;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class FabricStockController extends Controller
{
    public function index(): View
    {
        $fabricStocks = FabricStock::latest('updated_at')->limit(5)->get();
        $activeContracts = SalesContract::where('status', 'active')->latest()->limit(5)->get();
        $page = 'dashboard';

        return $this->renderPage($page, $fabricStocks, $activeContracts);
    }

    public function inventory(): View
    {
        return $this->renderPage(
            'inventory',
            FabricStock::latest('updated_at')->paginate(15),
            collect()
        );
    }

    public function contracts(): View
    {
        return $this->renderPage(
            'contracts',
            collect(),
            SalesContract::where('status', 'active')->latest()->paginate(20)
        );
    }

    /**
     * @param  Collection<int, FabricStock>|LengthAwarePaginator  $fabricStocks
     * @param  Collection<int, SalesContract>|LengthAwarePaginator  $activeContracts
     */
    private function renderPage(
        string $page,
        Collection|LengthAwarePaginator $fabricStocks,
        Collection|LengthAwarePaginator $activeContracts
    ): View {

        $allFabricQualities = FabricStock::orderBy('quality_name')->get();
        $stockMovements = FabricStockMovement::with('fabricStock')->latest('movement_date')->latest('id')
            ->when($page === 'dashboard', fn ($query) => $query->limit(10))->paginate($page === 'dashboard' ? 10 : 50);

        // Totals
        $totalMetersInStock = FabricStock::sum('current_stock_meters');
        $totalMetersCommitted = FabricStock::sum('committed_contract_meters');
        $totalMetersAvailableOpen = FabricStock::sum('available_open_meters');
        $totalRollsInStock = FabricStock::sum('total_rolls_in_stock');
        $totalInventoryValue = FabricStock::selectRaw('SUM(current_stock_meters * unit_cost_meter) as total')->value('total') ?? 0;

        return view('fabric.index', compact(
            'page',
            'fabricStocks',
            'activeContracts',
            'totalMetersInStock',
            'totalMetersCommitted',
            'totalMetersAvailableOpen',
            'totalRollsInStock',
            'totalInventoryValue',
            'allFabricQualities',
            'stockMovements'
        ));
    }

    public function storeStock(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'quality_mode' => 'required|in:existing,new',
            'fabric_stock_id' => 'required_if:quality_mode,existing|nullable|exists:fabric_stocks,id',
            'quality_name' => 'required_if:quality_mode,new|nullable|string|max:150|unique:fabric_stocks,quality_name',
            'construction' => 'nullable|string|max:150',
            'width_inches' => 'required_if:quality_mode,new|nullable|numeric|min:20|max:150',
            'read' => 'required_if:quality_mode,new|nullable|numeric|gt:0|max:100000',
            'pick' => 'required_if:quality_mode,new|nullable|numeric|gt:0|max:100000',
            'warp_count' => 'required_if:quality_mode,new|nullable|numeric|gt:0|max:100000',
            'weft_count' => 'required_if:quality_mode,new|nullable|numeric|gt:0|max:100000',
            'previous_stock_meters' => 'nullable|numeric|min:0',
            'daily_received_meters' => 'nullable|numeric|min:0',
            'adjustment_meters' => 'nullable|numeric|between:-999999999999,999999999999',
            'dispatch_meters' => 'nullable|numeric|min:0',
            'rolls_received' => 'nullable|integer|min:0',
            'total_rolls_in_stock' => 'nullable|integer|min:0',
            'area' => 'nullable|string|max:100',
            'unit_cost_meter' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $isNew = $validated['quality_mode'] === 'new';
        $received = (float) ($validated['daily_received_meters'] ?? 0);
        $adjustment = (float) ($validated['adjustment_meters'] ?? 0);
        $dispatch = (float) ($validated['dispatch_meters'] ?? 0);
        $stock = DB::transaction(function () use ($validated, $isNew, $received, $adjustment, $dispatch): FabricStock {
            if ($isNew) {
                $read = (float) $validated['read'];
                $pick = (float) $validated['pick'];
                $width = (float) $validated['width_inches'];
                $warpCount = (float) $validated['warp_count'];
                $weftCount = (float) $validated['weft_count'];
                $truncateFourDecimals = static fn (float $value): float => floor(($value + 0.000000001) * 10000) / 10000;
                $warpWeightPerMeter = $truncateFourDecimals($read * $width / $warpCount / 20 * 1.0936 / 40);
                $weftWeightPerMeter = $truncateFourDecimals($pick * $width / $weftCount / 20 * 1.0936 / 40);

                $stock = FabricStock::create([
                    'quality_name' => trim($validated['quality_name']),
                    'construction' => $validated['construction'] ?? null,
                    'width_inches' => $width,
                    'read' => $read,
                    'pick' => $pick,
                    'warp_count' => $warpCount,
                    'weft_count' => $weftCount,
                    'warp_weight_per_meter_lb' => $warpWeightPerMeter,
                    'weft_weight_per_meter_lb' => $weftWeightPerMeter,
                    'total_weight_per_meter_lb' => $warpWeightPerMeter + $weftWeightPerMeter,
                    'current_stock_meters' => 0,
                    'total_meters_produced' => 0,
                    'total_rolls_in_stock' => 0,
                    'committed_contract_meters' => 0,
                    'available_open_meters' => 0,
                    'unit_cost_meter' => $validated['unit_cost_meter'] ?? 0,
                    'area' => $validated['area'] ?? null,
                    'notes' => $validated['notes'] ?? null,
                ]);
                $previousStock = 0.0;
            } else {
                $stock = FabricStock::whereKey($validated['fabric_stock_id'])->lockForUpdate()->firstOrFail();
                $previousStock = (float) $stock->current_stock_meters;
            }

            $movementDate = now()->toDateString();
            $existingMovement = FabricStockMovement::where('fabric_stock_id', $stock->id)
                ->whereDate('movement_date', $movementDate)->lockForUpdate()->first();
            if ($existingMovement) {
                $previousStock = (float) $existingMovement->previous_stock_meters;
                $received += (float) $existingMovement->received_meters;
                $adjustment += (float) $existingMovement->adjustment_meters;
                $dispatch += (float) $existingMovement->dispatch_meters;
            }

            $closingStock = $previousStock + $received + $adjustment - $dispatch;
            if ($closingStock < 0) {
                throw ValidationException::withMessages([
                    'dispatch_meters' => 'Dispatch cannot exceed the available fabric stock.',
                ]);
            }

            FabricStockMovement::updateOrCreate(
                ['fabric_stock_id' => $stock->id, 'movement_date' => $movementDate],
                [
                    'previous_stock_meters' => $previousStock,
                    'received_meters' => $received,
                    'adjustment_meters' => $adjustment,
                    'dispatch_meters' => $dispatch,
                    'closing_stock_meters' => $closingStock,
                    'rolls_received' => (int) ($validated['rolls_received'] ?? 0) + (int) ($existingMovement?->rolls_received ?? 0),
                    'area' => $validated['area'] ?? $stock->area,
                    'remarks' => $validated['notes'] ?? null,
                ]
            );

            $stock->current_stock_meters = $closingStock;
            $stock->total_meters_produced = (float) $stock->total_meters_produced + (float) ($validated['daily_received_meters'] ?? 0);
            $stock->total_rolls_in_stock = max(0, (int) $stock->total_rolls_in_stock + (int) ($validated['rolls_received'] ?? 0));
            $stock->available_open_meters = max(0, $closingStock - (float) $stock->committed_contract_meters);
            $stock->area = $validated['area'] ?? $stock->area;
            $stock->save();

            return $stock;
        });

        return redirect()->route('fabric.index')
            ->with('success', "Stock updated for {$stock->quality_name}: {$stock->current_stock_meters}m closing balance.");
    }
}
