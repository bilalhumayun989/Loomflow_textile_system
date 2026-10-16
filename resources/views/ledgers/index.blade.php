@extends('layouts.app')

@section('title', 'Party Ledgers & Audit Statements')

@section('content')
<div class="space-y-6">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-2 border-b border-slate-800">
        <div>
            <div class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-teal-400"></span>
                <span class="text-xs font-bold uppercase tracking-wider text-teal-400">Accounts & Ledgers</span>
            </div>
            <h1 class="text-2xl font-black text-white tracking-tight">Party Ledgers & Statements</h1>
            <p class="text-xs text-slate-400 mt-0.5">Audit trail and financial balance statements for Yarn Suppliers, Sizing Mills, Fabric Buyers, and Parts Vendors.</p>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('dashboard') }}" class="px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 text-xs font-bold transition-colors flex items-center gap-1.5">
                <svg class="w-4 h-4 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                <span>Back to Hub</span>
            </a>
        </div>
    </div>

    <!-- KPIs -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        
        <div class="glass-card rounded-2xl p-4 border border-emerald-500/20 bg-emerald-500/5">
            <div class="text-[11px] font-bold uppercase tracking-wider text-emerald-400 flex items-center justify-between">
                <span>Total Debit (Receivables / Invoices)</span>
                <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
            </div>
            <div class="text-2xl font-black text-emerald-400 mt-1 font-mono">Rs. {{ number_format($totalDebit, 2) }}</div>
            <div class="text-[11px] text-slate-400 mt-1">Fabric Sales and outgoing debits</div>
        </div>

        <div class="glass-card rounded-2xl p-4 border border-rose-500/20 bg-rose-500/5">
            <div class="text-[11px] font-bold uppercase tracking-wider text-rose-400 flex items-center justify-between">
                <span>Total Credit (Payables / Receipts)</span>
                <span class="w-2 h-2 rounded-full bg-rose-400"></span>
            </div>
            <div class="text-2xl font-black text-rose-400 mt-1 font-mono">Rs. {{ number_format($totalCredit, 2) }}</div>
            <div class="text-[11px] text-slate-400 mt-1">Yarn purchases & sizing labor payables</div>
        </div>

        <div class="glass-card rounded-2xl p-4 border border-slate-800">
            <div class="text-[11px] font-bold uppercase tracking-wider text-slate-400 flex items-center justify-between">
                <span>Net Balance Standing</span>
                <span class="w-2 h-2 rounded-full bg-slate-400"></span>
            </div>
            <div class="text-2xl font-black {{ $netReceivableOrPayable >= 0 ? 'text-teal-400' : 'text-amber-400' }} mt-1 font-mono">
                Rs. {{ number_format(abs($netReceivableOrPayable), 2) }}
            </div>
            <div class="text-[11px] text-slate-400 mt-1">{{ $netReceivableOrPayable >= 0 ? 'Net Receivable' : 'Net Payable to Suppliers' }}</div>
        </div>

    </div>

    <!-- LEDGER TABLE WITH FILTER -->
    <div class="glass-card rounded-3xl p-6 border border-slate-800">
        
        <!-- Filter Bar -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-5 pb-4 border-b border-slate-800">
            <div>
                <h3 class="text-sm font-bold text-white">General & Party Ledgers</h3>
                <p class="text-xs text-slate-400">Transaction vouchers and statement balances</p>
            </div>

            <form method="GET" action="{{ route('ledgers.index') }}" class="flex items-center gap-2">
                <select name="party_name" onchange="this.form.submit()" class="bg-slate-950 border border-slate-700 rounded-xl px-3 py-1.5 text-xs text-white">
                    <option value="">All Parties</option>
                    @foreach($parties as $p)
                        <option value="{{ $p->party_name }}" {{ request('party_name') == $p->party_name ? 'selected' : '' }}>{{ $p->party_name }} ({{ str_replace('_', ' ', $p->party_type) }})</option>
                    @endforeach
                </select>

                <select name="party_type" onchange="this.form.submit()" class="bg-slate-950 border border-slate-700 rounded-xl px-3 py-1.5 text-xs text-white">
                    <option value="">All Types</option>
                    <option value="yarn_supplier" {{ request('party_type') == 'yarn_supplier' ? 'selected' : '' }}>Yarn Supplier</option>
                    <option value="sizing_mill" {{ request('party_type') == 'sizing_mill' ? 'selected' : '' }}>Sizing Mill</option>
                    <option value="fabric_buyer" {{ request('party_type') == 'fabric_buyer' ? 'selected' : '' }}>Fabric Buyer</option>
                </select>

                @if(request('party_name') || request('party_type'))
                    <a href="{{ route('ledgers.index') }}" class="px-2.5 py-1.5 rounded-xl bg-slate-800 text-slate-400 hover:text-white text-xs">Clear</a>
                @endif
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="border-b border-slate-800 text-slate-400 font-semibold uppercase text-[10px] tracking-wider bg-slate-950/40">
                        <th class="p-3">Date</th>
                        <th class="p-3">Voucher #</th>
                        <th class="p-3">Party Name</th>
                        <th class="p-3">Type</th>
                        <th class="p-3">Description</th>
                        <th class="p-3 text-right text-emerald-400">Debit (Rs.)</th>
                        <th class="p-3 text-right text-rose-400">Credit (Rs.)</th>
                        <th class="p-3 text-right text-cyan-400 font-black">Balance (Rs.)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60">
                    @forelse($ledgers as $l)
                        <tr class="hover:bg-slate-800/30 transition-colors">
                            <td class="p-3 font-mono text-slate-400">{{ $l->transaction_date->format('d M, Y') }}</td>
                            <td class="p-3 font-mono font-bold text-white">{{ $l->voucher_no ?? '-' }}</td>
                            <td class="p-3 font-bold text-slate-200">{{ $l->party_name }}</td>
                            <td class="p-3">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase bg-slate-800 text-slate-300">
                                    {{ str_replace('_', ' ', $l->party_type) }}
                                </span>
                            </td>
                            <td class="p-3 text-slate-300">{{ $l->description }}</td>
                            <td class="p-3 text-right font-mono font-bold text-emerald-400">
                                {{ $l->debit > 0 ? number_format($l->debit, 2) : '-' }}
                            </td>
                            <td class="p-3 text-right font-mono font-bold text-rose-400">
                                {{ $l->credit > 0 ? number_format($l->credit, 2) : '-' }}
                            </td>
                            <td class="p-3 text-right font-mono font-black {{ $l->balance >= 0 ? 'text-teal-400' : 'text-amber-400' }}">
                                {{ number_format($l->balance, 2) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="p-6 text-center text-slate-500">No ledger entries match your filter.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $ledgers->links() }}
        </div>

    </div>

</div>
@endsection
