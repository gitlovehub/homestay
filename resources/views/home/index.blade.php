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
                    <a href="#featured"
                        class="rounded-xl bg-blue-600 px-6 py-3.5 font-semibold text-white shadow-lg shadow-blue-600/20 transition hover:-translate-y-0.5 hover:bg-blue-700">
                        Khám phá ngay
                    </a>

                    <a href="#about"
                        class="rounded-xl border border-slate-300 bg-white px-6 py-3.5 font-semibold text-slate-700 transition hover:border-blue-600 hover:text-blue-600">
                        Tìm hiểu thêm
                    </a>
                </div>

                <div class="mt-10 grid max-w-lg grid-cols-3 gap-5">
                    <div>
                        <p class="text-2xl font-bold text-slate-950">
                            100+
                        </p>
                        <p class="text-sm text-slate-500">
                            Homestay
                        </p>
                    </div>

                    <div>
                        <p class="text-2xl font-bold text-slate-950">
                            20+
                        </p>
                        <p class="text-sm text-slate-500">
                            Địa điểm
                        </p>
                    </div>

                    <div>
                        <p class="text-2xl font-bold text-slate-950">
                            4.9/5
                        </p>
                        <p class="text-sm text-slate-500">
                            Đánh giá
                        </p>
                    </div>
                </div>
            </div>

            <div class="relative">
                <div class="overflow-hidden rounded-[2rem] bg-white p-3 shadow-2xl shadow-slate-900/15">
                    <img src="https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=1200&q=85"
                        alt="Homestay nổi bật" class="h-[480px] w-full rounded-[1.5rem] object-cover">
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

    {{-- Bộ lọc --}}
    <section class="relative z-10 -mt-5 px-4 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-7xl rounded-3xl border border-slate-200 bg-white p-5 shadow-xl shadow-slate-900/10">

            <form
                action="{{ route('home') }}"
                method="GET"
                class="grid gap-4 md:grid-cols-2 lg:grid-cols-4"
            >
                {{-- Giữ lại các giá trị lọc nâng cao khi tìm kiếm nhanh --}}
                <input type="hidden" name="price_range" value="{{ request('price_range') }}">
                <input type="hidden" name="room_type" value="{{ request('room_type') }}">
                <input type="hidden" name="sort_price" value="{{ request('sort_price') }}">

                {{-- Địa điểm --}}
                <div>
                    <label
                        for="location"
                        class="mb-2 block text-sm font-semibold text-slate-700"
                    >
                        Địa điểm
                    </label>

                    <input
                        id="location"
                        type="text"
                        name="location"
                        value="{{ request('location') }}"
                        placeholder="Đà Lạt, Sa Pa, Hội An..."
                        class="w-full rounded-xl border border-slate-300 px-4 py-3 outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                    >
                </div>

                {{-- Ngày nhận phòng --}}
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
                        value="{{ request('check_in') }}"
                        class="w-full rounded-xl border border-slate-300 px-4 py-3 outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                    >
                </div>

                {{-- Ngày trả phòng --}}
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
                        value="{{ request('check_out') }}"
                        class="w-full rounded-xl border border-slate-300 px-4 py-3 outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                    >
                </div>

                {{-- Nút --}}
                <div class="flex items-end gap-3">
                    <button
                        type="submit"
                        class="flex-1 rounded-xl bg-blue-600 px-5 py-3 font-semibold text-white transition hover:bg-blue-700"
                    >
                        Tìm kiếm
                    </button>

                    <button
                        type="button"
                        id="open-filter"
                        class="inline-flex items-center justify-center rounded-xl border border-blue-600 px-4 py-3 font-semibold text-blue-600 transition hover:bg-blue-50"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M3 4a1 1 0 011-1h16a1 1 0 01.8 1.6L14 13.667V19a1 1 0 01-.553.894l-4 2A1 1 0 018 21v-7.333L3.2 4.6A1 1 0 013 4z"
                            />
                        </svg>

                        <span class="ml-2 hidden sm:inline">
                            Bộ lọc
                        </span>
                    </button>
                </div>
            </form>

            <div class="mt-3 flex flex-wrap items-center justify-between gap-3">
                <p class="text-xs text-slate-400">
                    Tìm nhanh theo địa điểm và thời gian lưu trú.
                </p>

                @if (
                    request()->filled('price_range') ||
                    request()->filled('room_type') ||
                    request()->filled('sort_price')
                )
                    <button
                        type="button"
                        id="open-filter-summary"
                        class="text-sm font-semibold text-blue-600 hover:text-blue-700"
                    >
                        Đang áp dụng bộ lọc nâng cao
                    </button>
                @endif
            </div>

        </div>
    </section>

    {{-- Modal bộ lọc --}}
    <div id="filter-modal" class="fixed inset-0 z-[100] hidden">
        {{-- Nền tối --}}
        <div id="filter-overlay" class="absolute inset-0 bg-slate-950/50 backdrop-blur-sm">
        </div>

        <div class="relative flex min-h-full items-center justify-center p-4">
            <div class="relative flex max-h-[88vh] w-full max-w-4xl flex-col overflow-hidden rounded-3xl bg-white shadow-2xl">

                {{-- Header --}}
                <div class="flex items-center justify-between border-b border-slate-200 px-6 py-5">
                    <div>
                        <h2 class="text-xl font-bold text-slate-900">
                            Bộ lọc nâng cao
                        </h2>

                        <p class="mt-1 text-sm text-slate-500">
                            Chọn điều kiện phù hợp để thu hẹp kết quả.
                        </p>
                    </div>

                    <button
                        id="close-filter"
                        type="button"
                        class="rounded-xl border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-600 transition hover:bg-slate-100"
                    >
                        × Đóng
                    </button>
                </div>

                <form
                    action="{{ route('home') }}"
                    method="GET"
                    class="flex min-h-0 flex-1 flex-col"
                >
                    {{-- Giữ tìm kiếm nhanh --}}
                    <input
                        type="hidden"
                        name="location"
                        value="{{ request('location') }}"
                    >

                    <input
                        type="hidden"
                        name="check_in"
                        value="{{ request('check_in') }}"
                    >

                    <input
                        type="hidden"
                        name="check_out"
                        value="{{ request('check_out') }}"
                    >

                    <div class="flex-1 space-y-8 overflow-y-auto px-6 py-6">

                        {{-- Khoảng giá --}}
                        <div>
                            <h3 class="mb-3 font-bold text-slate-900">
                                Khoảng giá
                            </h3>

                            @php
                                $priceRanges = [
                                    '' => 'Tất cả mức giá',
                                    'under_500' => 'Dưới 500.000đ',
                                    '500_1000' => '500.000đ - 1.000.000đ',
                                    '1000_2000' => '1.000.000đ - 2.000.000đ',
                                    'over_2000' => 'Trên 2.000.000đ',
                                ];
                            @endphp

                            <div class="flex flex-wrap gap-3">
                                @foreach ($priceRanges as $value => $label)
                                    <label class="cursor-pointer">
                                        <input
                                            type="radio"
                                            name="price_range"
                                            value="{{ $value }}"
                                            class="peer sr-only"
                                            @checked(request('price_range', '') === $value)
                                        >

                                        <span class="inline-flex rounded-xl border border-slate-300 px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:border-blue-500 hover:text-blue-600 peer-checked:border-blue-600 peer-checked:bg-blue-50 peer-checked:text-blue-700">
                                            {{ $label }}
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        {{-- Loại phòng --}}
                        <div>
                            <h3 class="mb-3 font-bold text-slate-900">
                                Loại phòng
                            </h3>

                            <div class="flex flex-wrap gap-3">
                                <label class="cursor-pointer">
                                    <input
                                        type="radio"
                                        name="room_type"
                                        value=""
                                        class="peer sr-only"
                                        @checked(request('room_type', '') === '')
                                    >

                                    <span class="inline-flex rounded-xl border border-slate-300 px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:border-blue-500 hover:text-blue-600 peer-checked:border-blue-600 peer-checked:bg-blue-50 peer-checked:text-blue-700">
                                        Tất cả loại phòng
                                    </span>
                                </label>

                                @foreach ($roomTypes as $roomType)
                                    <label class="cursor-pointer">
                                        <input
                                            type="radio"
                                            name="room_type"
                                            value="{{ $roomType }}"
                                            class="peer sr-only"
                                            @checked(request('room_type') === $roomType)
                                        >

                                        <span class="inline-flex rounded-xl border border-slate-300 px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:border-blue-500 hover:text-blue-600 peer-checked:border-blue-600 peer-checked:bg-blue-50 peer-checked:text-blue-700">
                                            {{ $roomType }}
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        {{-- Sắp xếp --}}
                        <div>
                            <h3 class="mb-3 font-bold text-slate-900">
                                Sắp xếp theo giá
                            </h3>

                            <div class="flex flex-wrap gap-3">
                                <label class="cursor-pointer">
                                    <input
                                        type="radio"
                                        name="sort_price"
                                        value=""
                                        class="peer sr-only"
                                        @checked(request('sort_price', '') === '')
                                    >

                                    <span class="inline-flex rounded-xl border border-slate-300 px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:border-blue-500 hover:text-blue-600 peer-checked:border-blue-600 peer-checked:bg-blue-50 peer-checked:text-blue-700">
                                        Mới nhất
                                    </span>
                                </label>

                                <label class="cursor-pointer">
                                    <input
                                        type="radio"
                                        name="sort_price"
                                        value="asc"
                                        class="peer sr-only"
                                        @checked(request('sort_price') === 'asc')
                                    >

                                    <span class="inline-flex rounded-xl border border-slate-300 px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:border-blue-500 hover:text-blue-600 peer-checked:border-blue-600 peer-checked:bg-blue-50 peer-checked:text-blue-700">
                                        Giá thấp đến cao
                                    </span>
                                </label>

                                <label class="cursor-pointer">
                                    <input
                                        type="radio"
                                        name="sort_price"
                                        value="desc"
                                        class="peer sr-only"
                                        @checked(request('sort_price') === 'desc')
                                    >

                                    <span class="inline-flex rounded-xl border border-slate-300 px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:border-blue-500 hover:text-blue-600 peer-checked:border-blue-600 peer-checked:bg-blue-50 peer-checked:text-blue-700">
                                        Giá cao đến thấp
                                    </span>
                                </label>
                            </div>
                        </div>
                    </div>

                    {{-- Footer --}}
                    <div class="flex flex-col-reverse gap-3 border-t border-slate-200 bg-white px-6 py-5 sm:flex-row sm:justify-center">
                        <a
                            href="{{ route('home') }}"
                            class="inline-flex min-w-44 items-center justify-center rounded-xl border border-red-500 px-6 py-3 font-semibold text-red-600 transition hover:bg-red-50"
                        >
                            Bỏ chọn
                        </a>

                        <button
                            type="submit"
                            class="inline-flex min-w-52 items-center justify-center rounded-xl bg-blue-600 px-6 py-3 font-semibold text-white transition hover:bg-blue-700"
                        >
                            Xem kết quả
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>      

    {{-- Featured --}}
    <section id="featured" class="py-20">
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

                <a href="#" class="font-semibold text-blue-600 hover:text-blue-700">
                    Xem tất cả →
                </a>
            </div>

            @if ($homestays->isEmpty())
                <div class="mt-10 rounded-3xl border border-dashed border-slate-300 bg-white p-12 text-center">
                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-blue-50 text-2xl">
                        🔍
                    </div>

                    <h3 class="mt-4 text-xl font-bold text-slate-900">
                        Không tìm thấy phòng phù hợp
                    </h3>

                    <p class="mx-auto mt-2 max-w-lg text-slate-500">
                        Không có Homestay nào đáp ứng đồng thời các điều kiện bạn đã chọn.
                        Hãy thử thay đổi khoảng giá, loại phòng hoặc địa điểm.
                    </p>

                    <a
                        href="{{ route('home') }}"
                        class="mt-6 inline-flex rounded-xl bg-blue-600 px-6 py-3 font-semibold text-white transition hover:bg-blue-700"
                    >
                        Xóa bộ lọc
                    </a>
                </div>
            @else
                <div class="mt-10 grid gap-7 md:grid-cols-2 lg:grid-cols-3">
                    @foreach ($homestays as $homestay)
                            <article
                                class="group overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-xl">
                                <div class="relative overflow-hidden">
                                    <img src="{{ $homestay->image ?: 'https://images.unsplash.com/photo-1564013799919-ab600027ffc6?auto=format&fit=crop&w=900&q=80' }}"
                                        alt="{{ $homestay->name }}"
                                        class="h-64 w-full object-cover transition duration-500 group-hover:scale-105">

                                    <span
                                        class="absolute left-4 top-4 rounded-full bg-white/95 px-3 py-1.5 text-xs font-semibold text-blue-700 shadow">
                                        {{ $homestay->category?->name ?? 'Homestay' }}
                                    </span>

                                    <span
                                        class="absolute right-4 top-4 rounded-full bg-emerald-500 px-3 py-1.5 text-xs font-semibold text-white">
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

                                    
                                    @if ($homestay->minimum_room_price !== null)
                                        <div class="mt-3 flex items-end gap-1">
                                            <span class="text-2xl font-bold text-blue-600">
                                                {{ number_format(
                                                    $homestay->minimum_room_price,
                                                    0,
                                                    ',',
                                                    '.'
                                                ) }}đ
                                            </span>

                                            <span class="pb-1 text-sm font-medium text-slate-400">
                                                / đêm
                                            </span>
                                        </div>
                                    @else
                                        <p class="mt-3 font-semibold text-slate-400">
                                            Chưa có phòng phù hợp
                                        </p>
                                    @endif

                                    <p class="mt-3 line-clamp-2 min-h-12 text-sm leading-6 text-slate-500">
                                        {{ \Illuminate\Support\Str::limit(
                                            $homestay->description
                                                ?? 'Không gian nghỉ dưỡng tiện nghi, phù hợp cho gia đình và nhóm bạn.',
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

                                        <a href="{{ route('homestays.show', $homestay->slug) }}"
                                            class="rounded-xl border border-blue-600 px-4 py-2.5 text-sm font-semibold text-blue-600 transition hover:bg-blue-600 hover:text-white">
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
    <section id="about" class="bg-slate-100 py-20">
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
@endsection
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const modal = document.getElementById('filter-modal');
        const openButton = document.getElementById('open-filter');
        const closeButton = document.getElementById('close-filter');
        const overlay = document.getElementById('filter-overlay');

        function openFilter() {
            modal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }

        function closeFilter() {
            modal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }

        openButton.addEventListener('click', openFilter);
        closeButton.addEventListener('click', closeFilter);
        overlay.addEventListener('click', closeFilter);

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') {
                closeFilter();
            }
        });
    });
</script>