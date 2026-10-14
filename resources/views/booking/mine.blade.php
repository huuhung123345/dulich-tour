@extends('layouts.app')
@section('title', 'Đơn đặt tour của tôi')
@section('content')
<div class="container mt-4">
    <h2>Đơn đặt tour của tôi</h2>
    <div class="table-responsive card card-body shadow-sm">
        <table class="table align-middle mb-0">
            <thead><tr><th>Mã đơn</th><th>Tour</th><th>Khởi hành</th><th>Số người</th><th>Tổng tiền</th><th>Thanh toán</th><th>Trạng thái</th><th></th></tr></thead>
            <tbody>
            @forelse($bookings as $b)
                <tr>
                    <td>{{ $b->booking_code }}<br><small class="text-muted">{{ $b->created_at->format('d/m/Y H:i') }}</small></td>
                    <td>{{ $b->departure->tour->tour_name }}</td>
                    <td>{{ $b->departure->start_date->format('d/m/Y') }}</td>
                    <td>{{ $b->adult_count }} NL, {{ $b->child_count }} TE</td>
                    <td>{{ number_format($b->total_amount, 0, ',', '.') }} đ
                        @if($b->discount_amount > 0)<br><small class="text-success">Giảm {{ number_format($b->discount_amount, 0, ',', '.') }} đ</small>@endif</td>
                    <td>{{ $b->payments->first()->method ?? '' }} – {{ $b->payments->first()->status ?? '' }}</td>
                    <td><span class="badge bg-{{ $b->status == 'CANCELLED' ? 'secondary' : ($b->status == 'PENDING' ? 'warning text-dark' : 'success') }}">{{ $b->status_text }}</span></td>
                    <td>
                        @if(in_array($b->status, ['PENDING','PAID','CONFIRMED']))
                            <form method="POST" action="{{ route('booking.cancel', $b) }}" onsubmit="return confirm('Bạn chắc chắn muốn hủy đơn này?')">
                                @csrf <button class="btn btn-sm btn-outline-danger">Hủy</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="8" class="text-center text-muted">Bạn chưa có đơn đặt tour nào. <a href="{{ route('home') }}">Xem tour</a></td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
