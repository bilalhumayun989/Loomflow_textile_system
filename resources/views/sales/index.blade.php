@extends('layouts.app')

@section('title', 'Sales Slips & Contract Management')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-[260px_minmax(0,1fr)] gap-6 items-start">

    {{-- ============================================================
         SIDEBAR
    ============================================================ --}}
    <aside class="rounded-3xl border border-zinc-200 bg-white p-4 shadow-sm lg:sticky lg:top-24">

        {{-- Dashboard main link --}}
        <a href="{{ route('sales.index') }}"
           class="mb-4 flex items-center gap-3 rounded-2xl px-4 py-3 transition-all
                  {{ $page === 'dashboard'
                       ? 'bg-zinc-900 text-zinc-50 shadow-md'
                       : 'bg-zinc-50 text-zinc-700 border border-zinc-200 hover:bg-zinc-100 hover:text-zinc-900' }}">
            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl
                         {{ $page === 'dashboard' ? 'bg-zinc-700' : 'bg-zinc-200' }}">
                <svg class="h-5 w-5 {{ $page === 'dashboard' ? 'text-zinc-100' : 'text-zinc-600' }}"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </span>
            <div>
                <span class="block text-sm font-extrabold leading-tight">Sales Dashboard</span>
                <span class="block text-[10px] text-zinc-400 mt-0.5">Revenue overview & activity</span>
            </div>
        </a>

        <div class="mb-2 px-1 text-[10px] font-bold uppercase tracking-widest text-zinc-400">Sections</div>

        <nav class="space-y-1.5" aria-label="Sales sections">

            {{-- Sales Slips --}}
            <a href="{{ route('sales.slips') }}"
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
                    <span class="block leading-tight">Sales Slips</span>
                    <span class="block text-[10px] font-normal text-zinc-400">Invoices & dispatch log</span>
                </div>
                @if($page === 'slips')
                    <span class="ml-auto w-1.5 h-1.5 rounded-full bg-zinc-400 shrink-0"></span>
                @endif
            </a>

            {{-- Contracts --}}
            <a href="{{ route('sales.contracts') }}"
               class="flex items-center gap-3 w-full rounded-2xl px-3 py-2.5 text-xs font-bold transition-all
                      {{ $page === 'contracts'
                           ? 'bg-zinc-900 text-zinc-50 shadow-sm'
                           : 'text-zinc-600 hover:bg-zinc-100 hover:text-zinc-900' }}">
                <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg
                             {{ $page === 'contracts' ? 'bg-zinc-700' : 'bg-zinc-100' }}">
                    <svg class="h-3.5 w-3.5 {{ $page === 'contracts' ? 'text-zinc-100' : 'text-zinc-500' }}"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"/>
                    </svg>
                </span>
                <div>
                    <span class="block leading-tight">Multi-Quality Contracts</span>
                    <span class="block text-[10px] font-normal text-zinc-400">Buyer orders & delivery progress</span>
                </div>
                @if($page === 'contracts')
                    <span class="ml-auto w-1.5 h-1.5 rounded-full bg-zinc-400 shrink-0"></span>
                @endif
            </a>

        </nav>

        {{-- Revenue snapshot --}}
        @if(isset($totalRevenue))
        <div class="mt-4 rounded-2xl bg-zinc-50 border border-zinc-200 p-3 space-y-2">
            <div class="text-[10px] font-bold uppercase tracking-wider text-zinc-400">Revenue Snapshot</div>
            <div class="flex items-center justify-between text-xs">
                <span class="text-zinc-500">Open Market</span>
                <span class="font-mono font-bold text-zinc-900">Rs. {{ number_format($openMarketRevenue, 0) }}</span>
            </div>
            <div class="flex items-center justify-between text-xs">
                <span class="text-zinc-500">Contract Sales</span>
                <span class="font-mono font-bold text-zinc-600">Rs. {{ number_format($contractSalesRevenue, 0) }}</span>
            </div>
            <div class="pt-1.5 border-t border-zinc-200 flex items-center justify-between text-xs">
                <span class="text-zinc-700 font-bold">Total Revenue</span>
                <span class="font-mono font-black text-zinc-900">Rs. {{ number_format($totalRevenue, 0) }}</span>
            </div>
        </div>
        @endif

        {{-- Quick Actions --}}
        <div class="mt-5 pt-4 border-t border-zinc-100 space-y-1.5">
            <div class="px-1 text-[10px] font-bold uppercase tracking-widest text-zinc-400 mb-2">Quick Actions</div>
            <button type="button" onclick="openModal('modalNewSaleSlip')"
                    class="flex items-center gap-2 w-full rounded-xl px-3 py-2 text-[11px] font-bold text-zinc-700 hover:bg-zinc-100 transition-colors">
                <svg class="h-3.5 w-3.5 text-zinc-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Enter Sale Slip
            </button>
            <button type="button" onclick="openModal('modalNewContract')"
                    class="flex items-center gap-2 w-full rounded-xl px-3 py-2 text-[11px] font-bold text-zinc-700 hover:bg-zinc-100 transition-colors">
                <svg class="h-3.5 w-3.5 text-zinc-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                New Contract
            </button>
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
                    <span class="text-xs font-bold uppercase tracking-wider text-zinc-500">Module 05</span>
                </div>
                <h1 class="text-2xl font-black text-zinc-900 tracking-tight">
                    {{ ['dashboard' => 'Sales Dashboard', 'slips' => 'Sales Slips & Invoices', 'contracts' => 'Multi-Quality Buyer Contracts'][$page] }}
                </h1>
                <p class="text-xs text-zinc-400 mt-0.5">
                    {{ ['dashboard' => 'Open market sales, contract deliveries, and full revenue overview.', 'slips' => 'All fabric dispatch invoices — open market and contract sales.', 'contracts' => 'Long-term buyer contracts with delivery tracking and remaining meters.'][$page] }}
                </p>
            </div>
            <div class="flex items-center gap-3 shrink-0">
                <button type="button" onclick="openModal('modalNewSaleSlip')"
                        class="px-4 py-2 rounded-xl bg-zinc-900 hover:bg-zinc-700 text-zinc-50 font-extrabold text-xs shadow-sm transition-all flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    + Enter Sale Slip
                </button>
                <button type="button" onclick="openModal('modalNewContract')"
                        class="px-3.5 py-2 rounded-xl bg-zinc-100 hover:bg-zinc-200 text-zinc-700 border border-zinc-300 text-xs font-bold transition-colors flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"/></svg>
                    + New Contract
                </button>
            </div>
        </div>

        {{-- KPI Cards (dashboard only) --}}
        @if($page === 'dashboard')
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="rounded-2xl p-4 border border-zinc-300 bg-zinc-50 shadow-sm">
                <div class="text-[11px] font-bold uppercase tracking-wider text-zinc-600 flex items-center justify-between">
                    <span>Total Net Sales</span><span class="w-2 h-2 rounded-full bg-zinc-500"></span>
                </div>
                <div class="text-xl font-black text-zinc-900 mt-1 font-mono">Rs. {{ number_format($totalRevenue, 0) }}</div>
                <div class="text-[11px] text-zinc-400 mt-1">{{ number_format($totalMetersSold) }}m dispatched</div>
            </div>
            <div class="rounded-2xl p-4 border border-zinc-200 bg-white shadow-sm">
                <div class="text-[11px] font-bold uppercase tracking-wider text-zinc-500 flex items-center justify-between">
                    <span>Contract Revenue</span><span class="w-2 h-2 rounded-full bg-zinc-400"></span>
                </div>
                <div class="text-xl font-black text-zinc-900 mt-1 font-mono">Rs. {{ number_format($contractSalesRevenue, 0) }}</div>
                <div class="text-[11px] text-zinc-400 mt-1">From long-term contracts</div>
            </div>
            <div class="rounded-2xl p-4 border border-zinc-200 bg-white shadow-sm">
                <div class="text-[11px] font-bold uppercase tracking-wider text-zinc-500 flex items-center justify-between">
                    <span>Open Market Revenue</span><span class="w-2 h-2 rounded-full bg-zinc-400"></span>
                </div>
                <div class="text-xl font-black text-zinc-900 mt-1 font-mono">Rs. {{ number_format($openMarketRevenue, 0) }}</div>
                <div class="text-[11px] text-zinc-400 mt-1">Spot & cash sales</div>
            </div>
            <div class="rounded-2xl p-4 border border-zinc-200 bg-white shadow-sm">
                <div class="text-[11px] font-bold uppercase tracking-wider text-zinc-500 flex items-center justify-between">
                    <span>Active Contracts</span><span class="w-2 h-2 rounded-full bg-zinc-400"></span>
                </div>
                <div class="text-2xl font-black text-zinc-900 mt-1">{{ $activeContractsCount }}</div>
                <div class="text-[11px] text-zinc-400 mt-1 font-mono">{{ number_format($totalContractMetersDelivered) }}m / {{ number_format($totalContractMetersAgreed) }}m</div>
            </div>
        </div>
        @endif


        {{-- ============================================================
             SECTION 1 — Sales Slips Table
        ============================================================ --}}
        @if(in_array($page, ['dashboard', 'slips'], true))
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
                            {{ $page === 'dashboard' ? 'Recent Sales Slips' : 'Sales Slips & Invoices' }}
                        </h2>
                        <p class="text-[10px] text-zinc-400 mt-0.5">Fabric dispatch invoices for open market and contract sales</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    @if($page === 'dashboard')
                        <a href="{{ route('sales.slips') }}" class="text-[11px] font-bold text-zinc-500 hover:text-zinc-900 transition-colors">View all &rarr;</a>
                    @else
                        <span class="text-xs text-zinc-400 font-mono">{{ $sales->total() }} slips</span>
                    @endif
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-zinc-200 text-zinc-500 font-semibold uppercase text-[10px] tracking-wider bg-zinc-50">
                            <th class="px-4 py-3">Slip #</th>
                            <th class="px-4 py-3">Date</th>
                            <th class="px-4 py-3">Type</th>
                            <th class="px-4 py-3">Buyer Name</th>
                            <th class="px-4 py-3">Fabric Quality</th>
                            <th class="px-4 py-3 text-right">Meters Sold</th>
                            <th class="px-4 py-3 text-center">Rolls</th>
                            <th class="px-4 py-3 text-right">Rate / m</th>
                            <th class="px-4 py-3 text-right bg-zinc-900/5 border-x border-zinc-200 font-black text-zinc-800">Net Total</th>
                            <th class="px-4 py-3 text-center">Payment</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-100">
                        @forelse($sales as $s)
                            <tr class="hover:bg-zinc-50 transition-colors">
                                <td class="px-4 py-3 font-mono font-bold text-zinc-900">{{ $s->slip_no }}</td>
                                <td class="px-4 py-3 text-zinc-500 font-mono">{{ $s->sale_date->format('d M, Y') }}</td>
                                <td class="px-4 py-3">
                                    @if($s->sale_type === 'contract')
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-zinc-900 text-zinc-50">
                                            Contract
                                        </span>
                                    @else
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-zinc-100 text-zinc-600 border border-zinc-200">
                                            Open Market
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    <div class="font-bold text-zinc-900">{{ $s->buyer_name }}</div>
                                    @if($s->sale_type === 'contract' && $s->contract)
                                        <div class="text-[10px] text-zinc-400 font-mono mt-0.5">{{ $s->contract->contract_no }}</div>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-zinc-600">{{ $s->fabric_quality }}</td>
                                <td class="px-4 py-3 text-right font-mono font-bold text-zinc-900 text-sm">{{ number_format($s->meters_sold, 0) }}m</td>
                                <td class="px-4 py-3 text-center font-mono text-zinc-600">{{ $s->rolls_sold }}</td>
                                <td class="px-4 py-3 text-right font-mono text-zinc-500">Rs. {{ number_format($s->rate_per_meter, 2) }}</td>
                                <td class="px-4 py-3 text-right font-mono font-black text-zinc-900 text-sm bg-zinc-50 border-x border-zinc-200">
                                    Rs. {{ number_format($s->net_total, 0) }}
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase
                                                 {{ $s->payment_status === 'paid'
                                                      ? 'bg-zinc-900 text-zinc-50'
                                                      : ($s->payment_status === 'partial'
                                                           ? 'bg-zinc-300 text-zinc-800'
                                                           : 'bg-zinc-100 text-zinc-500 border border-zinc-200') }}">
                                        {{ $s->payment_status }}
                                    </span>
                                    <div class="text-[9px] text-zinc-400 mt-0.5">{{ $s->payment_type }}</div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="px-4 py-12 text-center">
                                    <div class="text-zinc-400 text-sm font-medium">No sales slips entered yet</div>
                                    <div class="text-zinc-300 text-xs mt-1">Click "+ Enter Sale Slip" to log your first sale</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($page === 'slips')
                <div class="px-6 py-4 border-t border-zinc-100">{{ $sales->links() }}</div>
            @endif
        </div>
        @endif


        {{-- ============================================================
             SECTION 2 — Contracts Cards
        ============================================================ --}}
        @if(in_array($page, ['dashboard', 'contracts'], true))
        <div class="rounded-3xl border border-zinc-200 bg-white shadow-sm overflow-hidden">

            <div class="flex items-center justify-between px-6 py-4 border-b border-zinc-100 bg-zinc-50">
                <div class="flex items-center gap-2.5">
                    <span class="flex h-8 w-8 items-center justify-center rounded-xl bg-zinc-900">
                        <svg class="h-4 w-4 text-zinc-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"/>
                        </svg>
                    </span>
                    <div>
                        <h2 class="text-sm font-extrabold text-zinc-900 leading-tight">
                            {{ $page === 'dashboard' ? 'Active Buyer Contracts' : 'Multi-Quality Buyer Contracts' }}
                        </h2>
                        <p class="text-[10px] text-zinc-400 mt-0.5">Delivery progress, remaining meters, and contract values</p>
                    </div>
                </div>
                @if($page === 'dashboard')
                    <a href="{{ route('sales.contracts') }}" class="text-[11px] font-bold text-zinc-500 hover:text-zinc-900 transition-colors">View all &rarr;</a>
                @endif
            </div>

            <div class="p-5 grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
                @forelse($contracts as $cnt)
                    @php
                        $pct = $cnt->total_meters > 0
                            ? min(100, round(($cnt->delivered_meters / $cnt->total_meters) * 100))
                            : 0;
                    @endphp
                    <div class="rounded-2xl border border-zinc-200 bg-zinc-50 p-4 space-y-3 hover:border-zinc-300 transition-colors">

                        {{-- Header --}}
                        <div class="flex items-start justify-between gap-2">
                            <div>
                                <span class="inline-block px-2 py-0.5 rounded-md text-[10px] font-mono font-bold bg-zinc-200 text-zinc-700 border border-zinc-300 mb-1">
                                    {{ $cnt->contract_no }}
                                </span>
                                <div class="font-extrabold text-zinc-900 leading-tight">{{ $cnt->buyer_name }}</div>
                                <div class="text-[11px] text-zinc-500 mt-0.5">{{ $cnt->fabric_quality }}</div>
                            </div>
                            <span class="shrink-0 px-2 py-0.5 rounded-full text-[10px] font-bold uppercase
                                         {{ $cnt->status === 'active'
                                              ? 'bg-zinc-900 text-zinc-50'
                                              : 'bg-zinc-200 text-zinc-500' }}">
                                {{ $cnt->status }}
                            </span>
                        </div>

                        {{-- Progress bar --}}
                        <div>
                            <div class="flex justify-between text-[11px] mb-1.5 font-semibold">
                                <span class="text-zinc-500">Delivery Progress</span>
                                <span class="text-zinc-800 font-mono font-black">{{ $pct }}%</span>
                            </div>
                            <div class="w-full bg-zinc-200 rounded-full h-2 overflow-hidden">
                                <div class="bg-zinc-800 h-2 rounded-full transition-all duration-500"
                                     style="width: {{ $pct }}%"></div>
                            </div>
                            <div class="flex justify-between text-[10px] text-zinc-400 mt-1 font-mono">
                                <span>{{ number_format($cnt->delivered_meters) }}m delivered</span>
                                <span>{{ number_format($cnt->total_meters) }}m total</span>
                            </div>
                        </div>

                        {{-- Stats --}}
                        <div class="grid grid-cols-3 gap-2 pt-2 border-t border-zinc-200 text-xs">
                            <div>
                                <span class="block text-[9px] text-zinc-400 uppercase font-bold mb-0.5">Remaining</span>
                                <strong class="text-zinc-900 font-mono text-sm">{{ number_format($cnt->remaining_meters) }}m</strong>
                            </div>
                            <div>
                                <span class="block text-[9px] text-zinc-400 uppercase font-bold mb-0.5">Rate / m</span>
                                <strong class="text-zinc-900 font-mono">Rs.&nbsp;{{ $cnt->rate_per_meter }}</strong>
                            </div>
                            <div>
                                <span class="block text-[9px] text-zinc-400 uppercase font-bold mb-0.5">Total Value</span>
                                <strong class="text-zinc-900 font-mono">Rs.&nbsp;{{ number_format($cnt->total_amount, 0) }}</strong>
                            </div>
                        </div>

                        @if($cnt->notes)
                            <div class="text-[11px] text-zinc-400 italic bg-white border border-zinc-200 px-3 py-2 rounded-xl">
                                {{ $cnt->notes }}
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="col-span-3 py-12 text-center">
                        <div class="text-zinc-400 text-sm font-medium">No buyer contracts registered yet</div>
                        <div class="text-zinc-300 text-xs mt-1">Click "+ New Contract" to register your first buyer contract</div>
                    </div>
                @endforelse
            </div>

            @if($page === 'contracts')
                <div class="px-6 py-4 border-t border-zinc-100">{{ $contracts->links() }}</div>
            @endif
        </div>
        @endif

    </div>{{-- end main --}}
</div>{{-- end grid --}}


{{-- ============================================================
     MODAL 1: ENTER SALE SLIP
============================================================ --}}
<div id="modalNewSaleSlip" class="fixed inset-0 z-50 hidden flex items-center justify-center overflow-y-auto bg-zinc-950/60 p-3 backdrop-blur-sm sm:p-6">
    <div class="my-auto max-h-[94vh] w-full max-w-2xl overflow-y-auto rounded-3xl border border-zinc-200 bg-white shadow-2xl">
        <div class="sticky top-0 z-10 flex items-center justify-between border-b border-zinc-100 bg-white/95 px-6 py-4 backdrop-blur">
            <div class="flex items-center gap-3">
                <span class="flex h-10 w-10 items-center justify-center rounded-2xl bg-zinc-900">
                    <svg class="h-5 w-5 text-zinc-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </span>
                <div>
                    <h3 class="text-base font-extrabold text-zinc-900">Create Sales Slip</h3>
                    <p class="text-xs text-zinc-400">Sale details, fabric dispatch & payment</p>
                </div>
            </div>
            <button type="button" onclick="closeModal('modalNewSaleSlip')" class="text-zinc-400 hover:text-zinc-900 p-1">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form action="{{ route('sales.store') }}" method="POST" class="px-6 py-5 space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-zinc-600 mb-1.5">Sale Dispatch Type *</label>
                <div class="grid grid-cols-2 gap-3">
                    <label class="flex items-center gap-2 p-3 rounded-2xl bg-zinc-50 border border-zinc-300 cursor-pointer has-[:checked]:border-zinc-900 has-[:checked]:bg-zinc-100">
                        <input type="radio" name="sale_type" value="open_market" checked onchange="handleSaleTypeChange()" class="text-zinc-800 focus:ring-zinc-500">
                        <div>
                            <span class="block text-xs font-bold text-zinc-900">Open Market Sale</span>
                            <span class="block text-[10px] text-zinc-400">Spot sale ex-factory</span>
                        </div>
                    </label>
                    <label class="flex items-center gap-2 p-3 rounded-2xl bg-zinc-50 border border-zinc-300 cursor-pointer has-[:checked]:border-zinc-900 has-[:checked]:bg-zinc-100">
                        <input type="radio" name="sale_type" value="contract" onchange="handleSaleTypeChange()" class="text-zinc-800 focus:ring-zinc-500">
                        <div>
                            <span class="block text-xs font-bold text-zinc-900">Contract Sale</span>
                            <span class="block text-[10px] text-zinc-400">Under buyer contract</span>
                        </div>
                    </label>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-zinc-600 mb-1">Slip / Invoice # *</label>
                    <input type="text" name="slip_no" required placeholder="e.g. SLIP-9025"
                           class="w-full bg-zinc-50 border border-zinc-300 rounded-xl px-3 py-2 text-sm text-zinc-900 focus:outline-none focus:ring-2 focus:ring-zinc-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-zinc-600 mb-1">Sale Date *</label>
                    <input type="date" name="sale_date" required value="{{ date('Y-m-d') }}"
                           class="w-full bg-zinc-50 border border-zinc-300 rounded-xl px-3 py-2 text-sm text-zinc-900 focus:outline-none focus:ring-2 focus:ring-zinc-500">
                </div>
            </div>
            <div id="contractSelectContainer" class="hidden">
                <label class="block text-xs font-semibold text-zinc-700 mb-1">Select Active Contract *</label>
                <select name="sales_contract_id" id="selectContract" onchange="handleContractSelected()"
                        class="w-full bg-zinc-50 border border-zinc-400 rounded-xl px-3 py-2 text-sm text-zinc-900 focus:outline-none focus:ring-2 focus:ring-zinc-600">
                    <option value="">— Choose Contract —</option>
                    @foreach($contractOptions as $c)
                        <option value="{{ $c->id }}" data-buyer="{{ $c->buyer_name }}" data-phone="{{ $c->buyer_phone }}"
                                data-quality="{{ $c->fabric_quality }}" data-rate="{{ $c->rate_per_meter }}"
                                data-remaining="{{ $c->remaining_meters }}">
                            {{ $c->contract_no }} — {{ $c->buyer_name }} ({{ $c->fabric_quality }}) — Remaining: {{ number_format($c->remaining_meters) }}m @ Rs.{{ $c->rate_per_meter }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-zinc-600 mb-1">Buyer / Party Name *</label>
                    <input type="text" id="inpBuyerName" name="buyer_name" required placeholder="e.g. Haji Cloth Merchant"
                           class="w-full bg-zinc-50 border border-zinc-300 rounded-xl px-3 py-2 text-sm text-zinc-900 focus:outline-none focus:ring-2 focus:ring-zinc-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-zinc-600 mb-1">Buyer Phone</label>
                    <input type="text" id="inpBuyerPhone" name="buyer_phone" placeholder="e.g. +92 300 1234567"
                           class="w-full bg-zinc-50 border border-zinc-300 rounded-xl px-3 py-2 text-sm text-zinc-900 focus:outline-none focus:ring-2 focus:ring-zinc-500">
                </div>
            </div>
            <div>
                <label class="block text-xs font-semibold text-zinc-600 mb-1">Fabric Quality *</label>
                <select name="fabric_quality" id="selectFabricQuality" required
                        class="w-full bg-zinc-50 border border-zinc-300 rounded-xl px-3 py-2 text-sm text-zinc-900 focus:outline-none focus:ring-2 focus:ring-zinc-500">
                    @foreach($fabrics as $f)
                        <option value="{{ $f->quality_name }}">{{ $f->quality_name }} (Open: {{ number_format($f->available_open_meters) }}m)</option>
                    @endforeach
                </select>
            </div>
            <div class="grid grid-cols-3 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-zinc-600 mb-1">Meters Sold *</label>
                    <input type="number" step="0.01" id="inpMetersSold" name="meters_sold" required min="1"
                           placeholder="1000" oninput="calculateSaleNet()"
                           class="w-full bg-zinc-50 border border-zinc-300 rounded-xl px-3 py-2 text-sm text-zinc-900 font-mono font-bold focus:outline-none focus:ring-2 focus:ring-zinc-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-zinc-600 mb-1">Rolls Count *</label>
                    <input type="number" name="rolls_sold" required min="1" value="10"
                           class="w-full bg-zinc-50 border border-zinc-300 rounded-xl px-3 py-2 text-sm text-zinc-900 font-mono focus:outline-none focus:ring-2 focus:ring-zinc-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-zinc-600 mb-1">Rate / Meter *</label>
                    <input type="number" step="0.01" id="inpSaleRate" name="rate_per_meter" required min="1"
                           placeholder="235" oninput="calculateSaleNet()"
                           class="w-full bg-zinc-50 border border-zinc-300 rounded-xl px-3 py-2 text-sm text-zinc-900 font-mono font-bold focus:outline-none focus:ring-2 focus:ring-zinc-500">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-zinc-600 mb-1">Discount (Rs.)</label>
                    <input type="number" step="0.01" id="inpDiscount" name="discount" value="0" min="0"
                           oninput="calculateSaleNet()"
                           class="w-full bg-zinc-50 border border-zinc-300 rounded-xl px-3 py-2 text-sm text-zinc-900 font-mono focus:outline-none focus:ring-2 focus:ring-zinc-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-zinc-600 mb-1">Payment Method & Status *</label>
                    <div class="grid grid-cols-2 gap-2">
                        <select name="payment_type" class="bg-zinc-50 border border-zinc-300 rounded-xl px-2 py-2 text-xs text-zinc-900">
                            <option value="credit">Credit</option>
                            <option value="cash">Cash</option>
                            <option value="bank_transfer">Bank</option>
                        </select>
                        <select name="payment_status" class="bg-zinc-50 border border-zinc-300 rounded-xl px-2 py-2 text-xs text-zinc-900">
                            <option value="paid">Paid</option>
                            <option value="partial">Partial</option>
                            <option value="unpaid">Unpaid</option>
                        </select>
                    </div>
                </div>
            </div>
            {{-- Live total panel --}}
            <div class="p-4 rounded-2xl bg-zinc-50 border border-zinc-200 flex items-center justify-between">
                <div>
                    <span class="text-zinc-400 block text-[11px]">Subtotal: <span id="displaySubtotal" class="font-mono text-zinc-600">Rs. 0.00</span></span>
                    <span class="text-zinc-700 font-bold text-xs">Net Total Amount</span>
                </div>
                <strong id="displayNetTotal" class="font-mono text-2xl font-black text-zinc-900">Rs. 0.00</strong>
            </div>
            <div>
                <label class="block text-xs font-semibold text-zinc-600 mb-1">Vehicle # / Gate Pass Notes</label>
                <input type="text" name="vehicle_no" placeholder="e.g. Truck LEA-4491, Gate Pass #GP-88"
                       class="w-full bg-zinc-50 border border-zinc-300 rounded-xl px-3 py-2 text-sm text-zinc-900 focus:outline-none focus:ring-2 focus:ring-zinc-500">
            </div>
            <div class="pt-3 border-t border-zinc-100 flex justify-end gap-3">
                <button type="button" onclick="closeModal('modalNewSaleSlip')"
                        class="px-4 py-2 rounded-xl bg-zinc-100 text-zinc-600 text-xs font-semibold">Cancel</button>
                <button type="submit"
                        class="px-6 py-2.5 rounded-xl bg-zinc-900 hover:bg-zinc-700 text-zinc-50 text-xs font-black shadow-sm">
                    Generate Sales Slip
                </button>
            </div>
        </form>
    </div>
</div>


{{-- ============================================================
     MODAL 2: NEW CONTRACT
============================================================ --}}
<div id="modalNewContract" class="fixed inset-0 z-50 hidden bg-zinc-900/50 backdrop-blur-sm flex items-center justify-center p-4 overflow-y-auto">
    <div class="bg-white border border-zinc-200 rounded-3xl max-w-lg w-full p-6 shadow-xl relative my-8">
        <div class="flex items-center justify-between pb-3 border-b border-zinc-100">
            <div>
                <h3 class="text-lg font-bold text-zinc-900 flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-zinc-700"></span>
                    Register Multi-Quality Contract
                </h3>
                <p class="text-xs text-zinc-400 mt-0.5">Long-term buyer contract with agreed meters and rate</p>
            </div>
            <button type="button" onclick="closeModal('modalNewContract')" class="text-zinc-400 hover:text-zinc-900 p-1">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form action="{{ route('sales.contract.store') }}" method="POST" class="mt-4 space-y-4">
            @csrf
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-zinc-600 mb-1">Contract # *</label>
                    <input type="text" name="contract_no" required placeholder="e.g. CNT-2026-045"
                           class="w-full bg-zinc-50 border border-zinc-300 rounded-xl px-3 py-2 text-sm text-zinc-900 focus:outline-none focus:ring-2 focus:ring-zinc-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-zinc-600 mb-1">Start Date *</label>
                    <input type="date" name="start_date" required value="{{ date('Y-m-d') }}"
                           class="w-full bg-zinc-50 border border-zinc-300 rounded-xl px-3 py-2 text-sm text-zinc-900 focus:outline-none focus:ring-2 focus:ring-zinc-500">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-zinc-600 mb-1">Buyer / Mill Name *</label>
                    <input type="text" name="buyer_name" required placeholder="e.g. Crescent Garments Ltd"
                           class="w-full bg-zinc-50 border border-zinc-300 rounded-xl px-3 py-2 text-sm text-zinc-900 focus:outline-none focus:ring-2 focus:ring-zinc-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-zinc-600 mb-1">Buyer Phone</label>
                    <input type="text" name="buyer_phone" placeholder="e.g. +92 321 4455667"
                           class="w-full bg-zinc-50 border border-zinc-300 rounded-xl px-3 py-2 text-sm text-zinc-900 focus:outline-none focus:ring-2 focus:ring-zinc-500">
                </div>
            </div>
            <div>
                <label class="block text-xs font-semibold text-zinc-600 mb-1">Fabric Quality *</label>
                <select name="fabric_quality" required
                        class="w-full bg-zinc-50 border border-zinc-300 rounded-xl px-3 py-2 text-sm text-zinc-900 focus:outline-none focus:ring-2 focus:ring-zinc-500">
                    @foreach($fabrics as $f)
                        <option value="{{ $f->quality_name }}">{{ $f->quality_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-zinc-600 mb-1">Total Agreed Meters *</label>
                    <input type="number" step="0.01" name="total_meters" required min="100" placeholder="10000"
                           class="w-full bg-zinc-50 border border-zinc-300 rounded-xl px-3 py-2 text-sm text-zinc-900 font-mono font-bold focus:outline-none focus:ring-2 focus:ring-zinc-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-zinc-600 mb-1">Rate Per Meter (Rs.) *</label>
                    <input type="number" step="0.01" name="rate_per_meter" required min="1" placeholder="290"
                           class="w-full bg-zinc-50 border border-zinc-300 rounded-xl px-3 py-2 text-sm text-zinc-900 font-mono font-bold focus:outline-none focus:ring-2 focus:ring-zinc-500">
                </div>
            </div>
            <div>
                <label class="block text-xs font-semibold text-zinc-600 mb-1">Expiry / Completion Deadline</label>
                <input type="date" name="end_date" value="{{ date('Y-m-d', strtotime('+30 days')) }}"
                       class="w-full bg-zinc-50 border border-zinc-300 rounded-xl px-3 py-2 text-sm text-zinc-900 focus:outline-none focus:ring-2 focus:ring-zinc-500">
            </div>
            <div>
                <label class="block text-xs font-semibold text-zinc-600 mb-1">Delivery Terms / Notes</label>
                <input type="text" name="notes" placeholder="e.g. Bi-weekly delivery of 2,500m per lot"
                       class="w-full bg-zinc-50 border border-zinc-300 rounded-xl px-3 py-2 text-sm text-zinc-900 focus:outline-none focus:ring-2 focus:ring-zinc-500">
            </div>
            <div class="pt-3 border-t border-zinc-100 flex justify-end gap-3">
                <button type="button" onclick="closeModal('modalNewContract')"
                        class="px-4 py-2 rounded-xl bg-zinc-100 text-zinc-600 text-xs font-semibold">Cancel</button>
                <button type="submit"
                        class="px-5 py-2 rounded-xl bg-zinc-900 hover:bg-zinc-700 text-zinc-50 text-xs font-bold shadow-sm">
                    Register Contract
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function handleSaleTypeChange() {
        const selected = document.querySelector('input[name="sale_type"]:checked').value;
        const cc = document.getElementById('contractSelectContainer');
        selected === 'contract' ? cc.classList.remove('hidden') : cc.classList.add('hidden');
    }

    function handleContractSelected() {
        const select = document.getElementById('selectContract');
        const opt = select.options[select.selectedIndex];
        if (opt && opt.value) {
            document.getElementById('inpBuyerName').value = opt.dataset.buyer || '';
            document.getElementById('inpBuyerPhone').value = opt.dataset.phone || '';
            document.getElementById('inpSaleRate').value = opt.dataset.rate || '';
            const qs = document.getElementById('selectFabricQuality');
            for (let i = 0; i < qs.options.length; i++) {
                if (qs.options[i].value === opt.dataset.quality) { qs.selectedIndex = i; break; }
            }
            calculateSaleNet();
        }
    }

    function calculateSaleNet() {
        const meters   = parseFloat(document.getElementById('inpMetersSold').value) || 0;
        const rate     = parseFloat(document.getElementById('inpSaleRate').value)    || 0;
        const discount = parseFloat(document.getElementById('inpDiscount').value)    || 0;
        const subtotal = meters * rate;
        const net      = Math.max(0, subtotal - discount);
        document.getElementById('displaySubtotal').innerText  = 'Rs. ' + subtotal.toLocaleString('en-US', {minimumFractionDigits: 2});
        document.getElementById('displayNetTotal').innerText  = 'Rs. ' + net.toLocaleString('en-US', {minimumFractionDigits: 0});
    }
</script>
@endpush
@endsection
