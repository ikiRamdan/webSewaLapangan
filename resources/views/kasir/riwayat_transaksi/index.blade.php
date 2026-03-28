@extends('layouts.main')

@section('content')

<div class="table-wrapper">

    <h3 style="margin-bottom:20px;">Riwayat Transaksi</h3>

    {{-- 🔍 FILTER --}}
    <div class="table-actions">
        <form method="GET" style="display:flex; gap:10px;">

            <input type="date" name="tanggal"
                   value="{{ request('tanggal') }}"
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
            <a href="{{ url()->current() }}" class="btn btn-danger">Reset</a>

        </form>
    </div>

    {{-- TABLE --}}
    <div class="table-responsive">
        <table class="custom-table">

            <thead>
                <tr>
                    <th>No</th>
                    <th>Customer</th>
                    <th>Lapangan</th>
                    <th>Jam</th>
                    <th>Tanggal</th>
                    <th>Total</th>
                </tr>
            </thead>

            <tbody>
                @forelse($transactions as $trx)
                    @foreach($trx->details as $detail)

                        @php
                            if(request('field_id') && $detail->field_id != request('field_id')) continue;

                            $start = \Carbon\Carbon::parse($detail->start_time);
                            $end   = \Carbon\Carbon::parse($detail->end_time);

                            $total = 0;
                            $temp = $start->copy();

                            while($temp < $end){
                                $hour = (int) $temp->format('H');
                                $total += $detail->field->getPriceByHour($hour);
                                $temp->addHour();
                            }
                        @endphp

                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $trx->customer_name }}</td>
                            <td>{{ $detail->field->name }}</td>
                            <td>
                                {{ $start->format('H:i') }} - {{ $end->format('H:i') }}
                            </td>
                            <td>{{ $start->format('d-m-Y') }}</td>
                            <td><strong>Rp {{ number_format($total) }}</strong></td>
                        </tr>

                    @endforeach
                @empty
                    <tr>
                        <td colspan="6">Tidak ada data</td>
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
                Rp {{ number_format($grandTotal) }}
            </span>
        </h4>
    </div>

</div>

@endsection