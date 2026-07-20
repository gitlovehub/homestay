<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Thêm danh mục | HomeStay</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-slate-100">

    @include('partials.navbar')

    <main class="mx-auto max-w-5xl px-4 py-10 sm:px-6 lg:px-8">

        <div class="mb-8">

            <a
                href="{{ route('admin.categories.index') }}"
                class="text-sm font-semibold text-blue-600 transition hover:text-blue-700"
            >
                <span aria-hidden="true">←</span>
                Quay lại danh sách
            </a>

            <h1 class="mt-4 text-3xl font-bold text-slate-900">
                Thêm danh mục
            </h1>

            <p class="mt-2 text-slate-500">
                Tạo một loại Homestay mới trong hệ thống.
            </p>

        </div>

        <form
            method="POST"
            action="{{ route('admin.categories.store') }}"
            class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm"
        >

            @csrf

            <div class="space-y-8 p-6 sm:p-8">

                {{-- Tên danh mục --}}
                <section>
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
                            value="{{ old('name') }}"
                            autofocus
                            placeholder="Ví dụ: Villa, Nhà gỗ, Căn hộ..."
                            
                            class="w-full rounded-xl border px-4 py-3 text-slate-900 outline-none transition placeholder:text-slate-400
                                {{ $errors->has('name')
                                    ? 'border-red-400 focus:border-red-500 focus:ring-4 focus:ring-red-100'
                                    : 'border-slate-300 focus:border-blue-500 focus:ring-4 focus:ring-blue-100'
                                }}"
                        >

                        @error('name')
                            <p class="mt-2 text-sm font-medium text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>
                </section>

                {{-- Slug --}}
                <section>
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
                            value="{{ old('slug') }}"
                            placeholder="Để trống để hệ thống tự tạo"
                            
                            class="w-full rounded-xl border px-4 py-3 text-slate-900 outline-none transition placeholder:text-slate-400
                                {{ $errors->has('slug')
                                    ? 'border-red-400 focus:border-red-500 focus:ring-4 focus:ring-red-100'
                                    : 'border-slate-300 focus:border-blue-500 focus:ring-4 focus:ring-blue-100'
                                }}"
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
                </section>

                {{-- Mô tả --}}
                <section>
                    <div>

                        <div class="mb-2 flex items-center justify-between gap-4">

                            <label
                                for="description"
                                class="block text-sm font-semibold text-slate-700"
                            >
                                Mô tả
                            </label>

                            <span
                                id="description-counter"
                                class="text-xs font-medium text-slate-400"
                            >
                                0 ký tự
                            </span>

                        </div>

                        <textarea
                            id="description"
                            name="description"
                            rows="5"
                            placeholder="Nhập mô tả ngắn cho danh mục..."
                            class="w-full resize-y rounded-xl border px-4 py-3 text-slate-900 outline-none transition placeholder:text-slate-400
                                {{ $errors->has('description')
                                    ? 'border-red-400 focus:border-red-500 focus:ring-4 focus:ring-red-100'
                                    : 'border-slate-300 focus:border-blue-500 focus:ring-4 focus:ring-blue-100'
                                }}"                            
                        >{{ old('description') }}</textarea>

                        @error('description')
                            <p class="mt-2 text-sm font-medium text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>
                </section>

            </div>

            <div class="flex flex-col-reverse gap-3 border-t border-slate-200 bg-slate-50 px-6 py-5 sm:flex-row sm:justify-end sm:px-8">

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
                    Thêm danh mục
                </button>

            </div>

        </form>

    </main>

    <script>
        const description = document.getElementById('description');
        const counter = document.getElementById('description-counter');

        const updateCounter = () => {
            counter.textContent = `${description.value.length} ký tự`;
        };

        description.addEventListener('input', updateCounter);
        updateCounter();
    </script>
</body>

</html>