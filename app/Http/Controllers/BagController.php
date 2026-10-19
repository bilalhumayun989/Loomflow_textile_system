<?php

namespace App\Http\Controllers;

use App\Models\BagPurchase;
use App\Models\BagTransaction;
use App\Models\Ledger;
use App\Models\SizingSlip;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class BagController extends Controller
{
    public function index(): View
    {
        $purchases = BagPurchase::latest('purchase_date')->latest('id')->limit(5)->get();
        $transactions = BagTransaction::latest('transaction_date')->latest('id')->limit(5)->get();
        $sizingSlips = SizingSlip::latest('slip_date')->latest('id')->limit(5)->get();
        $page = 'dashboard';

        return $this->renderPage($page, $purchases, $transactions, $sizingSlips);
    }

    public function purchases(): View
    {
        return $this->renderPage(
            'purchases',
            BagPurchase::latest('purchase_date')->latest('id')->paginate(15),
            collect(),
            collect()
        );
    }

    public function ledger(): View
    {
        return $this->renderPage(
            'ledger',
            collect(),
            BagTransaction::latest('transaction_date')->latest('id')->paginate(20),
            collect()
        );
    }

    public function sizingUsage(): View
    {
        return $this->renderPage(
            'sizing',
            collect(),
            collect(),
            SizingSlip::latest('slip_date')->paginate(20)
        );
    }

    /**
     * @param  Collection<int, BagPurchase>|LengthAwarePaginator  $purchases
     * @param  Collection<int, BagTransaction>|LengthAwarePaginator  $transactions
     * @param  Collection<int, SizingSlip>|LengthAwarePaginator  $sizingSlips
     */
    private function renderPage(
        string $page,
        Collection|LengthAwarePaginator $purchases,
        Collection|LengthAwarePaginator $transactions,
        Collection|LengthAwarePaginator $sizingSlips
    ): View {

        // Calculate exact store balances
        $totalPurchased = BagPurchase::sum('total_bags');
        $bagsDirectToSizing = BagPurchase::sum('bags_to_sizing');
        $bagsDirectToFactory = BagPurchase::sum('bags_to_factory');

        $factoryIssuedToSizing = BagTransaction::where('transaction_type', 'issue_to_sizing')->sum('quantity_bags');
        $factoryReturnedFromSizing = BagTransaction::where('transaction_type', 'return_from_sizing')->sum('quantity_bags');

        // Current Factory Stock Store
        $factoryStoreStock = max(0, $bagsDirectToFactory - $factoryIssuedToSizing + $factoryReturnedFromSizing);

        // Current Sizing Units Stock
        $totalBagsReceivedBySizing = $bagsDirectToSizing + $factoryIssuedToSizing;
        $totalBagsUsedInBeams = SizingSlip::sum('bags_used');
        $sizingStockRemaining = max(0, $totalBagsReceivedBySizing - ($totalBagsUsedInBeams + $factoryReturnedFromSizing));

        // Unused bags breakdown
        $unusedBagsReturned = SizingSlip::sum('bags_returned_to_factory');
        $unusedBagsHeldWithSizing = SizingSlip::sum('bags_held_with_sizing');

        // Total Yarn Investment
        $totalYarnInvestment = BagPurchase::sum('total_amount');

        return view('bags.index', compact(
            'page',
            'purchases',
            'transactions',
            'sizingSlips',
            'totalPurchased',
            'bagsDirectToSizing',
            'bagsDirectToFactory',
            'factoryStoreStock',
            'sizingStockRemaining',
            'totalBagsUsedInBeams',
            'unusedBagsReturned',
            'unusedBagsHeldWithSizing',
            'totalYarnInvestment'
        ));
    }

    public function storePurchase(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'challan_no' => 'required|string|max:100',
            'supplier_name' => 'required|string|max:150',
            'quality' => 'required|string|max:100',
            'total_bags' => 'required|integer|min:1',
            'bags_to_sizing' => 'required|integer|min:0',
            'bags_to_factory' => 'required|integer|min:0',
            'sizing_destination' => 'nullable|string|max:150',
            'rate_per_bag' => 'required|numeric|min:0',
            'purchase_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        if (($validated['bags_to_sizing'] + $validated['bags_to_factory']) != $validated['total_bags']) {
            return back()->withInput()->with('error', 'Error: Split bags (Sizing + Factory) must exactly equal Total Bags ('.$validated['total_bags'].').');
        }

        if ($validated['bags_to_sizing'] > 0 && empty($validated['sizing_destination'])) {
            return back()->withInput()->withErrors([
                'sizing_destination' => 'Enter the sizing mill or contractor receiving these bags.',
            ]);
        }

        $totalAmount = $validated['total_bags'] * $validated['rate_per_bag'];
        $validated['total_amount'] = $totalAmount;

        $purchase = BagPurchase::create($validated);

        // 1. Transaction for bags to factory
        if ($purchase->bags_to_factory > 0) {
            BagTransaction::create([
                'transaction_type' => 'purchase_factory',
                'reference_no' => $purchase->challan_no,
                'quality' => $purchase->quality,
                'quantity_bags' => $purchase->bags_to_factory,
                'source' => $purchase->supplier_name,
                'destination' => 'Factory Yarn Store',
                'party_name' => 'Internal Store',
                'transaction_date' => $purchase->purchase_date,
                'notes' => 'Direct receipt into factory yarn warehouse',
            ]);
        }

        // 2. Transaction for bags to sizing
        if ($purchase->bags_to_sizing > 0) {
            BagTransaction::create([
                'transaction_type' => 'purchase_sizing',
                'reference_no' => $purchase->challan_no,
                'quality' => $purchase->quality,
                'quantity_bags' => $purchase->bags_to_sizing,
                'source' => $purchase->supplier_name,
                'destination' => $purchase->sizing_destination,
                'party_name' => $purchase->sizing_destination,
                'transaction_date' => $purchase->purchase_date,
                'notes' => 'Direct delivery from spinning mill to sizing unit',
            ]);
        }

        // 3. Record in Ledger
        Ledger::create([
            'party_name' => $purchase->supplier_name,
            'party_type' => 'yarn_supplier',
            'transaction_date' => $purchase->purchase_date,
            'voucher_no' => $purchase->challan_no,
            'description' => "Yarn Purchase: {$purchase->total_bags} bags ({$purchase->quality}) @ Rs. {$purchase->rate_per_bag}",
            'debit' => 0.00,
            'credit' => $totalAmount,
            'balance' => -$totalAmount,
            'notes' => $purchase->notes,
        ]);

        return redirect()->route('bags.index')
            ->with('success', "Bag Purchase {$purchase->challan_no} recorded successfully! ({$purchase->bags_to_sizing} to Sizing, {$purchase->bags_to_factory} to Factory)");
    }

    public function issueStock(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'reference_no' => 'required|string|max:100',
            'quality' => 'required|string|max:100',
            'quantity_bags' => 'required|integer|min:1',
            'destination' => 'required|string|max:150',
            'party_name' => 'required|string|max:150',
            'transaction_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $validated['transaction_type'] = 'issue_to_sizing';
        $validated['source'] = 'Factory Yarn Store';

        BagTransaction::create($validated);

        return redirect()->route('bags.index')
            ->with('success', "Issued {$validated['quantity_bags']} bags of {$validated['quality']} from Factory Store to {$validated['party_name']}.");
    }

    public function returnStock(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'reference_no' => 'required|string|max:100',
            'quality' => 'required|string|max:100',
            'quantity_bags' => 'required|integer|min:1',
            'source' => 'required|string|max:150',
            'party_name' => 'required|string|max:150',
            'transaction_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $validated['transaction_type'] = 'return_from_sizing';
        $validated['destination'] = 'Factory Yarn Store';

        BagTransaction::create($validated);

        return redirect()->route('bags.index')
            ->with('success', "Received back {$validated['quantity_bags']} unused bags of {$validated['quality']} into Factory Store.");
    }
}
