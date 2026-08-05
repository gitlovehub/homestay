@php
    $paymentStatusLabels = [
        'unpaid' => 'Chưa thanh toán',
        'pending' => 'Đang xử lý',
        'paid' => 'Đã thanh toán',
        'failed' => 'Thanh toán thất bại',
        'refunded' => 'Đã hoàn tiền',
    ];

    $paymentStatusClasses = [
        'unpaid' => 'border-slate-200 bg-slate-100 text-slate-700',
        'pending' => 'border-amber-200 bg-amber-50 text-amber-700',
        'paid' => 'border-emerald-200 bg-emerald-50 text-emerald-700',
        'failed' => 'border-red-200 bg-red-50 text-red-700',
        'refunded' => 'border-blue-200 bg-blue-50 text-blue-700',
    ];

    $latestPayment = $booking->payment;
    $selectedBankCode = old('bank_code', '');

    $room = $booking->room;
    $homestay = $room?->homestay;
@endphp

@extends('layouts.app')

@section('title', "Thanh toán {$booking->booking_code} | HomeStayGo")

@section('content')

    <x-alert />

    <main>

        <x-frontend-breadcrumb
            :items="[
                [
                    'label' => 'Trang chủ',
                    'url' => route('home'),
                ],
                [
                    'label' => 'Lịch sử đặt phòng',
                    'url' => route('bookings.history'),
                ],
                [
                    'label' => 'Chi tiết đơn',
                    'url' => route('bookings.show', $booking),
                ],
                [
                    'label' => 'Thanh toán',
                ],
            ]"
        />

        <section class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8 lg:py-10">

            {{-- Tiến trình --}}
            <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white p-4 shadow-sm sm:p-6">
                <div class="grid grid-cols-[1fr_auto_1fr_auto_1fr] items-center gap-1.5 sm:gap-3">
                    <!-- Bước 1 -->
                    <div class="flex min-w-0 items-center gap-2 sm:gap-3">
                        <span
                            class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-emerald-100 text-xs font-bold text-emerald-700 sm:h-10 sm:w-10 sm:text-sm">
                            ✓
                        </span>
                        <div class="min-w-0">
                            <p class="text-[10px] font-semibold uppercase tracking-wider text-emerald-600 sm:text-xs">
                                Bước 1
                            </p>
                            <p class="truncate text-xs font-bold text-slate-800 sm:text-sm">
                                Xác nhận đơn
                            </p>
                        </div>
                    </div>

                    <!-- Connector -->
                    <div class="h-px w-4 bg-emerald-300 sm:w-14"></div>

                    <!-- Bước 2 -->
                    <div class="flex min-w-0 items-center justify-center gap-2 sm:gap-3">
                        <span
                            class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-blue-600 text-xs font-bold text-white shadow-lg shadow-blue-200 sm:h-10 sm:w-10 sm:text-sm">
                            2
                        </span>
                        <div class="min-w-0">
                            <p class="text-[10px] font-semibold uppercase tracking-wider text-blue-600 sm:text-xs">
                                Bước 2
                            </p>
                            <p class="truncate text-xs font-bold text-slate-900 sm:text-sm">
                                Thanh toán
                            </p>
                        </div>
                    </div>

                    <!-- Connector -->
                    <div class="h-px w-4 bg-slate-200 sm:w-14"></div>

                    <!-- Bước 3 -->
                    <div class="flex min-w-0 items-center justify-end gap-2 sm:gap-3">
                        <span
                            class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-slate-100 text-xs font-bold text-slate-400 sm:h-10 sm:w-10 sm:text-sm">
                            3
                        </span>
                        <div class="min-w-0">
                            <p class="text-[10px] font-semibold uppercase tracking-wider text-slate-400 sm:text-xs">
                                Bước 3
                            </p>
                            <p class="truncate text-xs font-bold text-slate-500 sm:text-sm">
                                Hoàn tất
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-8 grid gap-8 lg:grid-cols-[minmax(0,1fr)_390px]">

                {{-- Cột trái --}}
                <div class="min-w-0 space-y-6">

                    {{-- Tiêu đề --}}
                    <div>
                        <p class="font-semibold uppercase tracking-widest text-blue-600">
                            Thanh toán an toàn
                        </p>

                        <div class="mt-2 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                            <div>
                                <h1 class="text-3xl font-bold tracking-tight text-slate-950 sm:text-4xl">
                                    Chọn phương thức thanh toán
                                </h1>

                                <p class="mt-3 max-w-2xl leading-7 text-slate-500">
                                    Bạn sẽ được chuyển đến cổng VNPAY để hoàn tất giao dịch.
                                    HomeStayGo không lưu thông tin thẻ hoặc tài khoản ngân hàng.
                                </p>
                            </div>

                            <span
                                class="inline-flex w-fit shrink-0 rounded-full border px-4 py-2 text-sm font-semibold
                                    {{ $paymentStatusClasses[$booking->payment_status]
                                        ?? 'border-slate-200 bg-slate-100 text-slate-700' }}"
                            >
                                {{ $paymentStatusLabels[$booking->payment_status]
                                    ?? $booking->payment_status }}
                            </span>
                        </div>
                    </div>

                    {{-- Thông tin đơn --}}
                    <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">

                        <div class="grid sm:grid-cols-[180px_minmax(0,1fr)]">

                            <div class="bg-slate-100">

                                @if ($room?->image)
                                    <img
                                        src="{{ Storage::url($room->image) }}"
                                        alt="{{ $room->name }}"
                                        class="h-52 w-full object-cover sm:h-full"
                                    >
                                @else
                                    <div class="flex h-52 items-center justify-center sm:h-full">
                                        <div class="text-center">
                                            <div class="text-5xl">
                                                🚪
                                            </div>

                                            <p class="mt-3 text-sm font-medium text-slate-400">
                                                Chưa có ảnh phòng
                                            </p>
                                        </div>
                                    </div>
                                @endif

                            </div>

                            <div class="p-5 sm:p-6">

                                <div class="flex flex-wrap items-center gap-2">
                                    <span class="rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700">
                                        {{ $booking->booking_code }}
                                    </span>

                                    @if ($room?->room_type)
                                        <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600">
                                            {{ $room->room_type }}
                                        </span>
                                    @endif
                                </div>

                                <h2 class="mt-4 text-xl font-bold text-slate-900">
                                    {{ $room?->name ?? 'Phòng không còn tồn tại' }}
                                </h2>

                                <p class="mt-1 font-semibold text-blue-600">
                                    {{ $homestay?->name ?? 'Homestay không còn tồn tại' }}
                                </p>

                                <div class="mt-5 grid grid-cols-2 gap-3 sm:grid-cols-4">

                                    <div class="rounded-xl bg-slate-50 p-3">
                                        <p class="text-xs text-slate-400">
                                            Nhận phòng
                                        </p>

                                        <p class="mt-1 text-sm font-bold text-slate-700">
                                            {{ $booking->check_in->format('d/m/Y') }}
                                        </p>
                                    </div>

                                    <div class="rounded-xl bg-slate-50 p-3">
                                        <p class="text-xs text-slate-400">
                                            Trả phòng
                                        </p>

                                        <p class="mt-1 text-sm font-bold text-slate-700">
                                            {{ $booking->check_out->format('d/m/Y') }}
                                        </p>
                                    </div>

                                    <div class="rounded-xl bg-slate-50 p-3">
                                        <p class="text-xs text-slate-400">
                                            Thời gian
                                        </p>

                                        <p class="mt-1 text-sm font-bold text-slate-700">
                                            {{ $booking->number_of_nights }} đêm
                                        </p>
                                    </div>

                                    <div class="rounded-xl bg-slate-50 p-3">
                                        <p class="text-xs text-slate-400">
                                            Số khách
                                        </p>

                                        <p class="mt-1 text-sm font-bold text-slate-700">
                                            {{ $booking->number_of_guests }} khách
                                        </p>
                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                    {{-- Phương thức thanh toán --}}
                    <form
                        id="vnpay-payment-form"
                        method="POST"
                        action="{{ route('bookings.payments.vnpay.create', $booking) }}"
                        class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm sm:p-7"
                    >
                        @csrf

                        <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">

                            <div>
                                <h2 class="text-2xl font-bold text-slate-900">
                                    Phương thức VNPAY
                                </h2>

                                <p class="mt-2 text-sm leading-6 text-slate-500">
                                    Chọn cách thanh toán phù hợp. Bạn có thể thay đổi lựa chọn trước khi tiếp tục.
                                </p>
                            </div>

                            <div class="inline-flex w-fit items-center gap-2 rounded-full bg-blue-50 px-3 py-2 text-xs font-bold text-blue-700">
                                <span class="h-2 w-2 rounded-full bg-blue-500"></span>
                                Cổng thanh toán bảo mật
                            </div>

                        </div>

                        <div class="mt-6 grid gap-4">

                            {{-- VNPAY QR --}}
                            <label class="group relative cursor-pointer">
                                <input
                                    type="radio"
                                    name="bank_code"
                                    value="VNPAYQR"
                                    class="peer sr-only"
                                    @checked($selectedBankCode === 'VNPAYQR')
                                >

                                <span class="flex items-start gap-4 rounded-2xl border-2 border-slate-200 p-5 transition duration-200 group-hover:border-blue-300 group-hover:bg-blue-50/40 peer-checked:border-blue-600 peer-checked:bg-blue-50 peer-checked:shadow-sm">
                                    <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-blue-100 text-2xl">
                                        ▦
                                    </span>

                                    <span class="min-w-0 flex-1">
                                        <span class="flex flex-wrap items-center gap-2">
                                            <span class="font-bold text-slate-900">
                                                VNPAY QR
                                            </span>

                                            <span class="rounded-full bg-emerald-100 px-2.5 py-1 text-[11px] font-bold text-emerald-700">
                                                Nhanh nhất
                                            </span>
                                        </span>

                                        <span class="mt-1 block text-sm leading-6 text-slate-500">
                                            Quét mã QR bằng ứng dụng ngân hàng hoặc ví điện tử có hỗ trợ VNPAY.
                                        </span>
                                    </span>

                                    <span class="mt-1 flex h-5 w-5 shrink-0 items-center justify-center rounded-full border-2 border-slate-300 peer-checked:border-blue-600">
                                        <span class="h-2.5 w-2.5 rounded-full bg-blue-600 opacity-0 transition peer-checked:opacity-100"></span>
                                    </span>
                                </span>
                            </label>

                            {{-- Ngân hàng nội địa --}}
                            <label class="group relative cursor-pointer">
                                <input
                                    type="radio"
                                    name="bank_code"
                                    value="VNBANK"
                                    class="peer sr-only"
                                    @checked($selectedBankCode === 'VNBANK')
                                >

                                <span class="flex items-start gap-4 rounded-2xl border-2 border-slate-200 p-5 transition duration-200 group-hover:border-blue-300 group-hover:bg-blue-50/40 peer-checked:border-blue-600 peer-checked:bg-blue-50 peer-checked:shadow-sm">
                                    <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-violet-100 text-2xl">
                                        🏦
                                    </span>

                                    <span class="min-w-0 flex-1">
                                        <span class="font-bold text-slate-900">
                                            Thẻ ATM hoặc tài khoản ngân hàng
                                        </span>

                                        <span class="mt-1 block text-sm leading-6 text-slate-500">
                                            Thanh toán qua ngân hàng nội địa, Internet Banking hoặc Mobile Banking.
                                        </span>
                                    </span>

                                    <span class="mt-1 flex h-5 w-5 shrink-0 items-center justify-center rounded-full border-2 border-slate-300 peer-checked:border-blue-600">
                                        <span class="h-2.5 w-2.5 rounded-full bg-blue-600 opacity-0 transition peer-checked:opacity-100"></span>
                                    </span>
                                </span>
                            </label>

                            {{-- Thẻ quốc tế --}}
                            <label class="group relative cursor-pointer">
                                <input
                                    type="radio"
                                    name="bank_code"
                                    value="INTCARD"
                                    class="peer sr-only"
                                    @checked($selectedBankCode === 'INTCARD')
                                >

                                <span class="flex items-start gap-4 rounded-2xl border-2 border-slate-200 p-5 transition duration-200 group-hover:border-blue-300 group-hover:bg-blue-50/40 peer-checked:border-blue-600 peer-checked:bg-blue-50 peer-checked:shadow-sm">
                                    <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-amber-100 text-2xl">
                                        💳
                                    </span>

                                    <span class="min-w-0 flex-1">
                                        <span class="font-bold text-slate-900">
                                            Thẻ thanh toán quốc tế
                                        </span>

                                        <span class="mt-1 block text-sm leading-6 text-slate-500">
                                            Sử dụng thẻ Visa, Mastercard, JCB hoặc UnionPay được VNPAY hỗ trợ.
                                        </span>
                                    </span>

                                    <span class="mt-1 flex h-5 w-5 shrink-0 items-center justify-center rounded-full border-2 border-slate-300 peer-checked:border-blue-600">
                                        <span class="h-2.5 w-2.5 rounded-full bg-blue-600 opacity-0 transition peer-checked:opacity-100"></span>
                                    </span>
                                </span>
                            </label>

                        </div>

                        @error('bank_code')
                            <p class="mt-4 text-sm font-semibold text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

                        <div class="mt-6 rounded-2xl border border-blue-100 bg-blue-50 p-5">

                            <div class="flex gap-3">
                                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-white text-lg shadow-sm">
                                    🔒
                                </span>

                                <div>
                                    <h3 class="font-bold text-blue-950">
                                        Giao dịch được xác minh tự động
                                    </h3>

                                    <p class="mt-1 text-sm leading-6 text-blue-700">
                                        Số tiền được lấy trực tiếp từ đơn đặt phòng. Trạng thái chỉ được cập nhật
                                        sau khi hệ thống xác minh phản hồi hợp lệ từ VNPAY.
                                    </p>
                                </div>
                            </div>

                        </div>

                    </form>

                </div>

                {{-- Cột phải --}}
                <aside class="lg:sticky lg:top-24 lg:self-start">

                    <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-lg">

                        <div class="border-b border-slate-100 p-6">
                            <p class="text-sm font-semibold text-slate-500">
                                Tổng thanh toán
                            </p>

                            <p class="mt-2 text-4xl font-black tracking-tight text-blue-600">
                                {{ number_format($booking->total_price, 0, ',', '.') }}đ
                            </p>

                            <p class="mt-2 text-xs leading-5 text-slate-400">
                                Số tiền đã bao gồm phí dịch vụ và giảm giá của đơn.
                            </p>
                        </div>

                        <div class="space-y-4 p-6 text-sm">

                            <div class="flex items-center justify-between gap-4">
                                <span class="text-slate-500">
                                    Giá phòng
                                </span>

                                <span class="font-semibold text-slate-800">
                                    {{ number_format($booking->room_price, 0, ',', '.') }}đ
                                </span>
                            </div>

                            <div class="flex items-center justify-between gap-4">
                                <span class="text-slate-500">
                                    Số đêm
                                </span>

                                <span class="font-semibold text-slate-800">
                                    {{ $booking->number_of_nights }}
                                </span>
                            </div>

                            <div class="flex items-center justify-between gap-4">
                                <span class="text-slate-500">
                                    Tiền phòng
                                </span>

                                <span class="font-semibold text-slate-800">
                                    {{ number_format($booking->subtotal, 0, ',', '.') }}đ
                                </span>
                            </div>

                            <div class="flex items-center justify-between gap-4">
                                <span class="text-slate-500">
                                    Phí dịch vụ
                                </span>

                                <span class="font-semibold text-slate-800">
                                    {{ number_format($booking->service_fee, 0, ',', '.') }}đ
                                </span>
                            </div>

                            <div class="flex items-center justify-between gap-4">
                                <span class="text-slate-500">
                                    Giảm giá
                                </span>

                                <span class="font-semibold text-emerald-600">
                                    -{{ number_format($booking->discount_amount, 0, ',', '.') }}đ
                                </span>
                            </div>

                        </div>

                        <div class="border-t border-slate-100 p-6">

                            <button
                                type="submit"
                                form="vnpay-payment-form"
                                id="vnpay-submit-button"
                                class="inline-flex w-full cursor-pointer items-center justify-center gap-3 rounded-2xl bg-blue-600 px-6 py-4 text-base font-bold text-white shadow-lg shadow-blue-200 transition hover:-translate-y-0.5 hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-200 disabled:cursor-wait disabled:opacity-70"
                            >
                                <span id="vnpay-submit-text">
                                    Thanh toán qua VNPAY
                                </span>
                            </button>

                            <a
                                href="{{ route('bookings.show', $booking) }}"
                                class="mt-3 inline-flex w-full items-center justify-center rounded-2xl border border-slate-300 bg-white px-6 py-3.5 text-sm font-semibold text-slate-700 transition hover:border-blue-500 hover:bg-blue-50 hover:text-blue-700"
                            >
                                Quay lại chi tiết đơn
                            </a>

                            <div class="mt-5 flex items-start gap-3 rounded-2xl bg-slate-50 p-4">
                                <span class="text-lg">
                                    ⏱️
                                </span>

                                <p class="text-xs leading-5 text-slate-500">
                                    Phiên thanh toán có hiệu lực
                                    <strong class="text-slate-700">
                                        {{ (int) config('services.vnpay.expire_minutes', 15) }} phút
                                    </strong>
                                    kể từ khi bạn được chuyển sang VNPAY.
                                </p>
                            </div>

                        </div>

                    </div>

                    <div class="mt-4 grid grid-cols-3 gap-3 text-center">

                        <div class="rounded-2xl border border-slate-200 bg-white p-3">
                            <p class="text-xl">
                                🛡️
                            </p>

                            <p class="mt-1 text-[11px] font-semibold text-slate-600">
                                Xác minh chữ ký
                            </p>
                        </div>

                        <div class="rounded-2xl border border-slate-200 bg-white p-3">
                            <p class="text-xl">
                                🔒
                            </p>

                            <p class="mt-1 text-[11px] font-semibold text-slate-600">
                                Không lưu thẻ
                            </p>
                        </div>

                        <div class="rounded-2xl border border-slate-200 bg-white p-3">
                            <p class="text-xl">
                                ⚡
                            </p>

                            <p class="mt-1 text-[11px] font-semibold text-slate-600">
                                Cập nhật tự động
                            </p>
                        </div>

                    </div>

                </aside>

            </div>

        </section>

    </main>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.getElementById('vnpay-payment-form');
            const submitButton = document.getElementById('vnpay-submit-button');
            const submitText = document.getElementById('vnpay-submit-text');
            const submitIcon = document.getElementById('vnpay-submit-icon');

            if (!form || !submitButton) {
                return;
            }

            form.addEventListener('submit', () => {
                submitButton.disabled = true;
                submitText.textContent = 'Đang kết nối VNPAY...';
                submitIcon.textContent = '⏳';
            });
        });
    </script>

@endsection
