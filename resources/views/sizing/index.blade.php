@extends('layouts.app')

@section('title', 'Sizing Slips & Beam Management')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-[250px_minmax(0,1fr)] gap-6 items-start">
    <aside class="rounded-3xl border border-zinc-200 bg-white p-4 shadow-sm lg:sticky lg:top-24">

        {{-- Dashboard main link --}}
        <a href="{{ route('sizing.index') }}"
           class="mb-4 flex items-center gap-3 rounded-2xl px-4 py-3 transition-all
                  {{ $page === 'dashboard'
                       ? 'bg-zinc-900 text-zinc-50 shadow-md'
                       : 'bg-zinc-50 text-zinc-700 border border-zinc-200 hover:bg-zinc-100 hover:text-zinc-900' }}">
            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl
                         {{ $page === 'dashboard' ? 'bg-zinc-700' : 'bg-zinc-200' }}">
                <svg class="h-5 w-5 {{ $page === 'dashboard' ? 'text-zinc-100' : 'text-zinc-600' }}"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
            </span>
            <div>
                <span class="block text-sm font-extrabold leading-tight">Sizing Dashboard</span>
                <span class="block text-[10px] text-zinc-400 mt-0.5">Overview & beam stats</span>
            </div>
        </a>

        <div class="mb-2 px-1 text-[10px] font-bold uppercase tracking-widest text-zinc-400">Sections</div>

        <nav class="space-y-1.5" aria-label="Sizing sections">

            {{-- Beams Tracking --}}
            <a href="{{ route('sizing.beams') }}"
               class="flex items-center gap-3 w-full rounded-2xl px-3 py-2.5 text-xs font-bold transition-all
                      {{ $page === 'beams'
                           ? 'bg-zinc-900 text-zinc-50 shadow-sm'
                           : 'text-zinc-600 hover:bg-zinc-100 hover:text-zinc-900' }}">
                <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg
                             {{ $page === 'beams' ? 'bg-zinc-700' : 'bg-zinc-100' }}">
                    <svg class="h-3.5 w-3.5 {{ $page === 'beams' ? 'text-zinc-100' : 'text-zinc-500' }}"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7"/>
                    </svg>
                </span>
                <div>
                    <span class="block leading-tight">Beams Tracking</span>
                    <span class="block text-[10px] font-normal text-zinc-400">Loom allocation & status</span>
                </div>
                @if($page === 'beams')
                    <span class="ml-auto w-1.5 h-1.5 rounded-full bg-zinc-400 shrink-0"></span>
                @endif
            </a>

            {{-- Sizing Slips --}}
            <a href="{{ route('sizing.slips') }}"
               class="flex items-center gap-3 w-full rounded-2xl px-3 py-2.5 text-xs font-bold transition-all
                      {{ $page === 'slips'
                           ? 'bg-zinc-900 text-zinc-50 shadow-sm'
                           : 'text-zinc-600 hover:bg-zinc-100 hover:text-zinc-900' }}">
                <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg
                             {{ $page === 'slips' ? 'bg-zinc-700' : 'bg-zinc-100' }}">
                    <svg class="h-3.5 w-3.5 {{ $page === 'slips' ? 'text-zinc-100' : 'text-zinc-500' }}"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </span>
                <div>
                    <span class="block leading-tight">Sizing Slips</span>
                    <span class="block text-[10px] font-normal text-zinc-400">Bag usage & returns log</span>
                </div>
                @if($page === 'slips')
                    <span class="ml-auto w-1.5 h-1.5 rounded-full bg-zinc-400 shrink-0"></span>
                @endif
            </a>

        </nav>

        {{-- Quick actions --}}
        <div class="mt-5 pt-4 border-t border-zinc-100 space-y-1.5">
            <div class="px-1 text-[10px] font-bold uppercase tracking-widest text-zinc-400 mb-2">Quick Actions</div>
            <button type="button" onclick="openModal('modalNewSizingSlip')"
                    class="flex items-center gap-2 w-full rounded-xl px-3 py-2 text-[11px] font-bold text-zinc-700 hover:bg-zinc-100 transition-colors">
                <svg class="h-3.5 w-3.5 text-zinc-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                New Sizing Slip
            </button>
            <a href="{{ route('bags.index') }}"
               class="flex items-center gap-2 w-full rounded-xl px-3 py-2 text-[11px] font-bold text-zinc-700 hover:bg-zinc-100 transition-colors">
                <svg class="h-3.5 w-3.5 text-zinc-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
                Bags Module
            </a>
            <a href="{{ route('production.index') }}"
               class="flex items-center gap-2 w-full rounded-xl px-3 py-2 text-[11px] font-bold text-zinc-700 hover:bg-zinc-100 transition-colors">
                <svg class="h-3.5 w-3.5 text-zinc-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
                Production Module
            </a>
        </div>
    </aside>

    <div class="min-w-0 space-y-6">

    <!-- Header & Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-2 border-b border-zinc-200">
        <div>
            <div class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-zinc-700"></span>
                <span class="text-xs font-bold uppercase tracking-wider text-zinc-500">Module 02</span>
            </div>
            <h1 class="text-2xl font-black text-zinc-900 tracking-tight">{{ ['dashboard' => 'Sizing Dashboard', 'beams' => 'Beams Tracking', 'slips' => 'Sizing Slips'][$page] }}</h1>
        </div>
        <div class="flex items-center gap-3">
            <button type="button" onclick="openModal('modalNewSizingSlip')" class="px-4 py-2 rounded-xl bg-zinc-900 hover:bg-zinc-700 text-zinc-50 font-extrabold text-xs shadow-sm transition-all flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span>+ New Sizing Slip Entry</span>
            </button>
            <a href="{{ route('bags.index') }}" class="px-3.5 py-2 rounded-xl bg-zinc-100 hover:bg-zinc-200 text-zinc-700 border border-zinc-300 text-xs font-bold transition-colors flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                <span>Bags History</span>
            </a>
        </div>
    </div>

    <!-- KPIs -->
    @if ($page === 'dashboard')
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
        <div class="glass-card rounded-2xl p-4 border border-zinc-300 bg-zinc-50">
            <div class="text-[11px] font-bold uppercase tracking-wider text-zinc-700 flex items-center justify-between">
                <span>Active Beams on Looms</span><span class="w-2 h-2 rounded-full bg-zinc-700"></span>
            </div>
            <div class="text-2xl font-black text-zinc-900 mt-1">{{ $mountedBeamsCount }} <span class="text-xs font-normal text-zinc-400">mounted</span></div>
            <div class="text-[11px] text-zinc-500 mt-1 font-mono">{{ number_format($remainingBeamMetersOnLooms) }}m yarn left</div>
        </div>
        <div class="glass-card rounded-2xl p-4 border border-zinc-300 bg-zinc-50">
            <div class="text-[11px] font-bold uppercase tracking-wider text-zinc-700 flex items-center justify-between">
                <span>Beams Ready in Store</span><span class="w-2 h-2 rounded-full bg-zinc-600"></span>
            </div>
            <div class="text-2xl font-black text-zinc-900 mt-1">{{ $readyBeamsCount }} <span class="text-xs font-normal text-zinc-400">ready</span></div>
            <div class="text-[11px] text-zinc-500 mt-1">Available for mounting</div>
        </div>
        <div class="glass-card rounded-2xl p-4 border border-zinc-200">
            <div class="text-[11px] font-bold uppercase tracking-wider text-zinc-500 flex items-center justify-between">
                <span>Total Beams Produced</span><span class="w-2 h-2 rounded-full bg-zinc-400"></span>
            </div>
            <div class="text-2xl font-black text-zinc-900 mt-1">{{ $totalBeamsCount }} <span class="text-xs font-normal text-zinc-400">beams</span></div>
            <div class="text-[11px] text-zinc-400 mt-1 font-mono">{{ number_format($totalBeamMetersProduced) }} total meters</div>
        </div>
        <div class="glass-card rounded-2xl p-4 border border-zinc-300 bg-zinc-50">
            <div class="text-[11px] font-bold uppercase tracking-wider text-zinc-700 flex items-center justify-between">
                <span>Bags Consumed in Beams</span><span class="w-2 h-2 rounded-full bg-zinc-600"></span>
            </div>
            <div class="text-2xl font-black text-zinc-900 mt-1">{{ $totalUsedBags }} <span class="text-xs font-normal text-zinc-400">bags</span></div>
            <div class="text-[11px] text-zinc-500 mt-1">Out of {{ $totalAllocatedBags }} allocated</div>
        </div>
        <div class="glass-card rounded-2xl p-4 border border-zinc-300 bg-zinc-50">
            <div class="text-[11px] font-bold uppercase tracking-wider text-zinc-600 flex items-center justify-between">
                <span>Unused Bags Status</span><span class="w-2 h-2 rounded-full bg-zinc-500"></span>
            </div>
            <div class="text-xs space-y-1 mt-1 font-bold">
                <div class="flex justify-between"><span class="text-zinc-500">Returned to Factory:</span> <span class="text-zinc-800 font-mono">{{ $totalReturnedBags }} bags</span></div>
                <div class="flex justify-between"><span class="text-zinc-500">Held With Sizing:</span> <span class="text-zinc-800 font-mono">{{ $totalHeldWithSizing }} bags</span></div>
            </div>
        </div>
    </div>
    @endif

    {{-- ============================================================
         SECTION 1 — Beams Tracking
    ============================================================ --}}
    @if (in_array($page, ['dashboard', 'beams'], true))
    <div class="rounded-3xl border border-zinc-200 bg-white shadow-sm overflow-hidden">

        <div class="flex items-center justify-between px-6 py-4 border-b border-zinc-100 bg-zinc-50">
            <div class="flex items-center gap-2.5">
                <span class="flex h-8 w-8 items-center justify-center rounded-xl bg-zinc-900">
                    <svg class="h-4 w-4 text-zinc-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7"/>
                    </svg>
                </span>
                <div>
                    <h2 class="text-sm font-extrabold text-zinc-900 leading-tight">
                        {{ $page === 'dashboard' ? 'Recent Beams' : 'Beams Tracking & Loom Allocation' }}
                    </h2>
                    <p class="text-[10px] text-zinc-400 mt-0.5">Active, ready, and exhausted sizing beams with loom assignments</p>
                </div>
            </div>
            @if($page === 'dashboard')
                <a href="{{ route('sizing.beams') }}" class="text-[11px] font-bold text-zinc-500 hover:text-zinc-900 transition-colors">View all &rarr;</a>
            @endif
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="border-b border-zinc-200 text-zinc-500 font-semibold uppercase text-[10px] tracking-wider bg-zinc-50">
                        <th class="px-4 py-3">Beam #</th>
                        <th class="px-4 py-3">Sizing Slip Ref</th>
                        <th class="px-4 py-3">Yarn Quality</th>
                        <th class="px-4 py-3 text-right">Initial Length</th>
                        <th class="px-4 py-3 text-right">Remaining Length</th>
                        <th class="px-4 py-3 text-center">Assigned Loom</th>
                        <th class="px-4 py-3 text-center">Status</th>
                        <th class="px-4 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-100">
                    @forelse($beams as $bm)
                        <tr class="hover:bg-zinc-50 transition-colors">
                            <td class="px-4 py-3 font-mono font-black text-zinc-900 text-sm">{{ $bm->beam_no }}</td>
                            <td class="px-4 py-3 font-mono text-zinc-500">{{ $bm->sizingSlip ? $bm->sizingSlip->slip_no : 'Manual' }}</td>
                            <td class="px-4 py-3 font-semibold text-zinc-700">{{ $bm->yarn_quality }}</td>
                            <td class="px-4 py-3 text-right font-mono text-zinc-500">{{ number_format($bm->total_length_meters, 0) }}m</td>
                            <td class="px-4 py-3 text-right font-mono font-bold {{ $bm->remaining_length_meters < 500 ? 'text-zinc-400' : 'text-zinc-900' }}">
                                {{ number_format($bm->remaining_length_meters, 0) }}m
                                @if($bm->remaining_length_meters < 500 && $bm->status !== 'exhausted')
                                    <span class="block text-[9px] font-bold text-zinc-400 uppercase">Low</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center">
                                @if($bm->assigned_loom_no)
                                    <span class="px-2.5 py-1 rounded-xl text-xs font-mono font-black bg-zinc-900 text-zinc-50">{{ $bm->assigned_loom_no }}</span>
                                @else
                                    <span class="text-zinc-400 italic text-[11px]">Not Mounted</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center">
                                @if($bm->status === 'mounted_on_loom')
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-zinc-900 text-zinc-50 inline-flex items-center gap-1.5">
                                        <span class="w-1.5 h-1.5 rounded-full bg-zinc-300 animate-pulse"></span> Running
                                    </span>
                                @elseif($bm->status === 'ready_in_store')
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-zinc-100 text-zinc-700 border border-zinc-300">Ready in Store</span>
                                @else
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-zinc-50 text-zinc-400 border border-zinc-200">Exhausted</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    @if($bm->status !== 'exhausted')
                                        <button type="button"
                                                onclick="openMountModal('{{ $bm->id }}', '{{ $bm->beam_no }}', '{{ $bm->assigned_loom_no }}')"
                                                class="px-2.5 py-1 rounded-lg bg-zinc-900 hover:bg-zinc-700 text-zinc-50 text-[11px] font-bold transition-colors whitespace-nowrap">
                                            {{ $bm->assigned_loom_no ? 'Change Loom' : 'Mount on Loom' }}
                                        </button>
                                        <form action="{{ route('sizing.beam.exhaust', $bm->id) }}" method="POST"
                                              onsubmit="return confirm('Mark Beam {{ $bm->beam_no }} as finished?')">
                                            @csrf
                                            <button type="submit"
                                                    class="px-2.5 py-1 rounded-lg bg-zinc-100 hover:bg-zinc-200 text-zinc-500 hover:text-zinc-800 text-[11px] font-bold transition-colors">
                                                Done
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-[10px] text-zinc-300 italic">Finished</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="px-4 py-10 text-center text-zinc-400">No beams generated yet. Create a Sizing Slip to generate beams.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($page === 'beams')
            <div class="px-6 py-4 border-t border-zinc-100">{{ $beams->links() }}</div>
        @endif
    </div>
    @endif


    {{-- ============================================================
         SECTION 2 — Sizing Slips
    ============================================================ --}}
    @if (in_array($page, ['dashboard', 'slips'], true))
    <div class="rounded-3xl border border-zinc-200 bg-white shadow-sm overflow-hidden">

        <div class="flex items-center justify-between px-6 py-4 border-b border-zinc-100 bg-zinc-50">
            <div class="flex items-center gap-2.5">
                <span class="flex h-8 w-8 items-center justify-center rounded-xl bg-zinc-900">
                    <svg class="h-4 w-4 text-zinc-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </span>
                <div>
                    <h2 class="text-sm font-extrabold text-zinc-900 leading-tight">
                        {{ $page === 'dashboard' ? 'Recent Sizing Slips' : 'Sizing Slips & Bag Consumption History' }}
                    </h2>
                    <p class="text-[10px] text-zinc-400 mt-0.5">Bags allocated per slip, used in beams, returned, and held with sizing</p>
                </div>
            </div>
            @if($page === 'dashboard')
                <a href="{{ route('sizing.slips') }}" class="text-[11px] font-bold text-zinc-500 hover:text-zinc-900 transition-colors">View all &rarr;</a>
            @endif
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="border-b border-zinc-200 text-zinc-500 font-semibold uppercase text-[10px] tracking-wider bg-zinc-50">
                        <th class="px-4 py-3">Slip #</th>
                        <th class="px-4 py-3">Date</th>
                        <th class="px-4 py-3">Sizing Party</th>
                        <th class="px-4 py-3">Quality</th>
                        <th class="px-4 py-3 text-center">Bags Given</th>
                        <th class="px-4 py-3 text-center bg-zinc-900/5 text-zinc-700 border-x border-zinc-200">Used in Beams</th>
                        <th class="px-4 py-3 text-center bg-zinc-100/70 text-zinc-600 border-r border-zinc-200">Returned to Factory</th>
                        <th class="px-4 py-3 text-center bg-zinc-100/70 text-zinc-600 border-r border-zinc-200">Held With Sizing</th>
                        <th class="px-4 py-3 text-center">Beams Created</th>
                        <th class="px-4 py-3 text-right">Total Length</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-100">
                    @forelse($slips as $s)
                        <tr class="hover:bg-zinc-50 transition-colors">
                            <td class="px-4 py-3 font-mono font-bold text-zinc-900">{{ $s->slip_no }}</td>
                            <td class="px-4 py-3 text-zinc-500 font-mono">{{ $s->slip_date->format('d M, Y') }}</td>
                            <td class="px-4 py-3 font-semibold text-zinc-700">{{ $s->sizing_party }}</td>
                            <td class="px-4 py-3 text-zinc-600 font-mono">{{ $s->yarn_quality }}</td>
                            <td class="px-4 py-3 text-center font-bold text-zinc-900 font-mono">{{ $s->bags_allocated }}</td>
                            <td class="px-4 py-3 text-center font-black text-zinc-900 bg-zinc-50 border-x border-zinc-200 font-mono">{{ $s->bags_used }}</td>
                            <td class="px-4 py-3 text-center font-bold text-zinc-600 bg-zinc-50 border-r border-zinc-200 font-mono">{{ $s->bags_returned_to_factory }}</td>
                            <td class="px-4 py-3 text-center font-bold text-zinc-600 bg-zinc-50 border-r border-zinc-200 font-mono">{{ $s->bags_held_with_sizing }}</td>
                            <td class="px-4 py-3 text-center">
                                <span class="font-bold text-zinc-900">{{ $s->total_beams }}</span>
                                <span class="text-zinc-400 text-[11px]"> beams</span>
                            </td>
                            <td class="px-4 py-3 text-right font-mono font-bold text-zinc-900">{{ number_format($s->total_length_meters, 0) }}m</td>
                        </tr>
                    @empty
                        <tr><td colspan="10" class="px-4 py-10 text-center text-zinc-400">No sizing slips found. Click "+ New Sizing Slip Entry" to create one.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($page === 'slips')
            <div class="px-6 py-4 border-t border-zinc-100">{{ $slips->links() }}</div>
        @endif
    </div>
    @endif

    </div>
</div>

<!-- MODAL 1: NEW SIZING SLIP -->
<div id="modalNewSizingSlip" class="fixed inset-0 z-50 hidden bg-zinc-900/50 backdrop-blur-sm flex items-center justify-center p-4 overflow-y-auto">
    <div class="bg-white border border-zinc-200 rounded-3xl max-w-xl w-full p-6 shadow-xl relative my-8">
        <div class="flex items-center justify-between pb-3 border-b border-zinc-100">
            <div>
                <h3 class="text-lg font-bold text-zinc-900 flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-zinc-700"></span>
                    New Sizing Slip Entry
                </h3>
                <p class="text-xs text-zinc-400">Generates ready beams and tracks used vs returned bags</p>
            </div>
            <button type="button" onclick="closeModal('modalNewSizingSlip')" class="text-zinc-400 hover:text-zinc-900 p-1">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form action="{{ route('sizing.slip.store') }}" method="POST" class="mt-4 space-y-4">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-zinc-600 mb-1">Sizing Slip # *</label>
                    <input type="text" name="slip_no" required placeholder="e.g. SZ-2026-092" class="w-full bg-zinc-50 border border-zinc-300 rounded-xl px-3 py-2 text-sm text-zinc-900 focus:outline-none focus:ring-2 focus:ring-zinc-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-zinc-600 mb-1">Slip Date *</label>
                    <input type="date" name="slip_date" required value="{{ date('Y-m-d') }}" class="w-full bg-zinc-50 border border-zinc-300 rounded-xl px-3 py-2 text-sm text-zinc-900 focus:outline-none focus:ring-2 focus:ring-zinc-500">
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-zinc-600 mb-1">Sizing Unit / Party *</label>
                    <input type="text" name="sizing_party" required placeholder="e.g. National Sizing Industries" class="w-full bg-zinc-50 border border-zinc-300 rounded-xl px-3 py-2 text-sm text-zinc-900 focus:outline-none focus:ring-2 focus:ring-zinc-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-zinc-600 mb-1">Yarn Quality *</label>
                    <input type="text" name="yarn_quality" required placeholder="e.g. 30/1 PC Combed" class="w-full bg-zinc-50 border border-zinc-300 rounded-xl px-3 py-2 text-sm text-zinc-900 focus:outline-none focus:ring-2 focus:ring-zinc-500">
                </div>
            </div>
            <div class="p-4 rounded-2xl bg-zinc-50 border border-zinc-200 space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-zinc-600">⚖️ Sizing Bags Reconciliation</span>
                    <span id="sizingBagStatus" class="text-xs font-bold font-mono text-zinc-500">Total Accounted: 0</span>
                </div>
                <div>
                    <label class="block text-[11px] font-semibold text-zinc-600 mb-1">Total Bags Given to Sizing *</label>
                    <input type="number" id="inpBagsAllocated" name="bags_allocated" required min="1" placeholder="75" oninput="calculateSizingBags()" class="w-full bg-white border border-zinc-300 rounded-xl px-3 py-2 text-sm text-zinc-900 font-mono font-bold focus:outline-none focus:ring-2 focus:ring-zinc-500">
                </div>
                <div class="grid grid-cols-3 gap-2.5">
                    <div>
                        <label class="block text-[10px] font-semibold text-zinc-600 mb-1">Used in Beams *</label>
                        <input type="number" id="inpBagsUsed" name="bags_used" required min="1" placeholder="65" oninput="calculateSizingBags()" class="w-full bg-white border border-zinc-300 rounded-xl px-2.5 py-1.5 text-xs text-zinc-900 font-mono font-bold focus:outline-none focus:ring-2 focus:ring-zinc-500">
                    </div>
                    <div>
                        <label class="block text-[10px] font-semibold text-zinc-600 mb-1">Returned to Mill</label>
                        <input type="number" id="inpBagsReturned" name="bags_returned_to_factory" value="0" min="0" oninput="calculateSizingBags()" class="w-full bg-white border border-zinc-300 rounded-xl px-2.5 py-1.5 text-xs text-zinc-900 font-mono font-bold focus:outline-none focus:ring-2 focus:ring-zinc-500">
                    </div>
                    <div>
                        <label class="block text-[10px] font-semibold text-zinc-600 mb-1">Held with Sizing</label>
                        <input type="number" id="inpBagsHeld" name="bags_held_with_sizing" value="0" min="0" oninput="calculateSizingBags()" class="w-full bg-white border border-zinc-300 rounded-xl px-2.5 py-1.5 text-xs text-zinc-900 font-mono font-bold focus:outline-none focus:ring-2 focus:ring-zinc-500">
                    </div>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-zinc-600 mb-1">Total Beams Produced *</label>
                    <input type="number" id="inpTotalBeams" name="total_beams" required min="1" max="20" placeholder="4" oninput="calculateBeamLengthSplit()" class="w-full bg-zinc-50 border border-zinc-300 rounded-xl px-3 py-2 text-sm text-zinc-900 font-mono font-bold focus:outline-none focus:ring-2 focus:ring-zinc-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-zinc-600 mb-1">Total Warp Length (Meters) *</label>
                    <input type="number" step="0.01" id="inpTotalLength" name="total_length_meters" required min="100" placeholder="18000" oninput="calculateBeamLengthSplit()" class="w-full bg-zinc-50 border border-zinc-300 rounded-xl px-3 py-2 text-sm text-zinc-900 font-mono font-bold focus:outline-none focus:ring-2 focus:ring-zinc-500">
                </div>
            </div>
            <div class="p-3 rounded-xl bg-zinc-50 border border-zinc-200 text-xs text-zinc-600 flex items-center justify-between">
                <span>Calculated Beam Length:</span>
                <strong id="displayPerBeamLength" class="font-mono text-sm text-zinc-900">4,500m per beam</strong>
            </div>
            <div>
                <label class="block text-xs font-semibold text-zinc-600 mb-1">Notes</label>
                <input type="text" name="notes" placeholder="e.g. Sizing chemicals used, tension settings" class="w-full bg-zinc-50 border border-zinc-300 rounded-xl px-3 py-2 text-sm text-zinc-900 focus:outline-none focus:ring-2 focus:ring-zinc-500">
            </div>
            <div class="pt-3 border-t border-zinc-100 flex justify-end gap-3">
                <button type="button" onclick="closeModal('modalNewSizingSlip')" class="px-4 py-2 rounded-xl bg-zinc-100 text-zinc-600 text-xs font-semibold">Cancel</button>
                <button type="submit" id="btnSubmitSizingSlip" class="px-5 py-2 rounded-xl bg-zinc-900 hover:bg-zinc-700 text-zinc-50 text-xs font-black shadow-sm">Create Slip & Beams</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL 2: MOUNT BEAM ON LOOM -->
<div id="modalMountBeam" class="fixed inset-0 z-50 hidden bg-zinc-900/50 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white border border-zinc-200 rounded-3xl max-w-sm w-full p-6 shadow-xl relative">
        <div class="flex items-center justify-between pb-3 border-b border-zinc-100">
            <h3 class="text-base font-bold text-zinc-900 flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-zinc-700"></span>
                Mount Beam onto Loom
            </h3>
            <button type="button" onclick="closeModal('modalMountBeam')" class="text-zinc-400 hover:text-zinc-900 p-1">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form id="formMountBeam" method="POST" class="mt-4 space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-zinc-600 mb-1">Beam Selected</label>
                <input type="text" id="mountBeamName" readonly class="w-full bg-zinc-50 border border-zinc-200 rounded-xl px-3 py-2 text-sm font-mono font-bold text-zinc-900">
            </div>
            <div>
                <label class="block text-xs font-semibold text-zinc-600 mb-1">Assign to Loom # *</label>
                <select name="assigned_loom_no" id="selectMountLoom" required class="w-full bg-zinc-50 border border-zinc-300 rounded-xl px-3 py-2 text-sm text-zinc-900 focus:outline-none focus:ring-2 focus:ring-zinc-500">
                    @foreach($looms as $loom)
                        <option value="{{ $loom }}">{{ $loom }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-zinc-600 mb-1">Mount Date *</label>
                <input type="date" name="mounted_date" required value="{{ date('Y-m-d') }}" class="w-full bg-zinc-50 border border-zinc-300 rounded-xl px-3 py-2 text-sm text-zinc-900 focus:outline-none focus:ring-2 focus:ring-zinc-500">
            </div>
            <div class="pt-3 border-t border-zinc-100 flex justify-end gap-3">
                <button type="button" onclick="closeModal('modalMountBeam')" class="px-4 py-2 rounded-xl bg-zinc-100 text-zinc-600 text-xs font-semibold">Cancel</button>
                <button type="submit" class="px-5 py-2 rounded-xl bg-zinc-900 hover:bg-zinc-700 text-zinc-50 text-xs font-bold">Mount on Loom</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function calculateSizingBags() {
        const allocated = parseFloat(document.getElementById('inpBagsAllocated').value) || 0;
        const used = parseFloat(document.getElementById('inpBagsUsed').value) || 0;
        const returned = parseFloat(document.getElementById('inpBagsReturned').value) || 0;
        const held = parseFloat(document.getElementById('inpBagsHeld').value) || 0;
        const sum = used + returned + held;
        const statusEl = document.getElementById('sizingBagStatus');
        const submitBtn = document.getElementById('btnSubmitSizingSlip');
        if (sum === allocated && allocated > 0) {
            statusEl.innerText = `Balanced: ${used}+${returned}+${held}=${allocated} ✓`;
            statusEl.className = 'text-xs font-bold font-mono text-zinc-700';
            submitBtn.disabled = false; submitBtn.classList.remove('opacity-50','cursor-not-allowed');
        } else {
            statusEl.innerText = `Sum: ${sum}/${allocated} ✗`;
            statusEl.className = 'text-xs font-bold font-mono text-zinc-400';
            submitBtn.disabled = true; submitBtn.classList.add('opacity-50','cursor-not-allowed');
        }
    }

    function calculateBeamLengthSplit() {
        const beams = parseInt(document.getElementById('inpTotalBeams').value) || 1;
        const totalLen = parseFloat(document.getElementById('inpTotalLength').value) || 0;
        if (beams > 0 && totalLen > 0) {
            document.getElementById('displayPerBeamLength').innerText = `${beams} Beams x ${(totalLen/beams).toFixed(1)}m each`;
        }
    }

    function openMountModal(beamId, beamNo, currentLoom) {
        document.getElementById('formMountBeam').action = `/sizing/beams/${beamId}/assign`;
        document.getElementById('mountBeamName').value = beamNo;
        if (currentLoom) document.getElementById('selectMountLoom').value = currentLoom;
        openModal('modalMountBeam');
    }
</script>
@endpush
@endsection
