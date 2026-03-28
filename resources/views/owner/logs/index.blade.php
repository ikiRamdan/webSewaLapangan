@extends('layouts.main')

@section('title','Log Aktivitas')

@section('content')

<div class="table-wrapper">

    <h3 style="margin-bottom:20px;">Log Aktivitas</h3>

    {{-- FILTER --}}
    <div class="table-actions">
        <form method="GET" style="display:flex; gap:10px; flex-wrap:wrap;">

            <select name="user_id" class="form-control">
                <option value="">Semua User</option>
                @foreach($users as $u)
                    <option value="{{ $u->id }}"
                        {{ request('user_id') == $u->id ? 'selected' : '' }}>
                        {{ $u->name }}
                    </option>
                @endforeach
            </select>

            <input type="text" name="action" 
                   placeholder="Action..." 
                   value="{{ request('action') }}"
                   class="form-control">

            <input type="date" name="start_date" class="form-control"
                   value="{{ request('start_date') }}">

            <input type="date" name="end_date" class="form-control"
                   value="{{ request('end_date') }}">

            <button class="btn btn-primary">Filter</button>
            <a href="{{ route('owner.logs') }}" class="btn btn-danger">Reset</a>

        </form>
    </div>

    {{-- TABLE --}}
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>User</th>
                    <th>Action</th>
                    <th>Description</th>
                    <th>Waktu</th>
                </tr>
            </thead>

            <tbody>
                @forelse($logs as $key => $log)
                <tr>

                    <td>{{ $logs->firstItem() + $key }}</td>

                    <td>
                        {{ $log->user->name ?? '-' }}
                        <br>
                        <small>{{ $log->user->role ?? '' }}</small>
                    </td>

                    <td>
                        <span class="badge badge-success">
                            {{ $log->action }}
                        </span>
                    </td>

                    <td>{{ $log->description }}</td>

                    <td>
                        {{ $log->created_at->format('d M Y H:i') }}
                    </td>

                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align:center;">
                        Tidak ada aktivitas
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="pagination-wrapper">
        {{ $logs->links() }}
    </div>

</div>

@endsection