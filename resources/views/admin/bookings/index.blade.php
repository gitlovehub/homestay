@extends('layouts.admin')

@section('title', 'Quản lý đặt phòng | HomeStayGo')

@section('page-title', 'Quản lý đặt phòng')

@section('content')
    <div class="mx-auto max-w-screen-2xl">

        @php
            $currentYear = now()->year;

            $statusLabels = [
                'pending' => 'Chờ xác nhận',
                'confirmed' => 'Đã xác nhận',
                'checked_in' => 'Đã nhận phòng',
                'completed' => 'Đã hoàn thành',
                'cancelled' => 'Đã hủy',
            ];

            $paymentLabels = [
                'unpaid' => 'Chưa thanh toán',
                'pending' => 'Đang xử lý',
                'paid' => 'Đã thanh toán',
                'refunded' => 'Đã hoàn tiền',
                'failed' => 'Thanh toán thất bại',
            ];

            $paymentStatus = [
                'unpaid' => 'text-slate-500 dark:text-slate-400',
                'pending' => 'text-amber-600 dark:text-amber-400',
                'paid' => 'text-emerald-600 dark:text-emerald-400',
                'refunded' => 'text-blue-600 dark:text-blue-400',
                'failed' => 'text-red-600 dark:text-red-400',
            ];
        @endphp

        <x-alert />

        <div class="mb-8">
            <h2 class="text-lg font-semibold text-slate-900 dark:text-slate-100 md:text-2xl">
                Danh sách các đơn đặt phòng trong hệ thống.
            </h2>
        </div>

        {{-- Thống kê --}}
        <section class="mb-8 grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">

            <div
                class="flex items-center gap-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md dark:border-slate-700 dark:bg-slate-800">
                <div
                    class="flex h-14 w-14 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-blue-600 dark:bg-blue-950/50 dark:text-blue-400">
                    <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>

                <div>
                    <p class="text-sm font-medium text-slate-500 dark:text-slate-400">
                        Tổng Booking
                    </p>
                    <p class="mt-1 text-2xl font-bold text-slate-900 dark:text-slate-100">
                        {{ number_format($statistics['total'], 0, ',', '.') }}
                    </p>
                </div>
            </div>

            <div
                class="flex items-center gap-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md dark:border-slate-700 dark:bg-slate-800">
                <div
                    class="flex h-14 w-14 shrink-0 items-center justify-center rounded-xl bg-amber-50 text-amber-600 dark:bg-amber-950/50 dark:text-amber-400">
                    <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="9" stroke-width="2" />
                        <path stroke-linecap="round" stroke-width="2" d="M12 7v5l3 2" />
                    </svg>
                </div>

                <div>
                    <p class="text-sm font-medium text-slate-500 dark:text-slate-400">
                        Chờ xác nhận
                    </p>
                    <p class="mt-1 text-2xl font-bold text-slate-900 dark:text-slate-100">
                        {{ number_format($statistics['pending'], 0, ',', '.') }}
                    </p>
                </div>
            </div>

            <div
                class="flex items-center gap-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md dark:border-slate-700 dark:bg-slate-800">
                <div
                    class="flex h-14 w-14 shrink-0 items-center justify-center rounded-xl bg-violet-50 text-violet-600 dark:bg-violet-950/50 dark:text-violet-400">
                    <svg viewBox="0 0 24 24" class="h-7 w-7" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <path d="M5 22h14"/>
                      <path d="M5 2h14"/>
                      <path d="M17 22v-4.172a2 2 0 0 0-.586-1.414L12 12l-4.414 4.414A2 2 0 0 0 7 17.828V22"/>
                      <path d="M7 2v4.172a2 2 0 0 0 .586 1.414L12 12l4.414-4.414A2 2 0 0 0 17 6.172V2"/>
                    </svg>
                </div>

                <div>
                    <p class="text-sm font-medium text-slate-500 dark:text-slate-400">
                        Đang xử lý
                    </p>
                    <p class="mt-1 text-2xl font-bold text-slate-900 dark:text-slate-100">
                        {{ number_format($statistics['in_progress'], 0, ',', '.') }}
                    </p>
                </div>
            </div>

            <div
                class="flex items-center gap-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md dark:border-slate-700 dark:bg-slate-800">
                <div
                    class="flex h-14 w-14 shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600 dark:bg-emerald-950/50 dark:text-emerald-400">
                    <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>

                <div>
                    <p class="text-sm font-medium text-slate-500 dark:text-slate-400">
                        Đã hoàn thành
                    </p>
                    <p class="mt-1 text-2xl font-bold text-slate-900 dark:text-slate-100">
                        {{ number_format($statistics['completed'], 0, ',', '.') }}
                    </p>
                </div>
            </div>

        </section>

        <section
            class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-800">

            {{-- Bộ lọc --}}
            <div class="border-b border-slate-200 bg-slate-50/70 p-5 dark:border-slate-700 dark:bg-slate-900/60">
                <form method="GET" action="{{ route('admin.bookings.index') }}"
                    class="grid gap-4 lg:grid-cols-12">

                    <div class="lg:col-span-4">
                        <label for="search"
                            class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-300">
                            Tìm kiếm
                        </label>

                        <div class="relative">
                            <span
                                class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400 dark:text-slate-500">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="m21 21-4.35-4.35M16.65 11A5.65 5.65 0 1 1 11 5.35 5.65 5.65 0 0 1 16.65 11Z" />
                                </svg>
                            </span>

                            <input id="search" name="search" type="search" value="{{ request('search') }}"
                                placeholder="Mã đơn, khách hàng, phòng, Homestay..."
                                class="h-11 w-full rounded-xl border border-slate-300 bg-white pl-11 pr-4 text-sm text-slate-900 shadow-sm outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100 dark:placeholder:text-slate-500 dark:focus:border-blue-400 dark:focus:ring-blue-900/40"
                                onsearch="this.form.submit()" oninput="if(this.value === '') this.form.submit()">
                        </div>
                    </div>

                    <div class="lg:col-span-2">
                        <label for="status"
                            class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-300">
                            Trạng thái
                        </label>

                        <select id="status" name="status"
                            class="h-11 w-full cursor-pointer rounded-xl border border-slate-300 bg-white px-4 text-sm text-slate-900 shadow-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100 dark:focus:border-blue-400 dark:focus:ring-blue-900/40">
                            <option value="">Tất cả trạng thái</option>

                            @foreach ($statusLabels as $value => $label)
                                <option value="{{ $value }}" @selected(request('status') === $value)>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="lg:col-span-2">
                        <label for="payment_status"
                            class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-300">
                            Thanh toán
                        </label>

                        <select id="payment_status" name="payment_status"
                            class="h-11 w-full cursor-pointer rounded-xl border border-slate-300 bg-white px-4 text-sm text-slate-900 shadow-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100 dark:focus:border-blue-400 dark:focus:ring-blue-900/40">
                            <option value="">Tất cả thanh toán</option>

                            @foreach ($paymentLabels as $value => $label)
                                <option value="{{ $value }}" @selected(request('payment_status') === $value)>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="lg:col-span-2">
                        <label for="sort"
                            class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-300">
                            Sắp xếp
                        </label>

                        <select id="sort" name="sort"
                            class="h-11 w-full cursor-pointer rounded-xl border border-slate-300 bg-white px-4 text-sm text-slate-900 shadow-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100 dark:focus:border-blue-400 dark:focus:ring-blue-900/40">
                            <option value="latest" @selected(request('sort', 'latest') === 'latest')>Mới nhất</option>
                            <option value="oldest" @selected(request('sort') === 'oldest')>Cũ nhất</option>
                            <option value="total_desc" @selected(request('sort') === 'total_desc')>Tổng tiền cao đến thấp</option>
                            <option value="total_asc" @selected(request('sort') === 'total_asc')>Tổng tiền thấp đến cao</option>
                            <option value="check_in_asc" @selected(request('sort') === 'check_in_asc')>Ngày nhận gần nhất</option>
                            <option value="check_in_desc" @selected(request('sort') === 'check_in_desc')>Ngày nhận xa nhất</option>
                        </select>
                    </div>

                    <div class="flex items-end lg:col-span-1">
                        @if (request()->hasAny(['search', 'status', 'payment_status', 'sort']))
                            <a href="{{ route('admin.bookings.index') }}" title="Xóa bộ lọc"
                                class="inline-flex h-11 w-full items-center justify-center rounded-xl border border-slate-300 bg-white px-4 text-sm font-semibold text-slate-600 shadow-sm transition hover:border-blue-300 hover:bg-blue-50 hover:text-blue-600 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-300 dark:hover:border-blue-500 dark:hover:bg-blue-950/40 dark:hover:text-blue-400">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                            </a>
                        @else
                            <button type="button" disabled title="Chưa có bộ lọc"
                                class="inline-flex h-11 w-full cursor-not-allowed items-center justify-center rounded-xl border border-slate-200 bg-slate-100 px-4 text-sm font-semibold text-slate-400 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-600">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                            </button>
                        @endif
                    </div>

                    <div class="flex items-end lg:col-span-1">
                        <button type="submit"
                            class="inline-flex h-11 w-full cursor-pointer items-center justify-center rounded-xl bg-blue-600 px-4 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-200 dark:bg-blue-500 dark:hover:bg-blue-600 dark:focus:ring-blue-900/50">
                            Lọc
                        </button>
                    </div>
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="min-h-120 w-full divide-y divide-slate-200 dark:divide-slate-700">
                    <thead class="bg-slate-50 dark:bg-slate-900/70">
                        <tr>
                            <th class="px-5 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                                Mã Booking
                            </th>
                            <th class="px-5 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                                Khách hàng
                            </th>
                            <th class="px-5 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                                Phòng
                            </th>
                            <th class="px-5 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                                Lưu trú
                            </th>
                            <th class="px-5 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                                Tổng tiền
                            </th>
                            <th class="px-5 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                                Trạng thái
                            </th>
                            <th class="px-5 py-4 text-center text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                                Thao tác
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100 bg-white dark:divide-slate-700 dark:bg-slate-800">
                        @forelse ($bookings as $booking)
                            <tr class="transition hover:bg-slate-50 dark:hover:bg-slate-700/50">
                                <td class="whitespace-nowrap px-5 py-5">
                                    <p class="font-bold text-blue-600 dark:text-blue-400">
                                        {{ $booking->booking_code }}
                                    </p>

                                    <p class="mt-1 text-xs text-slate-400 dark:text-slate-500">
                                        {{ $booking->created_at->year == $currentYear
                                            ? $booking->created_at->format('H:i d/m')
                                            : $booking->created_at->format('H:i d/m/Y') }}
                                    </p>
                                </td>

                                <td class="px-5 py-5">
                                    <div class="min-w-36">
                                        <p class="font-semibold text-slate-900 dark:text-slate-100">
                                            {{ $booking->customer_name }}
                                        </p>

                                        <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
                                            {{ $booking->customer_phone }}
                                        </p>
                                    </div>
                                </td>

                                <td class="px-5 py-5">
                                    <div class="max-w-52">
                                        <p class="truncate font-semibold text-slate-900 dark:text-slate-100">
                                            {{ $booking->room?->name ?? 'Phòng không tồn tại' }}
                                        </p>

                                        <p class="mt-1 truncate text-xs text-slate-500 dark:text-slate-400">
                                            {{ $booking->room?->homestay?->name ?? 'Homestay không xác định' }}
                                        </p>
                                    </div>
                                </td>

                                <td class="whitespace-nowrap px-5 py-5">
                                    <p class="text-sm font-semibold text-slate-700 dark:text-slate-300">
                                        {{ $booking->check_in->year == $currentYear
                                            ? $booking->check_in->format('d/m')
                                            : $booking->check_in->format('d/m/Y') }}

                                        <span class="mx-1 text-slate-400 dark:text-slate-500">→</span>

                                        {{ $booking->check_out->year == $currentYear
                                            ? $booking->check_out->format('d/m')
                                            : $booking->check_out->format('d/m/Y') }}
                                    </p>

                                    <p class="mt-1 text-xs text-slate-400 dark:text-slate-500">
                                        {{ $booking->number_of_nights }} đêm ·
                                        {{ $booking->number_of_guests }} khách
                                    </p>
                                </td>

                                <td class="whitespace-nowrap px-5 py-5">
                                    <p class="font-bold text-slate-900 dark:text-slate-100">
                                        {{ number_format($booking->total_price, 0, ',', '.') }}đ
                                    </p>

                                    <p class="mt-1 text-xs font-semibold {{ $paymentStatus[$booking->payment_status] ?? 'text-slate-500 dark:text-slate-400' }}">
                                        {{ $paymentLabels[$booking->payment_status] ?? 'Không xác định' }}
                                    </p>
                                </td>

                                <td class="whitespace-nowrap px-5 py-5">
                                    @switch($booking->status)
                                        @case('pending')
                                            <span class="inline-flex items-center gap-2 rounded-full border border-amber-200 bg-amber-50 px-3 py-1 text-xs font-semibold text-amber-700 dark:border-amber-800 dark:bg-amber-950/40 dark:text-amber-300">
                                                <span class="h-2 w-2 rounded-full bg-amber-500"></span>
                                                Chờ xác nhận
                                            </span>
                                            @break

                                        @case('confirmed')
                                            <span class="inline-flex items-center gap-2 rounded-full border border-blue-200 bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700 dark:border-blue-800 dark:bg-blue-950/40 dark:text-blue-300">
                                                <span class="h-2 w-2 rounded-full bg-blue-500"></span>
                                                Đã xác nhận
                                            </span>
                                            @break

                                        @case('checked_in')
                                            <span class="inline-flex items-center gap-2 rounded-full border border-violet-200 bg-violet-50 px-3 py-1 text-xs font-semibold text-violet-700 dark:border-violet-800 dark:bg-violet-950/40 dark:text-violet-300">
                                                <span class="h-2 w-2 rounded-full bg-violet-500"></span>
                                                Đã nhận phòng
                                            </span>
                                            @break

                                        @case('completed')
                                            <span class="inline-flex items-center gap-2 rounded-full border border-emerald-200 bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700 dark:border-emerald-800 dark:bg-emerald-950/40 dark:text-emerald-300">
                                                <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                                                Đã hoàn thành
                                            </span>
                                            @break

                                        @case('cancelled')
                                            <span class="inline-flex items-center gap-2 rounded-full border border-red-200 bg-red-50 px-3 py-1 text-xs font-semibold text-red-700 dark:border-red-800 dark:bg-red-950/40 dark:text-red-300">
                                                <span class="h-2 w-2 rounded-full bg-red-500"></span>
                                                Đã hủy
                                            </span>
                                            @break

                                        @default
                                            <span class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-slate-50 px-3 py-1 text-xs font-semibold text-slate-700 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-300">
                                                <span class="h-2 w-2 rounded-full bg-slate-500"></span>
                                                Không xác định
                                            </span>
                                    @endswitch
                                </td>

                                <td class="whitespace-nowrap px-5 py-5 text-center">
                                    <details data-action-menu class="group relative inline-block text-left">

                                        {{-- Nút mở menu --}}
                                        <summary
                                            title="Thao tác"
                                            class="flex h-9 w-9 cursor-pointer list-none items-center justify-center rounded-lg
                                                border border-slate-200 bg-white text-lg font-bold text-slate-600
                                                transition
                                                hover:border-blue-300 hover:bg-blue-50 hover:text-blue-600
                                                dark:border-slate-600 dark:bg-slate-800 dark:text-slate-300
                                                dark:hover:border-blue-500 dark:hover:bg-blue-950/40 dark:hover:text-blue-400">
                                            ⋮
                                        </summary>

                                        {{-- Menu thao tác --}}
                                        <div
                                            class="absolute right-0 z-50 mt-2 w-48 overflow-hidden rounded-xl
                                                border border-slate-200 bg-white text-left shadow-xl
                                                dark:border-slate-700 dark:bg-slate-800">

                                            {{-- Xem chi tiết --}}
                                            <a href="{{ route('admin.bookings.show', $booking) }}"
                                                class="flex h-11 w-full items-center gap-3 bg-transparent px-4
                                                    text-sm font-medium text-slate-700 transition
                                                    hover:bg-slate-50 hover:text-blue-600
                                                    dark:text-slate-300 dark:hover:bg-slate-700 dark:hover:text-blue-400">

                                                <svg viewBox="0 0 24 24"
                                                    class="h-4 w-4 shrink-0"
                                                    fill="none"
                                                    stroke="currentColor"
                                                    stroke-width="2"
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round">

                                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                                                    <circle cx="12" cy="12" r="3" />
                                                </svg>

                                                Xem
                                            </a>


                                            {{-- Booking đang chờ xác nhận --}}
                                            @if ($booking->status === 'pending')

                                                {{-- Xác nhận Booking --}}
                                                <form method="POST"
                                                    action="{{ route('admin.bookings.update-status', $booking) }}"
                                                    onsubmit="return confirm('Bạn có chắc muốn xác nhận đơn {{ $booking->booking_code }} không?')">

                                                    @csrf
                                                    @method('PATCH')

                                                    <input type="hidden" name="status" value="confirmed">

                                                    <button type="submit"
                                                        class="flex h-11 w-full cursor-pointer items-center gap-3 bg-transparent px-4
                                                            text-left text-sm font-medium text-slate-700 transition
                                                            hover:bg-slate-50 hover:text-blue-600
                                                            dark:text-slate-300 dark:hover:bg-slate-700 dark:hover:text-blue-400">

                                                        <svg viewBox="0 0 24 24"
                                                            class="h-4 w-4 shrink-0"
                                                            fill="none"
                                                            stroke="currentColor"
                                                            stroke-width="2"
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round">

                                                            <path d="M20 6L9 17l-5-5" />
                                                        </svg>

                                                        Xác nhận đơn
                                                    </button>
                                                </form>


                                                {{-- Hủy Booking --}}
                                                <form method="POST"
                                                    action="{{ route('admin.bookings.update-status', $booking) }}"
                                                    onsubmit="return confirm('Bạn có chắc muốn hủy đơn {{ $booking->booking_code }} không?')">

                                                    @csrf
                                                    @method('PATCH')

                                                    <input type="hidden" name="status" value="cancelled">

                                                    <button type="submit"
                                                        class="flex h-11 w-full cursor-pointer items-center gap-3 bg-transparent px-4
                                                            text-left text-sm font-medium text-red-600 transition
                                                            hover:bg-red-50 hover:text-red-700
                                                            dark:text-red-400 dark:hover:bg-red-950/40 dark:hover:text-red-300">

                                                        <svg viewBox="0 0 24 24"
                                                            class="h-4 w-4 shrink-0"
                                                            fill="none"
                                                            stroke="currentColor"
                                                            stroke-width="2"
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round">

                                                            <path d="M18 6L6 18M6 6l12 12" />
                                                        </svg>

                                                        Hủy Booking
                                                    </button>
                                                </form>

                                            @endif


                                            {{-- Booking đã xác nhận --}}
                                            @if ($booking->status === 'confirmed')

                                                {{-- Đã nhận phòng --}}
                                                <form method="POST"
                                                    action="{{ route('admin.bookings.update-status', $booking) }}"
                                                    onsubmit="return confirm('Xác nhận khách đã nhận phòng?')">

                                                    @csrf
                                                    @method('PATCH')

                                                    <input type="hidden" name="status" value="checked_in">

                                                    <button type="submit"
                                                        class="flex h-11 w-full cursor-pointer items-center gap-3 bg-transparent px-4
                                                            text-left text-sm font-medium text-slate-700 transition
                                                            hover:bg-slate-50 hover:text-blue-600
                                                            dark:text-slate-300 dark:hover:bg-slate-700 dark:hover:text-blue-400">

                                                        <svg viewBox="0 0 24 24"
                                                            class="h-4 w-4 shrink-0"
                                                            fill="none"
                                                            stroke="currentColor"
                                                            stroke-width="2"
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round">

                                                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" />
                                                            <circle cx="12" cy="10" r="3" />
                                                        </svg>

                                                        Đã nhận phòng
                                                    </button>
                                                </form>


                                                {{-- Hủy Booking --}}
                                                <form method="POST"
                                                    action="{{ route('admin.bookings.update-status', $booking) }}"
                                                    onsubmit="return confirm('Bạn có chắc muốn hủy đơn {{ $booking->booking_code }} không?')">

                                                    @csrf
                                                    @method('PATCH')

                                                    <input type="hidden" name="status" value="cancelled">

                                                    <button type="submit"
                                                        class="flex h-11 w-full cursor-pointer items-center gap-3 bg-transparent px-4
                                                            text-left text-sm font-medium text-red-600 transition
                                                            hover:bg-red-50 hover:text-red-700
                                                            dark:text-red-400 dark:hover:bg-red-950/40 dark:hover:text-red-300">

                                                        <svg viewBox="0 0 24 24"
                                                            class="h-4 w-4 shrink-0"
                                                            fill="none"
                                                            stroke="currentColor"
                                                            stroke-width="2"
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round">

                                                            <path d="M18 6L6 18M6 6l12 12" />
                                                        </svg>

                                                        Hủy Booking
                                                    </button>
                                                </form>

                                            @endif


                                            {{-- Booking đã nhận phòng --}}
                                            @if ($booking->status === 'checked_in')

                                                {{-- Hoàn thành Booking --}}
                                                <form method="POST"
                                                    action="{{ route('admin.bookings.update-status', $booking) }}"
                                                    onsubmit="return confirm('Xác nhận đơn này đã hoàn thành?')">

                                                    @csrf
                                                    @method('PATCH')

                                                    <input type="hidden" name="status" value="completed">

                                                    <button type="submit"
                                                        class="flex h-11 w-full cursor-pointer items-center gap-3 bg-transparent px-4
                                                            text-left text-sm font-medium text-emerald-700 transition
                                                            hover:bg-emerald-50 hover:text-emerald-800
                                                            dark:text-emerald-400 dark:hover:bg-emerald-950/40 dark:hover:text-emerald-300">

                                                        <svg viewBox="0 0 24 24"
                                                            class="h-4 w-4 shrink-0"
                                                            fill="none"
                                                            stroke="currentColor"
                                                            stroke-width="2"
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round">

                                                            <path d="M20 6L9 17l-5-5" />
                                                        </svg>

                                                        Hoàn thành
                                                    </button>
                                                </form>

                                            @endif

                                        </div>
                                    </details>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-14 text-center">
                                    <div
                                        class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-slate-100 text-3xl dark:bg-slate-900">
                                        📅
                                    </div>

                                    <h2 class="mt-4 text-lg font-bold text-slate-900 dark:text-slate-100">
                                        Chưa có đơn đặt phòng
                                    </h2>

                                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                                        Chưa tìm thấy Booking phù hợp trong hệ thống.
                                    </p>

                                    @if (request()->hasAny(['search', 'status', 'payment_status', 'sort']))
                                        <a href="{{ route('admin.bookings.index') }}"
                                            class="mt-5 inline-flex h-11 items-center justify-center rounded-xl bg-blue-600 px-5 text-sm font-semibold text-white transition hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600">
                                            Xóa bộ lọc
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($bookings->hasPages())
                <div class="border-t border-slate-200 px-6 py-5 dark:border-slate-700">
                    {{ $bookings->onEachSide(1)->links('components.pagination', [
                        'layout' => 'row',
                        'showInfo' => true,
                    ]) }}
                </div>
            @endif
        </section>
    </div>
@endsection