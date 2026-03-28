@extends('layouts.main')

@section('title', 'Dashboard Kasir')

@section('content')

<div class="welcome-card">
    <div class="welcome-text">
        <h4>Selamat datang di Kasir</h4>
        <p>
            Kelola data operasional RakhaSport dengan mudah. 
            Pantau pengguna dan lapangan secara real-time.
        </p>
    </div>
    <img src="{{ asset('images/admin.png') }}" class="welcome-img">
</div>

<div class="stats">
    <div class="stat-card card">
        <h4>Total Users</h4>
        <div class="divider"></div>
        <span>{{ $totalUsers ?? 0 }}</span>
    </div>

    <div class="stat-card card">
        <h4>Total Lapang</h4>
        <div class="divider"></div>
        <span>{{ $totalFields ?? 0 }}</span>
    </div>
</div>

@endsection