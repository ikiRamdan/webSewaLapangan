<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Struk - {{ $transaction->kode_invoice }}</title>

    <style>
        @page { size: 80mm auto; margin: 0; }

        body {
            font-family: 'Courier New', monospace;
            width: 80mm;
            margin: 0;
            padding: 10px;
            font-size: 12px;
        }

        .text-center { text-align: center; }
        .divider { border-top: 1px dashed #000; margin: 10px 0; }
        .bold { font-weight: bold; }
    </style>
</head>

<body>

    <div class="text-center">
        <h3>FUTSAL ARENA</h3>
        <p>{{ $transaction->kode_invoice }}</p>
    </div>

    <div class="divider"></div>

    <p>
        Nama: {{ $transaction->customer_name }} <br>
        Kasir: {{ $transaction->user->name ?? '-' }} <br>
        Tanggal: {{ date('d-m-Y H:i') }}
    </p>

    <div class="divider"></div>

    @foreach($transaction->details as $detail)
        <p>
            Lapangan: {{ $detail->field->name }} <br>
            Jam: {{ date('H:i', strtotime($detail->start_time)) }} - 
                 {{ date('H:i', strtotime($detail->end_time)) }} <br>
            Durasi: {{ $detail->duration_hours }} jam <br>
            Harga: Rp {{ number_format($detail->price_per_hour) }}
        </p>
        <div class="divider"></div>
    @endforeach

    <p class="bold">
        Total: Rp {{ number_format($transaction->total) }} <br>
        Bayar: Rp {{ number_format($transaction->amount_paid) }} <br>

        @if($transaction->payment_status == 'dp')
            Status: DP
        @else
            Status: LUNAS
        @endif
    </p>

    <div class="divider"></div>

    <div class="text-center">
        <p>Terima Kasih 🙏</p>
    </div>

    <!-- AUTO PRINT + AUTO KEMBALI -->
    <script>
        window.onload = function() {
            window.print();

            setTimeout(() => {
                window.location.href = "{{ route('kasir.fields.show', $transaction->details->first()->field_id) }}";
            }, 2000);
        }
    </script>

</body>
</html>