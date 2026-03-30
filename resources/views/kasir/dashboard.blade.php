@extends('layouts.main')

@section('title', 'Dashboard Kasir')

@section('content')

<div class="welcome-card">
    <div class="welcome-text">
        <h4>Selamat datang di Dashboard Kasir</h4>
        <p>
            Kelola data operasional RakhaSport dengan mudah. 
            Pantau transaksi sewa dan pendapatan secara real-time hari ini.
        </p>
    </div>
    <img src="{{ asset('images/admin.png') }}" class="welcome-img">
</div>

<div class="stats">
    <div class="stat-card card">
        <div class="stat-icon">
            <i class="fas fa-calendar-check"></i> </div>
        <h4>Toral Sewa Hari Ini</h4>
        <div class="divider"></div>
        <span class="stat-value">{{ $totalSewaHariIni ?? 0 }}</span>
        <p class="stat-label">Transaksi Berhasil</p>
    </div>

    <div class="stat-card card gold-theme">
        <div class="stat-icon">
            <i class="fas fa-money-bill-wave"></i>
        </div>
        <h4>Total Pendapatan</h4>
        <div class="divider"></div>
        <span class="stat-value">Rp {{ number_format($totalPendapatan ?? 0, 0, ',', '.') }}</span>
        <p class="stat-label">Akumulasi Bulan Ini</p>
    </div>
</div>

@endsection