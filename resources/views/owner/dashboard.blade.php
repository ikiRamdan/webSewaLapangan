@extends('layouts.main')

@section('title', 'Dashboard Owner')

@section('content')

<div class="welcome-card">
    <div class="welcome-text">
        <h4>Selamat datang di Dashboard Owner</h4>
        <p>
            Pantau performa bisnis RakhaSport secara real-time. Anda dapat melihat jumlah transaksi hari ini serta total penghasilan keseluruhan.
        </p>
    </div>
    <img src="{{ asset('images/admin.png') }}" alt="Owner Illustration" class="welcome-img">
</div>

<div class="stats">
    
    {{-- TOTAL SEWA HARI INI --}}
    <div class="stat-card">
        <h4>Total Sewa Hari Ini</h4>
        <div class="divider"></div>
        <span>{{ $totalToday }}</span>
    </div>

    {{-- TOTAL PENGHASILAN --}}
    <div class="stat-card">
        <h4>Total Penghasilan</h4>
        <div class="divider"></div>
        <span>Rp {{ number_format($totalRevenue) }}</span>
    </div>

</div>

@endsection