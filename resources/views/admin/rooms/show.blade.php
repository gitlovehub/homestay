<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Chi tiết phòng | HomeStay</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-slate-100">

    @include('partials.navbar')

    <main class="mx-auto max-w-6xl px-4 py-10 sm:px-6 lg:px-8">

        {{-- Header --}}
        <div class="mb-8 flex flex-col gap-5 sm:flex-row sm:items-end sm:justify-between">

            <div>
                <a
                    href="{{ route('admin.rooms.index') }}"
                    class="text-sm font-semibold text-blue-600 transition hover:text-blue-700"
                >
                    ← Quay lại danh sách phòng
                </a>

                <h1 class="mt-4 text-3xl font-bold text-slate-900">
                    Chi tiết phòng
                </h1>

                <p class="mt-2 text-slate-500">
                    Xem toàn bộ thông tin của phòng trong hệ thống.
                </p>
            </div>

            <a
                href="{{ route('admin.rooms.edit', $room) }}"
                class="inline-flex items-center justify-center rounded-xl bg-amber-500 px-5 py-3 text-sm font-semibold text-white transition hover:bg-amber-600"
            >
                Sửa phòng
            </a>

        </div>

        <div class="space-y-6">

            {{-- Thông tin nổi bật --}}
            <section class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">

                <div class="grid lg:grid-cols-[1.2fr_0.8fr]">

                    <div class="min-h-80 bg-slate-100">

                        @if ($room->image)
                            <img
                                src="{{ asset('storage/' . $room->image) }}"
                                alt="{{ $room->name }}"
                                class="h-full min-h-80 w-full object-cover"
                            >
                        @else
                            <div class="flex h-full min-h-80 items-center justify-center">

                                <div class="text-center">
                                    <div class="text-6xl">
                                        🚪
                                    </div>

                                    <p class="mt-4 font-semibold text-slate-600">
                                        Chưa có ảnh phòng
                                    </p>
                                </div>

                            </div>
                        @endif

                    </div>

                    <div class="flex flex-col justify-between p-6 sm:p-8">

                        <div>

                            <div class="flex flex-wrap items-center gap-3">

                                <span class="rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700">
                                    {{ $room->room_type }}
                                </span>

                                @switch($room->status)

                                    @case('available')
                                        <span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700">
                                            Còn trống
                                        </span>
                                        @break

                                    @case('maintenance')
                                        <span class="rounded-full bg-amber-50 px-3 py-1 text-xs font-semibold text-amber-700">
                                            Bảo trì
                                        </span>
                                        @break

                                    @default
                                        <span class="rounded-full bg-red-50 px-3 py-1 text-xs font-semibold text-red-700">
                                            Ngừng hoạt động
                                        </span>

                                @endswitch

                            </div>

                            <h2 class="mt-5 text-3xl font-bold text-slate-900">
                                {{ $room->name }}
                            </h2>

                            <p class="mt-2 text-sm font-medium text-slate-400">
                                Mã phòng: {{ $room->room_code }}
                            </p>

                            <div class="mt-7">
                                <p class="text-sm font-medium text-slate-500">
                                    Giá mỗi đêm
                                </p>

                                <p class="mt-1 text-3xl font-bold text-blue-600">
                                    {{ number_format($room->price_per_night, 0, ',', '.') }} VNĐ
                                </p>
                            </div>

                        </div>

                        <div class="mt-8 rounded-2xl bg-slate-50 p-5">

                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                                Thuộc Homestay
                            </p>

                            <p class="mt-2 font-bold text-slate-900">
                                {{ $room->homestay?->name ?? 'Không xác định' }}
                            </p>

                            <p class="mt-1 text-sm text-slate-500">
                                {{ $room->homestay?->address }}
                            </p>

                        </div>

                    </div>

                </div>

            </section>

            {{-- Thông số --}}
            <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">

                <div class="mb-6">
                    <h2 class="text-xl font-bold text-slate-900">
                        Thông số phòng
                    </h2>

                    <p class="mt-1 text-sm text-slate-500">
                        Sức chứa, số giường và diện tích của phòng.
                    </p>
                </div>

                <div class="grid gap-5 sm:grid-cols-3">

                    <div class="rounded-2xl bg-slate-50 p-5">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                            Sức chứa
                        </p>

                        <p class="mt-2 text-xl font-bold text-slate-900">
                            {{ $room->capacity }} người
                        </p>
                    </div>

                    <div class="rounded-2xl bg-slate-50 p-5">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                            Số giường
                        </p>

                        <p class="mt-2 text-xl font-bold text-slate-900">
                            {{ $room->number_of_beds }} giường
                        </p>
                    </div>

                    <div class="rounded-2xl bg-slate-50 p-5">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                            Diện tích
                        </p>

                        <p class="mt-2 text-xl font-bold text-slate-900">
                            {{ $room->area ? $room->area . ' m²' : 'Chưa cập nhật' }}
                        </p>
                    </div>

                </div>

            </section>

            {{-- Mô tả --}}
            <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">

                <h2 class="text-xl font-bold text-slate-900">
                    Mô tả phòng
                </h2>

                @if ($room->description)
                    <div class="mt-5 whitespace-pre-line leading-7 text-slate-600">
                        {{ $room->description }}
                    </div>
                @else
                    <p class="mt-5 text-sm italic text-slate-400">
                        Chưa có nội dung mô tả.
                    </p>
                @endif

            </section>

            {{-- Thông tin quản lý --}}
            <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">

                <h2 class="text-xl font-bold text-slate-900">
                    Thông tin quản lý
                </h2>

                <div class="mt-6 grid gap-5 sm:grid-cols-2">

                    <div class="rounded-2xl border border-slate-200 p-5">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                            Ngày tạo
                        </p>

                        <p class="mt-2 font-semibold text-slate-900">
                            {{ $room->created_at?->format('d/m/Y H:i') }}
                        </p>
                    </div>

                    <div class="rounded-2xl border border-slate-200 p-5">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                            Cập nhật gần nhất
                        </p>

                        <p class="mt-2 font-semibold text-slate-900">
                            {{ $room->updated_at?->format('d/m/Y H:i') }}
                        </p>
                    </div>

                </div>

            </section>

            {{-- Footer --}}
            <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">

                <a
                    href="{{ route('admin.rooms.index') }}"
                    class="inline-flex items-center justify-center rounded-xl border border-slate-300 bg-white px-6 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-100"
                >
                    Quay lại danh sách
                </a>

                <a
                    href="{{ route('admin.rooms.edit', $room) }}"
                    class="inline-flex items-center justify-center rounded-xl bg-blue-600 px-6 py-3 text-sm font-semibold text-white transition hover:bg-blue-700"
                >
                    Chỉnh sửa phòng
                </a>

            </div>

        </div>

    </main>

</body>

</html>