@extends('layouts.main')
@section('title', 'Tambah Lapangan')

@section('content')

<div class="form-wrapper">
<form action="/admin/fields" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="form-group">
        <label>Foto Lapangan</label>
        <input type="file" name="image" class="form-control">
    </div>

    <div class="form-group">
        <label>Nama Lapangan</label>
        <input type="text" name="name" class="form-control" required>
    </div>

    <div class="form-group">
        <label>Harga Siang (08:00 - 17:00)</label>
        <input type="number" name="price_day" class="form-control" required>
    </div>

    <div class="form-group">
        <label>Harga Malam (17:00 - 23:00)</label>
        <input type="number" name="price_night" class="form-control" required>
    </div>

    <div class="form-group">
        <label>Status</label>
        <select name="is_active" class="form-control">
            <option value="1">Aktif</option>
            <option value="0">Non-Aktif</option>
        </select>
    </div>

    <button class="btn btn-primary">Simpan</button>
    <a href="/admin/fields" class="btn btn-danger">Batal</a>
</form>
</div>
@endsection