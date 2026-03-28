@extends('layouts.main')
@section('title', 'Tambah User')

@section('content')

<div class="form-wrapper">
    <form action="/admin/users" method="POST">
        @csrf
        
        <div class="form-group">
            <label for="name">Nama Lengkap</label>
            <input type="text" name="name" id="name" class="form-control" placeholder="Masukkan nama lengkap" required>
        </div>

        <div class="form-group">
            <label for="email">Alamat Email</label>
            <input type="email" name="email" id="email" class="form-control" placeholder="nama@email.com" required>
        </div>

       <div class="form-group">
    <label for="role">Role Pengguna</label>
    <select name="role" id="role" class="form-control" required>
        <option value="">-- Pilih Role --</option>
        <option value="admin">Admin</option>
        <option value="owner">Owner</option>
        <option value="kasir">Kasir</option>
    </select>
</div>

        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" name="password" id="password" class="form-control" placeholder="Minimal 8 karakter" required>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Simpan Data</button>
            <a href="/admin/users" class="btn btn-danger">Batal</a>
        </div>
    </form>
</div>
@endsection