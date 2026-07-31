@extends('layouts.app')

@section('title', 'HomeStayGo - Đặt phòng Homestay')

@section('content')
    {{-- Hero --}}
    <section class="relative overflow-hidden bg-gradient-to-br from-blue-50 via-white to-indigo-50">
        <div class="absolute -right-24 -top-24 h-80 w-80 rounded-full bg-blue-200/40 blur-3xl"></div>

        <div class="mx-auto grid max-w-7xl items-center gap-12 px-4 py-16 sm:px-6 lg:grid-cols-2 lg:px-8 lg:py-24">
            <div class="relative">
                <span class="inline-flex rounded-full bg-blue-100 px-4 py-2 text-sm font-semibold text-blue-700">
                    Đặt phòng nhanh chóng và tiện lợi
                </span>

                <h1 class="mt-6 max-w-2xl text-4xl font-bold tracking-tight text-slate-950 sm:text-5xl lg:text-6xl">
                    Tìm Homestay lý tưởng cho
                    <span class="text-blue-600">
                        chuyến đi của bạn
                    </span>
                </h1>

                <p class="mt-6 max-w-xl text-lg leading-8 text-slate-600">
                    Khám phá những Homestay đẹp, tiện nghi, giá hợp lý
                    tại các địa điểm du lịch nổi tiếng.
                </p>

                <div class="mt-8 flex flex-wrap gap-4">
                    <a
                        href="{{ route('homestays.index') }}"
                        class="rounded-xl bg-blue-600 px-6 py-3.5 font-semibold text-white shadow-lg shadow-blue-600/20 transition hover:-translate-y-0.5 hover:bg-blue-700"
                    >
                        Khám phá ngay
                    </a>

                    <a
                        href="#about"
                        class="rounded-xl border border-slate-300 bg-white px-6 py-3.5 font-semibold text-slate-700 transition hover:border-blue-600 hover:text-blue-600"
                    >
                        Tìm hiểu thêm
                    </a>
                </div>

                <div class="mt-10 grid max-w-lg grid-cols-3 gap-5">
                    <div>
                        <p class="text-2xl font-bold text-slate-950">
                            {{ number_format($totalHomestays) }}+
                        </p>

                        <p class="text-sm text-slate-500">
                            Homestay
                        </p>
                    </div>

                    <div>
                        <p class="text-2xl font-bold text-slate-950">
                            {{ number_format($totalLocations) }}+
                        </p>

                        <p class="text-sm text-slate-500">
                            Thành phố
                        </p>
                    </div>

                    <div>
                        <p class="text-2xl font-bold text-slate-950">
                            {{ number_format($averageRating, 1) }}/5
                        </p>

                        <p class="text-sm text-slate-500">
                            Đánh giá
                        </p>
                    </div>
                </div>
            </div>

            <div class="relative">
                <div class="overflow-hidden rounded-[2rem] bg-white p-3 shadow-2xl shadow-slate-900/15">
                    <img
                        src="https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=1200&q=85"
                        alt="Homestay nổi bật"
                        class="h-[480px] w-full rounded-[1.5rem] object-cover"
                    >
                </div>

                <div class="absolute -bottom-6 -left-6 hidden rounded-2xl bg-white p-4 shadow-xl sm:block">
                    <div class="flex items-center gap-3">
                        <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-emerald-100 text-xl">
                            ✓
                        </span>

                        <div>
                            <p class="font-bold text-slate-900">
                                Đặt phòng an toàn
                            </p>

                            <p class="text-sm text-slate-500">
                                Xác nhận nhanh chóng
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Search --}}
    <section class="relative z-10 -mt-5 px-4 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-7xl rounded-3xl border border-slate-200 bg-white p-5 shadow-xl shadow-slate-900/10">
            <form
                action="{{ route('home') }}"
                method="GET"
                class="grid gap-4 md:grid-cols-2 lg:grid-cols-5"
            >
                {{-- Chọn địa điểm --}}
                <div class="lg:col-span-2">
                    <label
                        for="location-trigger"
                        class="mb-2 block text-sm font-semibold text-slate-700"
                    >
                        Địa điểm
                    </label>

                    <div
                        id="location-picker"
                        class="relative w-full"
                    >
                        {{-- Giá trị gửi lên Controller --}}
                        <input
                            id="location-input"
                            type="hidden"
                            name="location"
                            value="{{ request('location') }}"
                        >

                        {{-- Ô hiển thị địa điểm --}}
                        <button
                            id="location-trigger"
                            type="button"
                            class="flex min-h-[50px] w-full items-center gap-3 rounded-xl border border-slate-300 bg-white px-4 py-3 pr-20 text-left outline-none transition hover:border-blue-400 focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                        >
                            {{-- Icon địa điểm --}}
                            <svg
                                class="h-5 w-5 shrink-0 text-slate-500"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.8"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M20 10c0 5-8 11-8 11S4 15 4 10a8 8 0 1 1 16 0Z"
                                />

                                <circle
                                    cx="12"
                                    cy="10"
                                    r="2.5"
                                />
                            </svg>

                            <span
                                id="location-text"
                                class="min-w-0 flex-1 truncate text-base text-slate-500"
                            >
                                {{ request('location') ?: 'Bạn muốn đi đâu?' }}
                            </span>
                        </button>

                        {{-- Nút X xóa địa điểm --}}
                        <button
                            id="clear-location"
                            type="button"
                            aria-label="Xóa địa điểm đã chọn"
                            class="absolute right-10 top-1/2 z-10 flex h-7 w-7 -translate-y-1/2 items-center justify-center rounded-full text-lg text-slate-400 transition hover:bg-red-50 hover:text-red-600
                                {{ request('location') ? '' : 'hidden' }}"
                        >
                            ×
                        </button>

                        {{-- Mũi tên --}}
                        <svg
                            id="location-arrow"
                            class="pointer-events-none absolute right-4 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400 transition duration-200"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="m6 9 6 6 6-6"
                            />
                        </svg>

                        {{-- Danh sách địa điểm --}}
                        <div
                            id="location-menu"
                            class="absolute left-0 right-0 top-full z-50 mt-2 hidden rounded-2xl border border-slate-200 bg-white p-3 shadow-2xl"
                        >
                            <button
                                type="button"
                                data-location=""
                                class="location-option mb-2 flex w-full items-center rounded-xl px-3 py-2.5 text-left text-sm font-semibold text-slate-700 transition hover:bg-blue-50 hover:text-blue-700"
                            >
                                Tất cả địa điểm
                            </button>

                            <div class="border-t border-slate-100 pt-2">
                                <div class="grid max-h-64 grid-cols-1 gap-1 overflow-y-auto sm:grid-cols-2">

                                    @forelse ($locations as $location)
                                        <button
                                            type="button"
                                            data-location="{{ $location }}"
                                            class="location-option flex items-center gap-2 rounded-xl px-3 py-2.5 text-left text-sm font-medium transition
                                                {{ request('location') === $location
                                                    ? 'bg-blue-50 text-blue-700'
                                                    : 'text-slate-600 hover:bg-blue-50 hover:text-blue-700'
                                                }}"
                                        >
                                            <svg
                                                class="h-4 w-4 shrink-0"
                                                viewBox="0 0 24 24"
                                                fill="none"
                                                stroke="currentColor"
                                                stroke-width="1.8"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    d="M20 10c0 5-8 11-8 11S4 15 4 10a8 8 0 1 1 16 0Z"
                                                />

                                                <circle
                                                    cx="12"
                                                    cy="10"
                                                    r="2.5"
                                                />
                                            </svg>

                                            <span class="truncate">
                                                {{ $location }}
                                            </span>
                                        </button>
                                    @empty
                                        <p class="col-span-full px-3 py-4 text-center text-sm text-slate-500">
                                            Chưa có địa điểm
                                        </p>
                                    @endforelse

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <label
                        for="check_in"
                        class="mb-2 block text-sm font-semibold text-slate-700"
                    >
                        Ngày nhận phòng
                    </label>

                    <input
                        id="check_in"
                        type="date"
                        name="check_in"
                        class="w-full rounded-xl border border-slate-300 px-4 py-3 outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                    >
                </div>

                <div>
                    <label
                        for="check_out"
                        class="mb-2 block text-sm font-semibold text-slate-700"
                    >
                        Ngày trả phòng
                    </label>

                    <input
                        id="check_out"
                        type="date"
                        name="check_out"
                        class="w-full rounded-xl border border-slate-300 px-4 py-3 outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                    >
                </div>

                <div class="flex items-end">
                    <button
                        type="submit"
                        class="w-full rounded-xl bg-blue-600 px-5 py-3 font-semibold text-white transition hover:bg-blue-700"
                    >
                        Tìm kiếm
                    </button>
                </div>
            </form>

            <p class="mt-3 text-xs text-slate-400">
                Chức năng tìm kiếm sẽ được kết nối ở phần tiếp theo.
            </p>
        </div>
    </section>

    {{-- Featured --}}
    <section
        id="featured"
        class="py-20"
    >
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col justify-between gap-5 sm:flex-row sm:items-end">
                <div>
                    <p class="font-semibold uppercase tracking-widest text-blue-600">
                        Khám phá
                    </p>

                    <h2 class="mt-2 text-3xl font-bold tracking-tight text-slate-950 sm:text-4xl">
                        Homestay nổi bật
                    </h2>

                    <p class="mt-3 text-slate-600">
                        Những địa điểm lưu trú mới nhất trên hệ thống.
                    </p>
                </div>


                <div class="flex flex-wrap items-center gap-3">
                    <a
                        href="{{ route('home') }}"
                        class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-blue-200 hover:bg-blue-50 hover:text-blue-700"
                    >
                        Xem tất cả
                    </a>


                    <button
                        type="button"
                        id="openFilterModal"
                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-blue-600/20 transition hover:-translate-y-0.5 hover:bg-blue-700"
                    >
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M7 12h10M10 18h4"/>
                        </svg>
                        Bộ lọc
                    </button>
                </div>
            </div>

            @if ($homestays->isEmpty())
                <div class="mt-10 rounded-3xl border border-dashed border-slate-300 bg-white p-12 text-center">
                    <h3 class="text-xl font-bold text-slate-900">
                        Chưa có dữ liệu Homestay
                    </h3>

                    <p class="mt-2 text-slate-500">
                        Hãy chạy lệnh migrate và seeder để tạo dữ liệu mẫu.
                    </p>
                </div>
            @else
                <div class="mt-10 grid gap-7 md:grid-cols-2 lg:grid-cols-3">
                    @foreach ($homestays as $homestay)
                        <article class="group overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-xl">
                            <div class="relative overflow-hidden">
                                <img
                                    src="{{ $homestay->image ?: 'https://images.unsplash.com/photo-1564013799919-ab600027ffc6?auto=format&fit=crop&w=900&q=80' }}"
                                    alt="{{ $homestay->name }}"
                                    class="h-64 w-full object-cover transition duration-500 group-hover:scale-105"
                                >

                                <span class="absolute left-4 top-4 rounded-full bg-white/95 px-3 py-1.5 text-xs font-semibold text-blue-700 shadow">
                                    {{ $homestay->category?->name ?? 'Homestay' }}
                                </span>

                                <span class="absolute right-4 top-4 rounded-full bg-emerald-500 px-3 py-1.5 text-xs font-semibold text-white">
                                    Còn hoạt động
                                </span>
                            </div>

                            <div class="p-6">
                                <div class="flex items-center justify-between gap-3">
                                    <p class="text-sm font-medium text-blue-600">
                                        {{ $homestay->city }}
                                    </p>

                                    <span class="text-sm text-amber-500">
                                        ★ 4.8
                                    </span>
                                </div>

                                <h3 class="mt-2 line-clamp-1 text-xl font-bold text-slate-950">
                                    {{ $homestay->name }}
                                </h3>

                                <p class="mt-2 line-clamp-2 min-h-12 text-sm leading-6 text-slate-500">
                                    {{ \Illuminate\Support\Str::limit(
                                        $homestay->description ?? 'Không gian nghỉ dưỡng tiện nghi, phù hợp cho gia đình và nhóm bạn.',
                                        100
                                    ) }}
                                </p>

                                <div class="mt-5 flex items-center justify-between border-t border-slate-100 pt-5">
                                    <div>
                                        <p class="text-xs text-slate-400">
                                            Địa chỉ
                                        </p>

                                        <p class="mt-1 max-w-48 truncate text-sm font-semibold text-slate-700">
                                            {{ $homestay->address }}
                                        </p>
                                    </div>

                                    <a
                                        href="{{ route('homestays.show', $homestay->slug) }}"
                                        class="rounded-xl border border-blue-600 px-4 py-2.5 text-sm font-semibold text-blue-600 transition hover:bg-blue-600 hover:text-white"
                                    >
                                        Xem chi tiết
                                    </a>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>

                <div class="mt-12 flex justify-center">
                    {{ $homestays->links() }}
                </div>
            @endif
        </div>
    </section>

    {{-- About --}}
    <section
        id="about"
        class="bg-slate-100 py-20"
    >
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-2xl text-center">
                <p class="font-semibold uppercase tracking-widest text-blue-600">
                    HomeStayGo
                </p>

                <h2 class="mt-2 text-3xl font-bold tracking-tight text-slate-950 sm:text-4xl">
                    Vì sao nên lựa chọn chúng tôi?
                </h2>

                <p class="mt-4 leading-7 text-slate-600">
                    Quy trình đơn giản, nhiều lựa chọn và thông tin rõ ràng
                    cho mọi chuyến đi.
                </p>
            </div>

            <div class="mt-12 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                @php
                    $benefits = [
                        [
                            'icon' => '🏠',
                            'title' => 'Đa dạng Homestay',
                            'description' => 'Nhiều loại hình lưu trú tại các địa điểm du lịch.',
                        ],
                        [
                            'icon' => '💰',
                            'title' => 'Giá minh bạch',
                            'description' => 'Thông tin rõ ràng, dễ dàng so sánh lựa chọn.',
                        ],
                        [
                            'icon' => '⚡',
                            'title' => 'Đặt phòng nhanh',
                            'description' => 'Quy trình đặt phòng đơn giản và thuận tiện.',
                        ],
                        [
                            'icon' => '🛡️',
                            'title' => 'Thông tin an toàn',
                            'description' => 'Dữ liệu khách hàng được quản lý bảo mật.',
                        ],
                    ];
                @endphp

                @foreach ($benefits as $benefit)
                    <div class="rounded-3xl bg-white p-7 shadow-sm">
                        <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-blue-50 text-3xl">
                            {{ $benefit['icon'] }}
                        </div>

                        <h3 class="mt-5 text-lg font-bold text-slate-950">
                            {{ $benefit['title'] }}
                        </h3>

                        <p class="mt-2 leading-6 text-slate-500">
                            {{ $benefit['description'] }}
                        </p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Filter Modal --}}
    <div
        id="filterModal"
        class="fixed inset-0 z-[100] hidden"
        aria-labelledby="filterModalTitle"
        role="dialog"
        aria-modal="true"
    >
        <div id="filterOverlay" class="absolute inset-0 bg-slate-950/55 backdrop-blur-sm"></div>

        <div class="relative flex min-h-full items-center justify-center p-3 sm:p-6">
            <div
                id="filterPanel"
                class="flex h-[90vh] max-h-[760px] min-h-[560px] w-full max-w-5xl scale-95 flex-col overflow-hidden rounded-3xl border border-white/60 bg-white opacity-0 shadow-2xl transition duration-200"
            >
                <div class="flex items-center justify-between border-b border-slate-200 px-5 py-4 sm:px-7">
                    <div>
                        <h3 id="filterModalTitle" class="text-xl font-bold text-slate-950 sm:text-2xl">
                            Bộ lọc Homestay
                        </h3>
                        <p class="mt-1 text-sm text-slate-500">
                            Chọn các tiêu chí phù hợp với chuyến đi của bạn.
                        </p>
                    </div>

                    <button
                        type="button"
                        id="closeFilterModal"
                        class="flex h-10 w-10 items-center justify-center rounded-full border border-slate-200 text-slate-500 transition hover:border-red-200 hover:bg-red-50 hover:text-red-600"
                        aria-label="Đóng bộ lọc"
                    >
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" d="M6 6l12 12M18 6L6 18"/>
                        </svg>
                    </button>
                </div>

                <form action="{{ route('home') }}" method="GET" class="flex min-h-0 flex-1 flex-col overflow-hidden">
                    <div id="filterContent" class="min-h-0 flex-1 overflow-y-auto px-5 py-5 sm:px-7 sm:py-6">
                        <div class="grid gap-6 lg:grid-cols-2">
                            <div>
                                <label for="filter_keyword" class="mb-2 block text-sm font-semibold text-slate-700">
                                    Tên Homestay
                                </label>
                                <div class="relative">
                                    <svg class="pointer-events-none absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                        <circle cx="11" cy="11" r="7"></circle>
                                        <path stroke-linecap="round" d="m20 20-3.5-3.5"></path>
                                    </svg>
                                    <input
                                        id="filter_keyword"
                                        type="text"
                                        name="keyword"
                                        value="{{ request('keyword') }}"
                                        placeholder="Nhập tên Homestay..."
                                        class="w-full rounded-2xl border border-slate-300 py-3 pl-12 pr-4 outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                                    >
                                </div>
                            </div>

                            <div>
                                <label for="filter_location" class="mb-2 block text-sm font-semibold text-slate-700">
                                    Địa điểm
                                </label>
                                <div class="relative">
                                    <svg class="pointer-events-none absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 10c0 5-8 11-8 11S4 15 4 10a8 8 0 1 1 16 0Z"/>
                                        <circle cx="12" cy="10" r="2.5"/>
                                    </svg>
                                    <input
                                        id="filter_location"
                                        type="text"
                                        name="location"
                                        value="{{ request('location') }}"
                                        placeholder="Đà Lạt, Sa Pa, Hội An..."
                                        class="w-full rounded-2xl border border-slate-300 py-3 pl-12 pr-4 outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                                    >
                                </div>
                            </div>
                        </div>

                        <div class="mt-7 border-t border-slate-100 pt-6">
                            <p class="mb-3 text-sm font-semibold text-slate-800">
                                Khoảng giá
                            </p>

                            <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
                                @php
                                    $priceOptions = [
                                        'under_500' => 'Dưới 500.000đ',
                                        '500_1000' => '500.000đ - 1 triệu',
                                        '1000_2000' => '1 - 2 triệu',
                                        'over_2000' => 'Trên 2 triệu',
                                    ];
                                @endphp

                                @foreach ($priceOptions as $value => $label)
                                    <label class="cursor-pointer">
                                        <input
                                            type="radio"
                                            name="price_range"
                                            value="{{ $value }}"
                                            class="peer sr-only"
                                            @checked(request('price_range') === $value)
                                        >
                                        <span class="flex min-h-12 items-center justify-center rounded-2xl border border-slate-300 px-3 text-center text-sm font-semibold text-slate-600 transition peer-checked:border-blue-600 peer-checked:bg-blue-50 peer-checked:text-blue-700 hover:border-blue-300">
                                            {{ $label }}
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <div class="mt-7 grid gap-6 border-t border-slate-100 pt-6 md:grid-cols-2 lg:grid-cols-3">
                            <div>
                                <label for="room_type" class="mb-2 block text-sm font-semibold text-slate-700">
                                    Loại phòng
                                </label>
                                <select
                                    id="room_type"
                                    name="room_type"
                                    class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                                >
                                    <option value="">Tất cả loại phòng</option>
                                    @foreach (($roomTypes ?? []) as $roomType)
                                        <option value="{{ $roomType }}" @selected(request('room_type') === $roomType)>
                                            {{ $roomType }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="guests" class="mb-2 block text-sm font-semibold text-slate-700">
                                    Số người
                                </label>
                                <select
                                    id="guests"
                                    name="guests"
                                    class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                                >
                                    <option value="">Không giới hạn</option>
                                    @for ($guest = 1; $guest <= 10; $guest++)
                                        <option value="{{ $guest }}" @selected((string) request('guests') === (string) $guest)>
                                            {{ $guest }} người
                                        </option>
                                    @endfor
                                </select>
                            </div>

                            <div>
                                <label for="sort_price" class="mb-2 block text-sm font-semibold text-slate-700">
                                    Sắp xếp theo giá
                                </label>
                                <select
                                    id="sort_price"
                                    name="sort_price"
                                    class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                                >
                                    <option value="">Mới nhất</option>
                                    <option value="asc" @selected(request('sort_price') === 'asc')>
                                        Giá thấp đến cao
                                    </option>
                                    <option value="desc" @selected(request('sort_price') === 'desc')>
                                        Giá cao đến thấp
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="mt-7 border-t border-slate-100 pt-6">
                            <div class="flex items-center justify-between gap-3">
                                <p class="text-sm font-semibold text-slate-800">
                                    Tiện ích
                                </p>
                                <span class="text-xs text-slate-400">
                                    Có thể chọn nhiều
                                </span>
                            </div>

                            <div class="mt-3 grid grid-cols-2 gap-3 md:grid-cols-3 xl:grid-cols-6">
                                @forelse (($amenities ?? []) as $amenity)
<label
    class="flex h-12 cursor-pointer items-center gap-2 rounded-xl
           border border-slate-300 px-3 text-sm font-medium text-slate-600
           transition hover:border-blue-400
           has-[:checked]:border-blue-600
           has-[:checked]:bg-blue-50
           has-[:checked]:text-blue-700"
>
    <input
        type="checkbox"
        name="amenities[]"
        value="{{ $amenity->id }}"
        class="h-5 w-5 shrink-0 cursor-pointer accent-blue-600"
        @checked(
            in_array(
                (string) $amenity->id,
                array_map('strval', (array) request('amenities', [])),
                true
            )
        )
    >

    <span class="leading-5">
        {{ $amenity->name }}
    </span>
</label>
                                @empty
                                    <div class="rounded-2xl border border-dashed border-slate-300 bg-slate-50 px-4 py-4 text-sm text-slate-500 sm:col-span-2 lg:col-span-3">
                                        Chưa có dữ liệu tiện ích.
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <div class="shrink-0 flex flex-col-reverse gap-3 border-t border-slate-200 bg-white px-5 py-4 sm:flex-row sm:justify-end sm:px-7">
                        <a
                            href="{{ route('home') }}"
                            class="inline-flex min-h-12 items-center justify-center rounded-xl border border-slate-300 px-6 text-sm font-semibold text-slate-600 transition hover:bg-slate-100"
                        >
                            Xóa bộ lọc
                        </a>

                        <button
                            type="submit"
                            class="inline-flex min-h-12 items-center justify-center gap-2 rounded-xl bg-blue-600 px-7 text-sm font-semibold text-white shadow-lg shadow-blue-600/20 transition hover:bg-blue-700"
                        >
                            Xem kết quả
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m9 18 6-6-6-6"/>
                            </svg>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const modal = document.getElementById('filterModal');
            const panel = document.getElementById('filterPanel');
            const openButton = document.getElementById('openFilterModal');
            const closeButton = document.getElementById('closeFilterModal');
            const overlay = document.getElementById('filterOverlay');
            const filterContent = document.getElementById('filterContent');

            const openModal = () => {
                modal.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
                if (filterContent) filterContent.scrollTop = 0;

                requestAnimationFrame(() => {
                    panel.classList.remove('scale-95', 'opacity-0');
                    panel.classList.add('scale-100', 'opacity-100');
                });
            };

            const closeModal = () => {
                panel.classList.remove('scale-100', 'opacity-100');
                panel.classList.add('scale-95', 'opacity-0');

                setTimeout(() => {
                    modal.classList.add('hidden');
                    document.body.classList.remove('overflow-hidden');
                }, 180);
            };

            openButton?.addEventListener('click', openModal);
            closeButton?.addEventListener('click', closeModal);
            overlay?.addEventListener('click', closeModal);

            document.addEventListener('keydown', function (event) {
                if (event.key === 'Escape' && !modal.classList.contains('hidden')) {
                    closeModal();
                }
            });
        });
    </script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const picker = document.getElementById('location-picker');
        const trigger = document.getElementById('location-trigger');
        const menu = document.getElementById('location-menu');
        const input = document.getElementById('location-input');
        const text = document.getElementById('location-text');
        const arrow = document.getElementById('location-arrow');
        const clearButton = document.getElementById('clear-location');
        const options = document.querySelectorAll('.location-option');

        if (!picker || !trigger || !menu || !input || !text) {
            return;
        }

        let closeTimer = null;

        function openMenu() {
            clearTimeout(closeTimer);

            menu.classList.remove('hidden');
            arrow?.classList.add('rotate-180');
        }

        function closeMenu() {
            clearTimeout(closeTimer);

            menu.classList.add('hidden');
            arrow?.classList.remove('rotate-180');
        }

        function closeMenuWithDelay() {
            closeTimer = setTimeout(closeMenu, 150);
        }

        function removeSelectedStyle() {
            options.forEach(function (option) {
                option.classList.remove(
                    'bg-blue-50',
                    'text-blue-700'
                );
            });
        }

        picker.addEventListener('mouseenter', openMenu);
        picker.addEventListener('mouseleave', closeMenuWithDelay);

        trigger.addEventListener('click', function () {
            if (menu.classList.contains('hidden')) {
                openMenu();
            } else {
                closeMenu();
            }
        });

        options.forEach(function (option) {
            option.addEventListener('click', function () {
                const selectedLocation = this.dataset.location ?? '';

                input.value = selectedLocation;
                text.textContent =
                    selectedLocation || 'Bạn muốn đi đâu?';

                removeSelectedStyle();

                if (selectedLocation !== '') {
                    this.classList.add(
                        'bg-blue-50',
                        'text-blue-700'
                    );

                    clearButton?.classList.remove('hidden');
                } else {
                    clearButton?.classList.add('hidden');
                }

                closeMenu();
            });
        });

        // Nhấn dấu X để xóa địa điểm
        clearButton?.addEventListener('click', function (event) {
            event.preventDefault();
            event.stopPropagation();

            input.value = '';
            text.textContent = 'Bạn muốn đi đâu?';

            removeSelectedStyle();

            clearButton.classList.add('hidden');

            closeMenu();
        });

        document.addEventListener('click', function (event) {
            if (!picker.contains(event.target)) {
                closeMenu();
            }
        });

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') {
                closeMenu();
            }
        });
    });
</script>
@endsection