<?php

namespace App\Http\Controllers;

use App\Models\FabricStock;
use App\Models\FabricStockMovement;
use App\Models\Production;
use App\Models\SalesContract;
use App\Models\SizingBeam;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
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
            ->with('productions:id,sizing_beam_id,loom_no')
            ->withSum('productions', 'net_meters_produced')
            ->withSum('productions', 'beam_meters_consumed')
            ->orderByDesc('id')
            ->get();
        $sizingSlipProductionRows = $page === 'leakage'
            ? DB::table('sizing_slips')
                ->leftJoin('sizing_beams', 'sizing_beams.sizing_slip_id', '=', 'sizing_slips.id')
                ->leftJoin('productions', 'productions.sizing_beam_id', '=', 'sizing_beams.id')
                ->select([
                    'sizing_slips.id',
                    'sizing_slips.slip_no',
                    'sizing_slips.yarn_quality',
                    'sizing_slips.total_beams',
                ])
                ->selectRaw('COUNT(DISTINCT CASE WHEN productions.id IS NOT NULL THEN sizing_beams.id END) as used_beam_count')
                ->selectRaw('COUNT(DISTINCT productions.loom_no) as loom_count')
                ->selectRaw('MAX(sizing_slips.total_length_meters * sizing_slips.width_multiplier) as expected_total_meters')
                ->selectRaw('COALESCE(SUM(productions.expected_fabric_meters), 0) as expected_consumed_meters')
                ->selectRaw('COALESCE(SUM(productions.net_meters_produced), 0) as actual_woven_meters')
                ->selectRaw('COALESCE(SUM(productions.leakage_waste_meters), 0) as recorded_leakage_meters')
                ->groupBy('sizing_slips.id', 'sizing_slips.slip_no', 'sizing_slips.yarn_quality', 'sizing_slips.total_beams')
                ->orderByDesc('sizing_slips.id')
                ->get()
            : collect();
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
            'sizingSlipProductionRows',
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
        if ($request->has('rolls')) {
            return $this->storeRollRegister($request);
        }

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
            $fabric->daily_received_meters += $netMetersProduced;
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

    private function storeRollRegister(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'production_date' => 'required|date',
            'fabric_stock_id' => 'required|exists:fabric_stocks,id',
            'rolls' => 'required|array|min:1|max:200',
            'rolls.*.loom_no' => 'required|string|max:50',
            'rolls.*.meters' => 'required|numeric|gt:0|max:100000',
            'rolls.*.beam_meters_consumed' => 'nullable|numeric|gt:0|max:100000',
            'remarks' => 'nullable|string|max:1000',
        ]);

        $totalMeters = round(array_sum(array_map(static fn (array $roll): float => (float) $roll['meters'], $validated['rolls'])), 2);

        DB::transaction(function () use ($validated, $totalMeters): void {
            $fabric = FabricStock::whereKey($validated['fabric_stock_id'])->lockForUpdate()->firstOrFail();
            $movementDate = $validated['production_date'];
            $movement = FabricStockMovement::where('fabric_stock_id', $fabric->id)
                ->whereDate('movement_date', $movementDate)->lockForUpdate()->first();
            $previousStock = $movement ? (float) $movement->previous_stock_meters : (float) $fabric->current_stock_meters;
            $receivedTotal = $movement ? (float) $movement->received_meters + $totalMeters : $totalMeters;
            $adjustment = (float) ($movement?->adjustment_meters ?? 0);
            $dispatch = (float) ($movement?->dispatch_meters ?? 0);
            $closingStock = $previousStock + $receivedTotal + $adjustment - $dispatch;

            foreach ($validated['rolls'] as $roll) {
                $beam = SizingBeam::where('assigned_loom_no', $roll['loom_no'])
                    ->where('status', 'mounted_on_loom')
                    ->lockForUpdate()
                    ->first();
                $beamMetersConsumed = 0.0;
                $expectedFabricMeters = 0.0;
                $leakageMeters = 0.0;
                $leakagePercentage = 0.0;
                $efficiencyPercentage = 0.0;

                if ($beam) {
                    $beam->loadMissing('sizingSlip');
                    $widthMultiplier = max(0.01, (float) ($beam->sizingSlip?->width_multiplier ?? 1));
                    $beamMetersConsumed = round((float) ($roll['beam_meters_consumed'] ?? 0), 2);

                    if ($beamMetersConsumed <= 0) {
                        throw ValidationException::withMessages([
                            'rolls' => 'Enter the actual warp meters consumed for each loom with a mounted beam so expected cloth can be calculated from its sizing width factor.',
                        ]);
                    }

                    if ($beamMetersConsumed > (float) $beam->remaining_length_meters) {
                        throw ValidationException::withMessages([
                            'rolls' => 'The production meters exceed the mounted beam length for one of the selected looms. Record the actual meters consumed or finish that beam before saving.',
                        ]);
                    }

                    $expectedFabricMeters = round($beamMetersConsumed * $widthMultiplier, 2);
                    $leakageMeters = max(0, $expectedFabricMeters - (float) $roll['meters']);
                    $leakagePercentage = $expectedFabricMeters > 0
                        ? round(($leakageMeters / $expectedFabricMeters) * 100, 2)
                        : 0;
                    $beam->remaining_length_meters = round((float) $beam->remaining_length_meters - $beamMetersConsumed, 2);
                    if ($beam->remaining_length_meters <= 0) {
                        $beam->remaining_length_meters = 0;
                        $beam->status = 'exhausted';
                    }
                    $beam->save();
                }

                Production::create([
                    'production_date' => $movementDate,
                    'shift' => 'Day',
                    'loom_no' => $roll['loom_no'],
                    'operator_name' => 'Production register',
                    'sizing_beam_id' => $beam?->id,
                    'fabric_stock_id' => $fabric->id,
                    'fabric_quality' => $fabric->quality_name,
                    'start_meter' => 0,
                    'end_meter' => $roll['meters'],
                    'net_meters_produced' => $roll['meters'],
                    'beam_meters_consumed' => $beamMetersConsumed,
                    'expected_fabric_meters' => $expectedFabricMeters,
                    'leakage_waste_meters' => $leakageMeters,
                    'leakage_percentage' => $leakagePercentage,
                    'picks_per_inch' => 60,
                    'rpm' => 350,
                    'efficiency_percentage' => $efficiencyPercentage,
                    'remarks' => $validated['remarks'] ?? null,
                ]);
            }

            FabricStockMovement::updateOrCreate(
                ['fabric_stock_id' => $fabric->id, 'movement_date' => $movementDate],
                [
                    'previous_stock_meters' => $previousStock,
                    'received_meters' => $receivedTotal,
                    'adjustment_meters' => $adjustment,
                    'dispatch_meters' => $dispatch,
                    'closing_stock_meters' => $closingStock,
                    'rolls_received' => (int) ($movement?->rolls_received ?? 0) + count($validated['rolls']),
                    'area' => $movement?->area ?? $fabric->area,
                    'remarks' => $validated['remarks'] ?? $movement?->remarks,
                ]
            );

            $fabric->current_stock_meters = $closingStock;
            $fabric->daily_received_meters = $receivedTotal;
            $fabric->total_meters_produced = (float) $fabric->total_meters_produced + $totalMeters;
            $fabric->total_rolls_in_stock += count($validated['rolls']);
            $fabric->available_open_meters = max(0, $closingStock - (float) $fabric->committed_contract_meters);
            $fabric->save();
        });

        return redirect()->route('production.index')->with('success', "Recorded {$totalMeters}m across ".count($validated['rolls']).' loom rolls and updated fabric stock.');
    }
}
