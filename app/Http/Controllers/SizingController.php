<?php

namespace App\Http\Controllers;

use App\Models\BagTransaction;
use App\Models\SizingBeam;
use App\Models\SizingSlip;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class SizingController extends Controller
{
    public function index(): View
    {
        $slips = SizingSlip::with('beams')->latest('slip_date')->latest('id')->limit(5)->get();
        $beams = SizingBeam::with('sizingSlip')->latest('id')->limit(5)->get();
        $page = 'dashboard';

        return $this->renderPage($page, $slips, $beams);
    }

    public function beams(): View
    {
        return $this->renderPage(
            'beams',
            collect(),
            SizingBeam::with('sizingSlip')->latest('id')->paginate(15)
        );
    }

    public function slips(): View
    {
        return $this->renderPage(
            'slips',
            SizingSlip::with('beams')->latest('slip_date')->latest('id')->paginate(10),
            collect()
        );
    }

    /**
     * @param  Collection<int, SizingSlip>|LengthAwarePaginator  $slips
     * @param  Collection<int, SizingBeam>|LengthAwarePaginator  $beams
     */
    private function renderPage(
        string $page,
        Collection|LengthAwarePaginator $slips,
        Collection|LengthAwarePaginator $beams
    ): View {

        // Stats
        $totalSlips = SizingSlip::count();
        $totalAllocatedBags = SizingSlip::sum('bags_allocated');
        $totalUsedBags = SizingSlip::sum('bags_used');
        $totalReturnedBags = SizingSlip::sum('bags_returned_to_factory');
        $totalHeldWithSizing = SizingSlip::sum('bags_held_with_sizing');

        $totalBeamsCount = SizingBeam::count();
        $mountedBeamsCount = SizingBeam::where('status', 'mounted_on_loom')->count();
        $readyBeamsCount = SizingBeam::where('status', 'ready_in_store')->count();
        $exhaustedBeamsCount = SizingBeam::where('status', 'exhausted')->count();
        $totalBeamMetersProduced = SizingBeam::sum('total_length_meters');
        $remainingBeamMetersOnLooms = SizingBeam::where('status', 'mounted_on_loom')->sum('remaining_length_meters');

        // All active looms list (1 to 24)
        $looms = collect(range(1, 24))->map(fn ($i) => sprintf('Loom-%02d', $i));

        return view('sizing.index', compact(
            'page',
            'slips',
            'beams',
            'totalSlips',
            'totalAllocatedBags',
            'totalUsedBags',
            'totalReturnedBags',
            'totalHeldWithSizing',
            'totalBeamsCount',
            'mountedBeamsCount',
            'readyBeamsCount',
            'exhaustedBeamsCount',
            'totalBeamMetersProduced',
            'remainingBeamMetersOnLooms',
            'looms'
        ));
    }

    public function storeSlip(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'slip_no' => 'required|string|max:100|unique:sizing_slips,slip_no',
            'sizing_party' => 'required|string|max:150',
            'yarn_quality' => 'required|string|max:100',
            'bags_allocated' => 'required|integer|min:1',
            'bags_used' => 'required|integer|min:1',
            'bags_returned_to_factory' => 'required|integer|min:0',
            'bags_held_with_sizing' => 'required|integer|min:0',
            'total_beams' => 'required|integer|min:1|max:20',
            'total_length_meters' => 'required|numeric|min:100',
            'slip_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $accountedBags = $validated['bags_used'] + $validated['bags_returned_to_factory'] + $validated['bags_held_with_sizing'];
        if ($accountedBags != $validated['bags_allocated']) {
            return back()->withInput()->with('error', "Error: Bags Used ({$validated['bags_used']}) + Returned ({$validated['bags_returned_to_factory']}) + Held ({$validated['bags_held_with_sizing']}) = {$accountedBags}, which does not match Allocated Bags ({$validated['bags_allocated']}).");
        }

        $slip = SizingSlip::create($validated);

        // Auto create beams for this sizing slip
        $beamCount = (int) $validated['total_beams'];
        $meterPerBeam = round($validated['total_length_meters'] / $beamCount, 2);

        $lastBeamId = SizingBeam::max('id') ?? 100;
        for ($i = 1; $i <= $beamCount; $i++) {
            $beamNumber = 'BM-'.($lastBeamId + $i);
            SizingBeam::create([
                'sizing_slip_id' => $slip->id,
                'beam_no' => $beamNumber,
                'yarn_quality' => $slip->yarn_quality,
                'total_length_meters' => $meterPerBeam,
                'remaining_length_meters' => $meterPerBeam,
                'status' => 'ready_in_store',
                'notes' => "Beam {$i} of {$beamCount} from Sizing Slip {$slip->slip_no}",
            ]);
        }

        // If bags returned back to factory, record automatic bag transaction
        if ($slip->bags_returned_to_factory > 0) {
            BagTransaction::create([
                'transaction_type' => 'return_from_sizing',
                'reference_no' => $slip->slip_no,
                'quality' => $slip->yarn_quality,
                'quantity_bags' => $slip->bags_returned_to_factory,
                'source' => $slip->sizing_party,
                'destination' => 'Factory Yarn Store',
                'party_name' => $slip->sizing_party,
                'transaction_date' => $slip->slip_date,
                'notes' => "Automatic return of unused bags from Sizing Slip {$slip->slip_no}",
            ]);
        }

        return redirect()->route('sizing.index')
            ->with('success', "Sizing Slip {$slip->slip_no} saved! Generated {$beamCount} Beams ({$meterPerBeam}m each) ready in store.");
    }

    public function assignBeam(Request $request, SizingBeam $beam): RedirectResponse
    {
        $validated = $request->validate([
            'assigned_loom_no' => 'required|string|max:50',
            'mounted_date' => 'required|date',
        ]);

        $beam->update([
            'assigned_loom_no' => $validated['assigned_loom_no'],
            'mounted_date' => $validated['mounted_date'],
            'status' => 'mounted_on_loom',
        ]);

        return redirect()->route('sizing.index')
            ->with('success', "Beam {$beam->beam_no} mounted onto {$beam->assigned_loom_no} successfully!");
    }

    public function exhaustBeam(SizingBeam $beam): RedirectResponse
    {
        $beam->update([
            'status' => 'exhausted',
            'remaining_length_meters' => 0,
        ]);

        return redirect()->route('sizing.index')
            ->with('info', "Beam {$beam->beam_no} marked as finished / exhausted.");
    }
}
