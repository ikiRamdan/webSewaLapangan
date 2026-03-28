@extends('layouts.main')
@section('title', 'Status Lapangan')

@section('content')

<div class="table-wrapper">

    {{-- 🔍 SEARCH --}}
    <div class="table-actions">
        <form method="GET" style="display:flex; gap:10px; width:100%;">
            
            <div class="table-search">
                <input type="text" name="search_customer" 
                       placeholder="Cari nama customer..."
                       value="{{ request('search_customer') }}">
            </div>

            <div class="table-search">
                <input type="text" name="search_field" 
                       placeholder="Cari nama lapangan..."
                       value="{{ request('search_field') }}">
            </div>

            <button class="btn btn-primary">Filter</button>
            <a href="{{ url()->current() }}" class="btn btn-danger">Reset</a>
        </form>
    </div>

    {{-- TABLE --}}
    <div class="table-responsive">
        <table class="table-schedule">
            <thead>
                <tr>
                    <th class="sticky-date">Hari / Tanggal</th>
                    @foreach($hours as $hour)
                        <th>{{ $hour }}</th>
                    @endforeach
                </tr>
            </thead>

            <tbody>
                @foreach($dates as $date)
                <tr>
                    <td class="sticky-date">
                        <strong>{{ \Carbon\Carbon::parse($date)->isoFormat('dddd') }}</strong><br>
                        <small>{{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}</small>
                    </td>

                    @foreach($hours as $hour)
                        @php
                            $cell = $grid[$date][$hour];
                            $dtStr = $date . ' ' . $hour . ':00';
                            $isPast = \Carbon\Carbon::parse($dtStr, 'Asia/Jakarta')->isPast();
                        @endphp

                        <td class="grid-cell">

                            @if($cell['status'] == 'empty')
                                @if($isPast)
                                    <div class="status-box btn-past">Selesai</div>
                                @else
                                    <a href="{{ route('kasir.transaksi.create', ['fieldId' => $field->id, 'start' => $dtStr]) }}" 
                                       class="status-box btn-add">+</a>
                                @endif
                            @else
                                @php 
                                    $link = ($cell['status'] == 'booked') ? 
                                        route('kasir.transaksi.create', [
                                            'fieldId' => $field->id,
                                            'start' => $dtStr,
                                            'mode' => 'pelunasan'
                                        ]) : '#';
                                @endphp

                                <a href="{{ $link }}" 
                                   class="status-box"
                                   style="background-color: {{ $cell['color'] }}">
                                    {!! str_replace(' ', '<br>', $cell['label']) !!}
                                </a>
                            @endif

                        </td>
                    @endforeach
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- DRAG SCROLL --}}
<script>
const slider = document.querySelector('.table-responsive');
let isDown = false;
let startX;
let scrollLeft;

slider.addEventListener('mousedown', (e) => {
    isDown = true;
    startX = e.pageX - slider.offsetLeft;
    scrollLeft = slider.scrollLeft;
});
slider.addEventListener('mouseleave', () => isDown = false);
slider.addEventListener('mouseup', () => isDown = false);
slider.addEventListener('mousemove', (e) => {
    if (!isDown) return;
    e.preventDefault();
    const x = e.pageX - slider.offsetLeft;
    const walk = (x - startX) * 2;
    slider.scrollLeft = scrollLeft - walk;
});
</script>

@endsection