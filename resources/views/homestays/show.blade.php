@extends('layouts.app')

@section('title', $homestay->name . ' | HomeStayGo')

@section('content')
    <x-alert />

    <main>

        {{-- Breadcrumb --}}
        <x-frontend-breadcrumb :items="[
            ['label' => 'Trang chủ', 'url' => route('home')],
            ['label' => 'Homestay', 'url' => route('homestays.index')],
            ['label' => $homestay->name],
        ]" />

        <section class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
            <div class="grid gap-8 lg:grid-cols-[minmax(0,1fr)_360px]">

                {{-- ===================== CỘT TRÁI ===================== --}}
                <div class="min-w-0 space-y-8">

                    {{-- Ảnh Homestay --}}
                    <div class="overflow-hidden rounded-3xl bg-slate-200 shadow-sm">
                        @if ($homestay->thumbnail)
                            <img src="{{ Storage::url($homestay->thumbnail) }}" alt="{{ $homestay->name }}"
                                class="h-[320px] w-full object-cover sm:h-[450px] lg:h-[520px]">
                        @else
                            <div
                                class="flex h-[320px] flex-col items-center justify-center bg-slate-200 text-center sm:h-[450px] lg:h-[520px]">
                                <div class="text-6xl">🏡</div>
                                <p class="mt-4 font-semibold text-slate-500">Homestay chưa có hình ảnh</p>
                            </div>
                        @endif
                    </div>

                    {{-- Sticky Tab Navigation --}}
                    <div id="homestay-tab-navigation"
                        class="sticky top-20 z-30 overflow-hidden rounded-2xl border border-slate-200 bg-white/95 shadow-sm backdrop-blur">
                        <div class="overflow-x-auto">
                            <div class="flex min-w-max gap-2 p-2 lg:min-w-0" role="tablist" aria-label="Nội dung Homestay">
                                @php
                                    $tabItems = [
                                        ['id' => 'rooms', 'label' => 'Danh sách phòng'],
                                        ['id' => 'reviews', 'label' => 'Đánh giá'],
                                        ['id' => 'amenities', 'label' => 'Tiện ích'],
                                        ['id' => 'about', 'label' => 'Giới thiệu'],
                                        ['id' => 'policies', 'label' => 'Chính sách'],
                                    ];
                                @endphp

                                @foreach ($tabItems as $tab)
                                    <button type="button" role="tab" data-tab-target="{{ $tab['id'] }}"
                                        aria-selected="{{ $loop->first ? 'true' : 'false' }}"
                                        class="homestay-tab cursor-pointer border-b-5 inline-flex min-h-11 shrink-0 items-center justify-center gap-2 py-2.5 text-sm font-semibold transition lg:flex-1
                                            {{ $loop->first
                                                ? 'border-b-blue-600 text-blue-600'
                                                : 'border-b-transparent text-slate-600 hover:border-b-slate-300 hover:text-slate-900' }}"
                                        <span>{{ $tab['label'] }}</span>
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    {{-- ===================== NỘI DUNG TABS ===================== --}}
                    <div class="min-h-[320px] space-y-8">

                        {{-- TAB: Rooms --}}
                        <div data-scroll-section="rooms" role="tabpanel">
                            <div id="rooms"
                                class="scroll-mt-28 rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
                                <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
                                    <div>
                                        <p class="text-sm font-semibold uppercase tracking-widest text-blue-600">Lựa chọn
                                            phòng</p>
                                        <h2 class="mt-2 text-2xl font-bold text-slate-900">Phòng đang có sẵn</h2>
                                        <p class="mt-2 text-slate-500">Hãy chọn phòng phù hợp với số khách và nhu cầu của
                                            bạn.</p>
                                    </div>
                                    <span
                                        class="inline-flex w-fit rounded-full bg-emerald-50 px-4 py-2 text-sm font-semibold text-emerald-600">
                                        {{ $homestay->rooms->count() }} phòng khả dụng
                                    </span>
                                </div>

                                @if ($homestay->rooms->isEmpty())
                                    <div
                                        class="mt-7 rounded-2xl border border-dashed border-slate-300 bg-slate-50 p-10 text-center">
                                        <div class="text-5xl">🚪</div>
                                        <h3 class="mt-4 text-lg font-bold text-slate-900">Hiện chưa có phòng trống</h3>
                                        <p class="mt-2 text-sm text-slate-500">Vui lòng quay lại vào thời gian khác.</p>
                                    </div>
                                @else
                                    <div class="mt-7 space-y-5">
                                        @foreach ($homestay->rooms as $room)
                                            <article
                                                class="overflow-hidden rounded-2xl border border-slate-200 transition hover:border-blue-300 hover:shadow-lg">
                                                <div class="grid md:grid-cols-[230px_minmax(0,1fr)]">
                                                    {{-- Ảnh phòng --}}
                                                    <div class="bg-slate-100">
                                                        @if ($room->image)
                                                            <img src="{{ Storage::url($room->image) }}"
                                                                alt="{{ $room->name }}"
                                                                class="h-56 w-full object-cover md:h-full">
                                                        @else
                                                            <div
                                                                class="flex h-56 flex-col items-center justify-center text-center md:h-full md:min-h-64">
                                                                <div class="text-5xl">🚪</div>
                                                                <p class="mt-3 text-sm font-medium text-slate-400">Chưa có
                                                                    ảnh phòng</p>
                                                            </div>
                                                        @endif
                                                    </div>

                                                    {{-- Nội dung phòng --}}
                                                    <div class="flex flex-col p-5 sm:p-6">
                                                        <div
                                                            class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                                                            <div class="min-w-0">
                                                                <div class="flex flex-wrap items-center gap-2">
                                                                    <span
                                                                        class="rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-600">{{ $room->room_type }}</span>
                                                                    <span
                                                                        class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-600">Còn
                                                                        phòng</span>
                                                                </div>
                                                                <h3 class="mt-3 text-xl font-bold text-slate-900">
                                                                    {{ $room->name }}</h3>
                                                                <p class="mt-1 text-sm font-medium text-slate-400">Mã phòng:
                                                                    {{ $room->room_code }}</p>
                                                            </div>
                                                            <div class="shrink-0 sm:text-right">
                                                                <p class="text-sm text-slate-500">Giá mỗi đêm</p>
                                                                <p class="mt-1 text-2xl font-bold text-blue-600">
                                                                    {{ number_format($room->price_per_night, 0, ',', '.') }}đ
                                                                </p>
                                                            </div>
                                                        </div>

                                                        <div class="mt-5 grid grid-cols-2 gap-3 sm:grid-cols-3">
                                                            <div class="rounded-xl bg-slate-50 p-3">
                                                                <p class="text-xs text-slate-400">Sức chứa</p>
                                                                <p class="mt-1 text-sm font-semibold text-slate-700">👤
                                                                    {{ $room->capacity }} khách</p>
                                                            </div>
                                                            <div class="rounded-xl bg-slate-50 p-3">
                                                                <p class="text-xs text-slate-400">Số giường</p>
                                                                <p class="mt-1 text-sm font-semibold text-slate-700">🚪
                                                                    {{ $room->number_of_beds }} giường</p>
                                                            </div>
                                                            <div class="rounded-xl bg-slate-50 p-3">
                                                                <p class="text-xs text-slate-400">Diện tích</p>
                                                                <p class="mt-1 text-sm font-semibold text-slate-700">📐
                                                                    {{ $room->area ?? 0 }} m²</p>
                                                            </div>
                                                        </div>

                                                        @if ($room->description)
                                                            <p class="mt-5 line-clamp-2 text-sm leading-6 text-slate-500">
                                                                {{ $room->description }}</p>
                                                        @endif

                                                        <div
                                                            class="mt-auto flex flex-col gap-3 border-t border-slate-100 pt-5 sm:flex-row sm:items-center sm:justify-between">
                                                            <p class="text-xs leading-5 text-slate-400">Bạn sẽ chọn ngày
                                                                nhận và trả phòng ở bước tiếp theo.</p>

                                                            @auth
                                                                <a href="{{ route('bookings.create', $room) }}"
                                                                    class="inline-flex shrink-0 items-center justify-center rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-200">
                                                                    Đặt phòng ngay
                                                                </a>
                                                            @else
                                                                <a href="{{ route('login', ['redirect' => route('bookings.create', $room)]) }}"
                                                                    class="inline-flex shrink-0 items-center justify-center rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-200">
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
                        </div>

                        {{-- TAB: Reviews --}}
                        <div data-scroll-section="reviews" role="tabpanel">
                            <section id="reviews"
                                class="scroll-mt-28 overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
                                <div class="border-b border-slate-200 px-6 py-6 sm:px-8">
                                    <p class="text-sm font-semibold uppercase tracking-widest text-amber-500">Phản hồi khách
                                        hàng</p>
                                    <div class="mt-2 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                                        <div>
                                            <h2 class="text-2xl font-bold text-slate-900">Đánh giá Homestay</h2>
                                            <p class="mt-2 text-sm text-slate-500">Những trải nghiệm thực tế từ khách hàng
                                                đã sử dụng dịch vụ.</p>
                                        </div>
                                        @if ($reviewTotal > 0)
                                            <div class="flex items-center gap-2 rounded-full bg-amber-50 px-4 py-2">
                                                <x-icon-star class="h-4 w-4 text-amber-400" />
                                                <span
                                                    class="font-bold text-slate-900">{{ number_format($averageRating, 1) }}</span>
                                                <span class="font-bold text-slate-500">/ 5</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                @if ($reviewTotal > 0)
                                    <div class="grid border-b border-slate-200 lg:grid-cols-[240px_minmax(0,1fr)]">
                                        <div
                                            class="flex flex-col items-center justify-center border-b border-slate-200 p-8 text-center lg:border-b-0 lg:border-r">
                                            <p class="text-6xl font-bold tracking-tight text-slate-900">
                                                {{ number_format($averageRating, 1) }}</p>
                                            <div class="mt-3 flex items-center justify-center gap-1">
                                                @for ($star = 1; $star <= 5; $star++)
                                                    <x-icon-star
                                                        class="h-6 w-6 {{ $star <= round($averageRating) ? 'text-amber-400' : 'text-slate-200' }}" />
                                                @endfor
                                            </div>
                                            <p class="mt-3 text-sm font-semibold text-slate-700">{{ $reviewTotal }} lượt
                                                đánh giá</p>

                                            @guest
                                                <a href="{{ route('reviews.create', ['homestay' => $homestay->slug]) }}"
                                                    class="mt-5 inline-flex items-center justify-center rounded-xl bg-blue-600 px-5 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-200">
                                                    Viết đánh giá
                                                </a>
                                            @else
                                                @if ($reviewBooking ?? null)
                                                    <button type="button" id="open-review-modal"
                                                        class="mt-5 inline-flex cursor-pointer items-center justify-center rounded-xl bg-amber-500 px-5 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-amber-600 focus:outline-none focus:ring-4 focus:ring-amber-200">
                                                        Viết đánh giá
                                                    </button>
                                                @else
                                                    <button type="button"
                                                        onclick="alert('Bạn cần hoàn thành chuyến lưu trú trước khi đánh giá Homestay này.')"
                                                        class="mt-5 inline-flex cursor-pointer items-center justify-center rounded-xl border border-slate-300 bg-white px-5 py-2 text-sm font-semibold text-slate-600 transition hover:bg-slate-50">
                                                        Viết đánh giá
                                                    </button>
                                                @endif
                                            @endguest
                                        </div>

                                        <div class="p-6 sm:p-8">
                                            <h3 class="font-bold text-slate-900">Phân bố đánh giá</h3>
                                            <div class="mt-5 space-y-3">
                                                @foreach ($ratingDistribution as $star => $ratingData)
                                                    <div
                                                        class="grid grid-cols-[52px_minmax(0,1fr)_42px] items-center gap-3">
                                                        <div
                                                            class="flex items-center gap-1 text-sm font-semibold text-slate-700">
                                                            <span>{{ $star }}</span>
                                                            <x-icon-star class="h-4 w-4 text-amber-400" />
                                                        </div>
                                                        <div class="h-2.5 overflow-hidden rounded-full bg-slate-100">
                                                            <div class="h-full rounded-full bg-amber-400 transition-all"
                                                                style="width: {{ $ratingData['percentage'] }}%"></div>
                                                        </div>
                                                        <span
                                                            class="text-right text-sm font-medium text-slate-500">{{ $ratingData['count'] }}</span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Bộ lọc sao --}}
                                    <div class="border-b border-slate-200 px-6 py-5 sm:px-8">
                                        <div class="flex items-center gap-3 overflow-x-auto pb-1">
                                            <a href="{{ request()->url() }}#reviews"
                                                class="inline-flex shrink-0 items-center justify-center rounded-full border px-5 py-2.5 text-sm font-semibold transition
                                               {{ $selectedRating === null ? 'border-amber-300 bg-amber-50 text-amber-600' : 'border-slate-300 bg-white text-slate-700 hover:border-amber-300 hover:bg-amber-50 hover:text-amber-600' }}">
                                                Tất cả <span class="ml-2 text-sm opacity-70">{{ $reviewTotal }}</span>
                                            </a>

                                            @for ($star = 5; $star >= 1; $star--)
                                                <a href="{{ request()->url() }}?rating={{ $star }}#reviews"
                                                    class="inline-flex shrink-0 items-center justify-center gap-1 rounded-full border px-5 py-2 text-sm font-semibold transition
                                                   {{ $selectedRating === $star ? 'border-amber-400 bg-amber-50 text-amber-700' : 'border-slate-300 bg-white text-slate-700 hover:border-amber-300 hover:bg-amber-50 hover:text-amber-700' }}">
                                                    <span>{{ $star }}</span>
                                                    <x-icon-star class="h-4 w-4 text-amber-400" />
                                                </a>
                                            @endfor
                                        </div>
                                    </div>

                                    {{-- Danh sách review --}}
                                    @if ($reviews->isEmpty() && $selectedRating !== null)
                                        <div class="px-6 py-14 text-center sm:px-8">
                                            <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-slate-500">
                                                Homestay này hiện chưa nhận được đánh giá {{ $selectedRating }} sao từ
                                                khách hàng.
                                            </p>
                                            <a href="{{ request()->url() }}#reviews"
                                                class="mt-5 inline-flex items-center justify-center rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-700">
                                                Xem tất cả đánh giá
                                            </a>
                                        </div>
                                    @else
                                        <div class="divide-y divide-slate-200">
                                            @foreach ($reviews as $review)
                                                @php
                                                    $reviewer = $review->user;
                                                    $reviewerName = $reviewer?->name ?? 'Khách hàng';
                                                    $avatarText = mb_strtoupper(mb_substr($reviewerName, 0, 1));
                                                    $avatarUrl = null;
                                                    if (!empty($reviewer?->avatar)) {
                                                        $avatarUrl = \Illuminate\Support\Str::startsWith(
                                                            $reviewer->avatar,
                                                            ['http://', 'https://'],
                                                        )
                                                            ? $reviewer->avatar
                                                            : Storage::url($reviewer->avatar);
                                                    }
                                                @endphp

                                                <article class="p-6 transition hover:bg-slate-50/70 sm:p-8">
                                                    <div class="flex items-start gap-4">
                                                        @if ($avatarUrl)
                                                            <img src="{{ $avatarUrl }}" alt="{{ $reviewerName }}"
                                                                class="h-12 w-12 shrink-0 rounded-full object-cover ring-4 ring-slate-100">
                                                        @else
                                                            <div
                                                                class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-blue-100 text-base font-bold text-blue-600 ring-4 ring-slate-100">
                                                                {{ $avatarText ?: '?' }}
                                                            </div>
                                                        @endif

                                                        <div class="min-w-0 flex-1">
                                                            <div
                                                                class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                                                                <div class="min-w-0">
                                                                    <p class="truncate font-bold text-slate-900">
                                                                        {{ $reviewerName }}</p>
                                                                    <div
                                                                        class="mt-1 flex flex-wrap items-center gap-x-3 gap-y-1 text-xs text-slate-400">
                                                                        <span>{{ $review->created_at->format('d/m/Y H:i') }}</span>
                                                                        @if ($review->edited_at)
                                                                            <span
                                                                                class="inline-flex items-center gap-1 font-medium text-blue-500">
                                                                                <span
                                                                                    class="h-1 w-1 rounded-full bg-blue-500"></span>
                                                                                Đã chỉnh sửa
                                                                            </span>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                                <div class="flex shrink-0 items-center gap-1">
                                                                    @for ($s = 1; $s <= 5; $s++)
                                                                        <x-icon-star
                                                                            class="h-4 w-4 {{ $s <= $review->rating ? 'text-amber-400' : 'text-slate-200' }}" />
                                                                    @endfor
                                                                </div>
                                                            </div>

                                                            @if ($review->title)
                                                                <h3 class="mt-4 text-base font-semibold text-slate-900">
                                                                    {{ $review->title }}</h3>
                                                            @endif

                                                            <p
                                                                class="mt-2 text-sm leading-7 text-slate-600 wrap-break-word">
                                                                {{ $review->content }}</p>
                                                        </div>
                                                    </div>
                                                </article>
                                            @endforeach
                                        </div>
                                    @endif

                                    @if ($reviews->hasPages())
                                        <div class="border-t border-slate-200 p-6 sm:px-8">
                                            {{ $reviews->onEachSide(1)->links('components.pagination', ['layout' => 'row', 'showInfo' => false]) }}
                                        </div>
                                    @endif
                                @else
                                    <div class="px-6 py-10 text-center sm:px-8">
                                        <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-slate-500">
                                            Hãy trải nghiệm và trở thành người đầu tiên để lại đánh giá cho Homestay này
                                            nhé!
                                        </p>
                                        @guest
                                            <a href="{{ route('reviews.create', ['homestay' => $homestay->slug]) }}"
                                                class="mt-5 inline-flex items-center justify-center rounded-xl bg-amber-500 px-5 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-amber-600 focus:outline-none focus:ring-4 focus:ring-amber-200">
                                                Viết đánh giá
                                            </a>
                                        @else
                                            @if ($reviewBooking ?? null)
                                                <button type="button" id="open-review-modal"
                                                    class="mt-5 inline-flex cursor-pointer items-center justify-center rounded-xl bg-amber-500 px-5 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-amber-600 focus:outline-none focus:ring-4 focus:ring-amber-200">
                                                    Viết đánh giá
                                                </button>
                                            @else
                                                <button type="button"
                                                    onclick="alert('Bạn cần hoàn thành chuyến lưu trú trước khi đánh giá Homestay này.')"
                                                    class="mt-5 inline-flex cursor-pointer items-center justify-center rounded-xl border border-slate-300 bg-white px-5 py-2 text-sm font-semibold text-slate-600 transition hover:bg-slate-50">
                                                    Viết đánh giá
                                                </button>
                                            @endif
                                        @endguest
                                    </div>
                                @endif
                            </section>
                        </div>

                        {{-- TAB: Amenities --}}
                        <div data-scroll-section="amenities" role="tabpanel">
                            <div id="amenities"
                                class="scroll-mt-28 rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
                                <h2 class="text-2xl font-bold text-slate-900">Tiện ích nổi bật</h2>
                                <p class="mt-2 text-slate-500">Những dịch vụ và tiện nghi có tại Homestay.</p>

                                @if ($homestay->amenities->isNotEmpty())
                                    <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                                        @foreach ($homestay->amenities as $amenity)
                                            <div
                                                class="flex items-center gap-4 rounded-2xl border border-slate-200 bg-slate-50 p-4">
                                                <div
                                                    class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-blue-100 text-2xl">
                                                    {{ $amenity->icon ?: '✨' }}
                                                </div>
                                                <div class="min-w-0">
                                                    <p class="font-semibold text-slate-800">{{ $amenity->name }}</p>
                                                    @if ($amenity->description)
                                                        <p class="mt-1 line-clamp-1 text-sm text-slate-500">
                                                            {{ $amenity->description }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div
                                        class="mt-6 rounded-2xl border border-dashed border-slate-300 bg-slate-50 p-6 text-center">
                                        <p class="text-sm text-slate-500">Homestay này chưa cập nhật tiện ích.</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- TAB: About --}}
                        <div data-scroll-section="about" role="tabpanel">
                            <div id="about" class="scroll-mt-28 space-y-6">

                                {{-- Thông tin cơ bản --}}
                                <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
                                    <div class="p-6 sm:p-8">

                                        <div class="flex flex-col gap-6 sm:flex-row sm:items-start sm:justify-between">
                                            <div class="min-w-0 flex-1">
                                                @if ($homestay->category)
                                                    <span
                                                        class="inline-flex items-center rounded-full bg-blue-50 px-4 py-2 text-sm font-semibold text-blue-600">
                                                        {{ $homestay->category->name }}
                                                    </span>
                                                @endif

                                                <h1
                                                    class="mt-4 text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl">
                                                    {{ $homestay->name }}
                                                </h1>
                                            </div>

                                            <button type="button"
                                                class="inline-flex shrink-0 items-center justify-center gap-2 rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm font-semibold text-slate-700 transition hover:border-red-300 hover:bg-red-50 hover:text-red-600 focus:outline-none focus:ring-4 focus:ring-red-100">
                                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none"
                                                    stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78L12 21.23l8.84-8.84a5.5 5.5 0 0 0 0-7.78Z" />
                                                </svg>
                                                Yêu thích
                                            </button>
                                        </div>

                                        {{-- Địa chỉ + SĐT --}}
                                        <div class="mt-7 grid gap-4 sm:grid-cols-2">
                                            <div
                                                class="flex items-start gap-4 rounded-2xl border border-slate-200 bg-slate-50 p-5">
                                                <div
                                                    class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-blue-100 text-blue-600">
                                                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none"
                                                        stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M20 10c0 5-8 11-8 11S4 15 4 10a8 8 0 1 1 16 0Z" />
                                                        <circle cx="12" cy="10" r="2.5" />
                                                    </svg>
                                                </div>
                                                <div class="min-w-0">
                                                    <p class="text-sm font-medium text-slate-500">Địa chỉ</p>
                                                    <p class="mt-1 text-sm font-semibold leading-6 text-slate-800">
                                                        {{ $homestay->address }}{{ $homestay->city ? ', ' . $homestay->city : '' }}
                                                    </p>
                                                </div>
                                            </div>

                                            <div
                                                class="flex items-start gap-4 rounded-2xl border border-slate-200 bg-slate-50 p-5">
                                                <div
                                                    class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-emerald-100 text-emerald-600">
                                                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none"
                                                        stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.12.9.33 1.78.62 2.63a2 2 0 0 1-.45 2.11L8 9.73a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.85.29 1.73.5 2.63.62A2 2 0 0 1 22 16.92Z" />
                                                    </svg>
                                                </div>
                                                <div class="min-w-0">
                                                    <p class="text-sm font-medium text-slate-500">Số điện thoại</p>
                                                    @if ($homestay->phone)
                                                        <a href="tel:{{ $homestay->phone }}"
                                                            class="mt-1 block text-sm font-semibold text-slate-800 transition hover:text-blue-600">
                                                            {{ $homestay->phone }}
                                                        </a>
                                                    @else
                                                        <p class="mt-1 text-sm font-semibold text-slate-400">Chưa cập nhật
                                                        </p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mt-6 rounded-2xl border border-slate-200 bg-slate-50 p-5 sm:p-6">
                                            @if ($homestay->description)
                                                <p class="whitespace-pre-line leading-8 text-slate-600">
                                                    {{ $homestay->description }}</p>
                                            @else
                                                <p class="leading-7 text-slate-500">Homestay này chưa cập nhật nội dung
                                                    giới thiệu.</p>
                                            @endif
                                        </div>

                                    </div>
                                </div>

                                @if ($homestay->owner)
                                    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
                                        <h1 class="mt-1 text-2xl font-bold text-slate-900">
                                            Chủ sở hữu
                                        </h1>

                                        <div
                                            class="mt-6 flex flex-col gap-5 rounded-2xl border border-slate-200 bg-slate-50 p-5 sm:flex-row sm:items-center">
                                            <div
                                                class="flex h-16 w-16 shrink-0 items-center justify-center rounded-full bg-blue-600 text-xl font-bold text-white shadow-sm ring-4 ring-blue-100">
                                                {{ mb_strtoupper(mb_substr($homestay->owner->name, 0, 1)) }}
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <p class="text-lg font-bold text-slate-900">{{ $homestay->owner->name }}
                                                </p>
                                                @if ($homestay->owner->email)
                                                    <a href="mailto:{{ $homestay->owner->email }}"
                                                        class="mt-2 block truncate text-sm font-semibold text-blue-600 transition hover:text-blue-700">
                                                        {{ $homestay->owner->email }}
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- TAB: Policies --}}
                        <div data-scroll-section="policies" role="tabpanel">
                            <div id="policies"
                                class="scroll-mt-28 rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
                                <h2 class="text-2xl font-bold text-slate-900">Thời gian và chính sách</h2>

                                <div class="mt-6 grid gap-4 sm:grid-cols-2">
                                    <div class="rounded-2xl bg-slate-50 p-5">
                                        <p class="text-sm text-slate-500">Giờ nhận phòng</p>
                                        <p class="mt-2 text-lg font-bold text-slate-900">
                                            {{ $homestay->check_in_time ? \Carbon\Carbon::parse($homestay->check_in_time)->format('H:i') : 'Chưa cập nhật' }}
                                        </p>
                                    </div>
                                    <div class="rounded-2xl bg-slate-50 p-5">
                                        <p class="text-sm text-slate-500">Giờ trả phòng</p>
                                        <p class="mt-2 text-lg font-bold text-slate-900">
                                            {{ $homestay->check_out_time ? \Carbon\Carbon::parse($homestay->check_out_time)->format('H:i') : 'Chưa cập nhật' }}
                                        </p>
                                    </div>
                                </div>

                                <div class="mt-5 rounded-2xl border border-slate-200 p-5">
                                    <p class="font-semibold text-slate-800">Chính sách Homestay</p>
                                    <p class="mt-5 leading-7 text-slate-500">
                                        {{ $homestay->policy ?: 'Homestay chưa cập nhật chính sách.' }}
                                    </p>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                {{-- ===================== SIDEBAR ===================== --}}
                <aside class="lg:sticky lg:top-24 lg:self-start">
                    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-lg">
                        <div class="border-b border-slate-200 pb-5">
                            <p class="text-sm font-medium text-slate-500">Giá phòng từ</p>
                            <div class="mt-2 flex items-end gap-2">
                                <p class="text-3xl font-bold text-blue-600">
                                    {{ number_format($homestay->rooms->min('price_per_night') ?? ($homestay->base_price ?? 0), 0, ',', '.') }}đ
                                </p>
                                <span class="pb-1 text-sm text-slate-500">/ đêm</span>
                            </div>
                        </div>

                        <div class="mt-6 space-y-4">
                            <div class="flex items-center justify-between rounded-xl bg-slate-50 px-4 py-3 text-sm">
                                <span class="text-slate-500">Phòng khả dụng</span>
                                <span class="font-bold text-slate-800">{{ $homestay->rooms->count() }}</span>
                            </div>

                            <div class="flex items-center justify-between rounded-xl bg-slate-50 px-4 py-3 text-sm">
                                <span class="text-slate-500">Trạng thái</span>
                                <span class="inline-flex items-center gap-2 font-semibold text-emerald-600">
                                    <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                                    Đang hoạt động
                                </span>
                            </div>

                            @if ($homestay->rooms->isNotEmpty())
                                <a href="#rooms" data-scroll-to="rooms"
                                    class="inline-flex w-full items-center justify-center rounded-xl bg-blue-600 px-6 py-3.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-200">
                                    Xem phòng và đặt ngay
                                </a>
                            @else
                                <button type="button" disabled
                                    class="inline-flex w-full cursor-not-allowed items-center justify-center rounded-xl bg-slate-300 px-6 py-3.5 text-sm font-semibold text-white">
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


    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const navigation = document.getElementById('homestay-tab-navigation');
            const tabButtons = Array.from(document.querySelectorAll('[data-tab-target]'));
            const scrollLinks = Array.from(document.querySelectorAll('[data-scroll-to]'));
            const sectionIds = tabButtons.map(button => button.dataset.tabTarget);
            const sections = sectionIds
                .map(id => document.getElementById(id))
                .filter(Boolean);

            if (!navigation || tabButtons.length === 0 || sections.length === 0) {
                return;
            }

            const setActiveTab = (activeId) => {
                tabButtons.forEach((button) => {
                    const isActive = button.dataset.tabTarget === activeId;

                    button.setAttribute(
                        'aria-selected',
                        isActive ? 'true' : 'false'
                    );

                    button.classList.toggle('border-b-blue-600', isActive);
                    button.classList.toggle('text-blue-600', isActive);

                    button.classList.toggle('border-b-transparent', !isActive);
                    button.classList.toggle('text-slate-600', !isActive);

                    button.classList.toggle('hover:border-b-slate-300', !isActive);
                    button.classList.toggle('hover:text-slate-900', !isActive);
                });
            };

            const getScrollOffset = () => {
                const navigationHeight = navigation.offsetHeight || 0;
                return navigationHeight + 96;
            };

            const scrollToSection = (id, updateUrl = true) => {
                const target = document.getElementById(id);
                if (!target) return;

                const top = target.getBoundingClientRect().top +
                    window.scrollY -
                    getScrollOffset();

                setActiveTab(id);
                window.scrollTo({
                    top: Math.max(top, 0),
                    behavior: 'smooth',
                });

                if (updateUrl) {
                    const url = new URL(window.location.href);
                    url.hash = id;
                    window.history.replaceState(null, '', url.toString());
                }
            };

            tabButtons.forEach(button => {
                button.addEventListener('click', () => {
                    scrollToSection(button.dataset.tabTarget);
                });
            });

            scrollLinks.forEach(link => {
                link.addEventListener('click', event => {
                    event.preventDefault();
                    scrollToSection(link.dataset.scrollTo);
                });
            });

            let ticking = false;

            const updateActiveTabFromScroll = () => {
                const position = window.scrollY + getScrollOffset() + 24;
                let currentId = sections[0].id;

                sections.forEach(section => {
                    if (section.offsetTop <= position) {
                        currentId = section.id;
                    }
                });

                setActiveTab(currentId);
                ticking = false;
            };

            window.addEventListener('scroll', () => {
                if (ticking) return;

                ticking = true;
                window.requestAnimationFrame(updateActiveTabFromScroll);
            }, {
                passive: true
            });

            const hash = window.location.hash.replace('#', '').trim();
            const initialSection = sectionIds.includes(hash) ?
                hash :
                new URLSearchParams(window.location.search).has('rating') ?
                'reviews' :
                'rooms';

            setActiveTab(initialSection);

            if (hash || new URLSearchParams(window.location.search).has('rating')) {
                window.requestAnimationFrame(() => {
                    scrollToSection(initialSection, false);
                });
            }
        });
    </script>

    {{-- Modal đánh giá --}}
    @if ($reviewBooking ?? null)
        <div id="review-modal" class="fixed inset-0 z-[100] hidden items-center justify-center p-4" role="dialog"
            aria-modal="true" aria-labelledby="review-modal-title">
            <button type="button" data-review-overlay data-close-review-modal
                class="absolute inset-0 cursor-default opacity-0 backdrop-blur-sm transition-opacity duration-200"
                aria-label="Đóng modal"></button>

            <div data-review-panel
                class="relative z-10 w-full max-w-md translate-y-4 scale-95 rounded-3xl bg-white p-6 opacity-0 shadow-2xl transition-all duration-200 sm:p-7">
                <div class="flex items-start justify-between gap-4">
                    <div class="min-w-0">
                        <h2 id="review-modal-title" class="text-xl font-bold text-slate-900">Đánh giá Homestay</h2>
                        <p class="mt-1 truncate text-sm font-semibold text-slate-500">{{ $homestay->name }}</p>
                    </div>
                    <button type="button" data-close-review-modal
                        class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-slate-100 p-2 text-slate-500 transition hover:bg-red-50 hover:text-red-500"
                        aria-label="Đóng">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"
                            stroke-linecap="round">
                            <path d="M18 6L6 18M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form method="POST" action="{{ route('reviews.store', $reviewBooking) }}" class="mt-6">
                    @csrf

                    <div class="text-center">
                        <label class="text-sm font-semibold text-slate-700">
                            Bạn cảm thấy thế nào? <span class="text-red-500">*</span>
                        </label>
                        <input type="hidden" name="rating" id="review-rating" value="{{ old('rating', 0) }}">

                        <div id="review-rating-stars" class="mt-3 flex justify-center gap-2">
                            @for ($star = 1; $star <= 5; $star++)
                                <button type="button" data-rating="{{ $star }}"
                                    class="group cursor-pointer rounded-xl p-1 transition focus:outline-none"
                                    aria-label="{{ $star }} sao">
                                    <x-icon-star
                                        class="review-star-icon h-9 w-9 text-slate-200 transition duration-150 group-hover:scale-110 sm:h-10 sm:w-10" />
                                </button>
                            @endfor
                        </div>
                        <p id="review-rating-label" class="mt-2 text-sm font-semibold text-slate-400">Chọn số sao</p>
                        @error('rating')
                            <p class="mt-2 text-sm font-medium text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mt-5">
                        <label for="review-title" class="text-sm font-semibold text-slate-700">
                            Tiêu đề <span class="font-normal text-slate-400">(không bắt buộc)</span>
                        </label>
                        <input type="text" id="review-title" name="title" value="{{ old('title') }}"
                            maxlength="150" placeholder="Ví dụ: Kỳ nghỉ rất tuyệt vời"
                            class="mt-2 w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:ring-4 focus:ring-blue-100">
                        @error('title')
                            <p class="mt-2 text-sm font-medium text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mt-4">
                        <div class="flex items-center justify-between gap-3">
                            <label for="review-content" class="text-sm font-semibold text-slate-700">
                                Nội dung đánh giá <span class="text-red-500">*</span>
                            </label>
                            <span id="review-content-count" class="text-xs text-slate-400">0/1000</span>
                        </div>
                        <textarea id="review-content" name="content" rows="4" maxlength="1000"
                            placeholder="Chia sẻ cảm nhận của bạn..."
                            class="mt-2 w-full resize-none rounded-xl border border-slate-300 px-4 py-3 text-sm leading-6 outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:ring-4 focus:ring-blue-100">{{ old('content') }}</textarea>
                        @error('content')
                            <p class="mt-2 text-sm font-medium text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mt-6 grid grid-cols-2 gap-3">
                        <button type="button" data-close-review-modal
                            class="cursor-pointer rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                            Để sau
                        </button>
                        <button type="submit"
                            class="cursor-pointer rounded-xl bg-blue-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-blue-700 focus:ring-4 focus:ring-blue-200">
                            Gửi đánh giá
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const modal = document.getElementById('review-modal');
                if (!modal) return;

                const overlay = modal.querySelector('[data-review-overlay]');
                const panel = modal.querySelector('[data-review-panel]');
                const form = modal.querySelector('form');
                const closeButtons = modal.querySelectorAll('[data-close-review-modal]');
                const ratingInput = document.getElementById('review-rating');
                const ratingButtons = modal.querySelectorAll('[data-rating]');
                const ratingLabel = document.getElementById('review-rating-label');
                const contentInput = document.getElementById('review-content');
                const contentCount = document.getElementById('review-content-count');
                const openButton = document.getElementById('open-review-modal');

                const ratingMessages = {
                    0: 'Chọn số sao',
                    1: 'Rất không hài lòng',
                    2: 'Chưa hài lòng',
                    3: 'Bình thường',
                    4: 'Hài lòng',
                    5: 'Tuyệt vời',
                };

                let closeTimer = null;

                const openReviewModal = () => {
                    clearTimeout(closeTimer);
                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
                    document.body.classList.add('overflow-hidden');
                    requestAnimationFrame(() => {
                        overlay.classList.remove('opacity-0');
                        panel.classList.remove('opacity-0', 'translate-y-4', 'scale-95');
                    });
                };

                const closeReviewModal = () => {
                    overlay.classList.add('opacity-0');
                    panel.classList.add('opacity-0', 'translate-y-4', 'scale-95');
                    document.body.classList.remove('overflow-hidden');
                    closeTimer = setTimeout(() => {
                        modal.classList.add('hidden');
                        modal.classList.remove('flex');
                    }, 200);
                };

                const renderStars = (value) => {
                    ratingButtons.forEach(btn => {
                        const starValue = Number(btn.dataset.rating);
                        const icon = btn.querySelector('.review-star-icon');
                        const isActive = starValue <= value;
                        icon.classList.toggle('text-amber-400', isActive);
                        icon.classList.toggle('text-slate-200', !isActive);
                        icon.classList.toggle('scale-110', isActive);
                    });
                    ratingLabel.textContent = ratingMessages[value] ?? ratingMessages[0];
                    ratingLabel.classList.toggle('text-amber-600', value > 0);
                    ratingLabel.classList.toggle('text-slate-400', value === 0);
                };

                const updateContentCount = () => {
                    contentCount.textContent = `${contentInput.value.length}/1000`;
                };

                openButton?.addEventListener('click', openReviewModal);
                closeButtons.forEach(btn => btn.addEventListener('click', closeReviewModal));

                ratingButtons.forEach(btn => {
                    const value = Number(btn.dataset.rating);
                    btn.addEventListener('mouseenter', () => renderStars(value));
                    btn.addEventListener('mouseleave', () => renderStars(Number(ratingInput.value || 0)));
                    btn.addEventListener('click', () => {
                        ratingInput.value = value;
                        renderStars(value);
                    });
                });

                document.addEventListener('keydown', e => {
                    if (e.key === 'Escape' && !modal.classList.contains('hidden')) closeReviewModal();
                });

                contentInput.addEventListener('input', updateContentCount);

                form.addEventListener('submit', e => {
                    if (Number(ratingInput.value) < 1) {
                        e.preventDefault();
                        ratingLabel.textContent = 'Vui lòng chọn số sao';
                        ratingLabel.classList.remove('text-slate-400');
                        ratingLabel.classList.add('text-red-500');
                    } else {
                        ratingLabel.classList.remove('text-red-500');
                    }
                });

                updateContentCount();
                renderStars(Number(ratingInput.value || 0));

                const autoOpen = @json($showReviewForm ?? false);
                const hasErrors = @json($errors->has('rating') || $errors->has('title') || $errors->has('content'));
                if (autoOpen || hasErrors) openReviewModal();
            });
        </script>
    @endif

@endsection
