@php
    $statusLabels = [
        'pending' => 'Chờ xác nhận',
        'confirmed' => 'Đã xác nhận',
        'checked_in' => 'Đang lưu trú',
        'completed' => 'Đã hoàn thành',
        'cancelled' => 'Đã hủy',
    ];

    $statusClasses = [
        'pending' => 'border-amber-200 bg-amber-50 text-amber-700',
        'confirmed' => 'border-blue-200 bg-blue-50 text-blue-700',
        'checked_in' => 'border-violet-200 bg-violet-50 text-violet-700',
        'completed' => 'border-emerald-200 bg-emerald-50 text-emerald-700',
        'cancelled' => 'border-red-200 bg-red-50 text-red-700',
    ];

    $filterTabs = [
        '' => ['label' => 'Tất cả đơn', 'color' => 'slate'],
        'pending' => ['label' => 'Chờ xác nhận', 'color' => 'amber'],
        'need_payment' => ['label' => 'Chờ thanh toán', 'color' => 'gray'],
        'confirmed' => ['label' => 'Đã xác nhận', 'color' => 'blue'],
        'checked_in' => ['label' => 'Đang lưu trú', 'color' => 'violet'],
        'completed' => ['label' => 'Đã hoàn thành', 'color' => 'emerald'],
        'cancelled' => ['label' => 'Đã hủy', 'color' => 'red'],
        'needs_review' => ['label' => 'Cần đánh giá', 'color' => 'orange'],
    ];

    $filterActive = [
        'slate' => 'border-slate-900 bg-slate-900 text-white shadow-sm',
        'amber' => 'border-amber-500 bg-amber-500 text-white shadow-sm',
        'blue' => 'border-blue-600 bg-blue-600 text-white shadow-sm',
        'violet' => 'border-violet-600 bg-violet-600 text-white shadow-sm',
        'emerald' => 'border-emerald-600 bg-emerald-600 text-white shadow-sm',
        'red' => 'border-red-600 bg-red-600 text-white shadow-sm',
        'gray' => 'border-gray-500 bg-gray-500 text-white shadow-sm',
        'orange' => 'border-orange-600 bg-orange-600 text-white shadow-sm',
    ];

    $filterInactive = [
        'slate' => 'border-slate-200 bg-white text-slate-600 hover:border-slate-300 hover:bg-slate-50',
        'amber' => 'border-amber-200 bg-amber-50 text-amber-700 hover:border-amber-300 hover:bg-amber-100',
        'blue' => 'border-blue-200 bg-blue-50 text-blue-700 hover:border-blue-300 hover:bg-blue-100',
        'violet' => 'border-violet-200 bg-violet-50 text-violet-700 hover:border-violet-300 hover:bg-violet-100',
        'emerald' => 'border-emerald-200 bg-emerald-50 text-emerald-700 hover:border-emerald-300 hover:bg-emerald-100',
        'red' => 'border-red-200 bg-red-50 text-red-700 hover:border-red-300 hover:bg-red-100',
        'gray' => 'border-gray-200 bg-gray-100 text-gray-700 hover:border-gray-400 hover:bg-gray-200',
        'orange' => 'border-orange-200 bg-orange-50 text-orange-700 hover:border-orange-300 hover:bg-orange-100',
    ];

    $currentFilter = request('filter', '');

    $paymentStatusLabels = [
        'unpaid' => 'Chưa thanh toán',
        'pending' => 'Đang xử lý',
        'paid' => 'Đã thanh toán',
        'refunded' => 'Đã hoàn tiền',
        'failed' => 'Thanh toán thất bại',
    ];

    $paymentStatusClasses = [
        'unpaid' => 'border-slate-200 bg-slate-100 text-slate-700',
        'pending' => 'border-amber-200 bg-amber-50 text-amber-700',
        'paid' => 'border-emerald-200 bg-emerald-50 text-emerald-700',
        'refunded' => 'border-blue-200 bg-blue-50 text-blue-700',
        'failed' => 'border-red-200 bg-red-50 text-red-700',
    ];
@endphp

@extends('layouts.app')

@section('title', 'Lịch sử đặt phòng | HomeStayGo')

@section('content')

    <x-alert />

    <main>

        <x-frontend-breadcrumb :items="[
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
            ],
        ]" />

        <section class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">

            <div class="mb-8 flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">

                <div>
                    <p class="font-semibold uppercase tracking-widest text-blue-600">
                        Đơn của bạn
                    </p>

                    <h1 class="mt-2 text-3xl font-bold tracking-tight text-slate-950 sm:text-4xl">
                        Lịch sử đặt phòng
                    </h1>

                    <p class="mt-3 text-slate-500">
                        Theo dõi trạng thái, xem đánh giá và dễ dàng đặt lại phòng đã từng lưu trú.
                    </p>
                </div>

                @if ($bookings->total() > 0)
                    <div class="rounded-2xl border border-blue-100 bg-blue-50 px-5 py-3">
                        <p class="text-sm font-semibold text-blue-600">
                            Số đơn:
                        </p>

                        <p class="mt-1 text-2xl font-bold text-blue-700">
                            {{ $bookings->total() }}
                        </p>
                    </div>
                @endif

            </div>

            <div class="overflow-x-auto -mx-4 px-4 sm:mx-0 sm:px-0">
                <div class="flex min-w-max gap-2 pb-2">
                    @foreach ($filterTabs as $value => $tab)
                        @php
                            $isActive = $currentFilter === $value;
                            $color = $tab['color'];
                            $tabUrl =
                                $value === ''
                                    ? route('bookings.history')
                                    : route('bookings.history', ['filter' => $value]);
                        @endphp
                        <a href="{{ $tabUrl }}"
                            class="inline-flex items-center justify-center whitespace-nowrap rounded-md border px-4 py-2.5 text-sm font-semibold transition
                                {{ $isActive ? $filterActive[$color] : $filterInactive[$color] }}"
                            @if ($isActive) aria-current="page" @endif>
                            {{ $tab['label'] }}
                        </a>
                    @endforeach
                </div>
            </div>

            @if ($bookings->isEmpty())

                <div
                    class="mt-8 rounded-3xl border border-dashed border-slate-300 bg-white px-6 py-16 text-center shadow-sm">

                    <div class="text-6xl">
                        📅
                    </div>

                    <h2 class="mt-5 text-2xl font-bold text-slate-900">
                        @if (request()->filled('filter'))
                            Không tìm thấy đơn phù hợp
                        @else
                            Bạn chưa có đơn đặt phòng
                        @endif
                    </h2>

                    <p class="mx-auto mt-3 max-w-md text-slate-500">
                        @if (request()->filled('filter'))
                            Hãy thay đổi bộ lọc để xem thêm các đơn đặt phòng khác.
                        @else
                            Hãy tìm một Homestay phù hợp và bắt đầu chuyến đi của bạn.
                        @endif
                    </p>

                    <a href="{{ request()->filled('filter') ? route('bookings.history') : route('home') }}"
                        class="mt-7 inline-flex items-center justify-center rounded-xl bg-blue-600 px-6 py-3 text-sm font-semibold text-white transition hover:bg-blue-700">
                        {{ request()->filled('filter') ? 'Xem tất cả đơn' : 'Khám phá Homestay' }}
                    </a>

                </div>
            @else
                {{-- Card --}}
                <div class="mt-8 space-y-5">

                    @foreach ($bookings as $booking)
                        @php
                            $review = $booking->reviews->first();
                            $room = $booking->room;
                            $homestay = $room?->homestay;
                            $canReview = $booking->status === 'completed' && !$review && $homestay;
                            $canRebook = $room && $room->status === 'available' && $homestay && $homestay->status;
                            $canPay = $booking->status !== 'cancelled' && in_array($booking->payment_status, ['unpaid', 'pending', 'failed'], true);
                            $paymentButtonLabel = match ($booking->payment_status) {
                                'pending' => 'Tiếp tục thanh toán',
                                'failed' => 'Thanh toán lại',
                                default => 'Thanh toán VNPAY',
                            };
                            $statusClass =
                                $statusClasses[$booking->status] ?? 'border-slate-200 bg-slate-100 text-slate-700';
                            $statusLabel = $statusLabels[$booking->status] ?? $booking->status;
                        @endphp

                        <article
                            class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm transition hover:shadow-md">
                            <div class="grid lg:grid-cols-[220px_minmax(0,1fr)]">
                                {{-- Ảnh --}}
                                <div class="bg-slate-100">
                                    @if ($room?->image)
                                        <img src="{{ Storage::url($room->image) }}" alt="{{ $room->name }}"
                                            class="h-56 w-full object-cover lg:h-full" loading="lazy">
                                    @else
                                        <div class="flex h-56 items-center justify-center lg:h-full">
                                            <div class="text-center">
                                                <div class="text-5xl">🚪</div>
                                                <p class="mt-4 text-sm text-slate-400">Chưa có ảnh phòng</p>
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                {{-- Nội dung --}}
                                <div class="p-6 sm:p-7">
                                    <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                                        <div class="min-w-0">
                                            <div class="flex flex-wrap items-center gap-3">
                                                <span
                                                    class="text-sm font-medium text-slate-500">{{ $booking->booking_code }}
                                                </span>

                                                <span
                                                    class="rounded-full border px-3 py-1 text-xs font-semibold {{ $statusClass }}">
                                                    {{ $statusLabel }}
                                                </span>

                                                <span
                                                    class="rounded-full border px-3 py-1 text-xs font-semibold
                                                        {{ $paymentStatusClasses[$booking->payment_status]
                                                            ?? 'border-slate-200 bg-slate-100 text-slate-700' }}"
                                                >
                                                    {{ $paymentStatusLabels[$booking->payment_status]
                                                        ?? $booking->payment_status }}
                                                </span>

                                                @if ($review?->status === 'approved')
                                                    <button type="button" data-open-view-review
                                                        data-review-id="{{ $review->id }}"
                                                        data-review-number="{{ $review->review_number }}"
                                                        data-review-rating="{{ $review->rating }}"
                                                        data-review-title="{{ $review->title }}"
                                                        data-review-content="{{ $review->content }}"
                                                        data-review-homestay="{{ $homestay?->name }}"
                                                        data-booking-id="{{ $booking->id }}"
                                                        data-booking-code="{{ $booking->booking_code }}"
                                                        data-review-update-action="{{ url('/reviews/' . $review->id) }}"
                                                        class="inline-flex cursor-pointer items-center justify-center gap-2 rounded-full border border-emerald-200 bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700 transition hover:border-emerald-300 hover:bg-emerald-100">
                                                        <x-icon-star class="h-4 w-4 text-amber-400" />
                                                        Xem đánh giá
                                                    </button>
                                                @endif
                                            </div>

                                            <h2 class="mt-4 text-xl font-bold text-slate-900">
                                                {{ $room?->name ?? 'Phòng không còn tồn tại' }}
                                            </h2>

                                            @if ($homestay)
                                                <a href="{{ route('homestays.show', $homestay->slug) }}"
                                                    class="mt-1 inline-block font-medium text-blue-600 transition hover:text-blue-800 hover:underline">
                                                    {{ $homestay->name }}
                                                </a>
                                            @else
                                                <p class="mt-1 font-medium text-slate-400">Homestay không còn tồn tại
                                                </p>
                                            @endif
                                        </div>

                                        <div class="shrink-0 sm:text-right">
                                            <p class="text-sm text-slate-500">Tổng tiền</p>
                                            <p class="mt-1 text-2xl font-bold text-blue-600">
                                                {{ number_format($booking->total_price, 0, ',', '.') }}đ
                                            </p>
                                        </div>
                                    </div>

                                    <div class="mt-6 grid grid-cols-2 gap-3 sm:grid-cols-4">
                                        @foreach ([['Nhận phòng', $booking->check_in->format('d/m/Y')], ['Trả phòng', $booking->check_out->format('d/m/Y')], ['Số đêm', $booking->number_of_nights . ' đêm'], ['Số khách', $booking->number_of_guests . ' khách']] as [$label, $value])
                                            <div class="rounded-xl bg-slate-50 p-3">
                                                <p class="text-xs text-slate-500">{{ $label }}</p>
                                                <p class="mt-1 text-sm font-semibold text-slate-700">
                                                    {{ $value }}</p>
                                            </div>
                                        @endforeach
                                    </div>

                                    <div
                                        class="mt-6 flex flex-col gap-4 border-t border-slate-100 pt-5 xl:flex-row xl:items-center xl:justify-between">
                                        <p class="shrink-0 text-sm text-slate-500">
                                            Đặt lúc {{ $booking->created_at->format('H:i d/m/Y') }}
                                        </p>

                                        <div class="flex flex-wrap items-center justify-end gap-3">
                                            @if ($canReview)
                                                <button type="button" data-open-review-modal
                                                    data-booking-id="{{ $booking->id }}"
                                                    data-booking-code="{{ $booking->booking_code }}"
                                                    data-homestay-name="{{ $homestay->name }}"
                                                    data-review-action="{{ route('reviews.store', $booking) }}"
                                                    class="inline-flex cursor-pointer items-center justify-center gap-2 rounded-xl border border-orange-300 bg-orange-50 px-4 py-3 text-sm font-semibold text-orange-700 transition hover:border-orange-400 hover:bg-orange-100">
                                                    <x-icon-star class="h-4 w-4 text-amber-400" />
                                                    Viết đánh giá
                                                </button>
                                            @endif

                                            <a href="{{ route('bookings.show', $booking) }}"
                                                class="inline-flex items-center justify-center rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm font-semibold text-slate-700 transition hover:border-blue-600 hover:bg-blue-50 hover:text-blue-600">
                                                Xem chi tiết
                                            </a>

                                            @if ($canPay)
                                                <a
                                                    href="{{ route('bookings.payment.show', $booking) }}"
                                                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-200"
                                                >
                                                    {{ $paymentButtonLabel }}
                                                </a>
                                            @endif

                                            @if ($canRebook)
                                                <a href="{{ route('bookings.create', $room) }}"
                                                    class="inline-flex items-center justify-center rounded-xl bg-blue-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-blue-700 focus:ring-4 focus:ring-blue-200">
                                                    Đặt phòng lại
                                                </a>
                                            @else
                                                <span
                                                    class="inline-flex cursor-not-allowed items-center justify-center rounded-xl bg-slate-100 px-4 py-3 text-sm font-semibold text-slate-400">
                                                    Phòng ngừng nhận
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </article>
                    @endforeach

                </div>

                @if ($bookings->hasPages())
                    <div class="mt-10 border-t border-slate-200 pt-5">
                        {{ $bookings
                            ->appends(request()->query())
                            ->onEachSide(1)
                            ->links('components.pagination', [
                                'layout' => 'row',
                                'showInfo' => true,
                            ]) }}
                    </div>
                @endif

            @endif

        </section>

        {{-- Modal viết đánh giá --}}
        <div id="history-review-modal" class="fixed inset-0 z-[100] hidden items-center justify-center p-4"
            role="dialog" aria-modal="true" aria-labelledby="history-review-title">
            <button type="button" data-close-history-review
                class="absolute inset-0 cursor-default bg-slate-950/45 backdrop-blur-sm"
                aria-label="Đóng modal"></button>

            <div
                class="relative z-10 max-h-[90vh] w-full max-w-md overflow-y-auto rounded-3xl bg-white p-6 shadow-2xl sm:p-7">

                <div class="flex items-start justify-between gap-4">
                    <div class="min-w-0">
                        <h2 id="history-review-title" class="text-xl font-bold text-slate-900">
                            Đánh giá Homestay
                        </h2>

                        <p id="history-review-homestay" class="mt-1 truncate text-sm font-semibold text-slate-500">
                        </p>

                        <p id="history-review-booking" class="mt-1 text-xs font-semibold text-blue-600"></p>
                    </div>

                    <button type="button" data-close-history-review
                        class="flex h-9 w-9 shrink-0 cursor-pointer items-center justify-center rounded-full bg-slate-100 p-2 text-slate-500 transition hover:bg-red-50 hover:text-red-500"
                        aria-label="Đóng">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"
                            stroke-linecap="round">
                            <path d="M18 6L6 18M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form id="history-review-form" method="POST" action="" class="mt-6">
                    @csrf

                    <input type="hidden" name="_method" id="history-review-method" value="PUT" disabled>

                    <input type="hidden" name="review_mode" id="history-review-mode"
                        value="{{ old('review_mode', 'create') }}">

                    <input type="hidden" name="review_id" id="history-review-id" value="{{ old('review_id') }}">

                    <input type="hidden" name="review_booking_id" id="history-review-booking-id"
                        value="{{ old('review_booking_id') }}">

                    <div class="mt-5 text-center">
                        <label class="text-sm font-semibold text-slate-700">
                            Mức độ hài lòng
                            <span class="text-red-500">*</span>
                        </label>

                        <input type="hidden" name="rating" id="history-review-rating"
                            value="{{ old('rating', 0) }}">

                        <div class="mt-3 flex justify-center gap-2">
                            @for ($star = 1; $star <= 5; $star++)
                                <button type="button" data-history-rating="{{ $star }}"
                                    class="group cursor-pointer rounded-xl p-1 transition focus:outline-none"
                                    aria-label="{{ $star }} sao">
                                    <x-icon-star
                                        class="history-review-star h-9 w-9 text-slate-200 transition duration-150 group-hover:scale-110 sm:h-10 sm:w-10" />
                                </button>
                            @endfor
                        </div>

                        <p id="history-review-rating-label" class="mt-2 text-sm font-semibold text-slate-400">
                            Chọn số sao
                        </p>

                        @error('rating')
                            <p class="mt-2 text-sm font-medium text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="mt-4">
                        <div class="flex items-center justify-between gap-3">
                            <label for="history-review-content" class="text-sm font-semibold text-slate-700">
                                Nội dung đánh giá
                                <span class="text-red-500">*</span>
                            </label>

                            <span id="history-review-count" class="text-xs text-slate-400">
                                0/1000
                            </span>
                        </div>

                        <textarea id="history-review-content" name="content" rows="4" minlength="10" maxlength="1000"
                            placeholder="Chia sẻ cảm nhận của bạn..."
                            class="mt-2 w-full resize-none rounded-xl border border-slate-300 px-4 py-3 text-sm leading-6 outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:ring-4 focus:ring-blue-100">{{ old('content') }}</textarea>

                        @error('content')
                            <p class="mt-2 text-sm font-medium text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="mt-6 grid grid-cols-2 gap-3">
                        <button type="button" data-close-history-review
                            class="cursor-pointer rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-100">
                            Để sau
                        </button>

                        <button type="submit" id="history-review-submit"
                            class="cursor-pointer rounded-xl bg-blue-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-blue-700 focus:ring-4 focus:ring-blue-200">
                            Gửi đánh giá
                        </button>
                    </div>
                </form>

            </div>
        </div>

        {{-- Modal xem đánh giá đã duyệt --}}
        <div id="view-review-modal" class="fixed inset-0 z-[110] hidden items-center justify-center p-4"
            role="dialog" aria-modal="true" aria-labelledby="view-review-title">
            <button type="button" data-close-view-review
                class="absolute inset-0 cursor-default bg-slate-950/45 backdrop-blur-sm"
                aria-label="Đóng modal"></button>

            <div class="relative z-10 w-full max-w-lg rounded-3xl bg-white p-6 shadow-2xl sm:p-7">

                <div class="flex items-start justify-between gap-4">
                    <div class="min-w-0">
                        <h2 id="view-review-title" class="text-2xl font-bold text-slate-900">
                            Đánh giá của bạn
                        </h2>

                        <p id="view-review-homestay" class="mt-1 truncate text-sm font-semibold text-blue-600"></p>

                        <p id="view-review-booking" class="mt-1 text-xs text-slate-400"></p>
                    </div>

                    <button type="button" data-close-view-review
                        class="flex h-9 w-9 shrink-0 cursor-pointer items-center justify-center rounded-full bg-slate-100 p-2 text-slate-500 transition hover:bg-red-50 hover:text-red-500"
                        aria-label="Đóng">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"
                            stroke-linecap="round">
                            <path d="M18 6L6 18M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div id="view-review-stars" class="mt-5 flex gap-1 items-center justify-center"></div>
                
                <span id="view-review-number" class="hidden"></span>

                <p id="view-review-content" class="mt-3 rounded-xl bg-slate-50 p-5 text-sm font-semibold leading-7 text-slate-700"></p>

                <p id="view-review-limit-message" class="mt-4 text-sm text-slate-500"></p>

                <div class="mt-6 flex flex-col gap-3 sm:flex-row">
                    <button type="button" id="view-review-edit-button"
                        class="hidden flex-1 cursor-pointer items-center justify-center rounded-xl border border-orange-300 bg-orange-50 px-5 py-3 text-sm font-semibold text-orange-700 transition hover:border-orange-400 hover:bg-orange-100">
                        Sửa đánh giá
                    </button>

                    <button type="button" data-close-view-review
                        class="inline-flex flex-1 cursor-pointer items-center justify-center rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-blue-700">
                        Đóng
                    </button>
                </div>

            </div>
        </div>

    </main>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const $ = (sel, ctx = document) => ctx.querySelector(sel);
            const $$ = (sel, ctx = document) => [...ctx.querySelectorAll(sel)];

            const reviewModal = $('#history-review-modal');
            const viewReviewModal = $('#view-review-modal');
            const reviewForm = $('#history-review-form');
            const ratingInput = $('#history-review-rating');
            const ratingLabel = $('#history-review-rating-label');
            const contentInput = $('#history-review-content');
            const contentCount = $('#history-review-count');
            const reviewSubmitBtn = $('#history-review-submit');
            const reviewModalTitle = $('#history-review-title');
            const reviewMethod = $('#history-review-method');
            const reviewMode = $('#history-review-mode');
            const reviewId = $('#history-review-id');
            const bookingIdInput = $('#history-review-booking-id');
            const bookingText = $('#history-review-booking');
            const homestayText = $('#history-review-homestay');
            const ratingButtons = $$('[data-history-rating]');

            const viewHomestay = $('#view-review-homestay');
            const viewBooking = $('#view-review-booking');
            const viewStars = $('#view-review-stars');
            const viewContent = $('#view-review-content');
            const viewNumber = $('#view-review-number');
            const viewLimitMsg = $('#view-review-limit-message');
            const viewEditBtn = $('#view-review-edit-button');

            let activeViewBtn = null;

            const ratingMessages = {
                0: 'Chọn số sao',
                1: 'Rất không hài lòng',
                2: 'Chưa hài lòng',
                3: 'Bình thường',
                4: 'Hài lòng',
                5: 'Tuyệt vời',
            };

            const toggleBodyScroll = () => {
                const open = !reviewModal.classList.contains('hidden') ||
                    !viewReviewModal.classList.contains('hidden');
                document.body.classList.toggle('overflow-hidden', open);
            };

            const showModal = (el) => {
                el.classList.remove('hidden');
                el.classList.add('flex');
                toggleBodyScroll();
            };

            const hideModal = (el) => {
                el.classList.add('hidden');
                el.classList.remove('flex');
                toggleBodyScroll();
            };

            const renderStars = (value) => {
                ratingButtons.forEach(btn => {
                    const star = Number(btn.dataset.historyRating);
                    const icon = btn.querySelector('.history-review-star');
                    const active = star <= value;
                    icon.classList.toggle('text-amber-400', active);
                    icon.classList.toggle('text-slate-200', !active);
                    icon.classList.toggle('scale-110', active);
                });
                ratingLabel.textContent = ratingMessages[value] ?? ratingMessages[0];
                ratingLabel.classList.toggle('text-amber-600', value > 0);
                ratingLabel.classList.toggle('text-slate-400', value === 0);
            };

            const updateCount = () => {
                contentCount.textContent = `${contentInput.value.length}/1000`;
            };

            const openCreateReview = (btn, preserve = false) => {
                if (!preserve) {
                    reviewForm.reset();
                    contentInput.value = '';
                    ratingInput.value = 0;
                    renderStars(0);
                }

                reviewForm.action = btn.dataset.reviewAction;
                reviewMethod.disabled = true;
                reviewMode.value = 'create';
                reviewId.value = '';
                bookingIdInput.value = btn.dataset.bookingId;
                bookingText.textContent = `Mã Booking: ${btn.dataset.bookingCode}`;
                homestayText.textContent = btn.dataset.homestayName;
                reviewModalTitle.textContent = 'Đánh giá Homestay';
                reviewSubmitBtn.textContent = 'Gửi đánh giá';

                showModal(reviewModal);
                updateCount();
            };

            const openEditReview = (btn, preserve = false) => {
                const reviewNumber = Number(btn.dataset.reviewNumber || 1);
                if (reviewNumber >= 2) return;

                if (!preserve) {
                    contentInput.value = btn.dataset.reviewContent || '';
                    ratingInput.value = Number(btn.dataset.reviewRating || 0);
                }

                reviewForm.action = btn.dataset.reviewUpdateAction;
                reviewMethod.disabled = false;
                reviewMethod.value = 'PUT';
                reviewMode.value = 'edit';
                reviewId.value = btn.dataset.reviewId;
                bookingIdInput.value = btn.dataset.bookingId;
                bookingText.textContent = `Mã Booking: ${btn.dataset.bookingCode}`;
                homestayText.textContent = btn.dataset.reviewHomestay;
                reviewModalTitle.textContent = 'Sửa đánh giá';
                reviewSubmitBtn.textContent = 'Lưu chỉnh sửa';

                renderStars(Number(ratingInput.value || 0));
                hideModal(viewReviewModal);
                showModal(reviewModal);
                updateCount();
            };

            const renderApprovedStars = (rating) => {
                viewStars.innerHTML = '';
                for (let i = 1; i <= 5; i++) {
                    const svg = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
                    svg.setAttribute('viewBox', '0 0 24 24');
                    svg.setAttribute('fill', i <= rating ? 'currentColor' : 'none');
                    svg.setAttribute('stroke', 'currentColor');
                    svg.setAttribute('stroke-width', '1.8');
                    svg.classList.add('h-7', 'w-7', i <= rating ? 'text-amber-400' : 'text-slate-200');

                    const path = document.createElementNS('http://www.w3.org/2000/svg', 'path');
                    path.setAttribute('stroke-linecap', 'round');
                    path.setAttribute('stroke-linejoin', 'round');
                    path.setAttribute('d',
                        'M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.562.562 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.562.562 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z'
                    );
                    svg.appendChild(path);
                    viewStars.appendChild(svg);
                }
            };

            const openViewReview = (btn) => {
                activeViewBtn = btn;
                const rating = Number(btn.dataset.reviewRating || 0);
                const reviewNumber = Number(btn.dataset.reviewNumber || 1);
                const canEdit = reviewNumber < 2;

                viewHomestay.textContent = btn.dataset.reviewHomestay || 'Homestay';
                viewBooking.textContent = `Mã Booking: ${btn.dataset.bookingCode || ''}`;
                viewContent.textContent = btn.dataset.reviewContent || 'Không có nội dung đánh giá.';
                viewNumber.textContent = `Lần ${reviewNumber}/2`;
                viewLimitMsg.textContent = canEdit ?
                    'Bạn còn 1 lần sửa đánh giá.' :
                    'Không thể chỉnh sửa đánh giá.';

                viewEditBtn.classList.toggle('hidden', !canEdit);
                viewEditBtn.classList.toggle('inline-flex', canEdit);

                renderApprovedStars(rating);
                showModal(viewReviewModal);
            };

            // Rating stars
            ratingButtons.forEach(btn => {
                const value = Number(btn.dataset.historyRating);
                btn.addEventListener('mouseenter', () => renderStars(value));
                btn.addEventListener('mouseleave', () => renderStars(Number(ratingInput.value || 0)));
                btn.addEventListener('click', () => {
                    ratingInput.value = value;
                    renderStars(value);
                });
            });

            // Open / close
            $$('[data-open-review-modal]').forEach(btn =>
                btn.addEventListener('click', () => openCreateReview(btn))
            );
            $$('[data-close-history-review]').forEach(btn =>
                btn.addEventListener('click', () => hideModal(reviewModal))
            );
            $$('[data-open-view-review]').forEach(btn =>
                btn.addEventListener('click', () => openViewReview(btn))
            );
            $$('[data-close-view-review]').forEach(btn =>
                btn.addEventListener('click', () => hideModal(viewReviewModal))
            );

            viewEditBtn.addEventListener('click', () => {
                if (activeViewBtn) openEditReview(activeViewBtn);
            });

            contentInput.addEventListener('input', updateCount);
            updateCount();

            document.addEventListener('keydown', (e) => {
                if (e.key !== 'Escape') return;
                if (!reviewModal.classList.contains('hidden')) hideModal(reviewModal);
                if (!viewReviewModal.classList.contains('hidden')) hideModal(viewReviewModal);
            });

            // Restore modal after validation errors
            const oldMode = @json(old('review_mode', 'create'));
            const oldReviewId = @json(old('review_id'));
            const oldBookingId = @json(old('review_booking_id'));
            const hasErrors = @json($errors->any());

            if (hasErrors) {
                if (oldMode === 'edit' && oldReviewId) {
                    const btn = $(`[data-open-view-review][data-review-id="${oldReviewId}"]`);
                    if (btn) openEditReview(btn, true);
                } else if (oldBookingId) {
                    const btn = $(`[data-open-review-modal][data-booking-id="${oldBookingId}"]`);
                    if (btn) {
                        openCreateReview(btn, true);
                        renderStars(Number(ratingInput.value || 0));
                    }
                }
            }
        });
    </script>

@endsection