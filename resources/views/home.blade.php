@extends('layouts.app')
@section('title', 'Trang chủ - Du lịch Việt')
@section('content')
<section class="hero text-center">
    <div class="container">
        <h1 class="fw-bold">Khám phá Việt Nam cùng chúng tôi</h1>
        <p class="lead">Tìm và đặt tour du lịch nhanh chóng, giá tốt, lịch trình rõ ràng.</p>
        <form class="row g-2 justify-content-center bg-white p-3 rounded shadow text-dark" method="GET" action="{{ route('home') }}#tours">
            <div class="col-md-3"><input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Tên tour..."></div>
            <div class="col-md-2">
                <select name="destination" class="form-select">
                    <option value="">Điểm đến</option>
                    @foreach($destinations as $d)
                        <option value="{{ $d->destination_id }}" @selected(request('destination') == $d->destination_id)>{{ $d->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="category" class="form-select">
                    <option value="">Loại tour</option>
                    @foreach($categories as $c)
                        <option value="{{ $c->category_id }}" @selected(request('category') == $c->category_id)>{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2"><input type="number" name="max_price" value="{{ request('max_price') }}" class="form-control" placeholder="Giá tối đa"></div>
            <div class="col-md-2 d-grid"><button class="btn btn-primary">Tìm tour</button></div>
        </form>
    </div>
</section>

<div class="container mt-5" id="tours">
    <h2 class="mb-4">Tour nổi bật</h2>
    <div class="row g-4">
        @forelse($tours as $t)
            <div class="col-md-4">
                <div class="card tour-card h-100 shadow-sm">
                    <img src="{{ $t->main_image }}" class="card-img-top" alt="{{ $t->tour_name }}">
                    <div class="card-body d-flex flex-column">
                        <span class="badge bg-info text-dark align-self-start mb-2">{{ $t->category->name ?? '' }}</span>
                        <h5 class="card-title">{{ $t->tour_name }}</h5>
                        <p class="text-muted small mb-1">📍 {{ $t->destination->name ?? '' }} &nbsp; 🕒 {{ $t->duration_days }}N{{ $t->duration_nights }}Đ &nbsp; 🚩 {{ $t->departure_place }}</p>
                        <p class="fs-5 text-danger fw-bold mt-auto mb-2">{{ number_format($t->adult_price, 0, ',', '.') }} đ <small class="text-muted fs-6">/ người lớn</small></p>
                        <a href="{{ route('tours.show', $t) }}" class="btn btn-outline-primary">Xem chi tiết</a>
                    </div>
                </div>
            </div>
        @empty
            <p class="text-center text-muted">Không tìm thấy tour phù hợp.</p>
        @endforelse
    </div>
    <div class="mt-4">{{ $tours->links('pagination::bootstrap-5') }}</div>
</div>
@endsection
