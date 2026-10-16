@extends('layouts.app')

@section('title', 'Sizing Slips & Beam Management')

@section('content')
<div class="space-y-6">

    <!-- Header & Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-2 border-b border-slate-800">
        <div>
            <div class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-cyan-400"></span>
                <span class="text-xs font-bold uppercase tracking-wider text-cyan-400">Module 02</span>
            </div>
            <h1 class="text-2xl font-black text-white tracking-tight">Sizing Slips & Beams Tracking</h1>
            <p class="text-xs text-slate-400 mt-0.5">Sizing slip entry, loom-wise beam mounting & length tracking, and unused bags reconciliation.</p>
        </div>

        <div class="flex items-center gap-3">
            <button type="button" onclick="openModal('modalNewSizingSlip')" class="px-4 py-2 rounded-xl bg-gradient-to-r from-cyan-600 to-cyan-500 hover:from-cyan-500 hover:to-cyan-400 text-slate-950 font-extrabold text-xs shadow-lg shadow-cyan-600/25 transition-all flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span>+ New Sizing Slip Entry</span>
            </button>
            <a href="{{ route('bags.index') }}" class="px-3.5 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 text-xs font-bold transition-colors flex items-center gap-1.5">
                <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                <span>Bags History</span>
            </a>
        </div>
    </div>

    <!-- KPIs: Sizing Beams & Bag Breakdown -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
        
        <div class="glass-card rounded-2xl p-4 border border-cyan-500/20 bg-cyan-500/5">
            <div class="text-[11px] font-bold uppercase tracking-wider text-cyan-400 flex items-center justify-between">
                <span>Active Beams on Looms</span>
                <span class="w-2 h-2 rounded-full bg-cyan-400"></span>
            </div>
            <div class="text-2xl font-black text-cyan-400 mt-1">{{ $mountedBeamsCount }} <span class="text-xs font-normal text-slate-400">mounted</span></div>
            <div class="text-[11px] text-cyan-400/80 mt-1 font-mono">{{ number_format($remainingBeamMetersOnLooms) }}m yarn left</div>
        </div>

        <div class="glass-card rounded-2xl p-4 border border-emerald-500/20 bg-emerald-500/5">
            <div class="text-[11px] font-bold uppercase tracking-wider text-emerald-400 flex items-center justify-between">
                <span>Beams Ready in Store</span>
                <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
            </div>
            <div class="text-2xl font-black text-emerald-400 mt-1">{{ $readyBeamsCount }} <span class="text-xs font-normal text-slate-400">ready</span></div>
            <div class="text-[11px] text-emerald-400/80 mt-1">Available for mounting</div>
        </div>

        <div class="glass-card rounded-2xl p-4 border border-slate-800">
            <div class="text-[11px] font-bold uppercase tracking-wider text-slate-400 flex items-center justify-between">
                <span>Total Beams Produced</span>
                <span class="w-2 h-2 rounded-full bg-slate-400"></span>
            </div>
            <div class="text-2xl font-black text-white mt-1">{{ $totalBeamsCount }} <span class="text-xs font-normal text-slate-400">beams</span></div>
            <div class="text-[11px] text-slate-500 mt-1 font-mono">{{ number_format($totalBeamMetersProduced) }} total meters</div>
        </div>

        <div class="glass-card rounded-2xl p-4 border border-indigo-500/20 bg-indigo-500/5">
            <div class="text-[11px] font-bold uppercase tracking-wider text-indigo-400 flex items-center justify-between">
                <span>Bags Consumed in Beams</span>
                <span class="w-2 h-2 rounded-full bg-indigo-400"></span>
            </div>
            <div class="text-2xl font-black text-indigo-400 mt-1">{{ $totalUsedBags }} <span class="text-xs font-normal text-slate-400">bags</span></div>
            <div class="text-[11px] text-slate-400 mt-1">Out of {{ $totalAllocatedBags }} allocated</div>
        </div>

        <div class="glass-card rounded-2xl p-4 border border-amber-500/20 bg-amber-500/5">
            <div class="text-[11px] font-bold uppercase tracking-wider text-amber-400 flex items-center justify-between">
                <span>Unused Bags Status</span>
                <span class="w-2 h-2 rounded-full bg-amber-400"></span>
            </div>
            <div class="text-xs space-y-1 mt-1 font-bold">
                <div class="flex justify-between"><span class="text-slate-400">Returned to Factory:</span> <span class="text-emerald-400 font-mono">{{ $totalReturnedBags }} bags</span></div>
                <div class="flex justify-between"><span class="text-slate-400">Held With Sizing:</span> <span class="text-amber-400 font-mono">{{ $totalHeldWithSizing }} bags</span></div>
            </div>
        </div>

    </div>

    <!-- TABS: 1. BEAMS TRACKING (LOOM-WISE) | 2. SIZING SLIPS ENTRY -->
    <div class="glass-card rounded-3xl p-6 border border-slate-800">
        <div class="flex items-center justify-between mb-5">
            <div class="flex items-center gap-3">
                <button type="button" onclick="switchSizingTab('tabBeams')" id="btnTabBeams" class="px-4 py-2 rounded-xl text-xs font-bold bg-cyan-500 text-slate-950 shadow-md">
                    1. Beams Tracking & Loom Allocation
                </button>
                <button type="button" onclick="switchSizingTab('tabSlips')" id="btnTabSlips" class="px-4 py-2 rounded-xl text-xs font-bold bg-slate-800 text-slate-300 hover:text-white">
                    2. Sizing Slips & Bag Consumption History
                </button>
            </div>
            <div class="text-xs text-slate-400 font-mono">
                Total Beams: {{ $beams->total() }}
            </div>
        </div>

        <!-- TAB 1: BEAMS (LOOM-WISE) -->
        <div id="tabBeams">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-slate-800 text-slate-400 font-semibold uppercase text-[10px] tracking-wider bg-slate-950/40">
                            <th class="p-3">Beam #</th>
                            <th class="p-3">Sizing Slip Ref</th>
                            <th class="p-3">Yarn Quality</th>
                            <th class="p-3 text-right">Initial Length</th>
                            <th class="p-3 text-right">Remaining Length</th>
                            <th class="p-3 text-center">Assigned Loom</th>
                            <th class="p-3 text-center">Status</th>
                            <th class="p-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/60">
                        @forelse($beams as $bm)
                            <tr class="hover:bg-slate-800/30 transition-colors">
                                <td class="p-3 font-mono font-black text-cyan-400 text-sm">{{ $bm->beam_no }}</td>
                                <td class="p-3 font-mono text-slate-400">{{ $bm->sizingSlip ? $bm->sizingSlip->slip_no : 'Manual' }}</td>
                                <td class="p-3 font-semibold text-slate-200">{{ $bm->yarn_quality }}</td>
                                <td class="p-3 text-right font-mono text-slate-300">{{ number_format($bm->total_length_meters, 0) }}m</td>
                                <td class="p-3 text-right font-mono font-bold {{ $bm->remaining_length_meters < 500 ? 'text-rose-400' : 'text-emerald-400' }}">
                                    {{ number_format($bm->remaining_length_meters, 0) }}m
                                </td>
                                <td class="p-3 text-center">
                                    @if($bm->assigned_loom_no)
                                        <span class="px-2.5 py-1 rounded-xl text-xs font-mono font-black bg-indigo-500/20 text-indigo-300 border border-indigo-500/40">
                                            {{ $bm->assigned_loom_no }}
                                        </span>
                                    @else
                                        <span class="text-slate-500 italic">Not Mounted</span>
                                    @endif
                                </td>
                                <td class="p-3 text-center">
                                    @if($bm->status === 'mounted_on_loom')
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-cyan-500/20 text-cyan-300 border border-cyan-500/30 inline-flex items-center gap-1">
                                            <span class="w-1.5 h-1.5 rounded-full bg-cyan-400 animate-pulse"></span> Running
                                        </span>
                                    @elseif($bm->status === 'ready_in_store')
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">
                                            Ready in Store
                                        </span>
                                    @else
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-800 text-slate-400">
                                            Exhausted
                                        </span>
                                    @endif
                                </td>
                                <td class="p-3 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        @if($bm->status !== 'exhausted')
                                            <button type="button" onclick="openMountModal('{{ $bm->id }}', '{{ $bm->beam_no }}', '{{ $bm->assigned_loom_no }}')" class="px-2.5 py-1 rounded-lg bg-indigo-600/30 hover:bg-indigo-600 text-indigo-200 border border-indigo-500/30 text-[11px] font-bold transition-colors">
                                                {{ $bm->assigned_loom_no ? 'Change Loom' : 'Mount on Loom' }}
                                            </button>

                                            <form action="{{ route('sizing.beam.exhaust', $bm->id) }}" method="POST" onsubmit="return confirm('Mark Beam {{ $bm->beam_no }} as finished/exhausted?')">
                                                @csrf
                                                <button type="submit" class="px-2 py-1 rounded-lg bg-slate-800 hover:bg-rose-500/20 text-slate-400 hover:text-rose-300 text-[11px] transition-colors" title="Mark Beam Finished">
                                                    Done
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-[10px] text-slate-600">Finished</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="p-6 text-center text-slate-500">No beams generated yet. Create a Sizing Slip to generate beams.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $beams->links() }}
            </div>
        </div>

        <!-- TAB 2: SIZING SLIPS ENTRY & CONSUMPTION -->
        <div id="tabSlips" class="hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-slate-800 text-slate-400 font-semibold uppercase text-[10px] tracking-wider bg-slate-950/40">
                            <th class="p-3">Slip #</th>
                            <th class="p-3">Date</th>
                            <th class="p-3">Sizing Party</th>
                            <th class="p-3">Quality</th>
                            <th class="p-3 text-center">Bags Given</th>
                            <th class="p-3 text-center bg-indigo-950/20 text-indigo-300">Used in Beams</th>
                            <th class="p-3 text-center bg-emerald-950/20 text-emerald-300">Returned to Factory</th>
                            <th class="p-3 text-center bg-amber-950/20 text-amber-300">Held With Sizing</th>
                            <th class="p-3 text-center">Beams Created</th>
                            <th class="p-3 text-right">Total Length</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/60">
                        @forelse($slips as $s)
                            <tr class="hover:bg-slate-800/30 transition-colors">
                                <td class="p-3 font-mono font-bold text-white">{{ $s->slip_no }}</td>
                                <td class="p-3 text-slate-400 font-mono">{{ $s->slip_date->format('d M, Y') }}</td>
                                <td class="p-3 font-semibold text-slate-200">{{ $s->sizing_party }}</td>
                                <td class="p-3 text-slate-300 font-mono">{{ $s->yarn_quality }}</td>
                                <td class="p-3 text-center font-bold text-white font-mono">{{ $s->bags_allocated }}</td>
                                <td class="p-3 text-center font-bold text-indigo-400 bg-indigo-950/20 font-mono">{{ $s->bags_used }}</td>
                                <td class="p-3 text-center font-bold text-emerald-400 bg-emerald-950/20 font-mono">{{ $s->bags_returned_to_factory }}</td>
                                <td class="p-3 text-center font-bold text-amber-400 bg-amber-950/20 font-mono">{{ $s->bags_held_with_sizing }}</td>
                                <td class="p-3 text-center font-bold text-cyan-400">{{ $s->total_beams }} Beams</td>
                                <td class="p-3 text-right font-mono font-bold text-emerald-400">{{ number_format($s->total_length_meters, 0) }}m</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="p-6 text-center text-slate-500">No sizing slips found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $slips->links() }}
            </div>
        </div>

    </div>

</div>

<!-- MODAL 1: NEW SIZING SLIP ENTRY (CREATES SLIP + BEAMS + AUTO BAG ADJUSTMENT) -->
<div id="modalNewSizingSlip" class="fixed inset-0 z-50 hidden bg-slate-950/80 backdrop-blur-sm flex items-center justify-center p-4 overflow-y-auto">
    <div class="bg-slate-900 border border-slate-700/80 rounded-3xl max-w-xl w-full p-6 shadow-2xl relative my-8">
        <div class="flex items-center justify-between pb-3 border-b border-slate-800">
            <div>
                <h3 class="text-lg font-bold text-white flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-cyan-400"></span>
                    New Sizing Slip Entry
                </h3>
                <p class="text-xs text-slate-400">Generates ready beams and tracks used vs returned bags</p>
            </div>
            <button type="button" onclick="closeModal('modalNewSizingSlip')" class="text-slate-400 hover:text-white p-1">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <form action="{{ route('sizing.slip.store') }}" method="POST" class="mt-4 space-y-4">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">Sizing Slip # *</label>
                    <input type="text" name="slip_no" required placeholder="e.g. SZ-2026-092" class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3 py-2 text-sm text-white focus:outline-none focus:ring-2 focus:ring-cyan-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">Slip Date *</label>
                    <input type="date" name="slip_date" required value="{{ date('Y-m-d') }}" class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3 py-2 text-sm text-white focus:outline-none focus:ring-2 focus:ring-cyan-500">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">Sizing Unit / Party *</label>
                    <input type="text" name="sizing_party" required placeholder="e.g. National Sizing Industries" class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3 py-2 text-sm text-white focus:outline-none focus:ring-2 focus:ring-cyan-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">Yarn Quality *</label>
                    <input type="text" name="yarn_quality" required placeholder="e.g. 30/1 PC Combed" class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3 py-2 text-sm text-white focus:outline-none focus:ring-2 focus:ring-cyan-500">
                </div>
            </div>

            <!-- BAG RECONCILIATION: ALLOCATED vs (USED + RETURNED + HELD) -->
            <div class="p-4 rounded-2xl bg-slate-950 border border-slate-800 space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-300">⚖️ Sizing Bags Reconciliation</span>
                    <span id="sizingBagStatus" class="text-xs font-bold font-mono text-cyan-400">Total Accounted: 0</span>
                </div>

                <div>
                    <label class="block text-[11px] font-semibold text-slate-300 mb-1">Total Bags Given to Sizing *</label>
                    <input type="number" id="inpBagsAllocated" name="bags_allocated" required min="1" placeholder="75" oninput="calculateSizingBags()" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3 py-2 text-sm text-white font-mono font-bold focus:outline-none focus:ring-2 focus:ring-cyan-500">
                </div>

                <div class="grid grid-cols-3 gap-2.5">
                    <div>
                        <label class="block text-[10px] font-semibold text-indigo-400 mb-1">Used in Beams *</label>
                        <input type="number" id="inpBagsUsed" name="bags_used" required min="1" placeholder="65" oninput="calculateSizingBags()" class="w-full bg-slate-900 border border-indigo-800 rounded-xl px-2.5 py-1.5 text-xs text-indigo-300 font-mono font-bold focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-[10px] font-semibold text-emerald-400 mb-1">Returned to Mill</label>
                        <input type="number" id="inpBagsReturned" name="bags_returned_to_factory" value="0" min="0" oninput="calculateSizingBags()" class="w-full bg-slate-900 border border-emerald-800 rounded-xl px-2.5 py-1.5 text-xs text-emerald-300 font-mono font-bold focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    </div>
                    <div>
                        <label class="block text-[10px] font-semibold text-amber-400 mb-1">Held with Sizing</label>
                        <input type="number" id="inpBagsHeld" name="bags_held_with_sizing" value="0" min="0" oninput="calculateSizingBags()" class="w-full bg-slate-900 border border-amber-800 rounded-xl px-2.5 py-1.5 text-xs text-amber-300 font-mono font-bold focus:outline-none focus:ring-2 focus:ring-amber-500">
                    </div>
                </div>
            </div>

            <!-- BEAMS CREATION -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">Total Beams Produced *</label>
                    <input type="number" id="inpTotalBeams" name="total_beams" required min="1" max="20" placeholder="4" oninput="calculateBeamLengthSplit()" class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3 py-2 text-sm text-cyan-400 font-mono font-bold focus:outline-none focus:ring-2 focus:ring-cyan-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">Total Warp Length (Meters) *</label>
                    <input type="number" step="0.01" id="inpTotalLength" name="total_length_meters" required min="100" placeholder="18000" oninput="calculateBeamLengthSplit()" class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3 py-2 text-sm text-emerald-400 font-mono font-bold focus:outline-none focus:ring-2 focus:ring-cyan-500">
                </div>
            </div>

            <div class="p-3 rounded-xl bg-cyan-950/30 border border-cyan-800/40 text-xs text-cyan-300 flex items-center justify-between">
                <span>Calculated Beam Length:</span>
                <strong id="displayPerBeamLength" class="font-mono text-sm text-white">4,500m per beam</strong>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 mb-1">Notes</label>
                <input type="text" name="notes" placeholder="e.g. Sizing chemicals used, tension settings" class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3 py-2 text-sm text-white focus:outline-none focus:ring-2 focus:ring-cyan-500">
            </div>

            <div class="pt-3 border-t border-slate-800 flex justify-end gap-3">
                <button type="button" onclick="closeModal('modalNewSizingSlip')" class="px-4 py-2 rounded-xl bg-slate-800 text-slate-300 text-xs font-semibold">Cancel</button>
                <button type="submit" id="btnSubmitSizingSlip" class="px-5 py-2 rounded-xl bg-cyan-500 hover:bg-cyan-400 text-slate-950 text-xs font-black shadow-lg shadow-cyan-500/20">Create Slip & Beams</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL 2: MOUNT BEAM ON LOOM -->
<div id="modalMountBeam" class="fixed inset-0 z-50 hidden bg-slate-950/80 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-slate-900 border border-slate-700 rounded-3xl max-w-sm w-full p-6 shadow-2xl relative">
        <div class="flex items-center justify-between pb-3 border-b border-slate-800">
            <h3 class="text-base font-bold text-white flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-indigo-400"></span>
                Mount Beam onto Loom
            </h3>
            <button type="button" onclick="closeModal('modalMountBeam')" class="text-slate-400 hover:text-white p-1">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <form id="formMountBeam" method="POST" class="mt-4 space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-semibold text-slate-300 mb-1">Beam Selected</label>
                <input type="text" id="mountBeamName" readonly class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-sm font-mono font-bold text-cyan-400">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 mb-1">Assign to Loom # *</label>
                <select name="assigned_loom_no" id="selectMountLoom" required class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3 py-2 text-sm text-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    @foreach($looms as $loom)
                        <option value="{{ $loom }}">{{ $loom }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 mb-1">Mount Date *</label>
                <input type="date" name="mounted_date" required value="{{ date('Y-m-d') }}" class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3 py-2 text-sm text-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>

            <div class="pt-3 border-t border-slate-800 flex justify-end gap-3">
                <button type="button" onclick="closeModal('modalMountBeam')" class="px-4 py-2 rounded-xl bg-slate-800 text-slate-300 text-xs font-semibold">Cancel</button>
                <button type="submit" class="px-5 py-2 rounded-xl bg-indigo-500 hover:bg-indigo-400 text-white text-xs font-bold">Mount on Loom</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function switchSizingTab(tabId) {
        document.getElementById('tabBeams').classList.add('hidden');
        document.getElementById('tabSlips').classList.add('hidden');

        document.getElementById('btnTabBeams').className = 'px-4 py-2 rounded-xl text-xs font-bold bg-slate-800 text-slate-300 hover:text-white';
        document.getElementById('btnTabSlips').className = 'px-4 py-2 rounded-xl text-xs font-bold bg-slate-800 text-slate-300 hover:text-white';

        document.getElementById(tabId).classList.remove('hidden');

        if (tabId === 'tabBeams') {
            document.getElementById('btnTabBeams').className = 'px-4 py-2 rounded-xl text-xs font-bold bg-cyan-500 text-slate-950 shadow-md';
        } else {
            document.getElementById('btnTabSlips').className = 'px-4 py-2 rounded-xl text-xs font-bold bg-cyan-500 text-slate-950 shadow-md';
        }
    }

    function calculateSizingBags() {
        const allocated = parseFloat(document.getElementById('inpBagsAllocated').value) || 0;
        const used = parseFloat(document.getElementById('inpBagsUsed').value) || 0;
        const returned = parseFloat(document.getElementById('inpBagsReturned').value) || 0;
        const held = parseFloat(document.getElementById('inpBagsHeld').value) || 0;

        const sum = used + returned + held;
        const statusEl = document.getElementById('sizingBagStatus');
        const submitBtn = document.getElementById('btnSubmitSizingSlip');

        if (sum === allocated && allocated > 0) {
            statusEl.innerText = `Balanced: ${used} Used + ${returned} Returned + ${held} Held = ${allocated} ✓`;
            statusEl.className = 'text-xs font-bold font-mono text-emerald-400';
            submitBtn.disabled = false;
            submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
        } else {
            statusEl.innerText = `Current Sum: ${sum} / ${allocated} Bags (${allocated - sum > 0 ? (allocated - sum) + ' unassigned' : (sum - allocated) + ' excess'}) ✗`;
            statusEl.className = 'text-xs font-bold font-mono text-rose-400';
            submitBtn.disabled = true;
            submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
        }
    }

    function calculateBeamLengthSplit() {
        const beams = parseInt(document.getElementById('inpTotalBeams').value) || 1;
        const totalLen = parseFloat(document.getElementById('inpTotalLength').value) || 0;

        if (beams > 0 && totalLen > 0) {
            const perBeam = (totalLen / beams).toFixed(1);
            document.getElementById('displayPerBeamLength').innerText = `${beams} Beams x ${perBeam}m each`;
        }
    }

    function openMountModal(beamId, beamNo, currentLoom) {
        document.getElementById('formMountBeam').action = `/sizing/beams/${beamId}/assign`;
        document.getElementById('mountBeamName').value = beamNo;
        if (currentLoom) {
            document.getElementById('selectMountLoom').value = currentLoom;
        }
        openModal('modalMountBeam');
    }
</script>
@endpush
@endsection
