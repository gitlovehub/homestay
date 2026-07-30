<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Chi tiết danh mục | HomeStay</title>

    @vite([
        'resources/css/app.css',
        'resources/js/app.js',
    ])
</head>

<body class="min-h-screen bg-slate-100">

    @include('partials.navbar')

    <main class="mx-auto max-w-5xl px-4 py-10 sm:px-6 lg:px-8">

        <x-alert />

        {{-- Phần tiêu đề --}}
        <div class="mb-8 flex flex-col gap-5 sm:flex-row sm:items-end sm:justify-between">

            <div>
                <a
                    href="{{ route('admin.categories.index') }}"
                    class="text-sm font-semibold text-blue-600 transition hover:text-blue-700"
                >
                    ← Quay lại danh sách danh mục
                </a>

                <h1 class="mt-4 text-3xl font-bold text-slate-900">
                    Chi tiết danh mục
                </h1>

                <p class="mt-2 text-slate-500">
                    Xem toàn bộ thông tin của danh mục Homestay.
                </p>
            </div>

            <a
                href="{{ route('admin.categories.edit', $category) }}"
                class="inline-flex items-center justify-center rounded-xl bg-amber-500 px-5 py-3 text-sm font-semibold text-white transition hover:bg-amber-600"
            >
                Chỉnh sửa danh mục
            </a>

        </div>

        <div class="space-y-6">

            {{-- Thông tin chính --}}
            <section class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">

                <div class="border-b border-slate-200 bg-slate-50 px-6 py-5 sm:px-8">

                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                                Tên danh mục
                            </p>

                            <h2 class="mt-2 text-3xl font-bold text-slate-900">
                                {{ $category->name }}
                            </h2>
                        </div>

                        <div>
                            @if ($category->status)
                                <span class="inline-flex items-center gap-2 rounded-full bg-emerald-100 px-4 py-2 text-sm font-semibold text-emerald-700">
                                    <span class="h-2.5 w-2.5 rounded-full bg-emerald-500"></span>

                                    Hoạt động
                                </span>
                            @else
                                <span class="inline-flex items-center gap-2 rounded-full bg-red-100 px-4 py-2 text-sm font-semibold text-red-700">
                                    <span class="h-2.5 w-2.5 rounded-full bg-red-500"></span>

                                    Tạm khóa
                                </span>
                            @endif
                        </div>

                    </div>

                </div>

                <div class="grid gap-6 p-6 sm:grid-cols-2 sm:p-8">

                    {{-- ID --}}
                    <div class="rounded-2xl bg-slate-50 p-5">

                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                            Mã danh mục
                        </p>

                        <p class="mt-2 text-lg font-bold text-slate-900">
                            #{{ $category->id }}
                        </p>

                    </div>

                    {{-- Slug --}}
                    <div class="rounded-2xl bg-slate-50 p-5">

                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                            Slug
                        </p>

                        <div class="mt-2">
                            <span class="inline-flex rounded-full bg-blue-100 px-3 py-1 text-sm font-semibold text-blue-700">
                                {{ $category->slug }}
                            </span>
                        </div>

                    </div>

                </div>

            </section>

            {{-- Mô tả --}}
            <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">

                <h2 class="text-xl font-bold text-slate-900">
                    Mô tả danh mục
                </h2>

                <p class="mt-1 text-sm text-slate-500">
                    Nội dung giới thiệu và mô tả về danh mục này.
                </p>

                @if ($category->description)
                    <div class="mt-6 whitespace-pre-line rounded-2xl bg-slate-50 p-5 leading-7 text-slate-600">
                        {{ $category->description }}
                    </div>
                @else
                    <div class="mt-6 rounded-2xl border border-dashed border-slate-300 bg-slate-50 p-6 text-center">

                        <div class="text-3xl">
                            📝
                        </div>

                        <p class="mt-3 text-sm italic text-slate-400">
                            Danh mục này chưa có nội dung mô tả.
                        </p>

                    </div>
                @endif

            </section>

            {{-- Thông tin quản lý --}}
            <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">

                <div>
                    <h2 class="text-xl font-bold text-slate-900">
                        Thông tin quản lý
                    </h2>

                    <p class="mt-1 text-sm text-slate-500">
                        Thời gian danh mục được tạo và cập nhật.
                    </p>
                </div>

                <div class="mt-6 grid gap-5 sm:grid-cols-2">

                    {{-- Ngày tạo --}}
                    <div class="rounded-2xl border border-slate-200 p-5">

                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                            Ngày tạo
                        </p>

                        <p class="mt-2 font-semibold text-slate-900">
                            {{ $category->created_at?->format('d/m/Y H:i') ?? 'Không xác định' }}
                        </p>

                    </div>

                    {{-- Ngày cập nhật --}}
                    <div class="rounded-2xl border border-slate-200 p-5">

                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                            Cập nhật gần nhất
                        </p>

                        <p class="mt-2 font-semibold text-slate-900">
                            {{ $category->updated_at?->format('d/m/Y H:i') ?? 'Không xác định' }}
                        </p>

                    </div>

                </div>

            </section>


        </div>

    </main>

</body>

</html>