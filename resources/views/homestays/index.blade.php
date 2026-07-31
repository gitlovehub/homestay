@extends('layouts.app')

@section('title', 'Danh sách Homestay | HomeStayGo')

@section('content')

    @php
        $selectedAmenities = collect(request('amenities', []))
            ->map(fn ($id) => (int) $id)
            ->all();

        $activeFilterCount = collect([
            request('search'),
            request('location'),
            request('min_price'),
            request('max_price'),
            request('guests'),
            request('room_type'),
            request('rating'),
        ])->filter(fn ($value) => filled($value))->count()
            + count($selectedAmenities);
    @endphp

    <x-frontend-breadcrumb
        :items="[
            ['label' => 'Trang chủ', 'url' => route('home')],
            ['label' => 'Danh sách Homestay'],
        ]"
    />

    {{-- Header --}}
    <section class="border-b border-slate-200 bg-white">
        <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
            <p class="font-semibold uppercase tracking-widest text-blue-600">
                Khám phá nơi lưu trú
            </p>
            <div class="mt-2 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <h1 class="text-3xl font-bold tracking-tight text-slate-950 sm:text-4xl">
                        Danh sách Homestay
                    </h1>
                    <p class="mt-2 max-w-2xl text-slate-500">
                        Tìm kiếm và lựa chọn Homestay phù hợp với địa điểm,
                        ngân sách và nhu cầu của bạn.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <main class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">

        {{-- ===== DESKTOP: FILTER TRÁI + LIST 3 CỘT PHẢI ===== --}}
        <div class="flex flex-col gap-8 lg:flex-row">

            {{-- BỘ LỌC BÊN TRÁI --}}
            <aside class="hidden w-64 shrink-0 lg:block">
                <div class="sticky top-24 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="mb-5 flex items-center justify-between">
                        <h2 class="text-lg font-bold text-slate-900">Bộ lọc</h2>
                        @if ($activeFilterCount > 0)
                            <span class="flex h-7 min-w-7 items-center justify-center rounded-full bg-blue-600 px-2 text-xs font-bold text-white">
                                {{ $activeFilterCount }}
                            </span>
                        @endif
                    </div>

                    <form method="GET" action="{{ route('homestays.index') }}" class="space-y-4">
                        <div>
                            <label for="search" class="text-sm font-semibold text-slate-700">Tên Homestay</label>
                            <input type="text" id="search" name="search" value="{{ request('search') }}"
                                placeholder="Nhập tên..."
                                class="mt-1 w-full rounded-xl border border-slate-300 px-3 py-2 text-sm outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-100">
                        </div>

                        <div>
                            <label for="location" class="text-sm font-semibold text-slate-700">Địa điểm</label>
                            <input type="text" id="location" name="location" value="{{ request('location') }}"
                                placeholder="Thành phố..."
                                class="mt-1 w-full rounded-xl border border-slate-300 px-3 py-2 text-sm outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-100">
                        </div>

                        <div
                            x-data="{
                                step: 100000,

                                minRaw: @js(
                                    request()->filled('min_price')
                                        ? (int) request('min_price')
                                        : null
                                ),

                                maxRaw: @js(
                                    request()->filled('max_price')
                                        ? (int) request('max_price')
                                        : null
                                ),

                                minDisplay: '',
                                maxDisplay: '',

                                init() {
                                    this.minDisplay = this.formatPrice(this.minRaw);
                                    this.maxDisplay = this.formatPrice(this.maxRaw);
                                },

                                parsePrice(value) {
                                    const number = String(value ?? '')
                                        .replace(/\D/g, '');

                                    return number === ''
                                        ? null
                                        : Number(number);
                                },

                                formatPrice(value) {
                                    if (
                                        value === null ||
                                        value === ''
                                    ) {
                                        return '';
                                    }

                                    return new Intl.NumberFormat('vi-VN')
                                        .format(Number(value));
                                },

                                updateMin(event) {
                                    this.minRaw = this.parsePrice(
                                        event.target.value
                                    );

                                    this.minDisplay = this.formatPrice(
                                        this.minRaw
                                    );

                                    event.target.value = this.minDisplay;
                                },

                                updateMax(event) {
                                    this.maxRaw = this.parsePrice(
                                        event.target.value
                                    );

                                    this.maxDisplay = this.formatPrice(
                                        this.maxRaw
                                    );

                                    event.target.value = this.maxDisplay;
                                },

                                changeMin(amount) {
                                    const currentValue = Number(
                                        this.minRaw ?? 0
                                    );

                                    this.minRaw = Math.max(
                                        0,
                                        currentValue + amount
                                    );

                                    this.minDisplay = this.formatPrice(
                                        this.minRaw
                                    );
                                },

                                changeMax(amount) {
                                    const currentValue = Number(
                                        this.maxRaw ?? 0
                                    );

                                    this.maxRaw = Math.max(
                                        0,
                                        currentValue + amount
                                    );

                                    this.maxDisplay = this.formatPrice(
                                        this.maxRaw
                                    );
                                },

                                resetPrice() {
                                    this.minRaw = null;
                                    this.maxRaw = null;

                                    this.minDisplay = '';
                                    this.maxDisplay = '';
                                }
                            }"
                        >
                            <div class="flex items-center justify-between gap-3">

                                <p class="text-sm font-semibold text-slate-700">
                                    Khoảng giá / đêm
                                </p>

                                <button
                                    type="button"
                                    @click="resetPrice()"
                                    class="cursor-pointer text-xs font-semibold text-blue-600 transition hover:text-blue-700 hover:underline"
                                >
                                    Đặt lại
                                </button>

                            </div>

                            <div class="space-y-2">

                                {{-- Giá từ --}}
                                <div>

                                    <label class="text-xs font-semibold text-slate-500">
                                        Giá từ
                                    </label>

                                    <div class="mt-1 flex overflow-hidden rounded-xl border border-slate-300 bg-white transition focus-within:border-blue-500 focus-within:ring-4 focus-within:ring-blue-100">

                                        <button
                                            type="button"
                                            @click="changeMin(-step)"
                                            class="flex w-10 shrink-0 cursor-pointer items-center justify-center border-r border-slate-200 text-lg font-semibold text-slate-500 transition hover:bg-slate-100 hover:text-blue-600"
                                            aria-label="Giảm giá tối thiểu"
                                        >
                                            −
                                        </button>

                                        <div class="relative min-w-0 flex-1">

                                            <input
                                                type="text"
                                                inputmode="numeric"
                                                autocomplete="off"
                                                :value="minDisplay"
                                                @input="updateMin($event)"
                                                @keydown.arrow-up.prevent="changeMin(step)"
                                                @keydown.arrow-down.prevent="changeMin(-step)"
                                                placeholder="0"
                                                class="w-full border-0 bg-transparent px-3 py-3 pr-8 text-sm font-semibold text-slate-800 outline-none placeholder:font-normal placeholder:text-slate-400 focus:ring-0"
                                            >

                                            <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3 text-xs font-semibold text-slate-400">
                                                đ
                                            </span>

                                        </div>

                                        <button
                                            type="button"
                                            @click="changeMin(step)"
                                            class="flex w-10 shrink-0 cursor-pointer items-center justify-center border-l border-slate-200 text-lg font-semibold text-slate-500 transition hover:bg-slate-100 hover:text-blue-600"
                                            aria-label="Tăng giá tối thiểu"
                                        >
                                            +
                                        </button>

                                    </div>

                                    <input
                                        type="hidden"
                                        name="min_price"
                                        :value="minRaw ?? ''"
                                    >

                                </div>

                                {{-- Giá đến --}}
                                <div>

                                    <label class="text-xs font-semibold text-slate-500">
                                        Giá đến
                                    </label>

                                    <div class="mt-1 flex overflow-hidden rounded-xl border border-slate-300 bg-white transition focus-within:border-blue-500 focus-within:ring-4 focus-within:ring-blue-100">

                                        <button
                                            type="button"
                                            @click="changeMax(-step)"
                                            class="flex w-10 shrink-0 cursor-pointer items-center justify-center border-r border-slate-200 text-lg font-semibold text-slate-500 transition hover:bg-slate-100 hover:text-blue-600"
                                            aria-label="Giảm giá tối đa"
                                        >
                                            −
                                        </button>

                                        <div class="relative min-w-0 flex-1">

                                            <input
                                                type="text"
                                                inputmode="numeric"
                                                autocomplete="off"
                                                :value="maxDisplay"
                                                @input="updateMax($event)"
                                                @keydown.arrow-up.prevent="changeMax(step)"
                                                @keydown.arrow-down.prevent="changeMax(-step)"
                                                placeholder="Không giới hạn"
                                                class="w-full border-0 bg-transparent px-3 py-3 pr-8 text-sm font-semibold text-slate-800 outline-none placeholder:text-xs placeholder:font-normal placeholder:text-slate-400 focus:ring-0"
                                            >

                                            <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3 text-xs font-semibold text-slate-400">
                                                đ
                                            </span>

                                        </div>

                                        <button
                                            type="button"
                                            @click="changeMax(step)"
                                            class="flex w-10 shrink-0 cursor-pointer items-center justify-center border-l border-slate-200 text-lg font-semibold text-slate-500 transition hover:bg-slate-100 hover:text-blue-600"
                                            aria-label="Tăng giá tối đa"
                                        >
                                            +
                                        </button>

                                    </div>

                                    <input
                                        type="hidden"
                                        name="max_price"
                                        :value="maxRaw ?? ''"
                                    >

                                </div>

                            </div>

                            <p class="mt-1 text-xs leading-5 text-slate-400">
                                Mỗi bước tăng hoặc giảm là
                                <span class="font-semibold text-slate-500">
                                    100.000đ
                                </span>
                            </p>

                        </div>

                        <div>
                            <label for="guests" class="text-sm font-semibold text-slate-700">Số khách</label>
                            <input type="number" id="guests" name="guests" value="{{ request('guests') }}"
                                min="1" max="50" placeholder="Ví dụ: 4"
                                class="mt-1 w-full rounded-xl border border-slate-300 px-3 py-2 text-sm outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-100">
                        </div>

                        <div>
                            <label for="room-type" class="text-sm font-semibold text-slate-700">Loại phòng</label>
                            <select id="room-type" name="room_type"
                                class="mt-1 w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-sm outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-100">
                                <option value="">Tất cả</option>
                                @foreach ($roomTypes as $roomType)
                                    <option value="{{ $roomType }}" @selected(request('room_type') === $roomType)>
                                        {{ $roomType }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="rating" class="text-sm font-semibold text-slate-700">Đánh giá</label>
                            <select id="rating" name="rating"
                                class="mt-1 w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-sm outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-100">
                                <option value="">Tất cả</option>
                                @for ($star = 5; $star >= 1; $star--)
                                    <option value="{{ $star }}" @selected((int) request('rating') === $star)>
                                        Từ {{ $star }} sao
                                    </option>
                                @endfor
                            </select>
                        </div>

                        @if ($amenities->isNotEmpty())
                            <div>
                                <p class="text-sm font-semibold text-slate-700">Tiện ích</p>
                                <div class="mt-1.5 max-h-44 space-y-1 overflow-y-auto pr-1">
                                    @foreach ($amenities as $amenity)
                                        <label class="flex cursor-pointer items-center gap-2 rounded-lg px-1.5 py-1.5 hover:bg-slate-50">
                                            <input type="checkbox" name="amenities[]" value="{{ $amenity->id }}"
                                                @checked(in_array($amenity->id, $selectedAmenities))
                                                class="h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                                            <span class="min-w-0 truncate text-sm text-slate-600">
                                                {{ $amenity->icon ?: '✨' }} {{ $amenity->name }}
                                            </span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <input type="hidden" name="sort" value="{{ request('sort', 'popular') }}">

                        <div class="space-y-2 border-t border-slate-200 pt-4">
                            <button type="submit"
                                class="w-full cursor-pointer rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-blue-700">
                                Áp dụng bộ lọc
                            </button>
                            <a href="{{ route('homestays.index') }}"
                                class="flex w-full items-center justify-center rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                                Xóa tất cả
                            </a>
                        </div>
                    </form>
                </div>
            </aside>

            {{-- DANH SÁCH BÊN PHẢI --}}
            <section class="min-w-0 flex-1">

                {{-- Sort bar --}}
                <div class="mb-5 flex flex-col gap-3 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <p class="font-bold text-slate-900">Kết quả tìm kiếm</p>
                        <p class="mt-0.5 text-sm text-slate-500">
                            Hiển thị {{ $homestays->firstItem() ?? 0 }} – {{ $homestays->lastItem() ?? 0 }}
                            / {{ $homestays->total() }} Homestay
                        </p>
                    </div>

                    <form method="GET" action="{{ route('homestays.index') }}" class="flex items-center gap-2">
                        @foreach (request()->except(['sort', 'page']) as $key => $value)
                            @if (is_array($value))
                                @foreach ($value as $item)
                                    <input type="hidden" name="{{ $key }}[]" value="{{ $item }}">
                                @endforeach
                            @else
                                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                            @endif
                        @endforeach

                        <label for="sort" class="shrink-0 text-sm font-semibold text-slate-600">Sắp xếp:</label>
                        <select id="sort" name="sort" onchange="this.form.submit()"
                            class="rounded-xl border border-slate-300 bg-white px-3 py-2 text-sm font-semibold text-slate-700 outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-100">
                            <option value="popular" @selected($sort === 'popular')>Phổ biến nhất</option>
                            <option value="bookings_desc" @selected($sort === 'bookings_desc')>Nhiều lượt đặt nhất</option>
                            <option value="rating_desc" @selected($sort === 'rating_desc')>Đánh giá cao nhất</option>
                            <option value="price_asc" @selected($sort === 'price_asc')>Giá thấp → cao</option>
                            <option value="price_desc" @selected($sort === 'price_desc')>Giá cao → thấp</option>
                            <option value="latest" @selected($sort === 'latest')>Mới nhất</option>
                        </select>
                    </form>
                </div>

                @if ($homestays->isEmpty())
                    <div class="rounded-2xl border border-dashed border-slate-300 bg-white px-6 py-16 text-center">
                        <div class="text-5xl">🔎</div>
                        <h2 class="mt-4 text-xl font-bold text-slate-900">Không tìm thấy Homestay phù hợp</h2>
                        <p class="mx-auto mt-2 max-w-sm text-sm text-slate-500">
                            Thử đổi địa điểm, khoảng giá hoặc bỏ bớt điều kiện lọc.
                        </p>
                        <a href="{{ route('homestays.index') }}"
                            class="mt-5 inline-flex rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-blue-700">
                            Xóa bộ lọc
                        </a>
                    </div>
                @else
                    {{-- GRID 3 CỘT --}}
                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach ($homestays as $homestay)
                            @php
                                $averageRating = (float) ($homestay->average_rating ?? 0);
                                $reviewCount   = (int) ($homestay->approved_reviews_count ?? 0);
                                $bookingCount  = (int) ($homestay->bookings_count ?? 0);
                                $minimumPrice  = $homestay->min_room_price
                                    ?? $homestay->base_price
                                    ?? 0;
                            @endphp

                            <article class="group flex h-full flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition hover:-translate-y-0.5 hover:border-blue-200 hover:shadow-md">
                                {{-- Ảnh --}}
                                <div class="relative aspect-4/3 overflow-hidden bg-slate-100">
                                    @if ($homestay->thumbnail)
                                        <img
                                            src="{{ Storage::url($homestay->thumbnail) }}"
                                            alt="{{ $homestay->name }}"
                                            class="h-full w-full object-cover transition duration-500 group-hover:scale-105"
                                            loading="lazy"
                                        >
                                    @else
                                        <div class="flex h-full items-center justify-center">
                                            <div class="text-center">
                                                <div class="text-4xl">🏡</div>
                                                <p class="mt-1 text-xs text-slate-400">Chưa có hình</p>
                                            </div>
                                        </div>
                                    @endif

                                    <span class="absolute left-2.5 top-2.5 rounded-full px-2.5 py-1 text-[11px] font-semibold border border-slate-200 text-slate-900 shadow-xl backdrop-blur">
                                        {{ $homestay->category?->name ?? 'Homestay' }}
                                    </span>

                                    @if ($reviewCount > 0)
                                        <span class="absolute right-2.5 top-2.5 inline-flex items-center gap-0.5 rounded-full bg-white/95 px-2.5 py-1 text-[11px] font-bold text-slate-800 shadow-sm backdrop-blur">
                                            <x-icon-star class="h-3 w-3 text-amber-400" />
                                            {{ number_format($averageRating, 1) }}
                                        </span>
                                    @endif
                                </div>

                                {{-- Nội dung --}}
                                <div class="flex flex-1 flex-col p-3.5">
                                    <p class="truncate text-xs font-semibold text-blue-600">
                                        {{ $homestay->city ?: 'Chưa cập nhật' }}
                                    </p>

                                    <h2 class="mt-0.5 line-clamp-1 text-[15px] font-bold text-slate-950">
                                        {{ $homestay->name }}
                                    </h2>

                                    <div class="mt-2 flex flex-wrap gap-1.5">
                                        <span class="rounded-full bg-blue-50 px-3 py-0.5 border border-blue-200 text-[10px] font-semibold text-blue-700">
                                            {{ $bookingCount }}
                                            lượt đặt
                                        </span>
                                        <span class="inline-flex items-center gap-0.5 rounded-full bg-amber-50 px-3 py-0.5 border border-amber-200 text-[10px] font-semibold text-amber-700">
                                            <x-icon-star class="h-2.5 w-2.5 text-amber-400" />
                                            {{ $reviewCount }}
                                            đánh giá
                                        </span>
                                    </div>

                                    <p class="mt-2 line-clamp-2 text-xs leading-5 text-slate-500">
                                        {{ \Illuminate\Support\Str::limit(
                                            $homestay->description
                                                ?: 'Không gian nghỉ dưỡng tiện nghi, phù hợp gia đình & nhóm bạn.',
                                            80
                                        ) }}
                                    </p>

                                    @if ($homestay->amenities->isNotEmpty())
                                        <div class="mt-2 flex flex-wrap gap-1">
                                            @foreach ($homestay->amenities->take(2) as $amenity)
                                                <span class="rounded-md bg-slate-100 px-1.5 py-0.5 text-[11px] font-semibold text-slate-600">
                                                    {{ $amenity->icon ?: '✨' }} {{ $amenity->name }}
                                                </span>
                                            @endforeach
                                            @if ($homestay->amenities->count() > 2)
                                                <span class="rounded-md bg-slate-100 px-1.5 py-0.5 text-[11px] font-semibold text-slate-500">
                                                    +{{ $homestay->amenities->count() - 2 }}
                                                </span>
                                            @endif
                                        </div>
                                    @endif

                                    <div class="mt-auto flex items-end justify-between gap-2 border-t border-slate-100 pt-3">
                                        <div>
                                            <p class="text-[10px] text-slate-400">Giá từ</p>
                                            <span class="text-base font-bold text-blue-600">
                                                {{ number_format($minimumPrice, 0, ',', '.') }}đ
                                            </span>
                                            <span class="text-[10px] text-slate-400">/ đêm</span>
                                        </div>
                                        <a href="{{ route('homestays.show', $homestay->slug) }}"
                                            class="shrink-0 rounded-xl bg-blue-600 px-6 py-1.5 text-xs font-semibold text-white hover:bg-blue-700">
                                            Chi tiết
                                        </a>
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>

                    @if ($homestays->hasPages())
                        <div class="mt-8">
                            {{ $homestays->links() }}
                        </div>
                    @endif
                @endif
            </section>
            
        </div>
    </main>

@endsection