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
                        class="font-medium transition hover:text-blue-600"
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
                                    <span class="inline-flex rounded-full bg-blue-50 px-3 py-1 text-sm font-semibold text-blue-600">
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
                                <span>
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

                            <span class="inline-flex w-fit rounded-full bg-emerald-50 px-3 py-1.5 text-sm font-semibold text-emerald-600">
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

                                                            <span class="rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-600">
                                                                {{ $room->room_type }}
                                                            </span>

                                                            <span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-600">
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

                            <div class="mt-6 rounded-2xl border border-dashed border-slate-300 bg-slate-50 p-6 text-center">

                                <p class="text-sm text-slate-500">
                                    Homestay này chưa cập nhật tiện ích.
                                </p>

                            </div>

                        @endif

                    </div>

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

                            <p class="mt-3 whitespace-pre-line leading-7 text-slate-500">
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

</body>

</html>