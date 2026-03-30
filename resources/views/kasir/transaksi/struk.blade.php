<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Struk - {{ $transaction->kode_invoice }}</title>

    <style>
        @page { 
            size: 80mm auto; 
            margin: 0; 
        }

        body {
            font-family: 'Courier New', monospace;
            width: 70mm; /* Sedikit lebih kecil dari 80mm agar aman di margin printer */
            margin: 0 auto;
            padding: 20px 5px;
            font-size: 12px;
            color: #000;
            text-align: center; /* Membuat semua teks di tengah */
        }

        .logo-container {
            margin-bottom: 10px;
        }

        .logo-img {
            width: 50px; /* Sesuaikan ukuran logo */
            height: auto;
            filter: grayscale(100%); /* Struk thermal biasanya hitam putih */
        }

        .brand-name {
            font-size: 16px;
            font-weight: bold;
            display: block;
            margin-top: 5px;
        }

        .invoice-code {
            font-size: 11px;
            margin-bottom: 10px;
        }

        .divider { 
            border-top: 1px dashed #000; 
            margin: 10px 0; 
            width: 100%;
        }

        .info-table {
            width: 100%;
            margin-bottom: 10px;
            font-size: 11px;
        }

        /* Detail item belanja */
        .item-row {
            margin-bottom: 8px;
        }

        .bold { font-weight: bold; }
        
        .status-badge {
            border: 1px solid #000;
            padding: 2px 8px;
            display: inline-block;
            margin-top: 5px;
            text-transform: uppercase;
        }

        .footer {
            margin-top: 20px;
            font-size: 10px;
        }

        @media print {
            .no-print { display: none; }
        }
    </style>
</head>

<body>

    <div class="logo-container">
        <img src="{{ asset('images/logo.png') }}" class="logo-img">
        <span class="brand-name">RAKHASPORT</span>
        <div class="invoice-code">{{ $transaction->kode_invoice }}</div>
    </div>

    <div class="divider"></div>

    <div class="info-table">
        Cust: {{ $transaction->customer_name }} <br>
        Kasir: {{ $transaction->user->name ?? '-' }} <br>
        Tgl: {{ date('d/m/Y H:i') }}
    </div>

    <div class="divider"></div>

    @foreach($transaction->details as $detail)
        <div class="item-row">
            <span class="bold">{{ $detail->field->name }}</span> <br>
            {{ date('H:i', strtotime($detail->start_time)) }} - {{ date('H:i', strtotime($detail->end_time)) }} <br>
            {{ $detail->duration_hours }} Jam x Rp {{ number_format($detail->price_per_hour) }}
        </div>
        <div class="divider"></div>
    @endforeach

    <div style="margin-top: 10px;">
        TOTAL : <span class="bold">Rp {{ number_format($transaction->total) }}</span> <br>
        BAYAR : <span class="bold">Rp {{ number_format($transaction->amount_paid) }}</span> <br>
        
        <div class="status-badge bold">
            @if($transaction->payment_status == 'dp')
                STATUS: DP (BELUM LUNAS)
            @else
                STATUS: LUNAS
            @endif
        </div>
    </div>

    <div class="divider"></div>

    <div class="footer">
        <p>Terima Kasih Telah Bermain!<br>
        Simpan struk ini sebagai bukti sewa.</p>
        <p>*** RAKHASPORT ***</p>
    </div>

    <script>
        window.onload = function() {
            window.print();

            setTimeout(() => {
                // Logika redirect yang sama seperti sebelumnya
                if (document.referrer.includes('transaksi/create') || document.referrer.includes('transaksi/store')) {
                    window.location.href = "{{ route('kasir.fields.show', $transaction->details->first()->field_id) }}";
                }
            }, 2000);
        }
    </script>

</body>
</html>