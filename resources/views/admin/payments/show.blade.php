@extends('layouts.admin')

@section('title', 'Chi tiết thanh toán | HomeStayGo')
@section('page-title', 'Chi tiết thanh toán')

@section('content')
    @php
        $statusLabels = [
            'pending' => 'Đang xử lý',
            'paid' => 'Đã thanh toán',
            'failed' => 'Thất bại',
            'refunded' => 'Đã hoàn tiền',
        ];

        $statusStyles = [
            'pending' => [
                'badge' => 'border-amber-200 bg-amber-50 text-amber-700 dark:border-amber-800 dark:bg-amber-950/40 dark:text-amber-300',
                'dot' => 'bg-amber-500',
                'text' => 'text-amber-700 dark:text-amber-300',
                'panel' => 'border-amber-200 bg-amber-50 dark:border-amber-800 dark:bg-amber-950/40',
            ],
            'paid' => [
                'badge' => 'border-emerald-200 bg-emerald-50 text-emerald-700 dark:border-emerald-800 dark:bg-emerald-950/40 dark:text-emerald-300',
                'dot' => 'bg-emerald-500',
                'text' => 'text-emerald-700 dark:text-emerald-300',
                'panel' => 'border-emerald-200 bg-emerald-50 dark:border-emerald-800 dark:bg-emerald-950/40',
            ],
            'failed' => [
                'badge' => 'border-red-200 bg-red-50 text-red-700 dark:border-red-800 dark:bg-red-950/40 dark:text-red-300',
                'dot' => 'bg-red-500',
                'text' => 'text-red-700 dark:text-red-300',
                'panel' => 'border-red-200 bg-red-50 dark:border-red-800 dark:bg-red-950/40',
            ],
            'refunded' => [
                'badge' => 'border-blue-200 bg-blue-50 text-blue-700 dark:border-blue-800 dark:bg-blue-950/40 dark:text-blue-300',
                'dot' => 'bg-blue-500',
                'text' => 'text-blue-700 dark:text-blue-300',
                'panel' => 'border-blue-200 bg-blue-50 dark:border-blue-800 dark:bg-blue-950/40',
            ],
        ];

        $currentStatus = $statusStyles[$payment->status]
            ?? [
                'badge' => 'border-slate-200 bg-slate-50 text-slate-700 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-200',
                'dot' => 'bg-slate-500',
                'text' => 'text-slate-700 dark:text-slate-200',
                'panel' => 'border-slate-200 bg-slate-50 dark:border-slate-600 dark:bg-slate-700/70',
            ];

        $booking = $payment->booking;
        $room = $booking?->room;
        $homestay = $room?->homestay;

        $responseData = $payment->response_data;

        if (is_string($responseData)) {
            $decodedResponseData = json_decode($responseData, true);

            $responseData = json_last_error() === JSON_ERROR_NONE
                ? $decodedResponseData
                : ['raw' => $responseData];
        }

        $responseData = is_array($responseData)
            ? $responseData
            : [];

        $methodMessage = match ($payment->payment_method) {
            'cash' => 'Thanh toán tiền mặt được ghi nhận khi khách thanh toán trực tiếp.',
            'bank_transfer' => 'Chuyển khoản ngân hàng cần được đối chiếu trước khi ghi nhận thanh toán.',
            'vnpay' => 'Trạng thái giao dịch được cập nhật theo kết quả xác minh từ VNPAY.',
            'momo' => 'Trạng thái giao dịch được cập nhật theo kết quả xác minh từ MoMo.',
            default => 'Trạng thái giao dịch được cập nhật theo phương thức thanh toán tương ứng.',
        };
    @endphp

    <div class="mx-auto max-w-screen-2xl">
        <x-alert />

        <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
            <div>
                <h2 class="text-lg font-semibold text-slate-900 dark:text-slate-100 md:text-2xl">
                    Kiểm tra thông tin chi tiết của giao dịch thanh toán.
                </h2>

                <a href="{{ route('admin.payments.index') }}"
                    class="mt-1 inline-flex items-center gap-1 text-xs font-medium text-blue-600 transition hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 sm:text-sm">
                    ←
                    Trở về danh sách thanh toán
                </a>
            </div>

            <span class="inline-flex w-fit shrink-0 items-center gap-2 rounded-full border px-4 py-2 text-sm font-semibold {{ $currentStatus['badge'] }}">
                <span class="h-2.5 w-2.5 shrink-0 rounded-full {{ $currentStatus['dot'] }}"></span>
                {{ $statusLabels[$payment->status] ?? 'Không xác định' }}
            </span>
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            <div class="min-w-0 space-y-6 lg:col-span-2">

                {{-- Thông tin giao dịch --}}
                <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-800">
                    <div class="border-b border-slate-200 bg-slate-50/80 px-5 py-4 sm:px-6 dark:border-slate-700 dark:bg-slate-900/40">
                        <div class="flex items-center gap-3">
                            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-100 text-xl dark:bg-blue-950/40">
                                💳
                            </span>

                            <div>
                                <h2 class="text-base font-bold text-slate-900 dark:text-slate-100 sm:text-lg">
                                    Thông tin giao dịch
                                </h2>
                                <p class="mt-0.5 text-sm text-slate-500 dark:text-slate-400">
                                    Hiển thị dữ liệu phù hợp với phương thức {{ $paymentMethod['label'] }}.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="p-5 sm:p-6">
                        <dl class="grid gap-4 sm:grid-cols-2">
                            <div class="rounded-xl bg-slate-50 p-4 sm:col-span-2 dark:bg-slate-700/50">
                                <dt class="text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">
                                    Mã giao dịch HomeStayGo
                                </dt>
                                <dd class="mt-1.5 break-all font-bold text-blue-600 dark:text-blue-400">
                                    {{ $payment->transaction_code ?: 'Chưa có mã giao dịch' }}
                                </dd>
                            </div>

                            <div class="rounded-xl bg-slate-50 p-4 dark:bg-slate-700/50">
                                <dt class="text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">
                                    Phương thức
                                </dt>
                                <dd class="mt-1.5 font-semibold text-slate-900 dark:text-slate-100">
                                    {{ $paymentMethod['label'] }}
                                </dd>
                            </div>

                            <div class="rounded-xl bg-slate-50 p-4 dark:bg-slate-700/50">
                                <dt class="text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">
                                    Số tiền
                                </dt>
                                <dd class="mt-1.5 font-bold text-slate-900 dark:text-slate-100">
                                    {{ number_format($payment->amount, 0, ',', '.') }}đ
                                </dd>
                            </div>

                            {{-- Chỉ phương thức dùng ngân hàng mới hiện ngân hàng --}}
                            @if ($paymentMethod['uses_bank'])
                                <div class="rounded-xl bg-slate-50 p-4 dark:bg-slate-700/50">
                                    <dt class="text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">
                                        Ngân hàng
                                    </dt>
                                    <dd class="mt-1.5 font-semibold text-slate-900 dark:text-slate-100">
                                        @if ($payment->bank_code)
                                            {{ $payment->bank_code }}
                                        @elseif ($payment->payment_method === 'vnpay')
                                            VNPAY tự chọn
                                        @else
                                            Chưa ghi nhận ngân hàng
                                        @endif
                                    </dd>
                                </div>
                            @endif

                            {{-- Chỉ cổng thanh toán online mới hiện mã gateway --}}
                            @if ($paymentMethod['uses_gateway'])
                                <div class="rounded-xl bg-slate-50 p-4 dark:bg-slate-700/50">
                                    <dt class="text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">
                                        Mã giao dịch {{ $paymentMethod['label'] }}
                                    </dt>
                                    <dd class="mt-1.5 break-all font-semibold text-slate-900 dark:text-slate-100">
                                        {{ $payment->gateway_transaction_code ?: 'Chưa có mã từ cổng thanh toán' }}
                                    </dd>
                                </div>

                                <div class="rounded-xl bg-slate-50 p-4 dark:bg-slate-700/50">
                                    <dt class="text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">
                                        Mã phản hồi
                                    </dt>
                                    <dd class="mt-1.5 font-semibold text-slate-900 dark:text-slate-100">
                                        {{ $payment->response_code ?: 'Chưa có' }}
                                    </dd>
                                </div>

                                <div class="rounded-xl bg-slate-50 p-4 dark:bg-slate-700/50">
                                    <dt class="text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">
                                        Trạng thái {{ $paymentMethod['label'] }}
                                    </dt>
                                    <dd class="mt-1.5 font-semibold text-slate-900 dark:text-slate-100">
                                        {{ $payment->transaction_status ?: 'Chưa có' }}
                                    </dd>
                                </div>
                            @endif

                            {{-- Tiền mặt không có ngân hàng/gateway --}}
                            @if ($payment->payment_method === 'cash')
                                <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-4 sm:col-span-2 dark:border-emerald-800 dark:bg-emerald-950/40">
                                    <p class="text-sm font-medium text-emerald-700 dark:text-emerald-300">
                                        Đây là giao dịch tiền mặt, không có ngân hàng hoặc mã giao dịch từ cổng thanh toán.
                                    </p>
                                </div>
                            @endif
                        </dl>
                    </div>
                </section>

                {{-- Booking --}}
                <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-800">
                    <div class="border-b border-slate-200 bg-slate-50/80 px-5 py-4 sm:px-6 dark:border-slate-700 dark:bg-slate-900/40">
                        <div class="flex items-center gap-3">
                            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-violet-100 text-xl dark:bg-violet-950/40">
                                📅
                            </span>

                            <div>
                                <h2 class="text-base font-bold text-slate-900 dark:text-slate-100 sm:text-lg">
                                    Thông tin Booking
                                </h2>
                                <p class="mt-0.5 text-sm text-slate-500 dark:text-slate-400">
                                    Đơn đặt phòng liên kết với giao dịch.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="p-5 sm:p-6">
                        @if ($booking)
                            <dl class="grid gap-4 sm:grid-cols-2">
                                <div class="rounded-xl bg-slate-50 p-4 dark:bg-slate-700/50">
                                    <dt class="text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">Mã Booking</dt>
                                    <dd class="mt-1.5 font-bold text-blue-600 dark:text-blue-400">
                                        {{ $booking->booking_code }}
                                    </dd>
                                </div>

                                <div class="rounded-xl bg-slate-50 p-4 dark:bg-slate-700/50">
                                    <dt class="text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">Khách hàng</dt>
                                    <dd class="mt-1.5 font-semibold text-slate-900 dark:text-slate-100">
                                        {{ $booking->customer_name ?: 'Chưa cập nhật' }}
                                    </dd>
                                </div>

                                <div class="rounded-xl bg-slate-50 p-4 dark:bg-slate-700/50">
                                    <dt class="text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">Số điện thoại</dt>
                                    <dd class="mt-1.5 font-semibold text-slate-900 dark:text-slate-100">
                                        {{ $booking->customer_phone ?: 'Chưa cập nhật' }}
                                    </dd>
                                </div>

                                <div class="rounded-xl bg-slate-50 p-4 dark:bg-slate-700/50">
                                    <dt class="text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">Email</dt>
                                    <dd class="mt-1.5 break-all font-semibold text-slate-900 dark:text-slate-100">
                                        {{ $booking->customer_email ?: 'Chưa cập nhật' }}
                                    </dd>
                                </div>

                                <div class="rounded-xl bg-slate-50 p-4 dark:bg-slate-700/50">
                                    <dt class="text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">Ngày nhận phòng</dt>
                                    <dd class="mt-1.5 font-semibold text-slate-900 dark:text-slate-100">
                                        {{ $booking->check_in?->format('d/m/Y') ?? 'Không xác định' }}
                                    </dd>
                                </div>

                                <div class="rounded-xl bg-slate-50 p-4 dark:bg-slate-700/50">
                                    <dt class="text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">Ngày trả phòng</dt>
                                    <dd class="mt-1.5 font-semibold text-slate-900 dark:text-slate-100">
                                        {{ $booking->check_out?->format('d/m/Y') ?? 'Không xác định' }}
                                    </dd>
                                </div>

                                <div class="rounded-xl bg-slate-50 p-4 sm:col-span-2 dark:bg-slate-700/50">
                                    <dt class="text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">
                                        Homestay và phòng
                                    </dt>
                                    <dd class="mt-1.5 font-semibold text-slate-900 dark:text-slate-100">
                                        {{ $homestay?->name ?? 'Homestay không xác định' }}
                                    </dd>
                                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                                        {{ $room?->name ?? 'Phòng không xác định' }}
                                    </p>
                                </div>
                            </dl>
                        @else
                            <div class="rounded-xl border border-amber-200 bg-amber-50 p-4 text-sm text-amber-700 dark:border-amber-800 dark:bg-amber-950/40 dark:text-amber-300">
                                Booking liên kết với giao dịch này không còn tồn tại.
                            </div>
                        @endif
                    </div>
                </section>

                {{-- Chỉ hiện response data khi giao dịch có dữ liệu --}}
                @if ($responseData)
                    <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-800">
                        <div class="border-b border-slate-200 bg-slate-50/80 px-5 py-4 sm:px-6 dark:border-slate-700 dark:bg-slate-900/40">
                            <h2 class="text-base font-bold text-slate-900 dark:text-slate-100 sm:text-lg">
                                @if ($paymentMethod['uses_gateway'])
                                    Dữ liệu phản hồi {{ $paymentMethod['label'] }}
                                @else
                                    Dữ liệu thanh toán
                                @endif
                            </h2>
                        </div>

                        <div class="p-5 sm:p-6">
                            <pre class="max-h-96 overflow-auto whitespace-pre-wrap break-words rounded-2xl bg-slate-950 p-5 text-xs leading-6 text-slate-200">{{ json_encode(
                                $responseData,
                                JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
                            ) }}</pre>
                        </div>
                    </section>
                @endif
            </div>

            <aside class="min-w-0 space-y-6">
                <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-800">
                    <div class="p-6">
                        <p class="text-sm font-semibold text-slate-500 dark:text-slate-400">
                            Giá trị giao dịch
                        </p>
                        <p class="mt-2 text-3xl font-black tracking-tight text-blue-600 dark:text-blue-400">
                            {{ number_format($payment->amount, 0, ',', '.') }}đ
                        </p>

                        <div class="mt-5 rounded-xl border p-4 {{ $currentStatus['panel'] }}">
                            <p class="text-xs font-semibold uppercase tracking-wider {{ $currentStatus['text'] }}">
                                Trạng thái
                            </p>
                            <p class="mt-1 font-bold {{ $currentStatus['text'] }}">
                                {{ $statusLabels[$payment->status] ?? 'Không xác định' }}
                            </p>
                        </div>

                        @if ($payment->paid_at)
                            <div class="mt-5 border-t border-slate-100 pt-5 dark:border-slate-700">
                                <p class="text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">
                                    Thanh toán lúc
                                </p>
                                <p class="mt-1 font-bold text-slate-900 dark:text-slate-100">
                                    {{ $payment->paid_at->format('H:i · d/m/Y') }}
                                </p>
                            </div>
                        @endif
                    </div>
                </section>

                <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-800">
                    <div class="border-b border-slate-200 bg-slate-50/80 px-5 py-4 dark:border-slate-700 dark:bg-slate-900/40">
                        <h2 class="font-bold text-slate-900 dark:text-slate-100">
                            Thao tác nhanh
                        </h2>
                    </div>

                    <div class="space-y-3 p-5">
                        @if ($booking)
                            <a href="{{ route('admin.bookings.show', $booking) }}"
                                class="inline-flex h-11 w-full items-center justify-center rounded-xl bg-blue-600 px-5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-200 dark:focus:ring-blue-900/40">
                                Xem chi tiết Booking
                            </a>
                        @endif

                        <a href="{{ route('admin.payments.index') }}"
                            class="inline-flex h-11 w-full items-center justify-center rounded-xl border border-slate-300 bg-white px-5 text-sm font-semibold text-slate-700 transition hover:border-blue-300 hover:bg-blue-50 hover:text-blue-700 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-300 dark:hover:border-blue-700 dark:hover:bg-blue-950/40 dark:hover:text-blue-400">
                            Danh sách thanh toán
                        </a>

                        <div class="rounded-xl border border-blue-200 bg-blue-50 p-4 text-sm leading-6 text-blue-700 dark:border-blue-800 dark:bg-blue-950/40 dark:text-blue-300">
                            {{ $methodMessage }}
                        </div>
                    </div>
                </section>

                <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-800">
                    <div class="border-b border-slate-200 bg-slate-50/80 px-5 py-4 dark:border-slate-700 dark:bg-slate-900/40">
                        <h2 class="font-bold text-slate-900 dark:text-slate-100">
                            Thông tin hệ thống
                        </h2>
                    </div>

                    <dl class="divide-y divide-slate-100 px-5 dark:divide-slate-700">
                        <div class="flex items-center justify-between gap-3 py-3.5">
                            <dt class="text-sm text-slate-500 dark:text-slate-400">Mã bản ghi</dt>
                            <dd class="font-semibold text-slate-900 dark:text-slate-100">#{{ $payment->id }}</dd>
                        </div>

                        <div class="flex items-center justify-between gap-3 py-3.5">
                            <dt class="text-sm text-slate-500 dark:text-slate-400">Phương thức</dt>
                            <dd class="text-right font-semibold text-slate-900 dark:text-slate-100">
                                {{ $paymentMethod['label'] }}
                            </dd>
                        </div>

                        <div class="flex items-center justify-between gap-3 py-3.5">
                            <dt class="text-sm text-slate-500 dark:text-slate-400">Trạng thái</dt>
                            <dd class="text-right font-semibold {{ $currentStatus['text'] }}">
                                {{ $statusLabels[$payment->status] ?? 'Không xác định' }}
                            </dd>
                        </div>

                        <div class="flex items-start justify-between gap-3 py-3.5">
                            <dt class="text-sm text-slate-500 dark:text-slate-400">Ngày tạo</dt>
                            <dd class="text-right font-semibold text-slate-900 dark:text-slate-100">
                                {{ $payment->created_at->format('H:i · d/m/Y') }}
                            </dd>
                        </div>

                        <div class="flex items-start justify-between gap-3 py-3.5">
                            <dt class="text-sm text-slate-500 dark:text-slate-400">Cập nhật lần cuối</dt>
                            <dd class="text-right font-semibold text-slate-900 dark:text-slate-100">
                                {{ $payment->updated_at->format('H:i · d/m/Y') }}
                            </dd>
                        </div>

                        @if ($payment->expired_at)
                            <div class="flex items-start justify-between gap-3 py-3.5">
                                <dt class="text-sm text-slate-500 dark:text-slate-400">Hết hạn lúc</dt>
                                <dd class="text-right font-semibold text-slate-900 dark:text-slate-100">
                                    {{ $payment->expired_at->format('H:i · d/m/Y') }}
                                </dd>
                            </div>
                        @endif
                    </dl>
                </section>
            </aside>
        </div>
    </div>
@endsection