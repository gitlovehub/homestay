@extends('layouts.admin')

@section('title', 'Chỉnh sửa danh mục | HomeStayGo')

@section('page-title', 'Chỉnh sửa danh mục')

@section('content')
    <div class="mx-auto max-w-4xl">

        <p class="mb-4 text-sm font-semibold md:text-lg text-slate-500">
            Chỉnh sửa thông tin danh mục Homestay trong hệ thống.
        </p>

        <form method="POST" action="{{ route('admin.categories.update', $category) }}"
            class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">

            @csrf
            @method('PUT')

            <div class="space-y-8 p-6 sm:p-8">

                {{-- Tên danh mục --}}
                <section>
                    <div>

                        <label for="name" class="mb-2 block text-sm font-semibold text-slate-700">
                            Tên danh mục
                            <span class="text-red-500">*</span>
                        </label>

                        <input id="name" name="name" type="text" value="{{ old('name', $category->name) }}"
                            autofocus placeholder="Ví dụ: Villa, Nhà gỗ, Căn hộ..."
                            class="w-full rounded-xl border px-4 py-3 text-slate-900 outline-none transition placeholder:text-slate-400
                                {{ $errors->has('name')
                                    ? 'border-red-400 focus:border-red-500 focus:ring-4 focus:ring-red-100'
                                    : 'border-slate-300 focus:border-blue-500 focus:ring-4 focus:ring-blue-100' }}">

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

                        <label for="slug" class="mb-2 block text-sm font-semibold text-slate-700">
                            Slug
                        </label>

                        <input id="slug" name="slug" type="text" value="{{ old('slug', $category->slug) }}"
                            placeholder="Để trống để hệ thống tự tạo"
                            class="w-full rounded-xl border px-4 py-3 text-slate-900 outline-none transition placeholder:text-slate-400
                                {{ $errors->has('slug')
                                    ? 'border-red-400 focus:border-red-500 focus:ring-4 focus:ring-red-100'
                                    : 'border-slate-300 focus:border-blue-500 focus:ring-4 focus:ring-blue-100' }}">

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

                            <label for="description" class="block text-sm font-semibold text-slate-700">
                                Mô tả
                            </label>

                            <span id="description-counter" class="text-xs font-medium text-slate-400">
                                0 ký tự
                            </span>

                        </div>

                        <textarea id="description" name="description" rows="5" maxlength="1000"
                            placeholder="Nhập mô tả ngắn cho danh mục..."
                            class="w-full resize-y rounded-xl border px-4 py-3 text-slate-900 outline-none transition placeholder:text-slate-400
                                {{ $errors->has('description')
                                    ? 'border-red-400 focus:border-red-500 focus:ring-4 focus:ring-red-100'
                                    : 'border-slate-300 focus:border-blue-500 focus:ring-4 focus:ring-blue-100' }}">{{ old('description', $category->description) }}</textarea>

                        @error('description')
                            <p class="mt-2 text-sm font-medium text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>
                </section>

                {{-- Trạng thái --}}
                <section>
                    <div>

                        <label class="mb-3 block text-sm font-semibold text-slate-700">
                            Trạng thái
                            <span class="text-red-500">*</span>
                        </label>

                        <div class="grid gap-3 sm:grid-cols-2">

                            {{-- Hoạt động --}}
                            <label for="status-active"
                                class="cursor-pointer rounded-2xl border p-4 transition
                       has-checked:border-emerald-500 has-checked:bg-emerald-50 has-checked:ring-2 has-checked:ring-emerald-200
                       border-slate-300 bg-white hover:border-emerald-400 hover:bg-emerald-50">
                                <div class="flex items-start gap-3">

                                    <input id="status-active" name="status" type="radio" value="1"
                                        {{ old('status', $category->status ? '1' : '0') == '1' ? 'checked' : '' }}
                                        class="mt-1 h-4 w-4 border-slate-300 text-emerald-600 focus:ring-emerald-500">

                                    <div>
                                        <p class="text-sm font-semibold text-slate-900">
                                            Hoạt động
                                        </p>

                                        <p class="mt-1 text-xs text-slate-500">
                                            Danh mục được phép hiển thị và sử dụng.
                                        </p>
                                    </div>

                                </div>
                            </label>

                            {{-- Tạm khóa --}}
                            <label for="status-inactive"
                                class="cursor-pointer rounded-2xl border p-4 transition
                       has-checked:border-red-500 has-checked:bg-red-50 has-checked:ring-2 has-checked:ring-red-200
                       border-slate-300 bg-white hover:border-red-400 hover:bg-red-50">
                                <div class="flex items-start gap-3">

                                    <input id="status-inactive" name="status" type="radio" value="0"
                                        {{ old('status', $category->status ? '1' : '0') == '0' ? 'checked' : '' }}
                                        class="mt-1 h-4 w-4 border-slate-300 text-red-600 focus:ring-red-500">

                                    <div>
                                        <p class="text-sm font-semibold text-slate-900">
                                            Tạm khóa
                                        </p>

                                        <p class="mt-1 text-xs text-slate-500">
                                            Danh mục chưa được phép hiển thị hoặc sử dụng.
                                        </p>
                                    </div>

                                </div>
                            </label>

                        </div>

                        @error('status')
                            <p class="mt-2 text-sm font-medium text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>
                </section>

            </div>

            {{-- Nút thao tác --}}
            <div
                class="flex flex-col-reverse gap-3 border-t border-slate-200 bg-slate-50 px-6 py-5 sm:flex-row sm:justify-end sm:px-8">

                <a href="{{ route('admin.categories.index') }}"
                    class="rounded-xl border border-slate-300 px-6 py-3 text-center text-sm font-semibold text-slate-700 transition hover:bg-slate-100">
                    Hủy
                </a>

                <button type="submit"
                    class="cursor-pointer rounded-xl bg-blue-600 px-6 py-3 text-sm font-semibold text-white transition hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-200">
                    Cập nhật danh mục
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
