@extends('layouts.main')
@section('title', 'Edit Lapangan')

@section('content')

<div class="form-wrapper">
<form action="/admin/fields/{{ $field->id }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="form-group">
        <label>Foto Saat Ini</label><br>
        @if($field->image)
            <img src="{{ asset('image/'.$field->image) }}" width="150" style="border-radius:8px;">
        @endif
        <input type="file" name="image" class="form-control">
    </div>

    <div class="form-group">
        <label>Nama Lapangan</label>
        <input type="text" name="name" class="form-control" value="{{ $field->name }}" required>
    </div>

    <div class="form-group">
        <label>Harga Siang (08:00 - 17:00)</label>
        <input type="number" name="price_day" class="form-control" value="{{ $field->price_day }}" required>
    </div>

    <div class="form-group">
        <label>Harga Malam (17:00 - 23:00)</label>
        <input type="number" name="price_night" class="form-control" value="{{ $field->price_night }}" required>
    </div>

    <div class="form-group">
        <label>Status</label>
        <select name="is_active" class="form-control">
            <option value="1" {{ $field->is_active ? 'selected' : '' }}>Aktif</option>
            <option value="0" {{ !$field->is_active ? 'selected' : '' }}>Non-Aktif</option>
        </select>
    </div>

    <button class="btn btn-primary">Update</button>
    <a href="/admin/fields" class="btn btn-danger">Kembali</a>
</form>
</div>
@endsection