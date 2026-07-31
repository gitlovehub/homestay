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
            'pending' => 'bg-amber-50 text-amber-700 border border-amber-200',
            'confirmed' => 'bg-blue-50 text-blue-700 border border-blue-200',
            'checked_in' => 'bg-violet-50 text-violet-700 border border-violet-200',
            'completed' => 'bg-emerald-50 text-emerald-700 border border-emerald-200',
            'cancelled' => 'bg-red-50 text-red-700 border border-red-200',
        ];

        $reviewStatusLabels = [
            'pending' => 'Đang chờ duyệt',
            'approved' => 'Đã đánh giá',
            'hidden' => 'Đánh giá bị ẩn',
        ];

        $reviewStatusClasses = [
            'pending' => 'border-amber-200 bg-amber-50 text-amber-700',
            'approved' => 'border-emerald-200 bg-emerald-50 text-emerald-700',
            'hidden' => 'border-slate-200 bg-slate-200 text-slate-600',
        ];
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
                ],
            ]"
        />

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

                                    @php
                                        $bookingReview = $booking->reviews->first();

                                        $canReview =
                                            $booking->status === 'completed'
                                            && $bookingReview === null
                                            && $booking->room?->homestay;
                                    @endphp

                                    <div class="mt-6 flex flex-col gap-3 border-t border-slate-100 pt-5 sm:flex-row sm:items-center sm:justify-between">

                                        <p class="text-sm text-slate-400">
                                            Đặt lúc {{ $booking->created_at->format('H:i d/m/Y') }}
                                        </p>

                                        <div class="flex flex-col gap-3 sm:flex-row sm:items-center">

                                            {{-- Đã gửi đánh giá --}}
                                            @if ($bookingReview)

                                                <span class="inline-flex items-center justify-center rounded-xl border px-4 py-3 text-sm font-semibold
                                                    {{ $reviewStatusClasses[$bookingReview->status]
                                                        ?? 'border-slate-200 bg-slate-100 text-slate-600' }}"
                                                >
                                                    {{ $reviewStatusLabels[$bookingReview->status]
                                                        ?? 'Đã đánh giá' }}
                                                </span>

                                            {{-- Đủ điều kiện đánh giá --}}
                                            @elseif ($canReview)

                                                <button
                                                    type="button"
                                                    data-open-review-modal
                                                    data-booking-id="{{ $booking->id }}"
                                                    data-booking-code="{{ $booking->booking_code }}"
                                                    data-homestay-name="{{ $booking->room->homestay->name }}"
                                                    data-review-action="{{ route('reviews.store', $booking) }}"
                                                    class="inline-flex cursor-pointer items-center justify-center gap-2 rounded-xl border border-slate-300 bg-white px-5 py-3 text-sm font-semibold text-slate-700 transition hover:border-amber-600 hover:text-amber-600"
                                                >
                                                    <x-icon-star class="h-4 w-4 text-amber-400" />

                                                    Đánh giá ngay
                                                </button>

                                            @endif

                                            <a
                                                href="{{ route('bookings.show', $booking) }}"
                                                class="inline-flex items-center justify-center rounded-xl border border-slate-300 bg-white px-5 py-3 text-sm font-semibold text-slate-700 transition hover:border-blue-600 hover:text-blue-600"
                                            >
                                                Xem chi tiết
                                            </a>

                                        </div>

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

        {{-- Modal đánh giá Booking --}}
        <div
            id="history-review-modal"
            class="fixed inset-0 z-100 hidden items-center justify-center p-4"
            role="dialog"
            aria-modal="true"
            aria-labelledby="history-review-title"
        >
            {{-- Nhấn vùng tối để đóng --}}
            <button
                type="button"
                data-close-history-review
                class="absolute inset-0 cursor-default backdrop-blur-sm"
                aria-label="Đóng modal"
            ></button>

            <div class="relative z-10 w-full max-w-md rounded-3xl bg-white p-6 shadow-2xl sm:p-7">

                <div class="flex items-start justify-between gap-4">

                    <div class="min-w-0">

                        <h2
                            id="history-review-title"
                            class="text-xl font-bold text-slate-900"
                        >
                            Đánh giá Homestay
                        </h2>

                        <p
                            id="history-review-homestay"
                            class="mt-1 truncate text-sm font-semibold text-slate-500"
                        ></p>

                        <p
                            id="history-review-booking"
                            class="mt-1 text-xs font-semibold text-blue-600"
                        ></p>

                    </div>

                    <button
                        type="button"
                        data-close-history-review
                        class="flex h-9 w-9 shrink-0 cursor-pointer items-center justify-center rounded-full p-2 bg-slate-100 text-slate-500 transition hover:bg-red-50 hover:text-red-500"
                        aria-label="Đóng"
                    >
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round">
                            <path d="M18 6L6 18M6 6l12 12"/>
                        </svg>
                    </button>

                </div>

                <form
                    id="history-review-form"
                    method="POST"
                    action=""
                    class="mt-6"
                >
                    @csrf

                    <input
                        type="hidden"
                        name="review_booking_id"
                        id="history-review-booking-id"
                        value="{{ old('review_booking_id') }}"
                    >

                    {{-- Chọn sao --}}
                    <div class="text-center">

                        <label class="text-sm font-semibold text-slate-700">
                            Mức độ hài lòng
                            <span class="text-red-500">*</span>
                        </label>

                        <input
                            type="hidden"
                            name="rating"
                            id="history-review-rating"
                            value="{{ old('rating', 0) }}"
                        >

                        <div class="mt-3 flex justify-center gap-2">

                            @for ($star = 1; $star <= 5; $star++)

                                <button
                                    type="button"
                                    data-history-rating="{{ $star }}"
                                    class="group cursor-pointer rounded-xl p-1 transition focus:outline-none"
                                    aria-label="{{ $star }} sao"
                                >
                                    <x-icon-star class="history-review-star h-9 w-9 text-slate-200 transition duration-150 group-hover:scale-110 sm:h-10 sm:w-10" />
                                </button>

                            @endfor

                        </div>

                        <p
                            id="history-review-rating-label"
                            class="mt-2 text-sm font-semibold text-slate-400"
                        >
                            Chọn số sao
                        </p>

                        @error('rating')
                            <p class="mt-2 text-sm font-medium text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>

                    {{-- Tiêu đề --}}
                    <div class="mt-5">

                        <label
                            for="history-review-input-title"
                            class="text-sm font-semibold text-slate-700"
                        >
                            Tiêu đề

                            <span class="font-normal text-slate-400">
                                (không bắt buộc)
                            </span>
                        </label>

                        <input
                            type="text"
                            id="history-review-input-title"
                            name="title"
                            value="{{ old('title') }}"
                            maxlength="150"
                            placeholder="Ví dụ: Trải nghiệm tuyệt vời"
                            class="mt-2 w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                        >

                        @error('title')
                            <p class="mt-2 text-sm font-medium text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>

                    {{-- Nội dung --}}
                    <div class="mt-4">

                        <div class="flex items-center justify-between gap-3">

                            <label
                                for="history-review-content"
                                class="text-sm font-semibold text-slate-700"
                            >
                                Nội dung đánh giá
                                <span class="text-red-500">*</span>
                            </label>

                            <span
                                id="history-review-count"
                                class="text-xs text-slate-400"
                            >
                                0/1000
                            </span>

                        </div>

                        <textarea
                            id="history-review-content"
                            name="content"
                            rows="4"
                            minlength="10"
                            maxlength="1000"
                            required
                            placeholder="Chia sẻ cảm nhận của bạn..."
                            class="mt-2 w-full resize-none rounded-xl border border-slate-300 px-4 py-3 text-sm leading-6 outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                        >{{ old('content') }}</textarea>

                        @error('content')
                            <p class="mt-2 text-sm font-medium text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>

                    <div class="mt-6 grid grid-cols-2 gap-3">

                        <button
                            type="button"
                            data-close-history-review
                            class="cursor-pointer rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50"
                        >
                            Để sau
                        </button>

                        <button
                            type="submit"
                            class="cursor-pointer rounded-xl bg-blue-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-blue-700 focus:ring-4 focus:ring-blue-200"
                        >
                            Gửi đánh giá
                        </button>

                    </div>

                </form>

            </div>

        </div>

    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const modal = document.getElementById(
                'history-review-modal'
            );

            const form = document.getElementById(
                'history-review-form'
            );

            const bookingInput = document.getElementById(
                'history-review-booking-id'
            );

            const bookingText = document.getElementById(
                'history-review-booking'
            );

            const homestayText = document.getElementById(
                'history-review-homestay'
            );

            const ratingInput = document.getElementById(
                'history-review-rating'
            );

            const ratingLabel = document.getElementById(
                'history-review-rating-label'
            );

            const ratingButtons = document.querySelectorAll(
                '[data-history-rating]'
            );

            const openButtons = document.querySelectorAll(
                '[data-open-review-modal]'
            );

            const closeButtons = document.querySelectorAll(
                '[data-close-history-review]'
            );

            const contentInput = document.getElementById(
                'history-review-content'
            );

            const contentCount = document.getElementById(
                'history-review-count'
            );

            const ratingMessages = {
                0: 'Chọn số sao',
                1: 'Rất không hài lòng',
                2: 'Chưa hài lòng',
                3: 'Bình thường',
                4: 'Hài lòng',
                5: 'Tuyệt vời',
            };

            function renderStars(value) {
                ratingButtons.forEach(function (button) {
                    const starValue = Number(
                        button.dataset.historyRating
                    );

                    const icon = button.querySelector(
                        '.history-review-star'
                    );

                    const active = starValue <= value;

                    icon.classList.toggle(
                        'text-amber-400',
                        active
                    );

                    icon.classList.toggle(
                        'text-slate-200',
                        !active
                    );

                    icon.classList.toggle(
                        'scale-110',
                        active
                    );
                });

                ratingLabel.textContent =
                    ratingMessages[value] ?? ratingMessages[0];

                ratingLabel.classList.toggle(
                    'text-amber-600',
                    value > 0
                );

                ratingLabel.classList.toggle(
                    'text-slate-400',
                    value === 0
                );
            }

            function openModal(button, preserveValues = false) {
                if (!preserveValues) {
                    form.reset();
                    ratingInput.value = 0;
                    renderStars(0);
                }

                form.action = button.dataset.reviewAction;

                bookingInput.value =
                    button.dataset.bookingId;

                bookingText.textContent =
                    'Mã Booking: ' +
                    button.dataset.bookingCode;

                homestayText.textContent =
                    button.dataset.homestayName;

                modal.classList.remove('hidden');
                modal.classList.add('flex');

                document.body.classList.add('overflow-hidden');

                updateCount();
            }

            function closeModal() {
                modal.classList.add('hidden');
                modal.classList.remove('flex');

                document.body.classList.remove('overflow-hidden');
            }

            ratingButtons.forEach(function (button) {
                const value = Number(
                    button.dataset.historyRating
                );

                button.addEventListener(
                    'mouseenter',
                    function () {
                        renderStars(value);
                    }
                );

                button.addEventListener(
                    'mouseleave',
                    function () {
                        renderStars(
                            Number(ratingInput.value || 0)
                        );
                    }
                );

                button.addEventListener(
                    'click',
                    function () {
                        ratingInput.value = value;
                        renderStars(value);
                    }
                );
            });

            openButtons.forEach(function (button) {
                button.addEventListener(
                    'click',
                    function () {
                        openModal(button);
                    }
                );
            });

            closeButtons.forEach(function (button) {
                button.addEventListener(
                    'click',
                    closeModal
                );
            });

            document.addEventListener(
                'keydown',
                function (event) {
                    if (
                        event.key === 'Escape'
                        && !modal.classList.contains('hidden')
                    ) {
                        closeModal();
                    }
                }
            );

            function updateCount() {
                contentCount.textContent =
                    contentInput.value.length + '/1000';
            }

            contentInput.addEventListener(
                'input',
                updateCount
            );

            updateCount();

            /*
            |--------------------------------------------------------------------------
            | Validation lỗi thì mở lại đúng Booking
            |--------------------------------------------------------------------------
            */

            const oldBookingId = @json(
                old('review_booking_id')
            );

            const hasValidationErrors = @json(
                $errors->any()
            );

            if (hasValidationErrors && oldBookingId) {
                const oldButton = document.querySelector(
                    '[data-open-review-modal][data-booking-id="' +
                    oldBookingId +
                    '"]'
                );

                if (oldButton) {
                    openModal(oldButton, true);

                    renderStars(
                        Number(ratingInput.value || 0)
                    );
                }
            }
        });
    </script>

</body>

</html>