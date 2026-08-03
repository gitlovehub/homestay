@extends('layouts.admin')

@section('title', 'Thêm phòng mới | HomeStayGo')

@section('page-title', 'Thêm phòng mới')

@section('content')
    <div class="mx-auto max-w-4xl">

        <p class="mb-4 text-sm font-semibold md:text-lg text-slate-500">
            Nhập đầy đủ thông tin để tạo phòng mới cho Homestay.
        </p>

        <form action="{{ route('admin.rooms.store') }}" method="POST" enctype="multipart/form-data"
            class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">

            @csrf

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

                    <div class="grid gap-6 overflow-visible md:grid-cols-2">

                        {{-- Homestay --}}
                        <div x-data="{
                            open: false,
                            selected: @js((string) old('homestay_id', '')),
                            homestays: @js(
    $homestays
        ->map(
            fn($homestay) => [
                'id' => (string) $homestay->id,
                'name' => $homestay->name,
            ],
        )
        ->values(),
),
                        
                            get selectedName() {
                                const homestay = this.homestays.find(
                                    item => String(item.id) === String(this.selected)
                                );
                        
                                return homestay ? homestay.name : '';
                            },
                        
                            selectHomestay(homestay) {
                                this.selected = String(homestay.id);
                                this.open = false;
                            }
                        }" @click.outside="open = false" @keydown.escape.window="open = false"
                            class="relative z-30 overflow-visible md:col-span-2">
                            <label for="homestay_id" class="mb-2 block text-sm font-semibold text-slate-700">
                                Homestay
                                <span class="text-red-500">*</span>
                            </label>

                            {{-- Giá trị gửi về controller --}}
                            <input id="homestay_id" type="hidden" name="homestay_id" :value="selected">

                            {{-- Nút mở dropdown --}}
                            <button type="button" @click="open = !open"
                                :class="open
                                    ?
                                    'border-blue-500 ring-4 ring-blue-100' :
                                    'border-slate-300 hover:border-slate-400'"
                                class="flex w-full items-center justify-between rounded-xl border bg-white px-4 py-3 text-left text-slate-900 outline-none transition">
                                <span x-show="selectedName" x-text="selectedName" class="truncate font-medium"></span>

                                <span x-show="!selectedName" class="text-slate-400">
                                    -- Chọn Homestay --
                                </span>

                                <svg class="ml-3 h-5 w-5 shrink-0 text-slate-500 transition-transform duration-200"
                                    :class="{ 'rotate-180': open }" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="m19 9-7 7-7-7" />
                                </svg>
                            </button>

                            {{-- Danh sách luôn mở xuống dưới --}}
                            <div x-cloak x-show="open" x-transition:enter="transition ease-out duration-150"
                                x-transition:enter-start="opacity-0 -translate-y-2"
                                x-transition:enter-end="opacity-100 translate-y-0"
                                x-transition:leave="transition ease-in duration-100"
                                x-transition:leave-start="opacity-100 translate-y-0"
                                x-transition:leave-end="opacity-0 -translate-y-2"
                                class="absolute left-0 right-0 top-full z-50 mt-2 max-h-64 overflow-y-auto rounded-xl border border-slate-200 bg-white p-2 shadow-xl">
                                <button type="button" @click="selected = ''; open = false"
                                    class="flex w-full items-center rounded-lg px-3 py-2.5 text-left text-sm text-slate-500 transition hover:bg-slate-100">
                                    -- Chọn Homestay --
                                </button>

                                <template x-for="homestay in homestays" :key="homestay.id">
                                    <button type="button" @click="selectHomestay(homestay)"
                                        :class="selected === String(homestay.id) ?
                                            'bg-blue-50 text-blue-700' :
                                            'text-slate-700 hover:bg-slate-100'"
                                        class="flex w-full items-center justify-between rounded-lg px-3 py-2.5 text-left text-sm font-medium transition">
                                        <span x-text="homestay.name" class="truncate"></span>

                                        <svg x-show="selected === String(homestay.id)"
                                            class="ml-3 h-5 w-5 shrink-0 text-blue-600" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="m5 13 4 4L19 7" />
                                        </svg>
                                    </button>
                                </template>

                                <div x-show="homestays.length === 0" class="px-3 py-5 text-center text-sm text-slate-500">
                                    Chưa có Homestay nào.
                                </div>
                            </div>

                            @error('homestay_id')
                                <p class="mt-2 text-sm font-medium text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- Tên phòng --}}
                        <div>
                            <label for="name" class="mb-2 block text-sm font-semibold text-slate-700">
                                Tên phòng
                                <span class="text-red-500">*</span>
                            </label>

                            <input id="name" name="name" type="text" value="{{ old('name') }}"
                                placeholder="Ví dụ: Phòng Deluxe 01"
                                class="h-12 w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:ring-4 focus:ring-blue-100">

                            @error('name')
                                <p class="mt-2 text-sm font-medium text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- Mã phòng --}}
                        <div>
                            <label for="room_code" class="mb-2 block text-sm font-semibold text-slate-700">
                                Mã phòng
                                <span class="text-red-500">*</span>
                            </label>

                            <input id="room_code" name="room_code" type="text" value="{{ old('room_code') }}"
                                placeholder="Ví dụ: DLX-001"
                                class="h-12 w-full rounded-xl border border-slate-300 bg-white px-4 py-3 uppercase text-slate-900 outline-none transition placeholder:normal-case placeholder:text-slate-400 focus:border-blue-500 focus:ring-4 focus:ring-blue-100">

                            @error('room_code')
                                <p class="mt-2 text-sm font-medium text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- Loại phòng --}}
                        <div class="md:col-span-2">
                            <label for="room_type" class="mb-2 block text-sm font-semibold text-slate-700">
                                Loại phòng
                                <span class="text-red-500">*</span>
                            </label>

                            <input id="room_type" name="room_type" type="text" value="{{ old('room_type') }}"
                                placeholder="Ví dụ: Phòng đơn, Phòng đôi, Phòng gia đình..."
                                class="h-12 w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:ring-4 focus:ring-blue-100">

                            @error('room_type')
                                <p class="mt-2 text-sm font-medium text-red-600">
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
                            <label for="price_per_night" class="mb-2 block text-sm font-semibold text-slate-700">
                                Giá mỗi đêm
                                <span class="text-red-500">*</span>
                            </label>

                            <div class="relative">
                                <input id="price_per_night" name="price_per_night" type="number" step="100000"
                                    value="{{ old('price_per_night') }}" min="0" placeholder="500000"
                                    class="h-12 w-full rounded-xl border border-slate-300 px-4 py-3 pr-16 text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:ring-4 focus:ring-blue-100">

                                <span
                                    class="absolute inset-y-0 right-4 flex items-center text-sm font-semibold text-slate-400">
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
                            <label for="capacity" class="mb-2 block text-sm font-semibold text-slate-700">
                                Sức chứa
                                <span class="text-red-500">*</span>
                            </label>

                            <input id="capacity" name="capacity" type="number" value="{{ old('capacity', 2) }}"
                                min="1"
                                class="h-12 w-full rounded-xl border border-slate-300 px-4 py-3 text-slate-900 outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100">

                            @error('capacity')
                                <p class="mt-2 text-sm text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- Số giường --}}
                        <div>
                            <label for="number_of_beds" class="mb-2 block text-sm font-semibold text-slate-700">
                                Số giường
                                <span class="text-red-500">*</span>
                            </label>

                            <input id="number_of_beds" name="number_of_beds" type="number"
                                value="{{ old('number_of_beds', 1) }}" min="1"
                                class="h-12 w-full rounded-xl border border-slate-300 px-4 py-3 text-slate-900 outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100">

                            @error('number_of_beds')
                                <p class="mt-2 text-sm text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- Diện tích --}}
                        <div class="sm:col-span-2 lg:col-span-4">
                            <label for="area" class="mb-2 block text-sm font-semibold text-slate-700">
                                Diện tích
                            </label>

                            <div class="relative max-w-md">
                                <input id="area" name="area" type="number" value="{{ old('area') }}"
                                    min="0" step="1" placeholder="25"
                                    class="h-12 w-full rounded-xl border border-slate-300 px-4 py-3 pr-14 text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:ring-4 focus:ring-blue-100">

                                <span
                                    class="absolute inset-y-0 right-4 flex items-center text-sm font-semibold text-slate-400">
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

                            <label for="image"
                                class="flex h-64 cursor-pointer flex-col items-center justify-center rounded-2xl border-2 border-dashed border-slate-300 bg-slate-50 px-6 py-8 text-center transition hover:border-blue-400 hover:bg-blue-50">
                                <div class="flex h-14 w-14 items-center justify-center rounded-full bg-blue-100 text-2xl">
                                    📷
                                </div>

                                <p class="mt-4 text-sm font-semibold text-slate-700">
                                    Nhấn để chọn ảnh đại diện
                                </p>

                                <p id="image-name" class="mt-2 max-w-full truncate text-xs text-slate-400">
                                    JPG, JPEG, PNG hoặc WEBP. Tối đa 2MB.
                                </p>
                            </label>

                            <input id="image" name="image" type="file" accept=".jpg,.jpeg,.png,.webp"
                                class="hidden">

                            @error('image')
                                <p class="mt-2 text-sm font-medium text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- Xem trước ảnh --}}
                        <div id="image-preview-wrapper" class="hidden">

                            <div class="mb-2 flex min-h-6 items-center justify-between gap-4">
                                <p class="text-sm font-semibold text-slate-700">
                                    Xem trước ảnh
                                </p>

                                <button id="remove-image" type="button"
                                    class="cursor-pointer text-sm font-semibold text-red-600 transition hover:text-red-700">
                                    Xóa ảnh
                                </button>
                            </div>

                            <img id="image-preview" src="" alt="Ảnh đại diện Homestay"
                                class="h-64 w-full rounded-2xl border border-slate-200 object-cover">
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
                            <label for="description" class="block text-sm font-semibold text-slate-700">
                                Nội dung mô tả
                            </label>

                            <span id="description-counter" class="text-xs font-medium text-slate-400">
                                0/3000
                            </span>
                        </div>

                        <textarea id="description" name="description" rows="6" maxlength="3000"
                            placeholder="Mô tả không gian, nội thất và đặc điểm của phòng..."
                            class="w-full resize-y rounded-xl border border-slate-300 px-4 py-3 text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:ring-4 focus:ring-blue-100">{{ old('description') }}</textarea>

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

                            <input type="radio" name="status" value="available" class="peer sr-only"
                                @checked(old('status', 'available') === 'available')>

                            <div
                                class="flex items-start gap-4 rounded-2xl border-2 border-slate-200 p-5 transition peer-checked:border-emerald-500 peer-checked:bg-emerald-50 peer-checked:[&_.radio-circle]:border-emerald-500 peer-checked:[&_.radio-dot]:opacity-100">

                                <div
                                    class="radio-circle mt-1 flex h-5 w-5 shrink-0 items-center justify-center rounded-full border-2 border-slate-300 transition">
                                    <div class="radio-dot h-2.5 w-2.5 rounded-full bg-emerald-500 opacity-0 transition">
                                    </div>
                                </div>

                                <p class="font-bold text-slate-900">
                                    Còn trống
                                </p>

                            </div>

                        </label>

                        <label class="cursor-pointer">

                            <input type="radio" name="status" value="maintenance" class="peer sr-only"
                                @checked(old('status', 'maintenance') === 'maintenance')>

                            <div
                                class="flex items-start gap-4 rounded-2xl border-2 border-slate-200 p-5 transition peer-checked:border-amber-500 peer-checked:bg-amber-50 peer-checked:[&_.radio-circle]:border-amber-500 peer-checked:[&_.radio-dot]:opacity-100">

                                <div
                                    class="radio-circle mt-1 flex h-5 w-5 shrink-0 items-center justify-center rounded-full border-2 border-slate-300 transition">
                                    <div class="radio-dot h-2.5 w-2.5 rounded-full bg-amber-500 opacity-0 transition">
                                    </div>
                                </div>

                                <p class="font-bold text-slate-900">
                                    Bảo trì
                                </p>

                            </div>

                        </label>

                        <label class="cursor-pointer">

                            <input type="radio" name="status" value="inactive" class="peer sr-only"
                                @checked(old('status', 'inactive') === 'inactive')>

                            <div
                                class="flex items-start gap-4 rounded-2xl border-2 border-slate-200 p-5 transition peer-checked:border-red-500 peer-checked:bg-red-50 peer-checked:[&_.radio-circle]:border-red-500 peer-checked:[&_.radio-dot]:opacity-100">

                                <div
                                    class="radio-circle mt-1 flex h-5 w-5 shrink-0 items-center justify-center rounded-full border-2 border-slate-300 transition">
                                    <div class="radio-dot h-2.5 w-2.5 rounded-full bg-red-500 opacity-0 transition"></div>
                                </div>

                                <p class="font-bold text-slate-900">
                                    Ngừng hoạt động
                                </p>

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
            <div
                class="flex flex-col-reverse gap-3 border-t border-slate-200 bg-slate-50 px-6 py-5 sm:flex-row sm:justify-end sm:px-8">

                <a href="{{ route('admin.rooms.index') }}"
                    class="inline-flex items-center justify-center rounded-xl border border-slate-300 bg-white px-6 py-3 text-sm font-semibold text-slate-600 transition hover:bg-slate-100">
                    Hủy
                </a>

                <button type="submit"
                    class="cursor-pointer rounded-xl bg-blue-600 px-6 py-3 text-sm font-semibold text-white transition hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-200">
                    Thêm phòng mới
                </button>

            </div>

        </form>

    </div>

    <script>
        const imageInput = document.getElementById('image');
        const imagePreview = document.getElementById('image-preview');
        const imagePreviewWrapper = document.getElementById('image-preview-wrapper');
        const imageName = document.getElementById('image-name');
        const removeImageButton = document.getElementById('remove-image');

        let previewUrl = null;

        imageInput.addEventListener('change', function() {
            const file = this.files[0];

            if (!file) {
                return;
            }

            if (previewUrl) {
                URL.revokeObjectURL(previewUrl);
            }

            previewUrl = URL.createObjectURL(file);

            imagePreview.src = previewUrl;
            imagePreviewWrapper.classList.remove('hidden');
            imageName.textContent = file.name;
        });

        removeImageButton.addEventListener('click', function() {
            imageInput.value = '';
            imagePreview.src = '';
            imagePreviewWrapper.classList.add('hidden');
            imageName.textContent = 'JPG, JPEG, PNG hoặc WEBP. Tối đa 2MB.';

            if (previewUrl) {
                URL.revokeObjectURL(previewUrl);
                previewUrl = null;
            }
        });
    </script>
@endsection
