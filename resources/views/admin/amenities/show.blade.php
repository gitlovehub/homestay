<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Cập nhật tiện ích | HomeStay</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-slate-100">

    @include('partials.navbar')

    <main class="mx-auto max-w-5xl px-4 py-10 sm:px-6 lg:px-8">

        <div class="mb-8">

            <a
                href="{{ route('admin.amenities.index') }}"
                class="text-sm font-semibold text-blue-600 transition hover:text-blue-700"
            >
                <span aria-hidden="true">←</span>
                Quay lại danh sách tiện ích
            </a>

            <h1 class="mt-4 text-3xl font-bold text-slate-900">
                Chi tiết tiện ích
            </h1>

            <p class="mt-2 text-slate-500">
                Xem đầy đủ thông tin của tiện ích trong hệ thống.
            </p>

        </div>

        {{-- Card chi tiết --}}
        <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">

            <div class="border-b border-slate-200 bg-slate-50 px-6 py-6 sm:px-8">

                <div class="flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">

                    <div class="flex items-center gap-4">

                        {{-- Icon --}}
                        <div
                            class="flex h-16 w-16 shrink-0 items-center justify-center rounded-2xl border border-slate-200 bg-white text-3xl shadow-sm"
                        >
                            {{ $amenity->icon ?: '💎' }}
                        </div>

                        <div>

                            <h2 class="text-2xl font-bold text-slate-900">
                                {{ $amenity->name }}
                            </h2>

                            <div class="mt-2">

                                @if ($amenity->status)
                                    <span
                                        class="inline-flex items-center gap-2 rounded-full bg-emerald-100 px-4 py-1.5 text-sm font-semibold text-emerald-700"
                                    >
                                        <span class="h-2 w-2 rounded-full bg-emerald-500"></span>

                                        Đang hoạt động
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center gap-2 rounded-full bg-slate-200 px-4 py-1.5 text-sm font-semibold text-slate-600"
                                    >
                                        <span class="h-2 w-2 rounded-full bg-slate-500"></span>

                                        Ngừng hoạt động
                                    </span>
                                @endif

                            </div>

                        </div>

                    </div>

                    {{-- Nút sửa/xóa --}}

                    <div class="flex justify-end gap-2">

                        <a
                            href="{{ route('admin.amenities.edit', $amenity) }}"
                            class="rounded-lg border border-amber-300 px-3 py-2 font-semibold text-amber-600 transition hover:bg-amber-50">
                            Sửa
                        </a>

                        <form
                            action="{{ route('admin.amenities.destroy', $amenity) }}"
                            method="POST"
                            onsubmit="return confirm('Bạn có chắc chắn muốn xóa tiện ích này không?')"
                        >
                            @csrf
                            @method('DELETE')

                            <button
                                type="submit"
                                class="cursor-pointer rounded-lg border border-red-300 px-3 py-2 font-semibold text-red-600 transition hover:bg-red-50">
                                Xóa
                            </button>
                        </form>

                    </div>

                </div>

            </div>

            {{-- Nội dung --}}
            <div class="p-6 sm:p-8">

                <div class="grid gap-6 sm:grid-cols-2">

                    {{-- Mã tiện ích --}}
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5">

                        <p class="text-sm font-semibold text-slate-500">
                            Mã tiện ích
                        </p>

                        <p class="mt-2 text-lg font-bold text-slate-900">
                            #{{ $amenity->id }}
                        </p>

                    </div>

                    {{-- Tên tiện ích --}}
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5">

                        <p class="text-sm font-semibold text-slate-500">
                            Tên tiện ích
                        </p>

                        <p class="mt-2 text-lg font-bold text-slate-900">
                            {{ $amenity->name }}
                        </p>

                    </div>

                    {{-- Icon --}}
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5">

                        <p class="text-sm font-semibold text-slate-500">
                            Icon
                        </p>

                        <div class="mt-2 flex items-center gap-3">

                            <span class="text-3xl">
                                {{ $amenity->icon ?: '💎' }}
                            </span>

                            <span class="text-sm text-slate-500">
                                {{ $amenity->icon ? ' ' : 'Chưa có icon' }}
                            </span>

                        </div>

                    </div>

                    {{-- Trạng thái --}}
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5">

                        <p class="text-sm font-semibold text-slate-500">
                            Trạng thái
                        </p>

                        <div class="mt-3">

                            @if ($amenity->status)
                                <span
                                    class="inline-flex items-center gap-2 rounded-full bg-emerald-100 px-3 py-1.5 text-sm font-semibold text-emerald-700"
                                >
                                    <span class="h-2 w-2 rounded-full bg-emerald-500"></span>

                                    Đang hoạt động
                                </span>
                            @else
                                <span
                                    class="inline-flex items-center gap-2 rounded-full bg-slate-200 px-3 py-1.5 text-sm font-semibold text-slate-600"
                                >
                                    <span class="h-2 w-2 rounded-full bg-slate-500"></span>

                                    Ngừng hoạt động
                                </span>
                            @endif

                        </div>

                    </div>

                    {{-- Ngày tạo --}}
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5">

                        <p class="text-sm font-semibold text-slate-500">
                            Ngày tạo
                        </p>

                        <p class="mt-2 font-semibold text-slate-900">
                            {{ $amenity->created_at?->format('d/m/Y H:i') }}
                        </p>

                    </div>

                    {{-- Ngày cập nhật --}}
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5">

                        <p class="text-sm font-semibold text-slate-500">
                            Cập nhật lần cuối
                        </p>

                        <p class="mt-2 font-semibold text-slate-900">
                            {{ $amenity->updated_at?->format('d/m/Y H:i') }}
                        </p>

                    </div>

                    {{-- Mô tả --}}
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5 sm:col-span-2">

                        <p class="text-sm font-semibold text-slate-500">
                            Mô tả
                        </p>

                        <p class="mt-3 whitespace-pre-line leading-7 text-slate-700">
                            {{ $amenity->description ?: 'Chưa có mô tả cho tiện ích này.' }}
                        </p>

                    </div>

                </div>

            </div>

        </div>

    </main>

</body>

</html>