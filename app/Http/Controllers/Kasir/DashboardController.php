<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
   // File: App\Http\Controllers\Kasir\DashboardController.php (atau sejenisnya)

public function index()
{
    // 1. Hitung Total Sewa Lapang Hari Ini (Jumlah Transaksi)
    $totalSewaHariIni = \App\Models\Transaction::whereDate('created_at', now())
                        ->count();

    // 2. Hitung Total Pendapatan (Sum dari amount_paid agar akurat dengan uang yang masuk)
    // Kita gunakan amount_paid karena ada sistem DP dan Pelunasan di controller Anda
    $totalPendapatan = \App\Models\Transaction::sum('amount_paid');

    return view('kasir.dashboard', compact('totalSewaHariIni', 'totalPendapatan'));
}
}