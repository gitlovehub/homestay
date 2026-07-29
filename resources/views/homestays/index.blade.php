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
            <div class="mt-2">
                <h1 class="text-3xl font-bold tracking-tight text-slate-950 sm:text-4xl">
                    Danh sách Homestay
                </h1>
                <p class="mt-2 max-w-2xl text-slate-500">
                    Tìm kiếm và lựa chọn Homestay phù hợp với địa điểm,
                    ngân sách và nhu cầu của bạn.
                </p>
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

                    @include('homestays.partials.filter-form', [
                        'prefix' => 'desktop',
                    ])

                </div>
            </aside>

            {{-- DANH SÁCH BÊN PHẢI --}}
            <section class="min-w-0 flex-1">

                {{-- Sort bar --}}
                <div class="mb-6 flex flex-col gap-4 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:flex-row sm:items-center sm:justify-between">
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
