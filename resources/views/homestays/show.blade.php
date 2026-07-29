<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>{{ $homestay->name }} | HomeStayGo</title>

    @vite([
        'resources/css/app.css',
        'resources/js/app.js',
    ])
</head>

<body class="min-h-screen bg-slate-50">

    @include('partials.navbar')

    <x-alert />
    
    <main>

        {{-- Breadcrumb --}}
        <x-frontend-breadcrumb
            :items="[
                [
                    'label' => 'Trang chủ',
                    'url' => route('home'),
                ],
                [
                    'label' => 'Danh sách Homestay',
                    'url' => route('homestays.index'),
                ],
                [
                    'label' => $homestay->category?->name ?? 'Homestay',
                    'url' => $homestay->category
                        ? route('categories.show', $homestay->category)
                        : route('homestays.index'),
                ],
                [
                    'label' => $homestay->name,
                ],
            ]"
        />

        {{-- Nội dung chính --}}
        <section class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">

            <div class="grid gap-8 lg:grid-cols-[minmax(0,1fr)_360px]">

                {{-- Cột trái --}}
                <div class="min-w-0 space-y-8">

                    {{-- Ảnh Homestay --}}
                    <div class="overflow-hidden rounded-3xl bg-slate-200 shadow-sm">

                        @if ($homestay->thumbnail)
                            <img
                                src="{{ Storage::url($homestay->thumbnail) }}"
                                alt="{{ $homestay->name }}"
                                class="h-[320px] w-full object-cover sm:h-[450px] lg:h-[520px]"
                            >
                        @else
                            <div
                                class="flex h-[320px] items-center justify-center bg-slate-200 text-center sm:h-[450px] lg:h-[520px]"
                            >
                                <div>
                                    <div class="text-6xl">
                                        🏡
                                    </div>

                                    <p class="mt-4 font-semibold text-slate-500">
                                        Homestay chưa có hình ảnh
                                    </p>
                                </div>
                            </div>
                        @endif

                    </div>

                    {{-- Thông tin chính --}}
                    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">

                        <div class="flex flex-col gap-5 sm:flex-row sm:items-start sm:justify-between">

                            <div class="min-w-0">

                                @if ($homestay->category)
                                    <span class="inline-flex rounded-full bg-blue-100 px-4 py-2 text-sm font-semibold text-blue-600">
                                        {{ $homestay->category->name }}
                                    </span>
                                @endif

                                <h1 class="mt-4 text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl">
                                    {{ $homestay->name }}
                                </h1>

                                <div class="mt-4 flex flex-wrap items-center gap-x-5 gap-y-3 text-sm text-slate-500">

                                    <div class="flex items-center gap-2">
                                        <span class="text-lg">
                                            🗺️
                                        </span>

                                        <span>
                                            {{ $homestay->address }}

                                            @if ($homestay->city)
                                                , {{ $homestay->city }}
                                            @endif
                                        </span>
                                    </div>

                                    @if ($homestay->phone)
                                        <div class="flex items-center gap-2">
                                            <span class="text-lg">
                                                📞
                                            </span>

                                            <span>
                                                {{ $homestay->phone }}
                                            </span>
                                        </div>
                                    @endif

                                </div>

                            </div>

                            <button
                                type="button"
                                class="inline-flex shrink-0 cursor-pointer items-center justify-center gap-2 rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm font-semibold text-slate-700 transition hover:border-red-300 hover:bg-red-50 hover:text-red-600"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" 
                                    viewBox="0 0 24 24" 
                                    fill="currentColor" 
                                    class="h-5 w-5">
                                    <path d="M11.645 20.91l-.007-.003-.022-.012a15.247 15.247 0 01-.383-.218 25.18 25.18 0 01-4.244-3.17C4.688 15.36 2.25 12.174 2.25 8.25 2.25 5.322 4.714 3 7.688 3A5.5 5.5 0 0112 5.052 5.5 5.5 0 0116.313 3c2.973 0 5.437 2.322 5.437 5.25 0 3.925-2.438 7.111-4.739 9.256a25.175 25.175 0 01-4.244 3.17 15.247 15.247 0 01-.383.219l-.022.012-.007.004-.003.001a.752.752 0 01-.704 0l-.003-.001z" />
                                </svg>

                                Yêu thích
                            </button>

                        </div>

                    </div>

                    {{-- Giới thiệu --}}
                    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">

                        <h2 class="text-2xl font-bold text-slate-900">
                            Giới thiệu Homestay
                        </h2>

                        <div class="mt-5 leading-8 text-slate-600">

                            @if ($homestay->description)
                                <p>
                                    {{ $homestay->description }}
                                </p>
                            @else
                                <p>
                                    Homestay này chưa có nội dung giới thiệu.
                                </p>
                            @endif

                        </div>

                    </div>

                    {{-- Danh sách phòng --}}
                    <div
                        id="rooms"
                        class="scroll-mt-28 rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8"
                    >

                        <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-end">

                            <div>
                                <p class="text-sm font-semibold uppercase tracking-widest text-blue-600">
                                    Lựa chọn phòng
                                </p>

                                <h2 class="mt-2 text-2xl font-bold text-slate-900">
                                    Phòng đang có sẵn
                                </h2>

                                <p class="mt-2 text-slate-500">
                                    Hãy chọn phòng phù hợp với số khách và nhu cầu của bạn.
                                </p>
                            </div>

                            <span class="inline-flex w-fit rounded-full bg-emerald-50 border border-emerald-200 px-6 py-2 text-sm font-semibold text-emerald-600">
                                {{ $homestay->rooms->count() }} phòng khả dụng
                            </span>

                        </div>

                        @if ($homestay->rooms->isEmpty())

                            <div class="mt-7 rounded-2xl border border-dashed border-slate-300 bg-slate-50 p-10 text-center">

                                <div class="text-5xl">
                                    🚪
                                </div>

                                <h3 class="mt-4 text-lg font-bold text-slate-900">
                                    Hiện chưa có phòng trống
                                </h3>

                                <p class="mt-2 text-sm text-slate-500">
                                    Vui lòng quay lại vào thời gian khác.
                                </p>

                            </div>

                        @else

                            <div class="mt-7 space-y-5">

                                @foreach ($homestay->rooms as $room)

                                    <article class="overflow-hidden rounded-2xl border border-slate-200 transition hover:border-blue-300 hover:shadow-lg">

                                        <div class="grid md:grid-cols-[230px_minmax(0,1fr)]">

                                            {{-- Ảnh phòng --}}
                                            <div class="bg-slate-100">

                                                @if ($room->image)
                                                    <img
                                                        src="{{ Storage::url($room->image) }}"
                                                        alt="{{ $room->name }}"
                                                        class="h-56 w-full object-cover md:h-full"
                                                    >
                                                @else
                                                    <div class="flex h-56 items-center justify-center text-center md:h-full md:min-h-64">

                                                        <div>
                                                            <div class="text-5xl">
                                                                🚪
                                                            </div>

                                                            <p class="mt-3 text-sm font-medium text-slate-400">
                                                                Chưa có ảnh phòng
                                                            </p>
                                                        </div>

                                                    </div>
                                                @endif

                                            </div>

                                            {{-- Nội dung phòng --}}
                                            <div class="flex flex-col p-5 sm:p-6">

                                                <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">

                                                    <div class="min-w-0">

                                                        <div class="flex flex-wrap items-center gap-2">

                                                            <span class="rounded-full bg-blue-50 border border-blue-200 px-3 py-1 text-xs font-semibold text-blue-600">
                                                                {{ $room->room_type }}
                                                            </span>

                                                            <span class="rounded-full bg-emerald-50 border border-emerald-200 px-3 py-1 text-xs font-semibold text-emerald-600">
                                                                Còn phòng
                                                            </span>

                                                        </div>

                                                        <h3 class="mt-3 text-xl font-bold text-slate-900">
                                                            {{ $room->name }}
                                                        </h3>

                                                        <p class="mt-1 text-sm font-medium text-slate-400">
                                                            Mã phòng: {{ $room->room_code }}
                                                        </p>

                                                    </div>

                                                    <div class="shrink-0 sm:text-right">

                                                        <p class="text-sm text-slate-500">
                                                            Giá mỗi đêm
                                                        </p>

                                                        <p class="mt-1 text-2xl font-bold text-blue-600">
                                                            {{ number_format(
                                                                $room->price_per_night,
                                                                0,
                                                                ',',
                                                                '.'
                                                            ) }}đ
                                                        </p>

                                                    </div>

                                                </div>

                                                {{-- Thông số --}}
                                                <div class="mt-5 grid grid-cols-2 gap-3 sm:grid-cols-3">

                                                    <div class="rounded-xl bg-slate-50 p-3">
                                                        <p class="text-xs text-slate-400">
                                                            Sức chứa
                                                        </p>

                                                        <p class="mt-1 text-sm font-semibold text-slate-700">
                                                            👤 {{ $room->capacity }} khách
                                                        </p>
                                                    </div>

                                                    <div class="rounded-xl bg-slate-50 p-3">
                                                        <p class="text-xs text-slate-400">
                                                            Số giường
                                                        </p>

                                                        <p class="mt-1 text-sm font-semibold text-slate-700">
                                                            🚪 {{ $room->number_of_beds }} giường
                                                        </p>
                                                    </div>

                                                    <div class="rounded-xl bg-slate-50 p-3">
                                                        <p class="text-xs text-slate-400">
                                                            Diện tích
                                                        </p>

                                                        <p class="mt-1 text-sm font-semibold text-slate-700">
                                                            📐 {{ $room->area ?? 0 }} m²
                                                        </p>
                                                    </div>

                                                </div>

                                                @if ($room->description)
                                                    <p class="mt-5 line-clamp-2 text-sm leading-6 text-slate-500">
                                                        {{ $room->description }}
                                                    </p>
                                                @endif

                                                <div class="mt-auto flex flex-col gap-3 border-t border-slate-100 pt-5 sm:flex-row sm:items-center sm:justify-between">

                                                    <p class="text-xs leading-5 text-slate-400">
                                                        Bạn sẽ chọn ngày nhận và trả phòng ở bước tiếp theo.
                                                    </p>

                                                    @auth
                                                        <a
                                                            href="{{ route('bookings.create', $room) }}"
                                                            class="inline-flex shrink-0 items-center justify-center rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-200"
                                                        >
                                                            Đặt phòng ngay
                                                        </a>
                                                    @else
                                                        <a
                                                            href="{{ route('login', [
                                                                'redirect' => route('bookings.create', $room),
                                                            ]) }}"
                                                            class="inline-flex shrink-0 items-center justify-center rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-200"
                                                        >
                                                            Đăng nhập để đặt
                                                        </a>
                                                    @endauth

                                                </div>

                                            </div>

                                        </div>

                                    </article>

                                @endforeach

                            </div>

                        @endif

                    </div>

                    {{-- Tiện ích --}}
                    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">

                        <h2 class="text-2xl font-bold text-slate-900">
                            Tiện ích nổi bật
                        </h2>

                        <p class="mt-2 text-slate-500">
                            Những dịch vụ và tiện nghi có tại Homestay.
                        </p>

                        @if ($homestay->amenities->isNotEmpty())

                            <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">

                                @foreach ($homestay->amenities as $amenity)

                                    <div class="flex items-center gap-4 rounded-2xl border border-slate-200 bg-slate-50 p-4">

                                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-blue-100 text-2xl">
                                            {{ $amenity->icon ?: '✨' }}
                                        </div>

                                        <div class="min-w-0">

                                            <p class="font-semibold text-slate-800">
                                                {{ $amenity->name }}
                                            </p>

                                            @if ($amenity->description)
                                                <p class="mt-1 line-clamp-1 text-sm text-slate-500">
                                                    {{ $amenity->description }}
                                                </p>
                                            @endif

                                        </div>

                                    </div>

                                @endforeach

                            </div>

                        @else

                            <div class="mt-6 rounded-2xl border border-dashed border-slate-300 bg-slate-50 p-6 text-center">

                                <p class="text-sm text-slate-500">
                                    Homestay này chưa cập nhật tiện ích.
                                </p>

                            </div>

                        @endif

                    </div>

                    {{-- Đánh giá Homestay --}}
                    <section
                        id="reviews"
                        class="scroll-mt-28 overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm"
                    >
                        {{-- Tiêu đề --}}
                        <div class="border-b border-slate-200 px-6 py-6 sm:px-8">

                            <p class="text-sm font-semibold uppercase tracking-widest text-amber-500">
                                Phản hồi khách hàng
                            </p>

                            <div class="mt-2 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">

                                <div>

                                    <h2 class="text-2xl font-bold text-slate-900">
                                        Đánh giá Homestay
                                    </h2>

                                    <p class="mt-2 text-sm text-slate-500">
                                        Những trải nghiệm thực tế từ khách hàng đã sử dụng dịch vụ.
                                    </p>

                                </div>

                                @if ($reviewTotal > 0)

                                    <div class="flex items-center gap-2 rounded-full bg-amber-50 px-4 py-2">

                                        <x-icon-star class="h-4 w-4 text-amber-400" />

                                        <span class="font-bold text-slate-900">
                                            {{ number_format($averageRating, 1) }}
                                        </span>

                                        <span class="font-bold text-slate-500">
                                            / 5
                                        </span>

                                    </div>

                                @endif

                            </div>

                        </div>

                        @if ($reviewTotal > 0)

                            {{-- Tổng quan đánh giá --}}
                            <div class="grid border-b border-slate-200 lg:grid-cols-[240px_minmax(0,1fr)]">

                                {{-- Điểm trung bình --}}
                                <div class="flex flex-col items-center justify-center border-b border-slate-200 p-8 text-center lg:border-b-0 lg:border-r">

                                    <p class="text-6xl font-bold tracking-tight text-slate-900">
                                        {{ number_format($averageRating, 1) }}
                                    </p>

                                    <div class="mt-3 flex items-center justify-center gap-1">

                                        @for ($star = 1; $star <= 5; $star++)

                                            <x-icon-star
                                                class="h-6 w-6 {{ $star <= round($averageRating)
                                                    ? 'text-amber-400'
                                                    : 'text-slate-200' }}"
                                            />

                                        @endfor

                                    </div>

                                    <p class="mt-3 text-sm font-semibold text-slate-700">
                                        {{ $reviewTotal }} lượt đánh giá
                                    </p>

                                    @guest

                                        {{-- Chưa đăng nhập: đi qua route auth --}}
                                        <a
                                            href="{{ route('reviews.create', [
                                                'homestay' => $homestay->slug,
                                            ]) }}"
                                            class="mt-5 inline-flex items-center justify-center rounded-xl bg-blue-600 px-5 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-200"
                                        >
                                            Viết đánh giá
                                        </a>

                                    @else

                                        @if ($reviewBooking ?? null)

                                            {{-- Đã đăng nhập và đủ điều kiện: mở modal ngay --}}
                                            <button
                                                type="button"
                                                id="open-review-modal"
                                                class="mt-5 inline-flex cursor-pointer items-center justify-center rounded-xl bg-amber-500 px-5 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-amber-600 focus:outline-none focus:ring-4 focus:ring-amber-200"
                                            >
                                                Viết đánh giá
                                            </button>

                                        @else

                                            {{-- Đã đăng nhập nhưng chưa đủ điều kiện --}}
                                            <button
                                                type="button"
                                                onclick="alert('Bạn cần hoàn thành chuyến lưu trú trước khi đánh giá Homestay này.')"
                                                class="mt-5 inline-flex cursor-pointer items-center justify-center rounded-xl border border-slate-300 bg-white px-5 py-2 text-sm font-semibold text-slate-600 transition hover:bg-slate-50"
                                            >
                                                Viết đánh giá
                                            </button>

                                        @endif

                                    @endguest

                                </div>

                                {{-- Phân bố số sao --}}
                                <div class="p-6 sm:p-8">

                                    <h3 class="font-bold text-slate-900">
                                        Phân bố đánh giá
                                    </h3>

                                    <div class="mt-5 space-y-3">

                                        @foreach ($ratingDistribution as $star => $ratingData)

                                            <div class="grid grid-cols-[52px_minmax(0,1fr)_42px] items-center gap-3">

                                                <div class="flex items-center gap-1 text-sm font-semibold text-slate-700">

                                                    <span>
                                                        {{ $star }}
                                                    </span>

                                                    <x-icon-star class="h-4 w-4 text-amber-400" />

                                                </div>

                                                <div class="h-2.5 overflow-hidden rounded-full bg-slate-100">

                                                    <div
                                                        class="h-full rounded-full bg-amber-400 transition-all"
                                                        style="width: {{ $ratingData['percentage'] }}%"
                                                    ></div>

                                                </div>

                                                <span class="text-right text-sm font-medium text-slate-500">
                                                    {{ $ratingData['count'] }}
                                                </span>

                                            </div>

                                        @endforeach

                                    </div>

                                </div>

                            </div>

                            {{-- Bộ lọc số sao --}}
                            <div class="border-b border-slate-200 px-6 py-5 sm:px-8">

                                <div class="flex items-center gap-3 overflow-x-auto pb-1">

                                    {{-- Tất cả --}}
                                    <a
                                        href="{{ request()->url() }}#reviews"
                                        class="inline-flex shrink-0 items-center justify-center rounded-full border px-5 py-2.5 text-sm font-semibold transition
                                            {{ $selectedRating === null
                                                ? 'border-amber-300 bg-amber-50 text-amber-600'
                                                : 'border-slate-300 bg-white text-slate-700 hover:border-amber-300 hover:bg-amber-50 hover:text-amber-600' }}"
                                    >
                                        Tất cả

                                        <span class="ml-2 text-sm opacity-70">
                                            {{ $reviewTotal }}
                                        </span>
                                    </a>

                                    {{-- Lọc từ 5 sao xuống 1 sao --}}
                                    @for ($star = 5; $star >= 1; $star--)

                                        <a
                                            href="{{ request()->url() }}?rating={{ $star }}#reviews"
                                            class="inline-flex shrink-0 items-center justify-center gap-1 rounded-full border px-5 py-2 text-sm font-semibold transition
                                                {{ $selectedRating === $star
                                                    ? 'border-amber-400 bg-amber-50 text-amber-700'
                                                    : 'border-slate-300 bg-white text-slate-700 hover:border-amber-300 hover:bg-amber-50 hover:text-amber-700' }}"
                                        >
                                            <span>
                                                {{ $star }}
                                            </span>

                                            <x-icon-star class="h-4 w-4 text-amber-400" />
                                        </a>

                                    @endfor

                                </div>

                            </div>

                            {{-- Danh sách đánh giá --}}
                            @if ($reviews->isEmpty() && $selectedRating !== null)

                                {{-- Không có đánh giá theo mức sao đã chọn --}}
                                <div class="px-6 py-14 text-center sm:px-8">

                                    <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-slate-500">
                                        Homestay này hiện chưa nhận được đánh giá
                                        {{ $selectedRating }} sao từ khách hàng.
                                    </p>

                                    <a
                                        href="{{ request()->url() }}#reviews"
                                        class="mt-5 inline-flex items-center justify-center rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-700"
                                    >
                                        Xem tất cả đánh giá
                                    </a>

                                </div>

                            @else

                                <div class="divide-y divide-slate-200">

                                    @foreach ($reviews as $review)

                                        @php
                                            $reviewer = $review->user;

                                            $reviewerName = $reviewer?->name
                                                ?? 'Khách hàng';

                                            $avatarText = mb_strtoupper(
                                                mb_substr($reviewerName, 0, 1)
                                            );

                                            $avatarUrl = null;

                                            if (!empty($reviewer?->avatar)) {
                                                $avatarUrl = \Illuminate\Support\Str::startsWith(
                                                    $reviewer->avatar,
                                                    ['http://', 'https://']
                                                )
                                                    ? $reviewer->avatar
                                                    : Storage::url($reviewer->avatar);
                                            }
                                        @endphp

                                        <article class="p-6 transition hover:bg-slate-50/70 sm:p-8">

                                            <div class="flex items-start gap-4">

                                                {{-- Avatar --}}
                                                @if ($avatarUrl)

                                                    <img
                                                        src="{{ $avatarUrl }}"
                                                        alt="{{ $reviewerName }}"
                                                        class="h-12 w-12 shrink-0 rounded-full object-cover ring-4 ring-slate-100"
                                                    >

                                                @else

                                                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-blue-100 text-base font-bold text-blue-600 ring-4 ring-slate-100">
                                                        {{ $avatarText ?: '?' }}
                                                    </div>

                                                @endif

                                                <div class="min-w-0 flex-1">

                                                    {{-- Tên và thời gian --}}
                                                    <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">

                                                        <div class="min-w-0">

                                                            <p class="truncate font-bold text-slate-900">
                                                                {{ $reviewerName }}
                                                            </p>

                                                            <div class="mt-1 flex flex-wrap items-center gap-x-3 gap-y-1 text-xs text-slate-400">

                                                                <span>
                                                                    {{ $review->created_at->format('d/m/Y H:i') }}
                                                                </span>

                                                                @if ($review->edited_at)

                                                                    <span class="inline-flex items-center gap-1 font-medium text-blue-500">

                                                                        <span class="h-1 w-1 rounded-full bg-slate-300"></span>

                                                                        Đã chỉnh sửa
                                                                    </span>

                                                                @endif

                                                            </div>

                                                        </div>

                                                        {{-- Số sao --}}
                                                        <div class="flex shrink-0 items-center gap-1">

                                                            @for ($star = 1; $star <= 5; $star++)

                                                                <x-icon-star
                                                                    class="h-4 w-4 {{ $star <= $review->rating
                                                                        ? 'text-amber-400'
                                                                        : 'text-slate-200' }}"
                                                                />

                                                            @endfor

                                                        </div>

                                                    </div>

                                                    {{-- Tiêu đề --}}
                                                    @if ($review->title)

                                                        <h3 class="mt-4 text-base font-semibold text-slate-900">
                                                            {{ $review->title }}
                                                        </h3>

                                                    @endif

                                                    {{-- Nội dung --}}
                                                    <p class="mt-2 wrap-break-word text-sm leading-7 text-slate-600">
                                                        {{ $review->content }}
                                                    </p>

                                                </div>

                                            </div>

                                        </article>

                                    @endforeach

                                </div>

                            @endif

                            {{-- Phân trang --}}
                            @if ($reviews->hasPages())

                                <div class="border-t border-slate-200 px-6 py-5 sm:px-8">
                                    {{ $reviews->links() }}
                                </div>

                            @endif

                        @else

                            {{-- Trạng thái chưa có đánh giá --}}
                            <div class="px-6 py-10 text-center sm:px-8">

                                <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-slate-500">
                                    Hãy trải nghiệm và trở thành người đầu tiên để lại đánh giá cho Homestay này nhé!
                                </p>

                                @guest

                                    {{-- Chưa đăng nhập: đi qua route auth --}}
                                    <a
                                        href="{{ route('reviews.create', [
                                            'homestay' => $homestay->slug,
                                        ]) }}"
                                        class="mt-5 inline-flex items-center justify-center rounded-xl bg-amber-500 px-5 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-amber-600 focus:outline-none focus:ring-4 focus:ring-amber-200"
                                    >
                                        Viết đánh giá
                                    </a>

                                @else

                                    @if ($reviewBooking ?? null)

                                        {{-- Đã đăng nhập và đủ điều kiện: mở modal ngay --}}
                                        <button
                                            type="button"
                                            id="open-review-modal"
                                            class="mt-5 inline-flex cursor-pointer items-center justify-center rounded-xl bg-amber-500 px-5 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-amber-600 focus:outline-none focus:ring-4 focus:ring-amber-200"
                                        >
                                            Viết đánh giá
                                        </button>

                                    @else

                                        {{-- Đã đăng nhập nhưng chưa đủ điều kiện --}}
                                        <button
                                            type="button"
                                            onclick="alert('Bạn cần hoàn thành chuyến lưu trú trước khi đánh giá Homestay này.')"
                                            class="mt-5 inline-flex cursor-pointer items-center justify-center rounded-xl border border-slate-300 bg-white px-5 py-2 text-sm font-semibold text-slate-600 transition hover:bg-slate-50"
                                        >
                                            Viết đánh giá
                                        </button>

                                    @endif

                                @endguest

                            </div>

                        @endif

                    </section>

                    {{-- Chính sách --}}
                    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">

                        <h2 class="text-2xl font-bold text-slate-900">
                            Thời gian và chính sách
                        </h2>

                        <div class="mt-6 grid gap-4 sm:grid-cols-2">

                            <div class="rounded-2xl bg-slate-50 p-5">
                                <p class="text-sm text-slate-500">
                                    Giờ nhận phòng
                                </p>

                                <p class="mt-2 text-lg font-bold text-slate-900">
                                    {{ $homestay->check_in_time
                                        ? \Carbon\Carbon::parse($homestay->check_in_time)->format('H:i')
                                        : 'Chưa cập nhật' }}
                                </p>
                            </div>

                            <div class="rounded-2xl bg-slate-50 p-5">
                                <p class="text-sm text-slate-500">
                                    Giờ trả phòng
                                </p>

                                <p class="mt-2 text-lg font-bold text-slate-900">
                                    {{ $homestay->check_out_time
                                        ? \Carbon\Carbon::parse($homestay->check_out_time)->format('H:i')
                                        : 'Chưa cập nhật' }}
                                </p>
                            </div>

                        </div>

                        <div class="mt-5 rounded-2xl border border-slate-200 p-5">

                            <p class="font-semibold text-slate-800">
                                Chính sách Homestay
                            </p>

                            <p class="mt-5 leading-7 text-slate-500">
                                {{ $homestay->policy ?: 'Homestay chưa cập nhật chính sách.' }}
                            </p>

                        </div>

                    </div>

                    {{-- Chủ sở hữu --}}
                    @if ($homestay->owner)

                        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">

                            <h2 class="text-2xl font-bold text-slate-900">
                                Chủ sở hữu
                            </h2>

                            <div class="mt-6 flex items-center gap-4">

                                <div class="flex h-16 w-16 shrink-0 items-center justify-center rounded-full bg-blue-100 text-xl font-bold text-blue-600">
                                    {{ mb_strtoupper(
                                        mb_substr($homestay->owner->name, 0, 1)
                                    ) }}
                                </div>

                                <div class="min-w-0">

                                    <p class="text-lg font-bold text-slate-900">
                                        {{ $homestay->owner->name }}
                                    </p>

                                    <p class="mt-1 text-sm text-slate-500">
                                        Chủ sở hữu Homestay
                                    </p>

                                    @if ($homestay->owner->email)
                                        <p class="mt-2 truncate text-sm font-medium text-blue-600">
                                            {{ $homestay->owner->email }}
                                        </p>
                                    @endif

                                </div>

                            </div>

                        </div>

                    @endif

                </div>

                {{-- Sidebar --}}
                <aside class="lg:sticky lg:top-24 lg:self-start">

                    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-lg">

                        <div class="border-b border-slate-200 pb-5">

                            <p class="text-sm font-medium text-slate-500">
                                Giá phòng từ
                            </p>

                            <div class="mt-2 flex items-end gap-2">

                                <p class="text-3xl font-bold text-blue-600">
                                    {{ number_format(
                                        $homestay->rooms->min('price_per_night')
                                            ?? $homestay->base_price
                                            ?? 0,
                                        0,
                                        ',',
                                        '.'
                                    ) }}đ
                                </p>

                                <span class="pb-1 text-sm text-slate-500">
                                    / đêm
                                </span>

                            </div>

                        </div>

                        <div class="mt-6 space-y-4">

                            <div class="flex items-center justify-between rounded-xl bg-slate-50 px-4 py-3 text-sm">

                                <span class="text-slate-500">
                                    Phòng khả dụng
                                </span>

                                <span class="font-bold text-slate-800">
                                    {{ $homestay->rooms->count() }}
                                </span>

                            </div>

                            <div class="flex items-center justify-between rounded-xl bg-slate-50 px-4 py-3 text-sm">

                                <span class="text-slate-500">
                                    Trạng thái
                                </span>

                                <span class="inline-flex items-center gap-2 font-semibold text-emerald-600">
                                    <span class="h-2 w-2 rounded-full bg-emerald-500"></span>

                                    Đang hoạt động
                                </span>

                            </div>

                            @if ($homestay->rooms->isNotEmpty())
                                <a
                                    href="#rooms"
                                    class="inline-flex w-full items-center justify-center rounded-xl bg-blue-600 px-6 py-3.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-200"
                                >
                                    Xem phòng và đặt ngay
                                </a>
                            @else
                                <button
                                    type="button"
                                    disabled
                                    class="inline-flex w-full cursor-not-allowed items-center justify-center rounded-xl bg-slate-300 px-6 py-3.5 text-sm font-semibold text-white"
                                >
                                    Hiện chưa có phòng
                                </button>
                            @endif

                        </div>

                        <p class="mt-4 text-center text-xs leading-5 text-slate-400">
                            Bạn chỉ thanh toán sau khi hoàn tất các bước xác nhận.
                        </p>

                    </div>

                </aside>

            </div>

        </section>

    </main>

    @if ($reviewBooking ?? null)

        {{-- Modal đánh giá --}}
        <div
            id="review-modal"
            class="fixed inset-0 z-[100] hidden items-center justify-center p-4"
            role="dialog"
            aria-modal="true"
            aria-labelledby="review-modal-title"
        >
            {{-- Nền tối, nhấn vào đây để đóng --}}
            <button
                type="button"
                data-review-overlay
                data-close-review-modal
                class="absolute inset-0 cursor-default bg-slate-950/50 opacity-0 backdrop-blur-[2px] transition-opacity duration-200"
                aria-label="Đóng modal"
            ></button>

            {{-- Nội dung modal --}}
            <div
                data-review-panel
                class="relative z-10 w-full max-w-md translate-y-4 scale-95 rounded-3xl bg-white p-6 opacity-0 shadow-2xl transition-all duration-200 sm:p-7"
            >
                {{-- Header --}}
                <div class="flex items-start justify-between gap-4">

                    <div class="min-w-0">

                        <h2
                            id="review-modal-title"
                            class="text-xl font-bold text-slate-900"
                        >
                            Đánh giá Homestay
                        </h2>

                        <p class="mt-1 truncate text-sm font-semibold text-slate-500">
                            {{ $homestay->name }}
                        </p>

                    </div>

                    <button
                        type="button"
                        data-close-review-modal
                        class="flex h-9 w-9 shrink-0 cursor-pointer items-center justify-center rounded-full bg-slate-100 text-xl text-slate-500 transition hover:bg-red-50 hover:text-red-500"
                        aria-label="Đóng"
                    >
                        <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round">
                            <path d="M18 6L6 18M6 6l12 12"/>
                        </svg>
                    </button>

                </div>

                <form
                    method="POST"
                    action="{{ route('reviews.store', $reviewBooking) }}"
                    class="mt-6"
                >
                    @csrf

                    {{-- Số sao --}}
                    <div class="text-center">

                        <label class="text-sm font-semibold text-slate-700">
                            Bạn cảm thấy thế nào?
                            <span class="text-red-500">*</span>
                        </label>

                        <input
                            type="hidden"
                            name="rating"
                            id="review-rating"
                            value="{{ old('rating', 0) }}"
                        >

                        <div
                            id="review-rating-stars"
                            class="mt-3 flex justify-center gap-2"
                        >
                            @for ($star = 1; $star <= 5; $star++)

                                <button
                                    type="button"
                                    data-rating="{{ $star }}"
                                    class="group cursor-pointer rounded-xl p-1 transition focus:outline-none"
                                    aria-label="{{ $star }} sao"
                                >
                                    <x-icon-star
                                        class="review-star-icon h-9 w-9 text-slate-200 transition duration-150 group-hover:scale-110 sm:h-10 sm:w-10"
                                    />
                                </button>

                            @endfor
                        </div>

                        <p
                            id="review-rating-label"
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
                            for="review-title"
                            class="text-sm font-semibold text-slate-700"
                        >
                            Tiêu đề
                            <span class="font-normal text-slate-400">
                                (không bắt buộc)
                            </span>
                        </label>

                        <input
                            type="text"
                            id="review-title"
                            name="title"
                            value="{{ old('title') }}"
                            maxlength="150"
                            placeholder="Ví dụ: Kỳ nghỉ rất tuyệt vời"
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
                                for="review-content"
                                class="text-sm font-semibold text-slate-700"
                            >
                                Nội dung đánh giá
                                <span class="text-red-500">*</span>
                            </label>

                            <span
                                id="review-content-count"
                                class="text-xs text-slate-400"
                            >
                                0/1000
                            </span>

                        </div>

                        <textarea
                            id="review-content"
                            name="content"
                            rows="4"
                            maxlength="1000"
                            placeholder="Chia sẻ cảm nhận của bạn..."
                            class="mt-2 w-full resize-none rounded-xl border border-slate-300 px-4 py-3 text-sm leading-6 outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                        >{{ old('content') }}</textarea>

                        @error('content')
                            <p class="mt-2 text-sm font-medium text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>

                    {{-- Nút --}}
                    <div class="mt-6 grid grid-cols-2 gap-3">

                        <button
                            type="button"
                            data-close-review-modal
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
        
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const modal = document.getElementById('review-modal');
                const openButton = document.getElementById('open-review-modal');

                if (!modal) {
                    return;
                }

                const overlay = modal.querySelector('[data-review-overlay]');
                const panel = modal.querySelector('[data-review-panel]');
                const form = modal.querySelector('form');

                const closeButtons = modal.querySelectorAll(
                    '[data-close-review-modal]'
                );

                const ratingInput = document.getElementById(
                    'review-rating'
                );

                const ratingButtons = modal.querySelectorAll(
                    '[data-rating]'
                );

                const ratingLabel = document.getElementById(
                    'review-rating-label'
                );

                const contentInput = document.getElementById(
                    'review-content'
                );

                const contentCount = document.getElementById(
                    'review-content-count'
                );

                const ratingMessages = {
                    0: 'Chọn số sao',
                    1: 'Rất không hài lòng',
                    2: 'Chưa hài lòng',
                    3: 'Bình thường',
                    4: 'Hài lòng',
                    5: 'Tuyệt vời',
                };

                let closeTimer = null;

                function openReviewModal() {
                    clearTimeout(closeTimer);

                    modal.classList.remove('hidden');
                    modal.classList.add('flex');

                    document.body.classList.add('overflow-hidden');

                    requestAnimationFrame(function () {
                        overlay.classList.remove('opacity-0');

                        panel.classList.remove(
                            'opacity-0',
                            'translate-y-4',
                            'scale-95'
                        );
                    });
                }

                function closeReviewModal() {
                    overlay.classList.add('opacity-0');

                    panel.classList.add(
                        'opacity-0',
                        'translate-y-4',
                        'scale-95'
                    );

                    document.body.classList.remove('overflow-hidden');

                    closeTimer = setTimeout(function () {
                        modal.classList.add('hidden');
                        modal.classList.remove('flex');
                    }, 200);
                }

                function renderStars(value, hovering = false) {
                    ratingButtons.forEach(function (button) {
                        const starValue = Number(
                            button.dataset.rating
                        );

                        const icon = button.querySelector(
                            '.review-star-icon'
                        );

                        const isActive = starValue <= value;

                        icon.classList.toggle(
                            'text-amber-400',
                            isActive
                        );

                        icon.classList.toggle(
                            'text-slate-200',
                            !isActive
                        );

                        icon.classList.toggle(
                            'scale-110',
                            isActive
                        );
                    });

                    ratingLabel.textContent =
                        ratingMessages[value] ??
                        ratingMessages[0];

                    ratingLabel.classList.toggle(
                        'text-amber-600',
                        value > 0
                    );

                    ratingLabel.classList.toggle(
                        'text-slate-400',
                        value === 0
                    );
                }

                function updateContentCount() {
                    contentCount.textContent =
                        contentInput.value.length + '/1000';
                }

                if (openButton) {
                    openButton.addEventListener(
                        'click',
                        openReviewModal
                    );
                }

                closeButtons.forEach(function (button) {
                    button.addEventListener(
                        'click',
                        closeReviewModal
                    );
                });

                ratingButtons.forEach(function (button) {
                    const value = Number(
                        button.dataset.rating
                    );

                    button.addEventListener(
                        'mouseenter',
                        function () {
                            renderStars(value, true);
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

                document.addEventListener(
                    'keydown',
                    function (event) {
                        if (
                            event.key === 'Escape' &&
                            !modal.classList.contains('hidden')
                        ) {
                            closeReviewModal();
                        }
                    }
                );

                contentInput.addEventListener(
                    'input',
                    updateContentCount
                );

                form.addEventListener(
                    'submit',
                    function (event) {
                        if (Number(ratingInput.value) < 1) {
                            event.preventDefault();

                            ratingLabel.textContent =
                                'Vui lòng chọn số sao';

                            ratingLabel.classList.remove(
                                'text-slate-400'
                            );

                            ratingLabel.classList.add(
                                'text-red-500'
                            );

                            return;
                        }

                        ratingLabel.classList.remove(
                            'text-red-500'
                        );
                    }
                );

                updateContentCount();

                renderStars(
                    Number(ratingInput.value || 0)
                );

                const autoOpenReviewModal = @json($showReviewForm ?? false);

                const hasValidationErrors = @json(
                    $errors->has('rating')
                    || $errors->has('title')
                    || $errors->has('content')
                );

                if (
                    autoOpenReviewModal ||
                    hasValidationErrors
                ) {
                    openReviewModal();
                }
            });
        </script>

    @endif

</body>

</html>