@php
    $filterAction ??= route('homestays.index');
    $resetUrl ??= $filterAction;
    $formId ??= 'mobile-filter-form';
    $idPrefix ??= 'mobile-filter';

    $cities ??= collect();
    $roomTypes ??= collect();
    $amenities ??= collect();
    $selectedAmenities ??= [];
    $activeFilterCount ??= 0;
@endphp

<div
    x-data="{ mobileFilterOpen: false }"
    x-init="$watch('mobileFilterOpen', (isOpen) => {
        document.body.style.overflow = isOpen ? 'hidden' : '';
    })"
    @keydown.escape.window="mobileFilterOpen = false"
    class="contents"
>
    {{-- Nút lọc chỉ hiện trên mobile --}}
    <button
        type="button"
        @click="mobileFilterOpen = true"
        :aria-expanded="mobileFilterOpen"
        aria-controls="{{ $formId }}"
        class="inline-flex h-10 cursor-pointer items-center gap-2 rounded-xl border border-slate-300 bg-white px-3 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 lg:hidden"
    >
        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
        </svg>
        Bộ lọc
        @if ($activeFilterCount > 0)
            <span class="flex h-5 min-w-5 items-center justify-center rounded-full bg-blue-600 px-1.5 text-[11px] font-bold text-white">
                {{ $activeFilterCount }}
            </span>
        @endif
    </button>

    <div x-show="mobileFilterOpen" x-cloak style="display: none;" class="fixed inset-0 z-100 lg:hidden">
        {{-- Overlay --}}
        <button
            type="button"
            x-transition.opacity
            @click="mobileFilterOpen = false"
            class="absolute inset-0 z-0 cursor-default backdrop-blur-sm"
            aria-label="Đóng bộ lọc"
        ></button>

        {{-- Panel trượt từ phải --}}
        <div
            x-show="mobileFilterOpen"
            x-transition:enter="transform transition ease-out duration-300"
            x-transition:enter-start="translate-x-full"
            x-transition:enter-end="translate-x-0"
            x-transition:leave="transform transition ease-in duration-200"
            x-transition:leave-start="translate-x-0"
            x-transition:leave-end="translate-x-full"
            @click.stop
            class="absolute inset-y-0 right-0 z-10 flex w-72 max-w-[85vw] flex-col bg-white shadow-2xl"
        >
            {{-- Header drawer --}}
            <div class="flex items-center justify-between border-b border-slate-200 px-5 py-4">
                <div class="flex items-center gap-2">
                    <h2 class="text-lg font-bold text-slate-900">Bộ lọc</h2>
                    @if ($activeFilterCount > 0)
                        <span class="flex h-6 min-w-6 items-center justify-center rounded-full bg-blue-600 px-2 text-xs font-bold text-white">
                            {{ $activeFilterCount }}
                        </span>
                    @endif
                </div>

                <button
                    type="button"
                    @click.stop="mobileFilterOpen = false"
                    class="relative z-20 flex h-9 w-9 cursor-pointer items-center justify-center rounded-full bg-slate-100 p-2 text-slate-500 transition hover:bg-red-50 hover:text-red-500"
                    aria-label="Đóng bộ lọc"
                >
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4">
                        <path d="M18 6L6 18M6 6l12 12" />
                    </svg>
                </button>
            </div>

            {{-- Nội dung form lọc --}}
            <div class="flex-1 overflow-y-auto px-5 py-4">
                <form method="GET" action="{{ $filterAction }}" class="space-y-4" id="{{ $formId }}">

                    @if (request()->filled('check_in'))
                        <input type="hidden" name="check_in" value="{{ request('check_in') }}">
                    @endif

                    @if (request()->filled('check_out'))
                        <input type="hidden" name="check_out" value="{{ request('check_out') }}">
                    @endif

                    {{-- Tên Homestay --}}
                    <div>
                        <label for="{{ $idPrefix }}-search" class="text-sm font-semibold text-slate-700">
                            Tên Homestay
                        </label>
                        <input
                            type="text"
                            id="{{ $idPrefix }}-search"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Nhập tên..."
                            class="mt-1 h-11 w-full rounded-xl border border-slate-300 px-3 text-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                        >
                    </div>

                    {{-- Thành phố --}}
                    <div>
                        <label for="{{ $idPrefix }}-location" class="text-sm font-semibold text-slate-700">
                            Thành phố
                        </label>
                        <select
                            id="{{ $idPrefix }}-location"
                            name="location"
                            class="mt-1 h-11 w-full cursor-pointer rounded-xl border border-slate-300 bg-white px-3 text-sm text-slate-700 outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                        >
                            <option value="">Tất cả</option>
                            @foreach ($cities as $city)
                                <option value="{{ $city }}" @selected(request('location') === $city)>
                                    {{ $city }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Khoảng giá --}}
                    <div
                        x-data="{
                            step: 100000,
                            minRaw: @js(request()->filled('min_price') ? (int) request('min_price') : null),
                            maxRaw: @js(request()->filled('max_price') ? (int) request('max_price') : null),
                            minDisplay: '',
                            maxDisplay: '',
                            init() {
                                this.minDisplay = this.formatPrice(this.minRaw);
                                this.maxDisplay = this.formatPrice(this.maxRaw);
                            },
                            parsePrice(value) {
                                const number = String(value ?? '').replace(/\D/g, '');
                                return number === '' ? null : Number(number);
                            },
                            formatPrice(value) {
                                if (value === null || value === '') return '';
                                return new Intl.NumberFormat('vi-VN').format(Number(value));
                            },
                            updateMin(event) {
                                this.minRaw = this.parsePrice(event.target.value);
                                this.minDisplay = this.formatPrice(this.minRaw);
                                event.target.value = this.minDisplay;
                            },
                            updateMax(event) {
                                this.maxRaw = this.parsePrice(event.target.value);
                                this.maxDisplay = this.formatPrice(this.maxRaw);
                                event.target.value = this.maxDisplay;
                            },
                            changeMin(amount) {
                                const currentValue = Number(this.minRaw ?? 0);
                                this.minRaw = Math.max(0, currentValue + amount);
                                this.minDisplay = this.formatPrice(this.minRaw);
                            },
                            changeMax(amount) {
                                const currentValue = Number(this.maxRaw ?? 0);
                                this.maxRaw = Math.max(0, currentValue + amount);
                                this.maxDisplay = this.formatPrice(this.maxRaw);
                            },
                            resetPrice() {
                                this.minRaw = null;
                                this.maxRaw = null;
                                this.minDisplay = '';
                                this.maxDisplay = '';
                            }
                        }"
                    >
                        <div class="flex items-center justify-between gap-3">
                            <p class="text-sm font-semibold text-slate-700">Khoảng giá / đêm</p>
                            <button
                                type="button"
                                @click="resetPrice()"
                                class="cursor-pointer text-xs font-semibold text-blue-600 transition hover:text-blue-700 hover:underline"
                            >
                                Đặt lại
                            </button>
                        </div>

                        <div class="mt-2 space-y-2">
                            {{-- Giá từ --}}
                            <div>
                                <label class="text-xs font-semibold text-slate-500">Giá từ</label>
                                <div class="mt-1 flex h-11 overflow-hidden rounded-xl border border-slate-300 bg-white transition focus-within:border-blue-500 focus-within:ring-4 focus-within:ring-blue-100">
                                    <button
                                        type="button"
                                        @click="changeMin(-step)"
                                        class="flex w-10 shrink-0 cursor-pointer items-center justify-center border-r border-slate-200 text-lg font-semibold text-slate-500 transition hover:bg-slate-100 hover:text-blue-600"
                                    >−</button>
                                    <div class="relative min-w-0 flex-1">
                                        <input
                                            type="text"
                                            inputmode="numeric"
                                            autocomplete="off"
                                            :value="minDisplay"
                                            @input="updateMin($event)"
                                            @keydown.arrow-up.prevent="changeMin(step)"
                                            @keydown.arrow-down.prevent="changeMin(-step)"
                                            placeholder="0"
                                            class="h-full w-full border-0 bg-transparent px-3 pr-8 text-sm font-semibold text-slate-800 outline-none placeholder:font-normal placeholder:text-slate-400 focus:ring-0"
                                        >
                                        <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3 text-xs font-semibold text-slate-400">đ</span>
                                    </div>
                                    <button
                                        type="button"
                                        @click="changeMin(step)"
                                        class="flex w-10 shrink-0 cursor-pointer items-center justify-center border-l border-slate-200 text-lg font-semibold text-slate-500 transition hover:bg-slate-100 hover:text-blue-600"
                                    >+</button>
                                </div>
                                <input type="hidden" name="min_price" :value="minRaw ?? ''">
                            </div>

                            {{-- Giá đến --}}
                            <div>
                                <label class="text-xs font-semibold text-slate-500">Giá đến</label>
                                <div class="mt-1 flex h-11 overflow-hidden rounded-xl border border-slate-300 bg-white transition focus-within:border-blue-500 focus-within:ring-4 focus-within:ring-blue-100">
                                    <button
                                        type="button"
                                        @click="changeMax(-step)"
                                        class="flex w-10 shrink-0 cursor-pointer items-center justify-center border-r border-slate-200 text-lg font-semibold text-slate-500 transition hover:bg-slate-100 hover:text-blue-600"
                                    >−</button>
                                    <div class="relative min-w-0 flex-1">
                                        <input
                                            type="text"
                                            inputmode="numeric"
                                            autocomplete="off"
                                            :value="maxDisplay"
                                            @input="updateMax($event)"
                                            @keydown.arrow-up.prevent="changeMax(step)"
                                            @keydown.arrow-down.prevent="changeMax(-step)"
                                            placeholder="0"
                                            class="h-full w-full border-0 bg-transparent px-3 pr-8 text-sm font-semibold text-slate-800 outline-none placeholder:text-xs placeholder:font-normal placeholder:text-slate-400 focus:ring-0"
                                        >
                                        <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3 text-xs font-semibold text-slate-400">đ</span>
                                    </div>
                                    <button
                                        type="button"
                                        @click="changeMax(step)"
                                        class="flex w-10 shrink-0 cursor-pointer items-center justify-center border-l border-slate-200 text-lg font-semibold text-slate-500 transition hover:bg-slate-100 hover:text-blue-600"
                                    >+</button>
                                </div>
                                <input type="hidden" name="max_price" :value="maxRaw ?? ''">
                            </div>
                        </div>

                        <p class="mt-1 text-xs leading-5 text-slate-400">
                            Mỗi bước tăng hoặc giảm là <span class="font-semibold text-slate-500">100.000đ</span>
                        </p>
                    </div>

                    {{-- Số khách --}}
                    <div
                        x-data="{
                            guests: @js(
                                request()->filled('guests')
                                    ? max(1, min(50, (int) request('guests')))
                                    : null
                            ),
                            decreaseGuests() {
                                if (this.guests === null || this.guests === '') {
                                    this.guests = 1;
                                    return;
                                }
                                this.guests = Math.max(1, Number(this.guests) - 1);
                            },
                            increaseGuests() {
                                if (this.guests === null || this.guests === '') {
                                    this.guests = 1;
                                    return;
                                }
                                this.guests = Math.min(50, Number(this.guests) + 1);
                            },
                            validateGuests() {
                                if (this.guests === null || this.guests === '') {
                                    this.guests = null;
                                    return;
                                }
                                this.guests = Math.min(50, Math.max(1, Number(this.guests)));
                            }
                        }"
                    >
                        <label for="{{ $idPrefix }}-guests" class="text-sm font-semibold text-slate-700">
                            Số khách
                        </label>

                        <div class="mt-1 flex h-11 overflow-hidden rounded-xl border border-slate-300 bg-white transition focus-within:border-blue-500 focus-within:ring-4 focus-within:ring-blue-100">
                            <button
                                type="button"
                                @click="decreaseGuests()"
                                class="flex w-10 shrink-0 cursor-pointer items-center justify-center border-r border-slate-200 text-lg font-semibold text-slate-500 transition hover:bg-slate-100 hover:text-blue-600"
                                aria-label="Giảm số khách"
                            >−</button>

                            <div class="relative min-w-0 flex-1">
                                <input
                                    type="number"
                                    id="{{ $idPrefix }}-guests"
                                    min="1"
                                    max="50"
                                    x-model.number="guests"
                                    @input="validateGuests()"
                                    @keydown.arrow-up.prevent="increaseGuests()"
                                    @keydown.arrow-down.prevent="decreaseGuests()"
                                    placeholder="1"
                                    class="h-full w-full appearance-none border-0 bg-transparent px-3 pr-14 text-sm font-semibold text-slate-800 outline-none placeholder:font-normal placeholder:text-slate-400 focus:ring-0"
                                >
                                <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3 text-xs font-semibold text-slate-400">
                                    khách
                                </span>
                            </div>

                            <button
                                type="button"
                                @click="increaseGuests()"
                                class="flex w-10 shrink-0 cursor-pointer items-center justify-center border-l border-slate-200 text-lg font-semibold text-slate-500 transition hover:bg-slate-100 hover:text-blue-600"
                                aria-label="Tăng số khách"
                            >+</button>
                        </div>

                        <input type="hidden" name="guests" :value="guests ?? ''">
                    </div>

                    {{-- Loại phòng --}}
                    <div>
                        <label for="{{ $idPrefix }}-room-type" class="text-sm font-semibold text-slate-700">
                            Loại phòng
                        </label>
                        <select
                            id="{{ $idPrefix }}-room-type"
                            name="room_type"
                            class="mt-1 h-11 w-full cursor-pointer rounded-xl border border-slate-300 bg-white px-3 text-sm text-slate-700 outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                        >
                            <option value="">Tất cả</option>
                            @foreach ($roomTypes as $roomType)
                                <option value="{{ $roomType }}" @selected(request('room_type') === $roomType)>
                                    {{ $roomType }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Đánh giá --}}
                    <div>
                        <label for="{{ $idPrefix }}-rating" class="text-sm font-semibold text-slate-700">
                            Đánh giá
                        </label>
                        <select
                            id="{{ $idPrefix }}-rating"
                            name="rating"
                            class="mt-1 h-11 w-full cursor-pointer rounded-xl border border-slate-300 bg-white px-3 text-sm text-slate-700 outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                        >
                            <option value="">Tất cả</option>
                            @for ($star = 5; $star >= 1; $star--)
                                <option value="{{ $star }}" @selected((int) request('rating') === $star)>
                                    Từ {{ $star }} sao
                                </option>
                            @endfor
                        </select>
                    </div>

                    {{-- Tiện ích --}}
                    @if ($amenities->isNotEmpty())
                        <div>
                            <p class="text-sm font-semibold text-slate-700">Tiện ích</p>
                            <div class="mt-1.5 max-h-48 space-y-1 overflow-y-auto pr-1">
                                @foreach ($amenities as $amenity)
                                    <label class="flex cursor-pointer items-center gap-2 rounded-lg px-1.5 py-1.5 hover:bg-slate-50">
                                        <input
                                            type="checkbox"
                                            name="amenities[]"
                                            value="{{ $amenity->id }}"
                                            @checked(in_array($amenity->id, $selectedAmenities))
                                            class="h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500"
                                        >
                                        <span class="min-w-0 truncate text-sm text-slate-600">
                                            {{ $amenity->icon ?: '✨' }} {{ $amenity->name }}
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <input type="hidden" name="sort" value="{{ request('sort', 'popular') }}">
                </form>
            </div>

            {{-- Footer --}}
            <div class="space-y-2 border-t border-slate-200 px-5 py-4">
                <button
                    type="submit"
                    form="{{ $formId }}"
                    class="w-full cursor-pointer rounded-xl bg-blue-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-blue-700"
                >
                    Áp dụng bộ lọc
                </button>
                <a
                    href="{{ $resetUrl }}"
                    class="flex w-full items-center justify-center rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50"
                >
                    Xóa tất cả
                </a>
            </div>
        </div>
    </div>
</div>