<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ActivityLog;
use Carbon\Carbon;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $query = ActivityLog::with('user');

        // 🔍 FILTER USER
        if ($request->user_id) {
            $query->where('user_id', $request->user_id);
        }

        // 🔍 FILTER ACTION
        if ($request->action) {
            $query->where('action', 'like', '%' . $request->action . '%');
        }

        // 🔍 FILTER TANGGAL
        if ($request->start_date && $request->end_date) {
            $query->whereBetween('created_at', [
                Carbon::parse($request->start_date)->startOfDay(),
                Carbon::parse($request->end_date)->endOfDay()
            ]);
        }

        $logs = $query->latest()->paginate(10);

        $users = \App\Models\User::all();

        return view('owner.logs.index', compact('logs', 'users'));
    }
}