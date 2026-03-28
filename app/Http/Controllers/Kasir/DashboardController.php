<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $todayTransactions = Transaction::whereDate('created_at', now())->count();

        return view('kasir.dashboard', compact('todayTransactions'));
    }
}