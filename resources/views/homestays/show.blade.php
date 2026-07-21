<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>{{ $homestay->name }} | HomeStay</title>

    @vite([
        'resources/css/app.css',
        'resources/js/app.js',
    ])
</head>

<body class="min-h-screen bg-slate-50">

    @include('partials.navbar')

    <main>

        {{-- Breadcrumb --}}
        <section class="border-b border-slate-200 bg-white">

            <div class="mx-auto max-w-7xl px-4 py-5 sm:px-6 lg:px-8">

                <nav
                    class="flex flex-wrap items-center gap-2 text-sm text-slate-500"
                    aria-label="Điều hướng"
                >

                    <a
                        href="{{ route('home') }}"
                        class="cursor-pointer font-medium transition hover:text-blue-600"
                    >
                        Trang chủ
                    </a>

                    <span>/</span>

                    @if ($homestay->category)
                        <span>
                            {{ $homestay->category->name }}
                        </span>

                        <span>/</span>
                    @endif

                    <span class="font-semibold text-slate-800">
                        {{ $homestay->name }}
                    </span>

                </nav>

            </div>

        </section>

        {{-- Nội dung chính --}}
        <section class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">

            <div class="grid gap-8 lg:grid-cols-[minmax(0,1fr)_380px]">

                {{-- Cột bên trái --}}
                <div class="space-y-8">

                    {{-- Ảnh Homestay --}}
                    <div class="overflow-hidden rounded-3xl bg-slate-200 shadow-sm">

                        @if ($homestay->image)

                            <img
                                src="https://images.unsplash.com/photo-1564013799919-ab600027ffc6?auto=format&fit=crop&w=900&q=80"
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

                    {{-- Tiêu đề --}}
                    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">

                        <div class="flex flex-col gap-5 sm:flex-row sm:items-start sm:justify-between">

                            <div class="min-w-0">

                                @if ($homestay->category)
                                    <span
                                        class="inline-flex rounded-full bg-blue-50 px-3 py-1 text-sm font-semibold text-blue-600"
                                    >
                                        {{ $homestay->category->name }}
                                    </span>
                                @endif

                                <h1 class="mt-4 text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl">
                                    {{ $homestay->name }}
                                </h1>

                                <div class="mt-4 flex flex-wrap items-center gap-x-5 gap-y-3 text-sm text-slate-500">

                                    <div class="flex items-center gap-2">

                                        <span aria-hidden="true" class="text-lg">
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

                                            <span aria-hidden="true" class="text-lg">
                                                ☎
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
                                class="inline-flex cursor-pointer items-center justify-center gap-2 rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm font-semibold text-slate-700 transition hover:border-red-300 hover:bg-red-50 hover:text-red-600"
                            >
                                <span aria-hidden="true">
                                    ♡
                                </span>

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

                                <p class="whitespace-pre-line">
                                    {{ $homestay->description }}
                                </p>

                            @else

                                <p>
                                    Homestay này chưa có nội dung giới thiệu.
                                </p>

                            @endif

                        </div>

                    </div>

                    {{-- Tiện ích --}}
                    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">

                        <div>

                            <h2 class="text-2xl font-bold text-slate-900">
                                Tiện ích nổi bật
                            </h2>

                            <p class="mt-2 text-slate-500">
                                Những dịch vụ và tiện nghi có tại Homestay.
                            </p>

                        </div>

                        @if ($homestay->amenities->isNotEmpty())

                            <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">

                                @foreach ($homestay->amenities as $amenity)

                                    <div
                                        class="flex items-center gap-4 rounded-2xl border border-slate-200 bg-slate-50 p-4"
                                    >

                                        <div
                                            class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-blue-100 text-2xl"
                                        >
                                            {{ $amenity->icon ?: '💎' }}
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

                            <div
                                class="mt-6 rounded-2xl border border-dashed border-slate-300 bg-slate-50 p-6 text-center"
                            >
                                <p class="text-sm text-slate-500">
                                    Homestay này chưa cập nhật tiện ích.
                                </p>
                            </div>

                        @endif

                    </div>

                    {{-- Chủ sở hữu --}}
                    @if ($homestay->owner)

                        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">

                            <h2 class="text-2xl font-bold text-slate-900">
                                Chủ sở hữu
                            </h2>

                            <div class="mt-6 flex items-center gap-4">

                                <div
                                    class="flex h-16 w-16 shrink-0 items-center justify-center rounded-full bg-blue-100 text-xl font-bold text-blue-600"
                                >
                                    {{ mb_strtoupper(
                                        mb_substr($homestay->owner->name, 0, 1)
                                    ) }}
                                </div>

                                <div>

                                    <p class="text-lg font-bold text-slate-900">
                                        {{ $homestay->owner->name }}
                                    </p>

                                    <p class="mt-1 text-sm text-slate-500">
                                        Chủ sở hữu Homestay
                                    </p>

                                    @if ($homestay->owner->email)
                                        <p class="mt-2 text-sm font-medium text-blue-600">
                                            {{ $homestay->owner->email }}
                                        </p>
                                    @endif

                                </div>

                            </div>

                        </div>

                    @endif

                </div>

                {{-- Cột đặt phòng --}}
                <aside class="lg:sticky lg:top-24 lg:self-start">

                    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-lg">

                        <div class="border-b border-slate-200 pb-5">

                            <p class="text-sm font-medium text-slate-500">
                                Giá tham khảo
                            </p>

                            <div class="mt-2 flex items-end gap-2">

                                <p class="text-3xl font-bold text-blue-600">
                                    {{ number_format(
                                        $homestay->price ?? 0,
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

                        <form class="mt-6 space-y-5">

                            {{-- Ngày nhận và trả phòng --}}
                            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-1 xl:grid-cols-2">

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
                                        class="w-full cursor-pointer rounded-xl border border-slate-300 px-3 py-3 text-sm text-slate-700 outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
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
                                        class="w-full cursor-pointer rounded-xl border border-slate-300 px-3 py-3 text-sm text-slate-700 outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                                    >

                                </div>

                            </div>

                            {{-- Số khách --}}
                            <div>

                                <label
                                    for="guests"
                                    class="mb-2 block text-sm font-semibold text-slate-700"
                                >
                                    Số lượng khách
                                </label>

                                <select
                                    id="guests"
                                    name="guests"
                                    class="w-full cursor-pointer rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                                >
                                    <option value="1">
                                        1 khách
                                    </option>

                                    <option value="2">
                                        2 khách
                                    </option>

                                    <option value="3">
                                        3 khách
                                    </option>

                                    <option value="4">
                                        4 khách
                                    </option>

                                    <option value="5">
                                        5 khách trở lên
                                    </option>
                                </select>

                            </div>

                            <button
                                type="button"
                                class="inline-flex w-full cursor-pointer items-center justify-center rounded-xl bg-blue-600 px-6 py-3.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-200"
                            >
                                Kiểm tra phòng trống
                            </button>

                        </form>

                        <p class="mt-4 text-center text-xs leading-5 text-slate-400">
                            Bạn chưa bị tính phí khi kiểm tra phòng trống.
                        </p>

                        <div class="mt-6 border-t border-slate-200 pt-5">

                            <div class="flex items-center justify-between text-sm">

                                <span class="text-slate-500">
                                    Trạng thái
                                </span>

                                <span
                                    class="inline-flex items-center gap-2 font-semibold text-emerald-600"
                                >
                                    <span class="h-2 w-2 rounded-full bg-emerald-500"></span>

                                    Đang hoạt động
                                </span>

                            </div>

                        </div>

                    </div>

                </aside>

            </div>

        </section>

    </main>

</body>

</html>