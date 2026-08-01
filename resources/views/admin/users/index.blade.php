@extends('layouts.admin')

@section('title', 'Quản lý tài khoản | HomeStayGo')

@section('page-title', 'Quản lý tài khoản')

@section('content')
    @php
        $hasFilters =
            request()->filled('search') || request()->filled('booking_activity') || request()->filled('status');
    @endphp

    <div class="mx-auto max-w-screen-2xl">
        <x-alert />

        {{-- Giới thiệu --}}
        <section class="mb-6 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <h2 class="text-xl font-bold text-slate-900 sm:text-2xl">
                        Danh sách tài khoản
                    </h2>

                    <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-500">
                        Theo dõi thông tin, hoạt động đặt phòng và quản lý trạng thái
                        tài khoản trong hệ thống HomeStayGo.
                    </p>
                </div>

                <div
                    class="inline-flex w-fit items-center gap-2 rounded-xl bg-blue-50 px-4 py-3 text-sm font-semibold text-blue-700">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="1.8" class="h-5 w-5">
                        <circle cx="9" cy="8" r="4"></circle>
                        <path d="M3 21a6 6 0 0 1 12 0"></path>
                        <path d="M16 8h5"></path>
                        <path d="M18.5 5.5v5"></path>
                    </svg>

                    <span>
                        {{ number_format($statistics['total'] ?? 0, 0, ',', '.') }}
                        tài khoản
                    </span>
                </div>
            </div>
        </section>

        {{-- Thống kê --}}
        <section class="mb-6 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            {{-- Tổng tài khoản --}}
            <article
                class="group rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <p class="text-sm font-medium text-slate-500">
                            Tổng tài khoản
                        </p>

                        <p class="mt-2 text-2xl font-bold text-slate-900">
                            {{ number_format($statistics['total'] ?? 0, 0, ',', '.') }}
                        </p>
                    </div>

                    <span
                        class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-blue-600 transition group-hover:bg-blue-100">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.8" class="h-6 w-6">
                            <circle cx="9" cy="8" r="4"></circle>
                            <path d="M3 21a6 6 0 0 1 12 0"></path>
                            <path d="M16 5.5a4 4 0 0 1 0 7"></path>
                            <path d="M17 15a5 5 0 0 1 4 5"></path>
                        </svg>
                    </span>
                </div>

                <p class="mt-4 text-xs leading-5 text-slate-400">
                    Toàn bộ tài khoản đang có trong hệ thống.
                </p>
            </article>

            {{-- Tài khoản mới --}}
            <article
                class="group rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <p class="text-sm font-medium text-slate-500">
                            Mới trong tháng
                        </p>

                        <p class="mt-2 text-2xl font-bold text-slate-900">
                            {{ number_format($statistics['new_this_month'] ?? 0, 0, ',', '.') }}
                        </p>
                    </div>

                    <span
                        class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-amber-50 text-amber-600 transition group-hover:bg-amber-100">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.8" class="h-6 w-6">
                            <circle cx="9" cy="8" r="4"></circle>
                            <path d="M3 21a6 6 0 0 1 12 0"></path>
                            <path d="M18 8v6"></path>
                            <path d="M15 11h6"></path>
                        </svg>
                    </span>
                </div>

                <p class="mt-4 text-xs leading-5 text-slate-400">
                    Số tài khoản đăng ký trong tháng hiện tại.
                </p>
            </article>

            {{-- Hoạt động --}}
            <article
                class="group rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <p class="text-sm font-medium text-slate-500">
                            Đang hoạt động
                        </p>

                        <p class="mt-2 text-2xl font-bold text-slate-900">
                            {{ number_format($statistics['active'] ?? 0, 0, ',', '.') }}
                        </p>
                    </div>

                    <span
                        class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600 transition group-hover:bg-emerald-100">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.8" class="h-6 w-6">
                            <circle cx="12" cy="12" r="9"></circle>
                            <path d="m8 12 2.5 2.5L16 9"></path>
                        </svg>
                    </span>
                </div>

                <p class="mt-4 text-xs leading-5 text-slate-400">
                    Tài khoản có thể sử dụng đầy đủ chức năng.
                </p>
            </article>

            {{-- Tạm khóa --}}
            <article
                class="group rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <p class="text-sm font-medium text-slate-500">
                            Tạm khóa
                        </p>

                        <p class="mt-2 text-2xl font-bold text-slate-900">
                            {{ number_format($statistics['inactive'] ?? 0, 0, ',', '.') }}
                        </p>
                    </div>

                    <span
                        class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-red-50 text-red-600 transition group-hover:bg-red-100">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.8" class="h-6 w-6">
                            <circle cx="12" cy="12" r="9"></circle>
                            <path d="M6.5 6.5 17.5 17.5"></path>
                        </svg>
                    </span>
                </div>

                <p class="mt-4 text-xs leading-5 text-slate-400">
                    Tài khoản đang bị giới hạn một số chức năng.
                </p>
            </article>
        </section>

        {{-- Danh sách tài khoản --}}
        <section class="rounded-2xl border border-slate-200 bg-white shadow-sm">
            {{-- Tiêu đề và bộ lọc --}}
            <div class="rounded-t-2xl border-b border-slate-200 bg-slate-50/70 p-5 sm:p-6">
                <div class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h3 class="font-bold text-slate-900">
                            Tài khoản người dùng
                        </h3>

                        <p class="mt-1 text-sm text-slate-500">
                            Hiển thị {{ $users->count() }} trong tổng số
                            {{ $users->total() }} tài khoản.
                        </p>
                    </div>

                    @if ($hasFilters)
                        <span
                            class="inline-flex w-fit items-center gap-2 rounded-full bg-blue-100 px-3 py-1.5 text-xs font-semibold text-blue-700">
                            <span class="h-2 w-2 rounded-full bg-blue-500"></span>
                            Đang áp dụng bộ lọc
                        </span>
                    @endif
                </div>

                <form method="GET" action="{{ route('admin.users.index') }}"
                    class="grid gap-4 md:grid-cols-2 lg:grid-cols-12">
                    {{-- Tìm kiếm --}}
                    <div class="md:col-span-2 lg:col-span-5">
                        <label for="search" class="mb-2 block text-sm font-semibold text-slate-700">
                            Tìm kiếm tài khoản
                        </label>

                        <div class="relative">
                            <span
                                class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="1.8" class="h-5 w-5">
                                    <circle cx="11" cy="11" r="7"></circle>
                                    <path d="m20 20-3.5-3.5"></path>
                                </svg>
                            </span>

                            <input id="search" type="search" name="search" value="{{ request('search') }}"
                                placeholder="Nhập tên, email hoặc số điện thoại..."
                                class="w-full rounded-xl border border-slate-300 bg-white py-3 pl-11 pr-4 text-sm text-slate-900 shadow-sm outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                                onsearch="this.form.submit()" oninput="if (this.value === '') this.form.submit()">
                        </div>
                    </div>

                    {{-- Hoạt động đặt phòng --}}
                    <div class="lg:col-span-2">
                        <label for="booking_activity" class="mb-2 block text-sm font-semibold text-slate-700">
                            Hoạt động đặt phòng
                        </label>

                        <select id="booking_activity" name="booking_activity"
                            class="w-full cursor-pointer rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100">
                            <option value="">
                                Tất cả
                            </option>

                            <option value="has_booking" @selected(request('booking_activity') === 'has_booking')>
                                Đã từng đặt phòng
                            </option>

                            <option value="no_booking" @selected(request('booking_activity') === 'no_booking')>
                                Chưa từng đặt phòng
                            </option>
                        </select>
                    </div>

                    {{-- Trạng thái --}}
                    <div class="lg:col-span-2">
                        <label for="status" class="mb-2 block text-sm font-semibold text-slate-700">
                            Trạng thái
                        </label>

                        <select id="status" name="status"
                            class="w-full cursor-pointer rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100">
                            <option value="">
                                Tất cả trạng thái
                            </option>

                            <option value="active" @selected(request('status') === 'active')>
                                Hoạt động
                            </option>

                            <option value="inactive" @selected(request('status') === 'inactive')>
                                Tạm khóa
                            </option>
                        </select>
                    </div>

                    {{-- Đặt lại --}}
                    <div class="flex items-end lg:col-span-1">
                        @if ($hasFilters)
                            <a href="{{ route('admin.users.index') }}" title="Đặt lại bộ lọc"
                                class="inline-flex w-full items-center justify-center rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm font-semibold text-slate-600 shadow-sm transition hover:border-blue-300 hover:bg-blue-50 hover:text-blue-600">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="1.8" class="h-5 w-5">
                                    <path d="M3 12a9 9 0 1 0 3-6.7"></path>
                                    <path d="M3 4v6h6"></path>
                                </svg>

                                <span class="ml-2 lg:hidden">
                                    Đặt lại
                                </span>
                            </a>
                        @else
                            <button type="button" disabled title="Chưa sử dụng bộ lọc"
                                class="inline-flex w-full cursor-not-allowed items-center justify-center rounded-xl border border-slate-200 bg-slate-100 px-4 py-3 text-sm font-semibold text-slate-400">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="1.8" class="h-5 w-5">
                                    <path d="M3 12a9 9 0 1 0 3-6.7"></path>
                                    <path d="M3 4v6h6"></path>
                                </svg>

                                <span class="ml-2 lg:hidden">
                                    Đặt lại
                                </span>
                            </button>
                        @endif
                    </div>

                    {{-- Nút lọc --}}
                    <div class="flex items-end lg:col-span-2">
                        <button type="submit"
                            class="inline-flex w-full cursor-pointer items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-200">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="1.8" class="h-5 w-5">
                                <path d="M4 5h16"></path>
                                <path d="M7 12h10"></path>
                                <path d="M10 19h4"></path>
                            </svg>

                            Lọc tài khoản
                        </button>
                    </div>
                </form>
            </div>

            @if ($users->count())
                {{-- Bảng tài khoản --}}
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[1180px] border-collapse text-left">
                        <thead>
                            <tr
                                class="border-b border-slate-200 bg-slate-50 text-xs font-bold uppercase tracking-wider text-slate-500">
                                <th scope="col" class="px-5 py-4 sm:px-6">
                                    Tài khoản
                                </th>

                                <th scope="col" class="px-5 py-4 sm:px-6">
                                    Liên hệ
                                </th>

                                <th scope="col" class="px-5 py-4 sm:px-6">
                                    Hoạt động
                                </th>

                                <th scope="col" class="px-5 py-4 sm:px-6">
                                    Trạng thái
                                </th>

                                <th scope="col" class="px-5 py-4 sm:px-6">
                                    Đã thanh toán
                                </th>

                                <th scope="col" class="px-5 py-4 text-center sm:px-6">
                                    Thao tác
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100 bg-white text-sm">
                            @foreach ($users as $user)
                                @php
                                    $nameParts = preg_split('/\s+/', trim($user->name ?? ''));

                                    $avatarText = collect($nameParts)
                                        ->filter()
                                        ->take(2)
                                        ->map(fn($part) => mb_strtoupper(mb_substr($part, 0, 1)))
                                        ->implode('');

                                    $isCurrentUser = (int) auth()->id() === (int) $user->id;

                                    $avatarUrl = null;

                                    if (!empty($user->avatar)) {
                                        $avatarUrl = \Illuminate\Support\Str::startsWith($user->avatar, [
                                            'http://',
                                            'https://',
                                        ])
                                            ? $user->avatar
                                            : asset('storage/' . ltrim($user->avatar, '/'));
                                    }

                                    $totalPaid = (int) ($user->total_paid ?? 0);

                                    $successfulPayments = (int) ($user->successful_payments_count ?? 0);
                                @endphp

                                <tr class="transition hover:bg-slate-50/80">
                                    {{-- Tài khoản --}}
                                    <td class="whitespace-nowrap px-5 py-5 align-middle sm:px-6">
                                        <div class="flex items-center gap-3">
                                            @if ($avatarUrl)
                                                <img src="{{ $avatarUrl }}"
                                                    alt="Ảnh đại diện của {{ $user->name }}"
                                                    class="h-11 w-11 shrink-0 rounded-full border border-slate-200 object-cover"
                                                    loading="lazy">
                                            @else
                                                <div
                                                    class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-blue-100 text-sm font-bold text-blue-700">
                                                    {{ $avatarText ?: '?' }}
                                                </div>
                                            @endif

                                            <div class="min-w-0">
                                                <div class="flex items-center gap-2">
                                                    <a href="{{ route('admin.users.show', $user) }}"
                                                        class="max-w-44 truncate font-semibold text-slate-900 transition hover:text-blue-600"
                                                        title="{{ $user->name }}">
                                                        {{ $user->name }}
                                                    </a>

                                                    @if ($isCurrentUser)
                                                        <span
                                                            class="rounded-full bg-blue-50 px-2 py-0.5 text-[10px] font-bold uppercase tracking-wide text-blue-600">
                                                            Bạn
                                                        </span>
                                                    @endif
                                                </div>

                                                <p class="mt-1 max-w-48 truncate text-xs text-slate-400"
                                                    title="{{ $user->email }}">
                                                    {{ $user->email }}
                                                </p>
                                            </div>
                                        </div>
                                    </td>

                                    {{-- Liên hệ --}}
                                    <td class="px-5 py-5 align-middle sm:px-6">
                                        <div class="max-w-52">
                                            <p class="font-medium text-slate-700">
                                                {{ $user->phone ?: 'Chưa có số điện thoại' }}
                                            </p>

                                            <p class="mt-1 truncate text-xs text-slate-400" title="{{ $user->address }}">
                                                {{ $user->address ?: 'Chưa có địa chỉ' }}
                                            </p>
                                        </div>
                                    </td>

                                    {{-- Hoạt động --}}
                                    <td class="px-5 py-5 align-middle sm:px-6">
                                        <div class="flex flex-wrap gap-2">
                                            <span
                                                class="inline-flex items-center gap-1.5 rounded-lg bg-blue-50 px-2.5 py-1.5 text-xs font-semibold text-blue-700"
                                                title="Số đơn đặt phòng">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                    fill="none" stroke="currentColor" stroke-width="1.8"
                                                    class="h-3.5 w-3.5">
                                                    <rect x="3" y="5" width="18" height="16" rx="2">
                                                    </rect>
                                                    <path d="M8 3v4"></path>
                                                    <path d="M16 3v4"></path>
                                                    <path d="M3 10h18"></path>
                                                </svg>

                                                {{ number_format($user->bookings_count ?? 0, 0, ',', '.') }}
                                                đơn
                                            </span>

                                            <span
                                                class="inline-flex items-center gap-1.5 rounded-lg bg-amber-50 px-2.5 py-1.5 text-xs font-semibold text-amber-700"
                                                title="Số đánh giá">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                    fill="none" stroke="currentColor" stroke-width="1.8"
                                                    class="h-3.5 w-3.5">
                                                    <path d="M4 4h16v13H8l-4 4V4Z"></path>
                                                </svg>

                                                {{ number_format($user->reviews_count ?? 0, 0, ',', '.') }}
                                                đánh giá
                                            </span>
                                        </div>
                                    </td>

                                    {{-- Trạng thái --}}
                                    <td class="whitespace-nowrap px-5 py-5 align-middle sm:px-6">
                                        @if ($user->status === 'active')
                                            <span
                                                class="inline-flex items-center gap-2 rounded-full border border-emerald-200 bg-emerald-50 px-3 py-1.5 text-xs font-semibold text-emerald-700">
                                                <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                                                Hoạt động
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center gap-2 rounded-full border border-red-200 bg-red-50 px-3 py-1.5 text-xs font-semibold text-red-700">
                                                <span class="h-2 w-2 rounded-full bg-red-500"></span>
                                                Tạm khóa
                                            </span>
                                        @endif
                                    </td>

                                    {{-- Thanh toán --}}
                                    <td class="whitespace-nowrap px-5 py-5 align-middle sm:px-6">
                                        @if ($totalPaid > 0)
                                            <p class="font-bold text-emerald-600">
                                                {{ number_format($totalPaid, 0, ',', '.') }}đ
                                            </p>

                                            <p class="mt-1 text-xs text-slate-500">
                                                {{ number_format($successfulPayments, 0, ',', '.') }}
                                                giao dịch thành công
                                            </p>
                                        @else
                                            <p class="font-semibold text-slate-500">
                                                0đ
                                            </p>

                                            <p class="mt-1 text-xs text-slate-400">
                                                Chưa có thanh toán
                                            </p>
                                        @endif
                                    </td>

                                    {{-- Thao tác --}}
                                    <td class="whitespace-nowrap px-5 py-5 text-center align-middle sm:px-6">
                                        <details data-action-menu class="group relative inline-block text-left">
                                            <summary
                                                class="flex h-10 w-10 cursor-pointer list-none items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-500 shadow-sm transition hover:border-blue-300 hover:bg-blue-50 hover:text-blue-600"
                                                title="Mở menu thao tác">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                    fill="currentColor" class="h-5 w-5">
                                                    <circle cx="12" cy="5" r="1.8"></circle>
                                                    <circle cx="12" cy="12" r="1.8"></circle>
                                                    <circle cx="12" cy="19" r="1.8"></circle>
                                                </svg>
                                            </summary>

                                            <div
                                                class="absolute right-0 z-50 mt-2 w-60 overflow-hidden rounded-xl border border-slate-200 bg-white text-left shadow-xl">
                                                {{-- Xem chi tiết --}}
                                                <a href="{{ route('admin.users.show', $user) }}"
                                                    class="flex items-center gap-3 px-4 py-3 text-sm font-medium text-slate-700 transition hover:bg-slate-50">
                                                    <span
                                                        class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-100 text-slate-600">
                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                            fill="none" stroke="currentColor" stroke-width="1.8"
                                                            class="h-4 w-4">
                                                            <path
                                                                d="M2.5 12s3.5-6 9.5-6 9.5 6 9.5 6-3.5 6-9.5 6-9.5-6-9.5-6Z">
                                                            </path>
                                                            <circle cx="12" cy="12" r="2.5"></circle>
                                                        </svg>
                                                    </span>

                                                    <span>Xem chi tiết</span>
                                                </a>

                                                <div class="border-t border-slate-100"></div>

                                                @if (!$isCurrentUser)
                                                    <form method="POST"
                                                        action="{{ route('admin.users.update-status', $user) }}"
                                                        onsubmit="return confirm(
                                                            '{{ $user->status === 'active'
                                                                ? 'Bạn có chắc muốn khóa tài khoản ' . addslashes($user->name) . ' không?'
                                                                : 'Bạn có chắc muốn mở khóa tài khoản ' . addslashes($user->name) . ' không?' }}'
                                                        )">
                                                        @csrf
                                                        @method('PATCH')

                                                        <input type="hidden" name="status"
                                                            value="{{ $user->status === 'active' ? 'inactive' : 'active' }}">

                                                        <button type="submit"
                                                            class="flex w-full cursor-pointer items-center gap-3 px-4 py-3 text-left text-sm font-medium transition
                                                                {{ $user->status === 'active' ? 'text-red-700 hover:bg-red-50' : 'text-emerald-700 hover:bg-emerald-50' }}">
                                                            <span
                                                                class="flex h-8 w-8 items-center justify-center rounded-lg
                                                                    {{ $user->status === 'active' ? 'bg-red-50' : 'bg-emerald-50' }}">
                                                                @if ($user->status === 'active')
                                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                                        viewBox="0 0 24 24" fill="none"
                                                                        stroke="currentColor" stroke-width="1.8"
                                                                        class="h-4 w-4">
                                                                        <rect x="5" y="10" width="14" height="10"
                                                                            rx="2"></rect>
                                                                        <path d="M8 10V7a4 4 0 0 1 8 0v3"></path>
                                                                    </svg>
                                                                @else
                                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                                        viewBox="0 0 24 24" fill="none"
                                                                        stroke="currentColor" stroke-width="1.8"
                                                                        class="h-4 w-4">
                                                                        <rect x="5" y="10" width="14" height="10"
                                                                            rx="2"></rect>
                                                                        <path d="M8 10V7a4 4 0 0 1 7.5-2"></path>
                                                                    </svg>
                                                                @endif
                                                            </span>

                                                            <span>
                                                                {{ $user->status === 'active' ? 'Khóa tài khoản' : 'Mở khóa tài khoản' }}
                                                            </span>
                                                        </button>
                                                    </form>
                                                @else
                                                    <div
                                                        class="flex items-center gap-3 px-4 py-3 text-sm font-medium text-blue-600">
                                                        <span
                                                            class="flex h-8 w-8 items-center justify-center rounded-lg bg-blue-50">
                                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                                fill="none" stroke="currentColor" stroke-width="2"
                                                                class="h-4 w-4">
                                                                <path d="m5 12 4 4L19 6"></path>
                                                            </svg>
                                                        </span>

                                                        <span>Tài khoản hiện tại</span>
                                                    </div>
                                                @endif
                                            </div>
                                        </details>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Phân trang --}}
                @if ($users->hasPages())
                    <div class="rounded-b-2xl border-t border-slate-200 px-5 py-5 sm:px-6">
                        {{ $users->links() }}
                    </div>
                @endif
            @else
                {{-- Không có dữ liệu --}}
                <div class="rounded-b-2xl px-6 py-16 text-center">
                    <div
                        class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-slate-100 text-slate-400">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.6" class="h-8 w-8">
                            <circle cx="9" cy="8" r="4"></circle>
                            <path d="M3 21a6 6 0 0 1 12 0"></path>
                            <path d="M17 8h4"></path>
                            <path d="M19 6v4"></path>
                        </svg>
                    </div>

                    <h3 class="mt-5 text-lg font-bold text-slate-900">
                        @if ($hasFilters)
                            Không tìm thấy tài khoản phù hợp
                        @else
                            Chưa có tài khoản
                        @endif
                    </h3>

                    <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-slate-500">
                        @if ($hasFilters)
                            Không có tài khoản nào phù hợp với nội dung tìm kiếm
                            hoặc bộ lọc hiện tại.
                        @else
                            Hệ thống hiện chưa có tài khoản người dùng nào.
                        @endif
                    </p>

                    @if ($hasFilters)
                        <a href="{{ route('admin.users.index') }}"
                            class="mt-5 inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-200">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="1.8" class="h-5 w-5">
                                <path d="M3 12a9 9 0 1 0 3-6.7"></path>
                                <path d="M3 4v6h6"></path>
                            </svg>

                            Xóa bộ lọc
                        </a>
                    @endif
                </div>
            @endif
        </section>
    </div>
@endsection