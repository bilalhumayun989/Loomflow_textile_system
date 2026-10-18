@extends('layouts.app')

@section('title', 'Main Operations Hub')

@section('content')
<div class="space-y-8">
    
    <!-- Top Welcome Banner & Shift Stats -->
    <div class="relative overflow-hidden rounded-3xl bg-white border border-zinc-200 p-6 sm:p-8 shadow-sm">
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-zinc-100 border border-zinc-300 text-zinc-600 text-xs font-semibold mb-3">
                    <span class="w-2 h-2 rounded-full bg-zinc-500 animate-pulse"></span>
                    Weaving Floor Status: Active &bull; {{ date('l, d M Y') }}
                </div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-zinc-900 tracking-tight">
                    Welcome back, {{ Auth::user()->name }}
                </h1>
                <p class="text-sm text-zinc-500 mt-1 max-w-2xl">
                    Select any operation box below to manage yarn bags, sizing beam preparation, spare parts store, finished fabric, sales slips, and loom-wise production efficiency.
                </p>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('production.index') }}" class="px-4 py-2.5 rounded-xl bg-zinc-900 hover:bg-zinc-700 text-zinc-50 font-bold text-xs shadow-sm transition-all flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span>Record Production</span>
                </a>
                <a href="{{ route('bags.index') }}" class="px-4 py-2.5 rounded-xl bg-zinc-100 hover:bg-zinc-200 text-zinc-800 border border-zinc-300 font-bold text-xs transition-colors flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    <span>New Yarn Purchase</span>
                </a>
            </div>
        </div>

        <!-- Quick Summary Ribbon -->
        <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-6 gap-3 mt-6 pt-6 border-t border-zinc-100">
            <div class="p-3 rounded-2xl bg-zinc-50 border border-zinc-200">
                <div class="text-[10px] uppercase font-bold text-zinc-500">Factory Yarn Store</div>
                <div class="text-lg font-black text-zinc-900 mt-0.5">{{ number_format($factoryBagStock) }} <span class="text-xs font-normal text-zinc-400">bags</span></div>
            </div>
            <div class="p-3 rounded-2xl bg-zinc-50 border border-zinc-200">
                <div class="text-[10px] uppercase font-bold text-zinc-500">Sizing Persons Stock</div>
                <div class="text-lg font-black text-zinc-900 mt-0.5">{{ number_format($sizingBagsInHand) }} <span class="text-xs font-normal text-zinc-400">bags</span></div>
            </div>
            <div class="p-3 rounded-2xl bg-zinc-50 border border-zinc-200">
                <div class="text-[10px] uppercase font-bold text-zinc-500">Looms Mounted Beams</div>
                <div class="text-lg font-black text-zinc-900 mt-0.5">{{ $activeBeamsCount }} <span class="text-xs font-normal text-zinc-400">active</span></div>
            </div>
            <div class="p-3 rounded-2xl bg-zinc-50 border border-zinc-200">
                <div class="text-[10px] uppercase font-bold text-zinc-500">Fabric In Stock</div>
                <div class="text-lg font-black text-zinc-900 mt-0.5">{{ number_format($totalFabricStockMeters) }} <span class="text-xs font-normal text-zinc-400">meters</span></div>
            </div>
            <div class="p-3 rounded-2xl bg-zinc-50 border border-zinc-200">
                <div class="text-[10px] uppercase font-bold text-zinc-500">Open Sale Ready</div>
                <div class="text-lg font-black text-zinc-900 mt-0.5">{{ number_format($openFabricMeters) }} <span class="text-xs font-normal text-zinc-400">meters</span></div>
            </div>
            <div class="p-3 rounded-2xl bg-zinc-50 border border-zinc-200">
                <div class="text-[10px] uppercase font-bold text-zinc-500">Avg Efficiency</div>
                <div class="text-lg font-black text-zinc-900 mt-0.5">{{ $avgEfficiency }}%</div>
            </div>
        </div>
    </div>

    <!-- THE 6 MAIN BOXES -->
    <div>
        <div class="flex items-center justify-between mb-4">
            <div>
                <h2 class="text-lg font-extrabold text-zinc-900 tracking-tight flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-zinc-700"></span>
                    Core Operation Modules (6 Boxes)
                </h2>
                <p class="text-xs text-zinc-400">Click any box to open its dedicated workbench, forms, and logs</p>
            </div>
            <div class="text-xs text-zinc-400 font-mono">Click box to launch &rarr;</div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

            <!-- BOX 1: BAGS -->
            <a href="{{ route('bags.index') }}" class="glass-card glass-card-hover rounded-3xl p-6 relative group overflow-hidden border border-zinc-200 bg-white">
                <div class="flex items-start justify-between">
                    <div class="w-14 h-14 rounded-2xl bg-zinc-100 border border-zinc-300 text-zinc-700 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                    </div>
                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-zinc-100 border border-zinc-300 text-zinc-600">Module 01</span>
                </div>
                <div class="mt-5">
                    <h3 class="text-2xl font-black text-zinc-900 group-hover:text-zinc-600 transition-colors">Bags</h3>
                    <p class="text-xs text-zinc-400 mt-1 line-clamp-2">
                        Yarn Bag purchases split between Sizing & Factory Store. Daily store issuance, return ledger, and unused bag tracking.
                    </p>
                </div>
                <div class="mt-6 pt-4 border-t border-zinc-100 grid grid-cols-2 gap-2 text-xs">
                    <div>
                        <span class="text-[10px] uppercase text-zinc-400 font-semibold block">Factory Godown</span>
                        <strong class="text-zinc-900 text-sm font-bold">{{ number_format($factoryBagStock) }} Bags</strong>
                    </div>
                    <div>
                        <span class="text-[10px] uppercase text-zinc-400 font-semibold block">At Sizing Mills</span>
                        <strong class="text-zinc-600 text-sm font-bold">{{ number_format($sizingBagsInHand) }} Bags</strong>
                    </div>
                </div>
                <div class="mt-4 flex items-center justify-between text-xs font-bold text-zinc-500 group-hover:translate-x-1 transition-transform">
                    <span>Manage Bags & Purchases</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </div>
            </a>

            <!-- BOX 2: SIZING -->
            <a href="{{ route('sizing.index') }}" class="glass-card glass-card-hover rounded-3xl p-6 relative group overflow-hidden border border-zinc-200 bg-white">
                <div class="flex items-start justify-between">
                    <div class="w-14 h-14 rounded-2xl bg-zinc-100 border border-zinc-300 text-zinc-700 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                    </div>
                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-zinc-100 border border-zinc-300 text-zinc-600">Module 02</span>
                </div>
                <div class="mt-5">
                    <h3 class="text-2xl font-black text-zinc-900 group-hover:text-zinc-600 transition-colors">Sizing</h3>
                    <p class="text-xs text-zinc-400 mt-1 line-clamp-2">
                        Sizing slip entry, beam generation, and loom-wise beam allocation. Track bags used in beams vs returned to factory or held.
                    </p>
                </div>
                <div class="mt-6 pt-4 border-t border-zinc-100 grid grid-cols-2 gap-2 text-xs">
                    <div>
                        <span class="text-[10px] uppercase text-zinc-400 font-semibold block">Beams on Looms</span>
                        <strong class="text-zinc-900 text-sm font-bold">{{ $activeBeamsCount }} Active</strong>
                    </div>
                    <div>
                        <span class="text-[10px] uppercase text-zinc-400 font-semibold block">Ready in Store</span>
                        <strong class="text-zinc-600 text-sm font-bold">{{ $readyBeamsCount }} Beams</strong>
                    </div>
                </div>
                <div class="mt-4 flex items-center justify-between text-xs font-bold text-zinc-500 group-hover:translate-x-1 transition-transform">
                    <span>Manage Sizing Slips & Beams</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </div>
            </a>

            <!-- BOX 3: PARTS -->
            <a href="{{ route('parts.index') }}" class="glass-card glass-card-hover rounded-3xl p-6 relative group overflow-hidden border border-zinc-200 bg-white">
                <div class="flex items-start justify-between">
                    <div class="w-14 h-14 rounded-2xl bg-zinc-100 border border-zinc-300 text-zinc-700 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        </svg>
                    </div>
                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-zinc-100 border border-zinc-300 text-zinc-600">Module 03</span>
                </div>
                <div class="mt-5">
                    <h3 class="text-2xl font-black text-zinc-900 group-hover:text-zinc-600 transition-colors">Parts</h3>
                    <p class="text-xs text-zinc-400 mt-1 line-clamp-2">
                        Loom parts store inventory (Grippers, rapier tapes, heald wires, drop pins, sensors). Issue parts directly to factory looms.
                    </p>
                </div>
                <div class="mt-6 pt-4 border-t border-zinc-100 grid grid-cols-2 gap-2 text-xs">
                    <div>
                        <span class="text-[10px] uppercase text-zinc-400 font-semibold block">Total Stock Items</span>
                        <strong class="text-zinc-900 text-sm font-bold">{{ number_format($totalPartsStock) }} Units</strong>
                    </div>
                    <div>
                        <span class="text-[10px] uppercase text-zinc-400 font-semibold block">Low Stock Alert</span>
                        <strong class="{{ $lowStockPartsCount > 0 ? 'text-zinc-900' : 'text-zinc-500' }} text-sm font-bold">{{ $lowStockPartsCount }} Items</strong>
                    </div>
                </div>
                <div class="mt-4 flex items-center justify-between text-xs font-bold text-zinc-500 group-hover:translate-x-1 transition-transform">
                    <span>Manage Parts & Issuance</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </div>
            </a>

            <!-- BOX 4: FABRIC STOCK -->
            <a href="{{ route('fabric.index') }}" class="glass-card glass-card-hover rounded-3xl p-6 relative group overflow-hidden border border-zinc-200 bg-white">
                <div class="flex items-start justify-between">
                    <div class="w-14 h-14 rounded-2xl bg-zinc-100 border border-zinc-300 text-zinc-700 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                    </div>
                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-zinc-100 border border-zinc-300 text-zinc-600">Module 04</span>
                </div>
                <div class="mt-5">
                    <h3 class="text-2xl font-black text-zinc-900 group-hover:text-zinc-600 transition-colors">Fabric Stock</h3>
                    <p class="text-xs text-zinc-400 mt-1 line-clamp-2">
                        All fabric varieties produced on looms. Sell openly in spot market or allocate against multi-quality party contracts.
                    </p>
                </div>
                <div class="mt-6 pt-4 border-t border-zinc-100 grid grid-cols-2 gap-2 text-xs">
                    <div>
                        <span class="text-[10px] uppercase text-zinc-400 font-semibold block">Open Market Ready</span>
                        <strong class="text-zinc-900 text-sm font-bold">{{ number_format($openFabricMeters) }}m</strong>
                    </div>
                    <div>
                        <span class="text-[10px] uppercase text-zinc-400 font-semibold block">Committed Contract</span>
                        <strong class="text-zinc-600 text-sm font-bold">{{ number_format($committedFabricMeters) }}m</strong>
                    </div>
                </div>
                <div class="mt-4 flex items-center justify-between text-xs font-bold text-zinc-500 group-hover:translate-x-1 transition-transform">
                    <span>Manage Fabric Stock & Rolls</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </div>
            </a>

            <!-- BOX 5: SALES -->
            <a href="{{ route('sales.index') }}" class="glass-card glass-card-hover rounded-3xl p-6 relative group overflow-hidden border border-zinc-200 bg-white">
                <div class="flex items-start justify-between">
                    <div class="w-14 h-14 rounded-2xl bg-zinc-100 border border-zinc-300 text-zinc-700 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-zinc-100 border border-zinc-300 text-zinc-600">Module 05</span>
                </div>
                <div class="mt-5">
                    <h3 class="text-2xl font-black text-zinc-900 group-hover:text-zinc-600 transition-colors">Sales</h3>
                    <p class="text-xs text-zinc-400 mt-1 line-clamp-2">
                        Enter sales slips, open cash sales, and long-term buyer contracts. Auto deduct fabric rolls and update buyer ledgers.
                    </p>
                </div>
                <div class="mt-6 pt-4 border-t border-zinc-100 grid grid-cols-2 gap-2 text-xs">
                    <div>
                        <span class="text-[10px] uppercase text-zinc-400 font-semibold block">Total Revenue</span>
                        <strong class="text-zinc-900 text-sm font-bold">Rs. {{ number_format($totalSalesAmount, 0) }}</strong>
                    </div>
                    <div>
                        <span class="text-[10px] uppercase text-zinc-400 font-semibold block">Active Contracts</span>
                        <strong class="text-zinc-600 text-sm font-bold">{{ $activeContractsCount }} Contracts</strong>
                    </div>
                </div>
                <div class="mt-4 flex items-center justify-between text-xs font-bold text-zinc-500 group-hover:translate-x-1 transition-transform">
                    <span>Generate Sales Slips</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </div>
            </a>

            <!-- BOX 6: PRODUCTION -->
            <a href="{{ route('production.index') }}" class="glass-card glass-card-hover rounded-3xl p-6 relative group overflow-hidden border border-zinc-200 bg-white">
                <div class="flex items-start justify-between">
                    <div class="w-14 h-14 rounded-2xl bg-zinc-100 border border-zinc-300 text-zinc-700 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-zinc-100 border border-zinc-300 text-zinc-600">Module 06</span>
                </div>
                <div class="mt-5">
                    <h3 class="text-2xl font-black text-zinc-900 group-hover:text-zinc-600 transition-colors">Production</h3>
                    <p class="text-xs text-zinc-400 mt-1 line-clamp-2">
                        Loom-wise shift reports. Live calculation: how much beam it took, how much fabric it made, and leakage/waste loss.
                    </p>
                </div>
                <div class="mt-6 pt-4 border-t border-zinc-100 grid grid-cols-2 gap-2 text-xs">
                    <div>
                        <span class="text-[10px] uppercase text-zinc-400 font-semibold block">Total Fabric Made</span>
                        <strong class="text-zinc-900 text-sm font-bold">{{ number_format($totalProductionMeters) }}m</strong>
                    </div>
                    <div>
                        <span class="text-[10px] uppercase text-zinc-400 font-semibold block">Beam Leakage Loss</span>
                        <strong class="text-zinc-600 text-sm font-bold">{{ number_format($totalLeakageMeters) }}m Waste</strong>
                    </div>
                </div>
                <div class="mt-4 flex items-center justify-between text-xs font-bold text-zinc-500 group-hover:translate-x-1 transition-transform">
                    <span>Loom-wise Reports & Leakage</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </div>
            </a>

        </div>
    </div>

    <!-- Live Recent Operations Activity Log -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Recent Bag Purchases -->
        <div class="glass-card rounded-3xl p-5 border border-zinc-200">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-bold text-zinc-800 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-zinc-600"></span>
                    Recent Yarn Purchases
                </h3>
                <a href="{{ route('bags.index') }}" class="text-[11px] text-zinc-500 hover:text-zinc-900 hover:underline">View All &rarr;</a>
            </div>
            <div class="space-y-3">
                @forelse($recentPurchases as $purchase)
                    <div class="p-3 rounded-2xl bg-zinc-50 border border-zinc-100 flex items-center justify-between text-xs">
                        <div>
                            <div class="font-bold text-zinc-900">{{ $purchase->challan_no }} &bull; {{ $purchase->quality }}</div>
                            <div class="text-[11px] text-zinc-500">{{ $purchase->supplier_name }}</div>
                            <div class="text-[10px] text-zinc-400 mt-0.5">Split: <span class="text-zinc-700 font-semibold">{{ $purchase->bags_to_sizing }} Sizing</span> / <span class="text-zinc-700 font-semibold">{{ $purchase->bags_to_factory }} Factory</span></div>
                        </div>
                        <div class="text-right">
                            <span class="font-mono font-bold text-zinc-800">{{ $purchase->total_bags }} Bags</span>
                            <span class="block text-[10px] text-zinc-400">{{ $purchase->purchase_date->format('d M') }}</span>
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-zinc-400">No yarn purchases recorded yet.</p>
                @endforelse
            </div>
        </div>

        <!-- Recent Loom Production -->
        <div class="glass-card rounded-3xl p-5 border border-zinc-200">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-bold text-zinc-800 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-zinc-600"></span>
                    Recent Loom Production
                </h3>
                <a href="{{ route('production.index') }}" class="text-[11px] text-zinc-500 hover:text-zinc-900 hover:underline">View All &rarr;</a>
            </div>
            <div class="space-y-3">
                @forelse($recentProductions as $prod)
                    <div class="p-3 rounded-2xl bg-zinc-50 border border-zinc-100 flex items-center justify-between text-xs">
                        <div>
                            <div class="font-bold text-zinc-900">{{ $prod->loom_no }} &bull; {{ $prod->shift }} Shift</div>
                            <div class="text-[11px] text-zinc-500">{{ $prod->operator_name }} &bull; {{ $prod->fabric_quality }}</div>
                            <div class="text-[10px] text-zinc-500 mt-0.5">Leakage: {{ $prod->leakage_waste_meters }}m ({{ $prod->leakage_percentage }}%)</div>
                        </div>
                        <div class="text-right">
                            <span class="font-mono font-bold text-zinc-800">+{{ $prod->net_meters_produced }}m</span>
                            <span class="block text-[10px] text-zinc-500 font-semibold">{{ $prod->efficiency_percentage }}% Eff.</span>
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-zinc-400">No shift productions recorded yet.</p>
                @endforelse
            </div>
        </div>

        <!-- Recent Sales Slips -->
        <div class="glass-card rounded-3xl p-5 border border-zinc-200">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-bold text-zinc-800 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-zinc-600"></span>
                    Recent Sales Slips
                </h3>
                <a href="{{ route('sales.index') }}" class="text-[11px] text-zinc-500 hover:text-zinc-900 hover:underline">View All &rarr;</a>
            </div>
            <div class="space-y-3">
                @forelse($recentSales as $sale)
                    <div class="p-3 rounded-2xl bg-zinc-50 border border-zinc-100 flex items-center justify-between text-xs">
                        <div>
                            <div class="font-bold text-zinc-900">{{ $sale->slip_no }} &bull; {{ $sale->buyer_name }}</div>
                            <div class="text-[11px] text-zinc-500">{{ $sale->meters_sold }}m ({{ $sale->rolls_sold }} Rolls)</div>
                            <span class="inline-block px-1.5 py-0.5 rounded text-[9px] font-bold uppercase mt-1 bg-zinc-200 text-zinc-700">
                                {{ $sale->sale_type }}
                            </span>
                        </div>
                        <div class="text-right">
                            <span class="font-mono font-bold text-zinc-800">Rs. {{ number_format($sale->net_total, 0) }}</span>
                            <span class="block text-[10px] text-zinc-500 font-semibold uppercase">{{ $sale->payment_status }}</span>
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-zinc-400">No sales slips entered yet.</p>
                @endforelse
            </div>
        </div>

    </div>

</div>
@endsection
