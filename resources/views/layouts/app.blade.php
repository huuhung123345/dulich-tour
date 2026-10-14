<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Du lịch Việt - Đặt tour trực tuyến')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .hero { background: linear-gradient(120deg, #0d6efd, #20c997); color: #fff; padding: 60px 0; }
        .tour-card img { height: 200px; object-fit: cover; }
        footer { background: #212529; color: #adb5bd; }
    </style>
</head>
<body class="d-flex flex-column min-vh-100 bg-light">
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand fw-bold" href="{{ route('home') }}">✈ Du lịch Việt</a>
        <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#nav"><span class="navbar-toggler-icon"></span></button>
        <div class="collapse navbar-collapse" id="nav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">Trang chủ</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('home') }}#tours">Danh sách tour</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('contact') }}">Liên hệ</a></li>
            </ul>
            <ul class="navbar-nav">
                @auth
                    @if(auth()->user()->isAdmin())
                        <li class="nav-item"><a class="nav-link" href="{{ route('admin.dashboard') }}">Quản trị</a></li>
                    @endif
                    <li class="nav-item"><a class="nav-link" href="{{ route('booking.mine') }}">Đơn của tôi</a></li>
                    <li class="nav-item"><span class="nav-link text-white-50">{{ auth()->user()->full_name }}</span></li>
                    <li class="nav-item">
                        <form method="POST" action="{{ route('logout') }}">@csrf
                            <button class="btn btn-link nav-link">Đăng xuất</button>
                        </form>
                    </li>
                @else
                    <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Đăng nhập</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('register') }}">Đăng ký</a></li>
                @endauth
            </ul>
        </div>
    </div>
</nav>

<main class="flex-grow-1">
    <div class="container mt-3">
        @if(session('ok'))
            <div class="alert alert-success alert-dismissible fade show">{{ session('ok') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
        @endif
    </div>
    @yield('content')
</main>

<footer class="py-4 mt-5">
    <div class="container text-center small">
        © {{ date('Y') }} Du lịch Việt – Đồ án website du lịch và đặt tour. Hotline: 0900 000 001 – Email: admin@dulich.vn
    </div>
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
