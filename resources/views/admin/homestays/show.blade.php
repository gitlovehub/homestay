<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        Chi tiết {{ $homestay->name }} | HomeStay
    </title>

    @vite([
        'resources/css/app.css',
        'resources/js/app.js',
    ])
</head>

<body class="min-h-screen bg-slate-100">

    @include('partials.navbar')

    <main class="mx-auto max-w-6xl px-4 py-10 sm:px-6 lg:px-8">

        {{-- Điều hướng --}}
        <div class="mb-6 flex flex-row gap-4 items-center justify-between">

            <a
                href="{{ route('admin.homestays.index') }}"
                class="inline-flex text-sm font-semibold text-blue-600 transition hover:text-blue-700"
            >
                ← Quay lại danh sách Homestay
            </a>

            <div class="flex justify-end gap-2">

                <a
                    href="{{ route('admin.homestays.edit', $homestay) }}"
                    class="rounded-lg border border-amber-300 px-3 py-2 font-semibold text-amber-600 transition hover:bg-amber-50">
                    Sửa
                </a>

                <form
                    action="{{ route('admin.homestays.destroy', $homestay) }}"
                    method="POST"
                    onsubmit="return confirm('Bạn có chắc muốn xóa Homestay này không?')">

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

        <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">

            {{-- Ảnh --}}
            <div class="bg-slate-100">
                @if ($homestay->image)
                    <img
                        src="{{ Storage::url($homestay->image) }}"
                        alt="{{ $homestay->name }}"
                        class="h-72 w-full object-cover sm:h-96"
                    >
                @else
                    <div class="flex h-72 items-center justify-center text-slate-400 sm:h-96">
                        <div class="text-center">
                            <div class="text-5xl">
                                🏠
                            </div>

                            <p class="mt-3 text-sm font-semibold">
                                Chưa có ảnh đại diện
                            </p>
                        </div>
                    </div>
                @endif
            </div>

            <div class="space-y-8 p-6 sm:p-8">

                {{-- Tiêu đề --}}
                <section>
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">

                        <div>
                            <h1 class="text-3xl font-bold text-slate-900">
                                {{ $homestay->name }}
                            </h1>

                            <p class="mt-2 text-sm text-slate-500">
                                Slug: {{ $homestay->slug }}
                            </p>
                        </div>

                        <div>
                            @if ($homestay->status)
                                <span class="inline-flex h-fit w-fit items-center whitespace-nowrap rounded-full bg-green-100 px-5 py-3 text-sm font-semibold leading-none text-green-700">
                                    Hoạt động
                                </span>
                            @else
                                <span class="inline-flex h-fit w-fit items-center whitespace-nowrap rounded-full bg-red-100 px-5 py-3 text-sm font-semibold leading-none text-red-700">
                                    Tạm khóa
                                </span>
                            @endif
                        </div>
                    </div>
                </section>

                <div class="border-t border-slate-200"></div>

                {{-- Thông tin cơ bản --}}
                <section>
                    <h2 class="mb-5 text-xl font-bold text-slate-900">
                        Thông tin cơ bản
                    </h2>

                    <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">

                        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5">
                            <p class="text-sm font-medium text-slate-500">
                                Danh mục
                            </p>

                            <p class="mt-2 font-semibold text-slate-900">
                                {{ $homestay->category?->name ?? 'Chưa phân loại' }}
                            </p>
                        </div>

                        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5">
                            <p class="text-sm font-medium text-slate-500">
                                Chủ sở hữu
                            </p>

                            <p class="mt-2 font-semibold text-slate-900">
                                {{ $homestay->owner?->name ?? 'Chưa xác định' }}
                            </p>

                            @if ($homestay->owner?->email)
                                <p class="mt-1 text-sm text-slate-500">
                                    {{ $homestay->owner->email }}
                                </p>
                            @endif
                        </div>

                        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5">
                            <p class="text-sm font-medium text-slate-500">
                                Số điện thoại
                            </p>

                            <p class="mt-2 font-semibold text-slate-900">
                                {{ $homestay->phone ?: 'Chưa cập nhật' }}
                            </p>
                        </div>

                    </div>
                </section>

                {{-- Tiện ích --}}
                <section>
                    <h2 class="text-xl font-bold text-slate-900">
                        Tiện ích
                    </h2>
                    <p class="mt-1 text-sm text-slate-500">Các tiện ích hiện có tại Homestay.</p>

                    <div class="mt-5 rounded-2xl sm:col-span-2">

                        @if ($homestay->amenities->isNotEmpty())

                            <div class="flex flex-wrap gap-3">

                                @foreach ($homestay->amenities as $amenity)

                                    <div
                                        class="inline-flex items-center gap-2 rounded-xl border border-blue-200 bg-blue-50 px-4 py-2 text-sm font-semibold text-blue-700"
                                    >
                                        <span class="text-lg">
                                            {{ $amenity->icon ?: '💎' }}
                                        </span>

                                        <span>
                                            {{ $amenity->name }}
                                        </span>
                                    </div>

                                @endforeach

                            </div>

                        @else

                            <div class="rounded-xl border border-dashed border-slate-300 bg-white p-4">

                                <p class="text-sm text-slate-500">
                                    Homestay này chưa có tiện ích.
                                </p>

                            </div>

                        @endif

                    </div>
                </section>

                {{-- Địa chỉ --}}
                <section>
                    <h2 class="mb-5 text-xl font-bold text-slate-900">
                        Địa chỉ
                    </h2>

                    <div class="grid gap-5 sm:grid-cols-2">

                        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5">
                            <p class="text-sm font-medium text-slate-500">
                                Địa chỉ chi tiết
                            </p>

                            <p class="mt-2 font-semibold text-slate-900">
                                {{ $homestay->address }}
                            </p>
                        </div>

                        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5">
                            <p class="text-sm font-medium text-slate-500">
                                Thành phố
                            </p>

                            <p class="mt-2 font-semibold text-slate-900">
                                {{ $homestay->city }}
                            </p>
                        </div>

                    </div>
                </section>

                {{-- Mô tả --}}
                <section>
                    <h2 class="mb-5 text-xl font-bold text-slate-900">
                        Mô tả
                    </h2>

                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5">
                        @if ($homestay->description)
                            <p class="whitespace-pre-line leading-7 text-slate-700">
                                {{ $homestay->description }}
                            </p>
                        @else
                            <p class="text-sm italic text-slate-400">
                                Homestay chưa có nội dung mô tả.
                            </p>
                        @endif
                    </div>
                </section>

                {{-- Thời gian --}}
                <section>
                    <h2 class="mb-5 text-xl font-bold text-slate-900">
                        Thông tin hệ thống
                    </h2>

                    <div class="grid gap-5 sm:grid-cols-2">

                        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5">
                            <p class="text-sm font-medium text-slate-500">
                                Ngày tạo
                            </p>

                            <p class="mt-2 font-semibold text-slate-900">
                                {{ $homestay->created_at?->format('d/m/Y H:i') }}
                            </p>
                        </div>

                        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5">
                            <p class="text-sm font-medium text-slate-500">
                                Cập nhật lần cuối
                            </p>

                            <p class="mt-2 font-semibold text-slate-900">
                                {{ $homestay->updated_at?->format('d/m/Y H:i') }}
                            </p>
                        </div>

                    </div>
                </section>

            </div>
        </div>

    </main>

</body>

</html>