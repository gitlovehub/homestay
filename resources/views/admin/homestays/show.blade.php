<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Chi tiết Homestay | HomeStay</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-slate-100">

    @include('partials.navbar')

    <main class="mx-auto max-w-6xl px-4 py-10 sm:px-6 lg:px-8">

        {{-- Header --}}
        <div class="mb-8 flex flex-col gap-5 sm:flex-row sm:items-end sm:justify-between">

            <div>

                <a
                    href="{{ route('admin.homestays.index') }}"
                    class="text-sm font-semibold text-blue-600 transition hover:text-blue-700"
                >
                    <span aria-hidden="true">←</span>
                    Quay lại danh sách Homestay
                </a>

                <h1 class="mt-4 text-3xl font-bold text-slate-900">
                    Chi tiết Homestay
                </h1>

                <p class="mt-2 text-slate-500">
                    Xem toàn bộ thông tin của Homestay trong hệ thống.
                </p>

            </div>

            <a
                href="{{ route('admin.homestays.edit', $homestay) }}"
                class="inline-flex items-center justify-center rounded-xl bg-amber-500 px-5 py-3 text-sm font-semibold text-white transition hover:bg-amber-600 focus:outline-none focus:ring-4 focus:ring-amber-200"
            >
                Sửa Homestay
            </a>

        </div>

        <div class="space-y-6">

            {{-- Ảnh và thông tin nổi bật --}}
            <section class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">

                <div class="grid lg:grid-cols-[1.2fr_0.8fr]">

                    {{-- Thumbnail --}}
                    <div class="min-h-80 bg-slate-100">

                        @if ($homestay->thumbnail)
                            <img
                                src="{{ asset('storage/' . $homestay->thumbnail) }}"
                                alt="{{ $homestay->name }}"
                                class="h-full min-h-80 w-full object-cover"
                            >
                        @else
                            <div class="flex min-h-80 h-full items-center justify-center px-6 text-center">

                                <div>
                                    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-white text-3xl shadow-sm">
                                        🏡
                                    </div>

                                    <p class="mt-4 font-semibold text-slate-700">
                                        Chưa có ảnh đại diện
                                    </p>

                                    <p class="mt-1 text-sm text-slate-400">
                                        Homestay này chưa được cập nhật ảnh.
                                    </p>
                                </div>

                            </div>
                        @endif

                    </div>

                    {{-- Thông tin nổi bật --}}
                    <div class="flex flex-col justify-between p-6 sm:p-8">

                        <div>

                            <div class="flex flex-wrap items-center gap-3">

                                <span class="inline-flex rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700">
                                    {{ $homestay->category?->name ?? 'Chưa phân loại' }}
                                </span>

                                @if ($homestay->status)
                                    <span class="inline-flex items-center gap-2 rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700">
                                        <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                                        Hoạt động
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-2 rounded-full bg-red-50 px-3 py-1 text-xs font-semibold text-red-700">
                                        <span class="h-2 w-2 rounded-full bg-red-500"></span>
                                        Tạm khóa
                                    </span>
                                @endif

                            </div>

                            <h2 class="mt-5 text-3xl font-bold leading-tight text-slate-900">
                                {{ $homestay->name }}
                            </h2>

                            <p class="mt-2 text-sm text-slate-400">
                                {{ $homestay->slug }}
                            </p>

                            <div class="mt-7">

                                <p class="text-sm font-medium text-slate-500">
                                    Giá cơ bản
                                </p>

                                <p class="mt-1 text-3xl font-bold text-blue-600">
                                    {{ number_format($homestay->base_price, 0, ',', '.') }} VNĐ
                                </p>

                            </div>

                        </div>

                        <div class="mt-8 grid grid-cols-2 gap-3">

                            <div class="rounded-2xl bg-slate-50 p-4">

                                <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                                    Nhận phòng
                                </p>

                                <p class="mt-2 font-bold text-slate-900">
                                    {{ \Carbon\Carbon::createFromFormat('H:i:s', $homestay->check_in_time)->format('h:i A') ?: 'Chưa cập nhật' }}
                                </p>

                            </div>

                            <div class="rounded-2xl bg-slate-50 p-4">

                                <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                                    Trả phòng
                                </p>

                                <p class="mt-2 font-bold text-slate-900">
                                    {{ \Carbon\Carbon::createFromFormat('H:i:s', $homestay->check_out_time)->format('h:i A') ?: 'Chưa cập nhật' }}
                                </p>

                            </div>

                        </div>

                    </div>

                </div>

            </section>

            {{-- Thông tin cơ bản --}}
            <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">

                <div class="mb-6">

                    <h2 class="text-xl font-bold text-slate-900">
                        Thông tin cơ bản
                    </h2>

                    <p class="mt-1 text-sm text-slate-500">
                        Thông tin quản lý và liên hệ của Homestay.
                    </p>

                </div>

                <div class="grid gap-5 md:grid-cols-2 lg:grid-cols-3">

                    {{-- Chủ sở hữu --}}
                    <div class="rounded-2xl border border-slate-200 p-5">

                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                            Chủ sở hữu
                        </p>

                        <p class="mt-2 font-semibold text-slate-900">
                            {{ $homestay->owner?->name ?? 'Chưa xác định' }}
                        </p>

                        <p class="mt-1 break-all text-sm text-slate-500">
                            {{ $homestay->owner?->email ?? 'Chưa có email' }}
                        </p>

                    </div>

                    {{-- Số điện thoại --}}
                    <div class="rounded-2xl border border-slate-200 p-5">

                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                            Số điện thoại
                        </p>

                        <p class="mt-2 font-semibold text-slate-900">
                            {{ $homestay->phone ?: 'Chưa cập nhật' }}
                        </p>

                    </div>

                    {{-- Thành phố --}}
                    <div class="rounded-2xl border border-slate-200 p-5">

                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                            Thành phố
                        </p>

                        <p class="mt-2 font-semibold text-slate-900">
                            {{ $homestay->city ?: 'Chưa cập nhật' }}
                        </p>

                    </div>

                    {{-- Địa chỉ --}}
                    <div class="rounded-2xl border border-slate-200 p-5 md:col-span-2">

                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                            Địa chỉ
                        </p>

                        <p class="mt-2 leading-6 text-slate-900">
                            {{ $homestay->address ?: 'Chưa cập nhật' }}
                        </p>

                    </div>

                    {{-- Ngày tạo --}}
                    <div class="rounded-2xl border border-slate-200 p-5">

                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                            Ngày tạo
                        </p>

                        <p class="mt-2 font-semibold text-slate-900">
                            {{ $homestay->created_at?->format('d/m/Y H:i') }}
                        </p>

                    </div>

                </div>

            </section>

            {{-- Vị trí --}}
            <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">

                <div class="mb-6">

                    <h2 class="text-xl font-bold text-slate-900">
                        Vị trí
                    </h2>

                    <p class="mt-1 text-sm text-slate-500">
                        Tọa độ được sử dụng để xác định vị trí Homestay.
                    </p>

                </div>

                <div class="grid gap-5 sm:grid-cols-2">

                    <div class="rounded-2xl bg-slate-50 p-5">

                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                            Vĩ độ
                        </p>

                        <p class="mt-2 font-mono font-semibold text-slate-900">
                            {{ $homestay->latitude ?? 'Chưa cập nhật' }}
                        </p>

                    </div>

                    <div class="rounded-2xl bg-slate-50 p-5">

                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                            Kinh độ
                        </p>

                        <p class="mt-2 font-mono font-semibold text-slate-900">
                            {{ $homestay->longitude ?? 'Chưa cập nhật' }}
                        </p>

                    </div>

                </div>

            </section>

            {{-- Tiện ích --}}
            <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">

                <div class="mb-6">

                    <h2 class="text-xl font-bold text-slate-900">
                        Tiện ích
                    </h2>

                    <p class="mt-1 text-sm text-slate-500">
                        Các tiện ích hiện có tại Homestay.
                    </p>

                </div>

                @if ($homestay->amenities->isNotEmpty())

                    <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3">

                        @foreach ($homestay->amenities as $amenity)

                            <div class="flex items-center gap-3 rounded-2xl border border-slate-200 bg-slate-50 p-4">

                                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-white text-xl shadow-sm">
                                    {{ $amenity->icon ?: '✓' }}
                                </div>

                                <div class="min-w-0">

                                    <p class="font-semibold text-slate-900">
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

                    <div class="rounded-2xl border border-dashed border-slate-300 bg-slate-50 px-6 py-10 text-center">

                        <p class="font-semibold text-slate-700">
                            Chưa có tiện ích
                        </p>

                        <p class="mt-1 text-sm text-slate-500">
                            Homestay này chưa được gắn tiện ích nào.
                        </p>

                    </div>

                @endif

            </section>

            {{-- Mô tả --}}
            <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">

                <div class="mb-5">

                    <h2 class="text-xl font-bold text-slate-900">
                        Mô tả Homestay
                    </h2>

                </div>

                @if ($homestay->description)

                    <div class="whitespace-pre-line leading-7 text-slate-600">
                        {{ $homestay->description }}
                    </div>

                @else

                    <p class="text-sm italic text-slate-400">
                        Chưa có nội dung mô tả.
                    </p>

                @endif

            </section>

            {{-- Chính sách --}}
            <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">

                <div class="mb-5">

                    <h2 class="text-xl font-bold text-slate-900">
                        Chính sách lưu trú
                    </h2>

                </div>

                @if ($homestay->policy)

                    <div class="whitespace-pre-line leading-7 text-slate-600">
                        {{ $homestay->policy }}
                    </div>

                @else

                    <p class="text-sm italic text-slate-400">
                        Chưa có chính sách lưu trú.
                    </p>

                @endif

            </section>

            {{-- Nút cuối trang --}}
            <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">

                <a
                    href="{{ route('admin.homestays.index') }}"
                    class="inline-flex items-center justify-center rounded-xl border border-slate-300 bg-white px-6 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-100"
                >
                    Quay lại danh sách
                </a>

                <a
                    href="{{ route('admin.homestays.edit', $homestay) }}"
                    class="inline-flex items-center justify-center rounded-xl bg-blue-600 px-6 py-3 text-sm font-semibold text-white transition hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-200"
                >
                    Chỉnh sửa Homestay
                </a>

            </div>

        </div>

    </main>

</body>

</html>