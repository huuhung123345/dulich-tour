@extends('layouts.app')
@section('title', 'Đăng ký')
@section('content')
<div class="container mt-4" style="max-width:520px">
    <div class="card shadow-sm"><div class="card-body">
        <h3 class="mb-3">Đăng ký tài khoản</h3>
        <form method="POST" action="{{ route('register.post') }}">
            @csrf
            <div class="mb-3"><label class="form-label">Họ tên *</label>
                <input name="full_name" value="{{ old('full_name') }}" class="form-control" required></div>
            <div class="mb-3"><label class="form-label">Email *</label>
                <input type="email" name="email" value="{{ old('email') }}" class="form-control" required></div>
            <div class="row">
                <div class="col-md-6 mb-3"><label class="form-label">Số điện thoại</label>
                    <input name="phone" value="{{ old('phone') }}" class="form-control"></div>
                <div class="col-md-6 mb-3"><label class="form-label">Địa chỉ</label>
                    <input name="address" value="{{ old('address') }}" class="form-control"></div>
            </div>
            <div class="mb-3"><label class="form-label">Mật khẩu * (tối thiểu 6 ký tự)</label>
                <input type="password" name="password" class="form-control" required></div>
            <div class="mb-3"><label class="form-label">Nhập lại mật khẩu *</label>
                <input type="password" name="password_confirmation" class="form-control" required></div>
            <button class="btn btn-primary w-100">Đăng ký</button>
        </form>
    </div></div>
</div>
@endsection
