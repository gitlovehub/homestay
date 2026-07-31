@extends('layouts.admin')

@section('title', 'Tổng quan quản trị | HomeStayGo')

@section('page-title', 'Tổng quan hệ thống')

@section('content')
    <div class="mx-auto max-w-screen-2xl space-y-8">

        {{-- Header --}}
        <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <div class="mb-1 flex items-center gap-2">
                    <span class="inline-flex h-2 w-2 rounded-full bg-emerald-500"></span>
                    <span class="text-sm font-medium text-slate-500">Hệ thống đang hoạt động</span>
                </div>
                <h1 class="text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl">
                    Xin chào, {{ auth()->user()?->name ?? 'Quản trị viên' }} 👋
                </h1>
                <p class="mt-2 max-w-2xl text-slate-500">
                    Chào mừng quay trở lại hệ thống quản lý <span class="font-medium text-slate-700">HomeStayGo</span>.
                    Dưới đây là tổng quan hoạt động hiện tại của bạn.
                </p>
            </div>

            <div class="flex items-center gap-4 rounded-2xl border border-slate-200/80 bg-white px-5 py-3.5 shadow-sm">
                <div
                    class="flex h-11 w-11 items-center justify-center rounded-xl bg-linear-to-br from-blue-500 to-blue-600 text-white shadow-md shadow-blue-500/20">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-medium uppercase tracking-wider text-slate-400">Hôm nay</p>
                    <p class="mt-0.5 text-base font-semibold text-slate-900">{{ now()->format('d/m/Y') }}</p>
                </div>
            </div>
        </div>

        {{-- Stats Cards --}}
        <div class="grid gap-5 sm:grid-cols-2 xl:grid-cols-4">

            {{-- Người dùng --}}
            <div
                class="group relative overflow-hidden rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-lg hover:shadow-blue-500/5">
                <div
                    class="absolute -right-6 -top-6 h-24 w-24 rounded-full bg-blue-50 opacity-60 transition group-hover:scale-110">
                </div>
                <div class="relative flex items-start justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-500">Người dùng</p>
                        <p class="mt-2 text-3xl font-bold tracking-tight text-slate-900">
                            {{ number_format($totalUsers ?? 0) }}
                        </p>
                    </div>
                    <div
                        class="flex h-12 w-12 items-center justify-center rounded-xl bg-linear-to-br from-blue-500 to-blue-600 text-white shadow-lg shadow-blue-500/25">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                d="M16 21v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2M9 11a4 4 0 100-8 4 4 0 000 8Zm13 10v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75" />
                        </svg>
                    </div>
                </div>
                <div class="relative mt-5 flex items-center justify-between text-sm">
                    <span class="text-slate-500">{{ number_format($activeUsers ?? 0) }} đang hoạt động</span>
                    <span class="inline-flex items-center gap-1 font-semibold text-blue-600">
                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M5 10l7-7m0 0l7 7m-7-7v18" />
                        </svg>
                        +{{ number_format($newUsersThisMonth ?? 0) }}
                    </span>
                </div>
            </div>

            {{-- Homestay --}}
            <div
                class="group relative overflow-hidden rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-lg hover:shadow-emerald-500/5">
                <div
                    class="absolute -right-6 -top-6 h-24 w-24 rounded-full bg-emerald-50 opacity-60 transition group-hover:scale-110">
                </div>
                <div class="relative flex items-start justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-500">Homestay</p>
                        <p class="mt-2 text-3xl font-bold tracking-tight text-slate-900">
                            {{ number_format($totalHomestays ?? 0) }}
                        </p>
                    </div>
                    <div
                        class="flex h-12 w-12 items-center justify-center rounded-xl bg-linear-to-br from-emerald-500 to-emerald-600 text-white shadow-lg shadow-emerald-500/25">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                    </div>
                </div>
                <div class="relative mt-5 flex items-center justify-between text-sm">
                    <span class="text-slate-500">{{ number_format($activeHomestays ?? 0) }} đang hoạt động</span>
                    <span class="inline-flex items-center gap-1 font-semibold text-emerald-600">
                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M5 10l7-7m0 0l7 7m-7-7v18" />
                        </svg>
                        +{{ number_format($newHomestaysThisMonth ?? 0) }}
                    </span>
                </div>
            </div>

            {{-- Đơn đặt phòng --}}
            <div
                class="group relative overflow-hidden rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-lg hover:shadow-violet-500/5">
                <div
                    class="absolute -right-6 -top-6 h-24 w-24 rounded-full bg-violet-50 opacity-60 transition group-hover:scale-110">
                </div>
                <div class="relative flex items-start justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-500">Đơn đặt phòng</p>
                        <p class="mt-2 text-3xl font-bold tracking-tight text-slate-900">
                            {{ number_format($totalBookings ?? 0) }}
                        </p>
                    </div>
                    <div
                        class="flex h-12 w-12 items-center justify-center rounded-xl bg-linear-to-br from-violet-500 to-violet-600 text-white shadow-lg shadow-violet-500/25">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                </div>
                <div class="relative mt-5 flex items-center justify-between text-sm">
                    <span class="text-slate-500">{{ number_format($pendingBookings ?? 0) }} đang chờ</span>
                    <span class="inline-flex items-center gap-1 font-semibold text-violet-600">
                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M5 10l7-7m0 0l7 7m-7-7v18" />
                        </svg>
                        +{{ number_format($newBookingsThisMonth ?? 0) }}
                    </span>
                </div>
            </div>

            {{-- Đánh giá --}}
            <div
                class="group relative overflow-hidden rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-lg hover:shadow-amber-500/5">
                <div
                    class="absolute -right-6 -top-6 h-24 w-24 rounded-full bg-amber-50 opacity-60 transition group-hover:scale-110">
                </div>
                <div class="relative flex items-start justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-500">Đánh giá</p>
                        <p class="mt-2 text-3xl font-bold tracking-tight text-slate-900">
                            {{ number_format($totalReviews ?? 0) }}
                        </p>
                    </div>
                    <div
                        class="flex h-12 w-12 items-center justify-center rounded-xl bg-linear-to-br from-amber-500 to-amber-600 text-white shadow-lg shadow-amber-500/25">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                        </svg>
                    </div>
                </div>
                <div class="relative mt-5 flex items-center justify-between text-sm">
                    <span class="text-slate-500">{{ number_format($averageRating ?? 0, 1) }}/5 điểm TB</span>
                    <span class="font-semibold text-amber-600">
                        {{ number_format($pendingReviews ?? 0) }} chờ duyệt
                    </span>
                </div>
            </div>
        </div>

        {{-- Charts Row --}}
        <div class="grid gap-6 xl:grid-cols-3">

            {{-- Booking Overview Chart --}}
            <section class="overflow-hidden rounded-3xl border border-slate-200/80 bg-white shadow-sm xl:col-span-2">
                <div
                    class="flex flex-col gap-4 border-b border-slate-100 px-6 py-5 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h2 class="text-lg font-bold text-slate-900">Hoạt động đặt phòng</h2>
                        <p class="mt-1 text-sm text-slate-500">Số lượng đơn & doanh thu 7 ngày gần nhất</p>
                    </div>
                    <div class="flex items-center gap-5 text-sm">
                        <div class="flex items-center gap-2">
                            <span class="h-2.5 w-2.5 rounded-full bg-blue-500"></span>
                            <span class="text-slate-600">Đơn đặt phòng</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="h-2.5 w-2.5 rounded-full bg-emerald-500"></span>
                            <span class="text-slate-600">Doanh thu</span>
                        </div>
                    </div>
                </div>

                <div class="p-6">
                    <div class="mb-6 grid gap-4 sm:grid-cols-2">
                        <div class="rounded-2xl bg-linear-to-br from-slate-50 to-slate-100/80 p-5">
                            <p class="text-sm font-medium text-slate-500">Tổng doanh thu hoàn thành</p>
                            <p class="mt-2 text-2xl font-bold text-slate-900">
                                {{ number_format($totalRevenue ?? 0, 0, ',', '.') }}₫
                            </p>
                        </div>
                        <div class="rounded-2xl bg-linear-to-br from-slate-50 to-slate-100/80 p-5">
                            <p class="text-sm font-medium text-slate-500">Đơn đặt phòng tháng này</p>
                            <p class="mt-2 text-2xl font-bold text-slate-900">
                                {{ number_format($newBookingsThisMonth ?? 0) }}
                            </p>
                        </div>
                    </div>

                    <div class="relative h-[320px]">
                        <canvas id="bookingOverviewChart" data-labels='@json($bookingChartLabels ?? [])'
                            data-bookings='@json($bookingChartData ?? [])'
                            data-revenue='@json($revenueChartData ?? [])'></canvas>
                    </div>
                </div>
            </section>

            {{-- Booking Status --}}
            <section class="overflow-hidden rounded-3xl border border-slate-200/80 bg-white shadow-sm">
                <div class="border-b border-slate-100 px-6 py-5">
                    <h2 class="text-lg font-bold text-slate-900">Trạng thái đặt phòng</h2>
                    <p class="mt-1 text-sm text-slate-500">Tỷ lệ các trạng thái hiện tại</p>
                </div>

                <div class="p-6">
                    <div class="relative mx-auto h-[220px] max-w-[220px]">
                        <canvas id="bookingStatusChart" data-statuses='@json(array_values($bookingStatusCounts ?? []))'></canvas>
                    </div>

                    <div class="mt-6 space-y-3.5">
                        @php
                            $statusItems = [
                                ['key' => 'pending', 'label' => 'Chờ xác nhận', 'color' => 'bg-amber-400'],
                                ['key' => 'confirmed', 'label' => 'Đã xác nhận', 'color' => 'bg-blue-500'],
                                ['key' => 'checked_in', 'label' => 'Đang nhận phòng', 'color' => 'bg-violet-500'],
                                ['key' => 'completed', 'label' => 'Hoàn thành', 'color' => 'bg-emerald-500'],
                                ['key' => 'cancelled', 'label' => 'Đã hủy', 'color' => 'bg-red-500'],
                            ];
                        @endphp

                        @foreach ($statusItems as $item)
                            <div class="flex items-center justify-between gap-3">
                                <div class="flex items-center gap-2.5">
                                    <span class="h-2.5 w-2.5 rounded-full {{ $item['color'] }}"></span>
                                    <span class="text-sm text-slate-600">{{ $item['label'] }}</span>
                                </div>
                                <span class="text-sm font-bold text-slate-900">
                                    {{ number_format($bookingStatusCounts[$item['key']] ?? 0) }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>
        </div>

        {{-- Latest Data --}}
        <div class="grid gap-6 xl:grid-cols-3">

            {{-- Latest Bookings --}}
            <section class="overflow-hidden rounded-3xl border border-slate-200/80 bg-white shadow-sm xl:col-span-2">
                <div class="flex items-center justify-between gap-4 border-b border-slate-100 px-6 py-5">
                    <div>
                        <h2 class="text-lg font-bold text-slate-900">Đơn đặt phòng mới nhất</h2>
                        <p class="mt-1 text-sm text-slate-500">5 đơn được tạo gần đây</p>
                    </div>
                    <a href="{{ route('admin.bookings.index') }}"
                        class="inline-flex items-center gap-1.5 text-sm font-semibold text-blue-600 transition hover:text-blue-700 hover:translate-x-1">
                        Xem tất cả →
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead>
                            <tr class="border-b border-slate-100 bg-slate-50/70">
                                <th
                                    class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                                    Khách hàng</th>
                                <th
                                    class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                                    Homestay</th>
                                <th
                                    class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                                    Nhận phòng</th>
                                <th
                                    class="px-6 py-3.5 text-right text-xs font-semibold uppercase tracking-wider text-slate-500">
                                    Tổng tiền</th>
                                <th
                                    class="px-6 py-3.5 text-center text-xs font-semibold uppercase tracking-wider text-slate-500">
                                    Trạng thái</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse ($latestBookings ?? collect() as $booking)
                                @php
                                    $bookingStatusLabels = [
                                        'pending' => 'Chờ xác nhận',
                                        'confirmed' => 'Đã xác nhận',
                                        'checked_in' => 'Đang nhận phòng',
                                        'completed' => 'Hoàn thành',
                                        'cancelled' => 'Đã hủy',
                                    ];
                                    $bookingStatusStyles = [
                                        'pending' => 'bg-amber-50 text-amber-700 ring-1 ring-amber-200/60',
                                        'confirmed' => 'bg-blue-50 text-blue-700 ring-1 ring-blue-200/60',
                                        'checked_in' => 'bg-violet-50 text-violet-700 ring-1 ring-violet-200/60',
                                        'completed' => 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200/60',
                                        'cancelled' => 'bg-red-50 text-red-700 ring-1 ring-red-200/60',
                                    ];
                                    $customerName =
                                        $booking->customer_name ?? ($booking->user?->name ?? 'Không xác định');
                                    $avatarText = mb_strtoupper(mb_substr(trim($customerName), 0, 1));
                                @endphp

                                <tr class="transition-colors hover:bg-slate-50/80">
                                    <td class="whitespace-nowrap px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-linear-to-br from-blue-500 to-blue-600 text-sm font-bold text-white shadow-sm">
                                                {{ $avatarText }}
                                            </div>
                                            <div class="min-w-0">
                                                <p class="max-w-30 truncate font-semibold text-slate-900">
                                                    {{ $customerName }}</p>
                                                <p class="max-w-30 truncate text-xs text-slate-400">
                                                    {{ $booking->booking_code }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <p class="max-w-45 truncate text-sm font-medium text-slate-900">
                                            {{ $booking->room?->homestay?->name ?? 'Không xác định' }}
                                        </p>
                                        <p class="max-w-45 truncate text-xs text-slate-400">
                                            {{ $booking->room?->name ?? 'Không xác định' }}
                                        </p>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-600">
                                        {{ $booking->check_in?->format('d/m/Y') ?? '—' }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-right text-sm font-bold text-slate-900">
                                        {{ number_format($booking->total_price ?? 0, 0, ',', '.') }}₫
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-center">
                                        <span
                                            class="inline-flex rounded-full px-3 py-1 text-xs font-semibold
                                            {{ $bookingStatusStyles[$booking->status] ?? 'bg-slate-100 text-slate-600' }}">
                                            {{ $bookingStatusLabels[$booking->status] ?? 'Không xác định' }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-16 text-center">
                                        <div
                                            class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-slate-100 text-slate-400">
                                            <svg class="h-7 w-7" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                        <p class="mt-3 text-sm text-slate-500">Chưa có đơn đặt phòng nào.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>

            {{-- Latest Reviews --}}
            <section class="overflow-hidden rounded-3xl border border-slate-200/80 bg-white shadow-sm">
                <div class="flex items-center justify-between gap-4 border-b border-slate-100 px-6 py-5">
                    <div>
                        <h2 class="text-lg font-bold text-slate-900">Đánh giá mới nhất</h2>
                        <p class="mt-1 text-sm text-slate-500">Hoạt động gần đây</p>
                    </div>
                    <a href="{{ route('admin.reviews.index') }}"
                        class="inline-flex items-center gap-1.5 text-sm font-semibold text-blue-600 transition hover:text-blue-700 hover:translate-x-1">
                        Xem tất cả →
                    </a>
                </div>

                <div class="divide-y divide-slate-100">
                    @forelse ($latestReviews ?? collect() as $review)
                        @php
                            $reviewUserName = $review->user?->name ?? 'Không xác định';
                            $reviewAvatar = mb_strtoupper(mb_substr(trim($reviewUserName), 0, 1));
                            $reviewStatusLabels = [
                                'pending' => 'Chờ duyệt',
                                'approved' => 'Đã duyệt',
                                'hidden' => 'Đã ẩn',
                            ];
                            $reviewStatusStyles = [
                                'pending' => 'bg-amber-50 text-amber-700 ring-1 ring-amber-200/50',
                                'approved' => 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200/50',
                                'hidden' => 'bg-slate-100 text-slate-600 ring-1 ring-slate-200/50',
                            ];
                        @endphp

                        <article class="px-6 py-5 transition-colors hover:bg-slate-50/60">
                            <div class="flex items-start gap-3.5">
                                <div
                                    class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-linear-to-br from-violet-500 to-violet-600 text-sm font-bold text-white shadow-sm">
                                    {{ $reviewAvatar }}
                                </div>

                                <div class="min-w-0 flex-1">
                                    <div class="flex items-start justify-between gap-3">
                                        <div class="min-w-0">
                                            <p class="truncate font-semibold text-slate-900">{{ $reviewUserName }}</p>
                                            <p class="truncate text-xs text-slate-400">
                                                {{ $review->homestay?->name ?? 'Homestay không xác định' }}
                                            </p>
                                        </div>
                                        <span
                                            class="shrink-0 rounded-full px-2.5 py-1 text-[11px] font-semibold
                                            {{ $reviewStatusStyles[$review->status] ?? 'bg-slate-100 text-slate-600' }}">
                                            {{ $reviewStatusLabels[$review->status] ?? 'Không xác định' }}
                                        </span>
                                    </div>

                                    <div class="mt-2 flex items-center gap-0.5 text-amber-400">
                                        @for ($star = 1; $star <= 5; $star++)
                                            <span class="text-sm">
                                                @if ($star <= (int) $review->rating)
                                                    <x-icon-star class="h-4 w-4 text-amber-400" />
                                                @else
                                                    <x-icon-star class="h-4 w-4 text-slate-400" />
                                                @endif
                                            </span>
                                        @endfor
                                    </div>

                                    <p class="mt-2 line-clamp-2 text-sm leading-relaxed text-slate-600">
                                        {{ $review->content ?: $review->title ?: 'Không có nội dung đánh giá.' }}
                                    </p>

                                    <p class="mt-2.5 text-xs text-slate-400">
                                        {{ $review->created_at?->diffForHumans() ?? 'Không xác định' }}
                                    </p>
                                </div>
                            </div>
                        </article>
                    @empty
                        <div class="px-6 py-16 text-center">
                            <div
                                class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-slate-100 text-slate-400">
                                <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                                </svg>
                            </div>
                            <p class="mt-3 text-sm text-slate-500">Chưa có đánh giá nào.</p>
                        </div>
                    @endforelse
                </div>
            </section>
        </div>
    </div>
@endsection
