@extends('layouts.app')

@section('title', 'Loom-wise Production & Leakage Analysis')

@section('content')
<div class="space-y-6">

    <!-- Header & Action Buttons -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-2 border-b border-slate-800">
        <div>
            <div class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-rose-400"></span>
                <span class="text-xs font-bold uppercase tracking-wider text-rose-400">Module 06</span>
            </div>
            <h1 class="text-2xl font-black text-white tracking-tight">Production & Leakage Loss Calculation</h1>
            <p class="text-xs text-slate-400 mt-0.5">Loom-wise shift production tracking: how much beam it took, how much fabric it made, and leakage waste loss.</p>
        </div>

        <div class="flex items-center gap-3">
            <button type="button" onclick="openModal('modalRecordProduction')" class="px-4 py-2 rounded-xl bg-gradient-to-r from-rose-600 to-amber-500 hover:from-rose-500 hover:to-amber-400 text-white font-extrabold text-xs shadow-lg shadow-rose-600/25 transition-all flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span>+ Record Loom Production</span>
            </button>
            <a href="{{ route('sizing.index') }}" class="px-3.5 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 text-xs font-bold transition-colors flex items-center gap-1.5">
                <svg class="w-4 h-4 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                <span>Active Beams</span>
            </a>
        </div>
    </div>

    <!-- KPIs: Production, Beam Consumed, Leakage & Efficiency -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
        
        <div class="glass-card rounded-2xl p-4 border border-emerald-500/20 bg-emerald-500/5">
            <div class="text-[11px] font-bold uppercase tracking-wider text-emerald-400 flex items-center justify-between">
                <span>Total Fabric Made</span>
                <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
            </div>
            <div class="text-2xl font-black text-emerald-400 mt-1 font-mono">{{ number_format($totalFabricProduced, 0) }} <span class="text-xs font-normal text-slate-400">meters</span></div>
            <div class="text-[11px] text-emerald-400/80 mt-1">Net cloth produced on looms</div>
        </div>

        <div class="glass-card rounded-2xl p-4 border border-cyan-500/20 bg-cyan-500/5">
            <div class="text-[11px] font-bold uppercase tracking-wider text-cyan-400 flex items-center justify-between">
                <span>Total Beam Length Taken</span>
                <span class="w-2 h-2 rounded-full bg-cyan-400"></span>
            </div>
            <div class="text-2xl font-black text-cyan-400 mt-1 font-mono">{{ number_format($totalBeamConsumed, 0) }} <span class="text-xs font-normal text-slate-400">meters</span></div>
            <div class="text-[11px] text-cyan-400/80 mt-1">Beam length fed into looms</div>
        </div>

        <div class="glass-card rounded-2xl p-4 border border-rose-500/30 bg-rose-500/10">
            <div class="text-[11px] font-bold uppercase tracking-wider text-rose-400 flex items-center justify-between">
                <span>Warp Leakage / Loss</span>
                <span class="w-2 h-2 rounded-full bg-rose-400 animate-pulse"></span>
            </div>
            <div class="text-2xl font-black text-rose-400 mt-1 font-mono">{{ number_format($totalLeakageMeters, 0) }} <span class="text-xs font-normal text-slate-400">meters</span></div>
            <div class="text-[11px] text-rose-400 font-bold mt-1">{{ $overallLeakagePercentage }}% Average Leakage</div>
        </div>

        <div class="glass-card rounded-2xl p-4 border border-indigo-500/20 bg-indigo-500/5">
            <div class="text-[11px] font-bold uppercase tracking-wider text-indigo-400 flex items-center justify-between">
                <span>Weaving Floor Efficiency</span>
                <span class="w-2 h-2 rounded-full bg-indigo-400"></span>
            </div>
            <div class="text-2xl font-black text-indigo-300 mt-1 font-mono">{{ $avgEfficiency }}%</div>
            <div class="text-[11px] text-slate-400 mt-1">Based on RPM & picks / inch</div>
        </div>

        <div class="glass-card rounded-2xl p-4 border border-slate-800">
            <div class="text-[11px] font-bold uppercase tracking-wider text-slate-400 flex items-center justify-between">
                <span>Loom Units Active</span>
                <span class="w-2 h-2 rounded-full bg-slate-400"></span>
            </div>
            <div class="text-2xl font-black text-white mt-1">{{ $loomWiseStats->count() }} <span class="text-xs font-normal text-slate-400">of 24</span></div>
            <div class="text-[11px] text-slate-500 mt-1">Looms producing with mounted beams</div>
        </div>

    </div>

    <!-- LOOM-WISE PERFORMANCE MATRIX -->
    <div class="glass-card rounded-3xl p-6 border border-slate-800">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-sm font-bold text-white flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-rose-400"></span>
                    Loom-wise Production & Leakage Summary
                </h3>
                <p class="text-xs text-slate-400">Calculates output, beam consumption, and leakage meters for each loom</p>
            </div>
            <span class="text-xs font-mono text-slate-400">Loom Analysis</span>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
            @forelse($loomWiseStats as $stat)
                @php
                    $leakagePct = $stat->total_beam > 0 ? round(($stat->total_leakage / $stat->total_beam) * 100, 2) : 0;
                @endphp
                <div class="p-4 rounded-2xl bg-slate-950/70 border border-slate-800 space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="font-mono font-black text-base text-white px-2 py-0.5 rounded-lg bg-indigo-500/20 text-indigo-300 border border-indigo-500/30">
                            {{ $stat->loom_no }}
                        </span>
                        <span class="text-[11px] font-bold text-slate-400">{{ $stat->shift_count }} Shifts</span>
                    </div>

                    <div class="space-y-1 text-xs pt-1">
                        <div class="flex justify-between">
                            <span class="text-slate-400">Fabric Made:</span>
                            <strong class="text-emerald-400 font-mono">{{ number_format($stat->total_produced, 0) }}m</strong>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-400">Beam Taken:</span>
                            <strong class="text-cyan-400 font-mono">{{ number_format($stat->total_beam, 0) }}m</strong>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-rose-400">Leakage Loss:</span>
                            <strong class="text-rose-400 font-mono">{{ number_format($stat->total_leakage, 0) }}m ({{ $leakagePct }}%)</strong>
                        </div>
                        <div class="flex justify-between pt-1 border-t border-slate-800/80">
                            <span class="text-slate-400">Efficiency:</span>
                            <strong class="text-indigo-400 font-mono">{{ round($stat->avg_eff, 1) }}%</strong>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-xs text-slate-500 col-span-4">No loom production recorded yet.</p>
            @endforelse
        </div>
    </div>

    <!-- FILTER & PRODUCTION SHIFT RECORDS TABLE -->
    <div class="glass-card rounded-3xl p-6 border border-slate-800">
        
        <!-- Filter Bar -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-5 pb-4 border-b border-slate-800">
            <div>
                <h3 class="text-sm font-bold text-white">Daily Shift Production Log</h3>
                <p class="text-xs text-slate-400">Complete records of each shift, operator, meter counters, and waste</p>
            </div>

            <form method="GET" action="{{ route('production.index') }}" class="flex items-center gap-2">
                <select name="loom_no" onchange="this.form.submit()" class="bg-slate-950 border border-slate-700 rounded-xl px-3 py-1.5 text-xs text-white">
                    <option value="">All Looms</option>
                    @foreach($looms as $loom)
                        <option value="{{ $loom }}" {{ request('loom_no') == $loom ? 'selected' : '' }}>{{ $loom }}</option>
                    @endforeach
                </select>

                <input type="date" name="date" value="{{ request('date') }}" onchange="this.form.submit()" class="bg-slate-950 border border-slate-700 rounded-xl px-3 py-1.5 text-xs text-white">

                @if(request('loom_no') || request('date'))
                    <a href="{{ route('production.index') }}" class="px-2.5 py-1.5 rounded-xl bg-slate-800 text-slate-400 hover:text-white text-xs">Clear</a>
                @endif
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="border-b border-slate-800 text-slate-400 font-semibold uppercase text-[10px] tracking-wider bg-slate-950/40">
                        <th class="p-3">Date</th>
                        <th class="p-3">Shift</th>
                        <th class="p-3">Loom #</th>
                        <th class="p-3">Operator</th>
                        <th class="p-3">Fabric Variety</th>
                        <th class="p-3 font-mono text-center">Counters (Start &rarr; End)</th>
                        <th class="p-3 text-right text-emerald-400 font-black">Net Produced</th>
                        <th class="p-3 text-right text-cyan-400 font-bold">Beam Taken</th>
                        <th class="p-3 text-right text-rose-400 font-bold bg-rose-950/20 border-x border-rose-800/40">Leakage Loss</th>
                        <th class="p-3 text-center text-indigo-400">Efficiency</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60">
                    @forelse($productions as $p)
                        <tr class="hover:bg-slate-800/30 transition-colors">
                            <td class="p-3 font-mono text-slate-400">{{ $p->production_date->format('d M, Y') }}</td>
                            <td class="p-3">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $p->shift === 'Day' ? 'bg-amber-500/20 text-amber-300' : 'bg-indigo-500/20 text-indigo-300' }}">
                                    {{ $p->shift }}
                                </span>
                            </td>
                            <td class="p-3 font-mono font-bold text-white">{{ $p->loom_no }}</td>
                            <td class="p-3 text-slate-200 font-medium">{{ $p->operator_name }}</td>
                            <td class="p-3 text-slate-300">{{ $p->fabric_quality }}</td>
                            <td class="p-3 font-mono text-center text-slate-400">
                                {{ number_format($p->start_meter, 0) }} &rarr; {{ number_format($p->end_meter, 0) }}
                            </td>
                            <td class="p-3 text-right font-mono font-black text-emerald-400 text-sm">
                                +{{ number_format($p->net_meters_produced, 0) }}m
                            </td>
                            <td class="p-3 text-right font-mono font-bold text-cyan-300">
                                {{ number_format($p->beam_meters_consumed, 0) }}m
                            </td>
                            <td class="p-3 text-right font-mono font-bold text-rose-400 bg-rose-950/20 border-x border-rose-800/40">
                                -{{ number_format($p->leakage_waste_meters, 0) }}m ({{ $p->leakage_percentage }}%)
                            </td>
                            <td class="p-3 text-center font-mono font-bold text-indigo-300">
                                {{ $p->efficiency_percentage }}%
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="p-6 text-center text-slate-500">No production entries match the criteria.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $productions->links() }}
        </div>

    </div>

</div>

<!-- MODAL: RECORD LOOM PRODUCTION (WITH REAL-TIME LEAKAGE & EFFICIENCY CALCULATIONS) -->
<div id="modalRecordProduction" class="fixed inset-0 z-50 hidden bg-slate-950/80 backdrop-blur-sm flex items-center justify-center p-4 overflow-y-auto">
    <div class="bg-slate-900 border border-slate-700/80 rounded-3xl max-w-xl w-full p-6 shadow-2xl relative my-8">
        <div class="flex items-center justify-between pb-3 border-b border-slate-800">
            <div>
                <h3 class="text-lg font-bold text-white flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-rose-400"></span>
                    Record Loom Shift Production
                </h3>
                <p class="text-xs text-slate-400">Live calculation of how much beam taken, fabric made, and leakage waste loss</p>
            </div>
            <button type="button" onclick="closeModal('modalRecordProduction')" class="text-slate-400 hover:text-white p-1">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <form action="{{ route('production.store') }}" method="POST" class="mt-4 space-y-4">
            @csrf

            <div class="grid grid-cols-3 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">Production Date *</label>
                    <input type="date" name="production_date" required value="{{ date('Y-m-d') }}" class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3 py-2 text-sm text-white focus:outline-none focus:ring-2 focus:ring-rose-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">Shift *</label>
                    <select name="shift" required class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3 py-2 text-sm text-white focus:outline-none focus:ring-2 focus:ring-rose-500">
                        <option value="Day">Day Shift</option>
                        <option value="Night">Night Shift</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">Loom # *</label>
                    <select name="loom_no" id="selectProdLoom" required class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3 py-2 text-sm text-white font-mono font-bold focus:outline-none focus:ring-2 focus:ring-rose-500">
                        @foreach($looms as $loom)
                            <option value="{{ $loom }}">{{ $loom }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">Operator / Weaver Name *</label>
                    <input type="text" name="operator_name" required placeholder="e.g. Tariq Mehmood" class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3 py-2 text-sm text-white focus:outline-none focus:ring-2 focus:ring-rose-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">Mounted Sizing Beam *</label>
                    <select name="sizing_beam_id" id="selectProdBeam" required onchange="handleBeamSelected()" class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3 py-2 text-sm text-white font-mono focus:outline-none focus:ring-2 focus:ring-rose-500">
                        <option value="">-- Choose Mounted Beam --</option>
                        @foreach($activeBeams as $bm)
                            <option value="{{ $bm->id }}" data-quality="{{ $bm->yarn_quality }}" data-remaining="{{ $bm->remaining_length_meters }}" data-loom="{{ $bm->assigned_loom_no }}">
                                {{ $bm->beam_no }} ({{ $bm->yarn_quality }}) - Remaining: {{ number_format($bm->remaining_length_meters) }}m [{{ $bm->assigned_loom_no ?? 'Ready' }}]
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 mb-1">Fabric Quality Being Woven *</label>
                <input type="text" id="inpProdQuality" name="fabric_quality" required placeholder="e.g. Cotton Sheeting 68x68 / 30x30" class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3 py-2 text-sm text-white focus:outline-none focus:ring-2 focus:ring-rose-500">
            </div>

            <!-- METER COUNTERS & CONSUMPTION -->
            <div class="p-4 rounded-2xl bg-slate-950 border border-slate-800 space-y-3">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-300 block">📊 Meter Counters & Beam Consumption</span>
                
                <div class="grid grid-cols-3 gap-3">
                    <div>
                        <label class="block text-[11px] font-semibold text-slate-400 mb-1">Starting Meter *</label>
                        <input type="number" step="0.01" id="inpStartMeter" name="start_meter" required value="0" min="0" oninput="calculateProductionLeakage()" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3 py-2 text-sm text-white font-mono focus:outline-none focus:ring-2 focus:ring-rose-500">
                    </div>
                    <div>
                        <label class="block text-[11px] font-semibold text-emerald-400 mb-1">Ending Meter *</label>
                        <input type="number" step="0.01" id="inpEndMeter" name="end_meter" required min="1" placeholder="320" oninput="calculateProductionLeakage()" class="w-full bg-slate-900 border border-emerald-700 rounded-xl px-3 py-2 text-sm text-emerald-300 font-mono font-bold focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    </div>
                    <div>
                        <label class="block text-[11px] font-semibold text-cyan-400 mb-1">Beam Consumed (m) *</label>
                        <input type="number" step="0.01" id="inpBeamConsumed" name="beam_meters_consumed" required min="1" placeholder="332" oninput="calculateProductionLeakage()" class="w-full bg-slate-900 border border-cyan-700 rounded-xl px-3 py-2 text-sm text-cyan-300 font-mono font-bold focus:outline-none focus:ring-2 focus:ring-cyan-500">
                    </div>
                </div>

                <!-- LIVE REAL-TIME LEAKAGE / WASTAGE PANEL -->
                <div class="p-3.5 rounded-xl bg-slate-900 border border-slate-800 grid grid-cols-3 gap-2 text-center">
                    <div>
                        <span class="text-[10px] text-slate-400 uppercase font-semibold block">Net Fabric Made</span>
                        <strong id="displayNetProduced" class="text-emerald-400 text-base font-black font-mono">0.00m</strong>
                    </div>
                    <div>
                        <span class="text-[10px] text-rose-400 uppercase font-semibold block">Leakage / Waste Loss</span>
                        <strong id="displayLeakageMeters" class="text-rose-400 text-base font-black font-mono">0.00m (0%)</strong>
                    </div>
                    <div>
                        <span class="text-[10px] text-indigo-400 uppercase font-semibold block">Loom Efficiency</span>
                        <strong id="displayEfficiency" class="text-indigo-400 text-base font-black font-mono">0.0%</strong>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">Loom Speed (RPM) *</label>
                    <input type="number" id="inpRpm" name="rpm" required value="350" min="100" max="800" oninput="calculateProductionLeakage()" class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3 py-2 text-sm text-white font-mono focus:outline-none focus:ring-2 focus:ring-rose-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">Picks Per Inch (PPI) *</label>
                    <input type="number" id="inpPpi" name="picks_per_inch" required value="60" min="20" max="200" oninput="calculateProductionLeakage()" class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3 py-2 text-sm text-white font-mono focus:outline-none focus:ring-2 focus:ring-rose-500">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 mb-1">Shift Notes / Stoppages Remarks</label>
                <input type="text" name="remarks" placeholder="e.g. 15 min warp stop, clean run" class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3 py-2 text-sm text-white focus:outline-none focus:ring-2 focus:ring-rose-500">
            </div>

            <div class="pt-3 border-t border-slate-800 flex justify-end gap-3">
                <button type="button" onclick="closeModal('modalRecordProduction')" class="px-4 py-2 rounded-xl bg-slate-800 text-slate-300 text-xs font-semibold">Cancel</button>
                <button type="submit" id="btnSubmitProduction" class="px-5 py-2 rounded-xl bg-gradient-to-r from-rose-600 to-amber-500 hover:from-rose-500 hover:to-amber-400 text-white text-xs font-black shadow-lg shadow-rose-600/20">Save Shift Production</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function handleBeamSelected() {
        const select = document.getElementById('selectProdBeam');
        const opt = select.options[select.selectedIndex];
        if (opt && opt.value) {
            document.getElementById('inpProdQuality').value = opt.dataset.quality || '';
            if (opt.dataset.loom && opt.dataset.loom !== 'Ready') {
                document.getElementById('selectProdLoom').value = opt.dataset.loom;
            }
        }
    }

    function calculateProductionLeakage() {
        const start = parseFloat(document.getElementById('inpStartMeter').value) || 0;
        const end = parseFloat(document.getElementById('inpEndMeter').value) || 0;
        const beamConsumed = parseFloat(document.getElementById('inpBeamConsumed').value) || 0;
        const rpm = parseFloat(document.getElementById('inpRpm').value) || 350;
        const ppi = parseFloat(document.getElementById('inpPpi').value) || 60;

        const netProduced = Math.max(0, end - start);
        const leakage = Math.max(0, beamConsumed - netProduced);
        const leakagePct = beamConsumed > 0 ? ((leakage / beamConsumed) * 100).toFixed(2) : '0.00';

        // Theoretical meters in 12hr shift
        const theoretical = (rpm * 60 * 12) / (ppi * 39.37);
        const efficiency = theoretical > 0 ? Math.min(99.9, (netProduced / theoretical) * 100).toFixed(1) : '85.0';

        document.getElementById('displayNetProduced').innerText = netProduced.toFixed(1) + 'm';
        document.getElementById('displayLeakageMeters').innerText = leakage.toFixed(1) + 'm (' + leakagePct + '%)';
        document.getElementById('displayEfficiency').innerText = efficiency + '%';

        const submitBtn = document.getElementById('btnSubmitProduction');
        if (end > start && beamConsumed > 0) {
            submitBtn.disabled = false;
            submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
        } else {
            submitBtn.disabled = true;
            submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
        }
    }
</script>
@endpush
@endsection
