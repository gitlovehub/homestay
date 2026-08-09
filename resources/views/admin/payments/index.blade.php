@extends('layouts.admin')

@section('title', 'Quản lý thanh toán | HomeStayGo')
@section('page-title', 'Quản lý thanh toán')

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
            ],
            'paid' => [
                'badge' => 'border-emerald-200 bg-emerald-50 text-emerald-700 dark:border-emerald-800 dark:bg-emerald-950/40 dark:text-emerald-300',
                'dot' => 'bg-emerald-500',
            ],
            'failed' => [
                'badge' => 'border-red-200 bg-red-50 text-red-700 dark:border-red-800 dark:bg-red-950/40 dark:text-red-300',
                'dot' => 'bg-red-500',
            ],
            'refunded' => [
                'badge' => 'border-violet-200 bg-violet-50 text-violet-700 dark:border-violet-800 dark:bg-violet-950/40 dark:text-violet-300',
                'dot' => 'bg-violet-500',
            ],
        ];

        $methodStyles = [
            'cash' => 'border-emerald-200 bg-emerald-50 text-emerald-700 dark:border-emerald-800 dark:bg-emerald-950/40 dark:text-emerald-300',
            'vnpay' => 'border-blue-200 bg-blue-50 text-blue-700 dark:border-blue-800 dark:bg-blue-950/40 dark:text-blue-300',
            'bank_transfer' => 'border-violet-200 bg-violet-50 text-violet-700 dark:border-violet-800 dark:bg-violet-950/40 dark:text-violet-300',
            'momo' => 'border-pink-200 bg-pink-50 text-pink-700 dark:border-pink-800 dark:bg-pink-950/40 dark:text-pink-300',
        ];
    @endphp

    <div class="mx-auto max-w-screen-2xl">
        <x-alert />

        <div class="mb-8">
            <h2 class="text-lg font-semibold text-slate-900 dark:text-slate-100 md:text-2xl">
                Theo dõi và kiểm tra toàn bộ giao dịch thanh toán trong hệ thống.
            </h2>
        </div>

        {{-- Thống kê --}}
        <section class="mb-8 grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <div class="flex items-center gap-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md dark:border-slate-700 dark:bg-slate-800">
                <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-blue-600 dark:bg-blue-950/40 dark:text-blue-400">
                    <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="2" y="5" width="20" height="14" rx="2" />
                        <path d="M2 10h20" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Tổng giao dịch</p>
                    <p class="mt-1 text-2xl font-bold text-slate-900 dark:text-slate-100">
                        {{ number_format($statistics['total'] ?? 0, 0, ',', '.') }}
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md dark:border-slate-700 dark:bg-slate-800">
                <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600 dark:bg-emerald-950/40 dark:text-emerald-400">
                    <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M20 6 9 17l-5-5" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Đã thanh toán</p>
                    <p class="mt-1 text-2xl font-bold text-slate-900 dark:text-slate-100">
                        {{ number_format($statistics['paid'] ?? 0, 0, ',', '.') }}
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md dark:border-slate-700 dark:bg-slate-800">
                <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-xl bg-amber-50 text-amber-600 dark:bg-amber-950/40 dark:text-amber-400">
                    <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="9" />
                        <path d="M12 7v5l3 2" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Đang xử lý</p>
                    <p class="mt-1 text-2xl font-bold text-slate-900 dark:text-slate-100">
                        {{ number_format($statistics['pending'] ?? 0, 0, ',', '.') }}
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md dark:border-slate-700 dark:bg-slate-800">
                <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-xl bg-violet-50 text-violet-600 dark:bg-violet-950/40 dark:text-violet-400">
                    <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6" />
                    </svg>
                </div>
                <div class="min-w-0">
                    <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Số tiền đã hoàn</p>
                    <p class="mt-1 truncate text-2xl font-bold text-slate-900 dark:text-slate-100">
                        {{ number_format($statistics['total_refunded_amount'] ?? 0, 0, ',', '.') }}đ
                    </p>
                </div>
            </div>
        </section>

        <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-800">
            {{-- Bộ lọc --}}
            <div class="border-b border-slate-200 bg-slate-50/70 p-5 dark:border-slate-700 dark:bg-slate-900/60">
                <form method="GET" action="{{ route('admin.payments.index') }}" class="grid gap-4 lg:grid-cols-12">
                    
                    <div class="lg:col-span-4">
                        <label for="search" class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-300">
                            Tìm kiếm
                        </label>

                        <div class="relative">
                            <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="11" cy="11" r="8" />
                                    <path d="m21 21-4.35-4.35" />
                                </svg>
                            </span>

                            <input id="search"
                                name="search"
                                type="search"
                                value="{{ request('search') }}"
                                placeholder="Mã giao dịch, Booking, khách hàng..."
                                class="h-11 w-full rounded-xl border border-slate-300 bg-white pl-11 pr-4 text-sm text-slate-900 shadow-sm outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100 dark:placeholder:text-slate-500 dark:focus:border-blue-400 dark:focus:ring-blue-900/40"
                                onsearch="this.form.submit()"
                                oninput="if(this.value === '') this.form.submit()">
                        </div>
                    </div>

                    <div class="lg:col-span-2">
                        <label for="payment_method" class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-300">
                            Phương thức
                        </label>

                        <select id="payment_method"
                            name="payment_method"
                            class="h-11 w-full cursor-pointer rounded-xl border border-slate-300 bg-white px-4 text-sm text-slate-900 shadow-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100 dark:focus:border-blue-400 dark:focus:ring-blue-900/40">
                            <option value="">Tất cả</option>

                            @foreach ($paymentMethods as $value => $config)
                                <option value="{{ $value }}" @selected(request('payment_method') === $value)>
                                    {{ $config['label'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="lg:col-span-2">
                        <label for="status" class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-300">
                            Trạng thái
                        </label>

                        <select id="status"
                            name="status"
                            class="h-11 w-full cursor-pointer rounded-xl border border-slate-300 bg-white px-4 text-sm text-slate-900 shadow-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100 dark:focus:border-blue-400 dark:focus:ring-blue-900/40">
                            <option value="">Tất cả</option>

                            @foreach ($statusLabels as $value => $label)
                                <option value="{{ $value }}" @selected(request('status') === $value)>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="lg:col-span-2">
                        <label for="sort" class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-300">
                            Sắp xếp
                        </label>

                        <select id="sort"
                            name="sort"
                            class="h-11 w-full cursor-pointer rounded-xl border border-slate-300 bg-white px-3 text-sm text-slate-900 shadow-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100 dark:focus:border-blue-400 dark:focus:ring-blue-900/40">
                            <option value="">Mới nhất</option>
                            <option value="oldest" @selected(request('sort') === 'oldest')>Cũ nhất</option>
                            <option value="amount_desc" @selected(request('sort') === 'amount_desc')>Tiền ↓</option>
                            <option value="amount_asc" @selected(request('sort') === 'amount_asc')>Tiền ↑</option>
                        </select>
                    </div>

                    <div class="flex items-end gap-3 lg:col-span-2">
                        @if (request()->hasAny(['search', 'payment_method', 'status', 'bank_code', 'sort']))
                            <a href="{{ route('admin.payments.index') }}"
                                title="Xóa bộ lọc"
                                class="inline-flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-blue-600 text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-200 dark:focus:ring-blue-900/40">

                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                            </a>
                        @else
                            <button type="button"
                                disabled
                                title="Chưa có bộ lọc"
                                class="inline-flex h-11 w-11 shrink-0 cursor-not-allowed items-center justify-center rounded-xl border border-slate-200 bg-slate-100 text-slate-400 dark:border-slate-700 dark:bg-slate-700 dark:text-slate-500">

                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                            </button>
                        @endif
                    
                        <button type="submit"
                            class="inline-flex h-11 w-full cursor-pointer items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-200 dark:focus:ring-blue-900/40">

                            <svg class="h-5 w-5 shrink-0" xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M4 5h16"></path>
                                <path d="M7 12h10"></path>
                                <path d="M10 19h4"></path>
                            </svg>

                            Lọc
                        </button>
                    </div>

                </form>
            </div>

            @if ($payments->count())
                <div class="overflow-x-auto">
                    <table class="min-h-120 w-full divide-y divide-slate-200 dark:divide-slate-700">
                        <thead class="bg-slate-50 dark:bg-slate-900/70">
                            <tr class="text-left text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                                <th class="px-6 py-4">Giao dịch</th>
                                <th class="px-6 py-4">Booking</th>
                                <th class="px-6 py-4">Phương thức</th>
                                <th class="px-6 py-4">Số tiền</th>
                                <th class="px-6 py-4 text-center">Trạng thái</th>
                                <th class="px-6 py-4">Thời gian</th>
                                <th class="px-6 py-4 text-center">Thao tác</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-200 text-sm dark:divide-slate-700">
                            @foreach ($payments as $payment)
                                @php
                                    $booking = $payment->booking;

                                    $method = $paymentMethods[$payment->payment_method]
                                        ?? [
                                            'label' => 'Không xác định',
                                            'uses_gateway' => false,
                                            'uses_bank' => false,
                                        ];

                                    $currentStatus = $statusStyles[$payment->status]
                                        ?? [
                                            'badge' => 'border-slate-200 bg-slate-50 text-slate-700 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-200',
                                            'dot' => 'bg-slate-500',
                                        ];

                                    $methodStyle = $methodStyles[$payment->payment_method]
                                        ?? 'border-slate-200 bg-slate-50 text-slate-700 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-200';
                                @endphp

                                <tr class="transition hover:bg-slate-50/80 dark:hover:bg-slate-700/40">
                                    <td class="px-6 py-5">
                                        <div class="max-w-60">
                                            <p class="break-all font-semibold text-blue-600 dark:text-blue-400">
                                                {{ $payment->transaction_code ?: 'Chưa có mã' }}
                                            </p>

                                            @if ($method['uses_gateway'] && $payment->gateway_transaction_code)
                                                <p class="mt-1 break-all text-xs text-slate-500 dark:text-slate-400">
                                                    {{ $method['label'] }}:
                                                    {{ $payment->gateway_transaction_code }}
                                                </p>
                                            @endif
                                        </div>
                                    </td>

                                    <td class="px-6 py-5">
                                        <div class="max-w-52">
                                            <p class="truncate font-semibold text-slate-900 dark:text-slate-100">
                                                {{ $booking?->booking_code ?? 'Không xác định' }}
                                            </p>
                                            <p class="mt-1 truncate text-xs text-slate-500 dark:text-slate-400">
                                                {{ $booking?->customer_name ?? 'Khách hàng không xác định' }}
                                            </p>
                                            <p class="mt-0.5 truncate text-xs text-slate-400 dark:text-slate-500">
                                                {{ $booking?->customer_phone ?? 'Không có số điện thoại' }}
                                            </p>
                                        </div>
                                    </td>

                                    <td class="px-6 py-5">
                                        <span class="inline-flex rounded-full border px-3 py-1.5 text-xs font-semibold {{ $methodStyle }}">
                                            {{ $method['label'] }}
                                        </span>
                                    </td>

                                    <td class="whitespace-nowrap px-6 py-5">
                                        <p class="font-bold text-slate-900 dark:text-slate-100">
                                            {{ number_format($payment->amount, 0, ',', '.') }}đ
                                        </p>

                                        @if ($method['uses_gateway'] && $payment->response_code)
                                            <p class="mt-1 text-xs text-slate-400 dark:text-slate-500">
                                                Mã phản hồi: {{ $payment->response_code }}
                                            </p>
                                        @endif
                                    </td>

                                    <td class="whitespace-nowrap px-6 py-5 text-center">
                                        <span class="inline-flex items-center gap-2 rounded-full border px-4 py-1.5 text-xs font-semibold {{ $currentStatus['badge'] }}">
                                            <span class="h-1.5 w-1.5 rounded-full {{ $currentStatus['dot'] }}"></span>
                                            {{ $statusLabels[$payment->status] ?? 'Không xác định' }}
                                        </span>
                                    </td>

                                    <td class="whitespace-nowrap px-6 py-5">
                                        @if ($payment->paid_at)
                                            <p class="font-semibold text-slate-800 dark:text-slate-200">
                                                {{ $payment->paid_at->format('d/m/Y') }}
                                            </p>
                                            <p class="mt-1 text-xs text-emerald-600 dark:text-emerald-400">
                                                Thanh toán lúc {{ $payment->paid_at->format('H:i') }}
                                            </p>
                                        @else
                                            <p class="font-semibold text-slate-800 dark:text-slate-200">
                                                {{ $payment->created_at->format('d/m/Y') }}
                                            </p>
                                            <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
                                                Tạo lúc {{ $payment->created_at->format('H:i') }}
                                            </p>
                                        @endif
                                    </td>

                                    <td class="whitespace-nowrap px-6 py-5 align-middle">
                                        <div
                                            x-data="{
                                                id: 'payment-{{ $payment->id }}',
                                                open: false,
                                                top: 0,
                                                left: 0,

                                                toggle() {
                                                    // Bấm lại chính menu đang mở thì đóng
                                                    if (this.open) {
                                                        this.open = false;
                                                        return;
                                                    }

                                                    const rect = this.$refs.trigger.getBoundingClientRect();

                                                    // w-56 = 224px
                                                    const menuWidth = 224;

                                                    // Luôn mở xuống dưới
                                                    this.top = rect.bottom + 8;

                                                    // Canh phải dropdown theo nút ba chấm
                                                    this.left = rect.right - menuWidth;

                                                    // Không để menu tràn khỏi mép trái màn hình
                                                    if (this.left < 8) {
                                                        this.left = 8;
                                                    }

                                                    // Đóng tất cả action menu khác
                                                    window.dispatchEvent(
                                                        new CustomEvent('action-menu-open', {
                                                            detail: {
                                                                id: this.id
                                                            }
                                                        })
                                                    );

                                                    this.open = true;
                                                },

                                                close() {
                                                    this.open = false;
                                                }
                                            }"

                                            data-action-menu

                                            class="flex justify-center"

                                            @action-menu-open.window="
                                                if ($event.detail.id != id) {
                                                    open = false
                                                }
                                            "

                                            @keydown.escape.window="close()"
                                            @resize.window="close()"
                                            @scroll.window="close()"
                                        >

                                            {{-- Nút ba chấm --}}
                                            <button
                                                x-ref="trigger"
                                                type="button"
                                                @click.stop="toggle()"

                                                class="inline-flex h-10 w-10 cursor-pointer items-center justify-center
                                                    rounded-xl text-slate-500 transition
                                                    hover:bg-slate-100 hover:text-slate-700
                                                    focus:outline-none focus:ring-4 focus:ring-blue-100
                                                    dark:text-slate-400
                                                    dark:hover:bg-slate-700
                                                    dark:hover:text-slate-200
                                                    dark:focus:ring-blue-900/30"

                                                title="Thao tác"
                                                aria-label="Thao tác"
                                                :aria-expanded="open"
                                            >
                                                <svg
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 24 24"
                                                    fill="currentColor"
                                                    class="h-5 w-5"
                                                    aria-hidden="true"
                                                >
                                                    <circle cx="12" cy="5" r="1.8"></circle>
                                                    <circle cx="12" cy="12" r="1.8"></circle>
                                                    <circle cx="12" cy="19" r="1.8"></circle>
                                                </svg>
                                            </button>


                                            {{-- Menu thao tác --}}
                                            <template x-teleport="body">
                                                <div
                                                    x-show="open"
                                                    x-cloak

                                                    @click.outside="close()"

                                                    x-transition:enter="transition ease-out duration-150"
                                                    x-transition:enter-start="opacity-0 scale-95"
                                                    x-transition:enter-end="opacity-100 scale-100"

                                                    x-transition:leave="transition ease-in duration-100"
                                                    x-transition:leave-start="opacity-100 scale-100"
                                                    x-transition:leave-end="opacity-0 scale-95"

                                                    :style="`
                                                        position: fixed;
                                                        top: ${top}px;
                                                        left: ${left}px;
                                                        width: 224px;
                                                    `"

                                                    class="z-[9999] origin-top-right overflow-hidden
                                                        rounded-2xl border border-slate-200/80 bg-white
                                                        shadow-xl shadow-slate-200/50 ring-1 ring-black/5
                                                        dark:border-slate-700/80
                                                        dark:bg-slate-800
                                                        dark:shadow-black/40"
                                                >

                                                    {{-- Chi tiết giao dịch --}}
                                                    <a
                                                        href="{{ route('admin.payments.show', $payment) }}"

                                                        class="group flex items-center gap-3 px-3.5 py-2.5
                                                            text-sm font-medium text-slate-700
                                                            transition-colors
                                                            hover:bg-slate-50
                                                            focus:bg-slate-50 focus:outline-none
                                                            dark:text-slate-200
                                                            dark:hover:bg-slate-700/60
                                                            dark:focus:bg-slate-700/60"
                                                    >
                                                        <span
                                                            class="flex h-8 w-8 shrink-0 items-center justify-center
                                                                rounded-xl bg-slate-100 text-slate-500
                                                                transition-colors
                                                                group-hover:bg-slate-200/80
                                                                group-hover:text-slate-700
                                                                dark:bg-slate-700/70
                                                                dark:text-slate-400
                                                                dark:group-hover:bg-slate-600
                                                                dark:group-hover:text-slate-200"
                                                        >
                                                            <svg
                                                                xmlns="http://www.w3.org/2000/svg"
                                                                viewBox="0 0 24 24"
                                                                fill="none"
                                                                stroke="currentColor"
                                                                stroke-width="1.75"
                                                                stroke-linecap="round"
                                                                stroke-linejoin="round"
                                                                class="h-4 w-4"
                                                            >
                                                                <path d="M12 2v20" />
                                                                <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6" />
                                                            </svg>
                                                        </span>

                                                        <span>Chi tiết giao dịch</span>
                                                    </a>


                                                    @if ($booking)

                                                        {{-- Đường ngăn --}}
                                                        <div
                                                            class="mx-3 border-t border-slate-100
                                                                dark:border-slate-700/80"
                                                        ></div>


                                                        {{-- Xem Booking --}}
                                                        <a
                                                            href="{{ route('admin.bookings.show', $booking) }}"

                                                            class="group flex items-center gap-3 px-3.5 py-2.5
                                                                text-sm font-medium text-blue-600
                                                                transition-colors
                                                                hover:bg-blue-50
                                                                focus:bg-blue-50 focus:outline-none
                                                                dark:text-blue-400
                                                                dark:hover:bg-blue-950/50
                                                                dark:focus:bg-blue-950/50"
                                                        >
                                                            <span
                                                                class="flex h-8 w-8 shrink-0 items-center justify-center
                                                                    rounded-xl bg-blue-50 text-blue-500
                                                                    transition-colors
                                                                    group-hover:bg-blue-100
                                                                    dark:bg-blue-950/50
                                                                    dark:text-blue-400
                                                                    dark:group-hover:bg-blue-950/70"
                                                            >
                                                                <svg
                                                                    xmlns="http://www.w3.org/2000/svg"
                                                                    viewBox="0 0 24 24"
                                                                    fill="none"
                                                                    stroke="currentColor"
                                                                    stroke-width="1.75"
                                                                    stroke-linecap="round"
                                                                    stroke-linejoin="round"
                                                                    class="h-4 w-4"
                                                                >
                                                                    <path d="M8 7V3" />
                                                                    <path d="M16 7V3" />
                                                                    <path d="M5 11h14" />
                                                                    <rect
                                                                        x="3"
                                                                        y="5"
                                                                        width="18"
                                                                        height="16"
                                                                        rx="2"
                                                                    />
                                                                </svg>
                                                            </span>

                                                            <span>Xem Booking</span>
                                                        </a>

                                                    @endif

                                                </div>
                                            </template>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if ($payments->hasPages())
                    <div class="border-t border-slate-200 px-6 py-5 dark:border-slate-700">
                        {{ $payments->onEachSide(1)->links('components.pagination', [
                            'layout' => 'row',
                            'showInfo' => true,
                        ]) }}
                    </div>
                @endif
            @else
                <div class="px-6 py-20 text-center">
                    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-slate-100 text-3xl text-slate-400 dark:bg-slate-700 dark:text-slate-500">
                        💳
                    </div>

                    <h2 class="mt-5 text-lg font-bold text-slate-900 dark:text-slate-100">
                        Chưa có giao dịch phù hợp
                    </h2>

                    <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-slate-500 dark:text-slate-400">
                        Không tìm thấy giao dịch phù hợp với bộ lọc hiện tại.
                    </p>

                    @if (request()->hasAny(['search', 'payment_method', 'status', 'bank_code', 'sort']))
                        <a href="{{ route('admin.payments.index') }}"
                            class="mt-5 inline-flex h-11 items-center justify-center rounded-xl bg-blue-600 px-5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-200 dark:focus:ring-blue-900/40">
                            Xóa bộ lọc
                        </a>
                    @endif
                </div>
            @endif
        </section>
    </div>
@endsection