@extends('layouts.app')

@section('title', $category->name . ' | HomeStayGo')

@section('content')

    @php
        $selectedAmenities = collect(
            request('amenities', [])
        )
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
        ])
            ->filter(fn ($value) => filled($value))
            ->count()
            + count($selectedAmenities);

        $categoryUrl = route(
            'categories.show',
            $category
        );
    @endphp

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
                'label' => $category->name,
            ],
        ]"
    />

    {{-- Header --}}
    <section class="border-b border-slate-200 bg-white">

        <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">

            <p class="font-semibold uppercase tracking-widest text-blue-600">
                Danh mục Homestay
            </p>

            <div class="mt-2">
                <h1 class="text-3xl font-bold tracking-tight text-slate-950 sm:text-4xl">
                    {{ $category->name }}
                </h1>
            
                <p class="mt-2 max-w-2xl text-slate-500">
                    Khám phá các Homestay thuộc danh mục
                </p>
            </div>

        </div>

    </section>

    <main class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">

        <div class="flex flex-col gap-8 lg:flex-row">

            {{-- Bộ lọc bên trái --}}
            <aside class="hidden w-64 shrink-0 lg:block">

                <div class="sticky top-24 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">

                    <div class="mb-5 flex items-center justify-between">


                        <h2 class="text-lg font-bold text-slate-900">Bộ lọc</h2>

                        @if ($activeFilterCount > 0)

                            <span class="flex h-8 min-w-8 items-center justify-center rounded-full bg-blue-600 px-2 text-xs font-bold text-white">
                                {{ $activeFilterCount }}
                            </span>

                        @endif

                    </div>

                    @include('homestays.partials.filter-form', [
                        'prefix' => 'category-desktop',
                        'filterAction' => $categoryUrl,
                        'resetUrl' => $categoryUrl,
                    ])

                </div>

            </aside>

            {{-- Danh sách Homestay --}}
            <section class="min-w-0 flex-1">

                {{-- Thanh sắp xếp --}}
                <div class="mb-6 flex flex-col gap-4 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:flex-row sm:items-center sm:justify-between">

                    <div>

                        <p class="font-bold text-slate-900">
                            Homestay thuộc {{ $category->name }}
                        </p>

                        <p class="mt-1 text-sm text-slate-500">
                            Hiển thị
                            {{ $homestays->firstItem() ?? 0 }}
                            –
                            {{ $homestays->lastItem() ?? 0 }}
                            trong tổng số
                            {{ $homestays->total() }}
                            kết quả
                        </p>

                    </div>

                    <form
                        method="GET"
                        action="{{ $categoryUrl }}"
                        class="flex items-center gap-3"
                    >

                        @foreach (
                            request()->except([
                                'sort',
                                'page',
                            ])
                            as $key => $value
                        )

                            @if (is_array($value))

                                @foreach ($value as $item)

                                    <input
                                        type="hidden"
                                        name="{{ $key }}[]"
                                        value="{{ $item }}"
                                    >

                                @endforeach

                            @else

                                <input
                                    type="hidden"
                                    name="{{ $key }}"
                                    value="{{ $value }}"
                                >

                            @endif

                        @endforeach

                        <label
                            for="category-sort"
                            class="hidden shrink-0 text-sm font-semibold text-slate-600 sm:block"
                        >
                            Sắp xếp:
                        </label>

                        <select
                            id="category-sort"
                            name="sort"
                            onchange="this.form.submit()"
                            class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100 sm:w-auto"
                        >
                            <option
                                value="popular"
                                @selected($sort === 'popular')
                            >
                                Phổ biến nhất
                            </option>

                            <option
                                value="bookings_desc"
                                @selected($sort === 'bookings_desc')
                            >
                                Nhiều lượt đặt nhất
                            </option>

                            <option
                                value="rating_desc"
                                @selected($sort === 'rating_desc')
                            >
                                Đánh giá cao nhất
                            </option>

                            <option
                                value="price_asc"
                                @selected($sort === 'price_asc')
                            >
                                Giá thấp đến cao
                            </option>

                            <option
                                value="price_desc"
                                @selected($sort === 'price_desc')
                            >
                                Giá cao đến thấp
                            </option>

                            <option
                                value="latest"
                                @selected($sort === 'latest')
                            >
                                Mới nhất
                            </option>

                        </select>

                    </form>

                </div>

                @if ($homestays->isEmpty())

                    <div class="rounded-3xl border border-dashed border-slate-300 bg-white px-6 py-16 text-center shadow-sm">

                        <div class="text-6xl">
                            🏡
                        </div>

                        <h2 class="mt-5 text-2xl font-bold text-slate-900">
                            Chưa có Homestay phù hợp
                        </h2>

                        <p class="mx-auto mt-3 max-w-md text-sm leading-6 text-slate-500">
                            Danh mục này chưa có Homestay phù hợp với các điều kiện lọc hiện tại.
                        </p>

                        <a
                            href="{{ $categoryUrl }}"
                            class="mt-6 inline-flex items-center justify-center rounded-xl bg-blue-600 px-6 py-3 text-sm font-semibold text-white transition hover:bg-blue-700"
                        >
                            Xóa bộ lọc
                        </a>

                    </div>

                @else

                    <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">

                        @foreach ($homestays as $homestay)

                            @php
                                $averageRating = (float) (
                                    $homestay->average_rating ?? 0
                                );

                                $reviewCount = (int) (
                                    $homestay->approved_reviews_count ?? 0
                                );

                                $bookingCount = (int) (
                                    $homestay->bookings_count ?? 0
                                );

                                $minimumPrice =
                                    $homestay->min_room_price
                                    ?? $homestay->base_price
                                    ?? 0;
                            @endphp

                            <article class="group flex h-full flex-col overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm transition duration-300 hover:-translate-y-1 hover:border-blue-200 hover:shadow-xl">

                                {{-- Ảnh --}}
                                <div class="relative aspect-[4/3] overflow-hidden bg-slate-100">

                                    @if ($homestay->thumbnail)

                                        <img
                                            src="{{ Storage::url($homestay->thumbnail) }}"
                                            alt="{{ $homestay->name }}"
                                            loading="lazy"
                                            class="h-full w-full object-cover transition duration-500 group-hover:scale-105"
                                        >

                                    @else

                                        <div class="flex h-full items-center justify-center text-center">

                                            <div>

                                                <div class="text-5xl">
                                                    🏡
                                                </div>

                                                <p class="mt-2 text-xs font-medium text-slate-400">
                                                    Chưa có hình ảnh
                                                </p>

                                            </div>

                                        </div>

                                    @endif

                                    <span class="absolute left-3 top-3 max-w-[65%] truncate rounded-full bg-white/95 px-3 py-1.5 text-xs font-semibold text-blue-700 shadow-sm backdrop-blur">
                                        {{ $homestay->category?->name ?? 'Homestay' }}
                                    </span>

                                    @if ($reviewCount > 0)

                                        <span class="absolute right-3 top-3 inline-flex items-center gap-1 rounded-full bg-white/95 px-3 py-1.5 text-xs font-bold text-slate-800 shadow-sm backdrop-blur">

                                            <x-icon-star class="h-3.5 w-3.5 text-amber-400" />

                                            {{ number_format(
                                                $averageRating,
                                                1
                                            ) }}

                                        </span>

                                    @endif

                                </div>

                                {{-- Nội dung --}}
                                <div class="flex flex-1 flex-col p-5">

                                    <p class="truncate text-xs font-semibold uppercase tracking-wide text-blue-600">
                                        {{ $homestay->city ?: 'Chưa cập nhật địa điểm' }}
                                    </p>

                                    <h2 class="mt-2 line-clamp-1 text-lg font-bold text-slate-950">
                                        {{ $homestay->name }}
                                    </h2>

                                    <div class="mt-3 flex flex-wrap gap-2">

                                        <span class="inline-flex items-center gap-1 rounded-full bg-blue-50 px-2.5 py-1 text-[11px] font-semibold text-blue-700">
                                            📅 {{ $bookingCount }} lượt đặt
                                        </span>

                                        <span class="inline-flex items-center gap-1 rounded-full bg-amber-50 px-2.5 py-1 text-[11px] font-semibold text-amber-700">

                                            <x-icon-star class="h-3 w-3 text-amber-400" />

                                            {{ $reviewCount }} đánh giá

                                        </span>

                                    </div>

                                    <p class="mt-4 line-clamp-2 min-h-10 text-sm leading-5 text-slate-500">
                                        {{ \Illuminate\Support\Str::limit(
                                            $homestay->description
                                                ?: 'Không gian nghỉ dưỡng tiện nghi, phù hợp cho gia đình và nhóm bạn.',
                                            90
                                        ) }}
                                    </p>

                                    @if ($homestay->amenities->isNotEmpty())

                                        <div class="mt-4 flex flex-wrap gap-1.5">

                                            @foreach ($homestay->amenities->take(2) as $amenity)

                                                <span class="rounded-lg bg-slate-100 px-2 py-1 text-[11px] font-medium text-slate-600">
                                                    {{ $amenity->icon ?: '✨' }}
                                                    {{ $amenity->name }}
                                                </span>

                                            @endforeach

                                            @if ($homestay->amenities->count() > 2)

                                                <span class="rounded-lg bg-slate-100 px-2 py-1 text-[11px] font-medium text-slate-500">
                                                    +{{ $homestay->amenities->count() - 2 }}
                                                </span>

                                            @endif

                                        </div>

                                    @endif

                                    <div class="mt-auto flex items-end justify-between gap-3 border-t border-slate-100 pt-4">

                                        <div class="min-w-0">

                                            <p class="text-xs text-slate-400">
                                                Giá phòng từ
                                            </p>

                                            <p class="mt-1 truncate text-lg font-bold text-blue-600">
                                                {{ number_format(
                                                    $minimumPrice,
                                                    0,
                                                    ',',
                                                    '.'
                                                ) }}đ
                                            </p>

                                            <p class="text-[11px] text-slate-400">
                                                mỗi đêm
                                            </p>

                                        </div>

                                        <a
                                            href="{{ route(
                                                'homestays.show',
                                                $homestay->slug
                                            ) }}"
                                            class="inline-flex shrink-0 items-center justify-center rounded-xl bg-blue-600 px-4 py-2.5 text-xs font-semibold text-white transition hover:bg-blue-700 focus:ring-4 focus:ring-blue-100"
                                        >
                                            Xem chi tiết
                                        </a>

                                    </div>

                                </div>

                            </article>

                        @endforeach

                    </div>

                    @if ($homestays->hasPages())

                        <div class="mt-10">
                            {{ $homestays->links() }}
                        </div>

                    @endif

                @endif

            </section>

        </div>

    </main>

@endsection