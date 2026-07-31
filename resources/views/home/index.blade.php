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
                <div class="lg:col-span-2">
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
        class="py-25"
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
                        Những Homestay được đặt nhiều và nhận đánh giá tốt từ khách hàng.
                    </p>
                </div>

                <a
                    href="{{ route('homestays.index') }}"
                    class="font-semibold text-blue-600 hover:text-blue-700"
                >
                    Xem tất cả →
                </a>
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

                        @php
                            $averageRating = (float) ($homestay->average_rating ?? 0);
                            $reviewCount = (int) ($homestay->approved_reviews_count ?? 0);
                            $bookingCount = (int) ($homestay->bookings_count ?? 0);
                        @endphp

                        <article
                            class="group flex h-full flex-col overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm transition duration-300 hover:-translate-y-1 hover:border-blue-200 hover:shadow-xl"
                        >
                            {{-- Hình ảnh --}}
                            <div class="relative overflow-hidden bg-slate-100">

                                @if ($homestay->thumbnail)

                                    <img
                                        src="{{ Storage::url($homestay->thumbnail) }}"
                                        alt="{{ $homestay->name }}"
                                        class="h-64 w-full object-cover transition duration-500 group-hover:scale-105"
                                    >

                                @else

                                    <div class="flex h-64 items-center justify-center">

                                        <div class="text-center">

                                            <div class="text-5xl">
                                                🏡
                                            </div>

                                            <p class="mt-3 text-sm font-medium text-slate-400">
                                                Chưa có hình ảnh
                                            </p>

                                        </div>

                                    </div>

                                @endif

                                {{-- Danh mục --}}
                                <span
                                    class="absolute left-4 top-4 rounded-full px-4 py-1.5 text-xs font-semibold text-slate-900 border border-slate-100 shadow-2xl backdrop-blur"
                                >
                                    {{ $homestay->category?->name ?? 'Homestay' }}
                                </span>

                            </div>

                            {{-- Nội dung --}}
                            <div class="flex flex-1 flex-col p-6">

                                {{-- Địa điểm và đánh giá --}}
                                <div class="flex items-center justify-between gap-3">

                                    <p class="min-w-0 truncate text-sm font-semibold text-blue-600">
                                        {{ $homestay->city ?: 'Chưa cập nhật địa điểm' }}
                                    </p>

                                    @if ($reviewCount > 0)

                                        <div class="flex shrink-0 items-center gap-1.5">

                                            <x-icon-star class="h-4 w-4 text-amber-400" />

                                            <span class="text-sm font-bold text-slate-800">
                                                {{ number_format($averageRating, 1) }}
                                            </span>

                                        </div>

                                    @else

                                        <span class="shrink-0 text-xs font-medium text-slate-400">
                                            Chưa có đánh giá
                                        </span>

                                    @endif

                                </div>

                                {{-- Tên Homestay --}}
                                <h3 class="mt-2 line-clamp-1 text-xl font-bold text-slate-950">
                                    {{ $homestay->name }}
                                </h3>

                                {{-- Thống kê --}}
                                <div class="mt-4 flex flex-wrap gap-2">

                                    <span
                                        class="inline-flex items-center gap-1.5 rounded-full bg-blue-50 border border-blue-200 px-3 py-1 text-xs font-semibold text-blue-700"
                                    >
                                        {{ $bookingCount }} lượt đặt
                                    </span>

                                    <span
                                        class="inline-flex items-center gap-1.5 rounded-full bg-amber-50 border border-amber-200 px-3 py-1 text-xs font-semibold text-amber-700"
                                    >
                                        <x-icon-star class="h-3.5 w-3.5 text-amber-400" />

                                        {{ $reviewCount }} đánh giá
                                    </span>

                                </div>

                                {{-- Mô tả --}}
                                <p class="mt-4 line-clamp-2 min-h-12 text-sm leading-6 text-slate-500">
                                    {{ \Illuminate\Support\Str::limit(
                                        $homestay->description
                                            ?: 'Không gian nghỉ dưỡng tiện nghi, phù hợp cho gia đình và nhóm bạn.',
                                        100
                                    ) }}
                                </p>

                                {{-- Địa chỉ và nút chi tiết --}}
                                <div
                                    class="mt-auto flex items-end justify-between gap-4 border-t border-slate-100 pt-5"
                                >
                                    <div class="min-w-0">

                                        <p class="text-xs text-slate-400">
                                            Địa chỉ
                                        </p>

                                        <p class="mt-1 truncate text-sm font-semibold text-slate-700">
                                            {{ $homestay->address ?: 'Chưa cập nhật địa chỉ' }}
                                        </p>

                                    </div>

                                    <a
                                        href="{{ route('homestays.show', $homestay->slug) }}"
                                        class="inline-flex shrink-0 items-center justify-center rounded-xl border border-blue-600 px-4 py-2.5 text-sm font-semibold text-blue-600 transition hover:bg-blue-600 hover:text-white focus:outline-none focus:ring-4 focus:ring-blue-100"
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
        class="bg-slate-100 py-25"
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
@endsection