@extends('layouts.app')
@section('title', 'Quản trị')
@section('content')
<div class="container mt-4">
    <h2>Trang quản trị</h2>
    @include('admin._menu')
    <div class="row g-3">
        @foreach([['Số tour', $tours, 'primary'], ['Tổng đơn đặt', $bookings, 'success'], ['Đơn chờ thanh toán', $pending, 'warning'], ['Khách hàng', $users, 'info'], ['Liên hệ', $contacts, 'secondary']] as [$label, $val, $color])
            <div class="col-md-4 col-lg-2">
                <div class="card border-{{ $color }} shadow-sm"><div class="card-body text-center">
                    <div class="fs-2 fw-bold">{{ $val }}</div><div class="text-muted small">{{ $label }}</div>
                </div></div>
            </div>
        @endforeach
        <div class="col-md-8 col-lg-4">
            <div class="card border-danger shadow-sm"><div class="card-body text-center">
                <div class="fs-2 fw-bold text-danger">{{ number_format($revenue, 0, ',', '.') }} đ</div>
                <div class="text-muted small">Doanh thu (thanh toán thành công)</div>
            </div></div>
        </div>
    </div>
</div>
@endsection
