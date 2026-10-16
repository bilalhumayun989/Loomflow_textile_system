@extends('layouts.app')

@section('title', 'Loom Parts Store & Issuance')

@section('content')
<div class="space-y-6">

    <!-- Header & Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-2 border-b border-slate-800">
        <div>
            <div class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-purple-400"></span>
                <span class="text-xs font-bold uppercase tracking-wider text-purple-400">Module 03</span>
            </div>
            <h1 class="text-2xl font-black text-white tracking-tight">Loom Parts Store & Factory Issuance</h1>
            <p class="text-xs text-slate-400 mt-0.5">Manage grippers, rapier tapes, heald wires, sensors, and issue spare parts directly to factory looms.</p>
        </div>

        <div class="flex items-center gap-3">
            <button type="button" onclick="openModal('modalIssuePart')" class="px-4 py-2 rounded-xl bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-500 hover:to-indigo-500 text-white font-extrabold text-xs shadow-lg shadow-purple-600/25 transition-all flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span>Issue Part to Factory Loom</span>
            </button>
            <button type="button" onclick="openModal('modalAddPart')" class="px-3.5 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 text-xs font-bold transition-colors flex items-center gap-1.5">
                <svg class="w-4 h-4 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>+ Add New Part Item</span>
            </button>
        </div>
    </div>

    <!-- KPIs -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        
        <div class="glass-card rounded-2xl p-4 border border-purple-500/20 bg-purple-500/5">
            <div class="text-[11px] font-bold uppercase tracking-wider text-purple-400 flex items-center justify-between">
                <span>Total Part Categories</span>
                <span class="w-2 h-2 rounded-full bg-purple-400"></span>
            </div>
            <div class="text-2xl font-black text-white mt-1">{{ $totalItemsCount }} <span class="text-xs font-normal text-slate-400">cataloged</span></div>
            <div class="text-[11px] text-slate-500 mt-1 font-mono">{{ number_format($totalStockUnits) }} total units in stock</div>
        </div>

        <div class="glass-card rounded-2xl p-4 border {{ $lowStockParts->count() > 0 ? 'border-rose-500/30 bg-rose-500/10' : 'border-emerald-500/20 bg-emerald-500/5' }}">
            <div class="text-[11px] font-bold uppercase tracking-wider {{ $lowStockParts->count() > 0 ? 'text-rose-400' : 'text-emerald-400' }} flex items-center justify-between">
                <span>Low Stock Reorder Alert</span>
                <span class="w-2 h-2 rounded-full {{ $lowStockParts->count() > 0 ? 'bg-rose-400 animate-ping' : 'bg-emerald-400' }}"></span>
            </div>
            <div class="text-2xl font-black {{ $lowStockParts->count() > 0 ? 'text-rose-400' : 'text-emerald-400' }} mt-1">
                {{ $lowStockParts->count() }} <span class="text-xs font-normal text-slate-400">items below limit</span>
            </div>
            <div class="text-[11px] text-slate-400 mt-1">
                @if($lowStockParts->count() > 0)
                    Needs restock: {{ $lowStockParts->pluck('part_name')->take(2)->join(', ') }}
                @else
                    All parts healthy above minimum
                @endif
            </div>
        </div>

        <div class="glass-card rounded-2xl p-4 border border-slate-800">
            <div class="text-[11px] font-bold uppercase tracking-wider text-slate-400 flex items-center justify-between">
                <span>Store Valuation</span>
                <span class="w-2 h-2 rounded-full bg-slate-400"></span>
            </div>
            <div class="text-2xl font-black text-emerald-400 mt-1 font-mono">Rs. {{ number_format($totalInventoryValuation, 0) }}</div>
            <div class="text-[11px] text-slate-500 mt-1">Cost of parts in godown</div>
        </div>

        <div class="glass-card rounded-2xl p-4 border border-indigo-500/20 bg-indigo-500/5">
            <div class="text-[11px] font-bold uppercase tracking-wider text-indigo-400 flex items-center justify-between">
                <span>Total Parts Issued</span>
                <span class="w-2 h-2 rounded-full bg-indigo-400"></span>
            </div>
            <div class="text-2xl font-black text-indigo-400 mt-1">{{ $totalIssuancesCount }} <span class="text-xs font-normal text-slate-400">issuances</span></div>
            <div class="text-[11px] text-slate-400 mt-1 font-mono">Rs. {{ number_format($totalIssuanceCost, 0) }} maintenance cost</div>
        </div>

    </div>

    <!-- TABS: 1. PARTS INVENTORY & STORE | 2. ISSUANCE LOG TO FACTORY LOOMS -->
    <div class="glass-card rounded-3xl p-6 border border-slate-800">
        <div class="flex items-center justify-between mb-5">
            <div class="flex items-center gap-3">
                <button type="button" onclick="switchPartsTab('tabInventory')" id="btnTabInventory" class="px-4 py-2 rounded-xl text-xs font-bold bg-purple-500 text-white shadow-md">
                    1. Parts Store Inventory & Stock
                </button>
                <button type="button" onclick="switchPartsTab('tabIssuances')" id="btnTabIssuances" class="px-4 py-2 rounded-xl text-xs font-bold bg-slate-800 text-slate-300 hover:text-white">
                    2. Loom Issuance History Log
                </button>
            </div>
            <div class="text-xs text-slate-400 font-mono">
                Store Catalog: {{ $parts->total() }} Items
            </div>
        </div>

        <!-- TAB 1: INVENTORY TABLE -->
        <div id="tabInventory">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-slate-800 text-slate-400 font-semibold uppercase text-[10px] tracking-wider bg-slate-950/40">
                            <th class="p-3">Part Code</th>
                            <th class="p-3">Part Name</th>
                            <th class="p-3">Category</th>
                            <th class="p-3 text-right">In Stock</th>
                            <th class="p-3 text-right">Min Threshold</th>
                            <th class="p-3 text-right">Unit Price</th>
                            <th class="p-3 text-right">Total Value</th>
                            <th class="p-3">Location / Rack</th>
                            <th class="p-3 text-right">Quick Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/60">
                        @forelse($parts as $part)
                            <tr class="hover:bg-slate-800/30 transition-colors {{ $part->stock_quantity <= $part->min_alert_threshold ? 'bg-rose-950/10' : '' }}">
                                <td class="p-3 font-mono font-bold text-purple-400">{{ $part->part_code }}</td>
                                <td class="p-3 font-bold text-white">{{ $part->part_name }}</td>
                                <td class="p-3">
                                    <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-slate-800 text-slate-300">{{ $part->category }}</span>
                                </td>
                                <td class="p-3 text-right font-mono font-black {{ $part->stock_quantity <= $part->min_alert_threshold ? 'text-rose-400' : 'text-emerald-400' }}">
                                    {{ number_format($part->stock_quantity) }} {{ $part->unit }}
                                    @if($part->stock_quantity <= $part->min_alert_threshold)
                                        <span class="block text-[9px] uppercase font-bold text-rose-500">Low Stock!</span>
                                    @endif
                                </td>
                                <td class="p-3 text-right font-mono text-slate-500">{{ $part->min_alert_threshold }} {{ $part->unit }}</td>
                                <td class="p-3 text-right font-mono text-slate-300">Rs. {{ number_format($part->unit_price, 2) }}</td>
                                <td class="p-3 text-right font-mono font-bold text-indigo-400">Rs. {{ number_format($part->stock_quantity * $part->unit_price, 2) }}</td>
                                <td class="p-3 text-slate-400 font-mono">{{ $part->rack_location ?? '-' }}</td>
                                <td class="p-3 text-right">
                                    <button type="button" onclick="preselectPartForIssuance('{{ $part->id }}', '{{ $part->part_name }}', '{{ $part->stock_quantity }}', '{{ $part->unit }}', '{{ $part->unit_price }}')" class="px-3 py-1 rounded-xl bg-purple-600/30 hover:bg-purple-600 text-purple-200 border border-purple-500/30 text-[11px] font-bold transition-all">
                                        Issue to Loom &rarr;
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="p-6 text-center text-slate-500">No parts in store yet. Click "+ Add New Part Item" to start.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $parts->links() }}
            </div>
        </div>

        <!-- TAB 2: ISSUANCE LOG -->
        <div id="tabIssuances" class="hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-slate-800 text-slate-400 font-semibold uppercase text-[10px] tracking-wider bg-slate-950/40">
                            <th class="p-3">Date</th>
                            <th class="p-3">Target Loom #</th>
                            <th class="p-3">Part Issued</th>
                            <th class="p-3">Mechanic / Incharge</th>
                            <th class="p-3 text-right">Quantity</th>
                            <th class="p-3 text-right">Unit Price</th>
                            <th class="p-3 text-right">Total Cost</th>
                            <th class="p-3">Reason / Remarks</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/60">
                        @forelse($issuances as $iss)
                            <tr class="hover:bg-slate-800/30 transition-colors">
                                <td class="p-3 font-mono text-slate-400">{{ $iss->issuance_date->format('d M, Y') }}</td>
                                <td class="p-3 font-mono font-black text-white">
                                    <span class="px-2.5 py-1 rounded-lg bg-indigo-500/20 text-indigo-300 border border-indigo-500/30">
                                        {{ $iss->loom_no }}
                                    </span>
                                </td>
                                <td class="p-3 font-bold text-purple-300">{{ $iss->part ? $iss->part->part_name : 'Part' }}</td>
                                <td class="p-3 font-medium text-slate-200">{{ $iss->issued_to_person }}</td>
                                <td class="p-3 text-right font-mono font-bold text-white">{{ $iss->quantity }}</td>
                                <td class="p-3 text-right font-mono text-slate-400">Rs. {{ number_format($iss->unit_price, 2) }}</td>
                                <td class="p-3 text-right font-mono font-bold text-rose-400">Rs. {{ number_format($iss->total_cost, 2) }}</td>
                                <td class="p-3 text-slate-400 italic">{{ $iss->remarks ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="p-6 text-center text-slate-500">No parts issuances recorded yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $issuances->links() }}
            </div>
        </div>

    </div>

</div>

<!-- MODAL 1: ADD NEW PART TO STORE -->
<div id="modalAddPart" class="fixed inset-0 z-50 hidden bg-slate-950/80 backdrop-blur-sm flex items-center justify-center p-4 overflow-y-auto">
    <div class="bg-slate-900 border border-slate-700/80 rounded-3xl max-w-lg w-full p-6 shadow-2xl relative my-8">
        <div class="flex items-center justify-between pb-3 border-b border-slate-800">
            <h3 class="text-lg font-bold text-white flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-purple-400"></span>
                Add New Loom Part to Store
            </h3>
            <button type="button" onclick="closeModal('modalAddPart')" class="text-slate-400 hover:text-white p-1">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <form action="{{ route('parts.store') }}" method="POST" class="mt-4 space-y-4">
            @csrf

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">Part Code / Item # *</label>
                    <input type="text" name="part_code" required placeholder="e.g. GRP-RH-02" class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3 py-2 text-sm text-white focus:outline-none focus:ring-2 focus:ring-purple-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">Category *</label>
                    <select name="category" required class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3 py-2 text-sm text-white focus:outline-none focus:ring-2 focus:ring-purple-500">
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}">{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 mb-1">Part Name / Specification *</label>
                <input type="text" name="part_name" required placeholder="e.g. Rapier Gripper Head (Right)" class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3 py-2 text-sm text-white focus:outline-none focus:ring-2 focus:ring-purple-500">
            </div>

            <div class="grid grid-cols-3 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">Opening Stock *</label>
                    <input type="number" name="stock_quantity" required min="0" placeholder="10" class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3 py-2 text-sm text-white font-mono focus:outline-none focus:ring-2 focus:ring-purple-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">Low Alert Limit *</label>
                    <input type="number" name="min_alert_threshold" required min="0" placeholder="5" class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3 py-2 text-sm text-white font-mono focus:outline-none focus:ring-2 focus:ring-purple-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">Unit *</label>
                    <input type="text" name="unit" required value="pcs" placeholder="pcs, set, kg" class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3 py-2 text-sm text-white focus:outline-none focus:ring-2 focus:ring-purple-500">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">Unit Cost (Rs.) *</label>
                    <input type="number" step="0.01" name="unit_price" required min="0" placeholder="3800" class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3 py-2 text-sm text-white font-mono focus:outline-none focus:ring-2 focus:ring-purple-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">Rack / Bin Location</label>
                    <input type="text" name="rack_location" placeholder="e.g. Rack P-1" class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3 py-2 text-sm text-white focus:outline-none focus:ring-2 focus:ring-purple-500">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 mb-1">Notes / Description</label>
                <input type="text" name="notes" placeholder="e.g. Compatible with Picanol / Dornier rapier looms" class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3 py-2 text-sm text-white focus:outline-none focus:ring-2 focus:ring-purple-500">
            </div>

            <div class="pt-3 border-t border-slate-800 flex justify-end gap-3">
                <button type="button" onclick="closeModal('modalAddPart')" class="px-4 py-2 rounded-xl bg-slate-800 text-slate-300 text-xs font-semibold">Cancel</button>
                <button type="submit" class="px-5 py-2 rounded-xl bg-purple-500 hover:bg-purple-400 text-slate-950 text-xs font-black">Save Part to Store</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL 2: ISSUE PART TO FACTORY LOOM -->
<div id="modalIssuePart" class="fixed inset-0 z-50 hidden bg-slate-950/80 backdrop-blur-sm flex items-center justify-center p-4 overflow-y-auto">
    <div class="bg-slate-900 border border-slate-700/80 rounded-3xl max-w-md w-full p-6 shadow-2xl relative my-8">
        <div class="flex items-center justify-between pb-3 border-b border-slate-800">
            <h3 class="text-lg font-bold text-white flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-indigo-400"></span>
                Issue Part to Factory Loom
            </h3>
            <button type="button" onclick="closeModal('modalIssuePart')" class="text-slate-400 hover:text-white p-1">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <form action="{{ route('parts.issue') }}" method="POST" class="mt-4 space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-semibold text-slate-300 mb-1">Select Part from Store *</label>
                <select name="part_id" id="selectIssuePart" required onchange="handlePartSelectChange()" class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3 py-2 text-sm text-white focus:outline-none focus:ring-2 focus:ring-purple-500">
                    <option value="">-- Choose Part Item --</option>
                    @foreach($parts as $p)
                        <option value="{{ $p->id }}" data-price="{{ $p->unit_price }}" data-stock="{{ $p->stock_quantity }}" data-unit="{{ $p->unit }}">
                            {{ $p->part_name }} ({{ $p->part_code }}) - In Stock: {{ $p->stock_quantity }} {{ $p->unit }}
                        </option>
                    @endforeach
                </select>
                <div id="issueStockNotice" class="text-[11px] text-slate-400 mt-1"></div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">Target Loom # *</label>
                    <select name="loom_no" required class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3 py-2 text-sm text-white font-mono font-bold focus:outline-none focus:ring-2 focus:ring-purple-500">
                        @foreach($looms as $loom)
                            <option value="{{ $loom }}">{{ $loom }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">Issuance Date *</label>
                    <input type="date" name="issuance_date" required value="{{ date('Y-m-d') }}" class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3 py-2 text-sm text-white focus:outline-none focus:ring-2 focus:ring-purple-500">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">Quantity to Issue *</label>
                    <input type="number" id="inpIssueQty" name="quantity" required min="1" value="1" oninput="calculateIssueTotalCost()" class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3 py-2 text-sm text-white font-mono font-bold focus:outline-none focus:ring-2 focus:ring-purple-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">Issued to (Person) *</label>
                    <input type="text" name="issued_to_person" required placeholder="e.g. Master Aslam" class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3 py-2 text-sm text-white focus:outline-none focus:ring-2 focus:ring-purple-500">
                </div>
            </div>

            <div class="p-3 rounded-xl bg-purple-950/30 border border-purple-800/40 text-xs flex items-center justify-between">
                <span class="text-slate-400">Total Part Cost:</span>
                <strong id="displayIssueCost" class="font-mono text-purple-300 text-sm">Rs. 0.00</strong>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 mb-1">Reason / Maintenance Remarks</label>
                <input type="text" name="remarks" placeholder="e.g. Routine replacement, worn gripper clip" class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3 py-2 text-sm text-white focus:outline-none focus:ring-2 focus:ring-purple-500">
            </div>

            <div class="pt-3 border-t border-slate-800 flex justify-end gap-3">
                <button type="button" onclick="closeModal('modalIssuePart')" class="px-4 py-2 rounded-xl bg-slate-800 text-slate-300 text-xs font-semibold">Cancel</button>
                <button type="submit" class="px-5 py-2 rounded-xl bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-500 hover:to-indigo-500 text-white text-xs font-black shadow-lg shadow-purple-600/20">Confirm Issuance</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function switchPartsTab(tabId) {
        document.getElementById('tabInventory').classList.add('hidden');
        document.getElementById('tabIssuances').classList.add('hidden');

        document.getElementById('btnTabInventory').className = 'px-4 py-2 rounded-xl text-xs font-bold bg-slate-800 text-slate-300 hover:text-white';
        document.getElementById('btnTabIssuances').className = 'px-4 py-2 rounded-xl text-xs font-bold bg-slate-800 text-slate-300 hover:text-white';

        document.getElementById(tabId).classList.remove('hidden');

        if (tabId === 'tabInventory') {
            document.getElementById('btnTabInventory').className = 'px-4 py-2 rounded-xl text-xs font-bold bg-purple-500 text-white shadow-md';
        } else {
            document.getElementById('btnTabIssuances').className = 'px-4 py-2 rounded-xl text-xs font-bold bg-purple-500 text-white shadow-md';
        }
    }

    let selectedPartPrice = 0;

    function handlePartSelectChange() {
        const select = document.getElementById('selectIssuePart');
        const selectedOption = select.options[select.selectedIndex];
        
        if (selectedOption && selectedOption.value) {
            selectedPartPrice = parseFloat(selectedOption.dataset.price) || 0;
            const stock = selectedOption.dataset.stock;
            const unit = selectedOption.dataset.unit;
            document.getElementById('issueStockNotice').innerHTML = `Currently in store: <strong class="text-emerald-400">${stock} ${unit}</strong> &bull; Rate: Rs. ${selectedPartPrice.toLocaleString()}`;
        } else {
            selectedPartPrice = 0;
            document.getElementById('issueStockNotice').innerHTML = '';
        }
        calculateIssueTotalCost();
    }

    function calculateIssueTotalCost() {
        const qty = parseFloat(document.getElementById('inpIssueQty').value) || 0;
        const total = qty * selectedPartPrice;
        document.getElementById('displayIssueCost').innerText = 'Rs. ' + total.toLocaleString('en-US', {minimumFractionDigits: 2});
    }

    function preselectPartForIssuance(partId, partName, stock, unit, price) {
        const select = document.getElementById('selectIssuePart');
        select.value = partId;
        handlePartSelectChange();
        openModal('modalIssuePart');
    }
</script>
@endpush
@endsection
