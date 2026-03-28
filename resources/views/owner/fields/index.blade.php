@extends('layouts.main')
@section('title','Data Lapangan')

@section('content')

<div class="table-actions">
    <form method="GET" action="{{ route('owner.fields') }}" class="table-search">
        <input type="text" name="name" placeholder="Nama Lapang" value="{{ request('name') }}">
        
        <button type="submit" class="btn btn-primary">Filter</button>
        <a href="{{ route('owner.fields') }}" class="btn btn-danger">Reset</a>
    </form>
</div>

<div class="table-wrapper">
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Foto</th>
                <th>Nama Lapangan</th>
                <th>Harga Siang</th>
                <th>Harga Malam</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($fields as $key => $field)
            <tr>
                <td>{{ $fields->firstItem() + $key }}</td>

                <td>
                    @if($field->image)
                        <img src="{{ asset('image/'.$field->image) }}" 
                             style="width: 70px; height: 50px; object-fit: cover; border-radius: 6px;">
                    @else
                        <div style="width: 70px; height: 50px; background: #eee; display:flex; align-items:center; justify-content:center;">
                            No Image
                        </div>
                    @endif
                </td>

                <td><strong>{{ $field->name }}</strong></td>

                <td>
                    Rp {{ number_format($field->price_day, 0, ',', '.') }}
                    <br><small>(08:00 - 17:00)</small>
                </td>

                <td>
                    Rp {{ number_format($field->price_night, 0, ',', '.') }}
                    <br><small>(17:00 - 23:00)</small>
                </td>

                <td>
                    <span class="badge {{ $field->is_active ? 'badge-success' : 'badge-danger' }}">
                        {{ $field->is_active ? 'Active' : 'Non-Active' }}
                    </span>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align:center;">Data lapangan tidak ditemukan</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="pagination-wrapper">
    {{ $fields->links() }}
</div>

@endsection