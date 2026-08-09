@extends('layouts.app')

@section('title', 'Chi tiết đơn đặt phòng | HomeStayGo')

@section('content')

    @php
        $statusLabels = [
            'pending' => 'Chờ xác nhận',
            'confirmed' => 'Đã xác nhận',
            'checked_in' => 'Đang lưu trú',
            'completed' => 'Đã hoàn thành',
            'cancelled' => 'Đã hủy',
        ];

        $statusClasses = [
            'pending' => 'bg-amber-50 text-amber-700 border border-amber-200',
            'confirmed' => 'bg-blue-50 text-blue-700 border border-blue-200',
            'checked_in' => 'bg-violet-50 text-violet-700 border border-violet-200',
            'completed' => 'bg-emerald-50 text-emerald-700 border border-emerald-200',
            'cancelled' => 'bg-red-50 text-red-700 border border-red-200',
        ];

        $paymentLabels = [
            'unpaid' => 'Chưa thanh toán',
            'pending' => 'Đang xử lý',
            'paid' => 'Đã thanh toán',
            'deposit_paid' => 'Đã thanh toán cọc',
            'refund_pending' => 'Đang hoàn tiền',
            'partially_refunded' => 'Đã hoàn một phần',
            'refunded' => 'Đã hoàn tiền',
            'refund_failed' => 'Hoàn tiền thất bại',
            'cancelled' => 'Đã hủy thanh toán',
            'failed' => 'Thanh toán thất bại',
        ];

        $paymentClasses = [
            'unpaid' => 'border-slate-200 bg-slate-100 text-slate-700',
            'pending' => 'border-amber-200 bg-amber-50 text-amber-700',
            'paid' => 'border-emerald-200 bg-emerald-50 text-emerald-700',
            'deposit_paid' => 'border-emerald-200 bg-emerald-50 text-emerald-700',
            'refund_pending' => 'border-amber-200 bg-amber-50 text-amber-700',
            'partially_refunded' => 'border-blue-200 bg-blue-50 text-blue-700',
            'refunded' => 'border-blue-200 bg-blue-50 text-blue-700',
            'refund_failed' => 'border-red-200 bg-red-50 text-red-700',
            'cancelled' => 'border-slate-200 bg-slate-100 text-slate-700',
            'failed' => 'border-red-200 bg-red-50 text-red-700',
        ];

        $canPay = $booking->status !== 'cancelled'
            && in_array($booking->payment_status, ['unpaid', 'pending', 'failed'], true);

        $isDeposit = $booking->isCashDepositOption();
        $vnpayAmount = $booking->amountRequiredForVnpay();
        $remainingCashAmount = $booking->remainingCashAmount();
        $canCancel = in_array($booking->status, ['pending', 'confirmed'], true)
            && $booking->check_in
            && $booking->check_in->copy()->startOfDay()->greaterThan(now('Asia/Ho_Chi_Minh')->startOfDay());
        $refundPercentage = $booking->customerCancellationRefundPercentage();
        $expectedRefundAmount = in_array($booking->payment_status, ['paid'], true)
            ? (int) round($vnpayAmount * $refundPercentage / 100)
            : 0;

        $paymentButtonLabel = match ($booking->payment_status) {
            'pending' => 'Tiếp tục thanh toán',
            'failed' => 'Thanh toán lại qua VNPAY',
            default => $isDeposit ? 'Thanh toán cọc 10%' : 'Thanh toán qua VNPAY',
        };

        $refundPayment = $booking->payments()->where('payment_method', 'vnpay')->whereNotNull('refund_status')->latest('id')->first();

        $actualRefundedAmount = (int) ($refundPayment?->refunded_amount ?? 0);

    @endphp

    <main>

        {{-- Breadcrumb --}}
        <x-frontend-breadcrumb
            :items="[
                [
                    'label' => 'Trang chủ',
                    'url' => route('home'),
                ],
                [
                    'label' => 'Hồ sơ cá nhân',
                    'url' => route('profile.edit'),
                ],
                [
                    'label' => 'Lịch sử đặt phòng',
                    'url' => route('bookings.history'),
                ],
                [
                    'label' => 'Chi tiết đơn'
                ],
            ]"
        />

        <section class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-8 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-medium text-emerald-700">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-8 rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-sm font-medium text-red-700">
                    {{ session('error') }}
                </div>
            @endif

            <div class="mb-8 flex flex-col gap-5 sm:flex-row sm:items-start sm:justify-between">

                <div>
                    <p class="font-semibold uppercase tracking-widest text-blue-600">
                        Chi tiết đặt phòng
                    </p>

                    <h1 class="mt-2 text-3xl font-bold tracking-tight text-slate-950 sm:text-4xl">
                        {{ $booking->booking_code }}
                    </h1>

                    <p class="mt-3 text-slate-500">
                        Được tạo lúc {{ $booking->created_at->format('H:i d/m/Y') }}
                    </p>
                </div>

                <span class="inline-flex w-fit rounded-full px-6 py-2 text-sm font-semibold {{ $statusClasses[$booking->status] ?? 'bg-slate-100 text-slate-700' }}">
                    {{ $statusLabels[$booking->status] ?? $booking->status }}
                </span>

            </div>

            <div class="grid gap-8 lg:grid-cols-[minmax(0,1fr)_380px]">

                {{-- Cột trái --}}
                <div class="min-w-0 space-y-8">

                    {{-- Thông tin phòng --}}
                    <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">

                        <div class="grid md:grid-cols-[250px_minmax(0,1fr)]">

                            <div class="bg-slate-100">

                                @if ($booking->room->image)
                                    <img
                                        src="{{ Storage::url($booking->room->image) }}"
                                        alt="{{ $booking->room->name }}"
                                        class="h-64 w-full object-cover md:h-full"
                                    >
                                @else
                                    <div class="flex h-64 items-center justify-center text-center md:h-full">

                                        <div>
                                            <div class="text-6xl">
                                                🚪
                                            </div>

                                            <p class="mt-3 text-sm font-medium text-slate-400">
                                                Chưa có ảnh phòng
                                            </p>
                                        </div>

                                    </div>
                                @endif

                            </div>

                            <div class="p-6 sm:p-8">

                                <span class="inline-flex rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-600">
                                    {{ $booking->room->room_type }}
                                </span>

                                <h2 class="mt-4 text-2xl font-bold text-slate-900">
                                    {{ $booking->room->name }}
                                </h2>

                                <a
                                    href="{{ route('homestays.show', $booking->room->homestay->slug) }}"
                                    class="mt-2 inline-block font-medium text-blue-600 hover:text-blue-700"
                                >
                                    {{ $booking->room->homestay->name }}
                                </a>

                                <p class="mt-3 text-sm leading-6 text-slate-500">
                                    {{ $booking->room->homestay->address }}

                                    @if ($booking->room->homestay->city)
                                        , {{ $booking->room->homestay->city }}
                                    @endif
                                </p>

                                <div class="mt-5 grid grid-cols-2 gap-3 sm:grid-cols-3">

                                    <div class="rounded-xl bg-slate-50 p-3">
                                        <p class="text-xs text-slate-400">
                                            Sức chứa
                                        </p>

                                        <p class="mt-1 text-sm font-semibold text-slate-700">
                                            {{ $booking->room->capacity }} khách
                                        </p>
                                    </div>

                                    <div class="rounded-xl bg-slate-50 p-3">
                                        <p class="text-xs text-slate-400">
                                            Số giường
                                        </p>

                                        <p class="mt-1 text-sm font-semibold text-slate-700">
                                            {{ $booking->room->number_of_beds }} giường
                                        </p>
                                    </div>

                                    <div class="rounded-xl bg-slate-50 p-3">
                                        <p class="text-xs text-slate-400">
                                            Diện tích
                                        </p>

                                        <p class="mt-1 text-sm font-semibold text-slate-700">
                                            {{ $booking->room->area ?? 0 }} m²
                                        </p>
                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                    {{-- Thông tin lưu trú --}}
                    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">

                        <h2 class="text-2xl font-bold text-slate-900">
                            Thông tin lưu trú
                        </h2>

                        <div class="mt-6 grid gap-4 sm:grid-cols-2">

                            <div class="rounded-2xl bg-slate-50 p-5">
                                <p class="text-sm text-slate-500">
                                    Ngày nhận phòng
                                </p>

                                <p class="mt-2 text-lg font-bold text-slate-900">
                                    {{ $booking->check_in->format('d/m/Y') }}
                                </p>
                            </div>

                            <div class="rounded-2xl bg-slate-50 p-5">
                                <p class="text-sm text-slate-500">
                                    Ngày trả phòng
                                </p>

                                <p class="mt-2 text-lg font-bold text-slate-900">
                                    {{ $booking->check_out->format('d/m/Y') }}
                                </p>
                            </div>

                            <div class="rounded-2xl bg-slate-50 p-5">
                                <p class="text-sm text-slate-500">
                                    Số đêm
                                </p>

                                <p class="mt-2 text-lg font-bold text-slate-900">
                                    {{ $booking->number_of_nights }} đêm
                                </p>
                            </div>

                            <div class="rounded-2xl bg-slate-50 p-5">
                                <p class="text-sm text-slate-500">
                                    Số khách
                                </p>

                                <p class="mt-2 text-lg font-bold text-slate-900">
                                    {{ $booking->number_of_guests }} khách
                                </p>
                            </div>

                        </div>

                    </div>

                    {{-- Thông tin khách --}}
                    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">

                        <h2 class="text-2xl font-bold text-slate-900">
                            Thông tin khách hàng
                        </h2>

                        <div class="mt-6 divide-y divide-slate-100">

                            <div class="flex flex-col gap-1 py-4 first:pt-0 sm:flex-row sm:items-center sm:justify-between">
                                <span class="text-sm text-slate-500">
                                    Họ và tên
                                </span>

                                <span class="font-semibold text-slate-800">
                                    {{ $booking->customer_name }}
                                </span>
                            </div>

                            <div class="flex flex-col gap-1 py-4 sm:flex-row sm:items-center sm:justify-between">
                                <span class="text-sm text-slate-500">
                                    Email
                                </span>

                                <span class="font-semibold text-slate-800">
                                    {{ $booking->customer_email }}
                                </span>
                            </div>

                            <div class="flex flex-col gap-1 py-4 last:pb-0 sm:flex-row sm:items-center sm:justify-between">
                                <span class="text-sm text-slate-500">
                                    Số điện thoại
                                </span>

                                <span class="font-semibold text-slate-800">
                                    {{ $booking->customer_phone }}
                                </span>
                            </div>

                        </div>

                    </div>

                    @if ($booking->note)
                        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">

                            <h2 class="text-2xl font-bold text-slate-900">
                                Ghi chú
                            </h2>

                            <p class="mt-5 leading-7 text-slate-600">
                                {{ $booking->note }}
                            </p>

                        </div>
                    @endif

                    @if ($booking->status === 'cancelled' && $booking->cancellation_reason)
                        <div class="rounded-3xl border border-red-200 bg-red-50 p-6 sm:p-8">

                            <h2 class="text-xl font-bold text-red-700">
                                Đơn đặt phòng đã bị hủy
                            </h2>

                            <p class="mt-3 leading-7 text-red-600">
                                {{ $booking->cancellation_reason }}
                            </p>

                            @if ($booking->cancelled_at)
                                <p class="mt-3 text-sm text-red-500">
                                    Thời gian hủy:
                                    {{ $booking->cancelled_at->format('H:i d/m/Y') }}
                                </p>
                            @endif

                            @if ($booking->refund_amount > 0)
                                <div class="mt-4 rounded-2xl border border-red-200 bg-white/70 p-4">
                                    <p class="text-sm text-red-600">Số tiền hoàn theo chính sách</p>
                                    <p class="mt-1 text-xl font-bold text-red-700">
                                        {{ number_format($booking->refund_amount, 0, ',', '.') }}đ
                                    </p>
                                </div>
                            @elseif ($isDeposit && in_array($booking->payment_status, ['deposit_paid', 'cancelled'], true))
                                <p class="mt-4 text-sm font-semibold text-red-700">
                                    Khoản cọc 10% không được hoàn do khách chủ động hủy.
                                </p>
                            @endif

                            @if ($booking->payment_status === 'refund_pending')

                                <div class="mt-4 rounded-2xl border border-amber-200 bg-amber-50 p-4">
                                    <p class="font-bold text-amber-700">
                                        Đang hoàn tiền
                                    </p>

                                    <p class="mt-1 text-sm leading-6 text-amber-600">
                                        Yêu cầu hoàn
                                        <strong>
                                            {{ number_format($booking->refund_amount, 0, ',', '.') }}đ
                                        </strong>
                                        đã được gửi và đang chờ VNPAY xác nhận.
                                    </p>
                                </div>

                            @elseif (in_array(
                                $booking->payment_status,
                                ['refunded', 'partially_refunded'],
                                true
                            ))

                                <div class="mt-4 rounded-2xl border border-emerald-200 bg-emerald-50 p-4">

                                    <p class="font-bold text-emerald-700">
                                        ✓ Đã hoàn tiền
                                    </p>

                                    <p class="mt-1 text-sm text-emerald-600">
                                        Số tiền đã hoàn:
                                        <strong>
                                            {{ number_format($actualRefundedAmount, 0, ',', '.') }}đ
                                        </strong>
                                    </p>

                                    @if ($refundPayment?->refunded_at)
                                        <p class="mt-1 text-xs text-emerald-500">
                                            Hoàn lúc:
                                            {{ $refundPayment->refunded_at->format('H:i d/m/Y') }}
                                        </p>
                                    @endif

                                </div>

                            @elseif ($booking->payment_status === 'refund_failed')

                                <div class="mt-4 rounded-2xl border border-red-200 bg-red-50 p-4">

                                    <p class="font-bold text-red-700">
                                        Hoàn tiền chưa thành công
                                    </p>

                                    <p class="mt-1 text-sm leading-6 text-red-600">
                                        Yêu cầu hoàn tiền hiện chưa được VNPAY xác nhận thành công.
                                        Vui lòng chờ quản trị viên kiểm tra lại.
                                    </p>

                                </div>

                            @endif

                        </div>
                    @endif

                    @if ($canCancel)
                        <div id="cancel-booking" class="rounded-3xl border border-red-200 bg-white p-6 shadow-sm sm:p-8">
                            <h2 class="text-2xl font-bold text-slate-900">Hủy đặt phòng</h2>

                            @if ($isDeposit)
                                <p class="mt-3 text-sm leading-6 text-slate-600">
                                    Đơn này sử dụng hình thức cọc 10%. Nếu bạn chủ động hủy, tiền cọc đã thanh toán sẽ không được hoàn lại.
                                </p>
                            @else
                                <p class="mt-3 text-sm leading-6 text-slate-600">
                                    Chính sách tại thời điểm hiện tại: hoàn <strong>{{ $refundPercentage }}%</strong>
                                    @if ($booking->payment_status === 'paid')
                                        (dự kiến {{ number_format($expectedRefundAmount, 0, ',', '.') }}đ).
                                    @else
                                        nếu giao dịch VNPAY đã được thanh toán thành công.
                                    @endif
                                </p>
                                <p class="mt-2 text-xs leading-5 text-slate-500">
                                    ≥ 30 ngày: hoàn 100% · 7–29 ngày: hoàn 50% · dưới 7 ngày: không hoàn.
                                </p>
                            @endif

                            <form method="POST" action="{{ route('bookings.cancel', $booking) }}" class="mt-5"
                                onsubmit="return confirm('Bạn có chắc muốn hủy đặt phòng này không?');">
                                @csrf
                                @method('PATCH')

                                <label for="cancellation_reason" class="mb-2 block text-sm font-semibold text-slate-700">
                                    Lý do hủy <span class="text-red-500">*</span>
                                </label>
                                <textarea id="cancellation_reason" name="cancellation_reason" rows="4" minlength="5" maxlength="500" required
                                    placeholder="Nhập lý do hủy đặt phòng..."
                                    class="w-full resize-none rounded-xl border border-slate-300 px-4 py-3 text-sm leading-6 outline-none transition focus:border-red-500 focus:ring-4 focus:ring-red-100">{{ old('cancellation_reason') }}</textarea>
                                @error('cancellation_reason')
                                    <p class="mt-2 text-sm font-semibold text-red-600">{{ $message }}</p>
                                @enderror

                                <button type="submit"
                                    class="mt-4 inline-flex h-11 w-full cursor-pointer items-center justify-center rounded-xl bg-red-600 px-5 text-sm font-semibold text-white shadow-sm transition hover:bg-red-700 focus:outline-none focus:ring-4 focus:ring-red-200">
                                    Xác nhận hủy đặt phòng
                                </button>
                            </form>
                        </div>
                    @endif

                </div>

                {{-- Cột tổng tiền --}}
                <aside class="lg:sticky lg:top-24 lg:self-start">

                    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-lg">

                        <h2 class="text-xl font-bold text-slate-900">
                            Chi tiết thanh toán
                        </h2>

                        <div class="mt-6 space-y-4 text-sm">

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

                        <div class="mt-6 border-t border-slate-200 pt-5">

                            <div class="flex items-center justify-between gap-4">

                                <span class="font-bold text-slate-900">
                                    Tổng cộng
                                </span>

                                <span class="text-2xl font-bold text-blue-600">
                                    {{ number_format($booking->total_price, 0, ',', '.') }}đ
                                </span>

                            </div>

                        </div>

                        <div class="mt-5 rounded-2xl border border-blue-100 bg-blue-50 p-4 text-sm">
                            <div class="flex items-center justify-between gap-4">
                                <span class="text-blue-700">Hình thức</span>
                                <span class="text-right font-bold text-blue-900">
                                    {{ $isDeposit ? 'Cọc 10% + 90% tiền mặt' : 'VNPAY 100%' }}
                                </span>
                            </div>
                            @if ($isDeposit)
                                <div class="mt-2 flex items-center justify-between gap-4">
                                    <span class="text-blue-700">Cọc qua VNPAY</span>
                                    <span class="font-bold text-blue-900">{{ number_format($vnpayAmount, 0, ',', '.') }}đ</span>
                                </div>
                                <div class="mt-2 flex items-center justify-between gap-4">
                                    <span class="text-blue-700">Còn lại khi check-in</span>
                                    <span class="font-bold text-blue-900">{{ number_format($remainingCashAmount, 0, ',', '.') }}đ</span>
                                </div>
                            @endif
                        </div>

                        <div class="mt-6 rounded-2xl border border-slate-200 bg-slate-50 p-4">

                            <div class="flex items-center justify-between gap-4">

                                <div>
                                    <p class="text-sm text-slate-500">
                                        Trạng thái thanh toán
                                    </p>

                                    <p class="mt-1 text-xs text-slate-400">
                                        Cập nhật tự động sau khi VNPAY xác minh.
                                    </p>
                                </div>

                                <span
                                    class="inline-flex shrink-0 rounded-full border px-3 py-1.5 text-xs font-semibold
                                        {{ $paymentClasses[$booking->payment_status]
                                            ?? 'border-slate-200 bg-slate-100 text-slate-700' }}"
                                >
                                    {{ $paymentLabels[$booking->payment_status]
                                        ?? $booking->payment_status }}
                                </span>

                            </div>

                        </div>

                        @if ($canPay)
                            <a
                                href="{{ route('bookings.payment.show', $booking) }}"
                                class="mt-6 inline-flex w-full items-center justify-center gap-2 rounded-xl bg-blue-600 px-5 py-3.5 text-sm font-bold text-white shadow-lg shadow-blue-200 transition hover:-translate-y-0.5 hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-200"
                            >
                                {{ $paymentButtonLabel }}
                            </a>
                        @elseif ($booking->payment_status === 'paid')
                            <div class="mt-6 rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-center">
                                <p class="font-bold text-emerald-700">✓ Đơn đã được thanh toán đầy đủ</p>
                                <p class="mt-1 text-xs leading-5 text-emerald-600">Bạn không cần thực hiện thêm giao dịch cho đơn này.</p>
                            </div>
                        @elseif ($booking->payment_status === 'deposit_paid')
                            <div class="mt-6 rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-center">
                                <p class="font-bold text-emerald-700">✓ Đã cọc 10% để giữ chỗ</p>
                                <p class="mt-1 text-xs leading-5 text-emerald-600">
                                    Còn {{ number_format($remainingCashAmount, 0, ',', '.') }}đ thanh toán tại homestay khi check-in.
                                </p>
                            </div>
                        @elseif ($booking->status === 'cancelled')
                            <div class="mt-6 rounded-2xl border border-red-200 bg-red-50 p-4 text-center">

                                <p class="font-bold text-red-700">
                                    Đơn đã bị hủy
                                </p>

                                <p class="mt-1 text-xs leading-5 text-red-600">
                                    Không thể thanh toán cho đơn đặt phòng đã hủy.
                                </p>

                            </div>
                        @endif

                        <a
                            href="{{ route('bookings.history') }}"
                            class="mt-3 inline-flex w-full items-center justify-center rounded-xl border border-slate-300 bg-white px-5 py-3 text-sm font-semibold text-slate-700 transition hover:border-blue-600 hover:text-blue-600"
                        >
                            Xem lịch sử đặt phòng
                        </a>

                        <a
                            href="{{ route('homestays.show', $booking->room->homestay->slug) }}"
                            class="mt-3 inline-flex w-full items-center justify-center rounded-xl border border-slate-300 bg-white px-5 py-3 text-sm font-semibold text-slate-700 transition hover:border-blue-600 hover:text-blue-600"
                        >
                            Quay lại Homestay
                        </a>

                    </div>

                </aside>

            </div>

        </section>

    </main>

@endsection
