@extends('layouts.main')

@section('content')

<div class="form-transaksi">

    {{-- ERROR --}}
    @if ($errors->any())
        <div style="background:#f8d7da; padding:10px; border-radius:10px; margin-bottom:15px;">
            <ul style="margin:0; padding-left:20px;">
                @foreach ($errors->all() as $error)
                    <li style="color:#721c24;">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <h3 style="margin-bottom:20px;">
        Transaksi {{ (isset($mode) && $mode == 'pelunasan') ? 'Pelunasan' : 'Booking Baru' }}
    </h3>

    <p style="margin-bottom:20px;">
        <strong>{{ $field->name }}</strong><br>
        Siang: Rp {{ number_format($field->price_day) }} (08-17)<br>
        Malam: Rp {{ number_format($field->price_night) }} (17-23)
    </p>

    <form method="POST" action="{{ route('kasir.transaksi.store') }}">
        @csrf
        <input type="hidden" name="field_id" value="{{ $field->id }}">

        {{-- ========================
            MODE PELUNASAN
        ======================== --}}
        @if(isset($mode) && $mode == 'pelunasan' && $detail)

            @php
                $transaction = $detail->transaction;
                $sisa = $detail->subtotal - $transaction->amount_paid;
            @endphp

            <input type="hidden" name="mode" value="pelunasan">
            <input type="hidden" name="detail_id" value="{{ $detail->id }}">

            <div class="form-group">
                <label>Nama Customer</label>
                <input type="text" value="{{ $transaction->customer_name }}" readonly>
            </div>

            <div class="form-group">
                <label>Sisa Pembayaran</label>
                <input type="text" value="Rp {{ number_format($sisa) }}" readonly>
            </div>

            <div class="form-group">
                <label>Nominal Pelunasan</label>
                <input type="number" name="amount_paid" value="{{ $sisa }}" readonly>
            </div>

            <button type="submit" class="btn-transaksi">
                KONFIRMASI PELUNASAN
            </button>

        @else

        {{-- ========================
            MODE BOOKING
        ======================== --}}

            <div class="form-group">
                <label>Nama Customer</label>
                <input type="text" name="customer_name" placeholder="Input nama..." required>
            </div>

            <div class="form-group">
                <label>Jam Mulai</label>
                <input type="datetime-local" name="start_time" id="start_time"
                       value="{{ isset($start) ? date('Y-m-d\TH:00', strtotime($start)) : '' }}"
                       step="3600" required>
            </div>

            <div class="form-group">
                <label>Jam Selesai</label>
                <input type="datetime-local" name="end_time" id="end_time"
                       step="3600" required>
            </div>

            <div class="form-group">
                <label>Total Biaya</label>
                <input type="text" id="total_display" readonly placeholder="Rp 0">
            </div>

            <div class="form-group">
                <label>Bayar Awal</label>
                <input type="number" name="amount_paid" placeholder="Kosongkan = DP 50%">
            </div>

            <button type="submit" name="action" value="booking" class="btn-transaksi">
                BOOKING (DP)
            </button>

            <button type="submit" name="action" value="lunas" class="btn-transaksi" style="background:#3b82f6; margin-top:10px;">
                BAYAR LUNAS
            </button>

        @endif

        <a href="{{ route('kasir.fields.show', $field->id) }}" 
           style="display:block; margin-top:15px; text-align:center; color:#333;">
           Kembali
        </a>

    </form>
</div>


{{-- ========================
    SCRIPT (TETAP DIPAKAI)
======================== --}}
<script>
document.addEventListener('DOMContentLoaded', function(){
    const startInput = document.getElementById('start_time');
    const endInput = document.getElementById('end_time');
    const totalDisplay = document.getElementById('total_display');

    const priceDay = {{ $field->price_day }};
    const priceNight = {{ $field->price_night }};

    function formatToSharpHour(input) {
        if (input && input.value) {
            let date = new Date(input.value);
            date.setMinutes(0);
            date.setSeconds(0);

            let year = date.getFullYear();
            let month = String(date.getMonth() + 1).padStart(2, '0');
            let day = String(date.getDate()).padStart(2, '0');
            let hours = String(date.getHours()).padStart(2, '0');

            input.value = `${year}-${month}-${day}T${hours}:00`;
        }
    }

    function getPrice(hour){
        if(hour >= 8 && hour < 17) return priceDay;
        if(hour >= 17 && hour < 23) return priceNight;
        return 0;
    }

    function calculate(){
        formatToSharpHour(startInput);
        formatToSharpHour(endInput);

        if(startInput.value && endInput.value){
            let start = new Date(startInput.value);
            let end   = new Date(endInput.value);

            let total = 0;

            while(start < end){
                let price = getPrice(start.getHours());

                if(price <= 0){
                    totalDisplay.value = "Jam tidak valid";
                    return;
                }

                total += price;
                start.setHours(start.getHours() + 1);
            }

            totalDisplay.value = "Rp " + total.toLocaleString('id-ID');
        }
    }

    startInput.addEventListener('change', calculate);
    endInput.addEventListener('change', calculate);

    if(startInput.value) calculate();
});
</script>

@endsection