@extends('layouts.admin')

@section('title', 'Chi tiết tài khoản | HomeStayGo')

@section('page-title', 'Chi tiết tài khoản')

@section('content')
    @php
        /*
        |--------------------------------------------------------------------------
        | Ảnh đại diện
        |--------------------------------------------------------------------------
        */
        $avatarUrl = null;

        if (!empty($user->avatar)) {
            $avatarUrl = \Illuminate\Support\Str::startsWith($user->avatar, ['http://', 'https://'])
                ? $user->avatar
                : asset('storage/' . ltrim($user->avatar, '/'));
        }

        $nameParts = preg_split('/\s+/u', trim($user->name ?? ''));

        $firstNamePart = $nameParts[0] ?? '';
        $lastNamePart = $nameParts[count($nameParts) - 1] ?? '';

        $avatarText = mb_strtoupper(mb_substr($firstNamePart, 0, 1) . mb_substr($lastNamePart, 0, 1));

        $isCurrentUser = (int) auth()->id() === (int) $user->id;

        /*
        |--------------------------------------------------------------------------
        | Vai trò tài khoản
        |--------------------------------------------------------------------------
        */
        $roleConfig = [
            'admin' => [
                'label' => 'Quản trị viên',
                'class' => 'border-violet-200 bg-violet-50 text-violet-700',
            ],

            'user' => [
                'label' => 'Khách hàng',
                'class' => 'border-blue-200 bg-blue-50 text-blue-700',
            ],
        ];

        $currentRole = $roleConfig[$user->role] ?? [
            'label' => 'Không xác định',
            'class' => 'border-slate-200 bg-slate-50 text-slate-600',
        ];

        /*
        |--------------------------------------------------------------------------
        | Trạng thái tài khoản
        |--------------------------------------------------------------------------
        */
        $accountStatusConfig = [
            'active' => [
                'label' => 'Đang hoạt động',
                'badge' => 'border-emerald-200 bg-emerald-50 text-emerald-700',
                'dot' => 'bg-emerald-500',
            ],

            'inactive' => [
                'label' => 'Tạm khóa',
                'badge' => 'border-red-200 bg-red-50 text-red-700',
                'dot' => 'bg-red-500',
            ],
        ];

        $currentAccountStatus = $accountStatusConfig[$user->status] ?? [
            'label' => 'Không xác định',
            'badge' => 'border-slate-200 bg-slate-50 text-slate-600',
            'dot' => 'bg-slate-400',
        ];

        /*
        |--------------------------------------------------------------------------
        | Trạng thái Booking
        |--------------------------------------------------------------------------
        */
        $bookingStatusConfig = [
            'pending' => [
                'label' => 'Chờ xác nhận',
                'class' => 'border-amber-200 bg-amber-50 text-amber-700',
                'dot' => 'bg-amber-500',
            ],

            'confirmed' => [
                'label' => 'Đã xác nhận',
                'class' => 'border-blue-200 bg-blue-50 text-blue-700',
                'dot' => 'bg-blue-500',
            ],

            'checked_in' => [
                'label' => 'Đã nhận phòng',
                'class' => 'border-violet-200 bg-violet-50 text-violet-700',
                'dot' => 'bg-violet-500',
            ],

            'completed' => [
                'label' => 'Đã hoàn thành',
                'class' => 'border-emerald-200 bg-emerald-50 text-emerald-700',
                'dot' => 'bg-emerald-500',
            ],

            'cancelled' => [
                'label' => 'Đã hủy',
                'class' => 'border-red-200 bg-red-50 text-red-700',
                'dot' => 'bg-red-500',
            ],
        ];

        /*
        |--------------------------------------------------------------------------
        | Trạng thái thanh toán
        |--------------------------------------------------------------------------
        */
        $paymentStatusConfig = [
            'unpaid' => [
                'label' => 'Chưa thanh toán',
                'class' => 'border-slate-200 bg-slate-100 text-slate-600',
            ],

            'pending' => [
                'label' => 'Đang xử lý',
                'class' => 'border-amber-200 bg-amber-50 text-amber-700',
            ],

            'paid' => [
                'label' => 'Đã thanh toán',
                'class' => 'border-emerald-200 bg-emerald-50 text-emerald-700',
            ],

            'refunded' => [
                'label' => 'Đã hoàn tiền',
                'class' => 'border-blue-200 bg-blue-50 text-blue-700',
            ],

            'failed' => [
                'label' => 'Thanh toán thất bại',
                'class' => 'border-red-200 bg-red-50 text-red-700',
            ],
        ];

        /*
        |--------------------------------------------------------------------------
        | Phương thức thanh toán
        |--------------------------------------------------------------------------
        */
        $paymentMethodLabels = [
            'cash' => 'Tiền mặt',
            'bank_transfer' => 'Chuyển khoản ngân hàng',
            'vnpay' => 'VNPay',
            'momo' => 'MoMo',
        ];

        /*
        |--------------------------------------------------------------------------
        | Thống kê
        |--------------------------------------------------------------------------
        */
        $bookingsCount = (int) ($user->bookings_count ?? 0);
        $reviewsCount = (int) ($user->reviews_count ?? 0);

        $successfulTransactions = (int) ($paymentStatistics['successful_transactions'] ?? 0);

        $pendingTransactions = (int) ($paymentStatistics['pending_transactions'] ?? 0);

        $totalPaid = (int) ($paymentStatistics['total_paid'] ?? 0);

        $totalRefunded = (int) ($paymentStatistics['total_refunded'] ?? 0);
    @endphp

    <div class="mx-auto max-w-screen-2xl">

        <x-alert />

        {{-- Hồ sơ tổng quan --}}
        <section class="mb-6 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            {{-- Phần đầu --}}
            <div
                class="relative overflow-hidden border-b border-slate-200 bg-gradient-to-r from-slate-50 via-blue-50/70 to-indigo-50 px-5 py-6 sm:px-6 lg:px-8">
                <div class="pointer-events-none absolute -right-12 -top-16 h-48 w-48 rounded-full bg-blue-200/40 blur-3xl">
                </div>

                <div class="relative flex flex-col gap-6 xl:flex-row xl:items-center xl:justify-between">
                    {{-- Avatar và thông tin --}}
                    <div class="flex min-w-0 flex-col gap-5 sm:flex-row sm:items-center">
                        @if ($avatarUrl)
                            <img src="{{ $avatarUrl }}" alt="Ảnh đại diện của {{ $user->name }}"
                                class="h-24 w-24 shrink-0 rounded-2xl border-4 border-white object-cover shadow-md">
                        @else
                            <div
                                class="flex h-24 w-24 shrink-0 items-center justify-center rounded-2xl border-4 border-white bg-blue-600 text-3xl font-bold text-white shadow-md">
                                {{ $avatarText ?: '?' }}
                            </div>
                        @endif

                        <div class="min-w-0">
                            {{-- Nhãn --}}
                            <div class="flex flex-wrap items-center gap-2">
                                <span
                                    class="inline-flex items-center rounded-full border px-3 py-1 text-xs font-semibold {{ $currentRole['class'] }}">
                                    {{ $currentRole['label'] }}
                                </span>

                                <span
                                    class="inline-flex items-center gap-2 rounded-full border px-3 py-1 text-xs font-semibold {{ $currentAccountStatus['badge'] }}">
                                    <span class="h-2 w-2 rounded-full {{ $currentAccountStatus['dot'] }}"></span>

                                    {{ $currentAccountStatus['label'] }}
                                </span>

                                @if ($isCurrentUser)
                                    <span
                                        class="inline-flex items-center rounded-full border border-blue-200 bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-700">
                                        Tài khoản của bạn
                                    </span>
                                @endif
                            </div>

                            <h2 class="mt-3 break-words text-2xl font-bold text-slate-900 sm:text-3xl">
                                {{ $user->name }}
                            </h2>

                            <p class="mt-1 break-all flex items-center gap-1 text-sm text-slate-500">
                                <svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="2" y="4" width="20" height="16" rx="2" />
                                    <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7" />
                                </svg>
                                {{ $user->email }}
                            </p>

                            <div
                                class="mt-4 flex flex-col gap-2 text-sm text-slate-600 sm:flex-row sm:flex-wrap sm:gap-x-6">
                                {{-- Số điện thoại --}}
                                <span class="inline-flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="1.8" class="h-4 w-4 shrink-0 text-blue-500">
                                        <path
                                            d="M4 4h4l2 5-2.5 1.5a16 16 0 0 0 6 6L15 14l5 2v4a2 2 0 0 1-2 2C9.2 22 2 14.8 2 6a2 2 0 0 1 2-2Z">
                                        </path>
                                    </svg>

                                    @if ($user->phone)
                                        <a href="tel:{{ $user->phone }}"
                                            class="font-medium transition hover:text-blue-600">
                                            {{ $user->phone }}
                                        </a>
                                    @else
                                        <span>Chưa cập nhật số điện thoại</span>
                                    @endif
                                </span>

                                {{-- Địa chỉ --}}
                                <span class="inline-flex min-w-0 items-start gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="1.8"
                                        class="mt-0.5 h-4 w-4 shrink-0 text-amber-500">
                                        <path d="M20 10c0 5-8 11-8 11S4 15 4 10a8 8 0 1 1 16 0Z"></path>
                                        <circle cx="12" cy="10" r="2.5"></circle>
                                    </svg>

                                    <span class="break-words">
                                        {{ $user->address ?: 'Chưa cập nhật địa chỉ' }}
                                    </span>
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- Ngày tham gia --}}
                    <div class="grid shrink-0 gap-3 sm:grid-cols-2 xl:w-auto xl:grid-cols-1">
                        <div class="rounded-xl border border-white/80 bg-white/80 px-5 py-4 shadow-sm backdrop-blur">
                            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                                Thành viên từ
                            </p>

                            <p class="mt-1.5 text-lg font-bold text-slate-900">
                                {{ $user->created_at->format('d/m/Y') }}
                            </p>
                        </div>

                        <a href="#booking-history"
                            class="inline-flex items-center justify-center gap-2 rounded-xl border border-blue-200 bg-blue-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-200">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="1.8" class="h-5 w-5">
                                <rect x="3" y="5" width="18" height="16" rx="2"></rect>
                                <path d="M8 3v4"></path>
                                <path d="M16 3v4"></path>
                                <path d="M3 10h18"></path>
                            </svg>

                            Xem lịch sử đặt phòng
                        </a>
                    </div>
                </div>
            </div>

            {{-- Thông tin nhanh --}}
            <div class="grid sm:grid-cols-2 xl:grid-cols-4">
                <div class="border-b border-slate-200 px-5 py-4 sm:border-r sm:px-6 xl:border-b-0">
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                        Mã tài khoản
                    </p>

                    <p class="mt-2 font-bold text-slate-900">
                        #{{ $user->id }}
                    </p>
                </div>

                <div class="border-b border-slate-200 px-5 py-4 sm:px-6 xl:border-r xl:border-b-0">
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                        Xác minh email
                    </p>

                    <div class="mt-2">
                        @if ($user->email_verified_at)
                            <span class="inline-flex items-center gap-2 text-sm font-semibold text-emerald-700">
                                <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                                Đã xác minh
                            </span>
                        @else
                            <span class="inline-flex items-center gap-2 text-sm font-semibold text-amber-700">
                                <span class="h-2 w-2 rounded-full bg-amber-500"></span>
                                Chưa xác minh
                            </span>
                        @endif
                    </div>
                </div>

                <div class="border-b border-slate-200 px-5 py-4 sm:border-r sm:border-b-0 sm:px-6">
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                        Vai trò
                    </p>

                    <p class="mt-2 font-semibold text-slate-900">
                        {{ $currentRole['label'] }}
                    </p>
                </div>

                <div class="px-5 py-4 sm:px-6">
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                        Cập nhật lần cuối
                    </p>

                    <p class="mt-2 font-semibold text-slate-900">
                        {{ $user->updated_at->format('H:i d/m/Y') }}
                    </p>
                </div>
            </div>
        </section>

        {{-- Thống kê --}}
        <section class="mb-6 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            {{-- Tổng Booking --}}
            <article
                class="group rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <p class="text-sm font-medium text-slate-500">
                            Tổng Booking
                        </p>

                        <p class="mt-2 text-2xl font-bold text-slate-900">
                            {{ number_format($bookingsCount, 0, ',', '.') }}
                        </p>
                    </div>

                    <span
                        class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-blue-600 transition group-hover:bg-blue-100">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.8" class="h-6 w-6">
                            <rect x="3" y="5" width="18" height="16" rx="2"></rect>
                            <path d="M8 3v4"></path>
                            <path d="M16 3v4"></path>
                            <path d="M3 10h18"></path>
                        </svg>
                    </span>
                </div>

                <p class="mt-4 text-xs leading-5 text-slate-400">
                    Tổng số đơn đặt phòng của tài khoản.
                </p>
            </article>

            {{-- Đánh giá --}}
            <article
                class="group rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <p class="text-sm font-medium text-slate-500">
                            Đánh giá đã gửi
                        </p>

                        <p class="mt-2 text-2xl font-bold text-slate-900">
                            {{ number_format($reviewsCount, 0, ',', '.') }}
                        </p>
                    </div>

                    <span
                        class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-amber-50 text-amber-600 transition group-hover:bg-amber-100">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.8" class="h-6 w-6">
                            <path d="m12 3 2.8 5.7 6.2.9-4.5 4.4 1.1 6.2L12 17.3l-5.6 2.9 1.1-6.2L3 9.6l6.2-.9L12 3Z">
                            </path>
                        </svg>
                    </span>
                </div>

                <p class="mt-4 text-xs leading-5 text-slate-400">
                    Tổng số đánh giá đã gửi trên hệ thống.
                </p>
            </article>

            {{-- Giao dịch thành công --}}
            <article
                class="group rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <p class="text-sm font-medium text-slate-500">
                            Giao dịch thành công
                        </p>

                        <p class="mt-2 text-2xl font-bold text-slate-900">
                            {{ number_format($successfulTransactions, 0, ',', '.') }}
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
                    Số giao dịch được thanh toán thành công.
                </p>
            </article>

            {{-- Tổng tiền đã thanh toán --}}
            <article
                class="group rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
                <div class="flex items-center justify-between gap-4">
                    <div class="min-w-0">
                        <p class="text-sm font-medium text-slate-500">
                            Tổng đã thanh toán
                        </p>

                        <p class="mt-2 break-words text-xl font-bold text-blue-600 sm:text-2xl">
                            {{ number_format($totalPaid, 0, ',', '.') }}đ
                        </p>
                    </div>

                    <span
                        class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-violet-50 text-violet-600 transition group-hover:bg-violet-100">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.8" class="h-6 w-6">
                            <rect x="3" y="5" width="18" height="14" rx="2"></rect>
                            <path d="M3 9h18"></path>
                            <path d="M7 15h2"></path>
                        </svg>
                    </span>
                </div>

                <p class="mt-4 text-xs leading-5 text-slate-400">
                    Tổng số tiền tài khoản đã thanh toán.
                </p>
            </article>
        </section>

        {{-- Nội dung chính --}}
        <div class="grid items-start gap-6 xl:grid-cols-12">
            {{-- Lịch sử đặt phòng --}}
            <section id="booking-history"
                class="min-w-0 scroll-mt-28 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm xl:col-span-8">
                <div
                    class="flex flex-col gap-3 border-b border-slate-200 bg-slate-50/70 px-5 py-4 sm:flex-row sm:items-center sm:justify-between sm:px-6">
                    <div>
                        <h3 class="font-bold text-slate-900">
                            Lịch sử đặt phòng
                        </h3>

                        <p class="mt-1 text-sm text-slate-500">
                            Các đơn đặt phòng mới nhất của khách hàng.
                        </p>
                    </div>

                    <span
                        class="inline-flex w-fit items-center rounded-full border border-blue-200 bg-blue-50 px-3 py-1.5 text-xs font-semibold text-blue-700">
                        {{ number_format($bookingsCount, 0, ',', '.') }}
                        đơn
                    </span>
                </div>

                @if ($bookings->count())
                    <div class="overflow-x-auto">
                        <table class="w-full min-w-[850px] border-collapse text-left">
                            <thead>
                                <tr
                                    class="border-b border-slate-200 bg-slate-50 text-xs font-bold uppercase tracking-wider text-slate-500">
                                    <th scope="col" class="px-5 py-4">
                                        Mã Booking
                                    </th>

                                    <th scope="col" class="px-5 py-4">
                                        Phòng
                                    </th>

                                    <th scope="col" class="px-5 py-4">
                                        Thời gian
                                    </th>

                                    <th scope="col" class="px-5 py-4">
                                        Tổng tiền
                                    </th>

                                    <th scope="col" class="px-5 py-4">
                                        Trạng thái
                                    </th>

                                    <th scope="col" class="px-5 py-4 text-center">
                                        Chi tiết
                                    </th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-slate-100 bg-white text-sm">
                                @foreach ($bookings as $booking)
                                    @php
                                        $currentBookingStatus = $bookingStatusConfig[$booking->status] ?? [
                                            'label' => 'Không xác định',
                                            'class' => 'border-slate-200 bg-slate-100 text-slate-600',
                                            'dot' => 'bg-slate-400',
                                        ];
                                    @endphp

                                    <tr class="transition hover:bg-slate-50/80">
                                        {{-- Mã Booking --}}
                                        <td class="whitespace-nowrap px-5 py-4 align-middle">
                                            <a href="{{ route('admin.bookings.show', $booking) }}"
                                                class="font-bold text-blue-600 transition hover:text-blue-700 hover:underline">
                                                {{ $booking->booking_code }}
                                            </a>

                                            <p class="mt-1 text-xs text-slate-400">
                                                {{ $booking->created_at->format('H:i d/m/Y') }}
                                            </p>
                                        </td>

                                        {{-- Phòng --}}
                                        <td class="px-5 py-4 align-middle">
                                            <div class="max-w-52">
                                                <p class="truncate font-semibold text-slate-900"
                                                    title="{{ $booking->room?->name }}">
                                                    {{ $booking->room?->name ?? 'Phòng không tồn tại' }}
                                                </p>

                                                <p class="mt-1 truncate text-xs text-slate-500"
                                                    title="{{ $booking->room?->homestay?->name }}">
                                                    {{ $booking->room?->homestay?->name ?? 'Homestay không xác định' }}
                                                </p>
                                            </div>
                                        </td>

                                        {{-- Thời gian --}}
                                        <td class="whitespace-nowrap px-5 py-4 align-middle">
                                            <p class="font-semibold text-slate-700">
                                                {{ $booking->check_in->format('d/m/Y') }}
                                            </p>

                                            <p class="mt-1 text-xs text-slate-400">
                                                đến {{ $booking->check_out->format('d/m/Y') }}
                                            </p>
                                        </td>

                                        {{-- Tổng tiền --}}
                                        <td class="whitespace-nowrap px-5 py-4 align-middle">
                                            <p class="font-bold text-slate-900">
                                                {{ number_format((float) ($booking->total_price ?? 0), 0, ',', '.') }}đ
                                            </p>

                                            <p class="mt-1 text-xs text-slate-400">
                                                {{ $booking->number_of_nights }} đêm
                                            </p>
                                        </td>

                                        {{-- Trạng thái --}}
                                        <td class="whitespace-nowrap px-5 py-4 align-middle">
                                            <span
                                                class="inline-flex items-center gap-2 rounded-full border px-3 py-1.5 text-xs font-semibold {{ $currentBookingStatus['class'] }}">
                                                <span
                                                    class="h-2 w-2 rounded-full {{ $currentBookingStatus['dot'] }}"></span>

                                                {{ $currentBookingStatus['label'] }}
                                            </span>
                                        </td>

                                        {{-- Chi tiết --}}
                                        <td class="whitespace-nowrap px-5 py-4 text-center align-middle">
                                            <a href="{{ route('admin.bookings.show', $booking) }}"
                                                class="inline-flex h-9 w-9 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-500 transition hover:border-blue-300 hover:bg-blue-50 hover:text-blue-600"
                                                title="Xem chi tiết Booking">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                    fill="none" stroke="currentColor" stroke-width="1.8"
                                                    class="h-4 w-4">
                                                    <path d="M9 5l7 7-7 7"></path>
                                                </svg>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if ($bookings->hasPages())
                        <div class="border-t border-slate-200 px-5 py-5 sm:px-6">
                            {{ $bookings->links() }}
                        </div>
                    @endif
                @else
                    <div class="px-6 py-16 text-center">
                        <div
                            class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-blue-50 text-blue-500">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="1.6" class="h-8 w-8">
                                <rect x="3" y="5" width="18" height="16" rx="2"></rect>
                                <path d="M8 3v4"></path>
                                <path d="M16 3v4"></path>
                                <path d="M3 10h18"></path>
                            </svg>
                        </div>

                        <h3 class="mt-4 font-bold text-slate-900">
                            Chưa có đơn đặt phòng
                        </h3>

                        <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-slate-500">
                            Tài khoản này chưa từng đặt phòng trên hệ thống HomeStayGo.
                        </p>
                    </div>
                @endif
            </section>

            {{-- Cột bên phải --}}
            <aside class="min-w-0 space-y-6 xl:col-span-4">
                <div class="space-y-6 xl:sticky xl:top-24">
                    {{-- Quản lý tài khoản --}}
                    <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                        <div class="border-b border-slate-200 bg-slate-50 px-5 py-4">
                            <h3 class="font-bold text-slate-900">
                                Quản lý tài khoản
                            </h3>

                            <p class="mt-1 text-sm text-slate-500">
                                Cập nhật trạng thái sử dụng của tài khoản.
                            </p>
                        </div>

                        <div class="p-5">
                            @if ($isCurrentUser)
                                <div class="rounded-xl border border-blue-200 bg-blue-50 p-4">
                                    <div class="flex items-start gap-3">
                                        <span
                                            class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-blue-100 text-blue-600">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="2" class="h-5 w-5">
                                                <path d="m5 12 4 4L19 6"></path>
                                            </svg>
                                        </span>

                                        <div>
                                            <p class="font-semibold text-blue-700">
                                                Tài khoản hiện tại
                                            </p>

                                            <p class="mt-1 text-sm leading-6 text-blue-600">
                                                Bạn không thể tự khóa tài khoản đang đăng nhập.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="mb-4 rounded-xl border p-4 {{ $currentAccountStatus['badge'] }}">
                                    <div class="flex items-start gap-3">
                                        <span
                                            class="mt-1.5 h-2.5 w-2.5 shrink-0 rounded-full {{ $currentAccountStatus['dot'] }}"></span>

                                        <div>
                                            <p class="font-semibold">
                                                {{ $currentAccountStatus['label'] }}
                                            </p>

                                            <p class="mt-1 text-sm leading-6 opacity-80">
                                                @if ($user->status === 'active')
                                                    Tài khoản đang được phép sử dụng các chức năng theo quyền hạn.
                                                @else
                                                    Tài khoản đang bị giới hạn các thao tác trong hệ thống.
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <form method="POST" action="{{ route('admin.users.update-status', $user) }}"
                                    onsubmit="return confirm(
                                        '{{ $user->status === 'active'
                                            ? 'Bạn có chắc muốn khóa tài khoản này không?'
                                            : 'Bạn có chắc muốn mở khóa tài khoản này không?' }}'
                                    )">
                                    @csrf
                                    @method('PATCH')

                                    <input type="hidden" name="status"
                                        value="{{ $user->status === 'active' ? 'inactive' : 'active' }}">

                                    <button type="submit"
                                        class="inline-flex w-full cursor-pointer items-center justify-center gap-2 rounded-xl px-5 py-3 text-sm font-semibold text-white transition focus:outline-none focus:ring-4
                                            {{ $user->status === 'active'
                                                ? 'bg-red-600 hover:bg-red-700 focus:ring-red-200'
                                                : 'bg-emerald-600 hover:bg-emerald-700 focus:ring-emerald-200' }}">
                                        @if ($user->status === 'active')
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="1.8" class="h-5 w-5">
                                                <rect x="5" y="10" width="14" height="10" rx="2"></rect>
                                                <path d="M8 10V7a4 4 0 0 1 8 0v3"></path>
                                            </svg>

                                            Khóa tài khoản
                                        @else
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="1.8" class="h-5 w-5">
                                                <rect x="5" y="10" width="14" height="10" rx="2"></rect>
                                                <path d="M8 10V7a4 4 0 0 1 7.5-2"></path>
                                            </svg>

                                            Mở khóa tài khoản
                                        @endif
                                    </button>
                                </form>
                            @endif
                        </div>
                    </section>

                    {{-- Thông tin tài khoản --}}
                    <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                        <div class="border-b border-slate-200 bg-slate-50 px-5 py-4">
                            <h3 class="font-bold text-slate-900">
                                Thông tin tài khoản
                            </h3>

                            <p class="mt-1 text-sm text-slate-500">
                                Thông tin quản trị và xác minh.
                            </p>
                        </div>

                        <div class="divide-y divide-slate-100 px-5">
                            <div class="flex items-center justify-between gap-4 py-4">
                                <p class="text-sm text-slate-500">
                                    Mã tài khoản
                                </p>

                                <p class="font-semibold text-slate-900">
                                    #{{ $user->id }}
                                </p>
                            </div>

                            <div class="flex items-center justify-between gap-4 py-4">
                                <p class="text-sm text-slate-500">
                                    Vai trò
                                </p>

                                <span
                                    class="rounded-full border px-2.5 py-1 text-xs font-semibold {{ $currentRole['class'] }}">
                                    {{ $currentRole['label'] }}
                                </span>
                            </div>

                            <div class="flex items-center justify-between gap-4 py-4">
                                <p class="text-sm text-slate-500">
                                    Xác minh email
                                </p>

                                @if ($user->email_verified_at)
                                    <span
                                        class="rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700">
                                        Đã xác minh
                                    </span>
                                @else
                                    <span
                                        class="rounded-full bg-amber-50 px-2.5 py-1 text-xs font-semibold text-amber-700">
                                        Chưa xác minh
                                    </span>
                                @endif
                            </div>

                            <div class="flex items-center justify-between gap-4 py-4">
                                <p class="text-sm text-slate-500">
                                    Giao dịch đang xử lý
                                </p>

                                <span class="rounded-full bg-amber-50 px-2.5 py-1 text-xs font-semibold text-amber-700">
                                    {{ number_format($pendingTransactions, 0, ',', '.') }}
                                </span>
                            </div>

                            <div class="flex items-start justify-between gap-4 py-4">
                                <p class="text-sm text-slate-500">
                                    Tổng đã hoàn tiền
                                </p>

                                <p class="text-right font-semibold text-blue-600">
                                    {{ number_format($totalRefunded, 0, ',', '.') }}đ
                                </p>
                            </div>
                        </div>
                    </section>

                    {{-- Giao dịch gần nhất --}}
                    <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                        <div
                            class="flex items-center justify-between gap-4 border-b border-slate-200 bg-slate-50 px-5 py-4">
                            <div>
                                <h3 class="font-bold text-slate-900">
                                    Giao dịch gần nhất
                                </h3>

                                <p class="mt-1 text-sm text-slate-500">
                                    Thanh toán mới nhất của tài khoản.
                                </p>
                            </div>

                            <span
                                class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="1.8" class="h-5 w-5">
                                    <rect x="3" y="5" width="18" height="14" rx="2"></rect>
                                    <path d="M3 9h18"></path>
                                </svg>
                            </span>
                        </div>

                        @if ($latestPayment)
                            @php
                                $latestPaymentStatus = $paymentStatusConfig[$latestPayment->status] ?? [
                                    'label' => 'Không xác định',
                                    'class' => 'border-slate-200 bg-slate-100 text-slate-600',
                                ];
                            @endphp

                            <div class="p-5">
                                <div
                                    class="rounded-xl border border-emerald-100 bg-gradient-to-br from-emerald-50 to-blue-50 p-4">
                                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">
                                        Số tiền
                                    </p>

                                    <p class="mt-1 break-words text-2xl font-bold text-emerald-600">
                                        {{ number_format((float) ($latestPayment->amount ?? 0), 0, ',', '.') }}đ
                                    </p>

                                    <span
                                        class="mt-3 inline-flex rounded-full border px-2.5 py-1 text-xs font-semibold {{ $latestPaymentStatus['class'] }}">
                                        {{ $latestPaymentStatus['label'] }}
                                    </span>
                                </div>

                                <dl class="mt-5 divide-y divide-slate-100">
                                    <div class="flex items-start justify-between gap-4 py-3">
                                        <dt class="text-sm text-slate-500">
                                            Phương thức
                                        </dt>

                                        <dd class="text-right text-sm font-semibold text-slate-800">
                                            {{ $paymentMethodLabels[$latestPayment->payment_method] ?? ($latestPayment->payment_method ?? 'Không xác định') }}
                                        </dd>
                                    </div>

                                    <div class="flex items-start justify-between gap-4 py-3">
                                        <dt class="text-sm text-slate-500">
                                            Mã giao dịch
                                        </dt>

                                        <dd class="max-w-44 break-all text-right text-sm font-semibold text-slate-800">
                                            {{ $latestPayment->transaction_code ?: 'Chưa có mã' }}
                                        </dd>
                                    </div>

                                    <div class="flex items-start justify-between gap-4 py-3">
                                        <dt class="text-sm text-slate-500">
                                            Thời gian
                                        </dt>

                                        <dd class="text-right text-sm font-semibold text-slate-800">
                                            {{ $latestPayment->paid_at
                                                ? $latestPayment->paid_at->format('H:i d/m/Y')
                                                : $latestPayment->created_at->format('H:i d/m/Y') }}
                                        </dd>
                                    </div>
                                </dl>
                            </div>
                        @else
                            <div class="px-5 py-12 text-center">
                                <div
                                    class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-slate-100 text-slate-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="1.6" class="h-7 w-7">
                                        <rect x="3" y="5" width="18" height="14" rx="2"></rect>
                                        <path d="M3 9h18"></path>
                                    </svg>
                                </div>

                                <h3 class="mt-4 font-semibold text-slate-800">
                                    Chưa có giao dịch
                                </h3>

                                <p class="mt-1 text-sm leading-6 text-slate-500">
                                    Tài khoản này chưa phát sinh thanh toán.
                                </p>
                            </div>
                        @endif
                    </section>
                </div>
            </aside>
        </div>
    </div>
@endsection
