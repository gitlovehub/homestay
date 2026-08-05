@extends('layouts.admin')

@section('title', 'Quản lý thanh toán | HomeStayGo')
@section('page-title', 'Quản lý thanh toán')

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
            'pending' => ['badge' => 'border-amber-200 bg-amber-50 text-amber-700', 'dot' => 'bg-amber-500'],
            'paid' => ['badge' => 'border-emerald-200 bg-emerald-50 text-emerald-700', 'dot' => 'bg-emerald-500'],
            'failed' => ['badge' => 'border-red-200 bg-red-50 text-red-700', 'dot' => 'bg-red-500'],
            'cancelled' => ['badge' => 'border-slate-300 bg-slate-100 text-slate-700', 'dot' => 'bg-slate-500'],
            'expired' => ['badge' => 'border-orange-200 bg-orange-50 text-orange-700', 'dot' => 'bg-orange-500'],
            'refunded' => ['badge' => 'border-blue-200 bg-blue-50 text-blue-700', 'dot' => 'bg-blue-500'],
        ];
    @endphp

    <div class="mx-auto max-w-screen-2xl">
        <x-alert />

        <p class="mb-8 text-sm font-semibold text-slate-500 md:text-lg">
            Theo dõi và kiểm tra toàn bộ giao dịch thanh toán VNPAY trong hệ thống.
        </p>

        {{-- Thống kê --}}
        <section class="mb-8 grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <div class="flex items-center gap-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
                <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                    <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="2" y="5" width="20" height="14" rx="2" />
                        <path d="M2 10h20" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-slate-500">Tổng giao dịch</p>
                    <p class="mt-1 text-2xl font-bold text-slate-900">
                        {{ number_format($statistics['total'] ?? 0, 0, ',', '.') }}
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
                <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">
                    <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 6 9 17l-5-5" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-slate-500">Đã thanh toán</p>
                    <p class="mt-1 text-2xl font-bold text-slate-900">
                        {{ number_format($statistics['paid'] ?? 0, 0, ',', '.') }}
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
                <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-xl bg-amber-50 text-amber-600">
                    <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="9" />
                        <path d="M12 7v5l3 2" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-slate-500">Đang xử lý</p>
                    <p class="mt-1 text-2xl font-bold text-slate-900">
                        {{ number_format($statistics['pending'] ?? 0, 0, ',', '.') }}
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
                <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-xl bg-violet-50 text-violet-600">
                    <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6" />
                    </svg>
                </div>
                <div class="min-w-0">
                    <p class="text-sm font-medium text-slate-500">Tổng tiền đã nhận</p>
                    <p class="mt-1 truncate text-2xl font-bold text-slate-900">
                        {{ number_format($statistics['total_paid_amount'] ?? 0, 0, ',', '.') }}đ
                    </p>
                </div>
            </div>
        </section>

        {{-- Danh sách giao dịch --}}
        <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-200 bg-slate-50/70 p-5">
                <form method="GET" action="{{ route('admin.payments.index') }}" class="grid gap-4 lg:grid-cols-12">
                    <div class="lg:col-span-6">
                        <label for="search" class="mb-2 block text-sm font-semibold text-slate-700">Tìm kiếm</label>
                        <div class="relative">
                            <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="11" cy="11" r="8" />
                                    <path d="m21 21-4.35-4.35" />
                                </svg>
                            </span>
                            <input
                                id="search"
                                name="search"
                                type="search"
                                value="{{ request('search') }}"
                                placeholder="Mã giao dịch, mã Booking, khách hàng..."
                                class="h-12 w-full rounded-xl border border-slate-300 bg-white pl-11 pr-4 text-sm text-slate-900 shadow-sm outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                                onsearch="this.form.submit()"
                                oninput="if(this.value === '') this.form.submit()"
                            >
                        </div>
                    </div>

                    <div class="lg:col-span-2">
                        <label for="status" class="mb-2 block text-sm font-semibold text-slate-700">Trạng thái</label>
                        <select id="status" name="status" class="h-12 w-full cursor-pointer rounded-xl border border-slate-300 bg-white px-4 text-sm text-slate-900 shadow-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100">
                            <option value="">Tất cả</option>
                            @foreach ($statusLabels as $value => $label)
                                <option value="{{ $value }}" @selected(request('status') === $value)>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="lg:col-span-2">
                        <label for="bank_code" class="mb-2 block text-sm font-semibold text-slate-700">Ngân hàng</label>
                        <select id="bank_code" name="bank_code" class="h-12 w-full cursor-pointer rounded-xl border border-slate-300 bg-white px-4 text-sm text-slate-900 shadow-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100">
                            <option value="">Tất cả</option>
                            @foreach ($bankCodes as $bankCode)
                                <option value="{{ $bankCode }}" @selected(request('bank_code') === $bankCode)>
                                    {{ $bankCode }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex items-end lg:col-span-1">
                        @if (request()->hasAny(['search', 'status', 'bank_code']))
                            <a href="{{ route('admin.payments.index') }}" class="inline-flex h-12 w-full items-center justify-center rounded-xl border border-slate-300 bg-white px-4 text-sm font-semibold text-slate-600 shadow-sm transition hover:bg-slate-50" title="Đặt lại bộ lọc">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M4 4v5h5" />
                                    <path d="M20 20v-5h-5" />
                                    <path d="M20 9A8 8 0 0 0 6 5.3L4 9" />
                                    <path d="M4 15a8 8 0 0 0 14 3.7L20 15" />
                                </svg>
                            </a>
                        @else
                            <button type="button" disabled class="inline-flex h-12 w-full cursor-not-allowed items-center justify-center rounded-xl border border-slate-200 bg-slate-100 px-4 text-sm font-semibold text-slate-400">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M4 4v5h5" />
                                    <path d="M20 20v-5h-5" />
                                    <path d="M20 9A8 8 0 0 0 6 5.3L4 9" />
                                    <path d="M4 15a8 8 0 0 0 14 3.7L20 15" />
                                </svg>
                            </button>
                        @endif
                    </div>

                    <div class="flex items-end lg:col-span-1">
                        <button type="submit" class="inline-flex h-12 w-full cursor-pointer items-center justify-center rounded-xl bg-blue-600 px-4 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700">
                            Lọc
                        </button>
                    </div>
                </form>
            </div>

            @if ($payments->count())
                <div class="overflow-x-auto">
                    <table class="min-h-120 w-full border-collapse text-left">
                        <thead>
                            <tr class="border-b border-slate-200 bg-slate-50/70 text-xs font-bold uppercase tracking-wider text-slate-500">
                                <th class="px-6 py-4">Giao dịch</th>
                                <th class="px-6 py-4">Booking</th>
                                <th class="px-6 py-4">Phương thức</th>
                                <th class="px-6 py-4">Số tiền</th>
                                <th class="px-6 py-4 text-center">Trạng thái</th>
                                <th class="px-6 py-4">Thời gian</th>
                                <th class="px-6 py-4 text-center">Thao tác</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-200 text-sm">
                            @foreach ($payments as $payment)
                                @php
                                    $booking = $payment->booking;
                                    $currentStatus = $statusStyles[$payment->status]
                                        ?? ['badge' => 'border-slate-200 bg-slate-50 text-slate-700', 'dot' => 'bg-slate-500'];
                                @endphp

                                <tr class="transition hover:bg-slate-50/80">
                                    <td class="px-6 py-5">
                                        <div class="max-w-60">
                                            <p class="break-all font-semibold text-blue-600">{{ $payment->transaction_code }}</p>
                                            <p class="mt-1 break-all text-xs text-slate-500">
                                                VNPAY: {{ $payment->gateway_transaction_code ?: 'Chưa có mã' }}
                                            </p>
                                        </div>
                                    </td>

                                    <td class="px-6 py-5">
                                        <div class="max-w-52">
                                            <p class="truncate font-semibold text-slate-900">{{ $booking?->booking_code ?? 'Không xác định' }}</p>
                                            <p class="mt-1 truncate text-xs text-slate-500">{{ $booking?->customer_name ?? 'Khách hàng không xác định' }}</p>
                                            <p class="mt-0.5 truncate text-xs text-slate-400">{{ $booking?->customer_phone ?? 'Không có số điện thoại' }}</p>
                                        </div>
                                    </td>

                                    <td class="whitespace-nowrap px-6 py-5">
                                        <p class="font-semibold uppercase text-slate-900">{{ $payment->payment_method ?: 'VNPAY' }}</p>
                                        <p class="mt-1 text-xs font-semibold text-slate-500">{{ $payment->bank_code ?: 'VNPAY tự chọn' }}</p>
                                    </td>

                                    <td class="whitespace-nowrap px-6 py-5">
                                        <p class="font-bold text-slate-900">{{ number_format($payment->amount, 0, ',', '.') }}đ</p>
                                        <p class="mt-1 text-xs text-slate-400">Mã phản hồi: {{ $payment->response_code ?: '—' }}</p>
                                    </td>

                                    <td class="whitespace-nowrap px-6 py-5 text-center">
                                        <span class="inline-flex items-center gap-2 rounded-full border px-4 py-1.5 text-xs font-semibold {{ $currentStatus['badge'] }}">
                                            <span class="h-1.5 w-1.5 rounded-full {{ $currentStatus['dot'] }}"></span>
                                            {{ $statusLabels[$payment->status] ?? 'Không xác định' }}
                                        </span>
                                    </td>

                                    <td class="whitespace-nowrap px-6 py-5">
                                        @if ($payment->paid_at)
                                            <p class="font-semibold text-slate-800">{{ $payment->paid_at->format('d/m/Y') }}</p>
                                            <p class="mt-1 text-xs text-emerald-600">Thanh toán lúc {{ $payment->paid_at->format('H:i') }}</p>
                                        @else
                                            <p class="font-semibold text-slate-800">{{ $payment->created_at->format('d/m/Y') }}</p>
                                            <p class="mt-1 text-xs text-slate-500">Tạo lúc {{ $payment->created_at->format('H:i') }}</p>
                                        @endif
                                    </td>

                                    <td class="whitespace-nowrap px-6 py-5 text-center">
                                        <details data-action-menu class="relative inline-block text-left">
                                            <summary class="flex h-9 w-9 cursor-pointer list-none items-center justify-center rounded-lg border border-slate-300 bg-white text-lg font-bold text-slate-500 shadow-sm transition hover:bg-slate-50 hover:text-slate-700" title="Thao tác">⋮</summary>

                                            <div class="absolute right-0 z-40 mt-2 w-48 overflow-hidden rounded-xl border border-slate-200 bg-white text-left shadow-xl">
                                                <a href="{{ route('admin.payments.show', $payment) }}" class="flex items-center gap-3 px-4 py-3 text-sm font-medium text-blue-700 transition hover:bg-blue-50">
                                                    <svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                      <path d="M12 2v20"/>
                                                      <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                                                    </svg>
                                                    Chi tiết giao dịch
                                                </a>

                                                @if ($booking)
                                                    <a href="{{ route('admin.bookings.show', $booking) }}" class="flex items-center gap-3 px-4 py-3 text-sm font-medium text-amber-500 transition hover:bg-amber-50">
                                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                        </svg>
                                                        Xem Booking
                                                    </a>
                                                @endif
                                            </div>
                                        </details>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if ($payments->hasPages())
                    <div class="border-t border-slate-200 px-6 py-4">
                        {{ $payments->links() }}
                    </div>
                @endif
            @else
                <div class="px-6 py-20 text-center">
                    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-slate-100 text-3xl text-slate-400">💳</div>
                    <h2 class="mt-5 text-lg font-bold text-slate-900">Chưa có giao dịch phù hợp</h2>
                    <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-slate-500">
                        Không tìm thấy giao dịch thanh toán phù hợp với nội dung tìm kiếm hoặc bộ lọc hiện tại.
                    </p>

                    @if (request()->hasAny(['search', 'status', 'bank_code']))
                        <a href="{{ route('admin.payments.index') }}" class="mt-5 inline-flex items-center justify-center rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-blue-700">
                            Xóa bộ lọc
                        </a>
                    @endif
                </div>
            @endif
        </section>
    </div>
@endsection