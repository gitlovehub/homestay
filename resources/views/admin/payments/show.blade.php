@extends('layouts.admin')

@section('title', 'Chi tiết thanh toán | HomeStayGo')
@section('page-title', 'Chi tiết thanh toán')

@section('content')
    @php
        $statusLabels = [
            'pending' => 'Đang xử lý',
            'paid' => 'Đã thanh toán',
            'failed' => 'Thất bại',
            'cancelled' => 'Đã hủy',
            'expired' => 'Đã hết hạn',
            'refunded' => 'Đã hoàn tiền',
        ];

        $statusStyles = [
            'pending' => [
                'badge' => 'border-amber-200 bg-amber-50 text-amber-700',
                'dot' => 'bg-amber-500',
                'text' => 'text-amber-700',
                'panel' => 'border-amber-200 bg-amber-50',
            ],
            'paid' => [
                'badge' => 'border-emerald-200 bg-emerald-50 text-emerald-700',
                'dot' => 'bg-emerald-500',
                'text' => 'text-emerald-700',
                'panel' => 'border-emerald-200 bg-emerald-50',
            ],
            'failed' => [
                'badge' => 'border-red-200 bg-red-50 text-red-700',
                'dot' => 'bg-red-500',
                'text' => 'text-red-700',
                'panel' => 'border-red-200 bg-red-50',
            ],
            'cancelled' => [
                'badge' => 'border-slate-300 bg-slate-100 text-slate-700',
                'dot' => 'bg-slate-500',
                'text' => 'text-slate-700',
                'panel' => 'border-slate-200 bg-slate-50',
            ],
            'expired' => [
                'badge' => 'border-orange-200 bg-orange-50 text-orange-700',
                'dot' => 'bg-orange-500',
                'text' => 'text-orange-700',
                'panel' => 'border-orange-200 bg-orange-50',
            ],
            'refunded' => [
                'badge' => 'border-blue-200 bg-blue-50 text-blue-700',
                'dot' => 'bg-blue-500',
                'text' => 'text-blue-700',
                'panel' => 'border-blue-200 bg-blue-50',
            ],
        ];

        $currentStatus = $statusStyles[$payment->status]
            ?? [
                'badge' => 'border-slate-200 bg-slate-50 text-slate-700',
                'dot' => 'bg-slate-500',
                'text' => 'text-slate-700',
                'panel' => 'border-slate-200 bg-slate-50',
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
    @endphp

    <div class="mx-auto max-w-screen-2xl">
        <x-alert />

        {{-- Header --}}
        <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
            <div>
                <p class="text-sm font-semibold text-slate-500 md:text-lg">
                    Kiểm tra thông tin giao dịch, Booking và dữ liệu phản hồi từ VNPAY.
                </p>

                <a href="{{ route('admin.payments.index') }}" class="mt-3 inline-flex items-center gap-2 text-sm font-semibold text-blue-600 transition hover:text-blue-700">
                    <span>←</span>
                    Quay lại danh sách thanh toán
                </a>
            </div>

            <span class="inline-flex w-fit shrink-0 items-center gap-2 rounded-full border px-4 py-2 text-sm font-semibold {{ $currentStatus['badge'] }}">
                <span class="h-2.5 w-2.5 shrink-0 rounded-full {{ $currentStatus['dot'] }}"></span>
                {{ $statusLabels[$payment->status] ?? 'Không xác định' }}
            </span>
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            {{-- Cột trái --}}
            <div class="min-w-0 space-y-6 lg:col-span-2">

                {{-- Thông tin giao dịch --}}
                <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                    <div class="border-b border-slate-200 bg-slate-50/80 px-5 py-4 sm:px-6">
                        <div class="flex items-center gap-3">
                            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-100 text-xl">💳</span>
                            <div>
                                <h2 class="text-base font-bold text-slate-900 sm:text-lg">Thông tin giao dịch</h2>
                                <p class="mt-0.5 text-sm text-slate-500">Mã giao dịch nội bộ và dữ liệu đối chiếu VNPAY</p>
                            </div>
                        </div>
                    </div>

                    <div class="p-5 sm:p-6">
                        <dl class="grid gap-4 sm:grid-cols-2">
                            <div class="rounded-xl bg-slate-50 p-4 sm:col-span-2">
                                <dt class="text-xs font-semibold uppercase tracking-wider text-slate-400">Mã giao dịch HomeStayGo</dt>
                                <dd class="mt-1.5 break-all font-bold text-blue-600">{{ $payment->transaction_code }}</dd>
                            </div>

                            <div class="rounded-xl bg-slate-50 p-4 sm:col-span-2">
                                <dt class="text-xs font-semibold uppercase tracking-wider text-slate-400">Mã giao dịch VNPAY</dt>
                                <dd class="mt-1.5 break-all font-semibold text-slate-900">
                                    {{ $payment->gateway_transaction_code ?: 'Chưa có mã giao dịch VNPAY' }}
                                </dd>
                            </div>

                            <div class="rounded-xl bg-slate-50 p-4">
                                <dt class="text-xs font-semibold uppercase tracking-wider text-slate-400">Phương thức</dt>
                                <dd class="mt-1.5 font-semibold uppercase text-slate-900">{{ $payment->payment_method ?: 'VNPAY' }}</dd>
                            </div>

                            <div class="rounded-xl bg-slate-50 p-4">
                                <dt class="text-xs font-semibold uppercase tracking-wider text-slate-400">Ngân hàng</dt>
                                <dd class="mt-1.5 font-semibold text-slate-900">{{ $payment->bank_code ?: 'VNPAY tự chọn' }}</dd>
                            </div>

                            <div class="rounded-xl bg-slate-50 p-4">
                                <dt class="text-xs font-semibold uppercase tracking-wider text-slate-400">Mã phản hồi</dt>
                                <dd class="mt-1.5 font-semibold text-slate-900">{{ $payment->response_code ?: 'Chưa có' }}</dd>
                            </div>

                            <div class="rounded-xl bg-slate-50 p-4">
                                <dt class="text-xs font-semibold uppercase tracking-wider text-slate-400">Trạng thái VNPAY</dt>
                                <dd class="mt-1.5 font-semibold text-slate-900">{{ $payment->transaction_status ?: 'Chưa có' }}</dd>
                            </div>
                        </dl>
                    </div>
                </section>

                {{-- Thông tin Booking --}}
                <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                    <div class="border-b border-slate-200 bg-slate-50/80 px-5 py-4 sm:px-6">
                        <div class="flex items-center gap-3">
                            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-violet-100 text-xl">📅</span>
                            <div>
                                <h2 class="text-base font-bold text-slate-900 sm:text-lg">Thông tin Booking</h2>
                                <p class="mt-0.5 text-sm text-slate-500">Đơn đặt phòng liên kết với giao dịch</p>
                            </div>
                        </div>
                    </div>

                    <div class="p-5 sm:p-6">
                        @if ($booking)
                            <dl class="grid gap-4 sm:grid-cols-2">
                                <div class="rounded-xl bg-slate-50 p-4">
                                    <dt class="text-xs font-semibold uppercase tracking-wider text-slate-400">Mã Booking</dt>
                                    <dd class="mt-1.5 font-bold text-blue-600">{{ $booking->booking_code }}</dd>
                                </div>

                                <div class="rounded-xl bg-slate-50 p-4">
                                    <dt class="text-xs font-semibold uppercase tracking-wider text-slate-400">Trạng thái thanh toán</dt>
                                    <dd class="mt-1.5 font-semibold {{ $currentStatus['text'] }}">
                                        {{ $statusLabels[$payment->status] ?? 'Không xác định' }}
                                    </dd>
                                </div>

                                <div class="rounded-xl bg-slate-50 p-4">
                                    <dt class="text-xs font-semibold uppercase tracking-wider text-slate-400">Khách hàng</dt>
                                    <dd class="mt-1.5 font-semibold text-slate-900">{{ $booking->customer_name }}</dd>
                                </div>

                                <div class="rounded-xl bg-slate-50 p-4">
                                    <dt class="text-xs font-semibold uppercase tracking-wider text-slate-400">Số điện thoại</dt>
                                    <dd class="mt-1.5 font-semibold text-slate-900">{{ $booking->customer_phone }}</dd>
                                </div>

                                <div class="rounded-xl bg-slate-50 p-4">
                                    <dt class="text-xs font-semibold uppercase tracking-wider text-slate-400">Email</dt>
                                    <dd class="mt-1.5 break-all font-semibold text-slate-900">{{ $booking->customer_email }}</dd>
                                </div>

                                <div class="rounded-xl bg-slate-50 p-4">
                                    <dt class="text-xs font-semibold uppercase tracking-wider text-slate-400">Số khách</dt>
                                    <dd class="mt-1.5 font-semibold text-slate-900">{{ $booking->number_of_guests }} khách</dd>
                                </div>

                                <div class="rounded-xl bg-slate-50 p-4">
                                    <dt class="text-xs font-semibold uppercase tracking-wider text-slate-400">Ngày nhận phòng</dt>
                                    <dd class="mt-1.5 font-semibold text-slate-900">{{ $booking->check_in?->format('d/m/Y') ?? 'Không xác định' }}</dd>
                                </div>

                                <div class="rounded-xl bg-slate-50 p-4">
                                    <dt class="text-xs font-semibold uppercase tracking-wider text-slate-400">Ngày trả phòng</dt>
                                    <dd class="mt-1.5 font-semibold text-slate-900">{{ $booking->check_out?->format('d/m/Y') ?? 'Không xác định' }}</dd>
                                </div>

                                <div class="rounded-xl bg-slate-50 p-4 sm:col-span-2">
                                    <dt class="text-xs font-semibold uppercase tracking-wider text-slate-400">Homestay và phòng</dt>
                                    <dd class="mt-1.5 font-semibold text-slate-900">{{ $homestay?->name ?? 'Homestay không xác định' }}</dd>
                                    <p class="mt-1 text-sm text-slate-500">{{ $room?->name ?? 'Phòng không xác định' }}</p>
                                </div>
                            </dl>
                        @else
                            <div class="rounded-xl border border-amber-200 bg-amber-50 p-4 text-sm text-amber-700">
                                Booking liên kết với giao dịch này không còn tồn tại.
                            </div>
                        @endif
                    </div>
                </section>

                {{-- Dữ liệu phản hồi --}}
                <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                    <div class="border-b border-slate-200 bg-slate-50/80 px-5 py-4 sm:px-6">
                        <div class="flex items-center gap-3">
                            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-slate-200 font-bold text-slate-700">{ }</span>
                            <div>
                                <h2 class="text-base font-bold text-slate-900 sm:text-lg">Dữ liệu phản hồi VNPAY</h2>
                                <p class="mt-0.5 text-sm text-slate-500">Dữ liệu được lưu sau khi hệ thống xác minh giao dịch</p>
                            </div>
                        </div>
                    </div>

                    <div class="p-5 sm:p-6">
                        @if ($responseData)
                            <pre class="max-h-96 overflow-auto whitespace-pre-wrap break-words rounded-2xl bg-slate-950 p-5 text-xs leading-6 text-slate-200">{{ json_encode(
                                $responseData,
                                JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
                            ) }}</pre>
                        @else
                            <div class="rounded-xl border border-slate-200 bg-slate-50 p-4 text-sm text-slate-600">
                                Giao dịch chưa có dữ liệu phản hồi được lưu.
                            </div>
                        @endif
                    </div>
                </section>
            </div>

            {{-- Cột phải --}}
            <aside class="min-w-0 space-y-6">
                {{-- Số tiền --}}
                <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                    <div class="p-6">
                        <p class="text-sm font-semibold text-slate-500">Giá trị giao dịch</p>
                        <p class="mt-2 text-3xl font-black tracking-tight text-blue-600">
                            {{ number_format($payment->amount, 0, ',', '.') }}đ
                        </p>

                        <div class="mt-5 rounded-xl border p-4 {{ $currentStatus['panel'] }}">
                            <p class="text-xs font-semibold uppercase tracking-wider {{ $currentStatus['text'] }}">Trạng thái</p>
                            <p class="mt-1 font-bold {{ $currentStatus['text'] }}">
                                {{ $statusLabels[$payment->status] ?? 'Không xác định' }}
                            </p>
                        </div>

                        @if ($payment->paid_at)
                            <div class="mt-5 border-t border-slate-100 pt-5">
                                <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Thanh toán lúc</p>
                                <p class="mt-1 font-bold text-slate-900">{{ $payment->paid_at->format('H:i · d/m/Y') }}</p>
                            </div>
                        @endif
                    </div>
                </section>

                {{-- Thao tác nhanh --}}
                <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                    <div class="border-b border-slate-200 bg-slate-50/80 px-5 py-4">
                        <h2 class="font-bold text-slate-900">Thao tác nhanh</h2>
                    </div>

                    <div class="space-y-3 p-5">
                        @if ($booking)
                            <a href="{{ route('admin.bookings.show', $booking) }}" class="inline-flex w-full items-center justify-center rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700">
                                Xem chi tiết Booking
                            </a>
                        @endif

                        <a href="{{ route('admin.payments.index') }}" class="inline-flex w-full items-center justify-center rounded-xl border border-slate-300 bg-white px-5 py-3 text-sm font-semibold text-slate-700 transition hover:border-blue-300 hover:bg-blue-50 hover:text-blue-700">
                            Danh sách thanh toán
                        </a>

                        <div class="rounded-xl border border-blue-200 bg-blue-50 p-4 text-sm leading-6 text-blue-700">
                            Trạng thái giao dịch chỉ được cập nhật sau khi dữ liệu VNPAY được hệ thống xác minh.
                        </div>
                    </div>
                </section>

                {{-- Thông tin hệ thống --}}
                <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                    <div class="border-b border-slate-200 bg-slate-50/80 px-5 py-4">
                        <h2 class="font-bold text-slate-900">Thông tin hệ thống</h2>
                    </div>

                    <dl class="divide-y divide-slate-100 px-5">
                        <div class="flex items-center justify-between gap-3 py-3.5">
                            <dt class="text-sm text-slate-500">Mã bản ghi</dt>
                            <dd class="font-semibold text-slate-900">#{{ $payment->id }}</dd>
                        </div>

                        <div class="flex items-center justify-between gap-3 py-3.5">
                            <dt class="text-sm text-slate-500">Trạng thái</dt>
                            <dd class="text-right font-semibold {{ $currentStatus['text'] }}">
                                {{ $statusLabels[$payment->status] ?? 'Không xác định' }}
                            </dd>
                        </div>

                        <div class="flex items-start justify-between gap-3 py-3.5">
                            <dt class="text-sm text-slate-500">Ngày tạo</dt>
                            <dd class="text-right font-semibold text-slate-900">{{ $payment->created_at->format('H:i · d/m/Y') }}</dd>
                        </div>

                        <div class="flex items-start justify-between gap-3 py-3.5">
                            <dt class="text-sm text-slate-500">Cập nhật lần cuối</dt>
                            <dd class="text-right font-semibold text-slate-900">{{ $payment->updated_at->format('H:i · d/m/Y') }}</dd>
                        </div>

                        <div class="flex items-start justify-between gap-3 py-3.5">
                            <dt class="text-sm text-slate-500">Hết hạn lúc</dt>
                            <dd class="text-right font-semibold text-slate-900">
                                {{ $payment->expired_at?->format('H:i · d/m/Y') ?? 'Không có' }}
                            </dd>
                        </div>
                    </dl>
                </section>
            </aside>
        </div>
    </div>
@endsection