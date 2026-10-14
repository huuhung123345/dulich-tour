@extends('layouts.app')
@section('title', 'Liên hệ')
@section('content')
<div class="container mt-4" style="max-width:760px">
    <h2>Liên hệ với chúng tôi</h2>
    <p class="text-muted">Địa chỉ: Hà Nội · Hotline: 0900 000 001 · Email: admin@dulich.vn</p>
    <form method="POST" action="{{ route('contact.store') }}" class="card card-body shadow-sm">
        @csrf
        <div class="row g-3">
            <div class="col-md-6"><label class="form-label">Họ tên *</label>
                <input name="full_name" value="{{ old('full_name', auth()->user()->full_name ?? '') }}" class="form-control" required></div>
            <div class="col-md-6"><label class="form-label">Email *</label>
                <input type="email" name="email" value="{{ old('email', auth()->user()->email ?? '') }}" class="form-control" required></div>
            <div class="col-md-6"><label class="form-label">Số điện thoại</label>
                <input name="phone" value="{{ old('phone', auth()->user()->phone ?? '') }}" class="form-control"></div>
            <div class="col-md-6"><label class="form-label">Chủ đề *</label>
                <input name="subject" value="{{ old('subject') }}" class="form-control" required></div>
            <div class="col-12"><label class="form-label">Nội dung *</label>
                <textarea name="message" rows="5" class="form-control" required>{{ old('message') }}</textarea></div>
            <div class="col-12"><button class="btn btn-primary">Gửi liên hệ</button></div>
        </div>
    </form>
</div>
@endsection
