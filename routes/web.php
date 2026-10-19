<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BagController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FabricStockController;
use App\Http\Controllers\LedgerController;
use App\Http\Controllers\PartController;
use App\Http\Controllers\ProductionController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\SizingController;
use Illuminate\Support\Facades\Route;

// Guest Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::post('/quick-login', [AuthController::class, 'quickLogin'])->name('quick-login');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Authenticated Application Routes
Route::middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('home');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // 1. Bags (Yarn management, purchase split, store stock & sizing history)
    Route::get('/bags', [BagController::class, 'index'])->name('bags.index');
    Route::get('/bags/purchases', [BagController::class, 'purchases'])->name('bags.purchases');
    Route::get('/bags/ledger', [BagController::class, 'ledger'])->name('bags.ledger');
    Route::get('/bags/sizing-usage', [BagController::class, 'sizingUsage'])->name('bags.sizing-usage');
    Route::post('/bags/purchase', [BagController::class, 'storePurchase'])->name('bags.purchase');
    Route::post('/bags/issue', [BagController::class, 'issueStock'])->name('bags.issue');
    Route::post('/bags/return', [BagController::class, 'returnStock'])->name('bags.return');

    // 2. Sizing (Sizing slip entry, beam tracking, bags used & returned)
    Route::get('/sizing', [SizingController::class, 'index'])->name('sizing.index');
    Route::post('/sizing/slip', [SizingController::class, 'storeSlip'])->name('sizing.slip.store');
    Route::post('/sizing/beams/{beam}/assign', [SizingController::class, 'assignBeam'])->name('sizing.beam.assign');
    Route::post('/sizing/beams/{beam}/exhaust', [SizingController::class, 'exhaustBeam'])->name('sizing.beam.exhaust');

    // 3. Parts (Parts store, gripper/accessories inventory, factory issuance)
    Route::get('/parts', [PartController::class, 'index'])->name('parts.index');
    Route::post('/parts', [PartController::class, 'storePart'])->name('parts.store');
    Route::post('/parts/issue', [PartController::class, 'issuePart'])->name('parts.issue');

    // 4. Fabric Stock (Finished goods, open stock vs contract commitment)
    Route::get('/fabric-stock', [FabricStockController::class, 'index'])->name('fabric.index');
    Route::post('/fabric-stock', [FabricStockController::class, 'storeStock'])->name('fabric.store');

    // 5. Sales (Sales slips, open market sales, multi-quality contracts)
    Route::get('/sales', [SalesController::class, 'index'])->name('sales.index');
    Route::post('/sales', [SalesController::class, 'storeSale'])->name('sales.store');
    Route::post('/sales/contract', [SalesController::class, 'storeContract'])->name('sales.contract.store');

    // 6. Production (Loom-wise daily production, beam length consumption, leakage/loss calculation)
    Route::get('/production', [ProductionController::class, 'index'])->name('production.index');
    Route::post('/production', [ProductionController::class, 'storeProduction'])->name('production.store');

    // 7. Ledgers (Party ledgers & audit trail)
    Route::get('/ledgers', [LedgerController::class, 'index'])->name('ledgers.index');
});
