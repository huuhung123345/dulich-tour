@extends('layouts.app')
@section('title', 'Thêm tour')
@section('content')
<div class="container mt-4">
    <h2>Thêm tour mới</h2>
    @include('admin._menu')
    <form method="POST" action="{{ route('admin.tours.store') }}" class="card card-body shadow-sm">
        @csrf
        @include('admin.tours._form')
        <div class="mt-3"><button class="btn btn-success">Lưu tour</button>
            <a href="{{ route('admin.tours.index') }}" class="btn btn-link">Hủy</a></div>
    </form>
</div>
@endsection
