@extends('layouts.app')
@section('title', 'Liên hệ từ khách')
@section('content')
<div class="container mt-4">
    <h2>Liên hệ từ khách hàng</h2>
    @include('admin._menu')
    <div class="table-responsive card card-body shadow-sm">
        <table class="table align-middle mb-0">
            <thead><tr><th>Thời gian</th><th>Họ tên</th><th>Liên hệ</th><th>Chủ đề</th><th>Nội dung</th><th></th></tr></thead>
            <tbody>
            @forelse($contacts as $c)
                <tr>
                    <td>{{ $c->created_at->format('d/m/Y H:i') }}</td>
                    <td>{{ $c->full_name }}</td>
                    <td>{{ $c->email }}<br><small>{{ $c->phone }}</small></td>
                    <td>{{ $c->subject }}</td>
                    <td style="max-width:320px">{{ $c->message }}</td>
                    <td><form method="POST" action="{{ route('admin.contacts.destroy', $c) }}" onsubmit="return confirm('Xóa liên hệ này?')">
                        @csrf @method('DELETE') <button class="btn btn-sm btn-outline-danger">Xóa</button></form></td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center text-muted">Chưa có liên hệ nào.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-3">{{ $contacts->links('pagination::bootstrap-5') }}</div>
</div>
@endsection
