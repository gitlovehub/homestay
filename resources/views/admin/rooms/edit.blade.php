@extends('layouts.admin')

@section('title', 'Chỉnh sửa thông tin phòng | HomeStayGo')

@section('page-title', 'Chỉnh sửa thông tin phòng')

@section('content')
    <div class="mx-auto max-w-4xl">

        <div class="mb-8">
            <h2 class="text-lg font-semibold text-slate-900 dark:text-slate-100 md:text-2xl">
                Chỉnh sửa thông tin phòng
                <span class="font-bold text-blue-700 dark:text-blue-400">{{ $room->name }}</span>.
            </h2>

            <a href="{{ route('admin.rooms.index') }}"
                class="mt-1 inline-flex items-center gap-1 text-xs font-medium text-blue-600 transition hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 sm:text-sm">
                ←
                Trở về danh sách phòng
            </a>
        </div>

        <form action="{{ route('admin.rooms.update', $room) }}" method="POST" enctype="multipart/form-data"
            class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-900">
            @csrf
            @method('PUT')

            <div class="space-y-8 p-6 sm:p-8">

                {{-- Thông tin cơ bản --}}
                <section>
                    <div class="mb-6">
                        <h2 class="text-lg font-bold text-slate-900 dark:text-slate-100">Thông tin cơ bản</h2>
                        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Thông tin nhận diện và phân loại phòng.</p>
                    </div>

                    <div class="grid gap-6 md:grid-cols-2">

                        {{-- Homestay --}}
                        <div x-data="{
                            open: false,
                            selected: @js((string) old('homestay_id', $room->homestay_id)),
                            homestays: @js($homestays->map(fn($homestay) => [
                                'id' => (string) $homestay->id,
                                'name' => $homestay->name,
                            ])->values()),
                            get selectedHomestay() {
                                return this.homestays.find(homestay => String(homestay.id) === String(this.selected));
                            },
                            get selectedName() {
                                return this.selectedHomestay ? this.selectedHomestay.name : '';
                            },
                            selectHomestay(homestay) {
                                this.selected = String(homestay.id);
                                this.open = false;
                            }
                        }"
                            @click.outside="open = false"
                            @keydown.escape.window="open = false"
                            :class="open ? 'z-50' : 'z-20'"
                            class="relative overflow-visible md:col-span-2">

                            <label for="homestay_selector" class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-300">
                                Homestay
                                <span class="text-red-500">*</span>
                            </label>

                            <input type="hidden" name="homestay_id" :value="selected">

                            <button id="homestay_selector"
                                    type="button"
                                    @click="open = !open"
                                    :aria-expanded="open"
                                    :class="{
                                        'border-red-400 ring-4 ring-red-100 dark:border-red-500 dark:ring-red-900/30': {{ $errors->has('homestay_id') ? 'true' : 'false' }},
                                        'border-blue-500 ring-4 ring-blue-100 dark:border-blue-400 dark:ring-blue-900/40': open && !{{ $errors->has('homestay_id') ? 'true' : 'false' }},
                                        'border-slate-300 hover:border-slate-400 dark:border-slate-600 dark:hover:border-slate-500': !open && !{{ $errors->has('homestay_id') ? 'true' : 'false' }}
                                    }"
                                    class="flex h-11 w-full items-center justify-between rounded-xl border bg-white px-4 text-left text-sm text-slate-900 outline-none transition dark:bg-slate-800 dark:text-slate-100">
                                <span x-show="selectedName" x-text="selectedName" class="truncate font-medium"></span>
                                <span x-show="!selectedName" class="text-slate-400 dark:text-slate-500">-- Chọn Homestay --</span>
                                <svg class="ml-3 h-5 w-5 shrink-0 text-slate-500 transition-transform duration-200 dark:text-slate-400"
                                    :class="{ 'rotate-180': open }"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 9-7 7-7-7" />
                                </svg>
                            </button>

                            <div x-cloak
                                x-show="open"
                                x-transition:enter="transition ease-out duration-150"
                                x-transition:enter-start="opacity-0 -translate-y-2"
                                x-transition:enter-end="opacity-100 translate-y-0"
                                x-transition:leave="transition ease-in duration-100"
                                x-transition:leave-start="opacity-100 translate-y-0"
                                x-transition:leave-end="opacity-0 -translate-y-2"
                                class="absolute left-0 right-0 top-full z-50 mt-2 max-h-64 overflow-y-auto rounded-xl border border-slate-200 bg-white p-2 shadow-xl dark:border-slate-700 dark:bg-slate-800">

                                <button type="button"
                                        @click="selected = ''; open = false"
                                        class="flex h-11 w-full items-center rounded-lg px-3 text-left text-sm text-slate-500 transition hover:bg-slate-100 dark:text-slate-400 dark:hover:bg-slate-700">
                                    -- Chọn Homestay --
                                </button>

                                <template x-for="homestay in homestays" :key="homestay.id">
                                    <button type="button"
                                            @click="selectHomestay(homestay)"
                                            :class="selected === String(homestay.id) ? 'bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300' : 'text-slate-700 hover:bg-slate-100 dark:text-slate-200 dark:hover:bg-slate-700'"
                                            class="flex h-11 w-full items-center justify-between gap-3 rounded-lg px-3 text-left text-sm font-medium transition">
                                        <span x-text="homestay.name" class="truncate"></span>
                                        <svg x-show="selected === String(homestay.id)"
                                            class="h-5 w-5 shrink-0 text-blue-600"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m5 13 4 4L19 7" />
                                        </svg>
                                    </button>
                                </template>

                                <div x-show="homestays.length === 0" class="px-3 py-5 text-center text-sm text-slate-500 dark:text-slate-400">
                                    Chưa có Homestay nào.
                                </div>
                            </div>

                            @error('homestay_id')
                                <p class="mt-2 text-sm font-medium text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Tên phòng --}}
                        <div>
                            <label for="name" class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-300">
                                Tên phòng
                                <span class="text-red-500">*</span>
                            </label>

                            <input id="name"
                                name="name"
                                type="text"
                                value="{{ old('name', $room->name) }}"
                                placeholder="Ví dụ: Phòng Deluxe 01"
                                class="h-11 w-full rounded-xl border px-4 bg-white text-slate-900 outline-none transition placeholder:text-slate-400 dark:bg-slate-800 dark:text-slate-100 dark:placeholder:text-slate-500
                                    {{ $errors->has('name')
                                        ? 'border-red-400 focus:border-red-500 focus:ring-4 focus:ring-red-100 dark:border-red-500 dark:focus:border-red-400 dark:focus:ring-red-900/30'
                                        : 'border-slate-300 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 dark:border-slate-600 dark:focus:border-blue-400 dark:focus:ring-blue-900/40' }}">

                            @error('name')
                                <p class="mt-2 text-sm font-medium text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Mã phòng --}}
                        <div>
                            <label for="room_code" class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-300">
                                Mã phòng
                                <span class="text-red-500">*</span>
                            </label>

                            <input id="room_code"
                                name="room_code"
                                type="text"
                                value="{{ old('room_code', $room->room_code) }}"
                                placeholder="Ví dụ: DLX-001"
                                class="h-11 w-full rounded-xl border px-4 uppercase text-slate-900 outline-none transition placeholder:normal-case placeholder:text-slate-400
                                    {{ $errors->has('room_code')
                                        ? 'border-red-400 focus:border-red-500 focus:ring-4 focus:ring-red-100 dark:border-red-500 dark:focus:border-red-400 dark:focus:ring-red-900/30'
                                        : 'border-slate-300 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 dark:border-slate-600 dark:focus:border-blue-400 dark:focus:ring-blue-900/40' }}">

                            @error('room_code')
                                <p class="mt-2 text-sm font-medium text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Loại phòng --}}
                        <div class="md:col-span-2">
                            <label for="room_type" class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-300">
                                Loại phòng
                                <span class="text-red-500">*</span>
                            </label>

                            <input id="room_type"
                                name="room_type"
                                type="text"
                                value="{{ old('room_type', $room->room_type) }}"
                                placeholder="Ví dụ: Phòng đơn, Phòng đôi, Phòng gia đình..."
                                class="h-11 w-full rounded-xl border px-4 bg-white text-slate-900 outline-none transition placeholder:text-slate-400 dark:bg-slate-800 dark:text-slate-100 dark:placeholder:text-slate-500
                                    {{ $errors->has('room_type')
                                        ? 'border-red-400 focus:border-red-500 focus:ring-4 focus:ring-red-100 dark:border-red-500 dark:focus:border-red-400 dark:focus:ring-red-900/30'
                                        : 'border-slate-300 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 dark:border-slate-600 dark:focus:border-blue-400 dark:focus:ring-blue-900/40' }}">

                            @error('room_type')
                                <p class="mt-2 text-sm font-medium text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>
                </section>

                <hr class="border-slate-200 dark:border-slate-700">

                {{-- Giá và thông số --}}
                <section>
                    <div class="mb-6">
                        <h2 class="text-lg font-bold text-slate-900 dark:text-slate-100">Giá và thông số phòng</h2>
                        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Thiết lập giá mỗi đêm, sức chứa và diện tích phòng.</p>
                    </div>

                    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">

                        {{-- Giá --}}
                        <div class="sm:col-span-2">
                            <label for="price_per_night" class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-300">
                                Giá mỗi đêm
                                <span class="text-red-500">*</span>
                            </label>

                            <div class="relative">
                                <input id="price_per_night"
                                    name="price_per_night"
                                    type="number"
                                    step="100000"
                                    value="{{ old('price_per_night', $room->price_per_night) }}"
                                    min="0"
                                    placeholder="500000"
                                    class="h-11 w-full rounded-xl border px-4 pr-16 bg-white text-slate-900 outline-none transition placeholder:text-slate-400 dark:bg-slate-800 dark:text-slate-100 dark:placeholder:text-slate-500
                                        {{ $errors->has('price_per_night')
                                            ? 'border-red-400 focus:border-red-500 focus:ring-4 focus:ring-red-100 dark:border-red-500 dark:focus:border-red-400 dark:focus:ring-red-900/30'
                                            : 'border-slate-300 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 dark:border-slate-600 dark:focus:border-blue-400 dark:focus:ring-blue-900/40' }}">

                                <span class="absolute inset-y-0 right-4 flex items-center text-sm font-semibold text-slate-400 dark:text-slate-500">
                                    VNĐ
                                </span>
                            </div>

                            @error('price_per_night')
                                <p class="mt-2 text-sm font-medium text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Sức chứa --}}
                        <div>
                            <label for="capacity" class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-300">
                                Sức chứa
                                <span class="text-red-500">*</span>
                            </label>

                            <input id="capacity"
                                name="capacity"
                                type="number"
                                value="{{ old('capacity', $room->capacity) }}"
                                min="1"
                                class="h-11 w-full rounded-xl border px-4 bg-white text-slate-900 outline-none transition dark:bg-slate-800 dark:text-slate-100
                                    {{ $errors->has('capacity')
                                        ? 'border-red-400 focus:border-red-500 focus:ring-4 focus:ring-red-100 dark:border-red-500 dark:focus:border-red-400 dark:focus:ring-red-900/30'
                                        : 'border-slate-300 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 dark:border-slate-600 dark:focus:border-blue-400 dark:focus:ring-blue-900/40' }}">

                            @error('capacity')
                                <p class="mt-2 text-sm font-medium text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Số giường --}}
                        <div>
                            <label for="number_of_beds" class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-300">
                                Số giường
                                <span class="text-red-500">*</span>
                            </label>

                            <input id="number_of_beds"
                                name="number_of_beds"
                                type="number"
                                value="{{ old('number_of_beds', $room->number_of_beds) }}"
                                min="1"
                                class="h-11 w-full rounded-xl border px-4 bg-white text-slate-900 outline-none transition dark:bg-slate-800 dark:text-slate-100
                                    {{ $errors->has('number_of_beds')
                                        ? 'border-red-400 focus:border-red-500 focus:ring-4 focus:ring-red-100 dark:border-red-500 dark:focus:border-red-400 dark:focus:ring-red-900/30'
                                        : 'border-slate-300 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 dark:border-slate-600 dark:focus:border-blue-400 dark:focus:ring-blue-900/40' }}">

                            @error('number_of_beds')
                                <p class="mt-2 text-sm font-medium text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Diện tích --}}
                        <div class="lg:col-span-2">
                            <label for="area" class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-300">
                                Diện tích
                            </label>

                            <div class="relative">
                                <input id="area"
                                    name="area"
                                    type="number"
                                    value="{{ old('area', $room->area) }}"
                                    min="0"
                                    step="1"
                                    placeholder="25"
                                    class="h-11 w-full rounded-xl border px-4 pr-14 bg-white text-slate-900 outline-none transition placeholder:text-slate-400 dark:bg-slate-800 dark:text-slate-100 dark:placeholder:text-slate-500
                                        {{ $errors->has('area')
                                            ? 'border-red-400 focus:border-red-500 focus:ring-4 focus:ring-red-100 dark:border-red-500 dark:focus:border-red-400 dark:focus:ring-red-900/30'
                                            : 'border-slate-300 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 dark:border-slate-600 dark:focus:border-blue-400 dark:focus:ring-blue-900/40' }}">

                                <span class="absolute inset-y-0 right-4 flex items-center text-sm font-semibold text-slate-400 dark:text-slate-500">
                                    m²
                                </span>
                            </div>

                            @error('area')
                                <p class="mt-2 text-sm font-medium text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>
                </section>

                <hr class="border-slate-200 dark:border-slate-700">

                {{-- Ảnh --}}
                <section>
                    <div class="mb-6">
                        <h2 class="text-lg font-bold text-slate-900 dark:text-slate-100">Hình ảnh phòng</h2>
                        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Chọn một ảnh đại diện rõ ràng cho phòng.</p>
                    </div>

                    <div class="grid items-start gap-6 md:grid-cols-2">
                        <div>
                            <div class="mb-2 flex min-h-6 items-center">
                                <p class="text-sm font-semibold text-slate-700 dark:text-slate-300">Chọn ảnh</p>
                            </div>

                            <label for="image"
                                class="flex h-64 cursor-pointer flex-col items-center justify-center rounded-2xl border-2 border-dashed border-slate-300 bg-slate-50 px-6 py-8 text-center transition hover:border-blue-400 hover:bg-blue-50 dark:border-slate-600 dark:bg-slate-800 dark:hover:border-blue-400 dark:hover:bg-blue-900/20">
                                <div class="flex h-14 w-14 items-center justify-center rounded-full bg-blue-100 text-2xl dark:bg-blue-900/40">
                                    📷
                                </div>
                                <p class="mt-4 text-sm font-semibold text-slate-700 dark:text-slate-200">Nhấn để chọn ảnh đại diện</p>
                                <p id="image-name" class="mt-2 max-w-full truncate text-xs text-slate-400 dark:text-slate-500">
                                    {{ $room->image ? basename($room->image) : 'JPG, JPEG, PNG hoặc WEBP. Tối đa 2MB.' }}
                                </p>
                            </label>

                            <input id="image" name="image" type="file" accept=".jpg,.jpeg,.png,.webp" class="hidden">
                            <input type="hidden" name="remove_image" id="remove_image" value="0">

                            @error('image')
                                <p class="mt-2 text-sm font-medium text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div id="image-preview-wrapper" class="{{ $room->image ? '' : 'hidden' }}">
                            <div class="mb-2 flex min-h-6 items-center justify-between gap-4">
                                <p class="text-sm font-semibold text-slate-700 dark:text-slate-300">Xem trước ảnh</p>
                                <button id="remove-image"
                                        type="button"
                                        class="inline-flex h-11 cursor-pointer items-center text-sm font-semibold text-red-600 transition hover:text-red-700 dark:text-red-400 dark:hover:text-red-300">
                                    Xóa ảnh
                                </button>
                            </div>
                            <img id="image-preview"
                                src="{{ $room->image ? asset('storage/' . $room->image) : '' }}"
                                alt="{{ $room->name }}"
                                class="h-64 w-full rounded-2xl border border-slate-200 object-cover dark:border-slate-700">
                        </div>
                    </div>
                </section>

                <hr class="border-slate-200 dark:border-slate-700">

                {{-- Mô tả --}}
                <section>
                    <div class="mb-6">
                        <h2 class="text-lg font-bold text-slate-900 dark:text-slate-100">Mô tả phòng</h2>
                    </div>

                    <div>
                        <div class="mb-2 flex items-center justify-between">
                            <label for="description" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">
                                Nội dung mô tả
                            </label>
                            <span id="description-counter" class="text-xs font-medium text-slate-400 dark:text-slate-500">
                                0/3000
                            </span>
                        </div>

                        <textarea id="description"
                                name="description"
                                rows="6"
                                maxlength="3000"
                                placeholder="Mô tả không gian, nội thất và đặc điểm của phòng..."
                                class="w-full resize-y rounded-xl border px-4 py-3 bg-white text-slate-900 outline-none transition placeholder:text-slate-400 dark:bg-slate-800 dark:text-slate-100 dark:placeholder:text-slate-500
                                    {{ $errors->has('description')
                                        ? 'border-red-400 focus:border-red-500 focus:ring-4 focus:ring-red-100 dark:border-red-500 dark:focus:border-red-400 dark:focus:ring-red-900/30'
                                        : 'border-slate-300 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 dark:border-slate-600 dark:focus:border-blue-400 dark:focus:ring-blue-900/40' }}">{{ old('description', $room->description) }}</textarea>

                        @error('description')
                            <p class="mt-2 text-sm font-medium text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </section>

                <hr class="border-slate-200 dark:border-slate-700">

                {{-- Trạng thái --}}
                <section>
                    <div class="mb-6">
                        <h2 class="text-lg font-bold text-slate-900 dark:text-slate-100">
                            Trạng thái phòng
                        </h2>

                        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                            Chọn trạng thái hoạt động hiện tại của phòng.
                        </p>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-3">

                        {{-- Còn trống --}}
                        <label class="cursor-pointer">
                            <input type="radio"
                                name="status"
                                value="available"
                                class="peer sr-only"
                                @checked(old('status', $room->status) === 'available')>

                            <div
                                class="flex items-start gap-4 rounded-2xl border-2 border-slate-200 bg-white p-5 transition
                                    hover:border-emerald-300
                                    peer-checked:border-emerald-500
                                    peer-checked:bg-emerald-50
                                    peer-checked:[&_.radio-circle]:border-emerald-500
                                    peer-checked:[&_.radio-dot]:opacity-100
                                    dark:border-slate-700
                                    dark:bg-slate-800
                                    dark:hover:border-emerald-600
                                    dark:peer-checked:border-emerald-500
                                    dark:peer-checked:bg-emerald-950/40">

                                <div
                                    class="radio-circle mt-1 flex h-5 w-5 shrink-0 items-center justify-center rounded-full border-2 border-slate-300 transition dark:border-slate-500">
                                    <div
                                        class="radio-dot h-2.5 w-2.5 rounded-full bg-emerald-500 opacity-0 transition">
                                    </div>
                                </div>

                                <span class="font-bold text-slate-900 dark:text-slate-100">
                                    Còn trống
                                </span>
                            </div>
                        </label>

                        {{-- Bảo trì --}}
                        <label class="cursor-pointer">
                            <input type="radio"
                                name="status"
                                value="maintenance"
                                class="peer sr-only"
                                @checked(old('status', $room->status) === 'maintenance')>

                            <div
                                class="flex items-start gap-4 rounded-2xl border-2 border-slate-200 bg-white p-5 transition
                                    hover:border-amber-300
                                    peer-checked:border-amber-500
                                    peer-checked:bg-amber-50
                                    peer-checked:[&_.radio-circle]:border-amber-500
                                    peer-checked:[&_.radio-dot]:opacity-100
                                    dark:border-slate-700
                                    dark:bg-slate-800
                                    dark:hover:border-amber-600
                                    dark:peer-checked:border-amber-500
                                    dark:peer-checked:bg-amber-950/40">

                                <div
                                    class="radio-circle mt-1 flex h-5 w-5 shrink-0 items-center justify-center rounded-full border-2 border-slate-300 transition dark:border-slate-500">
                                    <div
                                        class="radio-dot h-2.5 w-2.5 rounded-full bg-amber-500 opacity-0 transition">
                                    </div>
                                </div>

                                <span class="font-bold text-slate-900 dark:text-slate-100">
                                    Bảo trì
                                </span>
                            </div>
                        </label>

                        {{-- Ngừng hoạt động --}}
                        <label class="cursor-pointer">
                            <input type="radio"
                                name="status"
                                value="inactive"
                                class="peer sr-only"
                                @checked(old('status', $room->status) === 'inactive')>

                            <div
                                class="flex items-start gap-4 rounded-2xl border-2 border-slate-200 bg-white p-5 transition
                                    hover:border-red-300
                                    peer-checked:border-red-500
                                    peer-checked:bg-red-50
                                    peer-checked:[&_.radio-circle]:border-red-500
                                    peer-checked:[&_.radio-dot]:opacity-100
                                    dark:border-slate-700
                                    dark:bg-slate-800
                                    dark:hover:border-red-600
                                    dark:peer-checked:border-red-500
                                    dark:peer-checked:bg-red-950/40">

                                <div
                                    class="radio-circle mt-1 flex h-5 w-5 shrink-0 items-center justify-center rounded-full border-2 border-slate-300 transition dark:border-slate-500">
                                    <div
                                        class="radio-dot h-2.5 w-2.5 rounded-full bg-red-500 opacity-0 transition">
                                    </div>
                                </div>

                                <span class="font-bold text-slate-900 dark:text-slate-100">
                                    Ngừng hoạt động
                                </span>
                            </div>
                        </label>

                    </div>

                    @error('status')
                        <p class="mt-2 text-sm font-medium text-red-600 dark:text-red-400">
                            {{ $message }}
                        </p>
                    @enderror
                </section>

            </div>

            {{-- Footer --}}
            <div class="flex flex-col-reverse gap-3 border-t border-slate-200 bg-slate-50 px-6 py-5 dark:border-slate-700 dark:bg-slate-800/60 sm:flex-row sm:justify-end sm:px-8">
                <a href="{{ route('admin.rooms.index') }}"
                    class="inline-flex h-11 w-full items-center justify-center rounded-xl border border-slate-300 bg-white px-6 text-sm font-semibold text-slate-600 transition hover:bg-slate-100 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-300 dark:hover:bg-slate-700 sm:w-auto">
                    Hủy
                </a>

                <button type="submit"
                    class="inline-flex h-11 w-full cursor-pointer items-center justify-center rounded-xl bg-blue-600 px-6 text-sm font-semibold text-white transition hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-200 dark:focus:ring-blue-900/40 sm:w-auto">
                    Lưu thay đổi
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
        const removeImageInput = document.getElementById('remove_image');

        const originalImage = @json($room->image ? asset('storage/' . $room->image) : '');

        let previewUrl = null;

        imageInput.addEventListener('change', function() {
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

        removeImageButton.addEventListener('click', function() {
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
@endsection