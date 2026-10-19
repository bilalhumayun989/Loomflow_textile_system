<?php

namespace App\Http\Controllers;

use App\Models\FabricStock;
use App\Models\SalesContract;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
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
            'totalInventoryValue'
        ));
    }

    public function storeStock(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'quality_name' => 'required|string|max:150|unique:fabric_stocks,quality_name',
            'construction' => 'required|string|max:150',
            'width_inches' => 'required|numeric|min:20|max:150',
            'current_stock_meters' => 'required|numeric|min:0',
            'total_rolls_in_stock' => 'required|integer|min:0',
            'committed_contract_meters' => 'required|numeric|min:0',
            'unit_cost_meter' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        if ($validated['committed_contract_meters'] > $validated['current_stock_meters']) {
            return back()->withInput()->with('error', 'Committed contract meters cannot exceed total current stock meters.');
        }

        $validated['available_open_meters'] = $validated['current_stock_meters'] - $validated['committed_contract_meters'];
        $validated['total_meters_produced'] = $validated['current_stock_meters'];

        $stock = FabricStock::create($validated);

        return redirect()->route('fabric.index')
            ->with('success', "Fabric Quality '{$stock->quality_name}' added to inventory ({$stock->current_stock_meters}m stock, {$stock->available_open_meters}m open sale ready)!");
    }
}
