<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RakhaSport - @yield('title', 'Dashboard')</title>
    <link rel="stylesheet" href="{{ asset('css/layouts/main.css') }}">
<link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

<div class="main-container">
    <div class="wrapper">

        <nav class="sidebar">
            <div class="brand">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="sidebar-logo">
                <span>RakhaSport</span>
            </div>

            <ul class="nav-menu">
@auth
@if(auth()->user()->role == 'kasir')

<li>
    <a href="/kasir/dashboard" class="{{ Request::is('kasir/dashboard*') ? 'active' : '' }}">
        <i class="fas fa-th-large"></i>
        <span>Dashboard</span>
    </a>
</li>

<li>
    <a href="/kasir/fields" class="{{ Request::is('kasir/fields') ? 'active' : '' }}">
        <i class="fas fa-futbol"></i>
        <span>Daftar Lapangan</span>
    </a>
</li>

<li>
    <a href="/kasir/transactions/history" class="{{ Request::is('kasir/transactions/history*') ? 'active' : '' }}">
        <i class="fas fa-history"></i>
        <span>Riwayat Transaksi</span>
    </a>
</li>

@endif
@endauth





@auth
@if(auth()->user()->role == 'admin')

<li>
    <a href="/admin/dashboard" class="{{ Request::is('admin/dashboard*') ? 'active' : '' }}">
        <i class="fas fa-th-large"></i>
        <span>Dashboard</span>
    </a>
</li>
<li>
    <a href="/admin/users" class="{{ Request::is('admin/users*') ? 'active' : '' }}">
        <i class="fas fa-user"></i>
        <span>Kelola Users</span>
    </a>
</li>
<li>
    <a href="/admin/fields" class="{{ Request::is('admin/fields*') ? 'active' : '' }}">
        <i class="fas fa-futbol"></i>
        <span>Kelola Lapangan</span>
    </a>
</li>

@endif
@endauth

@auth
@if(auth()->user()->role == 'owner')

<li>
    <a href="/owner/dashboard" class="{{ Request::is('owner/dashboard*') ? 'active' : '' }}">
        <i class="fas fa-chart-line"></i>
        <span>Dashboard</span>
    </a>
</li>

<li>
    <a href="/owner/fields" class="{{ Request::is('owner/fields*') ? 'active' : '' }}">
        <i class="fas fa-futbol"></i>
        <span>Data Lapangan</span>
    </a>
</li>

<li>
    <a href="/owner/reports" class="{{ Request::is('owner/reports*') ? 'active' : '' }}">
        <i class="fas fa-file-invoice-dollar"></i>
        <span>Laporan Transaksi</span>
    </a>
</li>

<li>
    <a href="{{ route('owner.logs') }}" class="{{ Request::is('owner/logs*') ? 'active' : '' }}">
        <i class="fas fa-clipboard-list"></i>
        <span>Log Aktivitas</span>
    </a>
</li>

@endif
@endauth

</ul>

            <div class="bottom-menu">
                <form action="/logout" method="POST">
                    @csrf
                    <button type="submit" class="btn-logout" onclick="return confirm('Log out sekarang?')">
                        <i class="fas fa-door-open"></i>
                        <span>Log Out</span>
                    </button>
                </form>
            </div>
        </nav>

        <main class="content-area">
            <header class="content-header">
                <h1 class="header-title">@yield('title', 'Dashboard')</h1>
                <div class="user-profile">
                    <div class="user-info" style="margin-right: 10px; text-align: right;">
                        <div class="user-name" style="font-weight: bold;">{{ auth()->user()->name }}</div>
                        <div class="user-role" style="font-size: 0.8rem; color: #666;">{{ ucfirst(auth()->user()->role) }}</div>
                    </div>
                    <i class="fas fa-user-circle" style="font-size: 2.5rem; color: #525252;"></i>
                </div>
            </header>

            <div class="scroll-content">
                @include('partials.alerts')
                @yield('content')
            </div>
        </main>

    </div>
</div>

</body>
</html>