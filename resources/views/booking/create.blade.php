@extends('layouts.app')
@section('title', 'Đặt tour')
@section('content')
<div class="container mt-4" style="max-width:860px">
    <h2>Đặt tour</h2>
    <div class="card mb-3"><div class="card-body">
        <h5>{{ $departure->tour->tour_name }}</h5>
        <div>Khởi hành: <strong>{{ $departure->start_date->format('d/m/Y') }}</strong> – Kết thúc: {{ $departure->end_date->format('d/m/Y') }}
            · Còn <strong>{{ $departure->available_seats }}</strong> chỗ</div>
        <div>Giá: người lớn {{ number_format($departure->tour->adult_price, 0, ',', '.') }} đ · trẻ em {{ number_format($departure->tour->child_price, 0, ',', '.') }} đ</div>
    </div></div>

    <form method="POST" action="{{ route('booking.store', $departure) }}" class="card card-body shadow-sm">
        @csrf
        <div class="row g-3">
            <div class="col-md-3"><label class="form-label">Người lớn *</label>
                <input type="number" id="adult" name="adult_count" min="1" max="20" value="{{ old('adult_count', 1) }}" class="form-control" required></div>
            <div class="col-md-3"><label class="form-label">Trẻ em</label>
                <input type="number" id="child" name="child_count" min="0" max="20" value="{{ old('child_count', 0) }}" class="form-control"></div>
            <div class="col-md-6"><label class="form-label">Mã giảm giá</label>
                <input name="promo_code" value="{{ old('promo_code') }}" class="form-control" placeholder="VD: WELCOME10"></div>

            <div class="col-md-6"><label class="form-label">Người liên hệ *</label>
                <input name="contact_name" value="{{ old('contact_name', auth()->user()->full_name) }}" class="form-control" required></div>
            <div class="col-md-6"><label class="form-label">Số điện thoại *</label>
                <input name="contact_phone" value="{{ old('contact_phone', auth()->user()->phone) }}" class="form-control" required></div>

            <div class="col-12">
                <label class="form-label">Họ tên hành khách (không bắt buộc)</label>
                <div id="passengers" class="row g-2"></div>
            </div>

            <div class="col-md-6"><label class="form-label">Phương thức thanh toán *</label>
                <select name="method" class="form-select" required>
                    <option value="BANK" @selected(old('method')=='BANK')>Chuyển khoản ngân hàng</option>
                    <option value="MOMO" @selected(old('method')=='MOMO')>Ví MoMo</option>
                    <option value="VNPAY" @selected(old('method')=='VNPAY')>VNPAY</option>
                    <option value="CASH" @selected(old('method')=='CASH')>Thanh toán tại văn phòng</option>
                </select></div>
            <div class="col-md-6"><label class="form-label">Ghi chú</label>
                <input name="note" value="{{ old('note') }}" class="form-control"></div>

            <div class="col-12">
                <div class="alert alert-info mb-0">Tạm tính: <strong id="total">0</strong> đ (chưa gồm giảm giá – hệ thống sẽ áp dụng mã khi đặt).</div>
            </div>
            <div class="col-12"><button class="btn btn-primary btn-lg">Xác nhận đặt tour</button>
                <a href="{{ route('tours.show', $departure->tour) }}" class="btn btn-link">Quay lại</a></div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    const adultPrice = {{ (int) $departure->tour->adult_price }};
    const childPrice = {{ (int) $departure->tour->child_price }};
    const oldNames = @json(old('passengers', []));
    const adult = document.getElementById('adult'), child = document.getElementById('child');
    const box = document.getElementById('passengers'), total = document.getElementById('total');

    function render() {
        const a = parseInt(adult.value) || 0, c = parseInt(child.value) || 0, n = a + c;
        const keep = Array.from(box.querySelectorAll('input')).map(i => i.value);
        box.innerHTML = '';
        for (let i = 0; i < n; i++) {
            const col = document.createElement('div'); col.className = 'col-md-6';
            const inp = document.createElement('input');
            inp.name = 'passengers[' + i + ']'; inp.className = 'form-control';
            inp.placeholder = (i < a ? 'Người lớn ' + (i + 1) : 'Trẻ em ' + (i - a + 1));
            inp.value = keep[i] ?? oldNames[i] ?? '';
            col.appendChild(inp); box.appendChild(col);
        }
        total.textContent = (a * adultPrice + c * childPrice).toLocaleString('vi-VN');
    }
    adult.addEventListener('input', render); child.addEventListener('input', render);
    render();
</script>
@endpush
