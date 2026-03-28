@extends('layouts.main')
@section('title', 'Daftar Lapangan')
@section('content')

<div class="card-container">
    @foreach($fields as $field)
    <div class="card">
        <img src="{{ asset('image/' . $field->image) }}" 
             alt="{{ $field->name }}" 
             class="card-img">

        <div class="card-body">
            <h3 class="card-title">{{ $field->name }}</h3>
            
            <p class="card-price-label">Harga</p>

            <div>
                <small>Siang (08-17)</small><br>
                <strong>Rp {{ number_format($field->price_day, 0, ',', '.') }}</strong>
            </div>

            <div class="mt-2">
                <small>Malam (17-23)</small><br>
                <strong>Rp {{ number_format($field->price_night, 0, ',', '.') }}</strong>
            </div>
        </div>

        <div class="card-footer">
            <a href="/kasir/fields/{{ $field->id }}" class="btn btn-primary">
                Lihat Detail
            </a>
        </div>
    </div>
    @endforeach
</div>

@endsection