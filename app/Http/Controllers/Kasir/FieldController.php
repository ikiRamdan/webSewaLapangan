<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Field;
use App\Models\TransactionDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;

class FieldController extends Controller
{
    public function index()
    {
        $fields = Field::where('is_active', true)->get();
        return view('kasir.fields.datafields', compact('fields'));
    }

    public function show(Request $request, $id)
    {
        $field = Field::findOrFail($id);
        $now = Carbon::now('Asia/Jakarta');

        $startHour = 8;
        $endHour   = 23;

        // Jam
        $hours = [];
        for ($i = $startHour; $i < $endHour; $i++) {
            $hours[] = sprintf('%02d:00', $i);
        }

        // Tanggal 7 hari
        $dates = [];
        for ($i = 0; $i < 7; $i++) {
            $dates[] = $now->copy()->addDays($i)->format('Y-m-d');
        }

        // 🔍 FILTER
        $searchCustomer = $request->search_customer;
        $searchField    = $request->search_field;

        $query = TransactionDetail::with('transaction', 'field')
            ->where('field_id', $field->id)
            ->whereBetween('start_time', [
                $now->copy()->startOfDay(),
                $now->copy()->addDays(7)->endOfDay()
            ]);

        // Filter nama customer
        if ($searchCustomer) {
            $query->whereHas('transaction', function ($q) use ($searchCustomer) {
                $q->where('customer_name', 'like', '%' . $searchCustomer . '%');
            });
        }

        // Filter nama lapangan
        if ($searchField) {
            $query->whereHas('field', function ($q) use ($searchField) {
                $q->where('name', 'like', '%' . $searchField . '%');
            });
        }

        $details = $query->get();

        // GRID DEFAULT
        $grid = [];
        foreach ($dates as $date) {
            foreach ($hours as $hour) {
                $hourInt = (int) substr($hour, 0, 2);

                $grid[$date][$hour] = [
                    'status' => 'empty',
                    'customer' => null,
                    'price' => $field->getPriceByHour($hourInt)
                ];
            }
        }

        // ISI GRID
        foreach ($details as $detail) {
            $start = Carbon::parse($detail->start_time, 'Asia/Jakarta');
            $end   = Carbon::parse($detail->end_time, 'Asia/Jakarta');

            while ($start < $end) {

                $date = $start->format('Y-m-d');
                $hour = $start->format('H:00');

                if (isset($grid[$date][$hour])) {

                    if ($detail->status == 'finished') {
                        $label = 'Tersewa';
                        $color = '#28a745';
                        $uiStatus = 'finished';
                    } else {
                        if ($now->between($start, $end)) {
                            $label = $detail->transaction->customer_name . ' Terisi';
                            $color = '#dc3545';
                            $uiStatus = 'booked';
                        } elseif ($start->lessThan($now)) {
                            $label = 'Tersewa';
                            $color = '#28a745';
                            $uiStatus = 'finished';
                        } else {
                            $label = $detail->transaction->customer_name . ' Booking';
                            $color = '#ffc107';
                            $uiStatus = 'booked';
                        }
                    }

                    $grid[$date][$hour]['status'] = $uiStatus;
                    $grid[$date][$hour]['customer'] = $detail->transaction->customer_name ?? '-';
                    $grid[$date][$hour]['label'] = $label;
                    $grid[$date][$hour]['color'] = $color;
                }

                $start->addHour();
            }
        }

        return view('kasir.fields.detailfields', compact(
            'field',
            'dates',
            'hours',
            'grid'
        ));
    }
}