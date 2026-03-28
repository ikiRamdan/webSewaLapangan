@extends('layouts.main')

@section('title', 'Dashboard Admin')

@section('content')

<div class="welcome-card">
    <div class="welcome-text">
        <h4>Selamat datang di Dashboard Admin</h4>
        <p>
            Kelola data operasional RakhaSport dengan mudah. Di sini Anda dapat memantau total pengguna dan jumlah lapangan yang tersedia dalam sistem secara real-time.
        </p>
    </div>
    <img src="{{ asset('images/admin.png') }}" alt="Admin Illustration" class="welcome-img">
</div>

<div class="stats">
    <div class="stat-card">
        <h4>Total Users</h4>
        <div class="divider"></div>
        <span>{{ $totalUsers }}</span>
    </div>

    <div class="stat-card">
        <h4>Total Lapang</h4>
        <div class="divider"></div>
        <span>{{ $totalFields }}</span>
    </div>
</div>

@endsection