<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi tiết đánh giá | HomeStay</title>

    @vite([
        'resources/css/app.css',
        'resources/js/app.js',
    ])
</head>

<body class="min-h-screen bg-slate-100 text-slate-900 antialiased">

    @include('partials.navbar')

    @php
        $userName = $review->user?->name ?? 'Không xác định';

        $nameParts = preg_split('/\s+/', trim($userName));

        $avatarText = collect($nameParts)
            ->filter()
            ->take(2)
            ->map(fn ($part) => mb_strtoupper(mb_substr($part, 0, 1)))
            ->implode('');

        $statusLabels = [
            'pending'  => 'Chờ duyệt',
            'approved' => 'Đã duyệt',
            'hidden'   => 'Đã ẩn',
        ];

        $statusStyles = [
            'pending' => [
                'badge' => 'border-amber-200 bg-amber-50 text-amber-700',
                'dot'   => 'bg-amber-500',
                'text'  => 'text-amber-700',
            ],
            'approved' => [
                'badge' => 'border-emerald-200 bg-emerald-50 text-emerald-700',
                'dot'   => 'bg-emerald-500',
                'text'  => 'text-emerald-700',
            ],
            'hidden' => [
                'badge' => 'border-red-200 bg-red-50 text-red-700',
                'dot'   => 'bg-red-500',
                'text'  => 'text-red-700',
            ],
        ];

        $currentStatus = $statusStyles[$review->status] ?? $statusStyles['hidden'];

        $booking  = $review->booking;
        $room     = $booking?->room;
        $homestay = $review->homestay ?? $room?->homestay;
    @endphp

    <main class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">

        <x-alert />

        {{-- Header --}}
        <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

            <div class="min-w-0">
                <a
                    href="{{ route('admin.reviews.index') }}"
                    class="mb-4 block text-sm font-semibold text-blue-600 transition hover:text-blue-700"
                >
                    ← Quay lại danh sách đánh giá
                </a>

                <h1 class="text-3xl font-bold tracking-tight text-slate-900">
                    Chi tiết đánh giá
                </h1>

                <p class="mt-2 text-sm leading-6 text-slate-500">
                    Kiểm tra thông tin khách hàng, Homestay và nội dung đánh giá.
                </p>
            </div>

            {{-- Badge trạng thái --}}
            <span class="inline-flex w-fit shrink-0 items-center gap-2 rounded-full border px-4 py-2 text-sm font-semibold {{ $currentStatus['badge'] }}">
                <span class="h-2 w-2 shrink-0 rounded-full {{ $currentStatus['dot'] }}"></span>
                {{ $statusLabels[$review->status] ?? 'Không xác định' }}
            </span>

        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

            {{-- Cột trái --}}
            <div class="min-w-0 space-y-6 lg:col-span-2">

                {{-- Card 1: Nội dung đánh giá --}}
                <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

                    <div class="border-b border-slate-200 bg-slate-50 px-5 py-4 sm:px-6">
                        <div class="flex items-center gap-3">
                            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-amber-50 text-xl">
                                ⭐
                            </span>
                            <div class="min-w-0">
                                <h2 class="text-base font-bold text-slate-900 sm:text-lg">
                                    Nội dung đánh giá
                                </h2>
                                <p class="mt-0.5 text-sm text-slate-500">
                                    Nhận xét của khách hàng sau thời gian lưu trú.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="p-5 sm:p-6">

                        {{-- Rating --}}
                        <div class="rounded-2xl border border-amber-100 bg-amber-50/70 p-4 sm:p-5">
                            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                                <div
                                    class="flex items-center gap-0.5"
                                    aria-label="{{ $review->rating }} trên 5 sao"
                                >
                                    @for ($star = 1; $star <= 5; $star++)
                                        <span class="text-2xl leading-none sm:text-3xl {{ $star <= $review->rating ? 'text-amber-400' : 'text-slate-200' }}">
                                            ★
                                        </span>
                                    @endfor
                                </div>

                                <p class="text-lg font-bold text-slate-900">
                                    {{ number_format($review->rating, 1) }}
                                    <span class="text-sm font-medium text-slate-500">/ 5</span>
                                </p>
                            </div>
                        </div>

                        <div class="mt-6">
                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                                Tiêu đề
                            </p>
                            <h3 class="mt-1.5 text-lg font-bold text-slate-900 sm:text-xl">
                                {{ $review->title ?: 'Không có tiêu đề' }}
                            </h3>
                        </div>

                        <div class="mt-5">
                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                                Nội dung
                            </p>
                            <div class="mt-2 rounded-2xl border border-slate-200 bg-slate-50 p-4 sm:p-5">
                                <p class="whitespace-pre-line wrap-break-word text-base font-semibold leading-7 text-slate-700">
                                    {{ $review->content ?: 'Khách hàng không nhập nội dung đánh giá.' }}
                                </p>
                            </div>
                        </div>

                        <div class="mt-5 flex items-center gap-2 text-sm">
                            <p class="rounded-lg bg-blue-100 text-blue-500 px-3 py-1.5 font-medium">
                                Lần đánh giá {{ $review->review_number }}
                            </p>
                            <span class="text-slate-500">
                                Gửi lúc {{ $review->created_at->format('H:i d/m/Y') }}
                            </span>
                        </div>

                    </div>
                </section>

                {{-- Card 2: Thông tin khách hàng --}}
                <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

                    <div class="border-b border-slate-200 bg-slate-50 px-5 py-4 sm:px-6">
                        <div class="flex items-center gap-3">
                            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-xl">
                                👤
                            </span>
                            <div class="min-w-0">
                                <h2 class="text-base font-bold text-slate-900 sm:text-lg">
                                    Thông tin khách hàng
                                </h2>
                                <p class="mt-0.5 text-sm text-slate-500">
                                    Người đã gửi đánh giá này.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="p-5 sm:p-6">

                        <div class="mb-6 flex items-center gap-4">
                            <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-full bg-blue-100 text-base font-bold text-blue-700 sm:h-16 sm:w-16 sm:text-lg">
                                {{ $avatarText ?: '?' }}
                            </div>
                            <div class="min-w-0">
                                <p class="truncate text-base font-bold text-slate-900 sm:text-lg">
                                    {{ $userName }}
                                </p>
                                <p class="mt-0.5 truncate text-sm text-slate-500">
                                    {{ $review->user?->email ?? 'Không có email' }}
                                </p>
                            </div>
                        </div>

                        <dl class="grid gap-4 sm:grid-cols-2">
                            <div class="rounded-xl bg-slate-50 p-4">
                                <dt class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                                    Họ và tên
                                </dt>
                                <dd class="mt-1.5 wrap-break-word font-semibold text-slate-900">
                                    {{ $userName }}
                                </dd>
                            </div>

                            <div class="rounded-xl bg-slate-50 p-4">
                                <dt class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                                    Email
                                </dt>
                                <dd class="mt-1.5 truncate font-semibold text-slate-900" title="{{ $review->user?->email }}">
                                    {{ $review->user?->email ?? 'Chưa cập nhật' }}
                                </dd>
                            </div>

                            <div class="rounded-xl bg-slate-50 p-4">
                                <dt class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                                    Số điện thoại
                                </dt>
                                <dd class="mt-1.5 font-semibold text-slate-900">
                                    {{ $review->user?->phone ?? $booking?->customer_phone ?? 'Chưa cập nhật' }}
                                </dd>
                            </div>

                            <div class="rounded-xl bg-slate-50 p-4">
                                <dt class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                                    Mã đơn đặt phòng
                                </dt>
                                <dd class="mt-1.5 font-semibold text-blue-600">
                                    {{ $booking?->booking_code ?? 'Không xác định' }}
                                </dd>
                            </div>
                        </dl>

                    </div>
                </section>

                {{-- Card 3: Thông tin Homestay --}}
                <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

                    <div class="border-b border-slate-200 bg-slate-50 px-5 py-4 sm:px-6">
                        <div class="flex items-center gap-3">
                            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-xl">
                                🏡
                            </span>
                            <div class="min-w-0">
                                <h2 class="text-base font-bold text-slate-900 sm:text-lg">
                                    Thông tin Homestay
                                </h2>
                                <p class="mt-0.5 text-sm text-slate-500">
                                    Nơi lưu trú được khách hàng đánh giá.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="p-5 sm:p-6">
                        <dl class="grid gap-4 sm:grid-cols-2">

                            <div class="rounded-xl bg-slate-50 p-4 sm:col-span-2">
                                <dt class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                                    Tên Homestay
                                </dt>
                                <dd class="mt-1.5 text-base font-bold text-slate-900 sm:text-lg">
                                    {{ $homestay?->name ?? 'Homestay không tồn tại' }}
                                </dd>
                            </div>

                            <div class="rounded-xl bg-slate-50 p-4">
                                <dt class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                                    Phòng
                                </dt>
                                <dd class="mt-1.5 font-semibold text-slate-900">
                                    {{ $room?->name ?? 'Không xác định' }}
                                </dd>
                            </div>

                            <div class="rounded-xl bg-slate-50 p-4">
                                <dt class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                                    Loại phòng
                                </dt>
                                <dd class="mt-1.5 font-semibold text-slate-900">
                                    {{ $room?->room_type ?? 'Không xác định' }}
                                </dd>
                            </div>

                            <div class="rounded-xl bg-slate-50 p-4">
                                <dt class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                                    Ngày nhận phòng
                                </dt>
                                <dd class="mt-1.5 font-semibold text-slate-900">
                                    {{ $booking?->check_in?->format('d/m/Y') ?? 'Không xác định' }}
                                </dd>
                            </div>

                            <div class="rounded-xl bg-slate-50 p-4">
                                <dt class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                                    Ngày trả phòng
                                </dt>
                                <dd class="mt-1.5 font-semibold text-slate-900">
                                    {{ $booking?->check_out?->format('d/m/Y') ?? 'Không xác định' }}
                                </dd>
                            </div>

                            <div class="rounded-xl bg-slate-50 p-4 sm:col-span-2">
                                <dt class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                                    Địa chỉ
                                </dt>
                                <dd class="mt-1.5 font-semibold leading-6 text-slate-700">
                                    {{ $homestay?->address ?? 'Chưa cập nhật địa chỉ' }}
                                </dd>
                            </div>

                        </dl>
                    </div>
                </section>

            </div>

            {{-- Cột phải --}}
            <aside class="min-w-0 space-y-6">

                {{-- Card 4: Thao tác nhanh --}}
                <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

                    <div class="border-b border-slate-200 bg-slate-50 px-5 py-4 sm:px-6">
                        <div class="flex items-center gap-3">
                            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-xl">
                                ⚙️
                            </span>
                            <div class="min-w-0">
                                <h2 class="text-base font-bold text-slate-900 sm:text-lg">
                                    Thao tác nhanh
                                </h2>
                                <p class="mt-0.5 text-sm text-slate-500">
                                    Kiểm duyệt đánh giá.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-3 p-5 sm:p-6">

                        @if ($review->status === 'pending')

                            {{-- Duyệt đánh giá --}}
                            <form
                                method="POST"
                                action="{{ route('admin.reviews.update-status', $review) }}"
                            >
                                @csrf
                                @method('PATCH')

                                <input
                                    type="hidden"
                                    name="status"
                                    value="approved"
                                >

                                <button
                                    type="submit"
                                    onclick="return confirm('Bạn có chắc muốn duyệt đánh giá này không?')"
                                    class="inline-flex w-full cursor-pointer items-center justify-center gap-2 rounded-xl bg-emerald-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-emerald-700"
                                >
                                    <span>✓</span>
                                    Duyệt đánh giá
                                </button>
                            </form>

                            {{-- Ẩn đánh giá --}}
                            <form
                                method="POST"
                                action="{{ route('admin.reviews.update-status', $review) }}"
                            >
                                @csrf
                                @method('PATCH')

                                <input
                                    type="hidden"
                                    name="status"
                                    value="hidden"
                                >

                                <button
                                    type="submit"
                                    onclick="return confirm('Bạn có chắc muốn ẩn đánh giá này không?')"
                                    class="inline-flex w-full cursor-pointer items-center justify-center gap-2 rounded-xl border border-red-200 bg-red-50 px-5 py-3 text-sm font-semibold text-red-600 transition hover:border-red-300 hover:bg-red-100 hover:text-red-700 focus:outline-none focus:ring-4 focus:ring-red-100"
                                >
                                    <span>🚫</span>
                                    Ẩn đánh giá
                                </button>
                            </form>

                        @elseif ($review->status === 'approved')

                            {{-- Thông báo --}}
                            <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm leading-6 text-emerald-700">
                                Đánh giá này đã được duyệt và đang hiển thị cho khách hàng.
                            </div>

                            {{-- Ẩn đánh giá --}}
                            <form
                                method="POST"
                                action="{{ route('admin.reviews.update-status', $review) }}"
                            >
                                @csrf
                                @method('PATCH')

                                <input
                                    type="hidden"
                                    name="status"
                                    value="hidden"
                                >

                                <button
                                    type="submit"
                                    onclick="return confirm('Bạn có chắc muốn ẩn đánh giá này không?')"
                                    class="inline-flex w-full cursor-pointer items-center justify-center gap-2 rounded-xl border border-red-200 bg-red-50 px-5 py-3 text-sm font-semibold text-red-600 transition hover:border-red-300 hover:bg-red-100 hover:text-red-700 focus:outline-none focus:ring-4 focus:ring-red-100"
                                >
                                    <span>🚫</span>
                                    Ẩn đánh giá
                                </button>
                            </form>

                        @elseif ($review->status === 'hidden')

                            {{-- Thông báo --}}
                            <div class="rounded-xl border border-red-200 bg-red-50 p-4 text-sm leading-6 text-red-700">
                                Đánh giá này đang bị ẩn và không hiển thị cho khách hàng.
                            </div>

                            {{-- Hiển thị lại --}}
                            <form
                                method="POST"
                                action="{{ route('admin.reviews.update-status', $review) }}"
                            >
                                @csrf
                                @method('PATCH')

                                <input
                                    type="hidden"
                                    name="status"
                                    value="approved"
                                >

                                <button
                                    type="submit"
                                    onclick="return confirm('Bạn có chắc muốn hiển thị lại đánh giá này không?')"
                                    class="inline-flex w-full cursor-pointer items-center justify-center gap-2 rounded-xl bg-emerald-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-emerald-700"
                                >
                                    <span>✓</span>
                                    Hiển thị lại
                                </button>
                            </form>

                        @endif

                    </div>
                    
                </section>

                {{-- Card 5: Thông tin hệ thống --}}
                <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

                    <div class="border-b border-slate-200 bg-slate-50 px-5 py-4 sm:px-6">
                        <div class="flex items-center gap-3">
                            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-indigo-50 text-xl">
                                📋
                            </span>
                            <h2 class="text-base font-bold text-slate-900 sm:text-lg">
                                Thông tin hệ thống
                            </h2>
                        </div>
                    </div>

                    <div class="divide-y divide-slate-100 p-5 sm:p-6">

                        <div class="flex items-center justify-between gap-3 py-3.5">
                            <dt class="text-sm text-slate-500">Mã đánh giá</dt>
                            <dd class="font-semibold text-slate-900">#{{ $review->id }}</dd>
                        </div>

                        <div class="flex items-center justify-between gap-3 py-3.5">
                            <dt class="text-sm text-slate-500">Trạng thái</dt>
                            <dd class="font-semibold {{ $currentStatus['text'] }}">
                                {{ $statusLabels[$review->status] ?? 'Không xác định' }}
                            </dd>
                        </div>

                        <div class="flex items-center justify-between gap-3 py-3.5">
                            <dt class="text-sm text-slate-500">Số lần đánh giá</dt>
                            <dd class="font-semibold text-slate-900">{{ $review->review_number }}</dd>
                        </div>

                        <div class="flex items-start justify-between gap-3 py-3.5">
                            <dt class="text-sm text-slate-500">Ngày tạo</dt>
                            <dd class="text-right font-semibold text-slate-900">
                                {{ $review->created_at->format('H:i d/m/Y') }}
                            </dd>
                        </div>

                        <div class="flex items-start justify-between gap-3 py-3.5">
                            <dt class="text-sm text-slate-500">Cập nhật</dt>
                            <dd class="text-right font-semibold text-slate-900">
                                {{ $review->updated_at->format('H:i d/m/Y') }}
                            </dd>
                        </div>

                    </div>
                </section>

            </aside>

        </div>

    </main>

</body>

</html>