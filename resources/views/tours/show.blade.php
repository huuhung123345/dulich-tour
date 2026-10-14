@extends('layouts.app')
@section('title', $tour->tour_name)
@section('content')
<div class="container mt-4">
    <div class="row g-4">
        <div class="col-lg-8">
            <img src="{{ $tour->main_image }}" class="img-fluid rounded mb-3 w-100" style="max-height:380px;object-fit:cover" alt="{{ $tour->tour_name }}">
            <h2>{{ $tour->tour_name }}</h2>
            <p class="text-muted">📍 {{ $tour->destination->name ?? '' }} &nbsp; 🕒 {{ $tour->duration_days }} ngày {{ $tour->duration_nights }} đêm &nbsp; 🚩 Khởi hành từ {{ $tour->departure_place }}</p>
            <p>{!! nl2br(e($tour->description)) !!}</p>

            <h4 class="mt-4">Lịch trình</h4>
            @forelse($tour->itineraries->sortBy('day_number') as $i)
                <div class="card mb-2"><div class="card-body">
                    <strong>Ngày {{ $i->day_number }}: {{ $i->title }}</strong>
                    <div>{!! nl2br(e($i->content)) !!}</div>
                </div></div>
            @empty
                <p class="text-muted">Lịch trình đang được cập nhật.</p>
            @endforelse

            <h4 class="mt-4">Dịch vụ bao gồm</h4>
            <p>{!! nl2br(e($tour->inclusions)) !!}</p>
            <h4>Chính sách hủy</h4>
            <p>{!! nl2br(e($tour->cancellation_policy)) !!}</p>

            <h4 class="mt-4">Đánh giá ({{ $reviews->count() }})</h4>
            @forelse($reviews as $r)
                <div class="border-bottom py-2"><strong>{{ $r->user->full_name ?? 'Khách' }}</strong>
                    <span class="text-warning">{{ str_repeat('★', $r->rating) }}{{ str_repeat('☆', 5 - $r->rating) }}</span>
                    <div>{{ $r->comment }}</div></div>
            @empty
                <p class="text-muted">Chưa có đánh giá nào.</p>
            @endforelse
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm sticky-top" style="top:15px">
                <div class="card-body">
                    <div class="fs-3 text-danger fw-bold">{{ number_format($tour->adult_price, 0, ',', '.') }} đ</div>
                    <div class="text-muted mb-3">Người lớn · Trẻ em: {{ number_format($tour->child_price, 0, ',', '.') }} đ</div>
                    <h6>Lịch khởi hành còn chỗ</h6>
                    @forelse($departures as $d)
                        <div class="d-flex justify-content-between align-items-center border rounded p-2 mb-2">
                            <div>
                                <div>{{ $d->start_date->format('d/m/Y') }} → {{ $d->end_date->format('d/m/Y') }}</div>
                                <small class="text-muted">Còn {{ $d->available_seats }} chỗ</small>
                            </div>
                            <a href="{{ route('booking.create', $d) }}" class="btn btn-sm btn-primary">Đặt tour</a>
                        </div>
                    @empty
                        <p class="text-muted">Hiện chưa có lịch khởi hành. Vui lòng <a href="{{ route('contact') }}">liên hệ</a> để được tư vấn.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
