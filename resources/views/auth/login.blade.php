@extends('layouts.app')
@section('title', 'Đăng nhập')
@section('content')
<div class="container mt-4" style="max-width:440px">
    <div class="card shadow-sm"><div class="card-body">
        <h3 class="mb-3">Đăng nhập</h3>
        <form method="POST" action="{{ route('login.post') }}">
            @csrf
            <div class="mb-3"><label class="form-label">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" class="form-control" required autofocus></div>
            <div class="mb-3"><label class="form-label">Mật khẩu</label>
                <input type="password" name="password" class="form-control" required></div>
            <button class="btn btn-primary w-100">Đăng nhập</button>
        </form>
        <p class="mt-3 mb-0 text-center">Chưa có tài khoản? <a href="{{ route('register') }}">Đăng ký</a></p>
    </div></div>
</div>
@endsection
