<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Trang quản trị | HomeStay</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-slate-100">

    @include('partials.navbar')

    <main class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">

        <div class="mb-8 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">

            <div>

                <h1 class="mt-1 text-3xl font-bold tracking-tight text-slate-900">
                    Xin chào, {{ auth()->user()->name }} 👋
                </h1>

                <p class="mt-2 text-slate-500">
                    Chào mừng quay trở lại hệ thống quản lý Homestay.
                    Dưới đây là tổng quan hoạt động hiện tại.
                </p>

            </div>

            <div class="flex items-center gap-3">

                <div class="rounded-2xl border border-slate-200 bg-white px-5 py-3 shadow-sm">

                    <p class="text-xs text-slate-500">
                        Hôm nay
                    </p>

                    <p class="mt-1 font-semibold text-slate-900">
                        {{ now()->format('d/m/Y') }}
                    </p>

                </div>

            </div>

        </div>

        {{-- Thống kê tổng quan --}}
        <div class="grid gap-5 sm:grid-cols-2 xl:grid-cols-4">

            {{-- Người dùng --}}
            <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

                <div class="p-5 sm:p-6">

                    <div class="flex items-start justify-between gap-4">

                        <div>
                            <p class="text-sm font-medium text-slate-500">
                                Người dùng
                            </p>

                            <p class="mt-3 text-3xl font-bold tracking-tight text-slate-900">
                                {{ number_format($totalUsers) }}
                            </p>
                        </div>

                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                            <svg
                                class="h-6 w-6"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="1.8"
                                    d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2M9 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8ZM22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"
                                />
                            </svg>
                        </div>

                    </div>

                    <div class="mt-4 flex items-center justify-between gap-3 text-sm">

                        <span class="text-slate-500">
                            {{ number_format($activeUsers) }} đang hoạt động
                        </span>

                        <span class="font-semibold text-blue-600">
                            +{{ number_format($newUsersThisMonth) }} tháng này
                        </span>

                    </div>

                </div>

                <div class="h-1 bg-blue-500"></div>

            </section>

            {{-- Homestay --}}
            <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

                <div class="p-5 sm:p-6">

                    <div class="flex items-start justify-between gap-4">

                        <div>
                            <p class="text-sm font-medium text-slate-500">
                                Homestay
                            </p>

                            <p class="mt-3 text-3xl font-bold tracking-tight text-slate-900">
                                {{ number_format($totalHomestays) }}
                            </p>
                        </div>

                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">
                            <svg
                                class="h-6 w-6"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="1.8"
                                    d="M3 11.5 12 4l9 7.5M5 10v10h14V10M9 20v-6h6v6"
                                />
                            </svg>
                        </div>

                    </div>

                    <div class="mt-4 flex items-center justify-between gap-3 text-sm">

                        <span class="text-slate-500">
                            {{ number_format($activeHomestays) }} đang hoạt động
                        </span>

                        <span class="font-semibold text-emerald-600">
                            +{{ number_format($newHomestaysThisMonth) }} tháng này
                        </span>

                    </div>

                </div>

                <div class="h-1 bg-emerald-500"></div>

            </section>

            {{-- Đơn đặt phòng --}}
            <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

                <div class="p-5 sm:p-6">

                    <div class="flex items-start justify-between gap-4">

                        <div>
                            <p class="text-sm font-medium text-slate-500">
                                Đơn đặt phòng
                            </p>

                            <p class="mt-3 text-3xl font-bold tracking-tight text-slate-900">
                                {{ number_format($totalBookings) }}
                            </p>
                        </div>

                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-amber-50 text-amber-600">
                            <svg
                                class="h-6 w-6"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="1.8"
                                    d="M8 2v4M16 2v4M3 10h18M5 4h14a2 2 0 0 1 2 2v14H3V6a2 2 0 0 1 2-2Z"
                                />
                            </svg>
                        </div>

                    </div>

                    <div class="mt-4 flex items-center justify-between gap-3 text-sm">

                        <span class="text-slate-500">
                            {{ number_format($pendingBookings) }} đang chờ
                        </span>

                        <span class="font-semibold text-amber-600">
                            +{{ number_format($newBookingsThisMonth) }} tháng này
                        </span>

                    </div>

                </div>

                <div class="h-1 bg-amber-500"></div>

            </section>

            {{-- Đánh giá --}}
            <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

                <div class="p-5 sm:p-6">

                    <div class="flex items-start justify-between gap-4">

                        <div>
                            <p class="text-sm font-medium text-slate-500">
                                Đánh giá
                            </p>

                            <p class="mt-3 text-3xl font-bold tracking-tight text-slate-900">
                                {{ number_format($totalReviews) }}
                            </p>
                        </div>

                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-violet-50 text-violet-600">
                            <svg
                                class="h-6 w-6"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="1.8"
                                    d="m12 3 2.78 5.63 6.22.9-4.5 4.39 1.06 6.2L12 17.2l-5.56 2.92 1.06-6.2L3 9.53l6.22-.9L12 3Z"
                                />
                            </svg>
                        </div>

                    </div>

                    <div class="mt-4 flex items-center justify-between gap-3 text-sm">

                        <span class="text-slate-500">
                            {{ number_format($averageRating, 1) }}/5 điểm trung bình
                        </span>

                        <span class="font-semibold text-violet-600">
                            {{ number_format($pendingReviews) }} chờ duyệt
                        </span>

                    </div>

                </div>

                <div class="h-1 bg-violet-500"></div>

            </section>

        </div>

        {{-- Khu vực biểu đồ --}}
        <div class="mt-8 grid gap-6 xl:grid-cols-3">

            {{-- Biểu đồ Booking và doanh thu --}}
            <section class="rounded-3xl border border-slate-200 bg-white shadow-sm xl:col-span-2">

                <div class="flex flex-col gap-4 border-b border-slate-100 px-6 py-5 sm:flex-row sm:items-center sm:justify-between">

                    <div>
                        <h2 class="text-lg font-bold text-slate-900">
                            Hoạt động đặt phòng
                        </h2>

                        <p class="mt-1 text-sm text-slate-500">
                            Số lượng đơn đặt phòng và doanh thu trong 7 ngày gần nhất.
                        </p>
                    </div>

                    <div class="flex flex-wrap items-center gap-4 text-sm">

                        <div class="flex items-center gap-2">
                            <span class="h-2.5 w-2.5 rounded-full bg-blue-500"></span>
                            <span class="text-slate-600">
                                Đơn đặt phòng
                            </span>
                        </div>

                        <div class="flex items-center gap-2">
                            <span class="h-2.5 w-2.5 rounded-full bg-emerald-500"></span>
                            <span class="text-slate-600">
                                Doanh thu
                            </span>
                        </div>

                    </div>

                </div>

                <div class="p-5 sm:p-6">

                    <div class="mb-6 grid gap-4 sm:grid-cols-2">

                        <div class="rounded-2xl bg-slate-50 p-4">
                            <p class="text-sm font-medium text-slate-500">
                                Tổng doanh thu hoàn thành
                            </p>

                            <p class="mt-2 text-2xl font-bold text-slate-900">
                                {{ number_format($totalRevenue, 0, ',', '.') }}₫
                            </p>
                        </div>

                        <div class="rounded-2xl bg-slate-50 p-4">
                            <p class="text-sm font-medium text-slate-500">
                                Đơn đặt phòng tháng này
                            </p>

                            <p class="mt-2 text-2xl font-bold text-slate-900">
                                {{ number_format($newBookingsThisMonth) }}
                            </p>
                        </div>

                    </div>

                    <div class="relative h-85">
                        <canvas
                            id="bookingOverviewChart"
                            data-labels='@json($bookingChartLabels)'
                            data-bookings='@json($bookingChartData)'
                            data-revenue='@json($revenueChartData)'
                        ></canvas>
                    </div>

                </div>

            </section>

            {{-- Trạng thái Booking --}}
            <section class="rounded-3xl border border-slate-200 bg-white shadow-sm">

                <div class="border-b border-slate-100 px-6 py-5">
                    <h2 class="text-lg font-bold text-slate-900">
                        Trạng thái đặt phòng
                    </h2>

                    <p class="mt-1 text-sm text-slate-500">
                        Tỷ lệ các trạng thái đơn hiện tại.
                    </p>
                </div>

                <div class="p-5 sm:p-6">

                    <div class="relative mx-auto h-[230px] max-w-[230px]">
                        <canvas
                            id="bookingStatusChart"
                            data-statuses='@json(array_values($bookingStatusCounts))'
                        ></canvas>
                    </div>

                    <div class="mt-6 space-y-3">

                        <div class="flex items-center justify-between gap-4">
                            <div class="flex items-center gap-2">
                                <span class="h-2.5 w-2.5 rounded-full bg-amber-400"></span>
                                <span class="text-sm text-slate-600">Chờ xác nhận</span>
                            </div>

                            <span class="text-sm font-bold text-slate-900">
                                {{ number_format($bookingStatusCounts['pending']) }}
                            </span>
                        </div>

                        <div class="flex items-center justify-between gap-4">
                            <div class="flex items-center gap-2">
                                <span class="h-2.5 w-2.5 rounded-full bg-blue-500"></span>
                                <span class="text-sm text-slate-600">Đã xác nhận</span>
                            </div>

                            <span class="text-sm font-bold text-slate-900">
                                {{ number_format($bookingStatusCounts['confirmed']) }}
                            </span>
                        </div>

                        <div class="flex items-center justify-between gap-4">
                            <div class="flex items-center gap-2">
                                <span class="h-2.5 w-2.5 rounded-full bg-violet-500"></span>
                                <span class="text-sm text-slate-600">Đang nhận phòng</span>
                            </div>

                            <span class="text-sm font-bold text-slate-900">
                                {{ number_format($bookingStatusCounts['checked_in']) }}
                            </span>
                        </div>

                        <div class="flex items-center justify-between gap-4">
                            <div class="flex items-center gap-2">
                                <span class="h-2.5 w-2.5 rounded-full bg-emerald-500"></span>
                                <span class="text-sm text-slate-600">Hoàn thành</span>
                            </div>

                            <span class="text-sm font-bold text-slate-900">
                                {{ number_format($bookingStatusCounts['completed']) }}
                            </span>
                        </div>

                        <div class="flex items-center justify-between gap-4">
                            <div class="flex items-center gap-2">
                                <span class="h-2.5 w-2.5 rounded-full bg-red-500"></span>
                                <span class="text-sm text-slate-600">Đã hủy</span>
                            </div>

                            <span class="text-sm font-bold text-slate-900">
                                {{ number_format($bookingStatusCounts['cancelled']) }}
                            </span>
                        </div>

                    </div>

                </div>

            </section>

        </div>

        {{-- Dữ liệu gần đây --}}
        <div class="mt-8 grid gap-6 xl:grid-cols-3">

            {{-- Booking mới nhất --}}
            <section class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm xl:col-span-2">

                <div class="flex items-center justify-between border-b border-slate-100 px-6 py-5">
                    <div>
                        <h2 class="text-lg font-bold text-slate-900">
                            Đơn đặt phòng mới nhất
                        </h2>

                        <p class="mt-1 text-sm text-slate-500">
                            5 đơn đặt phòng được tạo gần đây.
                        </p>
                    </div>

                    <a
                        href="{{ route('admin.bookings.index') }}"
                        class="text-sm font-semibold text-blue-600 transition hover:text-blue-700"
                    >
                        Xem tất cả
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full table-fixed divide-y divide-slate-200">

                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                                    Khách hàng
                                </th>

                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                                    Homestay
                                </th>

                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                                    Nhận phòng
                                </th>

                                <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wide text-slate-500">
                                    Tổng tiền
                                </th>

                                <th class="px-6 py-3 text-center text-xs font-semibold uppercase tracking-wide text-slate-500">
                                    Trạng thái
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100 bg-white">

                            @forelse ($latestBookings as $booking)

                                @php
                                    $bookingStatusLabels = [
                                        'pending' => 'Chờ xác nhận',
                                        'confirmed' => 'Đã xác nhận',
                                        'checked_in' => 'Đang nhận phòng',
                                        'completed' => 'Hoàn thành',
                                        'cancelled' => 'Đã hủy',
                                    ];

                                    $bookingStatusStyles = [
                                        'pending' => 'bg-amber-50 text-amber-700',
                                        'confirmed' => 'bg-blue-50 text-blue-700',
                                        'checked_in' => 'bg-violet-50 text-violet-700',
                                        'completed' => 'bg-emerald-50 text-emerald-700',
                                        'cancelled' => 'bg-red-50 text-red-700',
                                    ];

                                    $customerName = $booking->customer_name
                                        ?? $booking->user?->name
                                        ?? 'Không xác định';

                                    $avatarText = mb_strtoupper(
                                        mb_substr(trim($customerName), 0, 1)
                                    );
                                @endphp

                                <tr class="transition hover:bg-slate-50">

                                    <td class="whitespace-nowrap px-6 py-4">
                                        <div class="flex items-center gap-3">

                                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-blue-100 text-sm font-bold text-blue-700">
                                                {{ $avatarText }}
                                            </div>

                                            <div class="min-w-0">
                                                <p class="max-w-[100px] truncate font-semibold text-slate-900">
                                                    {{ $customerName }}
                                                </p>

                                                <p class="max-w-[100px] truncate text-xs text-slate-500">
                                                    {{ $booking->booking_code }}
                                                </p>
                                            </div>

                                        </div>
                                    </td>

                                    <td class="px-6 py-4">
                                        <p class="max-w-[150px] truncate text-sm font-medium text-slate-900">
                                            {{ $booking->room?->homestay?->name ?? 'Không xác định' }}
                                        </p>

                                        <p class="max-w-[150px] truncate text-xs text-slate-500">
                                            {{ $booking->room?->name ?? 'Không xác định' }}
                                        </p>
                                    </td>

                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-600">
                                        {{ $booking->check_in?->format('d/m/Y') ?? 'Không xác định' }}
                                    </td>

                                    <td class="whitespace-nowrap px-6 py-4 text-right text-sm font-bold text-slate-900">
                                        {{ number_format($booking->total_price, 0, ',', '.') }}₫
                                    </td>

                                    <td class="whitespace-nowrap px-6 py-4 text-center">
                                        <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $bookingStatusStyles[$booking->status] ?? 'bg-slate-100 text-slate-600' }}">
                                            {{ $bookingStatusLabels[$booking->status] ?? 'Không xác định' }}
                                        </span>
                                    </td>

                                </tr>

                            @empty

                                <tr>
                                    <td
                                        colspan="5"
                                        class="px-6 py-10 text-center text-sm text-slate-500"
                                    >
                                        Chưa có đơn đặt phòng nào.
                                    </td>
                                </tr>

                            @endforelse

                        </tbody>

                    </table>
                </div>

            </section>

            {{-- Review mới nhất --}}
            <section class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">

                <div class="flex items-center justify-between border-b border-slate-100 px-6 py-5">
                    <div>
                        <h2 class="text-lg font-bold text-slate-900">
                            Đánh giá mới nhất
                        </h2>

                        <p class="mt-1 text-sm text-slate-500">
                            Hoạt động đánh giá gần đây.
                        </p>
                    </div>

                    <a
                        href="{{ route('admin.reviews.index') }}"
                        class="text-sm font-semibold text-blue-600 transition hover:text-blue-700"
                    >
                        Xem tất cả
                    </a>
                </div>

                <div class="divide-y divide-slate-100">

                    @forelse ($latestReviews as $review)

                        @php
                            $reviewUserName = $review->user?->name ?? 'Không xác định';

                            $reviewAvatar = mb_strtoupper(
                                mb_substr(trim($reviewUserName), 0, 1)
                            );

                            $reviewStatusLabels = [
                                'pending' => 'Chờ duyệt',
                                'approved' => 'Đã duyệt',
                                'hidden' => 'Đã ẩn',
                            ];

                            $reviewStatusStyles = [
                                'pending' => 'bg-amber-50 text-amber-700',
                                'approved' => 'bg-emerald-50 text-emerald-700',
                                'hidden' => 'bg-slate-100 text-slate-600',
                            ];
                        @endphp

                        <article class="px-6 py-5 transition hover:bg-slate-50">

                            <div class="flex items-start gap-3">

                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-violet-100 text-sm font-bold text-violet-700">
                                    {{ $reviewAvatar }}
                                </div>

                                <div class="min-w-0 flex-1">

                                    <div class="flex items-start justify-between gap-3">

                                        <div class="min-w-0">
                                            <p class="truncate font-semibold text-slate-900">
                                                {{ $reviewUserName }}
                                            </p>

                                            <p class="truncate text-xs text-slate-500">
                                                {{ $review->homestay?->name ?? 'Homestay không xác định' }}
                                            </p>
                                        </div>

                                        <span class="shrink-0 rounded-full px-2.5 py-1 text-[11px] font-semibold {{ $reviewStatusStyles[$review->status] ?? 'bg-slate-100 text-slate-600' }}">
                                            {{ $reviewStatusLabels[$review->status] ?? 'Không xác định' }}
                                        </span>

                                    </div>

                                    <div class="mt-2 flex items-center gap-1 text-amber-400">
                                        @for ($star = 1; $star <= 5; $star++)
                                            <span>{{ $star <= $review->rating ? '★' : '☆' }}</span>
                                        @endfor
                                    </div>

                                    <p class="mt-2 line-clamp-2 text-sm text-slate-600">
                                        {{ $review->content ?: $review->title ?: 'Không có nội dung đánh giá.' }}
                                    </p>

                                    <p class="mt-2 text-xs text-slate-400">
                                        {{ $review->created_at->diffForHumans() }}
                                    </p>

                                </div>

                            </div>

                        </article>

                    @empty

                        <div class="px-6 py-10 text-center text-sm text-slate-500">
                            Chưa có đánh giá nào.
                        </div>

                    @endforelse

                </div>

            </section>

        </div>

        <div class="mt-8 rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">

            <h2 class="text-xl font-bold text-slate-900">
                Chức năng quản trị
            </h2>

            <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">

                <a
                    href="{{ route('admin.categories.index') }}"
                    class="rounded-2xl border border-slate-200 p-5 transition hover:border-blue-300 hover:bg-blue-50"
                >
                    <h3 class="font-bold text-slate-900">
                        Quản lý danh mục
                    </h3>

                    <p class="mt-2 text-sm text-slate-500">
                        Thêm, sửa và xóa danh mục Homestay.
                    </p>
                </a>

                <a
                    href="{{ route('admin.homestays.index') }}"
                    class="rounded-2xl border border-slate-200 p-5 transition hover:border-blue-300 hover:bg-blue-50"
                >
                    <h3 class="font-bold text-slate-900">
                        Quản lý Homestay
                    </h3>

                    <p class="mt-2 text-sm text-slate-500">
                        Quản lý thông tin và trạng thái Homestay.
                    </p>
                </a>

                <a
                    href="#"
                    class="rounded-2xl border border-slate-200 p-5 transition hover:border-blue-300 hover:bg-blue-50"
                >
                    <h3 class="font-bold text-slate-900">
                        Quản lý đặt phòng
                    </h3>

                    <p class="mt-2 text-sm text-slate-500">
                        Theo dõi và xác nhận các đơn đặt phòng.
                    </p>
                </a>

            </div>

        </div>

    </main>

</body>

</html>