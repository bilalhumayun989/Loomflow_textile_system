<?php

namespace App\Http\Controllers;

use App\Models\FabricStock;
use App\Models\Production;
use App\Models\SizingBeam;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductionController extends Controller
{
    public function index(Request $request): View
    {
        $query = Production::with('beam')->latest('production_date')->latest('id');

        if ($request->filled('loom_no')) {
            $query->where('loom_no', $request->loom_no);
        }

        if ($request->filled('date')) {
            $query->whereDate('production_date', $request->date);
        }

        $productions = $query->paginate(15)->withQueryString();

        // Active Beams mounted or ready
        $activeBeams = SizingBeam::where('status', '!=', 'exhausted')->get();
        $looms = collect(range(1, 24))->map(fn ($i) => sprintf('Loom-%02d', $i));

        // Aggregate statistics
        $totalFabricProduced = Production::sum('net_meters_produced');
        $totalBeamConsumed = Production::sum('beam_meters_consumed');
        $totalLeakageMeters = Production::sum('leakage_waste_meters');
        $overallLeakagePercentage = $totalBeamConsumed > 0 ? round(($totalLeakageMeters / $totalBeamConsumed) * 100, 2) : 0;
        $avgEfficiency = round(Production::avg('efficiency_percentage') ?? 87.5, 1);

        // Loom-wise performance summary
        $loomWiseStats = Production::selectRaw('loom_no, COUNT(*) as shift_count, SUM(net_meters_produced) as total_produced, SUM(beam_meters_consumed) as total_beam, SUM(leakage_waste_meters) as total_leakage, AVG(efficiency_percentage) as avg_eff')
            ->groupBy('loom_no')
            ->orderBy('loom_no')
            ->get();

        return view('production.index', compact(
            'productions',
            'activeBeams',
            'looms',
            'totalFabricProduced',
            'totalBeamConsumed',
            'totalLeakageMeters',
            'overallLeakagePercentage',
            'avgEfficiency',
            'loomWiseStats'
        ));
    }

    public function storeProduction(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'production_date' => 'required|date',
            'shift' => 'required|in:Day,Night',
            'loom_no' => 'required|string|max:50',
            'operator_name' => 'required|string|max:150',
            'sizing_beam_id' => 'required|exists:sizing_beams,id',
            'fabric_quality' => 'required|string|max:150',
            'start_meter' => 'required|numeric|min:0',
            'end_meter' => 'required|numeric|gt:start_meter',
            'beam_meters_consumed' => 'required|numeric|min:1',
            'picks_per_inch' => 'required|integer|min:20|max:200',
            'rpm' => 'required|integer|min:100|max:800',
            'remarks' => 'nullable|string',
        ]);

        $netMetersProduced = $validated['end_meter'] - $validated['start_meter'];
        $beamConsumed = (float) $validated['beam_meters_consumed'];

        // Calculate leakage / wastage
        $leakageMeters = max(0, $beamConsumed - $netMetersProduced);
        $leakagePercentage = $beamConsumed > 0 ? round(($leakageMeters / $beamConsumed) * 100, 2) : 0;

        // Calculate efficiency: (Net Meters / Theoretical Max Meters in 12 hours) * 100
        // Theoretical max meters in 12 hours = (RPM * 60 min * 12 hr) / (PPI * 39.37 inches per meter)
        $theoreticalMeters = ($validated['rpm'] * 60 * 12) / ($validated['picks_per_inch'] * 39.37);
        $efficiency = $theoreticalMeters > 0 ? min(99.9, round(($netMetersProduced / $theoreticalMeters) * 100, 2)) : 85.0;

        $validated['net_meters_produced'] = $netMetersProduced;
        $validated['leakage_waste_meters'] = $leakageMeters;
        $validated['leakage_percentage'] = $leakagePercentage;
        $validated['efficiency_percentage'] = $efficiency;

        $production = Production::create($validated);

        // Deduct beam length
        $beam = SizingBeam::find($validated['sizing_beam_id']);
        if ($beam) {
            $newRemaining = max(0, $beam->remaining_length_meters - $beamConsumed);
            $beam->remaining_length_meters = $newRemaining;
            if ($newRemaining <= 0) {
                $beam->status = 'exhausted';
            } else {
                $beam->status = 'mounted_on_loom';
                $beam->assigned_loom_no = $validated['loom_no'];
            }
            $beam->save();
        }

        // Add produced fabric to FabricStock
        $fabric = FabricStock::firstOrCreate(
            ['quality_name' => $validated['fabric_quality']],
            [
                'construction' => "{$validated['picks_per_inch']} PPI on {$validated['loom_no']}",
                'width_inches' => 63.0,
                'total_meters_produced' => 0,
                'total_rolls_in_stock' => 0,
                'current_stock_meters' => 0,
                'committed_contract_meters' => 0,
                'available_open_meters' => 0,
                'unit_cost_meter' => 210.00,
            ]
        );

        $fabric->increment('total_meters_produced', $netMetersProduced);
        $fabric->increment('current_stock_meters', $netMetersProduced);
        $fabric->increment('available_open_meters', $netMetersProduced);
        // Approximately 1 roll per 100 meters
        $fabric->increment('total_rolls_in_stock', max(1, (int) round($netMetersProduced / 100)));

        return redirect()->route('production.index')
            ->with('success', "Production recorded for {$production->loom_no}! Produced: {$netMetersProduced}m | Consumed: {$beamConsumed}m | Leakage: {$leakageMeters}m ({$leakagePercentage}%) | Efficiency: {$efficiency}%");
    }
}
