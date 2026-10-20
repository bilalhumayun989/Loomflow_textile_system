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
            <button type="button" onclick="openModal('modalYarnQualities')" class="px-3.5 py-2 rounded-xl bg-zinc-100 hover:bg-zinc-200 text-zinc-700 border border-zinc-300 text-xs font-bold transition-colors">Manage Yarn Qualities</button>
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
    @if($pendingSizingTransactions->isNotEmpty())
    <section class="overflow-hidden rounded-3xl border border-amber-200 bg-white shadow-sm">
        <div class="flex items-center justify-between border-b border-amber-100 bg-amber-50 px-6 py-4">
            <div>
                <h2 class="text-sm font-extrabold text-zinc-900">Bags Waiting for Sizing Slips</h2>
                <p class="mt-0.5 text-[10px] text-zinc-500">Choose an entry to start a slip with the sent quantity, party, quality, and date filled in.</p>
            </div>
            <span class="rounded-full bg-white px-3 py-1 text-xs font-bold text-zinc-700">{{ $pendingSizingTransactions->count() }} pending</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead><tr class="bg-zinc-50 text-[10px] font-semibold uppercase tracking-wider text-zinc-500">
                    <th class="px-4 py-3">Reference / Date</th><th class="px-4 py-3">Sizing Party</th><th class="px-4 py-3">Quality</th><th class="px-4 py-3 text-right">Bags Sent</th><th class="px-4 py-3 text-right">Weight</th><th class="px-4 py-3 text-right">Action</th>
                </tr></thead>
                <tbody class="divide-y divide-zinc-100">
                    @foreach($pendingSizingTransactions as $transaction)
                        @php
                            $sizingParty = $transaction->destination ?: ($transaction->party_name ?: $transaction->source);
                            $sizingSlipNo = 'SZ-'.$transaction->reference_no;
                        @endphp
                        <tr>
                            <td class="px-4 py-3"><span class="font-mono font-bold text-zinc-900">{{ $transaction->reference_no ?: 'Bag entry #'.$transaction->id }}</span><span class="mt-0.5 block text-[10px] text-zinc-400">{{ $transaction->transaction_date->format('d M, Y') }}</span></td>
                            <td class="px-4 py-3 font-semibold text-zinc-700">{{ $sizingParty }}</td>
                            <td class="px-4 py-3 text-zinc-600">{{ $transaction->quality }}</td>
                            <td class="px-4 py-3 text-right font-mono font-bold text-zinc-900">{{ $transaction->quantity_bags }}</td>
                            <td class="px-4 py-3 text-right font-mono text-zinc-600">{{ number_format($transaction->quantity_bags * $kilogramsPerBag, 2) }} kg<span class="block text-[10px] text-zinc-400">{{ number_format($transaction->quantity_bags * 100) }} lb</span></td>
                            <td class="px-4 py-3 text-right"><button type="button" onclick="openSizingSlipFromBag(this)" data-id="{{ $transaction->id }}" data-reference="{{ $transaction->reference_no }}" data-slip="{{ $sizingSlipNo }}" data-party="{{ $sizingParty }}" data-quality="{{ $transaction->quality }}" data-quantity="{{ $transaction->quantity_bags }}" data-date="{{ $transaction->transaction_date->format('Y-m-d') }}" class="rounded-lg bg-zinc-900 px-3 py-2 text-[11px] font-bold text-white hover:bg-zinc-700">Enter sizing slip</button></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
    @endif

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
                        <th class="px-4 py-3 text-right">Actual Woven</th>
                        <th class="px-4 py-3 text-right">Production Short / Over</th>
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
                            @php
                                $actualWovenMeters = (float) ($bm->productions_sum_net_meters_produced ?? 0);
                                $productionVarianceMeters = (float) $bm->total_length_meters - $actualWovenMeters;
                            @endphp
                            <td class="px-4 py-3 text-right font-mono font-semibold text-zinc-700">{{ number_format($actualWovenMeters, 2) }}m</td>
                            <td class="px-4 py-3 text-right font-mono {{ $productionVarianceMeters >= 0 ? 'text-amber-700' : 'text-emerald-700' }}">
                                {{ number_format(abs($productionVarianceMeters), 2) }}m {{ $productionVarianceMeters >= 0 ? 'short' : 'over' }}
                            </td>
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
                                    @if($bm->sizingSlip)
                                        <button type="button" onclick="openModal('modalBeamDetails{{ $bm->id }}')"
                                                class="px-2.5 py-1 rounded-lg border border-zinc-300 bg-white text-zinc-700 hover:bg-zinc-100 text-[11px] font-bold transition-colors">
                                            View
                                        </button>
                                    @endif
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
                        <tr><td colspan="10" class="px-4 py-10 text-center text-zinc-400">No beams generated yet. Create a Sizing Slip to generate beams.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @foreach($beams as $beamRecord)
            @if($beamRecord->sizingSlip)
                @php
                    $slipDetails = $beamRecord->sizingSlip;
                    $expectedSlipMeters = (float) $slipDetails->total_length_meters;
                    $expectedPerBeamMeters = $slipDetails->total_beams > 0 ? $expectedSlipMeters / $slipDetails->total_beams : 0;
                @endphp
                <div id="modalBeamDetails{{ $beamRecord->id }}" class="fixed inset-0 z-[60] hidden items-center justify-center overflow-y-auto bg-zinc-950/60 p-4 backdrop-blur-sm">
                    <div class="my-auto w-full max-w-3xl rounded-3xl border border-zinc-200 bg-white p-5 shadow-2xl sm:p-6">
                        <div class="flex items-start justify-between gap-4 border-b border-zinc-100 pb-4">
                            <div>
                                <p class="text-[10px] font-bold uppercase tracking-widest text-zinc-400">Sizing Slip Details</p>
                                <h2 class="mt-1 text-xl font-black text-zinc-900">{{ $slipDetails->slip_no }}</h2>
                                <p class="mt-1 text-xs text-zinc-500">{{ $slipDetails->sizing_party }} · {{ $slipDetails->slip_date?->format('d M, Y') }}</p>
                            </div>
                            <button type="button" onclick="closeModal('modalBeamDetails{{ $beamRecord->id }}')" class="rounded-lg p-2 text-zinc-400 hover:bg-zinc-100 hover:text-zinc-900" aria-label="Close beam details">✕</button>
                        </div>

                        <div class="mt-4 grid grid-cols-2 gap-3 sm:grid-cols-4">
                            <div class="rounded-xl bg-zinc-50 p-3"><span class="block text-[10px] font-bold uppercase text-zinc-400">Beams from slip</span><strong class="mt-1 block font-mono text-lg text-zinc-900">{{ $slipDetails->total_beams }}</strong></div>
                            <div class="rounded-xl bg-zinc-50 p-3"><span class="block text-[10px] font-bold uppercase text-zinc-400">Yarn quality</span><strong class="mt-1 block text-sm text-zinc-900">{{ $slipDetails->yarn_quality }}</strong></div>
                            <div class="rounded-xl bg-zinc-50 p-3"><span class="block text-[10px] font-bold uppercase text-zinc-400">Expected total</span><strong class="mt-1 block font-mono text-lg text-zinc-900">{{ number_format($expectedSlipMeters, 2) }}m</strong></div>
                            <div class="rounded-xl bg-zinc-50 p-3"><span class="block text-[10px] font-bold uppercase text-zinc-400">Expected / beam</span><strong class="mt-1 block font-mono text-lg text-zinc-900">{{ number_format($expectedPerBeamMeters, 2) }}m</strong></div>
                        </div>

                        <div class="mt-3 grid grid-cols-2 gap-3 sm:grid-cols-4">
                            <div class="rounded-xl border border-zinc-100 p-3"><span class="block text-[10px] font-bold uppercase text-zinc-400">Set length</span><strong class="mt-1 block text-sm text-zinc-800">{{ number_format($slipDetails->set_length_value ?? $expectedSlipMeters, 2) }} {{ $slipDetails->set_length_unit === 'gaz' ? 'gaz' : 'meters' }}</strong></div>
                            <div class="rounded-xl border border-zinc-100 p-3"><span class="block text-[10px] font-bold uppercase text-zinc-400">Bags allocated / used</span><strong class="mt-1 block font-mono text-sm text-zinc-800">{{ $slipDetails->bags_allocated }} / {{ $slipDetails->bags_used }}</strong></div>
                            <div class="rounded-xl border border-zinc-100 p-3"><span class="block text-[10px] font-bold uppercase text-zinc-400">Returned / held</span><strong class="mt-1 block font-mono text-sm text-zinc-800">{{ $slipDetails->bags_returned_to_factory }} / {{ $slipDetails->bags_held_with_sizing }}</strong></div>
                            <div class="rounded-xl border border-zinc-100 p-3"><span class="block text-[10px] font-bold uppercase text-zinc-400">Ends / Taarein</span><strong class="mt-1 block font-mono text-sm text-zinc-800">{{ $slipDetails->total_ends ? number_format($slipDetails->total_ends) : 'Not recorded' }}</strong></div>
                        </div>

                        @if($slipDetails->sizing_weight_pounds !== null)
                            <p class="mt-3 text-xs text-zinc-500">Sizing weight: <strong class="text-zinc-800">{{ number_format($slipDetails->sizing_weight_pounds * 0.45359237, 2) }} kg</strong> ({{ number_format($slipDetails->sizing_weight_pounds, 2) }} lb)</p>
                        @endif

                        <div class="mt-5 overflow-hidden rounded-2xl border border-zinc-200">
                            <div class="bg-zinc-50 px-4 py-3 text-xs font-extrabold text-zinc-800">All beams created from this slip</div>
                            <div class="overflow-x-auto">
                                <table class="w-full text-left text-xs">
                                    <thead class="text-[10px] uppercase tracking-wider text-zinc-500"><tr class="border-t border-zinc-200"><th class="px-4 py-2.5">Beam</th><th class="px-4 py-2.5">Status / Loom</th><th class="px-4 py-2.5 text-right">Expected</th><th class="px-4 py-2.5 text-right">Woven</th><th class="px-4 py-2.5 text-right">Remaining</th></tr></thead>
                                    <tbody class="divide-y divide-zinc-100">
                                        @foreach($slipDetails->beams as $slipBeam)
                                            <tr>
                                                <td class="px-4 py-2.5 font-mono font-bold text-zinc-900">{{ $slipBeam->beam_no }}</td>
                                                <td class="px-4 py-2.5 text-zinc-600">{{ str_replace('_', ' ', ucfirst($slipBeam->status)) }}{{ $slipBeam->assigned_loom_no ? ' · '.$slipBeam->assigned_loom_no : '' }}</td>
                                                <td class="px-4 py-2.5 text-right font-mono text-zinc-700">{{ number_format($slipBeam->total_length_meters, 2) }}m</td>
                                                <td class="px-4 py-2.5 text-right font-mono text-zinc-700">{{ number_format($slipBeam->productions_sum_net_meters_produced ?? 0, 2) }}m</td>
                                                <td class="px-4 py-2.5 text-right font-mono text-zinc-700">{{ number_format($slipBeam->remaining_length_meters, 2) }}m</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="mt-4 flex justify-end">
                            <button type="button" onclick="closeModal('modalBeamDetails{{ $beamRecord->id }}')" class="rounded-xl bg-zinc-100 px-4 py-2 text-xs font-bold text-zinc-700 hover:bg-zinc-200">Close details</button>
                        </div>
                    </div>
                </div>
            @endif
        @endforeach

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
                        <th class="px-4 py-3 text-right">Expected Production</th>
                        <th class="px-4 py-3 text-right">Expected per Beam</th>
                        <th class="px-4 py-3 text-right">Ends / Taarein</th>
                        <th class="px-4 py-3 text-right">Sizing Weight</th>
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
                            @php
                                $expectedTotalMeters = (float) $s->total_length_meters;
                                $expectedMetersPerBeam = $s->total_beams > 0 ? $expectedTotalMeters / $s->total_beams : 0;
                                $actualSlipProduction = (float) $s->beams->sum(fn ($beam) => (float) ($beam->productions_sum_net_meters_produced ?? 0));
                            @endphp
                            <td class="px-4 py-3 text-right font-mono font-bold text-zinc-900">
                                {{ number_format($expectedTotalMeters, 2) }}m
                                <span class="block text-[10px] font-normal text-zinc-400">from {{ number_format($s->set_length_value ?? $s->total_length_meters, 2) }} {{ $s->set_length_unit === 'gaz' ? 'gaz' : 'm' }}</span>
                                <span class="block text-[10px] font-normal text-zinc-500">{{ number_format($actualSlipProduction, 2) }}m woven so far</span>
                            </td>
                            <td class="px-4 py-3 text-right font-mono font-semibold text-zinc-700">
                                {{ number_format($expectedMetersPerBeam, 2) }}m
                                <span class="block text-[10px] font-normal text-zinc-400">per beam</span>
                            </td>
                            <td class="px-4 py-3 text-right font-mono text-zinc-700">{{ $s->total_ends ? number_format($s->total_ends) : '—' }}</td>
                            <td class="px-4 py-3 text-right font-mono text-zinc-700">{{ $s->sizing_weight_pounds !== null ? number_format($s->sizing_weight_pounds * 0.45359237, 2).' kg / '.number_format($s->sizing_weight_pounds, 2).' lb' : '—' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="13" class="px-4 py-10 text-center text-zinc-400">No sizing slips found. Click "+ New Sizing Slip Entry" to create one.</td></tr>
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
{{-- ============================================================
     MODAL: NEW SIZING SLIP
============================================================ --}}
<div id="modalNewSizingSlip" class="fixed inset-0 z-50 hidden flex items-center justify-center overflow-y-auto bg-zinc-950/60 p-3 backdrop-blur-sm sm:p-6">
    <div class="my-auto max-h-[94vh] w-full max-w-2xl overflow-y-auto rounded-3xl border border-zinc-200 bg-white shadow-2xl">

        {{-- Sticky header --}}
        <div class="sticky top-0 z-10 flex items-center justify-between border-b border-zinc-100 bg-white/95 px-6 py-4 backdrop-blur">
            <div class="flex items-center gap-3">
                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-2xl bg-zinc-900">
                    <svg class="h-5 w-5 text-zinc-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                </span>
                <div>
                    <h3 class="text-base font-extrabold text-zinc-900">New Sizing Slip Entry</h3>
                    <p class="text-xs text-zinc-400">Creates ready beams & tracks bag usage vs returns</p>
                </div>
            </div>
            <button type="button" onclick="closeModal('modalNewSizingSlip')"
                    class="rounded-xl p-1.5 text-zinc-400 hover:bg-zinc-100 hover:text-zinc-900 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <form action="{{ route('sizing.slip.store') }}" method="POST" class="px-6 py-5 space-y-5">
            @csrf
            <div>
                <label class="mb-1 block text-xs font-semibold text-zinc-600">Bag receipt / issue being sized *</label>
                <select id="inpBagTransactionId" name="bag_transaction_id" required onchange="populateBagTransaction(this)"
                        class="w-full rounded-xl border border-zinc-300 bg-zinc-50 px-3 py-2 text-sm text-zinc-900 focus:outline-none focus:ring-2 focus:ring-zinc-500">
                    <option value="">Select a pending bag transaction</option>
                    @foreach($pendingSizingTransactions as $transaction)
                        @php
                            $sizingParty = $transaction->destination ?: ($transaction->party_name ?: $transaction->source);
                            $sizingSlipNo = 'SZ-' . $transaction->id . '-' . date('ymd', strtotime($transaction->transaction_date));
                        @endphp
                        <option value="{{ $transaction->id }}" data-slip="{{ $sizingSlipNo }}" data-party="{{ $sizingParty }}" data-quality="{{ $transaction->quality }}" data-quantity="{{ $transaction->quantity_bags }}" data-date="{{ $transaction->transaction_date->format('Y-m-d') }}">
                            {{ $transaction->reference_no }} · {{ $transaction->quality }} · {{ $transaction->quantity_bags }} bags · {{ $sizingParty }}
                        </option>
                    @endforeach
                </select>
                <p class="mt-1 text-[10px] text-zinc-400">Each bag receipt can be used for one sizing slip. If none appear, record the yarn purchase or issue to sizing first.</p>
            </div>

            {{-- ── Section 1: Slip Basics ── --}}
            <div>
                <div class="text-[10px] font-bold uppercase tracking-widest text-zinc-400 mb-3">Slip Details</div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-zinc-600 mb-1">Sizing Slip # *</label>
                        <input type="text" id="inpSlipNo" name="slip_no" required placeholder="e.g. SZ-2026-092"
                               class="w-full bg-zinc-50 border border-zinc-300 rounded-xl px-3 py-2 text-sm text-zinc-900 focus:outline-none focus:ring-2 focus:ring-zinc-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-zinc-600 mb-1">Slip Date *</label>
                        <input type="date" id="inpSlipDate" name="slip_date" required value="{{ date('Y-m-d') }}"
                               class="w-full bg-zinc-50 border border-zinc-300 rounded-xl px-3 py-2 text-sm text-zinc-900 focus:outline-none focus:ring-2 focus:ring-zinc-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-zinc-600 mb-1">Sizing Unit / Party *</label>
                        <input type="text" id="inpSizingParty" name="sizing_party" required placeholder="e.g. National Sizing Industries"
                               class="w-full bg-zinc-50 border border-zinc-300 rounded-xl px-3 py-2 text-sm text-zinc-900 focus:outline-none focus:ring-2 focus:ring-zinc-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-zinc-600 mb-1">Yarn Quality *</label>
                        <div class="flex gap-2">
                            <select id="inpYarnQuality" name="yarn_quality" required data-yarn-quality-select
                                    class="min-w-0 flex-1 bg-zinc-50 border border-zinc-300 rounded-xl px-3 py-2 text-sm text-zinc-900 focus:outline-none focus:ring-2 focus:ring-zinc-500">
                                <option value="">Select yarn quality</option>
                                @foreach($yarnQualities as $yarnQuality)
                                    <option value="{{ $yarnQuality->name }}">{{ $yarnQuality->name }}</option>
                                @endforeach
                            </select>
                            <button type="button" onclick="openModal('modalYarnQualities')" class="shrink-0 rounded-xl border border-zinc-300 bg-white px-3 py-2 text-xs font-bold text-zinc-700 transition-colors hover:bg-zinc-100">+ Create</button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── Section 2: Bags Reconciliation ── --}}
            <div class="rounded-2xl border border-zinc-200 bg-zinc-50 overflow-hidden">
                <div class="flex items-center justify-between px-4 py-3 border-b border-zinc-200 bg-zinc-100">
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-bold uppercase tracking-wider text-zinc-700">⚖️ Bags Reconciliation</span>
                        <span class="text-[10px] text-zinc-400">Used + Returned + Held must equal Allocated</span>
                    </div>
                    <span id="sizingBagStatus" class="text-xs font-bold font-mono text-zinc-500">—</span>
                </div>
                <div class="p-4 space-y-3">
                    <div>
                        <label class="block text-xs font-semibold text-zinc-600 mb-1">Total Bags Given to Sizing *</label>
                        <input type="number" id="inpBagsAllocated" name="bags_allocated" required min="1"
                               placeholder="e.g. 75" oninput="calculateSizingBags(); updateSizingWeightPreview()"
                               class="w-full bg-white border border-zinc-300 rounded-xl px-3 py-2.5 text-base text-zinc-900 font-mono font-black focus:outline-none focus:ring-2 focus:ring-zinc-500">
                        <span id="sizingBagWeightPreview" class="mt-1 block text-[10px] text-zinc-500">100 lb per bag · 0.00 kg total</span>
                    </div>
                    <div class="grid grid-cols-3 gap-3">
                        <div class="rounded-xl border border-zinc-200 bg-white p-3">
                            <label class="block text-[10px] font-bold uppercase tracking-wider text-zinc-500 mb-2">Used in Beams *</label>
                            <input type="number" id="inpBagsUsed" name="bags_used" required min="0"
                                   placeholder="65" oninput="calculateSizingBags()"
                                   class="w-full bg-zinc-50 border border-zinc-200 rounded-lg px-2.5 py-2 text-sm text-zinc-900 font-mono font-bold focus:outline-none focus:ring-2 focus:ring-zinc-500">
                        </div>
                        <div class="rounded-xl border border-zinc-200 bg-white p-3">
                            <label class="block text-[10px] font-bold uppercase tracking-wider text-zinc-500 mb-2">Returned to Mill</label>
                            <input type="number" id="inpBagsReturned" name="bags_returned_to_factory"
                                   value="0" min="0" oninput="calculateSizingBags()"
                                   class="w-full bg-zinc-50 border border-zinc-200 rounded-lg px-2.5 py-2 text-sm text-zinc-900 font-mono font-bold focus:outline-none focus:ring-2 focus:ring-zinc-500">
                        </div>
                        <div class="rounded-xl border border-zinc-200 bg-white p-3">
                            <label class="block text-[10px] font-bold uppercase tracking-wider text-zinc-500 mb-2">Held with Sizing</label>
                            <input type="number" id="inpBagsHeld" name="bags_held_with_sizing"
                                   value="0" min="0" oninput="calculateSizingBags()"
                                   class="w-full bg-zinc-50 border border-zinc-200 rounded-lg px-2.5 py-2 text-sm text-zinc-900 font-mono font-bold focus:outline-none focus:ring-2 focus:ring-zinc-500">
                        </div>
                    </div>
                    {{-- Balance indicator bar --}}
                    <div id="bagBalanceBar" class="h-1.5 rounded-full bg-zinc-200 overflow-hidden">
                        <div id="bagBalanceFill" class="h-full rounded-full bg-zinc-300 transition-all duration-300" style="width:0%"></div>
                    </div>
                </div>
            </div>

            {{-- ── Section 3: Beam & Length Info ── --}}
            <div>
                <div class="text-[10px] font-bold uppercase tracking-widest text-zinc-400 mb-3">Beam & Warp Length</div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-zinc-600 mb-1">Total Beams Produced *</label>
                        <input type="number" id="inpTotalBeams" name="total_beams" required min="1" max="100"
                               placeholder="e.g. 4" oninput="calculateBeamLengthSplit()"
                               class="w-full bg-zinc-50 border border-zinc-300 rounded-xl px-3 py-2 text-sm text-zinc-900 font-mono font-bold focus:outline-none focus:ring-2 focus:ring-zinc-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-zinc-600 mb-1">Set Length (Gate Pass) *</label>
                        <div class="flex gap-2">
                            <input type="number" step="0.01" id="inpSetLength" name="set_length_value"
                                   required min="0.01" placeholder="e.g. 18481" oninput="calculateBeamLengthSplit()"
                                   class="min-w-0 flex-1 bg-zinc-50 border border-zinc-300 rounded-xl px-3 py-2 text-sm text-zinc-900 font-mono font-bold focus:outline-none focus:ring-2 focus:ring-zinc-500">
                            <select id="selectSetLengthUnit" name="set_length_unit" onchange="calculateBeamLengthSplit()"
                                    class="w-24 bg-zinc-50 border border-zinc-300 rounded-xl px-2 py-2 text-xs text-zinc-900 focus:outline-none focus:ring-2 focus:ring-zinc-500">
                                <option value="meters">Meters</option>
                                <option value="gaz">Gaz/yds</option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Live beam length result --}}
            <div class="mt-3 flex items-center justify-between rounded-xl bg-zinc-900 px-4 py-3">
                    <span class="text-xs text-zinc-400">Expected production:</span>
                    <strong id="displayPerBeamLength" class="font-mono text-sm text-zinc-50">Enter set length and beam count</strong>
                </div>
            </div>

            {{-- ── Section 4: Construction Details (optional) ── --}}
            <div>
                <div class="text-[10px] font-bold uppercase tracking-widest text-zinc-400 mb-3">Construction Details <span class="normal-case font-normal text-zinc-300">(optional)</span></div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-zinc-600 mb-1">Total Ends / Taarein</label>
                        <input type="number" name="total_ends" min="1" placeholder="e.g. 11550"
                               class="w-full bg-zinc-50 border border-zinc-300 rounded-xl px-3 py-2 text-sm text-zinc-900 font-mono focus:outline-none focus:ring-2 focus:ring-zinc-500">
                        <span class="mt-1 block text-[10px] text-zinc-400">Or leave blank and use Reed × Width</span>
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block text-xs font-semibold text-zinc-600 mb-1">Reed</label>
                            <input type="number" name="reed_count" min="1" placeholder="—"
                                   class="w-full bg-zinc-50 border border-zinc-300 rounded-xl px-3 py-2 text-sm text-zinc-900 font-mono focus:outline-none focus:ring-2 focus:ring-zinc-500">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-zinc-600 mb-1">Width (in)</label>
                            <input type="number" step="0.01" name="fabric_width_inches" min="0.01" placeholder="—"
                                   class="w-full bg-zinc-50 border border-zinc-300 rounded-xl px-3 py-2 text-sm text-zinc-900 font-mono focus:outline-none focus:ring-2 focus:ring-zinc-500">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-zinc-600 mb-1">Extra Selvedge Ends</label>
                        <input type="number" name="selvedge_ends" min="0" value="0"
                               class="w-full bg-zinc-50 border border-zinc-300 rounded-xl px-3 py-2 text-sm text-zinc-900 font-mono focus:outline-none focus:ring-2 focus:ring-zinc-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-zinc-600 mb-1">Sizing Bill Weight (kg)</label>
                        <input type="number" step="0.01" id="inpSizingWeightKg" name="sizing_weight_kg" min="0" placeholder="—" oninput="updateSizingWeightPreview()"
                               class="w-full bg-zinc-50 border border-zinc-300 rounded-xl px-3 py-2 text-sm text-zinc-900 font-mono focus:outline-none focus:ring-2 focus:ring-zinc-500">
                        <span id="sizingWeightPoundsPreview" class="mt-1 block text-[10px] text-zinc-500">Stored internally in pounds.</span>
                    </div>
                </div>
            </div>

            {{-- Notes --}}
            <div>
                <label class="block text-xs font-semibold text-zinc-600 mb-1">Notes</label>
                <input type="text" name="notes" placeholder="e.g. Sizing chemicals used, tension settings"
                       class="w-full bg-zinc-50 border border-zinc-300 rounded-xl px-3 py-2 text-sm text-zinc-900 focus:outline-none focus:ring-2 focus:ring-zinc-500">
            </div>

            {{-- Footer actions --}}
            <div class="pt-3 border-t border-zinc-100 flex items-center justify-between gap-3">
                <p class="text-[11px] text-zinc-400">Bags must balance before submitting</p>
                <div class="flex items-center gap-3">
                    <button type="button" onclick="closeModal('modalNewSizingSlip')"
                            class="px-4 py-2 rounded-xl bg-zinc-100 hover:bg-zinc-200 text-zinc-600 text-xs font-semibold transition-colors">
                        Cancel
                    </button>
                    <button type="submit" id="btnSubmitSizingSlip"
                            class="px-6 py-2.5 rounded-xl bg-zinc-900 hover:bg-zinc-700 text-zinc-50 text-xs font-black shadow-sm transition-colors opacity-50 cursor-not-allowed"
                            disabled>
                        Create Slip & Beams
                    </button>
                </div>
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
    function openSizingSlipFromBag(button) {
        const bagTransaction = document.getElementById('inpBagTransactionId');
        bagTransaction.value = button.dataset.id || '';
        populateBagTransaction(bagTransaction);
        const quantity = parseInt(button.dataset.quantity) || 0;
        document.getElementById('inpBagsAllocated').value = quantity;
        document.getElementById('inpBagsUsed').value = quantity;
        document.getElementById('inpBagsReturned').value = 0;
        document.getElementById('inpBagsHeld').value = 0;
        document.getElementById('inpSizingWeightKg').value = (quantity * 100 * 0.45359237).toFixed(2);
        updateSizingWeightPreview();
        calculateSizingBags();
        openModal('modalNewSizingSlip');
    }

    function populateBagTransaction(select) {
        const option = select.options[select.selectedIndex];
        if (!option || !option.value) return;

        document.getElementById('inpSlipNo').value = option.dataset.slip || '';
        document.getElementById('inpSlipDate').value = option.dataset.date || '';
        document.getElementById('inpSizingParty').value = option.dataset.party || '';
        document.getElementById('inpYarnQuality').value = option.dataset.quality || '';
        const quantity = parseInt(option.dataset.quantity) || 0;
        document.getElementById('inpBagsAllocated').value = quantity;
        document.getElementById('inpBagsUsed').value = quantity;
        document.getElementById('inpBagsReturned').value = 0;
        document.getElementById('inpBagsHeld').value = 0;
        document.getElementById('inpSizingWeightKg').value = (quantity * 100 * 0.45359237).toFixed(2);
        updateSizingWeightPreview();
        calculateSizingBags();
    }

    function updateSizingWeightPreview() {
        const allocated = parseFloat(document.getElementById('inpBagsAllocated').value) || 0;
        const enteredKilograms = parseFloat(document.getElementById('inpSizingWeightKg').value) || 0;
        document.getElementById('sizingBagWeightPreview').innerText = `100 lb per bag · ${(allocated * 100 * 0.45359237).toFixed(2)} kg total`;
        document.getElementById('sizingWeightPoundsPreview').innerText = `${enteredKilograms.toFixed(2)} kg = ${(enteredKilograms / 0.45359237).toFixed(2)} lb · stored in pounds`;
    }

    function calculateSizingBags() {
        const allocated = parseFloat(document.getElementById('inpBagsAllocated').value) || 0;
        const used = parseFloat(document.getElementById('inpBagsUsed').value) || 0;
        const returned = parseFloat(document.getElementById('inpBagsReturned').value) || 0;
        const held = parseFloat(document.getElementById('inpBagsHeld').value) || 0;
        const sum = used + returned + held;
        const statusEl = document.getElementById('sizingBagStatus');
        const submitBtn = document.getElementById('btnSubmitSizingSlip');
        const fill = document.getElementById('bagBalanceFill');

        // update progress bar
        const pct = allocated > 0 ? Math.min(100, Math.round((sum / allocated) * 100)) : 0;
        fill.style.width = pct + '%';

        if (sum === allocated && allocated > 0) {
            statusEl.innerText = `✓ Balanced: ${used}+${returned}+${held} = ${allocated}`;
            statusEl.className = 'text-xs font-bold font-mono text-zinc-700';
            fill.className = 'h-full rounded-full bg-zinc-800 transition-all duration-300';
            submitBtn.disabled = false;
            submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
        } else if (sum > allocated && allocated > 0) {
            statusEl.innerText = `✗ Over by ${sum - allocated} bags`;
            statusEl.className = 'text-xs font-bold font-mono text-zinc-500';
            fill.className = 'h-full rounded-full bg-zinc-400 transition-all duration-300';
            submitBtn.disabled = true;
            submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
        } else {
            const remaining = allocated - sum;
            statusEl.innerText = allocated > 0 ? `${remaining} bags unaccounted` : '—';
            statusEl.className = 'text-xs font-bold font-mono text-zinc-400';
            fill.className = 'h-full rounded-full bg-zinc-300 transition-all duration-300';
            submitBtn.disabled = true;
            submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
        }
    }

    function calculateBeamLengthSplit() {
        const beams = parseInt(document.getElementById('inpTotalBeams').value) || 1;
        const setLength = parseFloat(document.getElementById('inpSetLength').value) || 0;
        const unit = document.getElementById('selectSetLengthUnit').value;
        const totalLen = setLength * (unit === 'gaz' ? 0.9144 : 1);
        if (beams > 0 && totalLen > 0) {
            document.getElementById('displayPerBeamLength').innerText = `${totalLen.toFixed(2)}m total · ${(totalLen/beams).toFixed(2)}m expected per beam`;
        } else {
            document.getElementById('displayPerBeamLength').innerText = 'Enter set length';
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
@include('yarn-qualities.modal')
@endsection
