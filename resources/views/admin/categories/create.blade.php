@extends('layouts.admin')

@section('title', 'Thêm danh mục | HomeStayGo')

@section('page-title', 'Thêm danh mục')

@section('content')
    <div class="mx-auto max-w-4xl">
        <div class="mb-6">
            <h2 class="text-lg font-semibold text-slate-900 dark:text-slate-100 md:text-2xl">
                Thêm danh mục
            </h2>

            <a href="{{ route('admin.categories.index') }}"
                class="mt-1 inline-flex items-center gap-1 text-xs font-medium text-blue-600 transition hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 sm:text-sm">
                ←
                Trở về danh sách danh mục
            </a>
        </div>

        <form method="POST" action="{{ route('admin.categories.store') }}"
            class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-800">
            @csrf

            <div class="space-y-8 p-6 sm:p-8">
                <section>
                    <label for="name" class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-300">
                        Tên danh mục <span class="text-red-500">*</span>
                    </label>

                    <input id="name" name="name" type="text" value="{{ old('name') }}" autofocus
                        placeholder="Ví dụ: Villa, Nhà gỗ, Căn hộ..."
                        class="h-11 w-full rounded-xl border bg-white px-4 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 dark:bg-slate-900 dark:text-slate-100
                            {{ $errors->has('name')
                                ? 'border-red-400 focus:border-red-500 focus:ring-4 focus:ring-red-100 dark:focus:ring-red-900/40'
                                : 'border-slate-300 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 dark:border-slate-600 dark:focus:border-blue-400 dark:focus:ring-blue-900/40' }}">

                    @error('name')
                        <p class="mt-2 text-sm font-medium text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </section>

                <section>
                    <label for="slug" class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-300">
                        Slug
                    </label>

                    <input id="slug" name="slug" type="text" value="{{ old('slug') }}"
                        placeholder="Để trống để hệ thống tự tạo"
                        class="h-11 w-full rounded-xl border bg-white px-4 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 dark:bg-slate-900 dark:text-slate-100
                            {{ $errors->has('slug')
                                ? 'border-red-400 focus:border-red-500 focus:ring-4 focus:ring-red-100 dark:focus:ring-red-900/40'
                                : 'border-slate-300 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 dark:border-slate-600 dark:focus:border-blue-400 dark:focus:ring-blue-900/40' }}">

                    <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">
                        Ví dụ: “Nhà gỗ” sẽ có slug là “nha-go”.
                    </p>

                    @error('slug')
                        <p class="mt-2 text-sm font-medium text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </section>

                <section>
                    <div class="mb-2 flex items-center justify-between gap-4">
                        <label for="description" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">
                            Mô tả
                        </label>
                        <span id="description-counter" class="text-xs font-medium text-slate-400 dark:text-slate-500">
                            0/1000 ký tự
                        </span>
                    </div>

                    <textarea id="description" name="description" rows="5" maxlength="1000"
                        placeholder="Nhập mô tả ngắn cho danh mục..."
                        class="w-full resize-y rounded-xl border bg-white px-4 py-3 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 dark:bg-slate-900 dark:text-slate-100
                            {{ $errors->has('description')
                                ? 'border-red-400 focus:border-red-500 focus:ring-4 focus:ring-red-100 dark:focus:ring-red-900/40'
                                : 'border-slate-300 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 dark:border-slate-600 dark:focus:border-blue-400 dark:focus:ring-blue-900/40' }}">{{ old('description') }}</textarea>

                    @error('description')
                        <p class="mt-2 text-sm font-medium text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </section>

                <section>
                    <div class="mb-6">
                        <h3 class="text-lg font-bold text-slate-900 dark:text-slate-100">Trạng thái</h3>
                        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                            Chọn trạng thái hoạt động của danh mục.
                        </p>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <label class="cursor-pointer">
                            <input type="radio" name="status" value="1" class="peer sr-only"
                                @checked(old('status', '1') == '1')>

                            <div class="flex items-start gap-4 rounded-2xl border-2 border-slate-200 bg-white p-5 transition
                                hover:border-emerald-300
                                peer-checked:border-emerald-500 peer-checked:bg-emerald-50
                                peer-checked:[&_.radio-circle]:border-emerald-500
                                peer-checked:[&_.radio-dot]:opacity-100
                                dark:border-slate-700 dark:bg-slate-900 dark:hover:border-emerald-600
                                dark:peer-checked:border-emerald-500 dark:peer-checked:bg-emerald-950/40">
                                <div class="radio-circle mt-1 flex h-5 w-5 shrink-0 items-center justify-center rounded-full border-2 border-slate-300 transition dark:border-slate-500">
                                    <div class="radio-dot h-2.5 w-2.5 rounded-full bg-emerald-500 opacity-0 transition"></div>
                                </div>
                                <div>
                                    <p class="font-bold text-slate-900 dark:text-slate-100">Hoạt động</p>
                                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                                        Danh mục được phép hiển thị và sử dụng.
                                    </p>
                                </div>
                            </div>
                        </label>

                        <label class="cursor-pointer">
                            <input type="radio" name="status" value="0" class="peer sr-only"
                                @checked(old('status') == '0')>

                            <div class="flex items-start gap-4 rounded-2xl border-2 border-slate-200 bg-white p-5 transition
                                hover:border-red-300
                                peer-checked:border-red-500 peer-checked:bg-red-50
                                peer-checked:[&_.radio-circle]:border-red-500
                                peer-checked:[&_.radio-dot]:opacity-100
                                dark:border-slate-700 dark:bg-slate-900 dark:hover:border-red-600
                                dark:peer-checked:border-red-500 dark:peer-checked:bg-red-950/40">
                                <div class="radio-circle mt-1 flex h-5 w-5 shrink-0 items-center justify-center rounded-full border-2 border-slate-300 transition dark:border-slate-500">
                                    <div class="radio-dot h-2.5 w-2.5 rounded-full bg-red-500 opacity-0 transition"></div>
                                </div>
                                <div>
                                    <p class="font-bold text-slate-900 dark:text-slate-100">Tạm khóa</p>
                                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                                        Danh mục chưa được phép hiển thị hoặc sử dụng.
                                    </p>
                                </div>
                            </div>
                        </label>
                    </div>

                    @error('status')
                        <p class="mt-2 text-sm font-medium text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </section>
            </div>

            <div class="flex flex-col-reverse gap-3 border-t border-slate-200 bg-slate-50 px-6 py-5 sm:flex-row sm:justify-end sm:px-8 dark:border-slate-700 dark:bg-slate-900/50">
                <a href="{{ route('admin.categories.index') }}"
                    class="inline-flex h-11 items-center justify-center rounded-xl border border-slate-300 bg-white px-6 text-sm font-semibold text-slate-700 transition hover:bg-slate-100 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-200 dark:hover:bg-slate-700">
                    Hủy
                </a>

                <button type="submit"
                    class="inline-flex h-11 cursor-pointer items-center justify-center rounded-xl bg-blue-600 px-6 text-sm font-semibold text-white transition hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-200 dark:focus:ring-blue-900/40">
                    Thêm danh mục
                </button>
            </div>
        </form>
    </div>

    <script>
        const description = document.getElementById('description');
        const counter = document.getElementById('description-counter');

        const updateCounter = () => {
            counter.textContent = `${description.value.length}/1000 ký tự`;
        };

        description.addEventListener('input', updateCounter);
        updateCounter();
    </script>
@endsection