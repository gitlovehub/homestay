<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Lịch sử đặt phòng | HomeStayGo</title>

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
    @endphp

    <main>

        {{-- Breadcrumb --}}
        <section class="border-b border-slate-200 bg-white">

            <div class="mx-auto max-w-7xl px-4 py-5 sm:px-6 lg:px-8">

                <nav class="flex items-center gap-2 text-sm text-slate-500">

                    <a
                        href="{{ route('home') }}"
                        class="font-medium transition hover:text-blue-600"
                    >
                        Trang chủ
                    </a>

                    <span>/</span>

                    <span class="font-semibold text-slate-800">
                        Lịch sử đặt phòng
                    </span>

                </nav>

            </div>

        </section>

        <section class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">

            <div class="mb-8">

                <p class="font-semibold uppercase tracking-widest text-blue-600">
                    Đơn của bạn
                </p>

                <h1 class="mt-2 text-3xl font-bold tracking-tight text-slate-950 sm:text-4xl">
                    Lịch sử đặt phòng
                </h1>

                <p class="mt-3 text-slate-500">
                    Theo dõi trạng thái và xem lại các đơn đã đặt.
                </p>

            </div>

            @if (session('success'))
                <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-medium text-emerald-700">
                    {{ session('success') }}
                </div>
            @endif

            @if ($bookings->isEmpty())

                <div class="rounded-3xl border border-dashed border-slate-300 bg-white px-6 py-16 text-center shadow-sm">

                    <div class="text-6xl">
                        📅
                    </div>

                    <h2 class="mt-5 text-2xl font-bold text-slate-900">
                        Bạn chưa có đơn đặt phòng
                    </h2>

                    <p class="mx-auto mt-3 max-w-md text-slate-500">
                        Hãy tìm một Homestay phù hợp và bắt đầu chuyến đi của bạn.
                    </p>

                    <a
                        href="{{ route('home') }}"
                        class="mt-7 inline-flex items-center justify-center rounded-xl bg-blue-600 px-6 py-3 text-sm font-semibold text-white transition hover:bg-blue-700"
                    >
                        Khám phá Homestay
                    </a>

                </div>

            @else

                <div class="space-y-5">

                    @foreach ($bookings as $booking)

                        <article class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm transition hover:shadow-md">

                            <div class="grid lg:grid-cols-[220px_minmax(0,1fr)]">

                                {{-- Ảnh phòng --}}
                                <div class="bg-slate-100">

                                    @if ($booking->room?->image)
                                        <img
                                            src="{{ Storage::url($booking->room->image) }}"
                                            alt="{{ $booking->room->name }}"
                                            class="h-56 w-full object-cover lg:h-full"
                                        >
                                    @else
                                        <div class="flex h-56 items-center justify-center lg:h-full">

                                            <div class="text-center">
                                                <div class="text-5xl">
                                                    🚪
                                                </div>

                                                <p class="mt-3 text-sm text-slate-400">
                                                    Chưa có ảnh phòng
                                                </p>
                                            </div>

                                        </div>
                                    @endif

                                </div>

                                {{-- Nội dung --}}
                                <div class="p-6 sm:p-7">

                                    <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">

                                        <div class="min-w-0">

                                            <div class="flex flex-wrap items-center gap-2">

                                                <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $statusClasses[$booking->status] ?? 'bg-slate-100 text-slate-700' }}">
                                                    {{ $statusLabels[$booking->status] ?? $booking->status }}
                                                </span>

                                                <span class="text-sm font-medium text-slate-400">
                                                    {{ $booking->booking_code }}
                                                </span>

                                            </div>

                                            <h2 class="mt-4 text-xl font-bold text-slate-900">
                                                {{ $booking->room?->name ?? 'Phòng không còn tồn tại' }}
                                            </h2>

                                            <p class="mt-1 font-medium text-blue-600">
                                                {{ $booking->room?->homestay?->name ?? 'Homestay không còn tồn tại' }}
                                            </p>

                                        </div>

                                        <div class="shrink-0 sm:text-right">

                                            <p class="text-sm text-slate-500">
                                                Tổng tiền
                                            </p>

                                            <p class="mt-1 text-2xl font-bold text-blue-600">
                                                {{ number_format($booking->total_price, 0, ',', '.') }}đ
                                            </p>

                                        </div>

                                    </div>

                                    <div class="mt-6 grid grid-cols-2 gap-3 sm:grid-cols-4">

                                        <div class="rounded-xl bg-slate-50 p-3">

                                            <p class="text-xs text-slate-400">
                                                Nhận phòng
                                            </p>

                                            <p class="mt-1 text-sm font-semibold text-slate-700">
                                                {{ $booking->check_in->format('d/m/Y') }}
                                            </p>

                                        </div>

                                        <div class="rounded-xl bg-slate-50 p-3">

                                            <p class="text-xs text-slate-400">
                                                Trả phòng
                                            </p>

                                            <p class="mt-1 text-sm font-semibold text-slate-700">
                                                {{ $booking->check_out->format('d/m/Y') }}
                                            </p>

                                        </div>

                                        <div class="rounded-xl bg-slate-50 p-3">

                                            <p class="text-xs text-slate-400">
                                                Số đêm
                                            </p>

                                            <p class="mt-1 text-sm font-semibold text-slate-700">
                                                {{ $booking->number_of_nights }} đêm
                                            </p>

                                        </div>

                                        <div class="rounded-xl bg-slate-50 p-3">

                                            <p class="text-xs text-slate-400">
                                                Số khách
                                            </p>

                                            <p class="mt-1 text-sm font-semibold text-slate-700">
                                                {{ $booking->number_of_guests }} khách
                                            </p>

                                        </div>

                                    </div>

                                    <div class="mt-6 flex flex-col gap-3 border-t border-slate-100 pt-5 sm:flex-row sm:items-center sm:justify-between">

                                        <p class="text-sm text-slate-400">
                                            Đặt lúc {{ $booking->created_at->format('H:i d/m/Y') }}
                                        </p>

                                        <a
                                            href="{{ route('bookings.show', $booking) }}"
                                            class="inline-flex items-center justify-center rounded-xl border border-slate-300 bg-white px-5 py-3 text-sm font-semibold text-slate-700 transition hover:border-blue-600 hover:text-blue-600"
                                        >
                                            Xem chi tiết
                                        </a>

                                    </div>

                                </div>

                            </div>

                        </article>

                    @endforeach

                </div>

                @if ($bookings->hasPages())
                    <div class="mt-8">
                        {{ $bookings->links() }}
                    </div>
                @endif

            @endif

        </section>

    </main>

</body>

</html>
