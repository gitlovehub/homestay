@extends('layouts.admin')

@section('title', 'Thêm mới Homestay | HomeStayGo')

@section('page-title', 'Thêm mới Homestay')

@section('content')
    <div class="mx-auto max-w-4xl">

        <p class="mb-4 text-sm font-semibold md:text-lg text-slate-500">
            Nhập đầy đủ thông tin để thêm Homestay mới vào hệ thống.
        </p>

        <form action="{{ route('admin.homestays.store') }}" method="POST" enctype="multipart/form-data"
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
                            Tên, danh mục và chủ sở hữu của Homestay.
                        </p>
                    </div>

                    <div class="grid gap-6 md:grid-cols-2">

                        {{-- Tên Homestay --}}
                        <div class="md:col-span-2">

                            <label for="name" class="mb-2 block text-sm font-semibold text-slate-700">
                                Tên Homestay
                                <span class="text-red-500">*</span>
                            </label>

                            <input id="name" name="name" type="text" value="{{ old('name') }}" autofocus
                                placeholder="Ví dụ: Ocean View Homestay"
                                class="w-full rounded-xl border px-4 py-3 text-sm text-slate-900 outline-none transition placeholder:text-slate-400
                                {{ $errors->has('name')
                                    ? 'border-red-400 focus:border-red-500 focus:ring-4 focus:ring-red-100'
                                    : 'border-slate-300 focus:border-blue-500 focus:ring-4 focus:ring-blue-100' }}">

                            @error('name')
                                <p class="mt-2 text-sm font-medium text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>

                        {{-- Slug --}}
                        <div class="md:col-span-2">

                            <label for="slug" class="mb-2 block text-sm font-semibold text-slate-700">
                                Slug
                            </label>

                            <input id="slug" name="slug" type="text" value="{{ old('slug') }}"
                                placeholder="Để trống để hệ thống tự tạo từ tên Homestay"
                                class="w-full rounded-xl border px-4 py-3 text-sm text-slate-900 outline-none transition placeholder:text-slate-400
                                {{ $errors->has('slug')
                                    ? 'border-red-400 focus:border-red-500 focus:ring-4 focus:ring-red-100'
                                    : 'border-slate-300 focus:border-blue-500 focus:ring-4 focus:ring-blue-100' }}">

                            <p class="mt-2 text-xs text-slate-500">
                                Ví dụ: “Ocean View Homestay” sẽ có slug là “ocean-view-homestay”.
                            </p>

                            @error('slug')
                                <p class="mt-2 text-sm font-medium text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>

                        {{-- Danh mục --}}
                        <div x-data="{
                            open: false,
                            selected: @js((string) old('category_id', '')),
                        
                            categories: @js(
    $categories
        ->map(
            fn($category) => [
                'id' => (string) $category->id,
                'name' => $category->name,
            ],
        )
        ->values(),
),
                        
                            get selectedName() {
                                const category = this.categories.find(
                                    item => String(item.id) === String(this.selected)
                                );
                        
                                return category ? category.name : '';
                            },
                        
                            selectCategory(category) {
                                this.selected = String(category.id);
                                this.open = false;
                            }
                        }" @click.outside="open = false" @keydown.escape.window="open = false"
                            :class="open ? 'z-50' : 'z-20'" class="relative overflow-visible">
                            <label for="category_id" class="mb-2 block text-sm font-semibold text-slate-700">
                                Danh mục
                                <span class="text-red-500">*</span>
                            </label>

                            {{-- Giá trị gửi về Controller --}}
                            <input id="category_id" type="hidden" name="category_id" :value="selected">

                            {{-- Nút mở dropdown --}}
                            <button type="button" @click="open = !open"
                                :class="{
                                    'border-red-400 focus:border-red-500 ring-4 ring-red-100': {{ $errors->has('category_id') ? 'true' : 'false' }},
                                
                                    'border-blue-500 ring-4 ring-blue-100': open && !
                                        {{ $errors->has('category_id') ? 'true' : 'false' }},
                                
                                    'border-slate-300 hover:border-slate-400':
                                        !open && !{{ $errors->has('category_id') ? 'true' : 'false' }}
                                }"
                                class="flex w-full items-center justify-between rounded-xl border bg-white px-4 py-3 text-left text-sm text-slate-900 outline-none transition">
                                <span x-show="selectedName" x-text="selectedName" class="truncate font-medium"></span>

                                <span x-show="!selectedName" class="text-slate-400">
                                    -- Chọn danh mục --
                                </span>

                                <svg class="ml-3 h-5 w-5 shrink-0 text-slate-500 transition-transform duration-200"
                                    :class="{ 'rotate-180': open }" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="m19 9-7 7-7-7" />
                                </svg>
                            </button>

                            {{-- Danh sách danh mục --}}
                            <div x-cloak x-show="open" x-transition:enter="transition ease-out duration-150"
                                x-transition:enter-start="opacity-0 -translate-y-2"
                                x-transition:enter-end="opacity-100 translate-y-0"
                                x-transition:leave="transition ease-in duration-100"
                                x-transition:leave-start="opacity-100 translate-y-0"
                                x-transition:leave-end="opacity-0 -translate-y-2"
                                class="absolute left-0 right-0 top-full z-50 mt-2 max-h-64 overflow-y-auto rounded-xl border border-slate-200 bg-white p-2 shadow-xl">
                                <button type="button" @click="selected = ''; open = false"
                                    class="flex w-full items-center rounded-lg px-3 py-2.5 text-left text-sm text-slate-500 transition hover:bg-slate-100">
                                    -- Chọn danh mục --
                                </button>

                                <template x-for="category in categories" :key="category.id">
                                    <button type="button" @click="selectCategory(category)"
                                        :class="selected === String(category.id) ?
                                            'bg-blue-50 text-blue-700' :
                                            'text-slate-700 hover:bg-slate-100'"
                                        class="flex w-full items-center justify-between rounded-lg px-3 py-2.5 text-left text-sm font-medium transition">
                                        <span x-text="category.name" class="truncate"></span>

                                        <svg x-show="selected === String(category.id)"
                                            class="ml-3 h-5 w-5 shrink-0 text-blue-600" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="m5 13 4 4L19 7" />
                                        </svg>
                                    </button>
                                </template>

                                <div x-show="categories.length === 0" class="px-3 py-5 text-center text-sm text-slate-500">
                                    Chưa có danh mục nào.
                                </div>
                            </div>

                            @error('category_id')
                                <p class="mt-2 text-sm font-medium text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>


                        {{-- Chủ sở hữu --}}
                        <div x-data="{
                            open: false,
                            selected: @js((string) old('owner_id', '')),
                        
                            owners: @js(
    $owners
        ->map(
            fn($owner) => [
                'id' => (string) $owner->id,
                'name' => $owner->name,
                'email' => $owner->email,
            ],
        )
        ->values(),
),
                        
                            get selectedOwner() {
                                return this.owners.find(
                                    item => String(item.id) === String(this.selected)
                                );
                            },
                        
                            get selectedName() {
                                return this.selectedOwner ?
                                    `${this.selectedOwner.name} — ${this.selectedOwner.email}` :
                                    '';
                            },
                        
                            selectOwner(owner) {
                                this.selected = String(owner.id);
                                this.open = false;
                            }
                        }" @click.outside="open = false" @keydown.escape.window="open = false"
                            :class="open ? 'z-50' : 'z-20'" class="relative overflow-visible">
                            <label for="owner_id" class="mb-2 block text-sm font-semibold text-slate-700">
                                Chủ sở hữu
                                <span class="text-red-500">*</span>
                            </label>

                            {{-- Giá trị gửi về Controller --}}
                            <input id="owner_id" type="hidden" name="owner_id" :value="selected">

                            {{-- Nút mở dropdown --}}
                            <button type="button" @click="open = !open"
                                :class="{
                                    'border-red-400 focus:border-red-500 ring-4 ring-red-100': {{ $errors->has('owner_id') ? 'true' : 'false' }},
                                
                                    'border-blue-500 ring-4 ring-blue-100': open && !
                                        {{ $errors->has('owner_id') ? 'true' : 'false' }},
                                
                                    'border-slate-300 hover:border-slate-400':
                                        !open && !{{ $errors->has('owner_id') ? 'true' : 'false' }}
                                }"
                                class="flex w-full items-center justify-between rounded-xl border bg-white px-4 py-3 text-left text-sm text-slate-900 outline-none transition">
                                <span x-show="selectedName" x-text="selectedName" class="truncate font-medium"></span>

                                <span x-show="!selectedName" class="text-slate-400">
                                    -- Chọn chủ sở hữu --
                                </span>

                                <svg class="ml-3 h-5 w-5 shrink-0 text-slate-500 transition-transform duration-200"
                                    :class="{ 'rotate-180': open }" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="m19 9-7 7-7-7" />
                                </svg>
                            </button>

                            {{-- Danh sách chủ sở hữu --}}
                            <div x-cloak x-show="open" x-transition:enter="transition ease-out duration-150"
                                x-transition:enter-start="opacity-0 -translate-y-2"
                                x-transition:enter-end="opacity-100 translate-y-0"
                                x-transition:leave="transition ease-in duration-100"
                                x-transition:leave-start="opacity-100 translate-y-0"
                                x-transition:leave-end="opacity-0 -translate-y-2"
                                class="absolute left-0 right-0 top-full z-50 mt-2 max-h-64 overflow-y-auto rounded-xl border border-slate-200 bg-white p-2 shadow-xl">
                                <button type="button" @click="selected = ''; open = false"
                                    class="flex w-full items-center rounded-lg px-3 py-2.5 text-left text-sm text-slate-500 transition hover:bg-slate-100">
                                    -- Chọn chủ sở hữu --
                                </button>

                                <template x-for="owner in owners" :key="owner.id">
                                    <button type="button" @click="selectOwner(owner)"
                                        :class="selected === String(owner.id) ?
                                            'bg-blue-50 text-blue-700' :
                                            'text-slate-700 hover:bg-slate-100'"
                                        class="flex w-full items-center justify-between gap-3 rounded-lg px-3 py-2.5 text-left transition">
                                        <div class="min-w-0">
                                            <p x-text="owner.name" class="truncate text-sm font-semibold"></p>

                                            <p x-text="owner.email" class="truncate text-xs text-slate-500"></p>
                                        </div>

                                        <svg x-show="selected === String(owner.id)" class="h-5 w-5 shrink-0 text-blue-600"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="m5 13 4 4L19 7" />
                                        </svg>
                                    </button>
                                </template>

                                <div x-show="owners.length === 0" class="px-3 py-5 text-center text-sm text-slate-500">
                                    Chưa có chủ sở hữu nào.
                                </div>
                            </div>

                            @error('owner_id')
                                <p class="mt-2 text-sm font-medium text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                    </div>

                </section>

                <div class="border-t border-slate-200"></div>

                {{-- Giá và thời gian --}}
                <section>

                    <div class="mb-6">

                        <h2 class="text-lg font-bold text-slate-900">
                            Giá và thời gian
                        </h2>

                        <p class="mt-1 text-sm text-slate-500">
                            Thiết lập giá cơ bản và khung giờ nhận, trả phòng.
                        </p>

                    </div>

                    <div class="grid gap-6 md:grid-cols-3">

                        {{-- Giá cơ bản --}}
                        <div>

                            <label for="base_price" class="mb-2 block text-sm font-semibold text-slate-700">
                                Giá cơ bản
                                <span class="text-red-500">*</span>
                            </label>

                            <div class="relative">

                                <input id="base_price" name="base_price" type="number" min="0" step="100000"
                                    value="{{ old('base_price') }}" placeholder="Ví dụ: 2000000"
                                    class="w-full rounded-xl border px-4 py-3 pr-16 text-sm text-slate-900 outline-none transition placeholder:text-slate-400
                                    {{ $errors->has('base_price')
                                        ? 'border-red-400 focus:border-red-500 focus:ring-4 focus:ring-red-100'
                                        : 'border-slate-300 focus:border-blue-500 focus:ring-4 focus:ring-blue-100' }}">

                                <span
                                    class="pointer-events-none absolute inset-y-0 right-4 flex items-center text-sm font-medium text-slate-400">
                                    VNĐ
                                </span>

                            </div>

                            @error('base_price')
                                <p class="mt-2 text-sm font-medium text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>

                        {{-- Giờ nhận phòng --}}
                        <div>

                            <label for="check_in_time" class="mb-2 block text-sm font-semibold text-slate-700">
                                Giờ nhận phòng
                            </label>

                            <input id="check_in_time" name="check_in_time" type="time"
                                value="{{ old('check_in_time', '14:00') }}"
                                class="w-full rounded-xl border px-4 py-3 text-sm text-slate-900 outline-none transition
                                {{ $errors->has('check_in_time')
                                    ? 'border-red-400 focus:border-red-500 focus:ring-4 focus:ring-red-100'
                                    : 'border-slate-300 focus:border-blue-500 focus:ring-4 focus:ring-blue-100' }}">

                            @error('check_in_time')
                                <p class="mt-2 text-sm font-medium text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>

                        {{-- Giờ trả phòng --}}
                        <div>

                            <label for="check_out_time" class="mb-2 block text-sm font-semibold text-slate-700">
                                Giờ trả phòng
                            </label>

                            <input id="check_out_time" name="check_out_time" type="time"
                                value="{{ old('check_out_time', '12:00') }}"
                                class="w-full rounded-xl border px-4 py-3 text-sm text-slate-900 outline-none transition
                                {{ $errors->has('check_out_time')
                                    ? 'border-red-400 focus:border-red-500 focus:ring-4 focus:ring-red-100'
                                    : 'border-slate-300 focus:border-blue-500 focus:ring-4 focus:ring-blue-100' }}">

                            @error('check_out_time')
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

                            <label for="address" class="mb-2 block text-sm font-semibold text-slate-700">
                                Địa chỉ
                                <span class="text-red-500">*</span>
                            </label>

                            <input id="address" name="address" type="text" value="{{ old('address') }}"
                                placeholder="Ví dụ: 123 đường Trần Phú"
                                class="w-full rounded-xl border px-4 py-3 text-sm text-slate-900 outline-none transition placeholder:text-slate-400
                                {{ $errors->has('address')
                                    ? 'border-red-400 focus:border-red-500 focus:ring-4 focus:ring-red-100'
                                    : 'border-slate-300 focus:border-blue-500 focus:ring-4 focus:ring-blue-100' }}">

                            @error('address')
                                <p class="mt-2 text-sm font-medium text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>

                        {{-- Tỉnh/Thành phố --}}
                        <div x-data="{
                            open: false,
                            loading: false,
                            loadError: '',
                            locations: [],
                        
                            selected: @js((string) old('city', '')),
                        
                            async loadLocations(forceReload = false) {
                                this.loading = true;
                                this.loadError = '';
                        
                                const cacheKey = 'homestay_vietnam_provinces_v2';
                                const cacheTimeKey = 'homestay_vietnam_provinces_v2_time';
                                const cacheDuration = 24 * 60 * 60 * 1000;
                        
                                try {
                                    const cachedData = localStorage.getItem(cacheKey);
                                    const cachedTime = Number(
                                        localStorage.getItem(cacheTimeKey) || 0
                                    );
                        
                                    const cacheIsValid =
                                        cachedData &&
                                        cachedTime &&
                                        Date.now() - cachedTime < cacheDuration;
                        
                                    if (!forceReload && cacheIsValid) {
                                        this.locations = JSON.parse(cachedData);
                                        return;
                                    }
                        
                                    const response = await fetch(
                                        'https://provinces.open-api.vn/api/v2/p/', {
                                            method: 'GET',
                                            headers: {
                                                Accept: 'application/json'
                                            }
                                        }
                                    );
                        
                                    if (!response.ok) {
                                        throw new Error(
                                            `Không thể tải dữ liệu. Mã lỗi: ${response.status}`
                                        );
                                    }
                        
                                    const data = await response.json();
                        
                                    this.locations = data
                                        .map(location => ({
                                            code: String(location.code),
                                            name: location.name
                                        }))
                                        .sort((firstLocation, secondLocation) =>
                                            firstLocation.name.localeCompare(
                                                secondLocation.name,
                                                'vi'
                                            )
                                        );
                        
                                    localStorage.setItem(
                                        cacheKey,
                                        JSON.stringify(this.locations)
                                    );
                        
                                    localStorage.setItem(
                                        cacheTimeKey,
                                        String(Date.now())
                                    );
                                } catch (error) {
                                    console.error('Lỗi tải tỉnh/thành phố:', error);
                        
                                    this.loadError =
                                        'Không thể tải danh sách tỉnh/thành phố. Vui lòng thử lại.';
                                } finally {
                                    this.loading = false;
                                }
                            },
                        
                            selectLocation(location) {
                                this.selected = location.name;
                                this.open = false;
                            },
                        
                            clearLocation() {
                                this.selected = '';
                                this.open = false;
                            }
                        }" x-init="loadLocations()" @click.outside="open = false"
                            @keydown.escape.window="open = false" :class="open ? 'z-50' : 'z-20'"
                            class="relative overflow-visible">
                            <label for="city_selector" class="mb-2 block text-sm font-semibold text-slate-700">
                                Tỉnh/Thành phố
                                <span class="text-red-500">*</span>
                            </label>

                            {{-- Giá trị gửi về Controller --}}
                            <input id="city" type="hidden" name="city" :value="selected">

                            {{-- Nút mở dropdown --}}
                            <button id="city_selector" type="button" @click="open = !open" :aria-expanded="open"
                                :class="{
                                    'border-red-400 ring-4 ring-red-100': {{ $errors->has('city') ? 'true' : 'false' }},
                                
                                    'border-blue-500 ring-4 ring-blue-100': open && !
                                        {{ $errors->has('city') ? 'true' : 'false' }},
                                
                                    'border-slate-300 hover:border-slate-400':
                                        !open && !{{ $errors->has('city') ? 'true' : 'false' }}
                                }"
                                class="flex w-full items-center justify-between rounded-xl border bg-white px-4 py-3 text-left text-sm text-slate-900 outline-none transition">
                                <span x-show="selected" x-text="selected" class="truncate font-medium"></span>

                                <span x-show="!selected && !loading" class="text-slate-400">
                                    -- Chọn tỉnh/thành phố --
                                </span>

                                <span x-show="loading" class="flex items-center gap-2 text-slate-500">
                                    <svg class="h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none">
                                        <circle class="opacity-25" cx="12" cy="12" r="10"
                                            stroke="currentColor" stroke-width="4"></circle>

                                        <path class="opacity-75" fill="currentColor"
                                            d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                                    </svg>

                                    Đang tải dữ liệu...
                                </span>

                                <svg class="ml-3 h-5 w-5 shrink-0 text-slate-500 transition-transform duration-200"
                                    :class="{ 'rotate-180': open }" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="m19 9-7 7-7-7" />
                                </svg>
                            </button>

                            {{-- Danh sách tỉnh/thành phố --}}
                            <div x-cloak x-show="open" x-transition:enter="transition ease-out duration-150"
                                x-transition:enter-start="opacity-0 -translate-y-2"
                                x-transition:enter-end="opacity-100 translate-y-0"
                                x-transition:leave="transition ease-in duration-100"
                                x-transition:leave-start="opacity-100 translate-y-0"
                                x-transition:leave-end="opacity-0 -translate-y-2"
                                class="absolute left-0 right-0 top-full z-50 mt-2 max-h-72 overflow-y-auto rounded-xl border border-slate-200 bg-white p-2 shadow-xl">
                                {{-- Đang tải --}}
                                <div x-show="loading"
                                    class="flex items-center justify-center gap-2 px-4 py-6 text-sm text-slate-500">
                                    <svg class="h-5 w-5 animate-spin" viewBox="0 0 24 24" fill="none">
                                        <circle class="opacity-25" cx="12" cy="12" r="10"
                                            stroke="currentColor" stroke-width="4"></circle>

                                        <path class="opacity-75" fill="currentColor"
                                            d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                                    </svg>

                                    Đang tải danh sách tỉnh/thành phố...
                                </div>

                                {{-- Lỗi API --}}
                                <div x-show="!loading && loadError" class="px-3 py-4 text-center">
                                    <p x-text="loadError" class="text-sm font-medium text-red-600"></p>

                                    <button type="button" @click="loadLocations(true)"
                                        class="mt-3 inline-flex items-center justify-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-blue-700">
                                        Tải lại dữ liệu
                                    </button>
                                </div>

                                {{-- Bỏ lựa chọn --}}
                                <button x-show="!loading && !loadError" type="button" @click="clearLocation()"
                                    class="flex w-full items-center rounded-lg px-3 py-2.5 text-left text-sm text-slate-500 transition hover:bg-slate-100">
                                    -- Chọn tỉnh/thành phố --
                                </button>

                                {{-- Danh sách từ API --}}
                                <template x-for="location in locations" :key="location.code">
                                    <button type="button" @click="selectLocation(location)"
                                        :class="selected === location.name ?
                                            'bg-blue-50 text-blue-700' :
                                            'text-slate-700 hover:bg-slate-100'"
                                        class="flex w-full items-center justify-between gap-3 rounded-lg px-3 py-2.5 text-left text-sm font-medium transition">
                                        <span x-text="location.name" class="truncate"></span>

                                        <svg x-show="selected === location.name" class="h-5 w-5 shrink-0 text-blue-600"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="m5 13 4 4L19 7" />
                                        </svg>
                                    </button>
                                </template>

                                {{-- Không có dữ liệu --}}
                                <div x-show="
                !loading &&
                !loadError &&
                locations.length === 0
            "
                                    class="px-3 py-5 text-center text-sm text-slate-500">
                                    Không có dữ liệu tỉnh/thành phố.
                                </div>
                            </div>

                            @error('city')
                                <p class="mt-2 text-sm font-medium text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- Số điện thoại --}}
                        <div>

                            <label for="phone" class="mb-2 block text-sm font-semibold text-slate-700">
                                Số điện thoại
                            </label>

                            <input id="phone" name="phone" type="tel" inputmode="numeric" maxlength="11"
                                value="{{ old('phone') }}" placeholder="Ví dụ: 0987654321"
                                class="w-full rounded-xl border px-4 py-3 text-sm text-slate-900 outline-none transition placeholder:text-slate-400
                                {{ $errors->has('phone')
                                    ? 'border-red-400 focus:border-red-500 focus:ring-4 focus:ring-red-100'
                                    : 'border-slate-300 focus:border-blue-500 focus:ring-4 focus:ring-blue-100' }}">

                            @error('phone')
                                <p class="mt-2 text-sm font-medium text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>

                        {{-- Vĩ độ --}}
                        <div>

                            <label for="latitude" class="mb-2 block text-sm font-semibold text-slate-700">
                                Vĩ độ
                            </label>

                            <input id="latitude" name="latitude" type="number" step="0.0000001"
                                value="{{ old('latitude') }}" placeholder="Ví dụ: 11.940419"
                                class="w-full rounded-xl border px-4 py-3 text-sm text-slate-900 outline-none transition placeholder:text-slate-400
                                {{ $errors->has('latitude')
                                    ? 'border-red-400 focus:border-red-500 focus:ring-4 focus:ring-red-100'
                                    : 'border-slate-300 focus:border-blue-500 focus:ring-4 focus:ring-blue-100' }}">

                            @error('latitude')
                                <p class="mt-2 text-sm font-medium text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>

                        {{-- Kinh độ --}}
                        <div>

                            <label for="longitude" class="mb-2 block text-sm font-semibold text-slate-700">
                                Kinh độ
                            </label>

                            <input id="longitude" name="longitude" type="number" step="0.0000001"
                                value="{{ old('longitude') }}" placeholder="Ví dụ: 108.458313"
                                class="w-full rounded-xl border px-4 py-3 text-sm text-slate-900 outline-none transition placeholder:text-slate-400
                                {{ $errors->has('longitude')
                                    ? 'border-red-400 focus:border-red-500 focus:ring-4 focus:ring-red-100'
                                    : 'border-slate-300 focus:border-blue-500 focus:ring-4 focus:ring-blue-100' }}">

                            @error('longitude')
                                <p class="mt-2 text-sm font-medium text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>

                    </div>

                </section>

                <div class="border-t border-slate-200"></div>

                {{-- Tiện ích --}}
                <section>

                    <div class="mb-5">

                        <h2 class="text-lg font-bold text-slate-900">
                            Tiện ích
                        </h2>

                        <p class="mt-1 text-sm text-slate-500">
                            Chọn những tiện ích đang có tại Homestay.
                        </p>

                    </div>

                    @if ($amenities->isNotEmpty())

                        <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3">

                            @foreach ($amenities as $amenity)
                                <label for="amenity-{{ $amenity->id }}" class="group cursor-pointer">

                                    <input id="amenity-{{ $amenity->id }}" name="amenities[]" type="checkbox"
                                        value="{{ $amenity->id }}" class="peer sr-only" @checked(in_array($amenity->id, old('amenities', [])))>

                                    <div
                                        class="flex h-full items-start gap-3 rounded-2xl border border-slate-300 bg-white p-4 transition
                                        group-hover:border-blue-300
                                        group-hover:bg-blue-50/50
                                        peer-checked:border-blue-500
                                        peer-checked:bg-blue-50
                                        peer-focus-visible:ring-4
                                        peer-focus-visible:ring-blue-100">

                                        <div
                                            class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-slate-100 text-xl">
                                            {{ $amenity->icon ?: '✓' }}
                                        </div>

                                        <div class="min-w-0">

                                            <p class="font-semibold text-slate-900">
                                                {{ $amenity->name }}
                                            </p>

                                            @if ($amenity->description)
                                                <p class="mt-1 line-clamp-2 text-sm leading-5 text-slate-500">
                                                    {{ $amenity->description }}
                                                </p>
                                            @endif

                                        </div>

                                    </div>

                                </label>
                            @endforeach

                        </div>
                    @else
                        <div class="rounded-2xl border border-dashed border-slate-300 bg-slate-50 p-6 text-center">

                            <p class="text-sm font-medium text-slate-600">
                                Chưa có tiện ích đang hoạt động.
                            </p>

                        </div>

                    @endif

                    @error('amenities')
                        <p class="mt-3 text-sm font-medium text-red-600">
                            {{ $message }}
                        </p>
                    @enderror

                    @error('amenities.*')
                        <p class="mt-3 text-sm font-medium text-red-600">
                            {{ $message }}
                        </p>
                    @enderror

                </section>

                <div class="border-t border-slate-200"></div>

                {{-- Hình ảnh --}}
                <section>

                    <div class="mb-6">

                        <h2 class="text-lg font-bold text-slate-900">
                            Ảnh đại diện
                        </h2>

                        <p class="mt-1 text-sm text-slate-500">
                            Chọn ảnh chính được sử dụng để đại diện cho Homestay.
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

                            <label for="thumbnail"
                                class="flex h-64 cursor-pointer flex-col items-center justify-center rounded-2xl border-2 border-dashed border-slate-300 bg-slate-50 px-6 py-8 text-center transition hover:border-blue-400 hover:bg-blue-50">
                                <div class="flex h-14 w-14 items-center justify-center rounded-full bg-blue-100 text-2xl">
                                    📷
                                </div>

                                <p class="mt-4 text-sm font-semibold text-slate-700">
                                    Nhấn để chọn ảnh đại diện
                                </p>

                                <p id="thumbnail-name" class="mt-2 max-w-full truncate text-xs text-slate-400">
                                    JPG, JPEG, PNG hoặc WEBP. Tối đa 3MB.
                                </p>
                            </label>

                            <input id="thumbnail" name="thumbnail" type="file" accept=".jpg,.jpeg,.png,.webp"
                                class="hidden">

                            @error('thumbnail')
                                <p class="mt-2 text-sm font-medium text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- Xem trước ảnh --}}
                        <div id="thumbnail-preview-wrapper" class="hidden">

                            <div class="mb-2 flex min-h-6 items-center justify-between gap-4">
                                <p class="text-sm font-semibold text-slate-700">
                                    Xem trước ảnh
                                </p>

                                <button id="remove-thumbnail" type="button"
                                    class="cursor-pointer text-sm font-semibold text-red-600 transition hover:text-red-700">
                                    Xóa ảnh
                                </button>
                            </div>

                            <img id="thumbnail-preview" src="" alt="Ảnh đại diện Homestay"
                                class="h-64 w-full rounded-2xl border border-slate-200 object-cover">
                        </div>

                    </div>

                </section>

                <div class="border-t border-slate-200"></div>

                {{-- Mô tả và chính sách --}}
                <section>

                    <div class="mb-6">

                        <h2 class="text-lg font-bold text-slate-900">
                            Nội dung Homestay
                        </h2>

                        <p class="mt-1 text-sm text-slate-500">
                            Thêm phần giới thiệu và các chính sách lưu trú.
                        </p>

                    </div>

                    <div class="grid gap-6">

                        {{-- Mô tả --}}
                        <div>

                            <div class="mb-2 flex items-center justify-between gap-4">

                                <label for="description" class="block text-sm font-semibold text-slate-700">
                                    Mô tả
                                </label>

                                <span id="description-counter" class="text-xs font-medium text-slate-400">
                                    0/3000 ký tự
                                </span>

                            </div>

                            <textarea id="description" name="description" rows="7" maxlength="3000"
                                placeholder="Nhập nội dung giới thiệu về Homestay..."
                                class="w-full resize-y rounded-xl border px-4 py-3 text-sm text-slate-900 outline-none transition placeholder:text-slate-400
                                {{ $errors->has('description')
                                    ? 'border-red-400 focus:border-red-500 focus:ring-4 focus:ring-red-100'
                                    : 'border-slate-300 focus:border-blue-500 focus:ring-4 focus:ring-blue-100' }}">{{ old('description') }}</textarea>

                            @error('description')
                                <p class="mt-2 text-sm font-medium text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>

                        {{-- Chính sách --}}
                        <div>

                            <div class="mb-2 flex items-center justify-between gap-4">

                                <label for="policy" class="block text-sm font-semibold text-slate-700">
                                    Chính sách
                                </label>

                                <span id="policy-counter" class="text-xs font-medium text-slate-400">
                                    0/3000 ký tự
                                </span>

                            </div>

                            <textarea id="policy" name="policy" rows="6" maxlength="3000"
                                placeholder="Ví dụ: Không hút thuốc, không mang vật nuôi, giữ yên lặng sau 22 giờ..."
                                class="w-full resize-y rounded-xl border px-4 py-3 text-sm text-slate-900 outline-none transition placeholder:text-slate-400
                                {{ $errors->has('policy')
                                    ? 'border-red-400 focus:border-red-500 focus:ring-4 focus:ring-red-100'
                                    : 'border-slate-300 focus:border-blue-500 focus:ring-4 focus:ring-blue-100' }}">{{ old('policy') }}</textarea>

                            @error('policy')
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
                            Chọn trạng thái hoạt động của Homestay.
                        </p>

                    </div>

                    <div class="grid gap-4 sm:grid-cols-2">

                        {{-- Hoạt động --}}
                        <label for="status-active"
                            class="cursor-pointer rounded-2xl border border-slate-300 bg-white p-4 transition
                            hover:border-emerald-400 hover:bg-emerald-50
                            has-[:checked]:border-emerald-500
                            has-[:checked]:bg-emerald-50">

                            <div class="flex items-start gap-3">

                                <input id="status-active" name="status" type="radio" value="1"
                                    @checked(old('status', '1') == '1')
                                    class="mt-1 h-4 w-4 border-slate-300 text-emerald-600 focus:ring-emerald-500">

                                <div>

                                    <p class="font-semibold text-slate-900">
                                        Hoạt động
                                    </p>

                                    <p class="mt-1 text-sm text-slate-500">
                                        Homestay được phép hiển thị và sử dụng.
                                    </p>

                                </div>

                            </div>

                        </label>

                        {{-- Tạm khóa --}}
                        <label for="status-inactive"
                            class="cursor-pointer rounded-2xl border border-slate-300 bg-white p-4 transition
                            hover:border-red-400 hover:bg-red-50
                            has-[:checked]:border-red-500
                            has-[:checked]:bg-red-50">

                            <div class="flex items-start gap-3">

                                <input id="status-inactive" name="status" type="radio" value="0"
                                    @checked(old('status') == '0')
                                    class="mt-1 h-4 w-4 border-slate-300 text-red-600 focus:ring-red-500">

                                <div>

                                    <p class="font-semibold text-slate-900">
                                        Tạm khóa
                                    </p>

                                    <p class="mt-1 text-sm text-slate-500">
                                        Homestay tạm thời không được hiển thị.
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
            <div
                class="flex flex-col-reverse gap-3 border-t border-slate-200 bg-slate-50 px-6 py-5 sm:flex-row sm:justify-end sm:px-8">

                <a href="{{ route('admin.homestays.index') }}"
                    class="inline-flex items-center justify-center rounded-xl border border-slate-300 bg-white px-6 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-100">
                    Hủy
                </a>

                <button type="submit"
                    class="inline-flex cursor-pointer items-center justify-center rounded-xl bg-blue-600 px-6 py-3 text-sm font-semibold text-white transition hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-200">
                    Thêm mới Homestay
                </button>

            </div>

        </form>

    </div>

    <script>
        const thumbnailInput = document.getElementById('thumbnail');
        const thumbnailName = document.getElementById('thumbnail-name');
        const previewWrapper = document.getElementById('thumbnail-preview-wrapper');
        const previewImage = document.getElementById('thumbnail-preview');
        const removeThumbnailButton = document.getElementById('remove-thumbnail');

        let previewUrl = null;

        thumbnailInput.addEventListener('change', function() {
            const file = this.files[0];

            if (!file) {
                hideThumbnailPreview();
                return;
            }

            if (previewUrl) {
                URL.revokeObjectURL(previewUrl);
            }

            previewUrl = URL.createObjectURL(file);
            previewImage.src = previewUrl;
            thumbnailName.textContent = file.name;
            previewWrapper.classList.remove('hidden');
        });

        removeThumbnailButton.addEventListener('click', function() {
            thumbnailInput.value = '';
            hideThumbnailPreview();
        });

        function hideThumbnailPreview() {
            previewWrapper.classList.add('hidden');
            thumbnailName.textContent = 'JPG, JPEG, PNG hoặc WEBP. Tối đa 3MB.';

            if (previewUrl) {
                URL.revokeObjectURL(previewUrl);
                previewUrl = null;
            }

            previewImage.src = '';
        }

        const description = document.getElementById('description');
        const descriptionCounter = document.getElementById('description-counter');

        const policy = document.getElementById('policy');
        const policyCounter = document.getElementById('policy-counter');

        const updateCounter = (input, counter, maximum) => {
            counter.textContent = `${input.value.length}/${maximum} ký tự`;
        };

        description.addEventListener('input', function() {
            updateCounter(description, descriptionCounter, 3000);
        });

        policy.addEventListener('input', function() {
            updateCounter(policy, policyCounter, 3000);
        });

        updateCounter(description, descriptionCounter, 3000);
        updateCounter(policy, policyCounter, 3000);
    </script>
@endsection
