<?php

namespace App\Http\Controllers;

use App\Models\FabricStock;
use App\Models\Production;
use App\Models\SalesContract;
use App\Models\SizingBeam;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ProductionController extends Controller
{
    public function index(): View
    {
        $productions = Production::with('beam')->latest('production_date')->latest('id')->limit(5)->get();

        return $this->renderPage('dashboard', $productions);
    }

    public function loomSummary(): View
    {
        return $this->renderPage('loom-summary', collect());
    }

    public function leakage(): View
    {
        return $this->renderPage('leakage', collect());
    }

    public function history(Request $request): View
    {
        $query = Production::with('beam')->latest('production_date')->latest('id');

        if ($request->filled('loom_no')) {
            $query->where('loom_no', $request->loom_no);
        }

        if ($request->filled('date')) {
            $query->whereDate('production_date', $request->date);
        }

        return $this->renderPage('history', $query->paginate(15)->withQueryString());
    }

    /**
     * @param  Collection<int, Production>|LengthAwarePaginator  $productions
     */
    private function renderPage(string $page, Collection|LengthAwarePaginator $productions): View
    {

        // Active Beams mounted or ready
        $activeBeams = SizingBeam::withSum('productions', 'net_meters_produced')
            ->with('sizingSlip')
            ->where('status', 'mounted_on_loom')
            ->get();
        $beamLeakageRows = SizingBeam::with('sizingSlip')
            ->withSum('productions', 'net_meters_produced')
            ->withSum('productions', 'beam_meters_consumed')
            ->orderByDesc('id')
            ->get();
        $fabricQualities = FabricStock::orderBy('quality_name')->get();
        $looms = collect(range(1, 24))->map(fn ($i) => sprintf('Loom-%02d', $i));

        // Aggregate statistics
        $totalFabricProduced = Production::sum('net_meters_produced');
        $totalBeamConsumed = Production::sum('beam_meters_consumed');
        $totalLeakageMeters = Production::sum('leakage_waste_meters');
        $totalExpectedFabricMeters = Production::sum('expected_fabric_meters');
        $overallLeakagePercentage = $totalExpectedFabricMeters > 0 ? round(($totalLeakageMeters / $totalExpectedFabricMeters) * 100, 2) : 0;
        $avgEfficiency = round(Production::avg('efficiency_percentage') ?? 87.5, 1);

        // Loom-wise performance summary
        $loomWiseStats = Production::selectRaw('loom_no, COUNT(*) as shift_count, SUM(net_meters_produced) as total_produced, SUM(beam_meters_consumed) as total_beam, SUM(expected_fabric_meters) as total_expected_fabric, SUM(leakage_waste_meters) as total_leakage, AVG(efficiency_percentage) as avg_eff')
            ->groupBy('loom_no')
            ->orderBy('loom_no')
            ->get();

        return view('production.index', compact(
            'page',
            'productions',
            'activeBeams',
            'beamLeakageRows',
            'fabricQualities',
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
            'fabric_stock_id' => 'required|exists:fabric_stocks,id',
            'start_meter' => 'required|numeric|min:0',
            'end_meter' => 'required|numeric|gt:start_meter',
            'beam_meters_consumed' => 'required|numeric|min:1',
            'picks_per_inch' => 'required|integer|min:20|max:200',
            'rpm' => 'required|integer|min:100|max:800',
            'remarks' => 'nullable|string',
        ]);

        $netMetersProduced = $validated['end_meter'] - $validated['start_meter'];
        $beamConsumed = (float) $validated['beam_meters_consumed'];

        $production = DB::transaction(function () use ($validated, $beamConsumed, $netMetersProduced) {
            $beam = SizingBeam::whereKey($validated['sizing_beam_id'])->lockForUpdate()->firstOrFail();
            $beam->loadMissing('sizingSlip');
            $fabric = FabricStock::whereKey($validated['fabric_stock_id'])->lockForUpdate()->firstOrFail();

            if ($beam->status !== 'mounted_on_loom' || $beam->assigned_loom_no !== $validated['loom_no']) {
                return null;
            }

            if ($beamConsumed > (float) $beam->remaining_length_meters) {
                return null;
            }

            $widthMultiplier = (float) ($beam->sizingSlip?->width_multiplier ?? 1);
            $expectedFabricMeters = round($beamConsumed * $widthMultiplier, 2);
            $leakageMeters = max(0, $expectedFabricMeters - $netMetersProduced);
            $leakagePercentage = $expectedFabricMeters > 0 ? round(($leakageMeters / $expectedFabricMeters) * 100, 2) : 0;
            $theoreticalMeters = ($validated['rpm'] * 60 * 12) / ($validated['picks_per_inch'] * 39.37);
            $efficiency = $theoreticalMeters > 0 ? min(99.9, round(($netMetersProduced / $theoreticalMeters) * 100, 2)) : 85.0;

            $validated['fabric_quality'] = $fabric->quality_name;
            $validated['net_meters_produced'] = $netMetersProduced;
            $validated['expected_fabric_meters'] = $expectedFabricMeters;
            $validated['leakage_waste_meters'] = $leakageMeters;
            $validated['leakage_percentage'] = $leakagePercentage;
            $validated['efficiency_percentage'] = $efficiency;

            $production = Production::create($validated);

            $newRemaining = max(0, $beam->remaining_length_meters - $beamConsumed);
            $beam->remaining_length_meters = $newRemaining;
            if ($newRemaining <= 0) {
                $beam->status = 'exhausted';
            }
            $beam->save();

            $fabric->total_meters_produced += $netMetersProduced;
            $fabric->current_stock_meters += $netMetersProduced;
            $activeContractMeters = SalesContract::where('fabric_stock_id', $fabric->id)
                ->where('status', 'active')
                ->sum('remaining_meters');
            $fabric->committed_contract_meters = min(
                (float) $fabric->current_stock_meters,
                (float) $activeContractMeters
            );
            $fabric->available_open_meters = max(
                0,
                $fabric->current_stock_meters - $fabric->committed_contract_meters
            );
            $fabric->total_rolls_in_stock += max(1, (int) round($netMetersProduced / 100));
            $fabric->save();

            return $production;
        });

        if (! $production) {
            return back()->withInput()->with('error', 'Select a beam mounted on the chosen loom and enter consumption within its remaining meters.');
        }

        $leakageMeters = (float) $production->leakage_waste_meters;
        $leakagePercentage = (float) $production->leakage_percentage;
        $efficiency = (float) $production->efficiency_percentage;

        return redirect()->route('production.index')
            ->with('success', "Production recorded for {$production->loom_no}! Produced: {$netMetersProduced}m | Consumed: {$beamConsumed}m | Leakage: {$leakageMeters}m ({$leakagePercentage}%) | Efficiency: {$efficiency}%");
    }
}
