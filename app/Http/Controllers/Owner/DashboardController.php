<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Transaction;

class DashboardController extends Controller
{
    public function index()
{
    $today = \Carbon\Carbon::today();

    // Total transaksi hari ini
    $totalToday = \App\Models\Transaction::whereDate('created_at', $today)->count();

    // Total penghasilan (dari detail)
    $details = \App\Models\TransactionDetail::with('field')->get();

    $totalRevenue = 0;

    foreach ($details as $detail) {
        $start = \Carbon\Carbon::parse($detail->start_time);
        $end   = \Carbon\Carbon::parse($detail->end_time);

        while ($start < $end) {
            $hour = (int) $start->format('H');
            $totalRevenue += $detail->field->getPriceByHour($hour);
            $start->addHour();
        }
    }

    return view('owner.dashboard', compact(
        'totalToday',
        'totalRevenue'
    ));
}
}