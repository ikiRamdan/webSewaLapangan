@extends('layouts.main')
@section('title', 'Edit User')

@section('content')

<div class="form-wrapper">
    <form action="/admin/users/{{ $user->id }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="form-group">
            <label for="name">Username / Nama</label>
            <input type="text" 
                   name="name" 
                   id="name" 
                   class="form-control" 
                   value="{{ old('name', $user->name) }}" 
                   required>
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" 
                   name="email" 
                   id="email" 
                   class="form-control" 
                   value="{{ old('email', $user->email) }}" 
                   required>
        </div>

       <div class="form-group">
    <label for="role">Role / Hak Akses</label>
    <select name="role" id="role" class="form-control" required>
        <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
        <option value="owner" {{ $user->role == 'owner' ? 'selected' : '' }}>Owner</option>
        <option value="kasir" {{ $user->role == 'kasir' ? 'selected' : '' }}>Kasir</option>
    </select>
</div>

<div class="form-group">
    <label>
        <input type="checkbox" name="is_active" value="1" {{ $user->is_active ? 'checked' : '' }}>
        User Aktif
    </label>
</div>
        <div class="form-group" style="margin-top: 25px; padding-top: 15px; border-top: 1px dashed #e2e8f0;">
            <label for="password">Password Baru</label>
            <input type="password" 
                   name="password" 
                   id="password" 
                   class="form-control" 
                   placeholder="Isi hanya jika ingin ganti password">
            <small class="form-text">Biarkan kosong jika tidak ingin mengubah password.</small>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Update Data</button>
            <a href="/admin/users" class="btn btn-danger">Kembali</a>
        </div>
    </form>
</div>
@endsection