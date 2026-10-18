@extends('layouts.app')

@section('title', 'Fabric Stock Management')

@section('content')
<div class="space-y-6">

    <!-- Header & Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-2 border-b border-zinc-200">
        <div>
            <div class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-zinc-700"></span>
                <span class="text-xs font-bold uppercase tracking-wider text-zinc-500">Module 04</span>
            </div>
            <h1 class="text-2xl font-black text-zinc-900 tracking-tight">Fabric Stock Store</h1>
            <p class="text-xs text-zinc-400 mt-0.5">Finished woven cloth inventory: open market spot sale stock vs committed contract stock.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('sales.index') }}" class="px-4 py-2 rounded-xl bg-zinc-900 hover:bg-zinc-700 text-zinc-50 font-extrabold text-xs shadow-sm transition-all flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                <span>Sell Fabric (Open / Contract)</span>
            </a>
            <button type="button" onclick="openModal('modalAddFabric')" class="px-3.5 py-2 rounded-xl bg-zinc-100 hover:bg-zinc-200 text-zinc-700 border border-zinc-300 text-xs font-bold transition-colors flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span>+ Add Quality Spec</span>
            </button>
        </div>
    </div>

    <!-- KPIs -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="glass-card rounded-2xl p-4 border border-zinc-300 bg-zinc-50">
            <div class="text-[11px] font-bold uppercase tracking-wider text-zinc-700 flex items-center justify-between">
                <span>Available Open Market Stock</span><span class="w-2 h-2 rounded-full bg-zinc-600"></span>
            </div>
            <div class="text-2xl font-black text-zinc-900 mt-1">{{ number_format($totalMetersAvailableOpen) }} <span class="text-xs font-normal text-zinc-400">meters</span></div>
            <div class="text-[11px] text-zinc-500 mt-1">Ready for open / walk-in sales</div>
        </div>
        <div class="glass-card rounded-2xl p-4 border border-zinc-300 bg-zinc-50">
            <div class="text-[11px] font-bold uppercase tracking-wider text-zinc-700 flex items-center justify-between">
                <span>Committed to Contracts</span><span class="w-2 h-2 rounded-full bg-zinc-600"></span>
            </div>
            <div class="text-2xl font-black text-zinc-900 mt-1">{{ number_format($totalMetersCommitted) }} <span class="text-xs font-normal text-zinc-400">meters</span></div>
            <div class="text-[11px] text-zinc-500 mt-1">Reserved for ongoing buyer orders</div>
        </div>
        <div class="glass-card rounded-2xl p-4 border border-zinc-200">
            <div class="text-[11px] font-bold uppercase tracking-wider text-zinc-500 flex items-center justify-between">
                <span>Total Fabric In Store</span><span class="w-2 h-2 rounded-full bg-zinc-400"></span>
            </div>
            <div class="text-2xl font-black text-zinc-900 mt-1">{{ number_format($totalMetersInStock) }} <span class="text-xs font-normal text-zinc-400">meters</span></div>
            <div class="text-[11px] text-zinc-400 mt-1 font-mono">{{ number_format($totalRollsInStock) }} total rolls / thaans</div>
        </div>
        <div class="glass-card rounded-2xl p-4 border border-zinc-300 bg-zinc-50">
            <div class="text-[11px] font-bold uppercase tracking-wider text-zinc-700 flex items-center justify-between">
                <span>Inventory Valuation</span><span class="w-2 h-2 rounded-full bg-zinc-600"></span>
            </div>
            <div class="text-2xl font-black text-zinc-900 mt-1 font-mono">Rs. {{ number_format($totalInventoryValue, 0) }}</div>
            <div class="text-[11px] text-zinc-500 mt-1">Estimated stock market value</div>
        </div>
    </div>

    <!-- FABRIC TABLE -->
    <div class="glass-card rounded-3xl p-6 border border-zinc-200">
        <div class="flex items-center justify-between mb-5">
            <div>
                <h3 class="text-sm font-bold text-zinc-900 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-zinc-600"></span>
                    Fabric Qualities & Inventory Balances
                </h3>
                <p class="text-xs text-zinc-400">Split between Open Sale Stock and Contract Commitment</p>
            </div>
            <div class="text-xs text-zinc-400 font-mono">Qualities: {{ $fabricStocks->total() }}</div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="border-b border-zinc-200 text-zinc-500 font-semibold uppercase text-[10px] tracking-wider bg-zinc-50">
                        <th class="p-3">Quality / Variety Name</th>
                        <th class="p-3">Construction Details</th>
                        <th class="p-3 text-center">Width</th>
                        <th class="p-3 text-right">Lifetime Woven</th>
                        <th class="p-3 text-center">Rolls / Thaans</th>
                        <th class="p-3 text-right bg-zinc-100 text-zinc-700 border-x border-zinc-200">Open Sale Ready</th>
                        <th class="p-3 text-right bg-zinc-100 text-zinc-700 border-r border-zinc-200">Committed to Contract</th>
                        <th class="p-3 text-right font-black text-zinc-900">Current Stock</th>
                        <th class="p-3 text-right">Cost / Rate</th>
                        <th class="p-3 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-100">
                    @forelse($fabricStocks as $fab)
                        <tr class="hover:bg-zinc-50 transition-colors">
                            <td class="p-3 font-bold text-zinc-900 text-sm">{{ $fab->quality_name }}</td>
                            <td class="p-3 font-mono text-zinc-600">{{ $fab->construction ?? '-' }}</td>
                            <td class="p-3 text-center font-mono text-zinc-500">{{ $fab->width_inches }}"</td>
                            <td class="p-3 text-right font-mono text-zinc-500">{{ number_format($fab->total_meters_produced, 0) }}m</td>
                            <td class="p-3 text-center font-mono font-bold text-zinc-900">{{ $fab->total_rolls_in_stock }}</td>
                            <td class="p-3 text-right font-mono font-black text-zinc-900 bg-zinc-50 border-x border-zinc-200 text-sm">{{ number_format($fab->available_open_meters, 0) }}m</td>
                            <td class="p-3 text-right font-mono font-bold text-zinc-700 bg-zinc-50 border-r border-zinc-200">{{ number_format($fab->committed_contract_meters, 0) }}m</td>
                            <td class="p-3 text-right font-mono font-black text-zinc-900 text-sm">{{ number_format($fab->current_stock_meters, 0) }}m</td>
                            <td class="p-3 text-right font-mono text-zinc-600">Rs. {{ number_format($fab->unit_cost_meter, 2) }}</td>
                            <td class="p-3 text-right">
                                <a href="{{ route('sales.index') }}" class="px-3 py-1 rounded-xl bg-zinc-900 hover:bg-zinc-700 text-zinc-50 text-[11px] font-bold transition-all inline-block">
                                    Sell Now &rarr;
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="10" class="p-6 text-center text-zinc-400">No fabric qualities recorded yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $fabricStocks->links() }}</div>
    </div>

    <!-- ACTIVE CONTRACTS BOX -->
    <div class="glass-card rounded-3xl p-5 border border-zinc-200">
        <div class="flex items-center justify-between mb-3">
            <h3 class="text-sm font-bold text-zinc-900 flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-zinc-600"></span>
                Active Party Contracts Reserving Fabric Stock
            </h3>
            <a href="{{ route('sales.index') }}" class="text-xs text-zinc-500 hover:text-zinc-900 hover:underline">View Sales & Contracts &rarr;</a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            @forelse($activeContracts as $cnt)
                <div class="p-3 rounded-2xl bg-zinc-50 border border-zinc-200 flex items-center justify-between text-xs">
                    <div>
                        <div class="font-bold text-zinc-900 font-mono">{{ $cnt->contract_no }} &bull; <span class="text-zinc-600 font-sans">{{ $cnt->buyer_name }}</span></div>
                        <div class="text-[11px] text-zinc-600 mt-0.5">{{ $cnt->fabric_quality }}</div>
                        <div class="text-[10px] text-zinc-400 mt-1">Delivered: {{ number_format($cnt->delivered_meters) }}m / Total: {{ number_format($cnt->total_meters) }}m</div>
                    </div>
                    <div class="text-right">
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-mono font-bold bg-zinc-200 text-zinc-800 border border-zinc-300">
                            {{ number_format($cnt->remaining_meters) }}m remaining
                        </span>
                        <span class="block text-[10px] text-zinc-400 mt-1">Rate: Rs. {{ $cnt->rate_per_meter }}/m</span>
                    </div>
                </div>
            @empty
                <p class="text-xs text-zinc-400">No active contracts currently reserving stock.</p>
            @endforelse
        </div>
    </div>

</div>

<!-- MODAL: ADD NEW FABRIC QUALITY -->
<div id="modalAddFabric" class="fixed inset-0 z-50 hidden bg-zinc-900/50 backdrop-blur-sm flex items-center justify-center p-4 overflow-y-auto">
    <div class="bg-white border border-zinc-200 rounded-3xl max-w-lg w-full p-6 shadow-xl relative my-8">
        <div class="flex items-center justify-between pb-3 border-b border-zinc-100">
            <h3 class="text-lg font-bold text-zinc-900 flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-zinc-700"></span>
                Add New Fabric Quality Specification
            </h3>
            <button type="button" onclick="closeModal('modalAddFabric')" class="text-zinc-400 hover:text-zinc-900 p-1">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form action="{{ route('fabric.store') }}" method="POST" class="mt-4 space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-zinc-600 mb-1">Fabric Quality / Commercial Name *</label>
                <input type="text" name="quality_name" required placeholder="e.g. Cotton Sheeting 68x68 / 30x30" class="w-full bg-zinc-50 border border-zinc-300 rounded-xl px-3 py-2 text-sm text-zinc-900 focus:outline-none focus:ring-2 focus:ring-zinc-500">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-zinc-600 mb-1">Construction Details *</label>
                    <input type="text" name="construction" required placeholder="e.g. 30x30 / 68x68 63&quot; Grey" class="w-full bg-zinc-50 border border-zinc-300 rounded-xl px-3 py-2 text-sm text-zinc-900 focus:outline-none focus:ring-2 focus:ring-zinc-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-zinc-600 mb-1">Width (Inches) *</label>
                    <input type="number" step="0.5" name="width_inches" required value="63.0" class="w-full bg-zinc-50 border border-zinc-300 rounded-xl px-3 py-2 text-sm text-zinc-900 font-mono focus:outline-none focus:ring-2 focus:ring-zinc-500">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-zinc-600 mb-1">Current Stock (Meters) *</label>
                    <input type="number" step="0.01" name="current_stock_meters" required min="0" placeholder="5000" class="w-full bg-zinc-50 border border-zinc-300 rounded-xl px-3 py-2 text-sm text-zinc-900 font-mono font-bold focus:outline-none focus:ring-2 focus:ring-zinc-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-zinc-600 mb-1">Total Rolls / Thaans *</label>
                    <input type="number" name="total_rolls_in_stock" required min="0" placeholder="50" class="w-full bg-zinc-50 border border-zinc-300 rounded-xl px-3 py-2 text-sm text-zinc-900 font-mono focus:outline-none focus:ring-2 focus:ring-zinc-500">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-zinc-600 mb-1">Committed to Contracts (Meters) *</label>
                    <input type="number" step="0.01" name="committed_contract_meters" required value="0" min="0" class="w-full bg-zinc-50 border border-zinc-300 rounded-xl px-3 py-2 text-sm text-zinc-900 font-mono font-bold focus:outline-none focus:ring-2 focus:ring-zinc-500">
                    <span class="text-[10px] text-zinc-400">Remaining will be open sale ready</span>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-zinc-600 mb-1">Unit Cost / Price Per Meter (Rs.) *</label>
                    <input type="number" step="0.01" name="unit_cost_meter" required min="0" placeholder="195" class="w-full bg-zinc-50 border border-zinc-300 rounded-xl px-3 py-2 text-sm text-zinc-900 font-mono focus:outline-none focus:ring-2 focus:ring-zinc-500">
                </div>
            </div>
            <div>
                <label class="block text-xs font-semibold text-zinc-600 mb-1">Notes / Weave Type</label>
                <input type="text" name="notes" placeholder="e.g. Plain weave, twill, dobby design" class="w-full bg-zinc-50 border border-zinc-300 rounded-xl px-3 py-2 text-sm text-zinc-900 focus:outline-none focus:ring-2 focus:ring-zinc-500">
            </div>
            <div class="pt-3 border-t border-zinc-100 flex justify-end gap-3">
                <button type="button" onclick="closeModal('modalAddFabric')" class="px-4 py-2 rounded-xl bg-zinc-100 text-zinc-600 text-xs font-semibold">Cancel</button>
                <button type="submit" class="px-5 py-2 rounded-xl bg-zinc-900 hover:bg-zinc-700 text-zinc-50 text-xs font-black shadow-sm">Save Fabric Quality</button>
            </div>
        </form>
    </div>
</div>
@endsection
