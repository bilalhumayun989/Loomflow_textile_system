<?php

namespace App\Http\Controllers;

use App\Models\Ledger;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LedgerController extends Controller
{
    public function index(Request $request): View
    {
        $query = Ledger::latest('transaction_date')->latest('id');

        if ($request->filled('party_name')) {
            $query->where('party_name', $request->party_name);
        }

        if ($request->filled('party_type')) {
            $query->where('party_type', $request->party_type);
        }

        $ledgers = $query->paginate(20)->withQueryString();

        $parties = Ledger::select('party_name', 'party_type')->distinct()->orderBy('party_name')->get();

        $totalDebit = Ledger::sum('debit');
        $totalCredit = Ledger::sum('credit');
        $netReceivableOrPayable = $totalDebit - $totalCredit;

        return view('ledgers.index', compact(
            'ledgers',
            'parties',
            'totalDebit',
            'totalCredit',
            'netReceivableOrPayable'
        ));
    }
}
