<?php

namespace App\Exports;

use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ReportExport implements FromView
{
    protected $start_date, $end_date, $field_id;

    public function __construct($start_date, $end_date, $field_id)
    {
        $this->start_date = $start_date;
        $this->end_date   = $end_date;
        $this->field_id   = $field_id;
    }

    public function view(): View
    {
        $query = Transaction::with('details.field');

        if ($this->start_date && $this->end_date) {
            $query->whereBetween('created_at', [
                Carbon::parse($this->start_date)->startOfDay(),
                Carbon::parse($this->end_date)->endOfDay()
            ]);
        }

        if ($this->field_id) {
            $query->whereHas('details', function ($q) {
                $q->where('field_id', $this->field_id);
            });
        }

        $transactions = $query->get();

        return view('owner.reports.export', compact('transactions'));
    }
}