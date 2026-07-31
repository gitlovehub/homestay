@extends('layouts.app')

@section('title', 'Kết quả tìm kiếm | HomeStayGo')

@section('content')

    @php
        $selectedAmenities = collect(request('amenities', []))->map(fn($id) => (int) $id)->all();

        /*
    |--------------------------------------------------------------------------
    | Chỉ đếm các bộ lọc phụ
    |--------------------------------------------------------------------------
    |
    | Không đếm địa điểm và ngày vì đây là điều kiện tìm kiếm chính.
    |
    */

        $activeFilterCount =
            collect([
                request('search'),
                request('min_price'),
                request('max_price'),
                request('guests'),
                request('room_type'),
                request('rating'),
            ])
                ->filter(fn($value) => filled($value))
                ->count() + count($selectedAmenities);

        /*
    |--------------------------------------------------------------------------
    | URL dùng chung của trang kết quả tìm kiếm
    |--------------------------------------------------------------------------
    */

        $searchUrl = route('homestays.search');

        /*
    |--------------------------------------------------------------------------
    | Khi xóa bộ lọc phụ vẫn giữ thành phố và ngày
    |--------------------------------------------------------------------------
    */

        $resetQuery = array_filter(
            [
                'location' => $selectedLocation,
                'check_in' => $checkIn,
                'check_out' => $checkOut,
            ],
            fn($value) => filled($value),
        );

        $resetUrl = route('homestays.search', $resetQuery);
    @endphp

    <x-frontend-breadcrumb :items="[
        [
            'label' => 'Trang chủ',
            'url' => route('home'),
        ],
        [
            'label' => 'Kết quả tìm kiếm',
        ],
    ]" />

    {{-- Modal sửa ngày --}}
    <div x-data="{
        editSearchOpen: false,
    
        originalLocation: @js(old('location', $selectedLocation)),
        originalCheckIn: @js(old('check_in', $checkIn)),
        originalCheckOut: @js(old('check_out', $checkOut)),
    
        location: @js(old('location', $selectedLocation)),
        checkIn: @js(old('check_in', $checkIn)),
        checkOut: @js(old('check_out', $checkOut)),
    
        openEditSearch() {
            this.location = this.originalLocation;
            this.checkIn = this.originalCheckIn;
            this.checkOut = this.originalCheckOut;
            this.editSearchOpen = true;
        },
    
        closeEditSearch() {
            this.editSearchOpen = false;
        },
    
        openDatePicker(input) {
            if (!input || input.disabled) {
                return;
            }
    
            if (typeof input.showPicker === 'function') {
                input.showPicker();
                return;
            }
    
            input.focus();
            input.click();
        },
    
        formatDate(value) {
            if (!value) {
                return '';
            }
    
            const [year, month, day] = value.split('-');
    
            return `${day}/${month}/${year}`;
        },
    
        nextDay(value) {
            if (!value) {
                return @js(now()->toDateString());
            }
    
            const [year, month, day] = value
                .split('-')
                .map(Number);
    
            const date = new Date(
                year,
                month - 1,
                day
            );
    
            date.setDate(date.getDate() + 1);
    
            const nextYear = date.getFullYear();
    
            const nextMonth = String(
                date.getMonth() + 1
            ).padStart(2, '0');
    
            const nextDate = String(
                date.getDate()
            ).padStart(2, '0');
    
            return `${nextYear}-${nextMonth}-${nextDate}`;
        },
    
        handleCheckInChange() {
            if (
                this.checkOut &&
                this.checkOut < this.nextDay(this.checkIn)
            ) {
                this.checkOut = '';
            }
        }
    }" x-init="$watch('editSearchOpen', (isOpen) => {
        document.body.style.overflow = isOpen ?
            'hidden' :
            '';
    })" @keydown.escape.window="closeEditSearch()">

        {{-- Header kết quả tìm kiếm --}}
        <section class="border-b border-slate-200 bg-linear-to-br from-blue-50 via-white to-indigo-50">
            <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">

                <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">

                    {{-- Nội dung bên trái --}}
                    <div>
                        <p class="font-semibold uppercase tracking-widest text-blue-600">
                            Kết quả tìm kiếm
                        </p>

                        <h1 class="mt-2 text-3xl font-bold tracking-tight text-slate-950 sm:text-4xl">
                            @if ($selectedLocation !== '')
                                Homestay còn phòng tại
                                <span class="text-blue-600">
                                    {{ $selectedLocation }}
                                </span>
                            @else
                                Homestay còn phòng phù hợp
                            @endif
                        </h1>

                        <p class="mt-3 max-w-2xl leading-7 text-slate-500">
                            Danh sách chỉ bao gồm những Homestay còn ít nhất một
                            phòng phù hợp trong khoảng thời gian bạn đã chọn.
                        </p>

                        {{-- Thông tin tìm kiếm --}}
                        <div class="mt-5 flex flex-wrap gap-3">

                            {{-- Thành phố --}}
                            <span
                                class="inline-flex items-center gap-2 rounded-xl border border-blue-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm">
                                <svg class="h-4 w-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 21s7-5.686 7-12A7 7 0 105 9c0 6.314 7 12 7 12z" />

                                    <circle cx="12" cy="9" r="2.5" stroke-width="2" />
                                </svg>

                                {{ $selectedLocation !== '' ? $selectedLocation : 'Tất cả thành phố' }}
                            </span>

                            {{-- Ngày nhận phòng --}}
                            <span
                                class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm">
                                <svg class="h-4 w-4 text-emerald-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3M5 11h14M5 5h14a2 2 0 012 2v12a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z" />
                                </svg>

                                Nhận:
                                {{ \Carbon\Carbon::parse($checkIn)->format('d/m/Y') }}
                            </span>

                            {{-- Ngày trả phòng --}}
                            <span
                                class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm">
                                <svg class="h-4 w-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3M5 11h14M5 5h14a2 2 0 012 2v12a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z" />
                                </svg>

                                Trả:
                                {{ \Carbon\Carbon::parse($checkOut)->format('d/m/Y') }}
                            </span>

                            {{-- Số kết quả --}}
                            <span
                                class="inline-flex items-center rounded-xl bg-emerald-50 text-emerald-700 border border-emerald-200 px-4 py-2 text-sm font-semibold shadow-sm">
                                {{ $homestays->total() }} Homestay phù hợp
                            </span>
                        </div>
                    </div>

                    {{-- Nút mở modal --}}
                    <button type="button" @click="openEditSearch()"
                        class="inline-flex shrink-0 cursor-pointer items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 py-2 text-base font-semibold text-white transition hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-100">
                        <svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                          <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                          <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                        </svg>

                        Sửa ngày
                    </button>
                </div>
            </div>
        </section>

        {{-- Modal chỉnh sửa tìm kiếm --}}
        <div x-show="editSearchOpen" x-cloak style="display: none;"
            class="fixed inset-0 z-100 flex items-center justify-center p-4">
            {{-- Overlay --}}
            <button type="button" x-transition.opacity @click="closeEditSearch()"
                class="absolute inset-0 cursor-default bg-slate-950/50 backdrop-blur-sm" aria-label="Đóng modal"></button>

            {{-- Nội dung modal --}}
            <div x-show="editSearchOpen" x-transition:enter="transition duration-200 ease-out"
                x-transition:enter-start="scale-95 opacity-0" x-transition:enter-end="scale-100 opacity-100"
                x-transition:leave="transition duration-150 ease-in" x-transition:leave-start="scale-100 opacity-100"
                x-transition:leave-end="scale-95 opacity-0" @click.stop
                class="relative z-10 w-full max-w-3xl overflow-hidden rounded-3xl bg-white shadow-2xl">
                {{-- Header modal --}}
                <div class="flex items-start justify-between border-b border-slate-200 px-6 py-5">
                    <div>
                        <h2 class="text-xl font-bold text-slate-950">
                            Chỉnh sửa tìm kiếm
                        </h2>

                        <p class="mt-1 text-sm text-slate-500">
                            Chọn lại thành phố và thời gian lưu trú.
                        </p>
                    </div>

                    <button type="button" @click="closeEditSearch()"
                        class="flex h-10 w-10 cursor-pointer items-center justify-center rounded-full bg-slate-100 text-slate-500 transition hover:bg-red-50 hover:text-red-500"
                        aria-label="Đóng">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                {{-- Form --}}
                <form method="GET" action="{{ route('homestays.search') }}" class="p-6">
                    <div class="grid gap-5 md:grid-cols-3">
                        {{-- Thành phố --}}
                        <div>
                            <label for="edit-location" class="mb-2 block text-sm font-semibold text-slate-700">
                                Thành phố
                            </label>

                            <div class="relative">
                                <select id="edit-location" name="location" x-model="location"
                                    class="w-full cursor-pointer appearance-none rounded-xl border border-slate-300 bg-white px-4 py-3 pr-10 text-sm font-medium text-slate-700 outline-none transition hover:border-slate-400 focus:border-blue-500 focus:ring-4 focus:ring-blue-100">
                                    <option value="">
                                        Tất cả thành phố
                                    </option>

                                    @foreach ($locations as $location)
                                        <option value="{{ $location }}">
                                            {{ $location }}
                                        </option>
                                    @endforeach
                                </select>

                                <svg class="pointer-events-none absolute right-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>

                            @error('location')
                                <p class="mt-2 text-sm font-medium text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- Ngày nhận phòng --}}
                        <div>
                            <label for="edit-check-in" class="mb-2 block text-sm font-semibold text-slate-700">
                                Ngày nhận phòng
                            </label>

                            <div class="relative">
                                {{-- Input thật gửi về Laravel --}}
                                <input x-ref="editCheckInInput" id="edit-check-in" type="date" name="check_in"
                                    x-model="checkIn" min="{{ now()->toDateString() }}" @change="handleCheckInChange()"
                                    required class="absolute left-0 top-0 h-px w-px opacity-0">

                                {{-- Ô hiển thị dd/mm/yyyy --}}
                                <button type="button" @click="openDatePicker($refs.editCheckInInput)"
                                    class="flex min-h-12 w-full cursor-pointer items-center justify-between rounded-xl border border-slate-300 bg-white px-4 py-3 text-left outline-none transition hover:border-slate-400 focus:border-blue-500 focus:ring-4 focus:ring-blue-100">
                                    <span class="text-sm font-medium"
                                        :class="checkIn
                                            ?
                                            'text-slate-700' :
                                            'text-slate-400'"
                                        x-text="checkIn
                                    ? formatDate(checkIn)
                                    : 'dd/mm/yyyy'"></span>

                                    <svg class="h-5 w-5 shrink-0 text-slate-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3M5 11h14M5 5h14a2 2 0 012 2v12a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z" />
                                    </svg>
                                </button>
                            </div>

                            @error('check_in')
                                <p class="mt-2 text-sm font-medium text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- Ngày trả phòng --}}
                        <div>
                            <label for="edit-check-out" class="mb-2 block text-sm font-semibold text-slate-700">
                                Ngày trả phòng
                            </label>

                            <div class="relative" :class="!checkIn ? 'opacity-60' : ''">
                                {{-- Input thật gửi về Laravel --}}
                                <input x-ref="editCheckOutInput" id="edit-check-out" type="date" name="check_out"
                                    x-model="checkOut" :min="nextDay(checkIn)" :disabled="!checkIn" required
                                    class="absolute left-0 top-0 h-px w-px opacity-0">

                                {{-- Ô hiển thị dd/mm/yyyy --}}
                                <button type="button" :disabled="!checkIn"
                                    @click="openDatePicker($refs.editCheckOutInput)"
                                    class="flex min-h-12 w-full items-center justify-between rounded-xl border border-slate-300 bg-white px-4 py-3 text-left outline-none transition enabled:cursor-pointer enabled:hover:border-slate-400 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 disabled:cursor-not-allowed">
                                    <span class="text-sm font-medium"
                                        :class="checkOut
                                            ?
                                            'text-slate-700' :
                                            'text-slate-400'"
                                        x-text="checkOut
                                    ? formatDate(checkOut)
                                    : checkIn
                                        ? 'dd/mm/yyyy'
                                        : 'Chọn ngày nhận trước'"></span>

                                    <svg class="h-5 w-5 shrink-0 text-slate-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3M5 11h14M5 5h14a2 2 0 012 2v12a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z" />
                                    </svg>
                                </button>
                            </div>

                            @error('check_out')
                                <p class="mt-2 text-sm font-medium text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                    {{-- Nút hành động --}}
                    <div
                        class="mt-6 flex flex-col-reverse gap-3 border-t border-slate-200 pt-5 sm:flex-row sm:justify-end">
                        <button type="button" @click="closeEditSearch()"
                            class="inline-flex cursor-pointer items-center justify-center rounded-xl border border-slate-300 bg-white px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                            Hủy
                        </button>

                        <button type="submit"
                            class="inline-flex cursor-pointer items-center justify-center gap-2 rounded-xl bg-blue-600 px-6 py-3 text-sm font-semibold text-white transition hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-100">
                            <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                              <circle cx="11" cy="11" r="8"/>
                              <path d="m21 21-4.35-4.35"/>
                            </svg>
                            Tìm kiếm lại
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>

    <div x-data="{ mobileFilterOpen: false }" x-init="$watch('mobileFilterOpen', (isOpen) => {
        document.body.style.overflow = isOpen ?
            'hidden' :
            '';
    })" @keydown.escape.window="mobileFilterOpen = false">

        <main class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">

            {{-- ===== DESKTOP: FILTER TRÁI + LIST 3 CỘT PHẢI ===== --}}
            <div class="flex flex-col gap-8 lg:flex-row">

                {{-- BỘ LỌC BÊN TRÁI --}}
                <aside class="hidden w-64 shrink-0 lg:block">
                    <div class="sticky top-24 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                        <div class="mb-5 flex items-center justify-between">
                            <h2 class="text-lg font-bold text-slate-900">Bộ lọc</h2>
                            @if ($activeFilterCount > 0)
                                <span
                                    class="flex h-7 min-w-7 items-center justify-center rounded-full bg-blue-600 px-2 text-xs font-bold text-white">
                                    {{ $activeFilterCount }}
                                </span>
                            @endif
                        </div>

                        @include('partials.desktop-filter', [
                            'prefix' => 'search-desktop',
                            'filterAction' => $searchUrl,
                            'resetUrl' => $resetUrl,
                            'cities' => $locations,
                        ])
                    </div>
                </aside>

                {{-- DANH SÁCH BÊN PHẢI --}}
                <section class="min-w-0 flex-1">

                    {{-- Sort bar --}}
                    <div
                        class="mb-5 flex flex-col gap-3 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <h2 class="text-lg font-bold text-slate-900">Kết quả tìm kiếm</h2>
                            <p class="mt-0.5 text-sm text-slate-500">
                                Hiển thị {{ $homestays->firstItem() ?? 0 }} – {{ $homestays->lastItem() ?? 0 }}
                                / {{ $homestays->total() }} Homestay
                            </p>
                        </div>
                        <div class="flex items-center gap-2">
                            {{-- Nút lọc chỉ hiện trên mobile --}}
                            @include('partials.mobile-filter', [
                                'filterAction' => $searchUrl,
                                'resetUrl' => $resetUrl,
                                'formId' => 'search-mobile-filter-form',
                                'idPrefix' => 'search-mobile',
                                'cities' => $locations,
                            ])

                            <form method="GET" action="{{ $searchUrl }}" class="flex items-center gap-2">
                                @foreach (request()->except(['sort', 'page']) as $key => $value)
                                    @if (is_array($value))
                                        @foreach ($value as $item)
                                            <input type="hidden" name="{{ $key }}[]"
                                                value="{{ $item }}">
                                        @endforeach
                                    @else
                                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                                    @endif
                                @endforeach

                                <select id="sort" name="sort" onchange="this.form.submit()"
                                    class="cursor-pointer h-10 rounded-xl border border-slate-300 bg-white px-3 py-2 text-sm font-semibold text-slate-700 outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100">
                                    <option value="popular" @selected($sort === 'popular')>Phổ biến nhất</option>
                                    <option value="bookings_desc" @selected($sort === 'bookings_desc')>Nhiều lượt đặt nhất
                                    </option>
                                    <option value="rating_desc" @selected($sort === 'rating_desc')>Đánh giá cao nhất</option>
                                    <option value="price_asc" @selected($sort === 'price_asc')>Giá thấp → cao</option>
                                    <option value="price_desc" @selected($sort === 'price_desc')>Giá cao → thấp</option>
                                    <option value="latest" @selected($sort === 'latest')>Mới nhất</option>
                                </select>
                            </form>
                        </div>
                    </div>

                    @if ($homestays->isEmpty())
                        {{-- Không có kết quả --}}
                        <div
                            class="rounded-3xl border border-dashed border-slate-300 bg-white px-6 py-16 text-center shadow-sm">
                            <div
                                class="mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-blue-50 text-4xl">
                                🔎
                            </div>

                            <h2 class="mt-5 text-xl font-bold text-slate-900">
                                Không tìm thấy Homestay còn phòng
                            </h2>

                            <p class="mx-auto mt-2 max-w-lg text-sm leading-6 text-slate-500">
                                Không có Homestay phù hợp tại

                                <span class="font-semibold text-slate-700">
                                    {{ $selectedLocation !== '' ? $selectedLocation : 'các thành phố hiện có' }}
                                </span>

                                trong khoảng từ

                                <span class="font-semibold text-slate-700">
                                    {{ \Carbon\Carbon::parse($checkIn)->format('d/m/Y') }}
                                </span>

                                đến

                                <span class="font-semibold text-slate-700">
                                    {{ \Carbon\Carbon::parse($checkOut)->format('d/m/Y') }}.
                                </span>
                            </p>

                            <div class="mt-6 flex flex-col justify-center gap-3 sm:flex-row">
                                @if ($activeFilterCount > 0)
                                    <a href="{{ $resetUrl }}"
                                        class="inline-flex items-center justify-center rounded-xl border border-slate-300 bg-white px-5 py-3 text-sm font-semibold text-slate-700 transition hover:border-blue-300 hover:bg-blue-50 hover:text-blue-600">
                                        Bỏ bộ lọc phụ
                                    </a>
                                @endif

                                <a href="{{ route('home', [
                                    'location' => $selectedLocation,
                                ]) }}#search"
                                    class="inline-flex items-center justify-center rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-blue-700">
                                    Chọn ngày khác
                                </a>
                            </div>
                        </div>
                    @else
                        {{-- Danh sách kết quả --}}
                        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
                            @foreach ($homestays as $homestay)
                                @php
                                    $averageRating = (float) ($homestay->average_rating ?? 0);

                                    $reviewCount = (int) ($homestay->approved_reviews_count ?? 0);

                                    $bookingCount = (int) ($homestay->bookings_count ?? 0);

                                    $minimumPrice = (int) ($homestay->min_room_price ?? ($homestay->base_price ?? 0));

                                    $availableRoomCount = (int) ($homestay->available_rooms_count ?? 0);
                                @endphp

                                <article
                                    class="group flex h-full flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition duration-300 hover:-translate-y-1 hover:border-blue-200 hover:shadow-xl">
                                    {{-- Hình ảnh --}}
                                    <div class="relative aspect-4/3 overflow-hidden bg-slate-100">
                                        @if ($homestay->thumbnail)
                                            <img src="{{ Storage::url($homestay->thumbnail) }}"
                                                alt="{{ $homestay->name }}" loading="lazy"
                                                class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
                                        @else
                                            <div class="flex h-full items-center justify-center">
                                                <div class="text-center">
                                                    <div class="text-4xl">
                                                        🏡
                                                    </div>

                                                    <p class="mt-2 text-xs font-medium text-slate-400">
                                                        Chưa có hình ảnh
                                                    </p>
                                                </div>
                                            </div>
                                        @endif

                                        {{-- Danh mục --}}
                                        <span
                                            class="absolute left-3 top-3 rounded-full bg-white/95 px-3 py-1.5 text-[11px] font-semibold text-blue-700 shadow-sm backdrop-blur">
                                            {{ $homestay->category?->name ?? 'Homestay' }}
                                        </span>

                                        {{-- Đánh giá --}}
                                        @if ($reviewCount > 0)
                                            <span
                                                class="absolute right-3 top-3 inline-flex items-center gap-1 rounded-full bg-white/95 px-3 py-1.5 text-[11px] font-bold text-slate-800 shadow-sm backdrop-blur">
                                                <x-icon-star class="h-3.5 w-3.5 text-amber-400" />

                                                {{ number_format($averageRating, 1) }}
                                            </span>
                                        @endif
                                    </div>

                                    {{-- Nội dung --}}
                                    <div class="flex flex-1 flex-col p-4">
                                        {{-- Thành phố --}}
                                        <p class="truncate text-xs font-semibold text-blue-600">
                                            {{ $homestay->city ?: 'Chưa cập nhật thành phố' }}
                                        </p>

                                        {{-- Tên --}}
                                        <h2 class="mt-1 line-clamp-1 text-lg font-bold text-slate-950">
                                            {{ $homestay->name }}
                                        </h2>

                                        {{-- Thống kê --}}
                                        <div class="mt-3 flex flex-wrap gap-2">
                                            <span
                                                class="inline-flex items-center gap-1 rounded-full border border-emerald-200 bg-emerald-50 px-2.5 py-1 text-[11px] font-semibold text-emerald-700">
                                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M5 13l4 4L19 7" />
                                                </svg>

                                                {{ $availableRoomCount }}
                                                phòng phù hợp
                                            </span>

                                            <span
                                                class="inline-flex items-center gap-1 rounded-full border border-amber-200 bg-amber-50 px-2.5 py-1 text-[11px] font-semibold text-amber-700">
                                                <x-icon-star class="h-3 w-3 text-amber-400" />

                                                {{ $reviewCount }} đánh giá
                                            </span>
                                        </div>

                                        {{-- Mô tả --}}
                                        <p class="mt-3 line-clamp-2 min-h-10 text-xs leading-5 text-slate-500">
                                            {{ \Illuminate\Support\Str::limit(
                                                $homestay->description ?: 'Không gian nghỉ dưỡng tiện nghi, phù hợp cho gia đình và nhóm bạn.',
                                                90,
                                            ) }}
                                        </p>

                                        {{-- Tiện ích --}}
                                        @if ($homestay->amenities->isNotEmpty())
                                            <div class="mt-3 flex flex-wrap gap-1.5">
                                                @foreach ($homestay->amenities->take(2) as $amenity)
                                                    <span
                                                        class="rounded-lg bg-slate-100 px-2 py-1 text-[11px] font-semibold text-slate-600">
                                                        {{ $amenity->icon ?: '✨' }}
                                                        {{ $amenity->name }}
                                                    </span>
                                                @endforeach

                                                @if ($homestay->amenities->count() > 2)
                                                    <span
                                                        class="rounded-lg bg-slate-100 px-2 py-1 text-[11px] font-semibold text-slate-500">
                                                        +{{ $homestay->amenities->count() - 2 }}
                                                    </span>
                                                @endif
                                            </div>
                                        @endif

                                        {{-- Giá và nút chi tiết --}}
                                        <div
                                            class="mt-auto flex items-end justify-between gap-3 border-t border-slate-100 pt-4">
                                            <div class="min-w-0">
                                                <p class="text-[11px] text-slate-400">
                                                    Giá phòng phù hợp từ
                                                </p>

                                                <div class="mt-0.5">
                                                    <span class="text-lg font-bold text-blue-600">
                                                        {{ number_format($minimumPrice, 0, ',', '.') }}đ
                                                    </span>

                                                    <span class="text-[11px] text-slate-400">
                                                        / đêm
                                                    </span>
                                                </div>
                                            </div>

                                            <a href="{{ route('homestays.show', $homestay->slug) }}"
                                                class="inline-flex shrink-0 items-center justify-center rounded-xl bg-blue-600 px-4 py-2.5 text-xs font-semibold text-white transition hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-100">
                                                Xem chi tiết
                                            </a>
                                        </div>
                                    </div>
                                </article>
                            @endforeach
                        </div>

                        {{-- Phân trang --}}
                        @if ($homestays->hasPages())
                            <div class="mt-8">
                                {{ $homestays->links() }}
                            </div>
                        @endif
                    @endif

                </section>

            </div>
        </main>
    </div>

@endsection
