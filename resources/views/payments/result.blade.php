@extends('layouts.app')

@section('title', 'Kết quả thanh toán | HomeStayGo')

@section('content')
    @php
        $styles = [
            'success' => [
                'box' => 'border-green-200 bg-green-50',
                'icon' => 'bg-green-600',
                'title' => 'Thanh toán thành công',
                'text' => 'text-green-700',
                'symbol' => '✓',
            ],

            'pending' => [
                'box' => 'border-blue-200 bg-blue-50',
                'icon' => 'bg-blue-600',
                'title' => 'Đang xác nhận giao dịch',
                'text' => 'text-blue-700',
                'symbol' => '…',
            ],

            'cancelled' => [
                'box' => 'border-amber-200 bg-amber-50',
                'icon' => 'bg-amber-500',
                'title' => 'Giao dịch đã hủy',
                'text' => 'text-amber-700',
                'symbol' => '!',
            ],

            'expired' => [
                'box' => 'border-amber-200 bg-amber-50',
                'icon' => 'bg-amber-500',
                'title' => 'Giao dịch hết hạn',
                'text' => 'text-amber-700',
                'symbol' => '!',
            ],

            'invalid' => [
                'box' => 'border-red-200 bg-red-50',
                'icon' => 'bg-red-600',
                'title' => 'Dữ liệu không hợp lệ',
                'text' => 'text-red-700',
                'symbol' => '×',
            ],

            'not_found' => [
                'box' => 'border-red-200 bg-red-50',
                'icon' => 'bg-red-600',
                'title' => 'Không tìm thấy giao dịch',
                'text' => 'text-red-700',
                'symbol' => '×',
            ],

            'failed' => [
                'box' => 'border-red-200 bg-red-50',
                'icon' => 'bg-red-600',
                'title' => 'Thanh toán thất bại',
                'text' => 'text-red-700',
                'symbol' => '×',
            ],
        ];

        $style = $styles[$resultStatus]
            ?? $styles['failed'];

        $booking = $payment?->booking;

        $bookingCode = $booking?->getAttribute(
            'booking_code'
        ) ?? (
            $booking
                ? 'BK-' . $booking->id
                : 'Không xác định'
        );
    @endphp

    <section class="min-h-screen bg-slate-50 py-12 sm:py-16">
        <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
            <div
                class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-xl"
            >
                <div
                    class="border-b p-8 text-center {{ $style['box'] }}"
                >
                    <div
                        class="mx-auto flex h-16 w-16 items-center justify-center rounded-full text-3xl font-black text-white {{ $style['icon'] }}"
                    >
                        {{ $style['symbol'] }}
                    </div>

                    <h1 class="mt-5 text-3xl font-bold text-slate-950">
                        {{ $style['title'] }}
                    </h1>

                    <p
                        class="mx-auto mt-3 max-w-xl text-sm leading-7 {{ $style['text'] }}"
                    >
                        {{ $resultMessage }}
                    </p>
                </div>

                <div class="p-6 sm:p-8">
                    @if ($payment)
                        <div class="grid gap-5 sm:grid-cols-2">
                            <div
                                class="rounded-2xl border border-slate-200 bg-slate-50 p-4"
                            >
                                <p class="text-xs font-bold uppercase tracking-wider text-slate-400">
                                    Mã đặt phòng
                                </p>

                                <p class="mt-2 font-bold text-slate-900">
                                    {{ $bookingCode }}
                                </p>
                            </div>

                            <div
                                class="rounded-2xl border border-slate-200 bg-slate-50 p-4"
                            >
                                <p class="text-xs font-bold uppercase tracking-wider text-slate-400">
                                    Mã giao dịch
                                </p>

                                <p class="mt-2 break-all font-bold text-slate-900">
                                    {{ $payment->transaction_ref }}
                                </p>
                            </div>

                            <div
                                class="rounded-2xl border border-slate-200 bg-slate-50 p-4"
                            >
                                <p class="text-xs font-bold uppercase tracking-wider text-slate-400">
                                    Tổng thanh toán
                                </p>

                                <p class="mt-2 text-xl font-bold text-blue-600">
                                    {{ number_format($payment->amount, 0, ',', '.') }} ₫
                                </p>
                            </div>

                            <div
                                class="rounded-2xl border border-slate-200 bg-slate-50 p-4"
                            >
                                <p class="text-xs font-bold uppercase tracking-wider text-slate-400">
                                    Trạng thái hệ thống
                                </p>

                                <p class="mt-2 font-bold text-slate-900">
                                    {{ $payment->status }}
                                </p>
                            </div>

                            @if ($payment->vnp_transaction_no)
                                <div
                                    class="rounded-2xl border border-slate-200 bg-slate-50 p-4"
                                >
                                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400">
                                        Mã giao dịch VNPAY
                                    </p>

                                    <p class="mt-2 break-all font-bold text-slate-900">
                                        {{ $payment->vnp_transaction_no }}
                                    </p>
                                </div>
                            @endif

                            @if ($payment->vnp_bank_code)
                                <div
                                    class="rounded-2xl border border-slate-200 bg-slate-50 p-4"
                                >
                                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400">
                                        Ngân hàng
                                    </p>

                                    <p class="mt-2 font-bold text-slate-900">
                                        {{ $payment->vnp_bank_code }}
                                    </p>
                                </div>
                            @endif
                        </div>
                    @endif

                    @if ($resultStatus === 'pending')
                        <div
                            class="mt-6 rounded-2xl border border-blue-200 bg-blue-50 p-4 text-sm leading-6 text-blue-700"
                        >
                            IPN có thể đến sau Return URL vài giây.
                            Hãy chờ một lúc rồi tải lại trang để xem trạng thái mới nhất.
                        </div>
                    @endif

                    <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                        @if ($booking)
                            <a
                                href="{{ route('bookings.show', $booking) }}"
                                class="inline-flex flex-1 items-center justify-center rounded-xl bg-blue-600 px-5 py-3 text-sm font-bold text-white transition hover:bg-blue-700"
                            >
                                Xem chi tiết đặt phòng
                            </a>
                        @else
                            <a
                                href="{{ route('home') }}"
                                class="inline-flex flex-1 items-center justify-center rounded-xl bg-blue-600 px-5 py-3 text-sm font-bold text-white transition hover:bg-blue-700"
                            >
                                Về trang chủ
                            </a>
                        @endif

                        <button
                            type="button"
                            onclick="window.location.reload()"
                            class="inline-flex cursor-pointer flex-1 items-center justify-center rounded-xl border border-slate-300 bg-white px-5 py-3 text-sm font-bold text-slate-700 transition hover:bg-slate-50"
                        >
                            Tải lại trạng thái
                        </button>
                    </div>

                    @if (
                        in_array(
                            $resultStatus,
                            [
                                'failed',
                                'cancelled',
                                'expired',
                            ],
                            true
                        )
                        && $booking
                    )
                        <a
                            href="{{ route('payments.checkout', $booking) }}"
                            class="mt-3 inline-flex w-full items-center justify-center rounded-xl border border-blue-200 bg-blue-50 px-5 py-3 text-sm font-bold text-blue-700 transition hover:bg-blue-100"
                        >
                            Thanh toán lại
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection
