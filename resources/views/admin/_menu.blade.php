<ul class="nav nav-pills mb-3">
    <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">Tổng quan</a></li>
    <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.tours.*') ? 'active' : '' }}" href="{{ route('admin.tours.index') }}">Quản lý tour</a></li>
    <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.bookings.*') ? 'active' : '' }}" href="{{ route('admin.bookings.index') }}">Đơn đặt tour</a></li>
    <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.contacts.*') ? 'active' : '' }}" href="{{ route('admin.contacts.index') }}">Liên hệ</a></li>
</ul>
