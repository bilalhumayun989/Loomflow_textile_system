@extends('layouts.app')

@section('title', 'Bags (Yarn) Management & Stock')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-[250px_minmax(0,1fr)] gap-6 items-start">
    <aside class="rounded-3xl border border-zinc-200 bg-white p-4 shadow-sm lg:sticky lg:top-24">
        <a href="{{ route('bags.index') }}" class="mb-4 flex items-center gap-3 rounded-2xl {{ $page === 'dashboard' ? 'bg-zinc-900 text-white' : 'bg-zinc-100 text-zinc-800 hover:bg-zinc-200' }} px-4 py-3 transition-colors">
            <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-zinc-700">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
            </span>
            <span>
                <span class="block text-sm font-extrabold">Bags Dashboard</span>
            </span>
        </a>

        <nav class="space-y-2" aria-label="Bags sections">
            <a href="{{ route('bags.purchases') }}" class="block w-full rounded-2xl {{ $page === 'purchases' ? 'bg-zinc-100 text-zinc-800' : 'text-zinc-600 hover:bg-zinc-100' }} px-4 py-3 text-xs font-bold transition-colors">
                Bags Purchases
            </a>
            <a href="{{ route('bags.ledger') }}" class="block w-full rounded-2xl {{ $page === 'ledger' ? 'bg-zinc-100 text-zinc-800' : 'text-zinc-600 hover:bg-zinc-100' }} px-4 py-3 text-xs font-bold transition-colors">
                Daily Stock Issuance
            </a>
            <a href="{{ route('bags.sizing-usage') }}" class="block w-full rounded-2xl {{ $page === 'sizing' ? 'bg-zinc-100 text-zinc-800' : 'text-zinc-600 hover:bg-zinc-100' }} px-4 py-3 text-xs font-bold transition-colors">
                Sizing Bag Consumption
            </a>
        </nav>
    </aside>

    <div class="min-w-0 space-y-6">

    <!-- Header & Action Buttons -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-2 border-b border-zinc-200">
        <div>
            <div class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-zinc-700"></span>
                <span class="text-xs font-bold uppercase tracking-wider text-zinc-500">Module 01</span>
            </div>
            <h1 class="text-2xl font-black text-zinc-900 tracking-tight">{{ $page === 'dashboard' ? 'Bags Dashboard' : ['purchases' => 'Bags Purchases', 'ledger' => 'Daily Stock Issuance', 'sizing' => 'Sizing Bag Consumption'][$page] }}</h1>
        </div>

        <div class="flex flex-wrap items-center gap-2.5">
            <button type="button" onclick="openModal('modalNewPurchase')" class="px-4 py-2 rounded-xl bg-zinc-900 hover:bg-zinc-700 text-zinc-50 font-extrabold text-xs shadow-sm transition-all flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span>+ New Bag Purchase (Split)</span>
            </button>
            <button type="button" onclick="openModal('modalIssueStock')" class="px-3.5 py-2 rounded-xl bg-zinc-100 hover:bg-zinc-200 text-zinc-700 border border-zinc-300 text-xs font-bold transition-colors flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                <span>Issue Bags Daily</span>
            </button>
            <button type="button" onclick="openModal('modalReturnStock')" class="px-3.5 py-2 rounded-xl bg-zinc-100 hover:bg-zinc-200 text-zinc-700 border border-zinc-300 text-xs font-bold transition-colors flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18"/></svg>
                <span>Receive Bags Return</span>
            </button>
        </div>
    </div>

    <!-- KPI Metric Cards -->
    @if ($page === 'dashboard')
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
        <div class="glass-card rounded-2xl p-4 border border-zinc-200">
            <div class="text-[11px] font-bold uppercase tracking-wider text-zinc-500 flex items-center justify-between">
                <span>Total Bags Purchased</span>
                <span class="w-2 h-2 rounded-full bg-zinc-400"></span>
            </div>
            <div class="text-2xl font-black text-zinc-900 mt-1">{{ number_format($totalPurchased) }} <span class="text-xs font-normal text-zinc-400">bags</span></div>
            <div class="text-[11px] text-zinc-400 mt-1 font-mono">Rs. {{ number_format($totalYarnInvestment, 0) }}</div>
        </div>

        <div class="glass-card rounded-2xl p-4 border border-zinc-300 bg-zinc-50">
            <div class="text-[11px] font-bold uppercase tracking-wider text-zinc-700 flex items-center justify-between">
                <span>Factory Stock Store</span>
                <span class="w-2 h-2 rounded-full bg-zinc-700"></span>
            </div>
            <div class="text-2xl font-black text-zinc-900 mt-1">{{ number_format($factoryStoreStock) }} <span class="text-xs font-normal text-zinc-400">bags</span></div>
            <div class="text-[11px] text-zinc-500 mt-1">Ready in Mill Godown</div>
        </div>

        <div class="glass-card rounded-2xl p-4 border border-zinc-300 bg-zinc-50">
            <div class="text-[11px] font-bold uppercase tracking-wider text-zinc-700 flex items-center justify-between">
                <span>With Sizing Persons</span>
                <span class="w-2 h-2 rounded-full bg-zinc-600"></span>
            </div>
            <div class="text-2xl font-black text-zinc-900 mt-1">{{ number_format($sizingStockRemaining) }} <span class="text-xs font-normal text-zinc-400">bags</span></div>
            <div class="text-[11px] text-zinc-500 mt-1">Allocated to Sizing Mills</div>
        </div>

        <div class="glass-card rounded-2xl p-4 border border-zinc-300 bg-zinc-50">
            <div class="text-[11px] font-bold uppercase tracking-wider text-zinc-700 flex items-center justify-between">
                <span>Bags Used In Beams</span>
                <span class="w-2 h-2 rounded-full bg-zinc-600"></span>
            </div>
            <div class="text-2xl font-black text-zinc-900 mt-1">{{ number_format($totalBagsUsedInBeams) }} <span class="text-xs font-normal text-zinc-400">bags</span></div>
            <div class="text-[11px] text-zinc-500 mt-1">Converted to Sizing Beams</div>
        </div>

        <div class="glass-card rounded-2xl p-4 border border-zinc-300 bg-zinc-50">
            <div class="text-[11px] font-bold uppercase tracking-wider text-zinc-700 flex items-center justify-between">
                <span>Unused Bags Status</span>
                <span class="w-2 h-2 rounded-full bg-zinc-500"></span>
            </div>
            <div class="text-xs font-bold text-zinc-800 mt-1">
                <div class="flex justify-between py-0.5"><span class="text-zinc-500">Returned:</span> <span class="font-mono">{{ $unusedBagsReturned }} bags</span></div>
                <div class="flex justify-between py-0.5"><span class="text-zinc-500">Held in Sizing:</span> <span class="font-mono">{{ $unusedBagsHeldWithSizing }} bags</span></div>
            </div>
        </div>
    </div>
    @endif

    <!-- Bags section content -->
    <div class="glass-card rounded-3xl p-6 border border-zinc-200">
        <div class="flex items-center justify-between mb-5">
            <h2 class="text-lg font-extrabold text-zinc-900">{{ $page === 'dashboard' ? 'Recent Bags Activity' : ['purchases' => 'Bags Purchases & Split History', 'ledger' => 'Daily Stock Issuance & Ledger Log', 'sizing' => 'Sizing Bag Consumption & Returns'][$page] }}</h2>
            <div class="text-xs text-zinc-400 font-mono">{{ $page === 'dashboard' ? 'Latest records' : 'Complete history' }}</div>
        </div>

        <!-- TAB 1: PURCHASES & SPLIT -->
        @if (in_array($page, ['dashboard', 'purchases'], true))
        <section class="mb-8">
            @if ($page === 'dashboard')
                <h3 class="mb-3 text-sm font-bold text-zinc-700">Recent Purchases</h3>
            @endif
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-zinc-200 text-zinc-500 font-semibold uppercase text-[10px] tracking-wider bg-zinc-50">
                            <th class="p-3">Challan #</th>
                            <th class="p-3">Purchase Date</th>
                            <th class="p-3">Spinning Supplier</th>
                            <th class="p-3">Quality / Count</th>
                            <th class="p-3 text-right">Total Bags</th>
                            <th class="p-3 text-center bg-zinc-100 text-zinc-600 border-x border-zinc-200">To Sizing</th>
                            <th class="p-3 text-center bg-zinc-100 text-zinc-600 border-r border-zinc-200">To Factory Store</th>
                            <th class="p-3 text-right">Rate / Bag</th>
                            <th class="p-3 text-right">Total Amount</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-100">
                        @forelse($purchases as $p)
                            <tr class="hover:bg-zinc-50 transition-colors">
                                <td class="p-3 font-mono font-bold text-zinc-900">{{ $p->challan_no }}</td>
                                <td class="p-3 text-zinc-500">{{ $p->purchase_date->format('d M, Y') }}</td>
                                <td class="p-3 font-semibold text-zinc-800">{{ $p->supplier_name }}</td>
                                <td class="p-3"><span class="px-2 py-0.5 rounded-md bg-zinc-100 text-zinc-700 font-mono text-[11px]">{{ $p->quality }}</span></td>
                                <td class="p-3 text-right font-black text-zinc-900">{{ $p->total_bags }}</td>
                                <td class="p-3 text-center font-bold font-mono text-zinc-800 bg-zinc-50 border-x border-zinc-200">{{ $p->bags_to_sizing }}</td>
                                <td class="p-3 text-center font-bold font-mono text-zinc-800 bg-zinc-50 border-r border-zinc-200">{{ $p->bags_to_factory }}</td>
                                <td class="p-3 text-right font-mono text-zinc-500">Rs. {{ number_format($p->rate_per_bag, 2) }}</td>
                                <td class="p-3 text-right font-mono font-bold text-zinc-900">Rs. {{ number_format($p->total_amount, 2) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="9" class="p-6 text-center text-zinc-400">No bag purchases entered yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($page === 'purchases')
                <div class="mt-4">{{ $purchases->links() }}</div>
            @endif
        </section>
        @endif

        <!-- TAB 2: DAILY STOCK ISSUANCE & LEDGER -->
        @if (in_array($page, ['dashboard', 'ledger'], true))
        <section class="mb-8">
            @if ($page === 'dashboard')
                <h3 class="mb-3 text-sm font-bold text-zinc-700">Recent Stock Movements</h3>
            @endif
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-zinc-200 text-zinc-500 font-semibold uppercase text-[10px] tracking-wider bg-zinc-50">
                            <th class="p-3">Date</th><th class="p-3">Type</th><th class="p-3">Ref #</th>
                            <th class="p-3">Quality</th><th class="p-3 text-right">Quantity</th>
                            <th class="p-3">Source</th><th class="p-3">Destination</th>
                            <th class="p-3">Party / Mill</th><th class="p-3">Notes</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-100">
                        @forelse($transactions as $tx)
                            <tr class="hover:bg-zinc-50 transition-colors">
                                <td class="p-3 text-zinc-500 font-mono">{{ $tx->transaction_date->format('d M, Y') }}</td>
                                <td class="p-3">
                                    @if($tx->transaction_type === 'purchase_factory')
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-zinc-200 text-zinc-700 border border-zinc-300">Factory Purchase</span>
                                    @elseif($tx->transaction_type === 'purchase_sizing')
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-zinc-200 text-zinc-700 border border-zinc-300">Direct Sizing</span>
                                    @elseif($tx->transaction_type === 'issue_to_sizing')
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-zinc-300 text-zinc-800 border border-zinc-400">Issued To Sizing</span>
                                    @elseif($tx->transaction_type === 'return_from_sizing')
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-zinc-100 text-zinc-600 border border-zinc-200">Returned to Factory</span>
                                    @else
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-zinc-100 text-zinc-500">{{ $tx->transaction_type }}</span>
                                    @endif
                                </td>
                                <td class="p-3 font-mono font-semibold text-zinc-900">{{ $tx->reference_no ?? '-' }}</td>
                                <td class="p-3 font-medium text-zinc-700">{{ $tx->quality }}</td>
                                <td class="p-3 text-right font-mono font-bold text-zinc-900">{{ $tx->quantity_bags }} Bags</td>
                                <td class="p-3 text-zinc-500">{{ $tx->source }}</td>
                                <td class="p-3 text-zinc-700 font-semibold">{{ $tx->destination }}</td>
                                <td class="p-3 text-zinc-600">{{ $tx->party_name ?? '-' }}</td>
                                <td class="p-3 text-zinc-400 italic">{{ $tx->notes ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="9" class="p-6 text-center text-zinc-400">No stock transactions logged yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($page === 'ledger')
                <div class="mt-4">{{ $transactions->links() }}</div>
            @endif
        </section>
        @endif

        <!-- TAB 3: SIZING BAG CONSUMPTION -->
        @if (in_array($page, ['dashboard', 'sizing'], true))
        <section>
            @if ($page === 'dashboard')
                <h3 class="mb-3 text-sm font-bold text-zinc-700">Recent Sizing Usage & Returns</h3>
            @endif
            <div class="mb-4 p-4 rounded-2xl bg-zinc-50 border border-zinc-200 text-zinc-700 text-xs flex items-center justify-between">
                <div><strong>Sizing Bags Reconciliation:</strong> See how many bags were given, used in beams, and where unused bags are.</div>
                <a href="{{ route('sizing.index') }}" class="px-3 py-1.5 rounded-xl bg-zinc-900 hover:bg-zinc-700 text-zinc-50 font-bold text-xs transition-colors shrink-0">Open Sizing Module &rarr;</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-zinc-200 text-zinc-500 font-semibold uppercase text-[10px] tracking-wider bg-zinc-50">
                            <th class="p-3">Sizing Slip #</th><th class="p-3">Sizing Party</th><th class="p-3">Quality</th>
                            <th class="p-3 text-center">Bags Allocated</th>
                            <th class="p-3 text-center bg-zinc-100 text-zinc-600">Used in Beams</th>
                            <th class="p-3 text-center bg-zinc-100 text-zinc-600">Returned to Factory</th>
                            <th class="p-3 text-center bg-zinc-100 text-zinc-600">Held With Sizing</th>
                            <th class="p-3 text-center">Beams Made</th>
                            <th class="p-3">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-100">
                        @forelse($sizingSlips as $slip)
                            <tr class="hover:bg-zinc-50 transition-colors">
                                <td class="p-3 font-mono font-bold text-zinc-900">{{ $slip->slip_no }}</td>
                                <td class="p-3 font-semibold text-zinc-800">{{ $slip->sizing_party }}</td>
                                <td class="p-3 text-zinc-600">{{ $slip->yarn_quality }}</td>
                                <td class="p-3 text-center font-bold text-zinc-900 font-mono">{{ $slip->bags_allocated }}</td>
                                <td class="p-3 text-center font-bold text-zinc-800 bg-zinc-50 font-mono">{{ $slip->bags_used }}</td>
                                <td class="p-3 text-center font-bold text-zinc-700 bg-zinc-50 font-mono">{{ $slip->bags_returned_to_factory }}</td>
                                <td class="p-3 text-center font-bold text-zinc-700 bg-zinc-50 font-mono">{{ $slip->bags_held_with_sizing }}</td>
                                <td class="p-3 text-center font-bold text-zinc-700">{{ $slip->total_beams }} Beams ({{ number_format($slip->total_length_meters) }}m)</td>
                                <td class="p-3">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $slip->status === 'completed' ? 'bg-zinc-200 text-zinc-700' : 'bg-zinc-100 text-zinc-500' }}">
                                        {{ $slip->status }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="9" class="p-6 text-center text-zinc-400">No sizing slips found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($page === 'sizing')
                <div class="mt-4">{{ $sizingSlips->links() }}</div>
            @endif
        </section>
        @endif
    </div>

    </div>
</div>

<!-- MODAL 1: NEW BAG PURCHASE -->
<div id="modalNewPurchase" class="fixed inset-0 z-50 hidden bg-zinc-900/50 backdrop-blur-sm flex items-center justify-center p-4 overflow-y-auto">
    <div class="bg-white border border-zinc-200 rounded-3xl max-w-xl w-full p-6 shadow-xl relative my-8">
        <div class="flex items-center justify-between pb-3 border-b border-zinc-100">
            <div>
                <h3 class="text-lg font-bold text-zinc-900 flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-zinc-700"></span>
                    New Bag Purchase (Yarn)
                </h3>
                <p class="text-xs text-zinc-400">Enter purchase & split between Sizing and Factory Stock Store</p>
            </div>
            <button type="button" onclick="closeModal('modalNewPurchase')" class="text-zinc-400 hover:text-zinc-900 p-1 rounded-lg">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form action="{{ route('bags.purchase') }}" method="POST" class="mt-4 space-y-4">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-zinc-600 mb-1">Challan / Invoice # *</label>
                    <input type="text" name="challan_no" required placeholder="e.g. CH-8901" value="{{ old('challan_no') }}" class="w-full bg-zinc-50 border border-zinc-300 rounded-xl px-3 py-2 text-sm text-zinc-900 focus:outline-none focus:ring-2 focus:ring-zinc-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-zinc-600 mb-1">Purchase Date *</label>
                    <input type="date" name="purchase_date" required value="{{ date('Y-m-d') }}" class="w-full bg-zinc-50 border border-zinc-300 rounded-xl px-3 py-2 text-sm text-zinc-900 focus:outline-none focus:ring-2 focus:ring-zinc-500">
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-zinc-600 mb-1">Spinning Mill / Supplier *</label>
                    <input type="text" name="supplier_name" required placeholder="e.g. Al-Karam Spinning" value="{{ old('supplier_name') }}" class="w-full bg-zinc-50 border border-zinc-300 rounded-xl px-3 py-2 text-sm text-zinc-900 focus:outline-none focus:ring-2 focus:ring-zinc-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-zinc-600 mb-1">Yarn Quality / Count *</label>
                    <input type="text" name="quality" required placeholder="e.g. 30/1 PC, 40/1 Combed" value="{{ old('quality') }}" class="w-full bg-zinc-50 border border-zinc-300 rounded-xl px-3 py-2 text-sm text-zinc-900 focus:outline-none focus:ring-2 focus:ring-zinc-500">
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-zinc-600 mb-1">Total Bags Purchased *</label>
                    <input type="number" id="inpTotalBags" name="total_bags" required min="1" placeholder="100" oninput="calculateSplit()" class="w-full bg-zinc-50 border border-zinc-300 rounded-xl px-3 py-2 text-sm text-zinc-900 font-mono font-bold focus:outline-none focus:ring-2 focus:ring-zinc-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-zinc-600 mb-1">Rate Per Bag (Rs.) *</label>
                    <input type="number" step="0.01" id="inpRatePerBag" name="rate_per_bag" required min="0" placeholder="14500" oninput="calculateSplit()" class="w-full bg-zinc-50 border border-zinc-300 rounded-xl px-3 py-2 text-sm text-zinc-900 font-mono focus:outline-none focus:ring-2 focus:ring-zinc-500">
                </div>
            </div>
            <div class="p-4 rounded-2xl bg-zinc-50 border border-zinc-200 space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-zinc-600">📦 Dispatch Split: Sizing vs Factory</span>
                    <span id="splitStatus" class="text-xs font-bold font-mono text-zinc-500">Total: 0 Bags</span>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[11px] font-semibold text-zinc-600 mb-1">Direct to Sizing (Bags)</label>
                        <input type="number" id="inpBagsToSizing" name="bags_to_sizing" value="0" min="0" oninput="validateSplitNumbers()" class="w-full bg-white border border-zinc-300 rounded-xl px-3 py-2 text-sm text-zinc-900 font-mono font-bold focus:outline-none focus:ring-2 focus:ring-zinc-500">
                        <span class="text-[10px] text-zinc-400">Directly sent to sizing mill</span>
                    </div>
                    <div>
                        <label class="block text-[11px] font-semibold text-zinc-600 mb-1">To Factory Godown (Bags)</label>
                        <input type="number" id="inpBagsToFactory" name="bags_to_factory" value="0" min="0" oninput="validateSplitNumbers()" class="w-full bg-white border border-zinc-300 rounded-xl px-3 py-2 text-sm text-zinc-900 font-mono font-bold focus:outline-none focus:ring-2 focus:ring-zinc-500">
                        <span class="text-[10px] text-zinc-400">Stored in mill warehouse</span>
                    </div>
                </div>
                <div class="pt-2 border-t border-zinc-200 flex items-center justify-between text-xs">
                    <span class="text-zinc-500">Calculated Total Amount:</span>
                    <strong id="displayTotalAmount" class="font-mono text-zinc-900 text-sm">Rs. 0.00</strong>
                </div>
            </div>
            <div>
                <label class="block text-xs font-semibold text-zinc-600 mb-1">Notes / Contract Details</label>
                <textarea name="notes" rows="2" placeholder="e.g. Contract #889, direct dispatch" class="w-full bg-zinc-50 border border-zinc-300 rounded-xl px-3 py-2 text-sm text-zinc-900 focus:outline-none focus:ring-2 focus:ring-zinc-500"></textarea>
            </div>
            <div class="pt-3 border-t border-zinc-100 flex justify-end gap-3">
                <button type="button" onclick="closeModal('modalNewPurchase')" class="px-4 py-2 rounded-xl bg-zinc-100 hover:bg-zinc-200 text-zinc-600 text-xs font-semibold">Cancel</button>
                <button type="submit" id="btnSubmitPurchase" class="px-5 py-2 rounded-xl bg-zinc-900 hover:bg-zinc-700 text-zinc-50 text-xs font-black shadow-sm">Save Purchase & Split</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL 2: ISSUE BAGS DAILY -->
<div id="modalIssueStock" class="fixed inset-0 z-50 hidden bg-zinc-900/50 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white border border-zinc-200 rounded-3xl max-w-md w-full p-6 shadow-xl relative">
        <div class="flex items-center justify-between pb-3 border-b border-zinc-100">
            <h3 class="text-lg font-bold text-zinc-900 flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-zinc-600"></span>
                Issue Stock Daily (From Factory)
            </h3>
            <button type="button" onclick="closeModal('modalIssueStock')" class="text-zinc-400 hover:text-zinc-900 p-1">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form action="{{ route('bags.issue') }}" method="POST" class="mt-4 space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-zinc-600 mb-1">Issuance Reference / Slip # *</label>
                <input type="text" name="reference_no" required placeholder="e.g. ISS-105" class="w-full bg-zinc-50 border border-zinc-300 rounded-xl px-3 py-2 text-sm text-zinc-900 focus:outline-none focus:ring-2 focus:ring-zinc-500">
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-zinc-600 mb-1">Date *</label>
                    <input type="date" name="transaction_date" required value="{{ date('Y-m-d') }}" class="w-full bg-zinc-50 border border-zinc-300 rounded-xl px-3 py-2 text-sm text-zinc-900 focus:outline-none focus:ring-2 focus:ring-zinc-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-zinc-600 mb-1">Quantity (Bags) *</label>
                    <input type="number" name="quantity_bags" required min="1" max="{{ $factoryStoreStock }}" placeholder="10" class="w-full bg-zinc-50 border border-zinc-300 rounded-xl px-3 py-2 text-sm text-zinc-900 font-mono font-bold focus:outline-none focus:ring-2 focus:ring-zinc-500">
                    <span class="text-[10px] text-zinc-500">In Godown: {{ $factoryStoreStock }} bags</span>
                </div>
            </div>
            <div>
                <label class="block text-xs font-semibold text-zinc-600 mb-1">Yarn Quality *</label>
                <input type="text" name="quality" required placeholder="e.g. 30/1 PC Combed" class="w-full bg-zinc-50 border border-zinc-300 rounded-xl px-3 py-2 text-sm text-zinc-900 focus:outline-none focus:ring-2 focus:ring-zinc-500">
            </div>
            <div>
                <label class="block text-xs font-semibold text-zinc-600 mb-1">Destination / Sizing Mill *</label>
                <input type="text" name="destination" required placeholder="e.g. National Sizing Mill" class="w-full bg-zinc-50 border border-zinc-300 rounded-xl px-3 py-2 text-sm text-zinc-900 focus:outline-none focus:ring-2 focus:ring-zinc-500">
            </div>
            <div>
                <label class="block text-xs font-semibold text-zinc-600 mb-1">Receiver Name / Contractor *</label>
                <input type="text" name="party_name" required placeholder="e.g. Master Aslam" class="w-full bg-zinc-50 border border-zinc-300 rounded-xl px-3 py-2 text-sm text-zinc-900 focus:outline-none focus:ring-2 focus:ring-zinc-500">
            </div>
            <div>
                <label class="block text-xs font-semibold text-zinc-600 mb-1">Remarks</label>
                <input type="text" name="notes" placeholder="e.g. Lot for beam set 4" class="w-full bg-zinc-50 border border-zinc-300 rounded-xl px-3 py-2 text-sm text-zinc-900 focus:outline-none focus:ring-2 focus:ring-zinc-500">
            </div>
            <div class="pt-3 border-t border-zinc-100 flex justify-end gap-3">
                <button type="button" onclick="closeModal('modalIssueStock')" class="px-4 py-2 rounded-xl bg-zinc-100 text-zinc-600 text-xs font-semibold">Cancel</button>
                <button type="submit" class="px-5 py-2 rounded-xl bg-zinc-900 hover:bg-zinc-700 text-zinc-50 text-xs font-black">Confirm Issuance</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL 3: RECEIVE RETURNED BAGS -->
<div id="modalReturnStock" class="fixed inset-0 z-50 hidden bg-zinc-900/50 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white border border-zinc-200 rounded-3xl max-w-md w-full p-6 shadow-xl relative">
        <div class="flex items-center justify-between pb-3 border-b border-zinc-100">
            <h3 class="text-lg font-bold text-zinc-900 flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-zinc-500"></span>
                Receive Unused Bags Back
            </h3>
            <button type="button" onclick="closeModal('modalReturnStock')" class="text-zinc-400 hover:text-zinc-900 p-1">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form action="{{ route('bags.return') }}" method="POST" class="mt-4 space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-zinc-600 mb-1">Return Slip / Ref # *</label>
                <input type="text" name="reference_no" required placeholder="e.g. RET-45" class="w-full bg-zinc-50 border border-zinc-300 rounded-xl px-3 py-2 text-sm text-zinc-900 focus:outline-none focus:ring-2 focus:ring-zinc-500">
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-zinc-600 mb-1">Date *</label>
                    <input type="date" name="transaction_date" required value="{{ date('Y-m-d') }}" class="w-full bg-zinc-50 border border-zinc-300 rounded-xl px-3 py-2 text-sm text-zinc-900 focus:outline-none focus:ring-2 focus:ring-zinc-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-zinc-600 mb-1">Quantity Returned (Bags) *</label>
                    <input type="number" name="quantity_bags" required min="1" placeholder="5" class="w-full bg-zinc-50 border border-zinc-300 rounded-xl px-3 py-2 text-sm text-zinc-900 font-mono font-bold focus:outline-none focus:ring-2 focus:ring-zinc-500">
                </div>
            </div>
            <div>
                <label class="block text-xs font-semibold text-zinc-600 mb-1">Yarn Quality *</label>
                <input type="text" name="quality" required placeholder="e.g. 30/1 PC Combed" class="w-full bg-zinc-50 border border-zinc-300 rounded-xl px-3 py-2 text-sm text-zinc-900 focus:outline-none focus:ring-2 focus:ring-zinc-500">
            </div>
            <div>
                <label class="block text-xs font-semibold text-zinc-600 mb-1">Returning Sizing Mill / Party *</label>
                <input type="text" name="source" required placeholder="e.g. National Sizing Mill" class="w-full bg-zinc-50 border border-zinc-300 rounded-xl px-3 py-2 text-sm text-zinc-900 focus:outline-none focus:ring-2 focus:ring-zinc-500">
            </div>
            <div>
                <label class="block text-xs font-semibold text-zinc-600 mb-1">Contractor / Person Name *</label>
                <input type="text" name="party_name" required placeholder="e.g. Sizing Contractor" class="w-full bg-zinc-50 border border-zinc-300 rounded-xl px-3 py-2 text-sm text-zinc-900 focus:outline-none focus:ring-2 focus:ring-zinc-500">
            </div>
            <div>
                <label class="block text-xs font-semibold text-zinc-600 mb-1">Reason / Notes</label>
                <input type="text" name="notes" placeholder="e.g. Surplus bags from completed beam set" class="w-full bg-zinc-50 border border-zinc-300 rounded-xl px-3 py-2 text-sm text-zinc-900 focus:outline-none focus:ring-2 focus:ring-zinc-500">
            </div>
            <div class="pt-3 border-t border-zinc-100 flex justify-end gap-3">
                <button type="button" onclick="closeModal('modalReturnStock')" class="px-4 py-2 rounded-xl bg-zinc-100 text-zinc-600 text-xs font-semibold">Cancel</button>
                <button type="submit" class="px-5 py-2 rounded-xl bg-zinc-900 hover:bg-zinc-700 text-zinc-50 text-xs font-black">Receive into Godown</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function calculateSplit() {
        const total = parseFloat(document.getElementById('inpTotalBags').value) || 0;
        const sizing = parseFloat(document.getElementById('inpBagsToSizing').value) || 0;
        const factory = parseFloat(document.getElementById('inpBagsToFactory').value) || 0;
        if (sizing === 0 && factory === 0 && total > 0) {
            const half = Math.floor(total / 2);
            document.getElementById('inpBagsToSizing').value = half;
            document.getElementById('inpBagsToFactory').value = total - half;
        }
        validateSplitNumbers();
    }

    function validateSplitNumbers() {
        const total = parseFloat(document.getElementById('inpTotalBags').value) || 0;
        const rate = parseFloat(document.getElementById('inpRatePerBag').value) || 0;
        const sizing = parseFloat(document.getElementById('inpBagsToSizing').value) || 0;
        const factory = parseFloat(document.getElementById('inpBagsToFactory').value) || 0;
        const sum = sizing + factory;
        const statusEl = document.getElementById('splitStatus');
        const submitBtn = document.getElementById('btnSubmitPurchase');
        document.getElementById('displayTotalAmount').innerText = 'Rs. ' + (total * rate).toLocaleString('en-US', {minimumFractionDigits: 2});
        if (sum === total && total > 0) {
            statusEl.innerText = `Matched: ${sizing} Sizing + ${factory} Factory = ${total} ✓`;
            statusEl.className = 'text-xs font-bold font-mono text-zinc-700';
            submitBtn.disabled = false;
            submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
        } else {
            statusEl.innerText = `Sum: ${sum} / ${total} Bags ✗`;
            statusEl.className = 'text-xs font-bold font-mono text-zinc-400';
            submitBtn.disabled = true;
            submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
        }
    }
</script>
@endpush
@endsection
