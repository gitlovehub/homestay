<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Thêm mới Homestay | HomeStay</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-slate-100">

    @include('partials.navbar')

    <main class="mx-auto max-w-5xl px-4 py-10 sm:px-6 lg:px-8">

        <a
            href="{{ route('admin.homestays.index') }}"
            class="mb-4 inline-flex text-sm font-semibold text-blue-600 transition hover:text-blue-700"
        >
            ← Quay lại danh sách Homestay
        </a>

        <div class="mb-8">
            <h1 class="text-3xl font-bold text-slate-900">
                Thêm mới Homestay
            </h1>

            <p class="mt-2 text-slate-500">
                Nhập đầy đủ thông tin để thêm Homestay mới vào hệ thống.
            </p>
        </div>

        <form
            action="{{ route('admin.homestays.store') }}"
            method="POST"
            enctype="multipart/form-data"
            class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm"
        >
            @csrf

            <div class="space-y-8 p-6 sm:p-8">

                {{-- Thông tin cơ bản --}}
                <section>
                    <div class="mb-6">
                        <h2 class="text-lg font-bold text-slate-900">
                            Thông tin cơ bản
                        </h2>

                        <p class="mt-1 text-sm text-slate-500">
                            Tên, danh mục và chủ sở hữu của Homestay.
                        </p>
                    </div>

                    <div class="grid gap-6 md:grid-cols-2">

                        {{-- Tên Homestay --}}
                        <div class="md:col-span-2">
                            <label
                                for="name"
                                class="mb-2 block text-sm font-semibold text-slate-700"
                            >
                                Tên Homestay
                                <span class="text-red-500">*</span>
                            </label>

                            <input
                                id="name"
                                type="text"
                                name="name"
                                value="{{ old('name') }}"
                                placeholder="Ví dụ: Ocean View Homestay"
                                class="w-full rounded-xl border px-4 py-3 text-sm text-slate-700 outline-none transition
                                    {{ $errors->has('name')
                                        ? 'border-red-400 focus:border-red-500 focus:ring-4 focus:ring-red-100'
                                        : 'border-slate-300 focus:border-blue-500 focus:ring-4 focus:ring-blue-100' }}"
                            >

                            @error('name')
                                <p class="mt-2 text-sm font-medium text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- Danh mục --}}
                        <div>
                            <label
                                for="category_id"
                                class="mb-2 block text-sm font-semibold text-slate-700"
                            >
                                Danh mục
                                <span class="text-red-500">*</span>
                            </label>

                            <select
                                id="category_id"
                                name="category_id"
                                class="w-full rounded-xl border bg-white px-4 py-3 text-sm text-slate-700 outline-none transition
                                    {{ $errors->has('category_id')
                                        ? 'border-red-400 focus:border-red-500 focus:ring-4 focus:ring-red-100'
                                        : 'border-slate-300 focus:border-blue-500 focus:ring-4 focus:ring-blue-100' }}"
                            >
                                <option value="">
                                    -- Chọn danh mục --
                                </option>

                                @foreach ($categories as $category)
                                    <option
                                        value="{{ $category->id }}"
                                        @selected(old('category_id') == $category->id)
                                    >
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>

                            @error('category_id')
                                <p class="mt-2 text-sm font-medium text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- Chủ sở hữu --}}
                        <div>
                            <label
                                for="owner_id"
                                class="mb-2 block text-sm font-semibold text-slate-700"
                            >
                                Chủ sở hữu
                                <span class="text-red-500">*</span>
                            </label>

                            <select
                                id="owner_id"
                                name="owner_id"
                                class="w-full rounded-xl border bg-white px-4 py-3 text-sm text-slate-700 outline-none transition
                                    {{ $errors->has('owner_id')
                                        ? 'border-red-400 focus:border-red-500 focus:ring-4 focus:ring-red-100'
                                        : 'border-slate-300 focus:border-blue-500 focus:ring-4 focus:ring-blue-100' }}"
                            >
                                <option value="">
                                    -- Chọn chủ sở hữu --
                                </option>

                                @foreach ($owners as $owner)
                                    <option
                                        value="{{ $owner->id }}"
                                        @selected(old('owner_id') == $owner->id)
                                    >
                                        {{ $owner->name }} - {{ $owner->email }}
                                    </option>
                                @endforeach
                            </select>

                            @error('owner_id')
                                <p class="mt-2 text-sm font-medium text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                    </div>
                </section>

                <div class="border-t border-slate-200"></div>

                {{-- Địa chỉ và liên hệ --}}
                <section>
                    <div class="mb-6">
                        <h2 class="text-lg font-bold text-slate-900">
                            Địa chỉ và liên hệ
                        </h2>

                        <p class="mt-1 text-sm text-slate-500">
                            Thông tin vị trí và số điện thoại liên hệ.
                        </p>
                    </div>

                    <div class="grid gap-6 md:grid-cols-2">

                        {{-- Địa chỉ --}}
                        <div class="md:col-span-2">
                            <label
                                for="address"
                                class="mb-2 block text-sm font-semibold text-slate-700"
                            >
                                Địa chỉ
                                <span class="text-red-500">*</span>
                            </label>

                            <input
                                id="address"
                                type="text"
                                name="address"
                                value="{{ old('address') }}"
                                placeholder="Ví dụ: 123 đường Trần Phú"
                                class="w-full rounded-xl border px-4 py-3 text-sm text-slate-700 outline-none transition
                                    {{ $errors->has('address')
                                        ? 'border-red-400 focus:border-red-500 focus:ring-4 focus:ring-red-100'
                                        : 'border-slate-300 focus:border-blue-500 focus:ring-4 focus:ring-blue-100' }}"
                            >

                            @error('address')
                                <p class="mt-2 text-sm font-medium text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- Thành phố --}}
                        <div>
                            <label
                                for="city"
                                class="mb-2 block text-sm font-semibold text-slate-700"
                            >
                                Thành phố
                                <span class="text-red-500">*</span>
                            </label>

                            <input
                                id="city"
                                type="text"
                                name="city"
                                value="{{ old('city') }}"
                                placeholder="Ví dụ: Đà Nẵng"
                                class="w-full rounded-xl border px-4 py-3 text-sm text-slate-700 outline-none transition
                                    {{ $errors->has('city')
                                        ? 'border-red-400 focus:border-red-500 focus:ring-4 focus:ring-red-100'
                                        : 'border-slate-300 focus:border-blue-500 focus:ring-4 focus:ring-blue-100' }}"
                            >

                            @error('city')
                                <p class="mt-2 text-sm font-medium text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- Số điện thoại --}}
                        <div>
                            <label
                                for="phone"
                                class="mb-2 block text-sm font-semibold text-slate-700"
                            >
                                Số điện thoại
                                <span class="text-red-500">*</span>
                            </label>

                            <input
                                id="phone"
                                type="text"
                                name="phone"
                                value="{{ old('phone') }}"
                                placeholder="Ví dụ: 0987654321"
                                class="w-full rounded-xl border px-4 py-3 text-sm text-slate-700 outline-none transition
                                    {{ $errors->has('phone')
                                        ? 'border-red-400 focus:border-red-500 focus:ring-4 focus:ring-red-100'
                                        : 'border-slate-300 focus:border-blue-500 focus:ring-4 focus:ring-blue-100' }}"
                            >

                            @error('phone')
                                <p class="mt-2 text-sm font-medium text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                    </div>
                </section>

                <div class="border-t border-slate-200"></div>

                {{-- Ảnh và mô tả --}}
                <section>
                    <div class="mb-6">
                        <h2 class="text-lg font-bold text-slate-900">
                            Hình ảnh và mô tả
                        </h2>

                        <p class="mt-1 text-sm text-slate-500">
                            Thêm ảnh đại diện và nội dung giới thiệu Homestay.
                        </p>
                    </div>

                    <div class="grid gap-6">

                        {{-- Upload ảnh --}}
                        <div>
                            <label
                                for="image"
                                class="mb-2 block text-sm font-semibold text-slate-700"
                            >
                                Ảnh đại diện
                            </label>

                            <label
                                for="image"
                                class="flex min-h-44 cursor-pointer flex-col items-center justify-center rounded-2xl border-2 border-dashed border-slate-300 bg-slate-50 px-6 py-8 text-center transition hover:border-blue-400 hover:bg-blue-50"
                            >
                                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-blue-100 text-2xl">
                                    📷
                                </div>

                                <p class="mt-4 text-sm font-semibold text-slate-700">
                                    Nhấn để chọn ảnh đại diện
                                </p>

                                <p class="mt-1 text-xs text-slate-400">
                                    JPG, JPEG, PNG hoặc WEBP. Tối đa 2MB.
                                </p>
                            </label>

                            <input
                                id="image"
                                type="file"
                                name="image"
                                accept=".jpg,.jpeg,.png,.webp"
                                class="hidden"
                            >

                            @error('image')
                                <p class="mt-2 text-sm font-medium text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- Preview ảnh --}}
                        <div
                            id="image-preview-wrapper"
                            class="hidden"
                        >
                            <div class="mb-2 flex items-center justify-between">
                                <p class="text-sm font-semibold text-slate-700">
                                    Xem trước ảnh
                                </p>

                                <button
                                    id="remove-image"
                                    type="button"
                                    class="text-sm font-semibold text-red-600 transition hover:text-red-700"
                                >
                                    Xóa ảnh
                                </button>
                            </div>

                            <img
                                id="image-preview"
                                src=""
                                alt="Ảnh Homestay xem trước"
                                class="h-64 w-full rounded-2xl border border-slate-200 object-cover"
                            >
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
                                rows="7"
                                placeholder="Nhập mô tả về Homestay..."
                                class="w-full resize-y rounded-xl border px-4 py-3 text-sm text-slate-700 outline-none transition
                                    {{ $errors->has('description')
                                        ? 'border-red-400 focus:border-red-500 focus:ring-4 focus:ring-red-100'
                                        : 'border-slate-300 focus:border-blue-500 focus:ring-4 focus:ring-blue-100' }}"
                            >{{ old('description') }}</textarea>

                            @error('description')
                                <p class="mt-2 text-sm font-medium text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                    </div>
                </section>

                <div class="border-t border-slate-200"></div>

                {{-- Trạng thái --}}
                <section>
                    <div class="mb-4">
                        <h2 class="text-lg font-bold text-slate-900">
                            Trạng thái
                        </h2>

                        <p class="mt-1 text-sm text-slate-500">
                            Chọn trạng thái hiển thị của Homestay.
                        </p>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2">

                        {{-- Hoạt động --}}
                        <label
                            class="cursor-pointer rounded-2xl border border-slate-200 bg-white p-4 transition-all
                                hover:border-green-400 hover:bg-green-50
                                has-[:checked]:border-green-500
                                has-[:checked]:bg-green-50"
                        >
                            <div class="flex items-start gap-3">
                                <input
                                    type="radio"
                                    name="status"
                                    value="1"
                                    class="mt-1 h-4 w-4 border-slate-300 text-green-600 focus:ring-green-500"
                                    @checked(old('status', '1') == '1')
                                >

                                <div>
                                    <p class="font-semibold text-slate-800">
                                        Hoạt động
                                    </p>

                                    <p class="mt-1 text-sm text-slate-500">
                                        Homestay được phép hiển thị trong hệ thống.
                                    </p>
                                </div>
                            </div>
                        </label>

                        {{-- Tạm khóa --}}
                        <label
                            class="cursor-pointer rounded-2xl border border-slate-200 bg-white p-4 transition-all
                                hover:border-red-400 hover:bg-red-50
                                has-[:checked]:border-red-500
                                has-[:checked]:bg-red-50"
                        >
                            <div class="flex items-start gap-3">
                                <input
                                    type="radio"
                                    name="status"
                                    value="0"
                                    class="mt-1 h-4 w-4 border-slate-300 text-red-600 focus:ring-red-500"
                                    @checked(old('status') == '0')
                                >

                                <div>
                                    <p class="font-semibold text-slate-800">
                                        Tạm khóa
                                    </p>

                                    <p class="mt-1 text-sm text-slate-500">
                                        Homestay tạm thời không hiển thị.
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
                </section>

            </div>

            {{-- Nút hành động --}}
            <div class="flex flex-col-reverse gap-3 border-t border-slate-200 bg-slate-50 px-6 py-5 sm:flex-row sm:justify-end sm:px-8">

                <a
                    href="{{ route('admin.homestays.index') }}"
                    class="inline-flex items-center justify-center rounded-xl border border-slate-300 bg-white px-6 py-3 text-sm font-semibold text-slate-600 transition hover:bg-slate-100"
                >
                    Hủy
                </a>

                <button
                    type="submit"
                    class="inline-flex items-center justify-center rounded-xl bg-blue-600 px-6 py-3 text-sm font-semibold text-white transition hover:bg-blue-700"
                >
                    Thêm mới Homestay
                </button>

            </div>

        </form>

    </main>

    <script>
        const imageInput = document.getElementById('image');
        const previewWrapper = document.getElementById('image-preview-wrapper');
        const previewImage = document.getElementById('image-preview');
        const removeImageButton = document.getElementById('remove-image');

        imageInput.addEventListener('change', function (event) {
            const file = event.target.files[0];

            if (!file) {
                hidePreview();
                return;
            }

            previewImage.src = URL.createObjectURL(file);
            previewWrapper.classList.remove('hidden');
        });

        removeImageButton.addEventListener('click', function () {
            imageInput.value = '';
            hidePreview();
        });

        function hidePreview() {
            previewWrapper.classList.add('hidden');

            if (previewImage.src) {
                URL.revokeObjectURL(previewImage.src);
            }

            previewImage.src = '';
        }
    </script>

</body>

</html>