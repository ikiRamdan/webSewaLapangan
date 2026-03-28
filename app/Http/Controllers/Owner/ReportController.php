<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Field;

    use App\Exports\ReportExport;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaction::with('details.field');

        // 🔍 FILTER TANGGAL
        if ($request->start_date && $request->end_date) {
            $query->whereBetween('created_at', [
                Carbon::parse($request->start_date)->startOfDay(),
                Carbon::parse($request->end_date)->endOfDay()
            ]);
        }

        // 🔍 FILTER LAPANGAN
        if ($request->field_id) {
            $query->whereHas('details', function ($q) use ($request) {
                $q->where('field_id', $request->field_id);
            });
        }

        $transactions = $query->latest()->get();

        // 💰 HITUNG TOTAL
        $total = 0;

        foreach ($transactions as $trx) {
            foreach ($trx->details as $detail) {

                $start = Carbon::parse($detail->start_time);
                $end   = Carbon::parse($detail->end_time);

                while ($start < $end) {
                    $hour = (int) $start->format('H');
                    $total += $detail->field->getPriceByHour($hour);
                    $start->addHour();
                }
            }
        }

        $fields = Field::all();

        return view('owner.reports.index', compact(
            'transactions',
            'total',
            'fields'
        ));
    }

public function export(Request $request)
{
    return Excel::download(
        new ReportExport(
            $request->start_date,
            $request->end_date,
            $request->field_id
        ),
        'laporan.xlsx'
    );
}
}