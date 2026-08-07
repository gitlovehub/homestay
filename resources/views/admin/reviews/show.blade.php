@extends('layouts.admin')

@section('title', 'Chi tiết đánh giá | HomeStayGo')

@section('page-title', 'Chi tiết đánh giá')

@section('content')
    @php
        $userName = $review->user?->name ?? 'Không xác định';

        $nameParts = preg_split('/\s+/', trim($userName));
        $avatarText = collect($nameParts)
            ->filter()
            ->take(2)
            ->map(fn($part) => mb_strtoupper(mb_substr($part, 0, 1)))
            ->implode('');

        $statusLabels = [
            'pending' => 'Chờ duyệt',
            'approved' => 'Đã duyệt',
            'hidden' => 'Đã ẩn',
        ];

        $statusStyles = [
            'pending' => [
                'badge' => 'border-amber-200 bg-amber-50 text-amber-700 dark:border-amber-800 dark:bg-amber-950/40 dark:text-amber-300',
                'dot' => 'bg-amber-500',
                'text' => 'text-amber-700 dark:text-amber-300',
            ],
            'approved' => [
                'badge' => 'border-emerald-200 bg-emerald-50 text-emerald-700 dark:border-emerald-800 dark:bg-emerald-950/40 dark:text-emerald-300',
                'dot' => 'bg-emerald-500',
                'text' => 'text-emerald-700 dark:text-emerald-300',
            ],
            'hidden' => [
                'badge' => 'border-red-200 bg-red-50 text-red-700 dark:border-red-800 dark:bg-red-950/40 dark:text-red-300',
                'dot' => 'bg-red-500',
                'text' => 'text-red-700 dark:text-red-300',
            ],
        ];

        $currentStatus = $statusStyles[$review->status] ?? $statusStyles['hidden'];

        $booking = $review->booking;
        $room = $booking?->room;
        $homestay = $review->homestay ?? $room?->homestay;
    @endphp

    <div class="mx-auto max-w-screen-2xl">
        <x-alert />

        {{-- Header --}}
        <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
            <div class="min-w-0">
                <h2 class="text-lg font-semibold text-slate-900 dark:text-slate-100 md:text-2xl">
                    Chi tiết đánh giá
                </h2>

                <a href="{{ route('admin.reviews.index') }}"
                    class="mt-1 inline-flex items-center gap-1 text-xs font-medium text-blue-600 transition hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 sm:text-sm">
                    ←
                    Trở về danh sách đánh giá
                </a>
            </div>

            {{-- Badge trạng thái --}}
            <span
                class="inline-flex w-fit shrink-0 items-center gap-2 rounded-full border px-4 py-2 text-sm font-semibold {{ $currentStatus['badge'] }}">
                <span class="h-2.5 w-2.5 shrink-0 rounded-full {{ $currentStatus['dot'] }}"></span>
                {{ $statusLabels[$review->status] ?? 'Không xác định' }}
            </span>
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

            {{-- ==================== CỘT TRÁI ==================== --}}
            <div class="min-w-0 space-y-6 lg:col-span-2">

                {{-- Card 1: Nội dung đánh giá --}}
                <section
                    class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-800">

                    <div
                        class="border-b border-slate-200 bg-slate-50/80 px-5 py-4 sm:px-6 dark:border-slate-700 dark:bg-slate-900/60">
                        <div class="flex items-center gap-3">
                            <span
                                class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-amber-100 dark:bg-amber-900/50">
                                <x-icon-star class="h-5 w-5 text-amber-400" />
                            </span>

                            <div>
                                <h2 class="text-base font-bold text-slate-900 sm:text-lg dark:text-slate-100">
                                    Nội dung đánh giá
                                </h2>
                                <p class="mt-0.5 text-sm text-slate-500 dark:text-slate-400">
                                    Nhận xét của khách hàng sau thời gian lưu trú
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="p-5 sm:p-6">
                        {{-- Rating --}}
                        <div
                            class="rounded-2xl border border-amber-100 bg-gradient-to-br from-amber-50 to-orange-50/50 p-5 dark:border-amber-900 dark:from-amber-950/40 dark:to-orange-950/20">

                            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                                <div class="flex items-center gap-1"
                                    aria-label="{{ $review->rating }} trên 5 sao">

                                    @for ($star = 1; $star <= 5; $star++)
                                        <x-icon-star
                                            class="h-8 w-8 {{ $star <= $review->rating
                                                ? 'text-amber-400'
                                                : 'text-slate-200 dark:text-slate-700' }}" />
                                    @endfor
                                </div>

                                <p class="text-2xl font-bold text-slate-900 dark:text-slate-100">
                                    {{ number_format($review->rating, 1) }}
                                    <span class="text-base font-medium text-slate-500 dark:text-slate-400">
                                        / 5
                                    </span>
                                </p>
                            </div>
                        </div>

                        {{-- Tiêu đề --}}
                        <div class="mt-6">
                            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">
                                Tiêu đề
                            </p>

                            <h3 class="mt-1.5 text-lg font-bold text-slate-900 sm:text-xl dark:text-slate-100">
                                {{ $review->title ?: 'Không có tiêu đề' }}
                            </h3>
                        </div>

                        {{-- Nội dung --}}
                        <div class="mt-5">
                            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">
                                Nội dung
                            </p>

                            <div
                                class="mt-2 rounded-2xl border border-slate-200 bg-slate-50 p-4 sm:p-5 dark:border-slate-700 dark:bg-slate-900">
                                <p class="text-base leading-7 text-slate-700 dark:text-slate-300">
                                    {{ $review->content ?: 'Khách hàng không nhập nội dung đánh giá.' }}
                                </p>
                            </div>
                        </div>

                        {{-- Meta --}}
                        <div class="mt-5 flex flex-wrap items-center gap-3 text-sm">
                            <span
                                class="inline-flex items-center rounded-lg bg-blue-50 px-3 py-1.5 font-medium text-blue-600 dark:bg-blue-950/40 dark:text-blue-400">
                                Lần đánh giá {{ $review->review_number }}
                            </span>

                            <span class="text-slate-500 dark:text-slate-400">
                                Gửi lúc {{ $review->created_at->format('H:i · d/m/Y') }}
                            </span>
                        </div>
                    </div>
                </section>

                {{-- Card 2: Thông tin khách hàng --}}
                <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-800">
                    <div class="border-b border-slate-200 bg-slate-50/80 px-5 py-4 sm:px-6 dark:border-slate-700 dark:bg-slate-900/60">
                        <div class="flex items-center gap-3">
                            <span
                                class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-100 text-xl dark:bg-blue-900/50">
                                👤
                            </span>
                            <div>
                                <h2 class="text-base font-bold text-slate-900 sm:text-lg dark:text-slate-100">Thông tin khách hàng</h2>
                                <p class="mt-0.5 text-sm text-slate-500 dark:text-slate-400">Người đã gửi đánh giá này</p>
                            </div>
                        </div>
                    </div>

                    <div class="p-5 sm:p-6">
                        <div class="mb-6 flex items-center gap-4">
                            <div
                                class="flex h-14 w-14 shrink-0 items-center justify-center rounded-full bg-blue-100 text-lg font-bold text-blue-700 sm:h-16 sm:w-16 sm:text-xl dark:bg-blue-900/50 dark:text-blue-300">
                                {{ $avatarText ?: '?' }}
                            </div>
                            <div class="min-w-0">
                                <p class="truncate text-lg font-bold text-slate-900 dark:text-slate-100">{{ $userName }}</p>
                                <p class="mt-0.5 truncate text-sm text-slate-500 dark:text-slate-400">
                                    {{ $review->user?->email ?? 'Không có email' }}
                                </p>
                            </div>
                        </div>

                        <dl class="grid gap-4 sm:grid-cols-2">
                            <div class="rounded-xl bg-slate-50 p-4 dark:bg-slate-900">
                                <dt class="text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">Họ và tên</dt>
                                <dd class="mt-1.5 font-semibold text-slate-900 break-words dark:text-slate-100">{{ $userName }}</dd>
                            </div>

                            <div class="rounded-xl bg-slate-50 p-4 dark:bg-slate-900">
                                <dt class="text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">Email</dt>
                                <dd class="mt-1.5 font-semibold text-slate-900 truncate dark:text-slate-100"
                                    title="{{ $review->user?->email }}">
                                    {{ $review->user?->email ?? 'Chưa cập nhật' }}
                                </dd>
                            </div>

                            <div class="rounded-xl bg-slate-50 p-4 dark:bg-slate-900">
                                <dt class="text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">Số điện thoại</dt>
                                <dd class="mt-1.5 font-semibold text-slate-900 dark:text-slate-100">
                                    {{ $review->user?->phone ?? ($booking?->customer_phone ?? 'Chưa cập nhật') }}
                                </dd>
                            </div>

                            <div class="rounded-xl bg-slate-50 p-4 dark:bg-slate-900">
                                <dt class="text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">Mã đơn đặt phòng
                                </dt>
                                <dd class="mt-1.5 font-semibold text-blue-600 dark:text-blue-400">
                                    {{ $booking?->booking_code ?? 'Không xác định' }}
                                </dd>
                            </div>
                        </dl>
                    </div>
                </section>

                {{-- Card 3: Thông tin Homestay --}}
                <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-800">
                    <div class="border-b border-slate-200 bg-slate-50/80 px-5 py-4 sm:px-6 dark:border-slate-700 dark:bg-slate-900/60">
                        <div class="flex items-center gap-3">
                            <span
                                class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-emerald-100 text-xl dark:bg-emerald-900/50">
                                🏡
                            </span>
                            <div>
                                <h2 class="text-base font-bold text-slate-900 sm:text-lg dark:text-slate-100">Thông tin Homestay</h2>
                                <p class="mt-0.5 text-sm text-slate-500 dark:text-slate-400">Nơi lưu trú được khách hàng đánh giá</p>
                            </div>
                        </div>
                    </div>

                    <div class="p-5 sm:p-6">
                        <dl class="grid gap-4 sm:grid-cols-2">
                            <div class="rounded-xl bg-slate-50 p-4 sm:col-span-2 dark:bg-slate-900">
                                <dt class="text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">Tên Homestay</dt>
                                <dd class="mt-1.5 text-base font-bold text-slate-900 sm:text-lg dark:text-slate-100">
                                    {{ $homestay?->name ?? 'Homestay không tồn tại' }}
                                </dd>
                            </div>

                            <div class="rounded-xl bg-slate-50 p-4 dark:bg-slate-900">
                                <dt class="text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">Phòng</dt>
                                <dd class="mt-1.5 font-semibold text-slate-900 dark:text-slate-100">
                                    {{ $room?->name ?? 'Không xác định' }}
                                </dd>
                            </div>

                            <div class="rounded-xl bg-slate-50 p-4 dark:bg-slate-900">
                                <dt class="text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">Loại phòng</dt>
                                <dd class="mt-1.5 font-semibold text-slate-900 dark:text-slate-100">
                                    {{ $room?->room_type ?? 'Không xác định' }}
                                </dd>
                            </div>

                            <div class="rounded-xl bg-slate-50 p-4 dark:bg-slate-900">
                                <dt class="text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">Ngày nhận phòng
                                </dt>
                                <dd class="mt-1.5 font-semibold text-slate-900 dark:text-slate-100">
                                    {{ $booking?->check_in?->format('d/m/Y') ?? 'Không xác định' }}
                                </dd>
                            </div>

                            <div class="rounded-xl bg-slate-50 p-4 dark:bg-slate-900">
                                <dt class="text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">Ngày trả phòng
                                </dt>
                                <dd class="mt-1.5 font-semibold text-slate-900 dark:text-slate-100">
                                    {{ $booking?->check_out?->format('d/m/Y') ?? 'Không xác định' }}
                                </dd>
                            </div>

                            <div class="rounded-xl bg-slate-50 p-4 sm:col-span-2 dark:bg-slate-900">
                                <dt class="text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">Địa chỉ</dt>
                                <dd class="mt-1.5 font-semibold leading-6 text-slate-700 dark:text-slate-300">
                                    {{ $homestay?->address ?? 'Chưa cập nhật địa chỉ' }},
                                    {{ $homestay?->city ?? 'Chưa cập nhật thành phố' }}
                                </dd>
                            </div>
                        </dl>
                    </div>
                </section>
            </div>

            {{-- ==================== CỘT PHẢI ==================== --}}
            <aside class="min-w-0 space-y-6">

                {{-- Card 4: Thao tác nhanh --}}
                <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-800">
                    <div class="border-b border-slate-200 bg-slate-50/80 px-5 py-4 sm:px-6 dark:border-slate-700 dark:bg-slate-900/60">
                        <div class="flex items-center gap-3">
                            <span
                                class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-100 text-xl dark:bg-blue-900/50">
                                ⚙️
                            </span>
                            <div>
                                <h2 class="text-base font-bold text-slate-900 sm:text-lg dark:text-slate-100">Thao tác nhanh</h2>
                                <p class="mt-0.5 text-sm text-slate-500 dark:text-slate-400">Kiểm duyệt đánh giá</p>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-3 p-5 sm:p-6">
                        @if ($review->status === 'pending')
                            {{-- Duyệt --}}
                            <form method="POST" action="{{ route('admin.reviews.update-status', $review) }}">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="approved">
                                <button type="submit"
                                    onclick="return confirm('Bạn có chắc muốn duyệt đánh giá này không?')"
                                    class="inline-flex cursor-pointer w-full items-center justify-center gap-2 rounded-xl bg-emerald-600 px-5 h-11 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700 focus:outline-none focus:ring-4 focus:ring-emerald-100 dark:focus:ring-emerald-900/40">
                                    <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M20 6L9 17l-5-5" />
                                    </svg>
                                    Duyệt đánh giá
                                </button>
                            </form>

                            {{-- Ẩn --}}
                            <form method="POST" action="{{ route('admin.reviews.update-status', $review) }}">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="hidden">
                                <button type="submit" onclick="return confirm('Bạn có chắc muốn ẩn đánh giá này không?')"
                                    class="inline-flex cursor-pointer w-full items-center justify-center gap-2 rounded-xl border border-red-200 bg-red-50 px-5 h-11 text-sm font-semibold text-red-600 transition hover:border-red-300 hover:bg-red-100 hover:text-red-700 focus:outline-none focus:ring-4 focus:ring-red-100 dark:border-red-800 dark:bg-red-950/40 dark:text-red-400 dark:hover:bg-red-900/50 dark:focus:ring-red-900/40">
                                    <svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path
                                            d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24" />
                                        <line x1="1" y1="1" x2="23" y2="23" />
                                    </svg>
                                    Ẩn đánh giá
                                </button>
                            </form>
                        @elseif ($review->status === 'approved')
                            <div
                                class="rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm leading-6 text-emerald-700 dark:border-emerald-800 dark:bg-emerald-950/40 dark:text-emerald-300">
                                Đánh giá này đã được duyệt và đang hiển thị công khai.
                            </div>

                            <form method="POST" action="{{ route('admin.reviews.update-status', $review) }}">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="hidden">
                                <button type="submit" onclick="return confirm('Bạn có chắc muốn ẩn đánh giá này không?')"
                                    class="inline-flex cursor-pointer h-11 w-full items-center justify-center gap-2 rounded-xl bg-red-600 px-5 text-sm font-semibold text-white shadow-sm transition hover:bg-red-700 focus:outline-none focus:ring-4 focus:ring-red-200 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900/40">
                                    <svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path
                                            d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24" />
                                        <line x1="1" y1="1" x2="23" y2="23" />
                                    </svg>
                                    Ẩn đánh giá
                                </button>
                            </form>
                        @elseif ($review->status === 'hidden')
                            <div class="rounded-xl border border-red-200 bg-red-50 p-4 text-sm leading-6 text-red-700 dark:border-red-800 dark:bg-red-950/40 dark:text-red-300">
                                Đánh giá này đang bị ẩn và không hiển thị cho khách hàng.
                            </div>

                            <form method="POST" action="{{ route('admin.reviews.update-status', $review) }}">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="approved">
                                <button type="submit"
                                    onclick="return confirm('Bạn có chắc muốn hiển thị lại đánh giá này không?')"
                                    class="inline-flex cursor-pointer w-full items-center justify-center gap-2 rounded-xl bg-emerald-600 px-5 h-11 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700 focus:outline-none focus:ring-4 focus:ring-emerald-100 dark:focus:ring-emerald-900/40">
                                    <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="3"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M20 6L9 17l-5-5" />
                                    </svg>
                                    Hiển thị lại
                                </button>
                            </form>
                        @endif
                    </div>
                </section>

                {{-- Card 5: Thông tin hệ thống --}}
                <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-800">
                    <div class="border-b border-slate-200 bg-slate-50/80 px-5 py-4 sm:px-6 dark:border-slate-700 dark:bg-slate-900/60">
                        <div class="flex items-center gap-3">
                            <span
                                class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-indigo-100 text-xl dark:bg-indigo-900/50">
                                📋
                            </span>
                            <h2 class="text-base font-bold text-slate-900 sm:text-lg dark:text-slate-100">Thông tin hệ thống</h2>
                        </div>
                    </div>

                    <div class="divide-y divide-slate-100 px-5 sm:px-6">
                        <div class="flex items-center justify-between gap-3 h-11.5">
                            <dt class="text-sm text-slate-500 dark:text-slate-400">Mã đánh giá</dt>
                            <dd class="font-semibold text-slate-900 dark:text-slate-100">#{{ $review->id }}</dd>
                        </div>

                        <div class="flex items-center justify-between gap-3 h-11.5">
                            <dt class="text-sm text-slate-500 dark:text-slate-400">Trạng thái</dt>
                            <dd class="font-semibold {{ $currentStatus['text'] }}">
                                {{ $statusLabels[$review->status] ?? 'Không xác định' }}
                            </dd>
                        </div>

                        <div class="flex items-center justify-between gap-3 h-11.5">
                            <dt class="text-sm text-slate-500 dark:text-slate-400">Số lần đánh giá</dt>
                            <dd class="font-semibold text-slate-900 dark:text-slate-100">{{ $review->review_number }}</dd>
                        </div>

                        <div class="flex items-start justify-between gap-3 h-11.5">
                            <dt class="text-sm text-slate-500 dark:text-slate-400">Ngày tạo</dt>
                            <dd class="text-right font-semibold text-slate-900 dark:text-slate-100">
                                {{ $review->created_at->format('H:i · d/m/Y') }}
                            </dd>
                        </div>

                        <div class="flex items-start justify-between gap-3 h-11.5">
                            <dt class="text-sm text-slate-500 dark:text-slate-400">Cập nhật lần cuối</dt>
                            <dd class="text-right font-semibold text-slate-900 dark:text-slate-100">
                                {{ $review->updated_at->format('H:i · d/m/Y') }}
                            </dd>
                        </div>
                    </div>
                </section>
            </aside>
        </div>
    </div>
@endsection