@php
    $resultConfigurations = [
        'success' => [
            'eyebrow' => 'Thanh toán hoàn tất',
            'title' => 'Thanh toán thành công',
            'description' => 'Giao dịch đã được VNPAY xác nhận và trạng thái đơn đặt phòng đã được cập nhật.',
            'icon' => '✓',
            'icon_class' => 'bg-emerald-100 text-emerald-700 ring-emerald-50',
            'badge_class' => 'border-emerald-200 bg-emerald-50 text-emerald-700',
            'panel_class' => 'border-emerald-200 bg-emerald-50/70',
        ],

        'processing' => [
            'eyebrow' => 'Đang xác minh giao dịch',
            'title' => 'Thanh toán đang được xử lý',
            'description' => 'VNPAY đã trả kết quả về trình duyệt. Hệ thống đang chờ thông báo IPN để xác nhận trạng thái cuối cùng.',
            'icon' => '⏳',
            'icon_class' => 'bg-amber-100 text-amber-700 ring-amber-50',
            'badge_class' => 'border-amber-200 bg-amber-50 text-amber-700',
            'panel_class' => 'border-amber-200 bg-amber-50/70',
        ],

        'failed' => [
            'eyebrow' => 'Giao dịch chưa hoàn tất',
            'title' => 'Thanh toán không thành công',
            'description' => 'Giao dịch đã bị từ chối, bị hủy hoặc chưa hoàn thành tại cổng VNPAY.',
            'icon' => '!',
            'icon_class' => 'bg-red-100 text-red-700 ring-red-50',
            'badge_class' => 'border-red-200 bg-red-50 text-red-700',
            'panel_class' => 'border-red-200 bg-red-50/70',
        ],

        'invalid_signature' => [
            'eyebrow' => 'Không thể xác minh',
            'title' => 'Chữ ký giao dịch không hợp lệ',
            'description' => 'Dữ liệu trả về không vượt qua bước xác minh chữ ký. Trạng thái thanh toán không được cập nhật.',
            'icon' => '×',
            'icon_class' => 'bg-red-100 text-red-700 ring-red-50',
            'badge_class' => 'border-red-200 bg-red-50 text-red-700',
            'panel_class' => 'border-red-200 bg-red-50/70',
        ],

        'invalid_amount' => [
            'eyebrow' => 'Không thể đối chiếu',
            'title' => 'Số tiền giao dịch không hợp lệ',
            'description' => 'Số tiền VNPAY trả về không trùng với số tiền của đơn đặt phòng trong hệ thống.',
            'icon' => '×',
            'icon_class' => 'bg-red-100 text-red-700 ring-red-50',
            'badge_class' => 'border-red-200 bg-red-50 text-red-700',
            'panel_class' => 'border-red-200 bg-red-50/70',
        ],

        'not_found' => [
            'eyebrow' => 'Không tìm thấy giao dịch',
            'title' => 'Giao dịch không tồn tại',
            'description' => 'Hệ thống không tìm thấy mã giao dịch tương ứng với dữ liệu VNPAY trả về.',
            'icon' => '?',
            'icon_class' => 'bg-slate-100 text-slate-700 ring-slate-50',
            'badge_class' => 'border-slate-200 bg-slate-100 text-slate-700',
            'panel_class' => 'border-slate-200 bg-slate-50',
        ],
    ];

    $configuration = $resultConfigurations[$resultStatus] ?? $resultConfigurations['failed'];

    $booking = $payment?->booking;
    $room = $booking?->room;
    $homestay = $room?->homestay;

    $canAccessBooking = auth()->check()
        && $booking
        && (int) $booking->user_id === (int) auth()->id();

    $canRetryPayment = $canAccessBooking
        && in_array(
            $resultStatus,
            [
                'failed',
                'invalid_signature',
                'invalid_amount',
            ],
            true
        )
        && $booking->status !== 'cancelled'
        && in_array(
            $booking->payment_status,
            [
                'unpaid',
                'failed',
            ],
            true
        );

    $responseCodeLabels = [
        '00' => $resultStatus === 'success'
            ? 'Giao dịch đã được xác nhận'
            : 'VNPAY báo giao dịch thành công',
        '07' => 'Giao dịch nghi ngờ',
        '09' => 'Thẻ hoặc tài khoản chưa đăng ký Internet Banking',
        '10' => 'Xác thực thông tin không đúng quá số lần',
        '11' => 'Đã hết thời gian chờ thanh toán',
        '12' => 'Thẻ hoặc tài khoản đã bị khóa',
        '13' => 'Mã OTP không chính xác',
        '24' => 'Khách hàng đã hủy giao dịch',
        '51' => 'Tài khoản không đủ số dư',
        '65' => 'Vượt quá hạn mức giao dịch trong ngày',
        '75' => 'Ngân hàng đang bảo trì',
        '79' => 'Nhập sai mật khẩu thanh toán quá số lần',
        '99' => 'Lỗi khác',
    ];

    $responseMessage = $responseCodeLabels[(string) $responseCode]
        ?? 'Chưa có mô tả kết quả từ VNPAY';
@endphp

@extends('layouts.app')

@section('title', $configuration['title'] . ' | HomeStayGo')

@section('content')

    <main>

        <section class="mx-auto flex min-h-[calc(100vh-80px)] max-w-5xl items-center px-4 py-12 sm:px-6 lg:px-8">

            <div class="w-full">

                {{-- Tiến trình --}}
                <div class="mx-auto mb-8 overflow-hidden rounded-3xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">

                    <div class="grid grid-cols-[1fr_auto_1fr_auto_1fr] items-center gap-3">

                        <div class="flex min-w-0 items-center gap-3">
                            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-emerald-100 text-sm font-bold text-emerald-700">
                                ✓
                            </span>

                            <div class="hidden min-w-0 sm:block">
                                <p class="text-xs font-semibold uppercase tracking-wider text-emerald-600">
                                    Bước 1
                                </p>

                                <p class="truncate text-sm font-bold text-slate-800">
                                    Xác nhận đơn
                                </p>
                            </div>
                        </div>

                        <div class="h-px w-6 bg-emerald-300 sm:w-14"></div>

                        <div class="flex min-w-0 items-center justify-center gap-3">
                            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-emerald-100 text-sm font-bold text-emerald-700">
                                ✓
                            </span>

                            <div class="hidden min-w-0 sm:block">
                                <p class="text-xs font-semibold uppercase tracking-wider text-emerald-600">
                                    Bước 2
                                </p>

                                <p class="truncate text-sm font-bold text-slate-800">
                                    Thanh toán
                                </p>
                            </div>
                        </div>

                        <div class="h-px w-6 bg-slate-200 sm:w-14"></div>

                        <div class="flex min-w-0 items-center justify-end gap-3">
                            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-blue-600 text-sm font-bold text-white shadow-lg shadow-blue-200">
                                3
                            </span>

                            <div class="hidden min-w-0 sm:block">
                                <p class="text-xs font-semibold uppercase tracking-wider text-blue-600">
                                    Bước 3
                                </p>

                                <p class="truncate text-sm font-bold text-slate-900">
                                    Hoàn tất
                                </p>
                            </div>
                        </div>

                    </div>

                </div>

                <div class="overflow-hidden rounded-[2rem] border border-slate-200 bg-white shadow-xl shadow-slate-200/70">

                    {{-- Kết quả chính --}}
                    <div class="px-6 py-10 text-center sm:px-10 sm:py-12">

                        <div
                            class="mx-auto flex h-24 w-24 items-center justify-center rounded-full text-4xl font-black ring-8 {{ $configuration['icon_class'] }}"
                        >
                            {{ $configuration['icon'] }}
                        </div>

                        <p class="mt-7 text-sm font-bold uppercase tracking-[0.2em] text-blue-600">
                            {{ $configuration['eyebrow'] }}
                        </p>

                        <h1 class="mt-3 text-3xl font-black tracking-tight text-slate-950 sm:text-4xl">
                            {{ $configuration['title'] }}
                        </h1>

                        <p class="mx-auto mt-4 max-w-2xl text-base leading-7 text-slate-500">
                            {{ $configuration['description'] }}
                        </p>

                        <span
                            class="mt-6 inline-flex rounded-full border px-4 py-2 text-sm font-bold {{ $configuration['badge_class'] }}"
                        >
                            {{ $responseMessage }}
                        </span>

                    </div>

                    {{-- Thông tin giao dịch --}}
                    <div class="border-t border-slate-100 bg-slate-50/70 px-6 py-8 sm:px-10">

                        <div class="grid gap-6 lg:grid-cols-[minmax(0,1fr)_300px]">

                            <div class="rounded-3xl border bg-white p-5 shadow-sm sm:p-6 {{ $configuration['panel_class'] }}">

                                <h2 class="text-lg font-bold text-slate-900">
                                    Thông tin giao dịch
                                </h2>

                                <div class="mt-5 divide-y divide-slate-200/80">

                                    <div class="flex flex-col gap-1 py-3 first:pt-0 sm:flex-row sm:items-center sm:justify-between">
                                        <span class="text-sm text-slate-500">
                                            Mã booking
                                        </span>

                                        <span class="break-all font-semibold text-slate-800">
                                            {{ $booking?->booking_code ?? 'Không xác định' }}
                                        </span>
                                    </div>

                                    <div class="flex flex-col gap-1 py-3 sm:flex-row sm:items-center sm:justify-between">
                                        <span class="text-sm text-slate-500">
                                            Mã giao dịch HomeStayGo
                                        </span>

                                        <span class="break-all font-semibold text-slate-800">
                                            {{ $payment?->transaction_code ?? 'Không xác định' }}
                                        </span>
                                    </div>

                                    <div class="flex flex-col gap-1 py-3 sm:flex-row sm:items-center sm:justify-between">
                                        <span class="text-sm text-slate-500">
                                            Mã giao dịch VNPAY
                                        </span>

                                        <span class="break-all font-semibold text-slate-800">
                                            {{ $gatewayTransactionCode ?: 'Chưa có' }}
                                        </span>
                                    </div>

                                    <div class="flex flex-col gap-1 py-3 sm:flex-row sm:items-center sm:justify-between">
                                        <span class="text-sm text-slate-500">
                                            Ngân hàng
                                        </span>

                                        <span class="font-semibold text-slate-800">
                                            {{ $bankCode ?: 'Chưa xác định' }}
                                        </span>
                                    </div>

                                    <div class="flex flex-col gap-1 py-3 sm:flex-row sm:items-center sm:justify-between">
                                        <span class="text-sm text-slate-500">
                                            Mã phản hồi
                                        </span>

                                        <span class="font-semibold text-slate-800">
                                            {{ $responseCode ?: 'Không có' }}
                                        </span>
                                    </div>

                                    <div class="flex flex-col gap-1 py-3 last:pb-0 sm:flex-row sm:items-center sm:justify-between">
                                        <span class="text-sm text-slate-500">
                                            Trạng thái giao dịch
                                        </span>

                                        <span class="font-semibold text-slate-800">
                                            {{ $transactionStatus ?: 'Chưa xác định' }}
                                        </span>
                                    </div>

                                </div>

                            </div>

                            <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">

                                <p class="text-sm font-semibold text-slate-500">
                                    Số tiền
                                </p>

                                <p class="mt-2 text-3xl font-black tracking-tight text-blue-600">
                                    {{ $payment
                                        ? number_format($payment->amount, 0, ',', '.') . 'đ'
                                        : 'Không xác định' }}
                                </p>

                                @if ($homestay)
                                    <div class="mt-5 border-t border-slate-100 pt-5">
                                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                                            Homestay
                                        </p>

                                        <p class="mt-2 font-bold text-slate-900">
                                            {{ $homestay->name }}
                                        </p>

                                        <p class="mt-1 text-sm text-slate-500">
                                            {{ $room?->name }}
                                        </p>
                                    </div>
                                @endif

                                @if ($payment?->paid_at)
                                    <div class="mt-5 rounded-2xl bg-emerald-50 p-4">
                                        <p class="text-xs font-semibold uppercase tracking-wider text-emerald-600">
                                            Thanh toán lúc
                                        </p>

                                        <p class="mt-1 font-bold text-emerald-800">
                                            {{ $payment->paid_at->format('H:i d/m/Y') }}
                                        </p>
                                    </div>
                                @endif

                            </div>

                        </div>

                        {{-- Hành động --}}
                        <div class="mt-8 flex flex-col justify-center gap-3 sm:flex-row">

                            @if ($canAccessBooking)
                                <a
                                    href="{{ route('bookings.show', $booking) }}"
                                    class="inline-flex items-center justify-center rounded-2xl bg-blue-600 px-6 py-3.5 text-sm font-bold text-white shadow-lg shadow-blue-200 transition hover:-translate-y-0.5 hover:bg-blue-700"
                                >
                                    Xem chi tiết đơn
                                </a>

                                <a
                                    href="{{ route('bookings.history') }}"
                                    class="inline-flex items-center justify-center rounded-2xl border border-slate-300 bg-white px-6 py-3.5 text-sm font-semibold text-slate-700 transition hover:border-blue-500 hover:bg-blue-50 hover:text-blue-700"
                                >
                                    Lịch sử đặt phòng
                                </a>

                                @if ($canRetryPayment)
                                    <a
                                        href="{{ route('bookings.payment.show', $booking) }}"
                                        class="inline-flex items-center justify-center rounded-2xl border border-amber-300 bg-amber-50 px-6 py-3.5 text-sm font-semibold text-amber-700 transition hover:border-amber-400 hover:bg-amber-100"
                                    >
                                        Thử thanh toán lại
                                    </a>
                                @endif
                            @else
                                <a
                                    href="{{ route('home') }}"
                                    class="inline-flex items-center justify-center rounded-2xl bg-blue-600 px-6 py-3.5 text-sm font-bold text-white transition hover:bg-blue-700"
                                >
                                    Về trang chủ
                                </a>
                            @endif

                        </div>

                        @if ($resultStatus === 'processing')
                            <div class="mx-auto mt-6 max-w-2xl rounded-2xl border border-amber-200 bg-amber-50 p-4 text-center">
                                <p class="text-sm leading-6 text-amber-700">
                                    Không đóng trang thanh toán tại VNPAY quá sớm. Trạng thái sẽ được cập nhật sau khi HomeStayGo nhận IPN hợp lệ.
                                </p>
                            </div>
                        @endif

                    </div>

                </div>

            </div>

        </section>

    </main>

@endsection
