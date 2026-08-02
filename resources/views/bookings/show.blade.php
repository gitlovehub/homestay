<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Chi tiết đơn {{ $booking->booking_code }} | HomeStayGo
    </title>

    @vite([
        'resources/css/app.css',
        'resources/js/app.js',
    ])
</head>

<body class="min-h-screen bg-slate-50">

    @include('partials.navbar')

    @php
        /*
        |--------------------------------------------------------------------------
        | Trạng thái booking
        |--------------------------------------------------------------------------
        */

        $statusLabels = [
            'pending' => 'Chờ xác nhận',
            'confirmed' => 'Đã xác nhận',
            'checked_in' => 'Đã nhận phòng',
            'completed' => 'Đã hoàn thành',
            'cancelled' => 'Đã hủy',
        ];

        $statusClasses = [
            'pending' => 'border border-amber-200 bg-amber-50 text-amber-700',
            'confirmed' => 'border border-blue-200 bg-blue-50 text-blue-700',
            'checked_in' => 'border border-violet-200 bg-violet-50 text-violet-700',
            'completed' => 'border border-emerald-200 bg-emerald-50 text-emerald-700',
            'cancelled' => 'border border-red-200 bg-red-50 text-red-700',
        ];

        /*
        |--------------------------------------------------------------------------
        | Trạng thái thanh toán
        |--------------------------------------------------------------------------
        */

        $paymentStatus = $booking->payment_status ?? 'unpaid';

        $paymentLabels = [
            'unpaid' => 'Chưa thanh toán',
            'pending' => 'Đang xử lý',
            'paid' => 'Đã thanh toán',
            'refunded' => 'Đã hoàn tiền',
            'failed' => 'Thanh toán thất bại',
        ];

        $paymentClasses = [
            'unpaid' => 'border-amber-200 bg-amber-50 text-amber-700',
            'pending' => 'border-blue-200 bg-blue-50 text-blue-700',
            'paid' => 'border-emerald-200 bg-emerald-50 text-emerald-700',
            'refunded' => 'border-violet-200 bg-violet-50 text-violet-700',
            'failed' => 'border-red-200 bg-red-50 text-red-700',
        ];

        /*
        |--------------------------------------------------------------------------
        | Kiểm tra có được thanh toán hay không
        |--------------------------------------------------------------------------
        */

        $canPay = in_array(
            $paymentStatus,
            [
                'unpaid',
                'pending',
                'failed',
            ],
            true
        ) && in_array(
            $booking->status,
            [
                'pending',
                'confirmed',
            ],
            true
        );

        $paymentButtonLabel = match ($paymentStatus) {
            'pending' => 'Tiếp tục thanh toán',
            'failed' => 'Thử thanh toán lại',
            default => 'Thanh toán ngay',
        };
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
                    'label' => 'Chi tiết đơn',
                ],
            ]"
        />

        <section class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">

            {{-- Thông báo thành công --}}
            @if (session('success'))
                <div
                    class="mb-8 flex items-start gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm text-emerald-700"
                >
                    <svg
                        class="mt-0.5 h-5 w-5 shrink-0"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2.5"
                            d="m5 13 4 4L19 7"
                        />
                    </svg>

                    <p class="font-medium">
                        {{ session('success') }}
                    </p>
                </div>
            @endif

            {{-- Thông báo lỗi --}}
            @if (session('error'))
                <div
                    class="mb-8 flex items-start gap-3 rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-sm text-red-700"
                >
                    <svg
                        class="mt-0.5 h-5 w-5 shrink-0"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M12 9v4m0 4h.01M10.3 3.7 2.6 17a2 2 0 0 0 1.7 3h15.4a2 2 0 0 0 1.7-3L13.7 3.7a2 2 0 0 0-3.4 0Z"
                        />
                    </svg>

                    <p class="font-medium">
                        {{ session('error') }}
                    </p>
                </div>
            @endif

            {{-- Tiêu đề --}}
            <div
                class="mb-8 flex flex-col gap-5 sm:flex-row sm:items-start sm:justify-between"
            >

                <div>
                    <p
                        class="font-semibold uppercase tracking-widest text-blue-600"
                    >
                        Chi tiết đặt phòng
                    </p>

                    <h1
                        class="mt-2 text-3xl font-bold tracking-tight text-slate-950 sm:text-4xl"
                    >
                        {{ $booking->booking_code }}
                    </h1>

                    <p class="mt-3 text-slate-500">
                        Được tạo lúc
                        {{ $booking->created_at->format('H:i d/m/Y') }}
                    </p>
                </div>

                <div class="flex flex-wrap items-center gap-2">

                    <span
                        class="inline-flex w-fit rounded-full px-5 py-2 text-sm font-semibold
                        {{ $statusClasses[$booking->status] ?? 'border border-slate-200 bg-slate-100 text-slate-700' }}"
                    >
                        {{ $statusLabels[$booking->status] ?? $booking->status }}
                    </span>

                    <span
                        class="inline-flex w-fit rounded-full border px-5 py-2 text-sm font-semibold
                        {{ $paymentClasses[$paymentStatus] ?? 'border-slate-200 bg-slate-100 text-slate-700' }}"
                    >
                        {{ $paymentLabels[$paymentStatus] ?? $paymentStatus }}
                    </span>

                </div>

            </div>

            <div
                class="grid gap-8 lg:grid-cols-[minmax(0,1fr)_380px]"
            >

                {{-- Cột trái --}}
                <div class="min-w-0 space-y-8">

                    {{-- Thông tin phòng --}}
                    <div
                        class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm"
                    >

                        <div
                            class="grid md:grid-cols-[250px_minmax(0,1fr)]"
                        >

                            <div class="bg-slate-100">

                                @if ($booking->room?->image)
                                    <img
                                        src="{{ Storage::url($booking->room->image) }}"
                                        alt="{{ $booking->room->name }}"
                                        class="h-64 w-full object-cover md:h-full"
                                    >
                                @else
                                    <div
                                        class="flex h-64 items-center justify-center text-center md:h-full"
                                    >

                                        <div>
                                            <div class="text-6xl">
                                                🚪
                                            </div>

                                            <p
                                                class="mt-3 text-sm font-medium text-slate-400"
                                            >
                                                Chưa có ảnh phòng
                                            </p>
                                        </div>

                                    </div>
                                @endif

                            </div>

                            <div class="p-6 sm:p-8">

                                <span
                                    class="inline-flex rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-600"
                                >
                                    {{ $booking->room?->room_type ?? 'Phòng Homestay' }}
                                </span>

                                <h2
                                    class="mt-4 text-2xl font-bold text-slate-900"
                                >
                                    {{ $booking->room?->name ?? 'Không xác định' }}
                                </h2>

                                @if ($booking->room?->homestay)
                                    <a
                                        href="{{ route('homestays.show', $booking->room->homestay->slug) }}"
                                        class="mt-2 inline-block font-medium text-blue-600 hover:text-blue-700"
                                    >
                                        {{ $booking->room->homestay->name }}
                                    </a>
                                @endif

                                <p
                                    class="mt-3 text-sm leading-6 text-slate-500"
                                >
                                    {{ $booking->room?->homestay?->address ?? 'Chưa cập nhật địa chỉ' }}

                                    @if ($booking->room?->homestay?->city)
                                        , {{ $booking->room->homestay->city }}
                                    @endif
                                </p>

                                <div
                                    class="mt-5 grid grid-cols-2 gap-3 sm:grid-cols-3"
                                >

                                    <div class="rounded-xl bg-slate-50 p-3">
                                        <p class="text-xs text-slate-400">
                                            Sức chứa
                                        </p>

                                        <p
                                            class="mt-1 text-sm font-semibold text-slate-700"
                                        >
                                            {{ $booking->room?->capacity ?? 0 }}
                                            khách
                                        </p>
                                    </div>

                                    <div class="rounded-xl bg-slate-50 p-3">
                                        <p class="text-xs text-slate-400">
                                            Số giường
                                        </p>

                                        <p
                                            class="mt-1 text-sm font-semibold text-slate-700"
                                        >
                                            {{ $booking->room?->number_of_beds ?? 0 }}
                                            giường
                                        </p>
                                    </div>

                                    <div class="rounded-xl bg-slate-50 p-3">
                                        <p class="text-xs text-slate-400">
                                            Diện tích
                                        </p>

                                        <p
                                            class="mt-1 text-sm font-semibold text-slate-700"
                                        >
                                            {{ $booking->room?->area ?? 0 }} m²
                                        </p>
                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                    {{-- Thông tin lưu trú --}}
                    <div
                        class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8"
                    >

                        <h2 class="text-2xl font-bold text-slate-900">
                            Thông tin lưu trú
                        </h2>

                        <div class="mt-6 grid gap-4 sm:grid-cols-2">

                            <div class="rounded-2xl bg-slate-50 p-5">
                                <p class="text-sm text-slate-500">
                                    Ngày nhận phòng
                                </p>

                                <p
                                    class="mt-2 text-lg font-bold text-slate-900"
                                >
                                    {{ $booking->check_in?->format('d/m/Y') ?? 'Chưa xác định' }}
                                </p>
                            </div>

                            <div class="rounded-2xl bg-slate-50 p-5">
                                <p class="text-sm text-slate-500">
                                    Ngày trả phòng
                                </p>

                                <p
                                    class="mt-2 text-lg font-bold text-slate-900"
                                >
                                    {{ $booking->check_out?->format('d/m/Y') ?? 'Chưa xác định' }}
                                </p>
                            </div>

                            <div class="rounded-2xl bg-slate-50 p-5">
                                <p class="text-sm text-slate-500">
                                    Số đêm
                                </p>

                                <p
                                    class="mt-2 text-lg font-bold text-slate-900"
                                >
                                    {{ $booking->number_of_nights }} đêm
                                </p>
                            </div>

                            <div class="rounded-2xl bg-slate-50 p-5">
                                <p class="text-sm text-slate-500">
                                    Số khách
                                </p>

                                <p
                                    class="mt-2 text-lg font-bold text-slate-900"
                                >
                                    {{ $booking->number_of_guests }} khách
                                </p>
                            </div>

                        </div>

                    </div>

                    {{-- Thông tin khách --}}
                    <div
                        class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8"
                    >

                        <h2 class="text-2xl font-bold text-slate-900">
                            Thông tin khách hàng
                        </h2>

                        <div class="mt-6 divide-y divide-slate-100">

                            <div
                                class="flex flex-col gap-1 py-4 first:pt-0 sm:flex-row sm:items-center sm:justify-between"
                            >
                                <span class="text-sm text-slate-500">
                                    Họ và tên
                                </span>

                                <span class="font-semibold text-slate-800">
                                    {{ $booking->customer_name }}
                                </span>
                            </div>

                            <div
                                class="flex flex-col gap-1 py-4 sm:flex-row sm:items-center sm:justify-between"
                            >
                                <span class="text-sm text-slate-500">
                                    Email
                                </span>

                                <span class="font-semibold text-slate-800">
                                    {{ $booking->customer_email }}
                                </span>
                            </div>

                            <div
                                class="flex flex-col gap-1 py-4 last:pb-0 sm:flex-row sm:items-center sm:justify-between"
                            >
                                <span class="text-sm text-slate-500">
                                    Số điện thoại
                                </span>

                                <span class="font-semibold text-slate-800">
                                    {{ $booking->customer_phone }}
                                </span>
                            </div>

                        </div>

                    </div>

                    {{-- Ghi chú --}}
                    @if ($booking->note)
                        <div
                            class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8"
                        >

                            <h2 class="text-2xl font-bold text-slate-900">
                                Ghi chú
                            </h2>

                            <p
                                class="mt-5 whitespace-pre-line leading-7 text-slate-600"
                            >
                                {{ $booking->note }}
                            </p>

                        </div>
                    @endif

                    {{-- Lý do hủy --}}
                    @if (
                        $booking->status === 'cancelled'
                        && $booking->cancellation_reason
                    )
                        <div
                            class="rounded-3xl border border-red-200 bg-red-50 p-6 sm:p-8"
                        >

                            <h2 class="text-xl font-bold text-red-700">
                                Đơn đặt phòng đã bị hủy
                            </h2>

                            <p
                                class="mt-3 whitespace-pre-line leading-7 text-red-600"
                            >
                                {{ $booking->cancellation_reason }}
                            </p>

                            @if ($booking->cancelled_at)
                                <p class="mt-3 text-sm text-red-500">
                                    Thời gian hủy:
                                    {{ $booking->cancelled_at->format('H:i d/m/Y') }}
                                </p>
                            @endif

                        </div>
                    @endif

                </div>

                {{-- Cột thanh toán --}}
                <aside class="lg:sticky lg:top-24 lg:self-start">

                    <div
                        class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-xl"
                    >

                        {{-- Tổng tiền nổi bật --}}
                        <div
                            class="bg-gradient-to-br from-blue-600 to-indigo-700 px-6 py-6 text-white"
                        >

                            <div
                                class="flex items-center justify-between gap-4"
                            >

                                <div>
                                    <p
                                        class="text-sm font-medium text-blue-100"
                                    >
                                        Tổng thanh toán
                                    </p>

                                    <p class="mt-2 text-3xl font-black">
                                        {{ number_format((int) $booking->total_price, 0, ',', '.') }}đ
                                    </p>
                                </div>

                                <div
                                    class="flex h-12 w-12 items-center justify-center rounded-2xl bg-white/15 backdrop-blur"
                                >
                                    <svg
                                        class="h-6 w-6"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M3 7h18v11H3V7Zm0 4h18M7 15h3"
                                        />
                                    </svg>
                                </div>

                            </div>

                            <p
                                class="mt-3 text-xs leading-5 text-blue-100"
                            >
                                Mã đặt phòng:
                                {{ $booking->booking_code }}
                            </p>

                        </div>

                        <div class="p-6">

                            <h2 class="text-xl font-bold text-slate-900">
                                Chi tiết thanh toán
                            </h2>

                            <div class="mt-6 space-y-4 text-sm">

                                <div
                                    class="flex items-center justify-between gap-4"
                                >
                                    <span class="text-slate-500">
                                        Giá phòng mỗi đêm
                                    </span>

                                    <span
                                        class="font-semibold text-slate-800"
                                    >
                                        {{ number_format((int) $booking->room_price, 0, ',', '.') }}đ
                                    </span>
                                </div>

                                <div
                                    class="flex items-center justify-between gap-4"
                                >
                                    <span class="text-slate-500">
                                        Số đêm lưu trú
                                    </span>

                                    <span
                                        class="font-semibold text-slate-800"
                                    >
                                        {{ $booking->number_of_nights }} đêm
                                    </span>
                                </div>

                                <div
                                    class="flex items-center justify-between gap-4"
                                >
                                    <span class="text-slate-500">
                                        Tiền phòng
                                    </span>

                                    <span
                                        class="font-semibold text-slate-800"
                                    >
                                        {{ number_format((int) $booking->subtotal, 0, ',', '.') }}đ
                                    </span>
                                </div>

                                <div
                                    class="flex items-center justify-between gap-4"
                                >
                                    <span class="text-slate-500">
                                        Phí dịch vụ
                                    </span>

                                    <span
                                        class="font-semibold text-slate-800"
                                    >
                                        {{ number_format((int) $booking->service_fee, 0, ',', '.') }}đ
                                    </span>
                                </div>

                                <div
                                    class="flex items-center justify-between gap-4"
                                >
                                    <span class="text-slate-500">
                                        Giảm giá
                                    </span>

                                    <span
                                        class="font-semibold text-emerald-600"
                                    >
                                        -{{ number_format((int) $booking->discount_amount, 0, ',', '.') }}đ
                                    </span>
                                </div>

                            </div>

                            <div
                                class="mt-6 border-t border-dashed border-slate-200 pt-5"
                            >

                                <div
                                    class="flex items-end justify-between gap-4"
                                >

                                    <div>
                                        <span
                                            class="font-bold text-slate-900"
                                        >
                                            Tổng cộng
                                        </span>

                                        <p
                                            class="mt-1 text-xs text-slate-400"
                                        >
                                            Đã bao gồm các khoản phí
                                        </p>
                                    </div>

                                    <span
                                        class="text-2xl font-black text-blue-600"
                                    >
                                        {{ number_format((int) $booking->total_price, 0, ',', '.') }}đ
                                    </span>

                                </div>

                            </div>

                            {{-- Trạng thái thanh toán --}}
                            <div
                                class="mt-6 rounded-2xl border p-4
                                {{ $paymentClasses[$paymentStatus] ?? 'border-slate-200 bg-slate-50 text-slate-700' }}"
                            >

                                <div class="flex items-center gap-3">

                                    @if ($paymentStatus === 'paid')
                                        <span
                                            class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-emerald-100 text-emerald-600"
                                        >
                                            <svg
                                                class="h-5 w-5"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2.5"
                                                    d="m5 13 4 4L19 7"
                                                />
                                            </svg>
                                        </span>
                                    @elseif ($paymentStatus === 'failed')
                                        <span
                                            class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-red-100 text-red-600"
                                        >
                                            <svg
                                                class="h-5 w-5"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="m7 7 10 10M17 7 7 17"
                                                />
                                            </svg>
                                        </span>
                                    @else
                                        <span
                                            class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-amber-100 text-amber-600"
                                        >
                                            <svg
                                                class="h-5 w-5"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M12 7v5l3 2m6-2a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"
                                                />
                                            </svg>
                                        </span>
                                    @endif

                                    <div>
                                        <p
                                            class="text-xs font-medium opacity-75"
                                        >
                                            Trạng thái thanh toán
                                        </p>

                                        <p class="mt-0.5 font-bold">
                                            {{ $paymentLabels[$paymentStatus] ?? $paymentStatus }}
                                        </p>
                                    </div>

                                </div>

                            </div>

                            {{-- Nút thanh toán --}}
                            @if ($canPay)
                                <a
                                    href="{{ route('payments.checkout', $booking) }}"
                                    class="mt-6 inline-flex w-full items-center justify-center gap-3 rounded-2xl bg-gradient-to-r from-blue-600 to-indigo-600 px-5 py-4 text-sm font-bold text-white shadow-lg shadow-blue-200 transition hover:-translate-y-0.5 hover:from-blue-700 hover:to-indigo-700 hover:shadow-xl"
                                >
                                    <svg
                                        class="h-5 w-5"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M3 7h18v11H3V7Zm0 4h18M7 15h3"
                                        />
                                    </svg>

                                    {{ $paymentButtonLabel }}
                                </a>

                                <p
                                    class="mt-3 text-center text-xs leading-5 text-slate-400"
                                >
                                    Bạn sẽ được chuyển đến trang xác nhận
                                    thanh toán VNPAY.
                                </p>
                            @elseif ($paymentStatus === 'paid')
                                <div
                                    class="mt-6 flex items-start gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-emerald-700"
                                >

                                    <svg
                                        class="mt-0.5 h-5 w-5 shrink-0"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2.5"
                                            d="m5 13 4 4L19 7"
                                        />
                                    </svg>

                                    <div>
                                        <p class="font-bold">
                                            Đã thanh toán thành công
                                        </p>

                                        <p
                                            class="mt-1 text-xs leading-5"
                                        >
                                            Bạn không cần thực hiện lại
                                            giao dịch.
                                        </p>
                                    </div>

                                </div>
                            @elseif ($booking->status === 'cancelled')
                                <div
                                    class="mt-6 rounded-2xl border border-red-200 bg-red-50 p-4 text-sm text-red-700"
                                >
                                    Đơn đặt phòng đã bị hủy nên không thể
                                    thanh toán.
                                </div>
                            @elseif (
                                ! in_array(
                                    $booking->status,
                                    ['pending', 'confirmed'],
                                    true
                                )
                            )
                                <div
                                    class="mt-6 rounded-2xl border border-slate-200 bg-slate-50 p-4 text-sm text-slate-600"
                                >
                                    Trạng thái hiện tại của đơn không cho
                                    phép thực hiện thanh toán.
                                </div>
                            @endif

                            {{-- Nút phụ --}}
                            <a
                                href="{{ route('bookings.history') }}"
                                class="mt-6 inline-flex w-full items-center justify-center rounded-xl border border-slate-300 bg-white px-5 py-3 text-sm font-semibold text-slate-700 transition hover:border-blue-600 hover:bg-blue-50 hover:text-blue-600"
                            >
                                Xem lịch sử đặt phòng
                            </a>

                            @if ($booking->room?->homestay)
                                <a
                                    href="{{ route('homestays.show', $booking->room->homestay->slug) }}"
                                    class="mt-3 inline-flex w-full items-center justify-center rounded-xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white transition hover:bg-slate-800"
                                >
                                    Quay lại Homestay
                                </a>
                            @endif

                        </div>

                    </div>

                </aside>

            </div>

        </section>

    </main>

</body>

</html>