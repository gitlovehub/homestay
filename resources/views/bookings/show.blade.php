<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Chi tiết đơn {{ $booking->booking_code }} | HomeStayGo</title>

    @vite([
        'resources/css/app.css',
        'resources/js/app.js',
    ])
</head>

<body class="min-h-screen bg-slate-50">

    @include('partials.navbar')

    @php
        $statusLabels = [
            'pending' => 'Chờ xác nhận',
            'confirmed' => 'Đã xác nhận',
            'checked_in' => 'Đã nhận phòng',
            'completed' => 'Đã hoàn thành',
            'cancelled' => 'Đã hủy',
        ];

        $statusClasses = [
            'pending' => 'bg-amber-100 text-amber-800',
            'confirmed' => 'bg-blue-100 text-blue-800',
            'checked_in' => 'bg-indigo-100 text-indigo-800',
            'completed' => 'bg-emerald-100 text-emerald-800',
            'cancelled' => 'bg-red-100 text-red-800',
        ];

        $paymentLabels = [
            'unpaid' => 'Chưa thanh toán',
            'pending' => 'Đang xử lý',
            'paid' => 'Đã thanh toán',
            'refunded' => 'Đã hoàn tiền',
            'failed' => 'Thanh toán thất bại',
        ];
    @endphp

    <main>

        {{-- Breadcrumb --}}
        <section class="border-b border-slate-200 bg-white">
            <div class="mx-auto max-w-7xl px-4 py-5 sm:px-6 lg:px-8">

                <nav class="flex flex-wrap items-center gap-2 text-sm text-slate-500">
                    <a
                        href="{{ route('home') }}"
                        class="font-medium transition hover:text-blue-600"
                    >
                        Trang chủ
                    </a>

                    <span>/</span>

                    <a
                        href="{{ route('bookings.history') }}"
                        class="font-medium transition hover:text-blue-600"
                    >
                        Lịch sử đặt phòng
                    </a>

                    <span>/</span>

                    <span class="font-semibold text-slate-800">
                        {{ $booking->booking_code }}
                    </span>
                </nav>

            </div>
        </section>

        <section class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-8 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-medium text-emerald-700">
                    {{ session('success') }}
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

                <span class="inline-flex w-fit rounded-full px-4 py-2 text-sm font-semibold {{ $statusClasses[$booking->status] ?? 'bg-slate-100 text-slate-700' }}">
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

                            <p class="mt-5 whitespace-pre-line leading-7 text-slate-600">
                                {{ $booking->note }}
                            </p>

                        </div>
                    @endif

                    @if ($booking->status === 'cancelled' && $booking->cancellation_reason)
                        <div class="rounded-3xl border border-red-200 bg-red-50 p-6 sm:p-8">

                            <h2 class="text-xl font-bold text-red-700">
                                Đơn đặt phòng đã bị hủy
                            </h2>

                            <p class="mt-3 whitespace-pre-line leading-7 text-red-600">
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

                        <div class="mt-6 rounded-2xl bg-slate-50 p-4">

                            <div class="flex items-center justify-between gap-4 text-sm">

                                <span class="text-slate-500">
                                    Thanh toán
                                </span>

                                <span class="font-semibold text-slate-800">
                                    {{ $paymentLabels[$booking->payment_status] ?? $booking->payment_status }}
                                </span>

                            </div>

                        </div>

                        <a
                            href="{{ route('bookings.history') }}"
                            class="mt-6 inline-flex w-full items-center justify-center rounded-xl border border-slate-300 bg-white px-5 py-3 text-sm font-semibold text-slate-700 transition hover:border-blue-600 hover:text-blue-600"
                        >
                            Xem lịch sử đặt phòng
                        </a>

                        <a
                            href="{{ route('homestays.show', $booking->room->homestay->slug) }}"
                            class="mt-3 inline-flex w-full items-center justify-center rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-blue-700"
                        >
                            Quay lại Homestay
                        </a>

                    </div>

                </aside>

            </div>

        </section>

    </main>

</body>

</html>