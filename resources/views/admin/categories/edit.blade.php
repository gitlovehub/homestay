<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Cập nhật danh mục | HomeStay</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-slate-100">

    @include('partials.navbar')

    <main class="mx-auto max-w-4xl px-4 py-10 sm:px-6 lg:px-8">

        <x-alert />

        <div class="mb-8">

            <a
                href="{{ route('admin.categories.index') }}"
                class="text-sm font-semibold text-blue-600 transition hover:text-blue-700"
            >
                ← Quay lại danh sách
            </a>

            <h1 class="mt-4 text-3xl font-bold text-slate-900">
                Cập nhật danh mục
            </h1>

            <p class="mt-2 text-slate-500">
                Tạo một loại Homestay mới trong hệ thống.
            </p>

        </div>

        <div class="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">

            <form
                method="POST"
                action="{{ route('admin.categories.update', $category) }}"
                class="space-y-6"
            >

                @csrf
                @method('PUT')

                {{-- Tên danh mục --}}
                <div>

                    <label
                        for="name"
                        class="mb-2 block text-sm font-semibold text-slate-700"
                    >
                        Tên danh mục
                        <span class="text-red-500">*</span>
                    </label>

                    <input
                        id="name"
                        name="name"
                        type="text"
                        value="{{ old('name', $category->name) }}"
                        required
                        autofocus
                        placeholder="Ví dụ: Villa, Nhà gỗ, Căn hộ..."
                        class="w-full rounded-xl border border-slate-300 px-4 py-3 text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                    >

                    @error('name')
                        <p class="mt-2 text-sm font-medium text-red-600">
                            {{ $message }}
                        </p>
                    @enderror

                </div>

                {{-- Slug --}}
                <div>

                    <label
                        for="slug"
                        class="mb-2 block text-sm font-semibold text-slate-700"
                    >
                        Slug
                    </label>

                    <input
                        id="slug"
                        name="slug"
                        type="text"
                        value="{{ old('slug', $category->slug) }}"
                        placeholder="Để trống để hệ thống tự tạo"
                        class="w-full rounded-xl border border-slate-300 px-4 py-3 text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                    >

                    <p class="mt-2 text-xs text-slate-500">
                        Ví dụ: “Nhà gỗ” sẽ có slug là “nha-go”.
                    </p>

                    @error('slug')
                        <p class="mt-2 text-sm font-medium text-red-600">
                            {{ $message }}
                        </p>
                    @enderror

                </div>

                {{-- Mô tả --}}
                <div>

                    <label
                        for="description"
                        class="mb-2 block text-sm font-semibold text-slate-700"
                    >
                        Mô tả
                    </label>

                    <textarea
                        id="description"
                        name="description"
                        rows="5"
                        placeholder="Nhập mô tả ngắn cho danh mục..."
                        class="w-full resize-none rounded-xl border border-slate-300 px-4 py-3 text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                    >{{ old('description', $category->description) }}</textarea>

                    @error('description')
                        <p class="mt-2 text-sm font-medium text-red-600">
                            {{ $message }}
                        </p>
                    @enderror

                </div>

                <div class="flex flex-col-reverse gap-3 border-t border-slate-200 pt-6 sm:flex-row sm:justify-end">

                    <a
                        href="{{ route('admin.categories.index') }}"
                        class="rounded-xl border border-slate-300 px-6 py-3 text-center text-sm font-semibold text-slate-700 transition hover:bg-slate-50"
                    >
                        Hủy
                    </a>

                    <button
                        type="submit"
                        class="cursor-pointer rounded-xl bg-blue-600 px-6 py-3 text-sm font-semibold text-white transition hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-200"
                    >
                        Cập nhật danh mục
                    </button>

                </div>

            </form>

        </div>

    </main>

</body>

</html>