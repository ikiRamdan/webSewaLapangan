@extends('layouts.main')
@section('title','Data Users')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/table_style.css') }}">
@endpush

@section('content')
{{-- Bagian Filter & Tombol Tambah --}}
<div class="table-actions">
    {{-- Tambahkan id="search-form" agar bisa diakses elemen di luarnya --}}
    <form method="GET" action="/admin/users" class="table-search" id="search-form">
        <input type="text" name="name" placeholder="Search........" value="{{ request('name') }}">
        
        {{-- Jika ingin filter role langsung submit saat diganti, pindahkan ke dalam form --}}
        <select name="role" onchange="this.form.submit()" style="margin-left: 10px;">
            <option value="">--Filter by Role--</option>
            <option value="admin" {{ request('role')=='admin' ? 'selected' : '' }}>Admin</option>
            <option value="owner" {{ request('role')=='owner' ? 'selected' : '' }}>Owner</option>
            <option value="kasir" {{ request('role')=='kasir' ? 'selected' : '' }}>Kasir</option>
        </select>
    </form>

    <a href="/admin/users/create" class="btn-tambah-user">Tambah Users</a>
</div>

{{-- Container Tabel --}}
<div class="table-wrapper">
    <table class="custom-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Username</th>
                <th>Nama</th>
                <th>Role</th>
                <th>Status</th>
                <th style="text-align: center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $key => $user)
            <tr>
                <td>{{ $users->firstItem() + $key }}</td>
                <td>{{ $user->username ?? $user->name }}</td>
                <td><strong>{{ $user->name }}</strong></td>
                <td>{{ ucfirst($user->role) }}</td>
                <td>
                    <span style="color: #525252;">Aktif</span>
                </td>
                <td style="text-align: center">
                    <a href="/admin/users/{{ $user->id }}/edit" class="btn-aksi-edit">Edit</a>
                    
                    <form action="/admin/users/{{ $user->id }}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin hapus user ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-aksi-hapus">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="pagination-wrapper" style="margin-top: 20px;">
    {{ $users->links() }}
</div>
@endsection