<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Chỉnh sửa phòng | HomeStay</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-slate-100">

    @include('partials.navbar')

    <main class="mx-auto max-w-5xl px-4 py-10 sm:px-6 lg:px-8">

        {{-- Header --}}
        <div class="mb-8">

            <a
                href="{{ route('admin.rooms.index') }}"
                class="text-sm font-semibold text-blue-600 transition hover:text-blue-700"
            >
                ← Quay lại danh sách phòng
            </a>

            <h1 class="mt-4 text-3xl font-bold text-slate-900">
                Chỉnh sửa phòng
            </h1>

            <p class="mt-2 text-slate-500">
                Cập nhật thông tin phòng {{ $room->name }}.
            </p>

        </div>

        {{-- Lỗi --}}
        @if ($errors->any())
            <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 p-5">

                <p class="font-semibold text-red-700">
                    Vui lòng kiểm tra lại thông tin:
                </p>

                <ul class="mt-3 list-inside list-disc space-y-1 text-sm text-red-600">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>

            </div>
        @endif

        <form
            action="{{ route('admin.rooms.update', $room) }}"
            method="POST"
            enctype="multipart/form-data"
            class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm"
        >

            @csrf
            @method('PUT')

            <div class="space-y-8 p-6 sm:p-8">

                {{-- Thông tin cơ bản --}}
                <section>

                    <div class="mb-6">
                        <h2 class="text-lg font-bold text-slate-900">
                            Thông tin cơ bản
                        </h2>

                        <p class="mt-1 text-sm text-slate-500">
                            Thông tin nhận diện và phân loại phòng.
                        </p>
                    </div>

                    <div class="grid gap-6 md:grid-cols-2">

                        {{-- Homestay --}}
                        <div class="md:col-span-2">
                            <label
                                for="homestay_id"
                                class="mb-2 block text-sm font-semibold text-slate-700"
                            >
                                Homestay
                                <span class="text-red-500">*</span>
                            </label>

                            <select
                                id="homestay_id"
                                name="homestay_id"
                                required
                                class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-slate-900 outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                            >
                                <option value="">-- Chọn Homestay --</option>

                                @foreach ($homestays as $homestay)
                                    <option
                                        value="{{ $homestay->id }}"
                                        @selected(old('homestay_id', $room->homestay_id) == $homestay->id)
                                    >
                                        {{ $homestay->name }}
                                    </option>
                                @endforeach
                            </select>

                            @error('homestay_id')
                                <p class="mt-2 text-sm text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- Tên phòng --}}
                        <div>
                            <label
                                for="name"
                                class="mb-2 block text-sm font-semibold text-slate-700"
                            >
                                Tên phòng
                                <span class="text-red-500">*</span>
                            </label>

                            <input
                                id="name"
                                name="name"
                                type="text"
                                value="{{ old('name', $room->name) }}"
                                required
                                placeholder="Ví dụ: Phòng Deluxe 01"
                                class="w-full rounded-xl border border-slate-300 px-4 py-3 text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                            >

                            @error('name')
                                <p class="mt-2 text-sm text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- Mã phòng --}}
                        <div>
                            <label
                                for="room_code"
                                class="mb-2 block text-sm font-semibold text-slate-700"
                            >
                                Mã phòng
                                <span class="text-red-500">*</span>
                            </label>

                            <input
                                id="room_code"
                                name="room_code"
                                type="text"
                                value="{{ old('room_code', $room->room_code) }}"
                                required
                                placeholder="Ví dụ: DLX-001"
                                class="w-full rounded-xl border border-slate-300 px-4 py-3 uppercase text-slate-900 outline-none transition placeholder:normal-case placeholder:text-slate-400 focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                            >

                            @error('room_code')
                                <p class="mt-2 text-sm text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- Loại phòng --}}
                        <div class="md:col-span-2">
                            <label
                                for="room_type"
                                class="mb-2 block text-sm font-semibold text-slate-700"
                            >
                                Loại phòng
                                <span class="text-red-500">*</span>
                            </label>

                            <input
                                id="room_type"
                                name="room_type"
                                type="text"
                                value="{{ old('room_type', $room->room_type) }}"
                                required
                                placeholder="Ví dụ: Phòng đơn, Phòng đôi, Phòng gia đình..."
                                class="w-full rounded-xl border border-slate-300 px-4 py-3 text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                            >

                            @error('room_type')
                                <p class="mt-2 text-sm text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                    </div>

                </section>

                <hr class="border-slate-200">

                {{-- Giá và thông số --}}
                <section>

                    <div class="mb-6">
                        <h2 class="text-lg font-bold text-slate-900">
                            Giá và thông số phòng
                        </h2>

                        <p class="mt-1 text-sm text-slate-500">
                            Thiết lập giá mỗi đêm, sức chứa và diện tích phòng.
                        </p>
                    </div>

                    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">

                        {{-- Giá --}}
                        <div class="sm:col-span-2">
                            <label
                                for="price_per_night"
                                class="mb-2 block text-sm font-semibold text-slate-700"
                            >
                                Giá mỗi đêm
                                <span class="text-red-500">*</span>
                            </label>

                            <div class="relative">
                                <input
                                    id="price_per_night"
                                    name="price_per_night"
                                    type="number"
                                    value="{{ old('price_per_night', $room->price_per_night) }}"
                                    min="0"
                                    required
                                    placeholder="500000"
                                    class="w-full rounded-xl border border-slate-300 px-4 py-3 pr-16 text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                                >

                                <span class="absolute inset-y-0 right-4 flex items-center text-sm font-semibold text-slate-400">
                                    VNĐ
                                </span>
                            </div>

                            @error('price_per_night')
                                <p class="mt-2 text-sm text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- Sức chứa --}}
                        <div>
                            <label
                                for="capacity"
                                class="mb-2 block text-sm font-semibold text-slate-700"
                            >
                                Sức chứa
                                <span class="text-red-500">*</span>
                            </label>

                            <input
                                id="capacity"
                                name="capacity"
                                type="number"
                                value="{{ old('capacity', $room->capacity) }}"
                                min="1"
                                required
                                class="w-full rounded-xl border border-slate-300 px-4 py-3 text-slate-900 outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                            >

                            @error('capacity')
                                <p class="mt-2 text-sm text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- Số giường --}}
                        <div>
                            <label
                                for="number_of_beds"
                                class="mb-2 block text-sm font-semibold text-slate-700"
                            >
                                Số giường
                                <span class="text-red-500">*</span>
                            </label>

                            <input
                                id="number_of_beds"
                                name="number_of_beds"
                                type="number"
                                value="{{ old('number_of_beds', $room->number_of_beds) }}"
                                min="1"
                                required
                                class="w-full rounded-xl border border-slate-300 px-4 py-3 text-slate-900 outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                            >

                            @error('number_of_beds')
                                <p class="mt-2 text-sm text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- Diện tích --}}
                        <div class="sm:col-span-2 lg:col-span-4">
                            <label
                                for="area"
                                class="mb-2 block text-sm font-semibold text-slate-700"
                            >
                                Diện tích
                            </label>

                            <div class="relative max-w-md">
                                <input
                                    id="area"
                                    name="area"
                                    type="number"
                                    value="{{ old('area', $room->area) }}"
                                    min="0"
                                    step="0.01"
                                    placeholder="25"
                                    class="w-full rounded-xl border border-slate-300 px-4 py-3 pr-14 text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                                >

                                <span class="absolute inset-y-0 right-4 flex items-center text-sm font-semibold text-slate-400">
                                    m²
                                </span>
                            </div>

                            @error('area')
                                <p class="mt-2 text-sm text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                    </div>

                </section>

                <hr class="border-slate-200">

                {{-- Ảnh --}}
                <section>

                    <div class="mb-6">
                        <h2 class="text-lg font-bold text-slate-900">
                            Hình ảnh phòng
                        </h2>

                        <p class="mt-1 text-sm text-slate-500">
                            Chọn một ảnh đại diện rõ ràng cho phòng.
                        </p>
                    </div>

                    <div class="grid items-start gap-6 md:grid-cols-2">

                        {{-- Chọn ảnh --}}
                        <div>
                            <div class="mb-2 flex min-h-6 items-center">
                                <p class="text-sm font-semibold text-slate-700">
                                    Chọn ảnh
                                </p>
                            </div>

                            <label
                                for="image"
                                class="flex h-64 cursor-pointer flex-col items-center justify-center rounded-2xl border-2 border-dashed border-slate-300 bg-slate-50 px-6 py-8 text-center transition hover:border-blue-400 hover:bg-blue-50"
                            >
                                <div class="flex h-14 w-14 items-center justify-center rounded-full bg-blue-100 text-2xl">
                                    📷
                                </div>

                                <p class="mt-4 text-sm font-semibold text-slate-700">
                                    Nhấn để chọn ảnh đại diện
                                </p>

                                <p
                                    id="image-name"
                                    class="mt-2 max-w-full truncate text-xs text-slate-400"
                                >
                                    {{ $room->image ? basename($room->image) : 'JPG, JPEG, PNG hoặc WEBP. Tối đa 2MB.' }}
                                </p>
                            </label>

                            <input
                                id="image"
                                name="image"
                                type="file"
                                accept=".jpg,.jpeg,.png,.webp"
                                class="hidden"
                            >

                            <input
                                type="hidden"
                                name="remove_image"
                                id="remove_image"
                                value="0"
                            >

                            @error('image')
                                <p class="mt-2 text-sm font-medium text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- Xem trước ảnh --}}
                        <div id="image-preview-wrapper" class="{{ $room->image ? '' : 'hidden' }}">

                            <div class="mb-2 flex min-h-6 items-center justify-between gap-4">
                                <p class="text-sm font-semibold text-slate-700">
                                    Xem trước ảnh
                                </p>

                                <button
                                    id="remove-image"
                                    type="button"
                                    class="cursor-pointer text-sm font-semibold text-red-600 transition hover:text-red-700"
                                >
                                    Xóa ảnh
                                </button>
                            </div>

                            <img
                                id="image-preview"
                                src="{{ $room->image ? asset('storage/' . $room->image) : '' }}"
                                alt="{{ $room->name }}"
                                class="h-64 w-full rounded-2xl border border-slate-200 object-cover"
                            >
                        </div>

                    </div>

                </section>

                <hr class="border-slate-200">

                {{-- Mô tả --}}
                <section>

                    <div class="mb-6">
                        <h2 class="text-lg font-bold text-slate-900">
                            Mô tả phòng
                        </h2>
                    </div>

                    <div>
                        <div class="mb-2 flex items-center justify-between">
                            <label
                                for="description"
                                class="block text-sm font-semibold text-slate-700"
                            >
                                Nội dung mô tả
                            </label>

                            <span
                                id="description-counter"
                                class="text-xs font-medium text-slate-400"
                            >
                                0/3000
                            </span>
                        </div>

                        <textarea
                            id="description"
                            name="description"
                            rows="6"
                            maxlength="3000"
                            placeholder="Mô tả không gian, nội thất và đặc điểm của phòng..."
                            class="w-full resize-y rounded-xl border border-slate-300 px-4 py-3 text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                        >{{ old('description', $room->description) }}</textarea>

                        @error('description')
                            <p class="mt-2 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                </section>

                <hr class="border-slate-200">

                {{-- Trạng thái --}}
                <section>

                    <div class="mb-6">
                        <h2 class="text-lg font-bold text-slate-900">
                            Trạng thái phòng
                        </h2>

                        <p class="mt-1 text-sm text-slate-500">
                            Chọn trạng thái hoạt động hiện tại của phòng.
                        </p>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-3">

                        <label class="cursor-pointer">

                            <input
                                type="radio"
                                name="status"
                                value="available"
                                class="peer sr-only"
                                @checked(old('status', $room->status) === 'available')
                            >

                            <div class="flex items-start gap-4 rounded-2xl border-2 border-slate-200 p-5 transition peer-checked:border-emerald-500 peer-checked:bg-emerald-50 peer-checked:[&_.radio-circle]:border-emerald-500 peer-checked:[&_.radio-dot]:opacity-100">

                                <div class="radio-circle mt-1 flex h-5 w-5 shrink-0 items-center justify-center rounded-full border-2 border-slate-300 transition">
                                    <div class="radio-dot h-2.5 w-2.5 rounded-full bg-emerald-500 opacity-0 transition"></div>
                                </div>

                                <div>
                                    <p class="font-bold text-slate-900">
                                        Còn trống
                                    </p>

                                    <p class="mt-1 text-sm text-slate-500">
                                        Phòng có thể nhận đặt phòng.
                                    </p>
                                </div>

                            </div>

                        </label>

                        <label class="cursor-pointer">

                            <input
                                type="radio"
                                name="status"
                                value="maintenance"
                                class="peer sr-only"
                                @checked(old('status', $room->status) === 'maintenance')
                            >

                            <div class="flex items-start gap-4 rounded-2xl border-2 border-slate-200 p-5 transition peer-checked:border-amber-500 peer-checked:bg-amber-50 peer-checked:[&_.radio-circle]:border-amber-500 peer-checked:[&_.radio-dot]:opacity-100">

                                <div class="radio-circle mt-1 flex h-5 w-5 shrink-0 items-center justify-center rounded-full border-2 border-slate-300 transition">
                                    <div class="radio-dot h-2.5 w-2.5 rounded-full bg-amber-500 opacity-0 transition"></div>
                                </div>

                                <div>
                                    <p class="font-bold text-slate-900">
                                        Bảo trì
                                    </p>

                                    <p class="mt-1 text-sm text-slate-500">
                                        Phòng đang được sửa chữa.
                                    </p>
                                </div>

                            </div>

                        </label>

                        <label class="cursor-pointer">

                            <input
                                type="radio"
                                name="status"
                                value="inactive"
                                class="peer sr-only"
                                @checked(old('status', $room->status) === 'inactive')
                            >

                            <div class="flex items-start gap-4 rounded-2xl border-2 border-slate-200 p-5 transition peer-checked:border-red-500 peer-checked:bg-red-50 peer-checked:[&_.radio-circle]:border-red-500 peer-checked:[&_.radio-dot]:opacity-100">

                                <div class="radio-circle mt-1 flex h-5 w-5 shrink-0 items-center justify-center rounded-full border-2 border-slate-300 transition">
                                    <div class="radio-dot h-2.5 w-2.5 rounded-full bg-red-500 opacity-0 transition"></div>
                                </div>

                                <div>
                                    <p class="font-bold text-slate-900">
                                        Ngừng hoạt động
                                    </p>

                                    <p class="mt-1 text-sm text-slate-500">
                                        Tạm thời không sử dụng phòng.
                                    </p>
                                </div>

                            </div>

                        </label>

                    </div>

                    @error('status')
                        <p class="mt-2 text-sm text-red-600">
                            {{ $message }}
                        </p>
                    @enderror

                </section>

            </div>

            {{-- Footer --}}
            <div class="flex flex-col-reverse gap-3 border-t border-slate-200 bg-slate-50 px-6 py-5 sm:flex-row sm:justify-end sm:px-8">

                <a
                    href="{{ route('admin.rooms.index') }}"
                    class="inline-flex items-center justify-center rounded-xl border border-slate-300 bg-white px-6 py-3 text-sm font-semibold text-slate-600 transition hover:bg-slate-100"
                >
                    Hủy
                </a>

                <button
                    type="submit"
                    class="cursor-pointer rounded-xl bg-blue-600 px-6 py-3 text-sm font-semibold text-white transition hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-200"
                >
                    Lưu thay đổi
                </button>

            </div>

        </form>

    </main>

    <script>
        const imageInput = document.getElementById('image');
        const imagePreview = document.getElementById('image-preview');
        const imagePreviewWrapper = document.getElementById('image-preview-wrapper');
        const imageName = document.getElementById('image-name');
        const removeImageButton = document.getElementById('remove-image');
        const removeImageInput = document.getElementById('remove_image');

        const originalImage = @json(
            $room->image ? asset('storage/' . $room->image) : ''
        );

        let previewUrl = null;

        imageInput.addEventListener('change', function () {
            const file = this.files[0];

            if (!file) {
                return;
            }

            removeImageInput.value = '0';

            if (previewUrl) {
                URL.revokeObjectURL(previewUrl);
            }

            previewUrl = URL.createObjectURL(file);

            imagePreview.src = previewUrl;
            imagePreviewWrapper.classList.remove('hidden');
            imageName.textContent = file.name;
        });

        removeImageButton.addEventListener('click', function () {
            imageInput.value = '';
            removeImageInput.value = '1';

            if (previewUrl) {
                URL.revokeObjectURL(previewUrl);
                previewUrl = null;
            }

            imagePreview.src = '';
            imagePreviewWrapper.classList.add('hidden');

            imageName.textContent =
                'JPG, JPEG, PNG hoặc WEBP. Tối đa 2MB.';
        });
    </script>

</body>

</html>