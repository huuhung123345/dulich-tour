@extends('layouts.app')
@section('title', 'Sửa tour')
@section('content')
<div class="container mt-4">
    <h2>Sửa tour #{{ $tour->tour_id }}</h2>
    @include('admin._menu')
    <form method="POST" action="{{ route('admin.tours.update', $tour) }}" class="card card-body shadow-sm mb-4">
        @csrf @method('PUT')
        @include('admin.tours._form')
        <div class="mt-3"><button class="btn btn-primary">Cập nhật</button>
            <a href="{{ route('admin.tours.index') }}" class="btn btn-link">Quay lại</a></div>
    </form>

    <div class="card card-body shadow-sm">
        <h5>Lịch khởi hành</h5>
        <table class="table align-middle">
            <thead><tr><th>Ngày đi</th><th>Ngày về</th><th>Tổng chỗ</th><th>Còn trống</th><th>Trạng thái</th><th></th></tr></thead>
            <tbody>
            @forelse($departures as $d)
                <tr>
                    <td>{{ $d->start_date->format('d/m/Y') }}</td><td>{{ $d->end_date->format('d/m/Y') }}</td>
                    <td>{{ $d->total_seats }}</td><td>{{ $d->available_seats }}</td><td>{{ $d->status }}</td>
                    <td><form method="POST" action="{{ route('admin.departures.destroy', $d) }}" onsubmit="return confirm('Xóa lịch này?')">
                        @csrf @method('DELETE') <button class="btn btn-sm btn-outline-danger">Xóa</button></form></td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-muted text-center">Chưa có lịch khởi hành.</td></tr>
            @endforelse
            </tbody>
        </table>
        <form method="POST" action="{{ route('admin.departures.store', $tour) }}" class="row g-2 align-items-end">
            @csrf
            <div class="col-md-3"><label class="form-label">Ngày đi</label><input type="date" name="start_date" class="form-control" required></div>
            <div class="col-md-3"><label class="form-label">Ngày về</label><input type="date" name="end_date" class="form-control" required></div>
            <div class="col-md-3"><label class="form-label">Tổng số chỗ</label><input type="number" min="1" name="total_seats" value="30" class="form-control" required></div>
            <div class="col-md-3 d-grid"><button class="btn btn-success">+ Thêm lịch</button></div>
        </form>
    </div>
</div>
@endsection
