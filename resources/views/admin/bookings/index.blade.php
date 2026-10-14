@extends('layouts.app')
@section('title', 'Quản lý đơn đặt tour')
@section('content')
<div class="container mt-4">
    <h2>Đơn đặt tour</h2>
    @include('admin._menu')
    <form class="mb-3 d-flex" method="GET">
        <select name="status" class="form-select w-auto me-2" onchange="this.form.submit()">
            <option value="">Tất cả trạng thái</option>
            @foreach(\App\Models\Booking::STATUS as $k => $v)
                <option value="{{ $k }}" @selected(request('status') == $k)>{{ $v }}</option>
            @endforeach
        </select>
    </form>
    <div class="table-responsive card card-body shadow-sm">
        <table class="table table-hover align-middle mb-0">
            <thead><tr><th>Mã đơn</th><th>Khách hàng</th><th>Tour / Ngày đi</th><th>Người</th><th>Tổng tiền</th><th>Thanh toán</th><th>Trạng thái</th><th></th></tr></thead>
            <tbody>
            @forelse($bookings as $b)
                <tr>
                    <td><a href="{{ route('admin.bookings.show', $b) }}">{{ $b->booking_code }}</a></td>
                    <td>{{ $b->contact_name }}<br><small class="text-muted">{{ $b->contact_phone }}</small></td>
                    <td>{{ $b->departure->tour->tour_name }}<br><small class="text-muted">{{ $b->departure->start_date->format('d/m/Y') }}</small></td>
                    <td>{{ $b->adult_count + $b->child_count }}</td>
                    <td>{{ number_format($b->total_amount, 0, ',', '.') }} đ</td>
                    <td>{{ $b->payments->first()->method ?? '' }}<br><small>{{ $b->payments->first()->status ?? '' }}</small></td>
                    <td>
                        <form method="POST" action="{{ route('admin.bookings.status', $b) }}">
                            @csrf
                            <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                                @foreach(\App\Models\Booking::STATUS as $k => $v)
                                    <option value="{{ $k }}" @selected($b->status == $k)>{{ $v }}</option>
                                @endforeach
                            </select>
                        </form>
                    </td>
                    <td><a href="{{ route('admin.bookings.show', $b) }}" class="btn btn-sm btn-outline-primary">Chi tiết</a></td>
                </tr>
            @empty
                <tr><td colspan="8" class="text-center text-muted">Chưa có đơn nào.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-3">{{ $bookings->links('pagination::bootstrap-5') }}</div>
</div>
@endsection
