@extends('layouts.main')

@section('title','Laporan Transaksi')

@section('content')

<div class="table-wrapper">

    <h3 style="margin-bottom:20px;">Laporan Transaksi</h3>
<a href="{{ route('owner.reports.export', request()->all()) }}" 
   class="btn btn-success">
   Export Excel
</a>
    {{-- FILTER --}}
    <div class="table-actions">
        <form method="GET" style="display:flex; gap:10px; align-items:center; flex-wrap:wrap;">

            <input type="date" name="start_date" 
                   value="{{ request('start_date') }}" 
                   class="form-control">

            <input type="date" name="end_date" 
                   value="{{ request('end_date') }}" 
                   class="form-control">

            <select name="field_id" class="form-control">
                <option value="">Semua Lapangan</option>
                @foreach($fields as $f)
                    <option value="{{ $f->id }}"
                        {{ request('field_id') == $f->id ? 'selected' : '' }}>
                        {{ $f->name }}
                    </option>
                @endforeach
            </select>

            <button class="btn btn-primary">Filter</button>

            <a href="{{ route('owner.reports') }}" class="btn btn-danger">Reset</a>
        </form>
    </div>

    {{-- TABLE --}}
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Customer</th>
                    <th>Lapangan</th>
                    <th>Jam</th>
                    <th>Total</th>
                    <th>Tanggal</th>
                </tr>
            </thead>

            <tbody>
                @forelse($transactions as $key => $trx)

                    @foreach($trx->details as $detail)
                    <tr>

                        <td>{{ $loop->iteration }}</td>

                        <td>{{ $trx->customer_name }}</td>

                        <td>{{ $detail->field->name ?? '-' }}</td>

                        <td>
                            {{ \Carbon\Carbon::parse($detail->start_time)->format('H:i') }}
                            -
                            {{ \Carbon\Carbon::parse($detail->end_time)->format('H:i') }}
                        </td>

                        <td>
                            @php
                                $subtotal = 0;
                                $start = \Carbon\Carbon::parse($detail->start_time);
                                $end   = \Carbon\Carbon::parse($detail->end_time);

                                while($start < $end){
                                    $hour = (int) $start->format('H');
                                    $subtotal += $detail->field->getPriceByHour($hour);
                                    $start->addHour();
                                }
                            @endphp

                            Rp {{ number_format($subtotal) }}
                        </td>

                        <td>
                            {{ $trx->created_at->format('d M Y') }}
                        </td>

                    </tr>
                    @endforeach

                @empty
                    <tr>
                        <td colspan="6" style="text-align:center;">
                            Tidak ada data
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- TOTAL --}}
    <div style="margin-top:20px; text-align:right;">
        <h4>
            Total Pendapatan:
            <span style="color:#28a745;">
                Rp {{ number_format($total) }}
            </span>
        </h4>
    </div>

</div>


@endsection