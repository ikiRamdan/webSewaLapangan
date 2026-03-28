<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Field;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
class DashboardController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();
        $totalFields = Field::count();
        $totalTransactions = Transaction::count();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalFields',
            'totalTransactions'
        ));
    }
}