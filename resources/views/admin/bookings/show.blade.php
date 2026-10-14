@extends('layouts.app')
@section('title', 'Chi tiết đơn ' . $booking->booking_code)
@section('content')
<div class="container mt-4">
    <h2>Chi tiết đơn {{ $booking->booking_code }}</h2>
    @include('admin._menu')
    <div class="row g-3">
        <div class="col-md-6"><div class="card card-body shadow-sm">
            <h5>Thông tin đơn</h5>
            <p class="mb-1">Tour: <strong>{{ $booking->departure->tour->tour_name }}</strong></p>
            <p class="mb-1">Khởi hành: {{ $booking->departure->start_date->format('d/m/Y') }} – {{ $booking->departure->end_date->format('d/m/Y') }}</p>
            <p class="mb-1">Người liên hệ: {{ $booking->contact_name }} – {{ $booking->contact_phone }}</p>
            <p class="mb-1">Tài khoản đặt: {{ $booking->user->full_name }} ({{ $booking->user->email }})</p>
            <p class="mb-1">Ghi chú: {{ $booking->note ?: '—' }}</p>
            <p class="mb-1">Tạm tính: {{ number_format($booking->subtotal, 0, ',', '.') }} đ · Giảm: {{ number_format($booking->discount_amount, 0, ',', '.') }} đ</p>
            <p class="fs-5 text-danger fw-bold">Tổng: {{ number_format($booking->total_amount, 0, ',', '.') }} đ</p>
            <p>Trạng thái: <span class="badge bg-primary">{{ $booking->status_text }}</span></p>
        </div></div>
        <div class="col-md-6"><div class="card card-body shadow-sm">
            <h5>Hành khách</h5>
            <ul>@foreach($booking->passengers as $p)<li>{{ $p->full_name }} ({{ $p->passenger_type == 'ADULT' ? 'Người lớn' : 'Trẻ em' }})</li>@endforeach</ul>
            <h5>Thanh toán</h5>
            <ul>@foreach($booking->payments as $p)<li>{{ $p->method }} – {{ number_format($p->amount, 0, ',', '.') }} đ – {{ $p->status }}</li>@endforeach</ul>
        </div></div>
    </div>
    <a href="{{ route('admin.bookings.index') }}" class="btn btn-link mt-3">← Quay lại danh sách</a>
</div>
@endsection
