@extends('layouts.app')
@section('title', 'Quản lý tour')
@section('content')
<div class="container mt-4">
    <h2>Quản lý tour</h2>
    @include('admin._menu')
    <div class="d-flex justify-content-between mb-3">
        <form class="d-flex" method="GET">
            <input name="q" value="{{ request('q') }}" class="form-control me-2" placeholder="Tìm theo tên tour">
            <button class="btn btn-outline-primary">Tìm</button>
        </form>
        <a href="{{ route('admin.tours.create') }}" class="btn btn-success">+ Thêm tour</a>
    </div>
    <div class="table-responsive card card-body shadow-sm">
        <table class="table table-hover align-middle mb-0">
            <thead><tr><th>#</th><th>Tên tour</th><th>Điểm đến</th><th>Danh mục</th><th>Thời gian</th><th>Giá NL</th><th>Trạng thái</th><th style="width:170px"></th></tr></thead>
            <tbody>
            @forelse($tours as $t)
                <tr>
                    <td>{{ $t->tour_id }}</td>
                    <td>{{ $t->tour_name }}</td>
                    <td>{{ $t->destination->name ?? '' }}</td>
                    <td>{{ $t->category->name ?? '' }}</td>
                    <td>{{ $t->duration_days }}N{{ $t->duration_nights }}Đ</td>
                    <td>{{ number_format($t->adult_price, 0, ',', '.') }}</td>
                    <td>{!! $t->status ? '<span class="badge bg-success">Đang bán</span>' : '<span class="badge bg-secondary">Ẩn</span>' !!}</td>
                    <td>
                        <a href="{{ route('admin.tours.edit', $t) }}" class="btn btn-sm btn-primary">Sửa</a>
                        <form method="POST" action="{{ route('admin.tours.destroy', $t) }}" class="d-inline" onsubmit="return confirm('Xóa tour này?')">
                            @csrf @method('DELETE') <button class="btn btn-sm btn-danger">Xóa</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="8" class="text-center text-muted">Chưa có tour nào.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-3">{{ $tours->links('pagination::bootstrap-5') }}</div>
</div>
@endsection
