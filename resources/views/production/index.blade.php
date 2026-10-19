@extends('layouts.app')

@section('title', 'Loom-wise Production & Leakage Analysis')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-[260px_minmax(0,1fr)] gap-6 items-start">

    {{-- ============================================================
         SIDEBAR
    ============================================================ --}}
    <aside class="rounded-3xl border border-zinc-200 bg-white p-4 shadow-sm lg:sticky lg:top-24">

        {{-- Dashboard main link --}}
        <a href="{{ route('production.index') }}"
           class="mb-4 flex items-center gap-3 rounded-2xl px-4 py-3 transition-all
                  {{ $page === 'dashboard'
                       ? 'bg-zinc-900 text-zinc-50 shadow-md'
                       : 'bg-zinc-50 text-zinc-700 border border-zinc-200 hover:bg-zinc-100 hover:text-zinc-900' }}">
            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl
                         {{ $page === 'dashboard' ? 'bg-zinc-700' : 'bg-zinc-200' }}">
                <svg class="h-5 w-5 {{ $page === 'dashboard' ? 'text-zinc-100' : 'text-zinc-600' }}"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
            </span>
            <div>
                <span class="block text-sm font-extrabold leading-tight">Production Dashboard</span>
                <span class="block text-[10px] text-zinc-400 mt-0.5">Overview & efficiency stats</span>
            </div>
        </a>

        <div class="mb-2 px-1 text-[10px] font-bold uppercase tracking-widest text-zinc-400">Sections</div>

        <nav class="space-y-1.5" aria-label="Production sections">

            {{-- Loom-wise Summary --}}
            <a href="{{ route('production.loom-summary') }}"
               class="flex items-center gap-3 w-full rounded-2xl px-3 py-2.5 text-xs font-bold transition-all
                      {{ $page === 'loom-summary'
                           ? 'bg-zinc-900 text-zinc-50 shadow-sm'
                           : 'text-zinc-600 hover:bg-zinc-100 hover:text-zinc-900' }}">
                <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg
                             {{ $page === 'loom-summary' ? 'bg-zinc-700' : 'bg-zinc-100' }}">
                    <svg class="h-3.5 w-3.5 {{ $page === 'loom-summary' ? 'text-zinc-100' : 'text-zinc-500' }}"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </span>
                <div>
                    <span class="block leading-tight">Loom-wise Summary</span>
                    <span class="block text-[10px] font-normal text-zinc-400">Output, leakage & efficiency per loom</span>
                </div>
                @if($page === 'loom-summary')
                    <span class="ml-auto w-1.5 h-1.5 rounded-full bg-zinc-400 shrink-0"></span>
                @endif
            </a>

            {{-- Production History --}}
            <a href="{{ route('production.history') }}"
               class="flex items-center gap-3 w-full rounded-2xl px-3 py-2.5 text-xs font-bold transition-all
                      {{ $page === 'history'
                           ? 'bg-zinc-900 text-zinc-50 shadow-sm'
                           : 'text-zinc-600 hover:bg-zinc-100 hover:text-zinc-900' }}">
                <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg
                             {{ $page === 'history' ? 'bg-zinc-700' : 'bg-zinc-100' }}">
                    <svg class="h-3.5 w-3.5 {{ $page === 'history' ? 'text-zinc-100' : 'text-zinc-500' }}"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </span>
                <div>
                    <span class="block leading-tight">Production History</span>
                    <span class="block text-[10px] font-normal text-zinc-400">Shift-by-shift daily log</span>
                </div>
                @if($page === 'history')
                    <span class="ml-auto w-1.5 h-1.5 rounded-full bg-zinc-400 shrink-0"></span>
                @endif
            </a>

        </nav>

        {{-- Production stats snapshot --}}
        @if(isset($totalFabricProduced))
        <div class="mt-4 rounded-2xl bg-zinc-50 border border-zinc-200 p-3 space-y-2">
            <div class="text-[10px] font-bold uppercase tracking-wider text-zinc-400">Floor Snapshot</div>
            <div class="flex items-center justify-between text-xs">
                <span class="text-zinc-500">Fabric Made</span>
                <span class="font-mono font-bold text-zinc-900">{{ number_format($totalFabricProduced, 0) }}m</span>
            </div>
            <div class="flex items-center justify-between text-xs">
                <span class="text-zinc-500">Avg Efficiency</span>
                <span class="font-mono font-bold text-zinc-800">{{ $avgEfficiency }}%</span>
            </div>
            <div class="flex items-center justify-between text-xs">
                <span class="text-zinc-500">Avg Leakage</span>
                <span class="font-mono font-bold text-zinc-600">{{ $overallLeakagePercentage }}%</span>
            </div>
            <div class="pt-1.5 border-t border-zinc-200 flex items-center justify-between text-xs">
                <span class="text-zinc-700 font-bold">Active Looms</span>
                <span class="font-mono font-black text-zinc-900">{{ $loomWiseStats->count() }} / 24</span>
            </div>
        </div>
        @endif

        {{-- Quick Actions --}}
        <div class="mt-5 pt-4 border-t border-zinc-100 space-y-1.5">
            <div class="px-1 text-[10px] font-bold uppercase tracking-widest text-zinc-400 mb-2">Quick Actions</div>
            <button type="button" onclick="openModal('modalRecordProduction')"
                    class="flex items-center gap-2 w-full rounded-xl px-3 py-2 text-[11px] font-bold text-zinc-700 hover:bg-zinc-100 transition-colors">
                <svg class="h-3.5 w-3.5 text-zinc-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Record Production
            </button>
            <a href="{{ route('sizing.index') }}"
               class="flex items-center gap-2 w-full rounded-xl px-3 py-2 text-[11px] font-bold text-zinc-700 hover:bg-zinc-100 transition-colors">
                <svg class="h-3.5 w-3.5 text-zinc-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                Active Beams
            </a>
            <a href="{{ route('fabric.index') }}"
               class="flex items-center gap-2 w-full rounded-xl px-3 py-2 text-[11px] font-bold text-zinc-700 hover:bg-zinc-100 transition-colors">
                <svg class="h-3.5 w-3.5 text-zinc-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                </svg>
                Fabric Stock
            </a>
        </div>
    </aside>

    {{-- ============================================================
         MAIN CONTENT
    ============================================================ --}}
    <div class="min-w-0 space-y-6">

        {{-- Page Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-2 border-b border-zinc-200">
            <div>
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-zinc-700"></span>
                    <span class="text-xs font-bold uppercase tracking-wider text-zinc-500">Module 06</span>
                </div>
                <h1 class="text-2xl font-black text-zinc-900 tracking-tight">
                    {{ ['dashboard' => 'Production Dashboard', 'loom-summary' => 'Loom-wise Production Summary', 'history' => 'Production History Log'][$page] }}
                </h1>
                <p class="text-xs text-zinc-400 mt-0.5">
                    {{ ['dashboard' => 'Shift production, beam consumption, leakage waste, and efficiency overview.', 'loom-summary' => 'Per-loom breakdown of fabric output, beam consumed, and leakage loss.', 'history' => 'Complete shift-by-shift production log with meter counters and leakage data.'][$page] }}
                </p>
            </div>
            <div class="flex items-center gap-3 shrink-0">
                <button type="button" onclick="openModal('modalRecordProduction')"
                        class="px-4 py-2 rounded-xl bg-zinc-900 hover:bg-zinc-700 text-zinc-50 font-extrabold text-xs shadow-sm transition-all flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    + Record Loom Production
                </button>
                <a href="{{ route('sizing.index') }}"
                   class="px-3.5 py-2 rounded-xl bg-zinc-100 hover:bg-zinc-200 text-zinc-700 border border-zinc-300 text-xs font-bold transition-colors flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Active Beams
                </a>
            </div>
        </div>

        {{-- KPI Cards (dashboard only) --}}
        @if($page === 'dashboard')
        <div class="grid grid-cols-2 lg:grid-cols-5 gap-4">
            <div class="rounded-2xl p-4 border border-zinc-200 bg-white shadow-sm">
                <div class="text-[11px] font-bold uppercase tracking-wider text-zinc-500 flex items-center justify-between">
                    <span>Total Fabric Made</span><span class="w-2 h-2 rounded-full bg-zinc-400"></span>
                </div>
                <div class="text-xl font-black text-zinc-900 mt-1 font-mono">{{ number_format($totalFabricProduced, 0) }}<span class="text-xs font-normal text-zinc-400 ml-1">m</span></div>
                <div class="text-[11px] text-zinc-400 mt-1">Net cloth on looms</div>
            </div>
            <div class="rounded-2xl p-4 border border-zinc-200 bg-white shadow-sm">
                <div class="text-[11px] font-bold uppercase tracking-wider text-zinc-500 flex items-center justify-between">
                    <span>Beam Length Taken</span><span class="w-2 h-2 rounded-full bg-zinc-400"></span>
                </div>
                <div class="text-xl font-black text-zinc-900 mt-1 font-mono">{{ number_format($totalBeamConsumed, 0) }}<span class="text-xs font-normal text-zinc-400 ml-1">m</span></div>
                <div class="text-[11px] text-zinc-400 mt-1">Fed into looms</div>
            </div>
            <div class="rounded-2xl p-4 border border-zinc-400 bg-zinc-100 shadow-sm">
                <div class="text-[11px] font-bold uppercase tracking-wider text-zinc-700 flex items-center justify-between">
                    <span>Warp Leakage / Loss</span>
                    <span class="w-2 h-2 rounded-full bg-zinc-700 animate-pulse"></span>
                </div>
                <div class="text-xl font-black text-zinc-900 mt-1 font-mono">{{ number_format($totalLeakageMeters, 0) }}<span class="text-xs font-normal text-zinc-400 ml-1">m</span></div>
                <div class="text-[11px] text-zinc-600 font-bold mt-1">{{ $overallLeakagePercentage }}% avg leakage</div>
            </div>
            <div class="rounded-2xl p-4 border border-zinc-300 bg-zinc-50 shadow-sm">
                <div class="text-[11px] font-bold uppercase tracking-wider text-zinc-600 flex items-center justify-between">
                    <span>Floor Efficiency</span><span class="w-2 h-2 rounded-full bg-zinc-500"></span>
                </div>
                <div class="text-xl font-black text-zinc-900 mt-1 font-mono">{{ $avgEfficiency }}%</div>
                <div class="text-[11px] text-zinc-400 mt-1">RPM & picks / inch</div>
            </div>
            <div class="rounded-2xl p-4 border border-zinc-200 bg-white shadow-sm">
                <div class="text-[11px] font-bold uppercase tracking-wider text-zinc-500 flex items-center justify-between">
                    <span>Active Looms</span><span class="w-2 h-2 rounded-full bg-zinc-400"></span>
                </div>
                <div class="text-xl font-black text-zinc-900 mt-1">{{ $loomWiseStats->count() }}<span class="text-xs font-normal text-zinc-400 ml-1">of 24</span></div>
                <div class="text-[11px] text-zinc-400 mt-1">With mounted beams</div>
            </div>
        </div>
        @endif


        {{-- ============================================================
             SECTION 1 — Loom-wise Summary Cards
        ============================================================ --}}
        @if(in_array($page, ['dashboard', 'loom-summary'], true))
        <div class="rounded-3xl border border-zinc-200 bg-white shadow-sm overflow-hidden">

            <div class="flex items-center justify-between px-6 py-4 border-b border-zinc-100 bg-zinc-50">
                <div class="flex items-center gap-2.5">
                    <span class="flex h-8 w-8 items-center justify-center rounded-xl bg-zinc-900">
                        <svg class="h-4 w-4 text-zinc-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </span>
                    <div>
                        <h2 class="text-sm font-extrabold text-zinc-900 leading-tight">Loom-wise Production & Leakage Summary</h2>
                        <p class="text-[10px] text-zinc-400 mt-0.5">Output, beam consumption, and leakage % for every active loom</p>
                    </div>
                </div>
                @if($page === 'dashboard')
                    <a href="{{ route('production.loom-summary') }}" class="text-[11px] font-bold text-zinc-500 hover:text-zinc-900 transition-colors">View all &rarr;</a>
                @else
                    <span class="text-xs text-zinc-400 font-mono">{{ $loomWiseStats->count() }} looms</span>
                @endif
            </div>

            <div class="p-5 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3">
                @forelse($loomWiseStats as $stat)
                    @php
                        $leakagePct = $stat->total_beam > 0
                            ? round(($stat->total_leakage / $stat->total_beam) * 100, 1)
                            : 0;
                        $efficiency = round($stat->avg_eff, 1);
                        $effClass = $efficiency >= 85 ? 'text-zinc-900' : ($efficiency >= 70 ? 'text-zinc-600' : 'text-zinc-400');
                    @endphp
                    <div class="rounded-2xl border border-zinc-200 bg-zinc-50 p-4 space-y-3 hover:border-zinc-300 transition-colors">

                        {{-- Loom header --}}
                        <div class="flex items-center justify-between">
                            <span class="font-mono font-black text-zinc-50 text-sm px-3 py-1 rounded-xl bg-zinc-900">
                                {{ $stat->loom_no }}
                            </span>
                            <span class="text-[10px] font-bold text-zinc-400 bg-white border border-zinc-200 px-2 py-0.5 rounded-lg">
                                {{ $stat->shift_count }} shift{{ $stat->shift_count != 1 ? 's' : '' }}
                            </span>
                        </div>

                        {{-- Stats rows --}}
                        <div class="space-y-1.5 text-xs">
                            <div class="flex items-center justify-between">
                                <span class="text-zinc-500">Fabric Made</span>
                                <strong class="font-mono text-zinc-900 text-sm">{{ number_format($stat->total_produced, 0) }}m</strong>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-zinc-500">Beam Taken</span>
                                <strong class="font-mono text-zinc-600">{{ number_format($stat->total_beam, 0) }}m</strong>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-zinc-500">Leakage Loss</span>
                                <strong class="font-mono text-zinc-500">{{ number_format($stat->total_leakage, 0) }}m
                                    <span class="text-[10px] font-normal text-zinc-400">({{ $leakagePct }}%)</span>
                                </strong>
                            </div>
                        </div>

                        {{-- Efficiency bar --}}
                        <div class="pt-2 border-t border-zinc-200">
                            <div class="flex items-center justify-between mb-1.5">
                                <span class="text-[10px] text-zinc-400 uppercase font-bold">Efficiency</span>
                                <strong class="{{ $effClass }} font-mono text-sm">{{ $efficiency }}%</strong>
                            </div>
                            <div class="w-full bg-zinc-200 rounded-full h-1.5 overflow-hidden">
                                <div class="bg-zinc-800 h-1.5 rounded-full transition-all"
                                     style="width: {{ min(100, $efficiency) }}%"></div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-4 py-12 text-center">
                        <div class="text-zinc-400 text-sm font-medium">No loom production recorded yet</div>
                        <div class="text-zinc-300 text-xs mt-1">Click "+ Record Loom Production" to log your first shift</div>
                    </div>
                @endforelse
            </div>
        </div>
        @endif


        {{-- ============================================================
             SECTION 2 — Shift Production History Table
        ============================================================ --}}
        @if(in_array($page, ['dashboard', 'history'], true))
        <div class="rounded-3xl border border-zinc-200 bg-white shadow-sm overflow-hidden">

            <div class="flex items-center justify-between px-6 py-4 border-b border-zinc-100 bg-zinc-50">
                <div class="flex items-center gap-2.5">
                    <span class="flex h-8 w-8 items-center justify-center rounded-xl bg-zinc-900">
                        <svg class="h-4 w-4 text-zinc-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </span>
                    <div>
                        <h2 class="text-sm font-extrabold text-zinc-900 leading-tight">
                            {{ $page === 'dashboard' ? 'Recent Shift Production' : 'Daily Shift Production Log' }}
                        </h2>
                        <p class="text-[10px] text-zinc-400 mt-0.5">Meter counters, beam consumed, leakage loss and efficiency per shift</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    @if($page === 'dashboard')
                        <a href="{{ route('production.history') }}" class="text-[11px] font-bold text-zinc-500 hover:text-zinc-900 transition-colors">View all &rarr;</a>
                    @else
                        {{-- Filters for history page --}}
                        <form method="GET" action="{{ route('production.history') }}" class="flex items-center gap-2">
                            <select name="loom_no" onchange="this.form.submit()"
                                    class="bg-zinc-50 border border-zinc-300 rounded-xl px-3 py-1.5 text-xs text-zinc-900">
                                <option value="">All Looms</option>
                                @foreach($looms as $loom)
                                    <option value="{{ $loom }}" {{ request('loom_no') == $loom ? 'selected' : '' }}>{{ $loom }}</option>
                                @endforeach
                            </select>
                            <input type="date" name="date" value="{{ request('date') }}"
                                   onchange="this.form.submit()"
                                   class="bg-zinc-50 border border-zinc-300 rounded-xl px-3 py-1.5 text-xs text-zinc-900">
                            @if(request('loom_no') || request('date'))
                                <a href="{{ route('production.history') }}"
                                   class="px-2.5 py-1.5 rounded-xl bg-zinc-100 text-zinc-500 hover:text-zinc-900 text-xs">Clear</a>
                            @endif
                        </form>
                    @endif
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-zinc-200 text-zinc-500 font-semibold uppercase text-[10px] tracking-wider bg-zinc-50">
                            <th class="px-4 py-3">Date</th>
                            <th class="px-4 py-3">Shift</th>
                            <th class="px-4 py-3">Loom #</th>
                            <th class="px-4 py-3">Operator</th>
                            <th class="px-4 py-3">Fabric Variety</th>
                            <th class="px-4 py-3 text-center font-mono">Counters</th>
                            <th class="px-4 py-3 text-right bg-zinc-900/5 border-x border-zinc-200 font-black text-zinc-800">Net Produced</th>
                            <th class="px-4 py-3 text-right">Beam Taken</th>
                            <th class="px-4 py-3 text-right bg-zinc-100/70 border-x border-zinc-200 text-zinc-600">Leakage</th>
                            <th class="px-4 py-3 text-center">Efficiency</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-100">
                        @forelse($productions as $p)
                            <tr class="hover:bg-zinc-50 transition-colors">
                                <td class="px-4 py-3 font-mono text-zinc-500">{{ $p->production_date->format('d M, Y') }}</td>
                                <td class="px-4 py-3">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase
                                                 {{ $p->shift === 'Day' ? 'bg-zinc-200 text-zinc-700' : 'bg-zinc-900 text-zinc-50' }}">
                                        {{ $p->shift }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="font-mono font-black text-zinc-50 px-2.5 py-1 rounded-lg bg-zinc-900 text-xs">
                                        {{ $p->loom_no }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-zinc-700 font-medium">{{ $p->operator_name }}</td>
                                <td class="px-4 py-3 text-zinc-600">{{ $p->fabric_quality }}</td>
                                <td class="px-4 py-3 font-mono text-center text-zinc-500 text-[11px]">
                                    {{ number_format($p->start_meter, 0) }} &rarr; {{ number_format($p->end_meter, 0) }}
                                </td>
                                <td class="px-4 py-3 text-right font-mono font-black text-zinc-900 text-sm bg-zinc-50 border-x border-zinc-200">
                                    +{{ number_format($p->net_meters_produced, 0) }}m
                                </td>
                                <td class="px-4 py-3 text-right font-mono font-bold text-zinc-600">
                                    {{ number_format($p->beam_meters_consumed, 0) }}m
                                </td>
                                <td class="px-4 py-3 text-right font-mono text-zinc-500 bg-zinc-50 border-x border-zinc-200">
                                    <span class="font-bold">{{ number_format($p->leakage_waste_meters, 0) }}m</span>
                                    <span class="text-[10px] text-zinc-400 ml-0.5">({{ $p->leakage_percentage }}%)</span>
                                </td>
                                <td class="px-4 py-3 text-center font-mono font-black text-zinc-800">
                                    {{ $p->efficiency_percentage }}%
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="px-4 py-12 text-center">
                                    <div class="text-zinc-400 text-sm font-medium">No production entries match the criteria</div>
                                    <div class="text-zinc-300 text-xs mt-1">Click "+ Record Loom Production" to log your first shift</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($page === 'history')
                <div class="px-6 py-4 border-t border-zinc-100">{{ $productions->links() }}</div>
            @endif
        </div>
        @endif

    </div>{{-- end main --}}
</div>{{-- end grid --}}


{{-- ============================================================
     MODAL: RECORD LOOM PRODUCTION
============================================================ --}}
<div id="modalRecordProduction" class="fixed inset-0 z-50 hidden flex items-center justify-center overflow-y-auto bg-zinc-950/60 p-3 backdrop-blur-sm sm:p-6">
    <div class="my-auto max-h-[94vh] w-full max-w-2xl overflow-y-auto rounded-3xl border border-zinc-200 bg-white shadow-2xl">
        <div class="sticky top-0 z-10 flex items-center justify-between border-b border-zinc-100 bg-white/95 px-6 py-4 backdrop-blur">
            <div class="flex items-center gap-3">
                <span class="flex h-10 w-10 items-center justify-center rounded-2xl bg-zinc-900">
                    <svg class="h-5 w-5 text-zinc-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </span>
                <div>
                    <h3 class="text-base font-extrabold text-zinc-900">Record Loom Shift Production</h3>
                    <p class="text-xs text-zinc-400">Output, beam consumed, leakage & efficiency — calculated live</p>
                </div>
            </div>
            <button type="button" onclick="closeModal('modalRecordProduction')" class="text-zinc-400 hover:text-zinc-900 p-1">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <form action="{{ route('production.store') }}" method="POST" class="px-6 py-5 space-y-4">
            @csrf
            <div class="grid grid-cols-3 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-zinc-600 mb-1">Production Date *</label>
                    <input type="date" name="production_date" required value="{{ date('Y-m-d') }}"
                           class="w-full bg-zinc-50 border border-zinc-300 rounded-xl px-3 py-2 text-sm text-zinc-900 focus:outline-none focus:ring-2 focus:ring-zinc-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-zinc-600 mb-1">Shift *</label>
                    <select name="shift" required
                            class="w-full bg-zinc-50 border border-zinc-300 rounded-xl px-3 py-2 text-sm text-zinc-900 focus:outline-none focus:ring-2 focus:ring-zinc-500">
                        <option value="Day">Day Shift</option>
                        <option value="Night">Night Shift</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-zinc-600 mb-1">Loom # *</label>
                    <select name="loom_no" id="selectProdLoom" required
                            class="w-full bg-zinc-50 border border-zinc-300 rounded-xl px-3 py-2 text-sm text-zinc-900 font-mono font-bold focus:outline-none focus:ring-2 focus:ring-zinc-500">
                        @foreach($looms as $loom)
                            <option value="{{ $loom }}">{{ $loom }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-zinc-600 mb-1">Operator / Weaver Name *</label>
                    <input type="text" name="operator_name" required placeholder="e.g. Tariq Mehmood"
                           class="w-full bg-zinc-50 border border-zinc-300 rounded-xl px-3 py-2 text-sm text-zinc-900 focus:outline-none focus:ring-2 focus:ring-zinc-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-zinc-600 mb-1">Mounted Sizing Beam *</label>
                    <select name="sizing_beam_id" id="selectProdBeam" required onchange="handleBeamSelected()"
                            class="w-full bg-zinc-50 border border-zinc-300 rounded-xl px-3 py-2 text-sm text-zinc-900 font-mono focus:outline-none focus:ring-2 focus:ring-zinc-500">
                        <option value="">— Choose Mounted Beam —</option>
                        @foreach($activeBeams as $bm)
                            <option value="{{ $bm->id }}"
                                    data-quality="{{ $bm->yarn_quality }}"
                                    data-remaining="{{ $bm->remaining_length_meters }}"
                                    data-loom="{{ $bm->assigned_loom_no }}">
                                {{ $bm->beam_no }} ({{ $bm->yarn_quality }}) — {{ number_format($bm->remaining_length_meters) }}m left [{{ $bm->assigned_loom_no ?? 'Ready' }}]
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-zinc-600 mb-1">Fabric Quality Being Woven *</label>
                <input type="text" id="inpProdQuality" name="fabric_quality" required
                       placeholder="e.g. Cotton Sheeting 68x68 / 30x30"
                       class="w-full bg-zinc-50 border border-zinc-300 rounded-xl px-3 py-2 text-sm text-zinc-900 focus:outline-none focus:ring-2 focus:ring-zinc-500">
            </div>

            {{-- Meter counters panel --}}
            <div class="p-4 rounded-2xl bg-zinc-50 border border-zinc-200 space-y-3">
                <span class="text-xs font-bold uppercase tracking-wider text-zinc-600 block">📊 Meter Counters & Beam Consumption</span>
                <div class="grid grid-cols-3 gap-3">
                    <div>
                        <label class="block text-[11px] font-semibold text-zinc-500 mb-1">Starting Meter *</label>
                        <input type="number" step="0.01" id="inpStartMeter" name="start_meter"
                               required value="0" min="0" oninput="calculateProductionLeakage()"
                               class="w-full bg-white border border-zinc-300 rounded-xl px-3 py-2 text-sm text-zinc-900 font-mono focus:outline-none focus:ring-2 focus:ring-zinc-500">
                    </div>
                    <div>
                        <label class="block text-[11px] font-semibold text-zinc-700 mb-1">Ending Meter *</label>
                        <input type="number" step="0.01" id="inpEndMeter" name="end_meter"
                               required min="1" placeholder="320" oninput="calculateProductionLeakage()"
                               class="w-full bg-white border border-zinc-400 rounded-xl px-3 py-2 text-sm text-zinc-900 font-mono font-bold focus:outline-none focus:ring-2 focus:ring-zinc-600">
                    </div>
                    <div>
                        <label class="block text-[11px] font-semibold text-zinc-700 mb-1">Beam Consumed (m) *</label>
                        <input type="number" step="0.01" id="inpBeamConsumed" name="beam_meters_consumed"
                               required min="1" placeholder="332" oninput="calculateProductionLeakage()"
                               class="w-full bg-white border border-zinc-400 rounded-xl px-3 py-2 text-sm text-zinc-900 font-mono font-bold focus:outline-none focus:ring-2 focus:ring-zinc-600">
                    </div>
                </div>

                {{-- Live calculation panel --}}
                <div class="grid grid-cols-3 gap-3 p-3.5 rounded-xl bg-white border border-zinc-200 text-center">
                    <div>
                        <span class="text-[10px] text-zinc-500 uppercase font-semibold block mb-1">Net Fabric Made</span>
                        <strong id="displayNetProduced" class="text-zinc-900 text-lg font-black font-mono">0m</strong>
                    </div>
                    <div class="border-x border-zinc-100">
                        <span class="text-[10px] text-zinc-600 uppercase font-semibold block mb-1">Leakage / Waste</span>
                        <strong id="displayLeakageMeters" class="text-zinc-600 text-lg font-black font-mono">0m (0%)</strong>
                    </div>
                    <div>
                        <span class="text-[10px] text-zinc-500 uppercase font-semibold block mb-1">Loom Efficiency</span>
                        <strong id="displayEfficiency" class="text-zinc-800 text-lg font-black font-mono">0%</strong>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-zinc-600 mb-1">Loom Speed (RPM) *</label>
                    <input type="number" id="inpRpm" name="rpm" required value="350" min="100" max="800"
                           oninput="calculateProductionLeakage()"
                           class="w-full bg-zinc-50 border border-zinc-300 rounded-xl px-3 py-2 text-sm text-zinc-900 font-mono focus:outline-none focus:ring-2 focus:ring-zinc-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-zinc-600 mb-1">Picks Per Inch (PPI) *</label>
                    <input type="number" id="inpPpi" name="picks_per_inch" required value="60" min="20" max="200"
                           oninput="calculateProductionLeakage()"
                           class="w-full bg-zinc-50 border border-zinc-300 rounded-xl px-3 py-2 text-sm text-zinc-900 font-mono focus:outline-none focus:ring-2 focus:ring-zinc-500">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-zinc-600 mb-1">Shift Notes / Stoppages Remarks</label>
                <input type="text" name="remarks" placeholder="e.g. 15 min warp stop, clean run"
                       class="w-full bg-zinc-50 border border-zinc-300 rounded-xl px-3 py-2 text-sm text-zinc-900 focus:outline-none focus:ring-2 focus:ring-zinc-500">
            </div>

            <div class="pt-3 border-t border-zinc-100 flex justify-end gap-3">
                <button type="button" onclick="closeModal('modalRecordProduction')"
                        class="px-4 py-2 rounded-xl bg-zinc-100 text-zinc-600 text-xs font-semibold">Cancel</button>
                <button type="submit" id="btnSubmitProduction"
                        class="px-6 py-2.5 rounded-xl bg-zinc-900 hover:bg-zinc-700 text-zinc-50 text-xs font-black shadow-sm opacity-50 cursor-not-allowed"
                        disabled>
                    Save Shift Production
                </button>
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
        const start        = parseFloat(document.getElementById('inpStartMeter').value)    || 0;
        const end          = parseFloat(document.getElementById('inpEndMeter').value)       || 0;
        const beamConsumed = parseFloat(document.getElementById('inpBeamConsumed').value)   || 0;
        const rpm          = parseFloat(document.getElementById('inpRpm').value)            || 350;
        const ppi          = parseFloat(document.getElementById('inpPpi').value)            || 60;

        const netProduced  = Math.max(0, end - start);
        const leakage      = Math.max(0, beamConsumed - netProduced);
        const leakagePct   = beamConsumed > 0 ? ((leakage / beamConsumed) * 100).toFixed(1) : '0.0';
        const theoretical  = (rpm * 60 * 12) / (ppi * 39.37);
        const efficiency   = theoretical > 0 ? Math.min(99.9, (netProduced / theoretical) * 100).toFixed(1) : '0.0';

        document.getElementById('displayNetProduced').innerText    = netProduced.toFixed(0) + 'm';
        document.getElementById('displayLeakageMeters').innerText  = leakage.toFixed(0) + 'm (' + leakagePct + '%)';
        document.getElementById('displayEfficiency').innerText     = efficiency + '%';

        const btn = document.getElementById('btnSubmitProduction');
        if (end > start && beamConsumed > 0) {
            btn.disabled = false;
            btn.classList.remove('opacity-50', 'cursor-not-allowed');
        } else {
            btn.disabled = true;
            btn.classList.add('opacity-50', 'cursor-not-allowed');
        }
    }
</script>
@endpush
@endsection
