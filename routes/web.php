<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\AuthController;

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\FieldController as AdminFieldController;

use App\Http\Controllers\Kasir\DashboardController as KasirDashboardController;
use App\Http\Controllers\Kasir\FieldController as KasirFieldController;
use App\Http\Controllers\Kasir\TransactionController;

use App\Http\Controllers\Owner\DashboardController as OwnerDashboardController;
use App\Http\Controllers\Owner\FieldController as OwnerFieldController;
use App\Http\Controllers\Owner\ReportController;
use App\Http\Controllers\Owner\ActivityLogController;

/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect('/login');
});

Route::middleware('guest')->group(function () {

    Route::get('/login', [AuthController::class,'loginForm'])->name('login');
    Route::post('/login', [AuthController::class,'login']);

});

Route::post('/logout', [AuthController::class,'logout'])->middleware('auth');


/*
|--------------------------------------------------------------------------
| ADMIN
|--------------------------------------------------------------------------
*/

Route::middleware(['auth','role:admin'])
->prefix('admin')
->name('admin.')
->group(function(){

    Route::get('/dashboard',[AdminDashboardController::class,'index'])
        ->name('dashboard');

    Route::resource('/users',UserController::class);

    Route::resource('/fields',AdminFieldController::class);

});


/*
|--------------------------------------------------------------------------
| KASIR
|--------------------------------------------------------------------------
*/

Route::middleware(['auth','role:kasir'])
->prefix('kasir')
->name('kasir.')
->group(function(){

    // ================= DASHBOARD =================
    Route::get('/dashboard',[KasirDashboardController::class,'index'])
        ->name('dashboard');

    // ================= FIELDS =================
    Route::get('/fields',[KasirFieldController::class,'index'])
        ->name('fields.index');

    Route::get('/fields/{id}',[KasirFieldController::class,'show'])
        ->name('fields.show');

    // ================= TRANSAKSI =================
    Route::get('/transaksi/{fieldId}',[TransactionController::class,'create'])
        ->name('transaksi.create');

    Route::post('/transaksi',[TransactionController::class,'store'])
        ->name('transaksi.store');

    Route::get('/transactions/history',[TransactionController::class,'history'])
        ->name('transactions.history');
        

        Route::get('/fields/{id}/grid',[KasirFieldController::class,'grid'])
    ->name('kasir.fields.grid');
Route::get('/transaksi/{id}/cetak', [TransactionController::class, 'cetakStruk'])
    ->name('transaksi.cetak');
});


/*
|--------------------------------------------------------------------------
| OWNER
|--------------------------------------------------------------------------
*/

Route::prefix('owner')
    ->middleware(['auth', 'role:owner'])
    ->name('owner.')
    ->group(function () {

        // 📊 Dashboard
        Route::get('/dashboard', [OwnerDashboardController::class, 'index'])
            ->name('dashboard');

        // 🏟️ Data Lapangan
        Route::get('/fields', [OwnerFieldController::class, 'index'])
            ->name('fields');

        // 💰 Laporan Transaksi
        Route::get('/reports', [ReportController::class, 'index'])
            ->name('reports');

        // 📋 Log Aktivitas
       Route::get('/logs', [ActivityLogController::class, 'index'])
    ->name('logs');
            Route::get('/reports/export', [ReportController::class, 'export'])
    ->name('reports.export');
    });