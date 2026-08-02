@extends('layouts.app')

@section('title', 'Thanh toán VNPAY | HomeStayGo')

@section('content')
    @php
        $bookingDetailUrl = auth()->user()?->isAdmin()
        ? route('admin.bookings.show', $booking)
        : route('bookings.show', $booking);

        $checkInValue = $booking->getAttribute('check_in_date')
            ?? $booking->getAttribute('check_in');

        $checkOutValue = $booking->getAttribute('check_out_date')
            ?? $booking->getAttribute('check_out');

        $guestCount = $booking->getAttribute('number_of_guests')
            ?? $booking->getAttribute('guests')
            ?? 1;

        $bookingCode = $booking->getAttribute('booking_code')
            ?? ('BK-' . str_pad($booking->id, 6, '0', STR_PAD_LEFT));

        /*
        |--------------------------------------------------------------------------
        | Tránh lỗi Undefined variable $amount
        |--------------------------------------------------------------------------
        */

        $paymentAmount = isset($amount)
            ? (int) $amount
            : (int) $booking->total_price;

        $roomPrice = (int) ($booking->room_price ?? 0);
        $subtotal = (int) ($booking->subtotal ?? 0);
        $serviceFee = (int) ($booking->service_fee ?? 0);
        $discountAmount = (int) ($booking->discount_amount ?? 0);

        $isPaid = $booking->payment_status === 'paid'
            || $booking->isPaid();

        /*
        |--------------------------------------------------------------------------
        | Trạng thái booking
        |--------------------------------------------------------------------------
        */

        $bookingStatus = match ($booking->status) {
            'pending' => [
                'label' => 'Chờ xác nhận',
                'class' => 'border-amber-200 bg-amber-50 text-amber-700',
            ],

            'confirmed' => [
                'label' => 'Đã xác nhận',
                'class' => 'border-blue-200 bg-blue-50 text-blue-700',
            ],

            'checked_in' => [
                'label' => 'Đã nhận phòng',
                'class' => 'border-cyan-200 bg-cyan-50 text-cyan-700',
            ],

            'completed' => [
                'label' => 'Đã hoàn thành',
                'class' => 'border-emerald-200 bg-emerald-50 text-emerald-700',
            ],

            'cancelled' => [
                'label' => 'Đã hủy',
                'class' => 'border-red-200 bg-red-50 text-red-700',
            ],

            default => [
                'label' => ucfirst((string) $booking->status),
                'class' => 'border-slate-200 bg-slate-50 text-slate-700',
            ],
        };

        $paymentStatus = $isPaid
            ? [
                'label' => 'Đã thanh toán',
                'class' => 'border-emerald-200 bg-emerald-50 text-emerald-700',
            ]
            : [
                'label' => 'Chưa thanh toán',
                'class' => 'border-orange-200 bg-orange-50 text-orange-700',
            ];
    @endphp

    <section class="relative min-h-screen overflow-hidden bg-slate-50 py-8 sm:py-12 lg:py-16">
        {{-- Họa tiết nền --}}
        <div class="pointer-events-none absolute inset-0 overflow-hidden">
            <div
                class="absolute -left-32 -top-32 h-96 w-96 rounded-full bg-blue-200/40 blur-3xl">
            </div>

            <div
                class="absolute -right-40 top-52 h-[28rem] w-[28rem] rounded-full bg-indigo-200/30 blur-3xl">
            </div>

            <div
                class="absolute bottom-0 left-1/3 h-80 w-80 rounded-full bg-cyan-100/50 blur-3xl">
            </div>
        </div>

        <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

            {{-- Nút quay lại --}}
            <a
               href="{{ $bookingDetailUrl }}"
                class="group inline-flex items-center gap-2 rounded-xl px-1 py-2 text-sm font-semibold text-slate-500 transition hover:text-blue-600"
            >
                <span
                    class="flex h-8 w-8 items-center justify-center rounded-full border border-slate-200 bg-white shadow-sm transition group-hover:border-blue-200 group-hover:bg-blue-50"
                >
                    <svg
                        class="h-4 w-4"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="m15 18-6-6 6-6"
                        />
                    </svg>
                </span>

                Quay lại chi tiết đặt phòng
            </a>

            {{-- Tiêu đề --}}
            <div class="mt-6 flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <div
                        class="inline-flex items-center gap-2 rounded-full border border-blue-200 bg-blue-50 px-3 py-1.5 text-xs font-bold uppercase tracking-[0.16em] text-blue-700"
                    >
                        <span class="relative flex h-2 w-2">
                            <span
                                class="absolute inline-flex h-full w-full animate-ping rounded-full bg-blue-400 opacity-75">
                            </span>

                            <span
                                class="relative inline-flex h-2 w-2 rounded-full bg-blue-600">
                            </span>
                        </span>

                        Thanh toán trực tuyến
                    </div>

                    <h1 class="mt-4 text-3xl font-black tracking-tight text-slate-950 sm:text-4xl lg:text-5xl">
                        Xác nhận thanh toán
                        <span class="text-blue-600">VNPAY</span>
                    </h1>

                    <p class="mt-3 max-w-2xl text-sm leading-7 text-slate-500 sm:text-base">
                        Vui lòng kiểm tra thông tin đặt phòng và số tiền trước khi
                        chuyển sang cổng thanh toán bảo mật của VNPAY.
                    </p>
                </div>

                <div class="flex flex-wrap items-center gap-2">
                    <span
                        class="inline-flex items-center rounded-full border px-3 py-1.5 text-xs font-bold {{ $bookingStatus['class'] }}"
                    >
                        {{ $bookingStatus['label'] }}
                    </span>

                    <span
                        class="inline-flex items-center rounded-full border px-3 py-1.5 text-xs font-bold {{ $paymentStatus['class'] }}"
                    >
                        {{ $paymentStatus['label'] }}
                    </span>
                </div>
            </div>

            {{-- Thông báo --}}
            @if (session('success'))
                <div
                    class="mt-7 flex items-start gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm text-emerald-800 shadow-sm"
                >
                    <span
                        class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-emerald-100"
                    >
                        <svg
                            class="h-5 w-5"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2.2"
                                d="m5 13 4 4L19 7"
                            />
                        </svg>
                    </span>

                    <div>
                        <p class="font-bold">
                            Thành công
                        </p>

                        <p class="mt-1 leading-6">
                            {{ session('success') }}
                        </p>
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div
                    class="mt-7 flex items-start gap-3 rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-sm text-red-800 shadow-sm"
                >
                    <span
                        class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-red-100"
                    >
                        <svg
                            class="h-5 w-5"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 9v4m0 4h.01M10.3 3.7 2.6 17a2 2 0 0 0 1.7 3h15.4a2 2 0 0 0 1.7-3L13.7 3.7a2 2 0 0 0-3.4 0Z"
                            />
                        </svg>
                    </span>

                    <div>
                        <p class="font-bold">
                            Không thể thực hiện
                        </p>

                        <p class="mt-1 leading-6">
                            {{ session('error') }}
                        </p>
                    </div>
                </div>
            @endif

            <div class="mt-8 grid items-start gap-7 lg:grid-cols-[minmax(0,1fr)_400px]">

                {{-- Cột trái --}}
                <div class="space-y-7">

                    {{-- Thông tin booking --}}
                    <div
                        class="overflow-hidden rounded-[28px] border border-white/70 bg-white/90 shadow-xl shadow-slate-200/50 backdrop-blur"
                    >
                        <div
                            class="flex flex-col gap-4 border-b border-slate-100 bg-gradient-to-r from-slate-50 to-blue-50/60 px-5 py-5 sm:flex-row sm:items-center sm:justify-between sm:px-7"
                        >
                            <div>
                                <p class="text-xs font-bold uppercase tracking-[0.16em] text-slate-400">
                                    Mã đặt phòng
                                </p>

                                <h2 class="mt-1 text-xl font-black text-slate-950">
                                    {{ $bookingCode }}
                                </h2>
                            </div>

                            <div
                                class="inline-flex w-fit items-center gap-2 rounded-xl border border-blue-200 bg-white px-3 py-2 text-xs font-semibold text-blue-700 shadow-sm"
                            >
                                <svg
                                    class="h-4 w-4"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M9 12h6m-6 4h6M9 8h6M6 3h12a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2Z"
                                    />
                                </svg>

                                Đơn đặt phòng #{{ $booking->id }}
                            </div>
                        </div>

                        <div class="p-5 sm:p-7">

                            {{-- Homestay và phòng --}}
                            <div
                                class="rounded-2xl border border-slate-200 bg-slate-50/70 p-5"
                            >
                                <div class="flex items-start gap-4">
                                    <div
                                        class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-blue-600 text-white shadow-lg shadow-blue-200"
                                    >
                                        <svg
                                            class="h-6 w-6"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="m3 11 9-8 9 8v9a1 1 0 0 1-1 1h-5v-7H9v7H4a1 1 0 0 1-1-1v-9Z"
                                            />
                                        </svg>
                                    </div>

                                    <div class="min-w-0">
                                        <p class="text-xs font-bold uppercase tracking-wider text-slate-400">
                                            Homestay
                                        </p>

                                        <h3 class="mt-1 text-lg font-black text-slate-950">
                                            {{ $booking->room?->homestay?->name ?? 'Không xác định' }}
                                        </h3>

                                        <div class="mt-2 flex items-center gap-2 text-sm text-slate-500">
                                            <svg
                                                class="h-4 w-4 shrink-0"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M20 10c0 5-8 11-8 11S4 15 4 10a8 8 0 1 1 16 0Z"
                                                />

                                                <circle
                                                    cx="12"
                                                    cy="10"
                                                    r="2.5"
                                                />
                                            </svg>

                                            <span class="truncate">
                                                {{ $booking->room?->homestay?->address ?? 'Chưa cập nhật địa chỉ' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-5 border-t border-slate-200 pt-4">
                                    <div class="flex items-center justify-between gap-4">
                                        <div>
                                            <p class="text-xs font-bold uppercase tracking-wider text-slate-400">
                                                Loại phòng
                                            </p>

                                            <p class="mt-1 font-bold text-slate-900">
                                                {{ $booking->room?->name ?? 'Không xác định' }}
                                            </p>
                                        </div>

                                        @if ($booking->room?->room_type)
                                            <span
                                                class="rounded-full border border-slate-200 bg-white px-3 py-1 text-xs font-semibold text-slate-600"
                                            >
                                                {{ $booking->room->room_type }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            {{-- Thời gian --}}
                            <div class="mt-6 grid gap-4 sm:grid-cols-[1fr_auto_1fr] sm:items-center">
                                <div
                                    class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm"
                                >
                                    <div class="flex items-center gap-3">
                                        <span
                                            class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600"
                                        >
                                            <svg
                                                class="h-5 w-5"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M8 2v4m8-4v4M3 10h18M5 4h14a2 2 0 0 1 2 2v14H3V6a2 2 0 0 1 2-2Z"
                                                />
                                            </svg>
                                        </span>

                                        <div>
                                            <p class="text-xs font-bold uppercase tracking-wider text-slate-400">
                                                Nhận phòng
                                            </p>

                                            <p class="mt-1 font-black text-slate-900">
                                                {{ $checkInValue
                                                    ? \Carbon\Carbon::parse($checkInValue)->format('d/m/Y')
                                                    : 'Chưa xác định' }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div class="hidden items-center text-slate-300 sm:flex">
                                    <svg
                                        class="h-6 w-6"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M5 12h14m-4-4 4 4-4 4"
                                        />
                                    </svg>
                                </div>

                                <div
                                    class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm"
                                >
                                    <div class="flex items-center gap-3">
                                        <span
                                            class="flex h-10 w-10 items-center justify-center rounded-xl bg-orange-50 text-orange-600"
                                        >
                                            <svg
                                                class="h-5 w-5"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M8 2v4m8-4v4M3 10h18M5 4h14a2 2 0 0 1 2 2v14H3V6a2 2 0 0 1 2-2Z"
                                                />
                                            </svg>
                                        </span>

                                        <div>
                                            <p class="text-xs font-bold uppercase tracking-wider text-slate-400">
                                                Trả phòng
                                            </p>

                                            <p class="mt-1 font-black text-slate-900">
                                                {{ $checkOutValue
                                                    ? \Carbon\Carbon::parse($checkOutValue)->format('d/m/Y')
                                                    : 'Chưa xác định' }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Thông tin khách --}}
                            <div class="mt-6 grid gap-4 sm:grid-cols-2">
                                <div
                                    class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm"
                                >
                                    <div class="flex items-center gap-3">
                                        <span
                                            class="flex h-10 w-10 items-center justify-center rounded-xl bg-violet-50 text-violet-600"
                                        >
                                            <svg
                                                class="h-5 w-5"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2m7-10a4 4 0 1 0 0-8 4 4 0 0 0 0 8Zm13 10v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"
                                                />
                                            </svg>
                                        </span>

                                        <div>
                                            <p class="text-xs font-bold uppercase tracking-wider text-slate-400">
                                                Số khách
                                            </p>

                                            <p class="mt-1 font-bold text-slate-900">
                                                {{ $guestCount }} khách
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div
                                    class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm"
                                >
                                    <div class="flex items-center gap-3">
                                        <span
                                            class="flex h-10 w-10 items-center justify-center rounded-xl bg-cyan-50 text-cyan-600"
                                        >
                                            <svg
                                                class="h-5 w-5"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M3 5a2 2 0 0 1 2-2h3l2 5-2 1a16 16 0 0 0 7 7l1-2 5 2v3a2 2 0 0 1-2 2h-1C9.7 21 3 14.3 3 6V5Z"
                                                />
                                            </svg>
                                        </span>

                                        <div class="min-w-0">
                                            <p class="text-xs font-bold uppercase tracking-wider text-slate-400">
                                                Người đặt
                                            </p>

                                            <p class="mt-1 truncate font-bold text-slate-900">
                                                {{ $booking->customer_name ?? $booking->user?->name ?? 'Không xác định' }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Chi tiết chi phí --}}
                    <div
                        class="rounded-[28px] border border-white/70 bg-white/90 p-5 shadow-xl shadow-slate-200/50 backdrop-blur sm:p-7"
                    >
                        <div class="flex items-center justify-between gap-4">
                            <div>
                                <p class="text-xs font-bold uppercase tracking-[0.16em] text-blue-600">
                                    Chi tiết thanh toán
                                </p>

                                <h2 class="mt-1 text-xl font-black text-slate-950">
                                    Tổng hợp chi phí
                                </h2>
                            </div>

                            <span
                                class="flex h-11 w-11 items-center justify-center rounded-2xl bg-blue-50 text-blue-600"
                            >
                                <svg
                                    class="h-5 w-5"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M6 2h12v20l-3-2-3 2-3-2-3 2V2Zm3 5h6M9 11h6M9 15h4"
                                    />
                                </svg>
                            </span>
                        </div>

                        <div class="mt-6 space-y-4 text-sm">
                            @if ($roomPrice > 0)
                                <div class="flex items-center justify-between gap-4">
                                    <span class="text-slate-500">
                                        Giá phòng mỗi đêm
                                    </span>

                                    <span class="font-semibold text-slate-900">
                                        {{ number_format($roomPrice, 0, ',', '.') }} ₫
                                    </span>
                                </div>
                            @endif

                            @if ((int) $booking->number_of_nights > 0)
                                <div class="flex items-center justify-between gap-4">
                                    <span class="text-slate-500">
                                        Số đêm lưu trú
                                    </span>

                                    <span class="font-semibold text-slate-900">
                                        {{ $booking->number_of_nights }} đêm
                                    </span>
                                </div>
                            @endif

                            @if ($subtotal > 0)
                                <div class="flex items-center justify-between gap-4">
                                    <span class="text-slate-500">
                                        Tiền phòng
                                    </span>

                                    <span class="font-semibold text-slate-900">
                                        {{ number_format($subtotal, 0, ',', '.') }} ₫
                                    </span>
                                </div>
                            @endif

                            @if ($serviceFee > 0)
                                <div class="flex items-center justify-between gap-4">
                                    <span class="text-slate-500">
                                        Phí dịch vụ
                                    </span>

                                    <span class="font-semibold text-slate-900">
                                        {{ number_format($serviceFee, 0, ',', '.') }} ₫
                                    </span>
                                </div>
                            @endif

                            @if ($discountAmount > 0)
                                <div class="flex items-center justify-between gap-4">
                                    <span class="text-emerald-600">
                                        Giảm giá
                                    </span>

                                    <span class="font-bold text-emerald-600">
                                        −{{ number_format($discountAmount, 0, ',', '.') }} ₫
                                    </span>
                                </div>
                            @endif

                            <div class="border-t border-dashed border-slate-200 pt-4">
                                <div class="flex items-end justify-between gap-4">
                                    <div>
                                        <p class="font-bold text-slate-900">
                                            Tổng thanh toán
                                        </p>

                                        <p class="mt-1 text-xs text-slate-400">
                                            Đã bao gồm các khoản phí
                                        </p>
                                    </div>

                                    <p class="text-xl font-black text-blue-600 sm:text-2xl">
                                        {{ number_format($paymentAmount, 0, ',', '.') }} ₫
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Lưu ý bảo mật --}}
                    <div
                        class="flex items-start gap-4 rounded-[24px] border border-blue-200 bg-blue-50/80 p-5"
                    >
                        <span
                            class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-blue-600 text-white shadow-lg shadow-blue-200"
                        >
                            <svg
                                class="h-5 w-5"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 3 4 6v5c0 5 3.4 8.7 8 10 4.6-1.3 8-5 8-10V6l-8-3Zm-3 9 2 2 4-5"
                                />
                            </svg>
                        </span>

                    <div class="space-y-4">

                        <div>
                            <h3 class="font-black text-blue-950">
                                Thanh toán an toàn
                            </h3>

                            <p class="mt-1 text-sm leading-6 text-blue-700">
                                Số tiền được lấy trực tiếp từ đơn đặt phòng trong
                                hệ thống. Người dùng không thể thay đổi giá trị
                                thanh toán bằng dữ liệu trên trình duyệt.
                            </p>
                        </div>

                        <div>
                            <h3 class="font-black text-blue-950">
                                Xác minh giao dịch
                            </h3>

                            <p class="mt-1 text-sm leading-6 text-blue-700">
                                Trạng thái đơn đặt phòng chỉ được cập nhật sau khi
                                kết quả thanh toán được hệ thống xác minh.
                            </p>
                        </div>

                    </div>

                    </div>
                </div>

                {{-- Cột phải --}}
                <aside class="lg:sticky lg:top-24">
                    <div
                        class="overflow-hidden rounded-[30px] border border-white/70 bg-white shadow-2xl shadow-slate-300/40"
                    >
                        {{-- Header VNPAY --}}
                        <div
                            class="relative overflow-hidden bg-gradient-to-br from-blue-700 via-blue-600 to-indigo-700 p-6 text-white"
                        >
                            <div
                                class="absolute -right-10 -top-10 h-32 w-32 rounded-full border-[20px] border-white/10">
                            </div>

                            <div
                                class="absolute -bottom-12 -left-8 h-32 w-32 rounded-full bg-white/10 blur-sm">
                            </div>

                            <div class="relative">
                                <div class="flex items-center justify-between gap-4">
                                    <div
                                        class="flex h-14 w-14 items-center justify-center rounded-2xl bg-white text-sm font-black tracking-tight text-blue-700 shadow-lg"
                                    >
                                        VNP
                                    </div>

                                    <span
                                        class="rounded-full border border-white/20 bg-white/10 px-3 py-1.5 text-xs font-bold backdrop-blur"
                                    >
                                        Bảo mật
                                    </span>
                                </div>

                                <h2 class="mt-6 text-xl font-black">
                                    Thanh toán qua VNPAY
                                </h2>

                                <p class="mt-2 text-sm leading-6 text-blue-100">
                                    Hỗ trợ quét mã QR, thẻ ATM nội địa và ứng dụng
                                    ngân hàng.
                                </p>
                            </div>
                        </div>

                        <div class="p-5 sm:p-6">
                            {{-- Số tiền --}}
                            <div
                                class="rounded-2xl border border-slate-200 bg-slate-50 p-5 text-center"
                            >
                                <p class="text-sm font-semibold text-slate-500">
                                    Số tiền cần thanh toán
                                </p>

                                <p class="mt-2 break-words text-3xl font-black tracking-tight text-slate-950">
                                    {{ number_format($paymentAmount, 0, ',', '.') }}
                                    <span class="text-xl text-blue-600">₫</span>
                                </p>

                                <p class="mt-2 text-xs text-slate-400">
                                    Mã đơn: {{ $bookingCode }}
                                </p>
                            </div>

                            @if ($isPaid)
                                {{-- Đã thanh toán --}}
                                <div
                                    class="mt-5 rounded-2xl border border-emerald-200 bg-emerald-50 p-5 text-center"
                                >
                                    <span
                                        class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-emerald-100 text-emerald-600"
                                    >
                                        <svg
                                            class="h-7 w-7"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2.5"
                                                d="m5 13 4 4L19 7"
                                            />
                                        </svg>
                                    </span>

                                    <h3 class="mt-4 font-black text-emerald-900">
                                        Đã thanh toán thành công
                                    </h3>

                                    <p class="mt-2 text-sm leading-6 text-emerald-700">
                                        Đơn đặt phòng này đã hoàn tất thanh toán.
                                        Bạn không cần thực hiện lại giao dịch.
                                    </p>
                                </div>

                                <a
                                    href="{{ $bookingDetailUrl }}"
                                    class="mt-5 inline-flex w-full items-center justify-center gap-2 rounded-2xl bg-slate-900 px-5 py-4 text-sm font-bold text-white shadow-lg transition hover:bg-slate-800"
                                >
                                    <svg
                                        class="h-5 w-5"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12Zm10 3a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z"
                                        />
                                    </svg>

                                    Xem chi tiết đặt phòng
                                </a>
                            @elseif ($booking->status === 'cancelled')
                                {{-- Booking đã hủy --}}
                                <div
                                    class="mt-5 rounded-2xl border border-red-200 bg-red-50 p-5 text-center"
                                >
                                    <span
                                        class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-red-100 text-red-600"
                                    >
                                        <svg
                                            class="h-7 w-7"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="m7 7 10 10M17 7 7 17"
                                            />
                                        </svg>
                                    </span>

                                    <h3 class="mt-4 font-black text-red-900">
                                        Không thể thanh toán
                                    </h3>

                                    <p class="mt-2 text-sm leading-6 text-red-700">
                                        Đơn đặt phòng này đã bị hủy nên không thể
                                        tiếp tục thanh toán.
                                    </p>
                                </div>
                            @else
                                {{-- Form thanh toán --}}
                                <form
                                    action="{{ route('payments.vnpay.create', $booking) }}"
                                    method="POST"
                                    class="mt-5"
                                    x-data="{ submitting: false }"
                                    @submit="submitting = true"
                                >
                                    @csrf

                                    <button
                                        type="submit"
                                        :disabled="submitting"
                                        class="group inline-flex w-full cursor-pointer items-center justify-center gap-3 rounded-2xl bg-gradient-to-r from-blue-600 to-indigo-600 px-5 py-4 text-sm font-black text-white shadow-lg shadow-blue-200 transition hover:-translate-y-0.5 hover:from-blue-700 hover:to-indigo-700 hover:shadow-xl disabled:cursor-not-allowed disabled:opacity-60"
                                    >
                                        <svg
                                            x-show="!submitting"
                                            class="h-5 w-5 transition group-hover:translate-x-0.5"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M5 12h14m-4-4 4 4-4 4"
                                            />
                                        </svg>

                                        <svg
                                            x-show="submitting"
                                            x-cloak
                                            class="h-5 w-5 animate-spin"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                        >
                                            <circle
                                                class="opacity-25"
                                                cx="12"
                                                cy="12"
                                                r="10"
                                                stroke="currentColor"
                                                stroke-width="4"
                                            ></circle>

                                            <path
                                                class="opacity-75"
                                                fill="currentColor"
                                                d="M4 12a8 8 0 0 1 8-8v4a4 4 0 0 0-4 4H4Z"
                                            ></path>
                                        </svg>

                                        <span
                                            x-text="submitting
                                                ? 'Đang chuyển sang VNPAY...'
                                                : 'Thanh toán ngay qua VNPAY'"
                                        >
                                            Thanh toán ngay qua VNPAY
                                        </span>
                                    </button>
                                </form>
                            @endif

                                                    {{-- Phương thức hỗ trợ --}}
                        {{-- Phương thức thanh toán hỗ trợ --}}
                        <div class="mt-6 border-t border-slate-100 pt-6">
                            <div class="flex items-center justify-between gap-4">
                                <div>
                                    <p class="text-xs font-bold uppercase tracking-[0.14em] text-slate-400">
                                        Phương thức thanh toán
                                    </p>

                                    <p class="mt-1 text-xs leading-5 text-slate-500">
                                        Chọn phương thức tại cổng thanh toán VNPAY.
                                    </p>
                                </div>

                                <span
                                    class="inline-flex items-center gap-1.5 rounded-full border border-emerald-200 bg-emerald-50 px-2.5 py-1 text-[11px] font-bold text-emerald-700"
                                >
                                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                    Đang hoạt động
                                </span>
                            </div>

                            <div class="mt-4 grid grid-cols-3 gap-3">

                                {{-- QR Code --}}
                                <div
                                    class="group rounded-2xl border border-slate-200 bg-white p-3 text-center shadow-sm transition hover:border-blue-300 hover:bg-blue-50/50"
                                >
                                    <span
                                        class="mx-auto flex h-10 w-10 items-center justify-center rounded-xl bg-blue-50 text-blue-600 transition group-hover:bg-blue-100"
                                    >
                                        <svg
                                            class="h-5 w-5"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M4 4h6v6H4V4Zm10 0h6v6h-6V4ZM4 14h6v6H4v-6Zm10 0h2v2h-2v-2Zm4 0h2v6h-6v-2h4v-4Z"
                                            />
                                        </svg>
                                    </span>

                                    <p class="mt-2 text-xs font-bold text-slate-700">
                                        VNPAY QR
                                    </p>

                                    <p class="mt-1 text-[10px] leading-4 text-slate-400">
                                        Quét bằng ứng dụng ngân hàng
                                    </p>
                                </div>

                                {{-- Thẻ ATM --}}
                                <div
                                    class="group rounded-2xl border border-slate-200 bg-white p-3 text-center shadow-sm transition hover:border-blue-300 hover:bg-blue-50/50"
                                >
                                    <span
                                        class="mx-auto flex h-10 w-10 items-center justify-center rounded-xl bg-blue-50 text-blue-600 transition group-hover:bg-blue-100"
                                    >
                                        <svg
                                            class="h-5 w-5"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <rect
                                                x="3"
                                                y="5"
                                                width="18"
                                                height="14"
                                                rx="2"
                                                stroke-width="2"
                                            />

                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M3 10h18M7 15h4"
                                            />
                                        </svg>
                                    </span>

                                    <p class="mt-2 text-xs font-bold text-slate-700">
                                        Thẻ nội địa
                                    </p>

                                    <p class="mt-1 text-[10px] leading-4 text-slate-400">
                                        Thẻ ATM có Internet Banking
                                    </p>
                                </div>

                                {{-- Ngân hàng --}}
                                <div
                                    class="group rounded-2xl border border-slate-200 bg-white p-3 text-center shadow-sm transition hover:border-blue-300 hover:bg-blue-50/50"
                                >
                                    <span
                                        class="mx-auto flex h-10 w-10 items-center justify-center rounded-xl bg-blue-50 text-blue-600 transition group-hover:bg-blue-100"
                                    >
                                        <svg
                                            class="h-5 w-5"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M3 10h18M5 10V7l7-4 7 4v3M5 10v8m4-8v8m6-8v8m4-8v8M3 21h18"
                                            />
                                        </svg>
                                    </span>

                                    <p class="mt-2 text-xs font-bold text-slate-700">
                                        Tài khoản
                                    </p>

                                    <p class="mt-1 text-[10px] leading-4 text-slate-400">
                                        Thanh toán qua ngân hàng
                                    </p>
                                </div>
                            </div>
                        </div>

                        {{-- Thông tin giao dịch --}}
                        <div class="mt-6 rounded-2xl border border-slate-200 bg-slate-50 p-4">
                            <div class="flex items-center justify-between gap-4 text-xs">
                                <span class="text-slate-500">
                                    Mã đơn hàng
                                </span>

                                <span class="font-bold text-slate-700">
                                    {{ $bookingCode }}
                                </span>
                            </div>

                            <div class="mt-3 flex items-center justify-between gap-4 text-xs">
                                <span class="text-slate-500">
                                    Thời hạn giao dịch
                                </span>

                                <span class="font-bold text-amber-600">
                                    {{ config('services.vnpay.expire_minutes', 15) }} phút
                                </span>
                            </div>

                            <div class="mt-3 flex items-center justify-between gap-4 text-xs">
                                <span class="text-slate-500">
                                    Đơn vị tiền tệ
                                </span>

                                <span class="font-bold text-slate-700">
                                    VND
                                </span>
                            </div>
                        </div>

                    {{-- Cam kết bảo mật --}}
                    <div class="mt-5 space-y-3 border-t border-slate-100 pt-5">

                        <div class="flex items-start gap-3">
                            <span
                                class="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-emerald-50 text-emerald-600"
                            >
                                <svg
                                    class="h-3.5 w-3.5"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="3"
                                        d="m5 13 4 4L19 7"
                                    />
                                </svg>
                            </span>

                            <p class="text-xs leading-5 text-slate-500">
                                Số tiền được xác định từ đơn đặt phòng và không thể chỉnh sửa trên trình duyệt.
                            </p>
                        </div>

                        <div class="flex items-start gap-3">
                            <span
                                class="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-emerald-50 text-emerald-600"
                            >
                                <svg
                                    class="h-3.5 w-3.5"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="3"
                                        d="m5 13 4 4L19 7"
                                    />
                                </svg>
                            </span>

                            <p class="text-xs leading-5 text-slate-500">
                                Không đóng hoặc tải lại trình duyệt trong quá trình thanh toán.
                            </p>
                        </div>

                        <div class="flex items-start gap-3">
                            <span
                                class="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-emerald-50 text-emerald-600"
                            >
                                <svg
                                    class="h-3.5 w-3.5"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="3"
                                        d="m5 13 4 4L19 7"
                                    />
                                </svg>
                            </span>

                            <p class="text-xs leading-5 text-slate-500">
                                Trạng thái đơn chỉ được cập nhật sau khi kết quả giao dịch được xác minh.
                            </p>
                        </div>
                    </div>

                            {{-- Dòng bảo mật cuối --}}
                            <div
                                class="mt-5 flex items-center justify-center gap-2 rounded-xl bg-slate-50 px-3 py-3 text-xs font-semibold text-slate-500"
                            >
                                <svg
                                    class="h-4 w-4 text-blue-600"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <rect
                                        x="5"
                                        y="10"
                                        width="14"
                                        height="11"
                                        rx="2"
                                        stroke-width="2"
                                    />

                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M8 10V7a4 4 0 0 1 8 0v3"
                                    />
                                </svg>

                                Thanh toán được xử lý trên cổng VNPAY
                            </div>

                            {{-- Các lưu ý --}}
                            <div class="mt-6 space-y-3 border-t border-slate-100 pt-5">
                                <div class="flex items-start gap-3 text-xs leading-5 text-slate-500">
                                    <svg
                                        class="mt-0.5 h-4 w-4 shrink-0 text-emerald-500"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2.5"
                                            d="m5 13 4 4L19 7"
                                        />
                                    </svg>

                                    <p>
                                        Giao dịch có thời hạn
                                        <strong class="text-slate-700">
                                            {{ config('services.vnpay.expire_minutes', 15) }} phút
                                        </strong>.
                                    </p>
                                </div>

                                <div class="flex items-start gap-3 text-xs leading-5 text-slate-500">
                                    <svg
                                        class="mt-0.5 h-4 w-4 shrink-0 text-emerald-500"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2.5"
                                            d="m5 13 4 4L19 7"
                                        />
                                    </svg>

                                    <p>
                                        Không đóng trình duyệt trong quá trình
                                        thanh toán.
                                    </p>
                                </div>

                                <div class="flex items-start gap-3 text-xs leading-5 text-slate-500">
                                    <svg
                                        class="mt-0.5 h-4 w-4 shrink-0 text-emerald-500"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2.5"
                                            d="m5 13 4 4L19 7"
                                        />
                                    </svg>

                                    <p>
                                        Kết quả thanh toán sẽ được xác minh trước
                                        khi cập nhật đơn đặt phòng.
                                    </p>
                                </div>
                            </div>

                            <div
                                class="mt-6 flex items-center justify-center gap-2 text-xs font-semibold text-slate-400"
                            >
                                <svg
                                    class="h-4 w-4"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <rect
                                        x="5"
                                        y="10"
                                        width="14"
                                        height="11"
                                        rx="2"
                                    />

                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M8 10V7a4 4 0 0 1 8 0v3"
                                    />
                                </svg>

                                Kết nối thanh toán được bảo mật
                            </div>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </section>
@endsection