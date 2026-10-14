@php $img = isset($tour->tour_id) ? optional($tour->images->sortByDesc('is_primary')->first())->image_url : ''; @endphp
<div class="row g-3">
    <div class="col-md-8"><label class="form-label">Tên tour *</label>
        <input name="tour_name" value="{{ old('tour_name', $tour->tour_name) }}" class="form-control" required></div>
    <div class="col-md-4"><label class="form-label">Trạng thái *</label>
        <select name="status" class="form-select">
            <option value="1" @selected(old('status', $tour->status) == 1)>Đang bán</option>
            <option value="0" @selected(old('status', $tour->status) == 0 && $tour->status !== null)>Ẩn</option>
        </select></div>
    <div class="col-md-6"><label class="form-label">Danh mục *</label>
        <select name="category_id" class="form-select" required>
            <option value="">-- Chọn --</option>
            @foreach($categories as $c)
                <option value="{{ $c->category_id }}" @selected(old('category_id', $tour->category_id) == $c->category_id)>{{ $c->name }}</option>
            @endforeach
        </select></div>
    <div class="col-md-6"><label class="form-label">Điểm đến *</label>
        <select name="destination_id" class="form-select" required>
            <option value="">-- Chọn --</option>
            @foreach($destinations as $d)
                <option value="{{ $d->destination_id }}" @selected(old('destination_id', $tour->destination_id) == $d->destination_id)>{{ $d->name }}</option>
            @endforeach
        </select></div>
    <div class="col-md-3"><label class="form-label">Số ngày *</label>
        <input type="number" min="1" name="duration_days" value="{{ old('duration_days', $tour->duration_days) }}" class="form-control" required></div>
    <div class="col-md-3"><label class="form-label">Số đêm *</label>
        <input type="number" min="0" name="duration_nights" value="{{ old('duration_nights', $tour->duration_nights) }}" class="form-control" required></div>
    <div class="col-md-6"><label class="form-label">Nơi khởi hành</label>
        <input name="departure_place" value="{{ old('departure_place', $tour->departure_place) }}" class="form-control"></div>
    <div class="col-md-6"><label class="form-label">Giá người lớn (đ) *</label>
        <input type="number" min="0" name="adult_price" value="{{ old('adult_price', isset($tour->adult_price) ? (int) $tour->adult_price : '') }}" class="form-control" required></div>
    <div class="col-md-6"><label class="form-label">Giá trẻ em (đ) *</label>
        <input type="number" min="0" name="child_price" value="{{ old('child_price', isset($tour->child_price) ? (int) $tour->child_price : '') }}" class="form-control" required></div>
    <div class="col-12"><label class="form-label">Link ảnh đại diện (https://...)</label>
        <input name="image_url" value="{{ old('image_url', $img) }}" class="form-control" placeholder="https://..."></div>
    <div class="col-12"><label class="form-label">Mô tả</label>
        <textarea name="description" rows="3" class="form-control">{{ old('description', $tour->description) }}</textarea></div>
    <div class="col-md-6"><label class="form-label">Dịch vụ bao gồm</label>
        <textarea name="inclusions" rows="3" class="form-control">{{ old('inclusions', $tour->inclusions) }}</textarea></div>
    <div class="col-md-6"><label class="form-label">Chính sách hủy</label>
        <textarea name="cancellation_policy" rows="3" class="form-control">{{ old('cancellation_policy', $tour->cancellation_policy) }}</textarea></div>
</div>
