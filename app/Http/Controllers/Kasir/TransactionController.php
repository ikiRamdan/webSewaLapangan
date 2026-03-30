<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\Field;
use App\Services\BookingService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Helper\LogHelper;

class TransactionController extends Controller
{
    protected $bookingService;

    public function __construct(BookingService $bookingService)
    {
        $this->bookingService = $bookingService;
    }

    // ================= CREATE =================
    public function create(Request $request, $fieldId)
    {
        $field = Field::findOrFail($fieldId);
        $start = $request->start;
        $mode  = $request->mode;

        $detail = null;

        if ($mode === 'pelunasan') {
            $startTime = Carbon::parse($start, 'Asia/Jakarta')->startOfHour();
            $endTime   = Carbon::parse($start, 'Asia/Jakarta')->endOfHour();

            $detail = TransactionDetail::with('transaction')
                ->where('field_id', $fieldId)
                ->whereBetween('start_time', [$startTime, $endTime])
                ->where('status', 'booked')
                ->first();

            if (!$detail) {
                return redirect()
                    ->route('kasir.fields.show', $fieldId)
                    ->with('error', 'Booking tidak ditemukan atau sudah lunas');
            }
        }

        return view('kasir.fields.transaksi', compact(
            'field',
            'start',
            'mode',
            'detail'
        ));
    }

    // ================= STORE =================
    public function store(Request $request)
    {
        $request->validate([
            'field_id' => 'required|exists:fields,id',
        ]);

        $field = Field::findOrFail($request->field_id);

        // ================= PELUNASAN =================
        if ($request->mode === 'pelunasan') {
            $request->validate([
                'detail_id' => 'required|exists:transaction_details,id',
                'amount_paid' => 'required|numeric|min:1'
            ]);

            DB::beginTransaction();
            try {
                $detail = TransactionDetail::with('transaction')->lockForUpdate()->findOrFail($request->detail_id);
                $transaction = $detail->transaction;

                $newPaid = $transaction->amount_paid + $request->amount_paid;
                if ($newPaid > $transaction->total) $newPaid = $transaction->total;

                $transaction->update([
                    'amount_paid' => $newPaid,
                    'status' => 'finished',
                    'payment_status' => 'lunas'
                ]);

                $detail->update(['status' => 'finished']);

                // LOGGING
                // LogHelper::store('Pelunasan', "Pelunasan transaksi {$transaction->kode_invoice} (Rp ".number_format($request->amount_paid).")");

                DB::commit();
                return redirect()->route('kasir.fields.show', $field->id)->with('success', 'Pelunasan Berhasil!');
            } catch (\Exception $e) {
                DB::rollBack();
                return back()->with('error', 'Gagal pelunasan: ' . $e->getMessage());
            }
        }

        // ================= BOOKING BARU =================
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
        ]);

        $start = $request->start_time;
        $end   = $request->end_time;

        if (!$this->bookingService->validateOperatingHours($start, $end)) {
            return back()->with('error', 'Jam operasional 08:00 - 23:00')->withInput();
        }

        DB::beginTransaction();
        try {
            // Cek Bentrok
            $conflict = TransactionDetail::where('field_id', $field->id)
                ->where(function ($q) use ($start, $end) {
                    $q->where('start_time', '<', $end)->where('end_time', '>', $start);
                })->lockForUpdate()->exists();

            if ($conflict) {
                DB::rollBack();
                return back()->with('error', 'Slot sudah terisi!')->withInput();
            }

            // Hitung Harga
            $startTime = Carbon::parse($start);
            $endTime   = Carbon::parse($end);
            $duration  = $startTime->diffInHours($endTime);
            
            $current = $startTime->copy();
            $subtotal = 0;
            while ($current < $endTime) {
                $subtotal += $field->getPriceByHour((int)$current->format('H'));
                $current->addHour();
            }

            // Payment Logic
            $action = $request->action;
            if ($action == 'lunas') {
                $status = 'finished'; $paymentStatus = 'lunas'; $amountPaid = $subtotal;
            } else {
                $status = 'booked'; $paymentStatus = 'dp'; 
                $amountPaid = max($request->amount_paid ?? 0, $subtotal * 0.5);
            }

            // Simpan
            $transaction = Transaction::create([
                'user_id' => Auth::id(),
                'customer_name' => $request->customer_name,
                'total' => $subtotal,
                'amount_paid' => $amountPaid,
                'status' => $status,
                'payment_status' => $paymentStatus,
                'kode_invoice' => 'INV-' . now()->format('YmdHis')
            ]);

            TransactionDetail::create([
                'transaction_id' => $transaction->id,
                'field_id' => $field->id,
                'start_time' => $start,
                'end_time' => $end,
                'price_per_hour' => $subtotal / $duration,
                'subtotal' => $subtotal,
                'status' => $status
            ]);

            // LOGGING
            // LogHelper::store('Transaksi Baru', "Booking oleh {$transaction->customer_name} Lapangan {$field->name} ({$paymentStatus})");

            DB::commit();
            return redirect()->route('kasir.transaksi.cetak', $transaction->id);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error: ' . $e->getMessage())->withInput();
        }
    }

    // ================= CETAK =================
    public function cetakStruk($id)
    {
        $transaction = Transaction::with(['details.field', 'user'])
            ->findOrFail($id);

        return view('kasir.transaksi.struk', compact('transaction'));
    }

    // ================= HISTORY =================
public function history(Request $request)
{
    $tanggal = $request->tanggal 
        ? Carbon::parse($request->tanggal) 
        : now();

    $fieldId = $request->field_id;

    // Query transaksi + relasi
    $query = \App\Models\Transaction::with('details.field')
        ->whereDate('created_at', $tanggal);

    // 🔍 FILTER LAPANGAN
    if ($fieldId) {
        $query->whereHas('details', function ($q) use ($fieldId) {
            $q->where('field_id', $fieldId);
        });
    }

    $transactions = $query->latest()->get();

    // Ambil data lapangan untuk dropdown
    $fields = \App\Models\Field::where('is_active', true)->get();

    // HITUNG TOTAL
    $grandTotal = 0;

    foreach ($transactions as $trx) {
        foreach ($trx->details as $detail) {

            if ($fieldId && $detail->field_id != $fieldId) continue;

            $start = Carbon::parse($detail->start_time);
            $end   = Carbon::parse($detail->end_time);

            while ($start < $end) {
                $hour = (int) $start->format('H');
                $grandTotal += $detail->field->getPriceByHour($hour);
                $start->addHour();
            }
        }
    }

    return view('kasir.riwayat_transaksi.index', compact(
        'transactions',
        'fields',
        'grandTotal'
    ));
}
}