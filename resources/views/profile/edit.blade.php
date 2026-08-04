@extends('layouts.app')

@section('title', 'Hồ sơ cá nhân | HomeStayGo')

@section('content')

    <x-alert />

    @php
        $bookingStatusLabels = [
            'pending' => 'Chờ xác nhận',
            'confirmed' => 'Đã xác nhận',
            'checked_in' => 'Đã nhận phòng',
            'completed' => 'Đã hoàn thành',
            'cancelled' => 'Đã hủy',
        ];

        $bookingStatusClasses = [
            'pending' => 'bg-amber-50 text-amber-700 border border-amber-200',
            'confirmed' => 'bg-blue-50 text-blue-700 border border-blue-200',
            'checked_in' => 'bg-violet-50 text-violet-700 border border-violet-200',
            'completed' => 'bg-emerald-50 text-emerald-700 border border-emerald-200',
            'cancelled' => 'bg-red-50 text-red-700 border border-red-200',
        ];

        $avatarUrl = $user->avatar ? Storage::url($user->avatar) : null;
        $avatarText = mb_strtoupper(mb_substr($user->name, 0, 1));
    @endphp

    <main>

        <x-frontend-breadcrumb :items="[
            ['label' => 'Trang chủ', 'url' => route('home')],
            ['label' => 'Hồ sơ cá nhân'],
        ]" />

        <section class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
            <div class="mb-8">
                <p class="font-semibold uppercase tracking-widest text-blue-600">Tài khoản của bạn</p>
                <h1 class="mt-2 text-3xl font-bold tracking-tight text-slate-950 sm:text-4xl">
                    Xin chào,
                    <label class="text-blue-700">
                        {{ auth()->user()->name }}
                    </label>
                </h1>
                <p class="mt-3 max-w-2xl text-slate-500">Quản lý thông tin tài khoản, lịch sử đặt phòng và bảo mật đăng nhập.</p>
            </div>

            <div class="grid gap-8 lg:grid-cols-[310px_minmax(0,1fr)]">
                {{-- Sidebar --}}
                <aside class="space-y-6 lg:sticky lg:top-28 lg:self-start">
                    <nav class="rounded-3xl border border-slate-200 bg-white p-4 shadow-sm" aria-label="Truy cập nhanh">
                        <p class="px-3 pb-3 text-xs font-bold uppercase tracking-widest text-slate-400">Truy cập nhanh</p>
                        <ul class="space-y-1">
                            <li>
                                <a href="#account"
                                    class="group flex items-center gap-3 rounded-xl p-3 text-sm font-semibold text-slate-700 transition hover:bg-blue-50 hover:text-blue-600">
                                    <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-blue-100 text-base transition group-hover:bg-blue-200">👤</span>
                                    Thông tin cá nhân
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('bookings.history') }}"
                                    class="group flex items-center gap-3 rounded-xl p-3 text-sm font-semibold text-slate-700 transition hover:bg-blue-50 hover:text-blue-600">
                                    <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-amber-100 text-base transition group-hover:bg-amber-200">📅</span>
                                    Lịch sử đặt phòng
                                </a>
                            </li>
                            <li>
                                <a href="#security"
                                    class="group flex items-center gap-3 rounded-xl p-3 text-sm font-semibold text-slate-700 transition hover:bg-blue-50 hover:text-blue-600">
                                    <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-emerald-100 text-base transition group-hover:bg-emerald-200">🔐</span>
                                    Đổi mật khẩu
                                </a>
                            </li>
                            @if ($user->isAdmin())
                                <li>
                                    <a href="{{ route('admin.dashboard') }}"
                                        class="group flex items-center gap-3 rounded-xl p-3 text-sm font-semibold text-slate-700 transition hover:bg-blue-50 hover:text-blue-600">
                                        <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-indigo-100 text-base transition group-hover:bg-indigo-200">⚙️</span>
                                        Trang quản trị
                                    </a>
                                </li>
                            @endif
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                        class="cursor-pointer w-full group flex items-center gap-3 rounded-xl p-3 text-sm font-semibold text-red-600 transition hover:bg-red-50">
                                        <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-red-50 text-red-500 transition group-hover:bg-red-100">
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
                                            </svg>
                                        </span>
                                        Đăng xuất
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </nav>
                </aside>

                {{-- Nội dung chính --}}
                <div class="min-w-0 space-y-8">
                    {{-- Thống kê --}}
                    <section class="grid grid-cols-2 gap-4 xl:grid-cols-4">
                        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                            <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-100 text-xl">📋</div>
                            <p class="mt-4 text-2xl font-bold text-slate-900">{{ $user->bookings_count ?? 0 }}</p>
                            <p class="mt-1 text-sm font-medium text-slate-500">Tổng đơn đặt</p>
                        </div>
                        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                            <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-amber-100 text-xl">⏳</div>
                            <p class="mt-4 text-2xl font-bold text-slate-900">{{ $user->active_bookings_count ?? 0 }}</p>
                            <p class="mt-1 text-sm font-medium text-slate-500">Đang xử lý</p>
                        </div>
                        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                            <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-emerald-100 text-xl">✔️</div>
                            <p class="mt-4 text-2xl font-bold text-slate-900">{{ $user->completed_bookings_count ?? 0 }}</p>
                            <p class="mt-1 text-sm font-medium text-slate-500">Đã hoàn thành</p>
                        </div>
                        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                            <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-orange-100 text-xl">⭐</div>
                            <p class="mt-4 text-2xl font-bold text-slate-900">{{ $user->reviews_count ?? 0 }}</p>
                            <p class="mt-1 text-sm font-medium text-slate-500">Đánh giá đã gửi</p>
                        </div>
                    </section>

                    {{-- Booking gần nhất --}}
                    <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-7">
                        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                            <div>
                                <p class="text-sm font-semibold uppercase tracking-widest text-blue-600">Hoạt động gần đây</p>
                                <h2 class="mt-2 text-xl font-bold text-slate-900">Đơn đặt phòng gần nhất</h2>
                            </div>
                            <a href="{{ route('bookings.history') }}"
                                class="inline-flex items-center justify-center rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:border-blue-500 hover:text-blue-600">
                                Xem tất cả
                            </a>
                        </div>

                        @if ($latestBooking)
                            <div class="mt-6 rounded-2xl border border-slate-200 bg-slate-50 p-5">
                                <div class="flex flex-col gap-5 sm:flex-row sm:items-start sm:justify-between">
                                    <div class="min-w-0">
                                        <div class="flex flex-wrap items-center gap-2">
                                            <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $bookingStatusClasses[$latestBooking->status] ?? 'bg-slate-100 text-slate-700' }}">
                                                {{ $bookingStatusLabels[$latestBooking->status] ?? $latestBooking->status }}
                                            </span>
                                            <span class="text-xs font-semibold text-slate-400">{{ $latestBooking->booking_code }}</span>
                                        </div>
                                        <h3 class="mt-4 truncate text-lg font-bold text-slate-900">
                                            {{ $latestBooking->room?->name ?? 'Phòng không còn tồn tại' }}
                                        </h3>
                                        <p class="mt-1 truncate text-sm font-semibold text-blue-600">
                                            {{ $latestBooking->room?->homestay?->name ?? 'Homestay không còn tồn tại' }}
                                        </p>
                                        <div class="mt-4 flex flex-wrap gap-x-5 gap-y-2 text-sm text-slate-500">
                                            <span>Nhận phòng: <strong class="text-slate-700">{{ $latestBooking->check_in?->format('d/m/Y') }}</strong></span>
                                            <span>Trả phòng: <strong class="text-slate-700">{{ $latestBooking->check_out?->format('d/m/Y') }}</strong></span>
                                        </div>
                                    </div>
                                    <div class="shrink-0 sm:text-right">
                                        <p class="text-sm text-slate-500">Tổng tiền</p>
                                        <p class="mt-1 text-xl font-bold text-blue-600">
                                            {{ number_format($latestBooking->total_price, 0, ',', '.') }}đ
                                        </p>
                                        <a href="{{ route('bookings.show', $latestBooking) }}"
                                            class="mt-4 inline-flex items-center justify-center rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-700">
                                            Xem chi tiết
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="mt-6 rounded-2xl border border-dashed border-slate-300 bg-slate-50 px-6 py-10 text-center">
                                <div class="text-5xl">📅</div>
                                <h3 class="mt-4 font-bold text-slate-900">Bạn chưa có đơn đặt phòng</h3>
                                <p class="mt-2 text-sm text-slate-500">Hãy khám phá những Homestay phù hợp với bạn.</p>
                                <a href="{{ route('home') }}#featured"
                                    class="mt-5 inline-flex items-center justify-center rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-700">
                                    Khám phá Homestay
                                </a>
                            </div>
                        @endif
                    </section>

                    {{-- Cập nhật thông tin --}}
                    <section id="account" class="scroll-mt-28 overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
                        <div class="border-b border-slate-200 px-6 py-6 sm:px-8">
                            <p class="text-sm font-semibold uppercase tracking-widest text-blue-600">Thông tin tài khoản</p>
                            <h2 class="mt-2 text-2xl font-bold text-slate-900">Cập nhật hồ sơ</h2>
                            <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-500">Cập nhật ảnh đại diện và thông tin sử dụng khi đặt phòng.</p>
                        </div>

                        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="p-6 sm:p-8"
                            x-data="{
                                preview: null,
                                removeAvatar: {{ old('remove_avatar') ? 'true' : 'false' }}
                            }">
                            @csrf
                            @method('PATCH')

                            <div class="grid gap-8 lg:grid-cols-[220px_minmax(0,1fr)]">
                                {{-- Ảnh đại diện --}}
                                <div class="text-center">
                                    <h3 class="font-bold text-slate-900">Ảnh đại diện</h3>
                                    <p class="mt-1 text-sm leading-6 text-slate-500">JPG, PNG hoặc WEBP, tối đa 2MB.</p>

                                    <div class="mt-5 flex justify-center">
                                        <img x-show="preview && !removeAvatar" x-cloak :src="preview" alt="Xem trước ảnh đại diện"
                                            class="h-32 w-32 rounded-full object-cover ring-4 ring-slate-100 shadow-sm">

                                        <div x-show="!preview && !removeAvatar" class="flex justify-center">
                                            @if ($avatarUrl)
                                                <img src="{{ $avatarUrl }}" alt="{{ $user->name }}"
                                                    class="h-32 w-32 rounded-full object-cover ring-4 ring-slate-100 shadow-sm">
                                            @else
                                                <div class="flex h-32 w-32 items-center justify-center rounded-full bg-blue-100 text-4xl font-bold text-blue-600 ring-4 ring-slate-100 shadow-sm">
                                                    {{ $avatarText ?: '?' }}
                                                </div>
                                            @endif
                                        </div>

                                        <div x-show="removeAvatar" x-cloak
                                            class="flex h-32 w-32 items-center justify-center rounded-full bg-slate-100 text-4xl font-bold text-slate-400 ring-4 ring-slate-100 shadow-sm">
                                            {{ $avatarText ?: '?' }}
                                        </div>
                                    </div>

                                    <input type="hidden" name="remove_avatar" :value="removeAvatar ? 1 : 0">
                                    <input type="file" name="avatar" id="avatar" accept="image/jpeg,image/png,image/webp" class="hidden"
                                        @change="
                                            const file = $event.target.files[0];
                                            if (file) {
                                                preview = URL.createObjectURL(file);
                                                removeAvatar = false;
                                            }
                                        ">

                                    <div class="mt-6 flex flex-col items-center gap-3">
                                        <label for="avatar"
                                            class="inline-flex w-full max-w-[220px] cursor-pointer items-center justify-center rounded-xl bg-blue-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-blue-700">
                                            Chọn ảnh mới
                                        </label>

                                        @if ($user->avatar)
                                            <button type="button"
                                                @click="removeAvatar = true; preview = null; document.getElementById('avatar').value = '';"
                                                class="inline-flex w-full max-w-[220px] cursor-pointer items-center justify-center rounded-xl border border-red-200 bg-white px-4 py-3 text-sm font-semibold text-red-600 transition hover:bg-red-50">
                                                Xóa ảnh hiện tại
                                            </button>
                                        @endif
                                    </div>

                                    @error('avatar')
                                        <p class="mt-3 text-sm font-medium text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Thông tin cá nhân --}}
                                <div class="min-w-0">
                                    <div class="grid gap-5 sm:grid-cols-2">
                                        {{-- Họ tên --}}
                                        <div>
                                            <label for="name" class="text-sm font-semibold text-slate-700">
                                                Họ và tên <span class="text-red-500">*</span>
                                            </label>
                                            <input type="text" id="name" name="name"
                                                value="{{ old('name', $user->name) }}" autocomplete="name"
                                                placeholder="Nhập họ và tên"
                                                @class([
                                                    'mt-2 h-11 w-full rounded-xl border px-4 text-sm outline-none transition',
                                                    'border-red-400 bg-red-50 focus:border-red-500 focus:ring-4 focus:ring-red-100' => $errors->has('name'),
                                                    'border-slate-300 bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-100' => !$errors->has('name'),
                                                ])>
                                            @error('name')
                                                <p class="mt-2 text-sm font-medium text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        {{-- Email --}}
                                        <div>
                                            <label for="email" class="text-sm font-semibold text-slate-700">
                                                Email <span class="text-red-500">*</span>
                                            </label>
                                            <input type="email" id="email" name="email"
                                                value="{{ old('email', $user->email) }}" autocomplete="email"
                                                placeholder="Nhập địa chỉ email"
                                                @class([
                                                    'mt-2 h-11 w-full rounded-xl border px-4 text-sm outline-none transition',
                                                    'border-red-400 bg-red-50 focus:border-red-500 focus:ring-4 focus:ring-red-100' => $errors->has('email'),
                                                    'border-slate-300 bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-100' => !$errors->has('email'),
                                                ])>
                                            @error('email')
                                                <p class="mt-2 text-sm font-medium text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        {{-- Số điện thoại --}}
                                        <div>
                                            <label for="phone" class="text-sm font-semibold text-slate-700">Số điện thoại</label>
                                            <input type="text" id="phone" name="phone"
                                                value="{{ old('phone', $user->phone) }}" maxlength="11"
                                                inputmode="numeric" autocomplete="tel" placeholder="Ví dụ: 0912345678"
                                                @class([
                                                    'mt-2 h-11 w-full rounded-xl border px-4 text-sm outline-none transition',
                                                    'border-red-400 bg-red-50 focus:border-red-500 focus:ring-4 focus:ring-red-100' => $errors->has('phone'),
                                                    'border-slate-300 bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-100' => !$errors->has('phone'),
                                                ])>
                                            @error('phone')
                                                <p class="mt-2 text-sm font-medium text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        {{-- Địa chỉ --}}
                                        <div>
                                            <label for="address" class="text-sm font-semibold text-slate-700">Địa chỉ</label>
                                            <input type="text" id="address" name="address"
                                                value="{{ old('address', $user->address) }}" maxlength="255"
                                                autocomplete="street-address" placeholder="Nhập địa chỉ của bạn"
                                                @class([
                                                    'mt-2 h-11 w-full rounded-xl border px-4 text-sm outline-none transition',
                                                    'border-red-400 bg-red-50 focus:border-red-500 focus:ring-4 focus:ring-red-100' => $errors->has('address'),
                                                    'border-slate-300 bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-100' => !$errors->has('address'),
                                                ])>
                                            @error('address')
                                                <p class="mt-2 text-sm font-medium text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    {{-- Thông tin chỉ đọc --}}
                                    <div class="mt-6 grid gap-4 sm:grid-cols-2">
                                        <div class="rounded-xl bg-slate-50 px-4 py-3">
                                            <p class="text-xs font-medium text-slate-400">Loại tài khoản</p>
                                            <p class="mt-1 text-sm font-semibold text-slate-700">
                                                {{ $user->role === 'admin' ? 'Quản trị viên' : 'Khách hàng' }}
                                            </p>
                                        </div>
                                        <div class="rounded-xl bg-slate-50 px-4 py-3">
                                            <p class="text-xs font-medium text-slate-400">Ngày tham gia</p>
                                            <p class="mt-1 text-sm font-semibold text-slate-700">
                                                {{ $user->created_at->format('d/m/Y') }}
                                            </p>
                                        </div>
                                    </div>

                                    <div class="mt-7 flex items-center justify-end gap-3 border-t border-slate-200 pt-6">
                                        <button type="reset"
                                            class="inline-flex cursor-pointer items-center justify-center rounded-xl border border-slate-300 bg-white px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                                            Hủy thay đổi
                                        </button>
                                        <button type="submit"
                                            class="inline-flex cursor-pointer items-center justify-center rounded-xl bg-blue-600 px-6 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700 focus:ring-4 focus:ring-blue-200">
                                            Lưu thông tin
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </section>

                    {{-- Đổi mật khẩu --}}
                    <section id="security" class="scroll-mt-28 rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
                        <div class="border-b border-slate-200 pb-6">
                            <p class="text-sm font-semibold uppercase tracking-widest text-emerald-600">Bảo mật tài khoản</p>
                            <h2 class="mt-2 text-2xl font-bold text-slate-900">Đổi mật khẩu</h2>
                            <p class="mt-2 text-sm leading-6 text-slate-500">
                                Sử dụng mật khẩu mạnh và không chia sẻ mật khẩu với người khác.
                            </p>
                        </div>

                        @if (session('status') === 'password-updated')
                            <div class="mt-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-semibold text-emerald-700">
                                Đổi mật khẩu thành công.
                            </div>
                        @endif

                        <form method="POST" action="{{ route('password.update') }}" class="mt-7">
                            @csrf
                            @method('PUT')

                            <div class="grid gap-5 sm:grid-cols-2">
                                {{-- Mật khẩu hiện tại --}}
                                <div class="sm:col-span-2">
                                    <label for="update_password_current_password" class="text-sm font-semibold text-slate-700">
                                        Mật khẩu hiện tại <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative mt-2" x-data="{ show: false }">
                                        <input
                                            id="update_password_current_password"
                                            name="current_password"
                                            :type="show ? 'text' : 'password'"
                                            autocomplete="current-password"
                                            placeholder="Nhập mật khẩu hiện tại"
                                            class="h-11 w-full rounded-xl border px-4 pr-11 text-sm outline-none transition
                                                {{ $errors->updatePassword->has('current_password')
                                                    ? 'border-red-400 bg-red-50 focus:border-red-500 focus:ring-4 focus:ring-red-100'
                                                    : 'border-slate-300 focus:border-blue-500 focus:ring-4 focus:ring-blue-100' }}"
                                        >
                                        <button
                                            type="button"
                                            @click="show = !show"
                                            class="absolute inset-y-0 right-0 flex w-11 cursor-pointer items-center justify-center text-slate-400 transition hover:text-blue-600"
                                            :aria-label="show ? 'Ẩn mật khẩu' : 'Hiện mật khẩu'"
                                        >
                                            <svg x-show="!show" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                            <svg x-show="show" x-cloak class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                                            </svg>
                                        </button>
                                    </div>
                                    @if ($errors->updatePassword->has('current_password'))
                                        <p class="mt-2 text-sm font-medium text-red-600">
                                            {{ $errors->updatePassword->first('current_password') }}
                                        </p>
                                    @endif
                                </div>

                                {{-- Mật khẩu mới --}}
                                <div>
                                    <label for="update_password_password" class="text-sm font-semibold text-slate-700">
                                        Mật khẩu mới <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative mt-2" x-data="{ show: false }">
                                        <input
                                            id="update_password_password"
                                            name="password"
                                            :type="show ? 'text' : 'password'"
                                            autocomplete="new-password"
                                            placeholder="Nhập mật khẩu mới"
                                            class="h-11 w-full rounded-xl border px-4 pr-11 text-sm outline-none transition
                                                {{ $errors->updatePassword->has('password')
                                                    ? 'border-red-400 bg-red-50 focus:border-red-500 focus:ring-4 focus:ring-red-100'
                                                    : 'border-slate-300 focus:border-blue-500 focus:ring-4 focus:ring-blue-100' }}"
                                        >
                                        <button
                                            type="button"
                                            @click="show = !show"
                                            class="absolute inset-y-0 right-0 flex w-11 cursor-pointer items-center justify-center text-slate-400 transition hover:text-blue-600"
                                            :aria-label="show ? 'Ẩn mật khẩu' : 'Hiện mật khẩu'"
                                        >
                                            <svg x-show="!show" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                            <svg x-show="show" x-cloak class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                                            </svg>
                                        </button>
                                    </div>
                                    @if ($errors->updatePassword->has('password'))
                                        <p class="mt-2 text-sm font-medium text-red-600">
                                            {{ $errors->updatePassword->first('password') }}
                                        </p>
                                    @endif
                                </div>

                                {{-- Xác nhận mật khẩu --}}
                                <div>
                                    <label for="update_password_password_confirmation" class="text-sm font-semibold text-slate-700">
                                        Xác nhận mật khẩu <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative mt-2" x-data="{ show: false }">
                                        <input
                                            id="update_password_password_confirmation"
                                            name="password_confirmation"
                                            :type="show ? 'text' : 'password'"
                                            autocomplete="new-password"
                                            placeholder="Nhập lại mật khẩu mới"
                                            class="h-11 w-full rounded-xl border border-slate-300 px-4 pr-11 text-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                                        >
                                        <button
                                            type="button"
                                            @click="show = !show"
                                            class="absolute inset-y-0 right-0 flex w-11 cursor-pointer items-center justify-center text-slate-400 transition hover:text-blue-600"
                                            :aria-label="show ? 'Ẩn mật khẩu' : 'Hiện mật khẩu'"
                                        >
                                            <svg x-show="!show" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                            <svg x-show="show" x-cloak class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                                            </svg>
                                        </button>
                                    </div>
                                    @if ($errors->updatePassword->has('password_confirmation'))
                                        <p class="mt-2 text-sm font-medium text-red-600">
                                            {{ $errors->updatePassword->first('password_confirmation') }}
                                        </p>
                                    @endif
                                </div>
                            </div>

                            <div class="mt-7 flex justify-end border-t border-slate-200 pt-6">
                                <button type="submit"
                                    class="inline-flex cursor-pointer items-center justify-center rounded-xl bg-emerald-600 px-6 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700 focus:ring-4 focus:ring-emerald-200">
                                    Cập nhật mật khẩu
                                </button>
                            </div>
                        </form>
                    </section>

                    {{-- Xóa tài khoản --}}
                    <section class="rounded-3xl border border-red-200 bg-white p-6 shadow-sm sm:p-8"
                        x-data="{ deleteOpen: {{ $errors->userDeletion->isNotEmpty() ? 'true' : 'false' }} }"
                        @keydown.escape.window="deleteOpen = false">
                        <div class="flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">
                            <div>
                                <p class="text-sm font-semibold uppercase tracking-widest text-red-600">Khu vực nguy hiểm</p>
                                <h2 class="mt-2 text-xl font-bold text-slate-900">Xóa tài khoản</h2>
                                <p class="mt-2 max-w-xl text-sm leading-6 text-slate-500">
                                    Tài khoản và dữ liệu liên quan sẽ bị xóa vĩnh viễn. Hành động này không thể hoàn tác.
                                </p>
                            </div>
                            <button type="button" @click="deleteOpen = true"
                                class="inline-flex shrink-0 cursor-pointer items-center justify-center rounded-xl border border-red-300 bg-red-50 px-5 py-3 text-sm font-semibold text-red-600 transition hover:bg-red-100">
                                Xóa tài khoản
                            </button>
                        </div>

                        {{-- Modal xóa tài khoản --}}
                        <div x-show="deleteOpen" x-cloak x-transition.opacity
                            class="fixed inset-0 z-[100] flex items-center justify-center p-4" role="dialog" aria-modal="true">
                            <button type="button" @click="deleteOpen = false"
                                class="absolute inset-0 cursor-default bg-slate-950/50 backdrop-blur-[2px]"
                                aria-label="Đóng modal"></button>

                            <div x-show="deleteOpen" x-transition
                                class="relative z-10 w-full max-w-md rounded-3xl bg-white p-6 shadow-2xl sm:p-7">
                                <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-red-100 text-2xl">⚠️</div>
                                <h3 class="mt-5 text-xl font-bold text-slate-900">Xác nhận xóa tài khoản</h3>
                                <p class="mt-2 text-sm leading-6 text-slate-500">
                                    Nhập mật khẩu hiện tại để xác nhận bạn muốn xóa tài khoản này.
                                </p>

                                <form method="POST" action="{{ route('profile.destroy') }}" class="mt-6">
                                    @csrf
                                    @method('DELETE')

                                    <label for="delete_account_password" class="text-sm font-semibold text-slate-700">
                                        Mật khẩu hiện tại <span class="text-red-500">*</span>
                                    </label>
                                    <input type="password" id="delete_account_password" name="password"
                                        autocomplete="current-password" placeholder="Nhập mật khẩu để xác nhận"
                                        class="mt-2 h-11 w-full rounded-xl border px-4 text-sm outline-none transition
                                            {{ $errors->userDeletion->has('password')
                                                ? 'border-red-400 bg-red-50 focus:ring-4 focus:ring-red-100'
                                                : 'border-slate-300 focus:border-red-400 focus:ring-4 focus:ring-red-100' }}">

                                    @if ($errors->userDeletion->has('password'))
                                        <p class="mt-2 text-sm font-medium text-red-600">
                                            {{ $errors->userDeletion->first('password') }}
                                        </p>
                                    @endif

                                    <div class="mt-6 grid grid-cols-2 gap-3">
                                        <button type="button" @click="deleteOpen = false"
                                            class="cursor-pointer rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                                            Hủy
                                        </button>
                                        <button type="submit"
                                            class="cursor-pointer rounded-xl bg-red-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-red-700 focus:ring-4 focus:ring-red-200">
                                            Xóa vĩnh viễn
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </section>
    </main>

@endsection
