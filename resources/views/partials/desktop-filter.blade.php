@php
    $prefix ??= 'filter';

    $filterAction ??= route('homestays.index');

    $resetUrl ??= $filterAction;

    /*
    |--------------------------------------------------------------------------
    | Dữ liệu mặc định
    |--------------------------------------------------------------------------
    |
    | Giúp partial không báo lỗi nếu một trang chưa truyền đủ dữ liệu.
    |
    */

    $cities ??= collect();

    $roomTypes ??= collect();

    $amenities ??= collect();

    $selectedAmenities ??= [];
@endphp

<form method="GET" action="{{ $filterAction }}" class="space-y-4">

    {{-- Giữ điều kiện ngày khi áp dụng bộ lọc phụ --}}
    @if (request()->filled('check_in'))
        <input
            type="hidden"
            name="check_in"
            value="{{ request('check_in') }}"
        >
    @endif

    @if (request()->filled('check_out'))
        <input
            type="hidden"
            name="check_out"
            value="{{ request('check_out') }}"
        >
    @endif

    <div>
        <label for="search" class="text-sm font-semibold text-slate-700">Tên Homestay</label>
        <input type="text" id="{{ $prefix }}-search" name="search" value="{{ request('search') }}"
            placeholder="Nhập tên..."
            class="mt-1 w-full rounded-xl border border-slate-300 px-3 py-2 text-sm outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-100">
    </div>

    {{-- Thành phố --}}
    <div>
        <label
            for="{{ $prefix }}-location"
            class="text-sm font-semibold text-slate-700"
        >
            Thành phố
        </label>

        <select
            id="{{ $prefix }}-location"
            name="location"
            class="mt-1 w-full cursor-pointer rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-700 outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
        >
            <option value="">
                Tất cả
            </option>

            @foreach ($cities as $city)
                <option
                    value="{{ $city }}"
                    @selected(request('location') === $city)
                >
                    {{ $city }}
                </option>
            @endforeach
        </select>
    </div>

    <div x-data="{
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
            const number = String(value ?? '')
                .replace(/\D/g, '');
    
            return number === '' ?
                null :
                Number(number);
        },
    
        formatPrice(value) {
            if (
                value === null ||
                value === ''
            ) {
                return '';
            }
    
            return new Intl.NumberFormat('vi-VN')
                .format(Number(value));
        },
    
        updateMin(event) {
            this.minRaw = this.parsePrice(
                event.target.value
            );
    
            this.minDisplay = this.formatPrice(
                this.minRaw
            );
    
            event.target.value = this.minDisplay;
        },
    
        updateMax(event) {
            this.maxRaw = this.parsePrice(
                event.target.value
            );
    
            this.maxDisplay = this.formatPrice(
                this.maxRaw
            );
    
            event.target.value = this.maxDisplay;
        },
    
        changeMin(amount) {
            const currentValue = Number(
                this.minRaw ?? 0
            );
    
            this.minRaw = Math.max(
                0,
                currentValue + amount
            );
    
            this.minDisplay = this.formatPrice(
                this.minRaw
            );
        },
    
        changeMax(amount) {
            const currentValue = Number(
                this.maxRaw ?? 0
            );
    
            this.maxRaw = Math.max(
                0,
                currentValue + amount
            );
    
            this.maxDisplay = this.formatPrice(
                this.maxRaw
            );
        },
    
        resetPrice() {
            this.minRaw = null;
            this.maxRaw = null;
    
            this.minDisplay = '';
            this.maxDisplay = '';
        }
    }">
        <div class="flex items-center justify-between gap-3">

            <p class="text-sm font-semibold text-slate-700">
                Khoảng giá / đêm
            </p>

            <button type="button" @click="resetPrice()"
                class="cursor-pointer text-xs font-semibold text-blue-600 transition hover:text-blue-700 hover:underline">
                Đặt lại
            </button>

        </div>

        <div class="space-y-1">

            {{-- Giá từ --}}
            <div>

                <label class="text-xs font-semibold text-slate-500">
                    Giá từ
                </label>

                <div
                    class="mt-1 flex overflow-hidden rounded-xl border border-slate-300 bg-white transition focus-within:border-blue-500 focus-within:ring-4 focus-within:ring-blue-100">

                    <button type="button" @click="changeMin(-step)"
                        class="flex w-10 shrink-0 cursor-pointer items-center justify-center border-r border-slate-200 text-lg font-semibold text-slate-500 transition hover:bg-slate-100 hover:text-blue-600"
                        aria-label="Giảm giá tối thiểu">
                        −
                    </button>

                    <div class="relative min-w-0 flex-1">

                        <input type="text" inputmode="numeric" autocomplete="off"
                            :value="minDisplay" @input="updateMin($event)"
                            @keydown.arrow-up.prevent="changeMin(step)"
                            @keydown.arrow-down.prevent="changeMin(-step)" placeholder="0"
                            class="w-full border-0 bg-transparent px-3 py-3 pr-8 text-sm font-semibold text-slate-800 outline-none placeholder:font-normal placeholder:text-slate-400 focus:ring-0">

                        <span
                            class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3 text-xs font-semibold text-slate-400">
                            đ
                        </span>

                    </div>

                    <button type="button" @click="changeMin(step)"
                        class="flex w-10 shrink-0 cursor-pointer items-center justify-center border-l border-slate-200 text-lg font-semibold text-slate-500 transition hover:bg-slate-100 hover:text-blue-600"
                        aria-label="Tăng giá tối thiểu">
                        +
                    </button>

                </div>

                <input type="hidden" name="min_price" :value="minRaw ?? ''">

            </div>

            {{-- Giá đến --}}
            <div>

                <label class="text-xs font-semibold text-slate-500">
                    Giá đến
                </label>

                <div
                    class="mt-1 flex overflow-hidden rounded-xl border border-slate-300 bg-white transition focus-within:border-blue-500 focus-within:ring-4 focus-within:ring-blue-100">

                    <button type="button" @click="changeMax(-step)"
                        class="flex w-10 shrink-0 cursor-pointer items-center justify-center border-r border-slate-200 text-lg font-semibold text-slate-500 transition hover:bg-slate-100 hover:text-blue-600"
                        aria-label="Giảm giá tối đa">
                        −
                    </button>

                    <div class="relative min-w-0 flex-1">

                        <input type="text" inputmode="numeric" autocomplete="off"
                            :value="maxDisplay" @input="updateMax($event)"
                            @keydown.arrow-up.prevent="changeMax(step)"
                            @keydown.arrow-down.prevent="changeMax(-step)"
                            placeholder="0"
                            class="w-full border-0 bg-transparent px-3 py-3 pr-8 text-sm font-semibold text-slate-800 outline-none placeholder:text-xs placeholder:font-normal placeholder:text-slate-400 focus:ring-0">

                        <span
                            class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3 text-xs font-semibold text-slate-400">
                            đ
                        </span>

                    </div>

                    <button type="button" @click="changeMax(step)"
                        class="flex w-10 shrink-0 cursor-pointer items-center justify-center border-l border-slate-200 text-lg font-semibold text-slate-500 transition hover:bg-slate-100 hover:text-blue-600"
                        aria-label="Tăng giá tối đa">
                        +
                    </button>

                </div>

                <input type="hidden" name="max_price" :value="maxRaw ?? ''">

            </div>

        </div>

        <p class="mt-1 text-xs leading-5 text-slate-400">
            Mỗi bước tăng hoặc giảm là
            <span class="font-semibold text-slate-500">
                100.000đ
            </span>
        </p>

    </div>

    {{-- Số khách --}}
    <div
        x-data="{
            guests: @js(request()->filled('guests') ? max(1, min(50, (int) request('guests'))) : null),

            decreaseGuests() {
                if (
                    this.guests === null ||
                    this.guests === ''
                ) {
                    this.guests = 1;
                    return;
                }

                this.guests = Math.max(
                    1,
                    Number(this.guests) - 1
                );
            },

            increaseGuests() {
                if (
                    this.guests === null ||
                    this.guests === ''
                ) {
                    this.guests = 1;
                    return;
                }

                this.guests = Math.min(
                    50,
                    Number(this.guests) + 1
                );
            },

            validateGuests() {
                if (
                    this.guests === null ||
                    this.guests === ''
                ) {
                    this.guests = null;
                    return;
                }

                this.guests = Math.min(
                    50,
                    Math.max(1, Number(this.guests))
                );
            }
        }"
    >
        <label
            for="{{ $prefix }}-guests"
            class="text-sm font-semibold text-slate-700"
        >
            Số khách
        </label>

        <div
            class="mt-1 flex overflow-hidden rounded-xl border border-slate-300 bg-white transition focus-within:border-blue-500 focus-within:ring-4 focus-within:ring-blue-100"
        >
            {{-- Nút giảm --}}
            <button
                type="button"
                @click="decreaseGuests()"
                class="flex w-10 shrink-0 cursor-pointer items-center justify-center border-r border-slate-200 text-lg font-semibold text-slate-500 transition hover:bg-slate-100 hover:text-blue-600"
                aria-label="Giảm số khách"
            >
                −
            </button>

            {{-- Ô nhập số khách --}}
            <div class="relative min-w-0 flex-1">
                <input
                    type="number"
                    id="{{ $prefix }}-guests"
                    min="1"
                    max="50"
                    x-model.number="guests"
                    @input="validateGuests()"
                    @keydown.arrow-up.prevent="increaseGuests()"
                    @keydown.arrow-down.prevent="decreaseGuests()"
                    placeholder="1"
                    class="w-full appearance-none border-0 bg-transparent px-3 py-3 pr-14 text-sm font-semibold text-slate-800 outline-none placeholder:font-normal placeholder:text-slate-400 focus:ring-0"
                >

                <span
                    class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3 text-xs font-semibold text-slate-400"
                >
                    khách
                </span>
            </div>

            {{-- Nút tăng --}}
            <button
                type="button"
                @click="increaseGuests()"
                class="flex w-10 shrink-0 cursor-pointer items-center justify-center border-l border-slate-200 text-lg font-semibold text-slate-500 transition hover:bg-slate-100 hover:text-blue-600"
                aria-label="Tăng số khách"
            >
                +
            </button>
        </div>

        {{-- Giá trị thực gửi lên controller --}}
        <input
            type="hidden"
            name="guests"
            :value="guests ?? ''"
        >
    </div>

    <div>
        <label for="{{ $prefix }}-room-type" class="text-sm font-semibold text-slate-700">Loại phòng</label>
        <select id="{{ $prefix }}-room-type" name="room_type"
            class="mt-1 w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-sm outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-100">
            <option value="">Tất cả</option>
            @foreach ($roomTypes as $roomType)
                <option value="{{ $roomType }}" @selected(request('room_type') === $roomType)>
                    {{ $roomType }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label for=for="{{ $prefix }}-rating" class="text-sm font-semibold text-slate-700">Đánh giá</label>
        <select id=for="{{ $prefix }}-rating" name="rating"
            class="mt-1 w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-sm outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-100">
            <option value="">Tất cả</option>
            @for ($star = 5; $star >= 1; $star--)
                <option value="{{ $star }}" @selected((int) request('rating') === $star)>
                    Từ {{ $star }} sao
                </option>
            @endfor
        </select>
    </div>

    @if ($amenities->isNotEmpty())
        <div>
            <p class="text-sm font-semibold text-slate-700">Tiện ích</p>
            <div class="mt-1.5 max-h-44 space-y-1 overflow-y-auto pr-1">
                @foreach ($amenities as $amenity)
                    <label
                        class="flex cursor-pointer items-center gap-2 rounded-lg px-1.5 py-1.5 hover:bg-slate-50">
                        <input type="checkbox" name="amenities[]" value="{{ $amenity->id }}"
                            @checked(in_array($amenity->id, $selectedAmenities))
                            class="h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                        <span class="min-w-0 truncate text-sm text-slate-600">
                            {{ $amenity->icon ?: '✨' }} {{ $amenity->name }}
                        </span>
                    </label>
                @endforeach
            </div>
        </div>
    @endif

    <input type="hidden" name="sort" value="{{ request('sort', 'popular') }}">

    <div class="space-y-2 border-t border-slate-200 pt-4">
        <button type="submit"
            class="w-full cursor-pointer rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-blue-700">
            Áp dụng bộ lọc
        </button>
        <a href="{{ $resetUrl }}"
            class="flex w-full items-center justify-center rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">
            Xóa tất cả
        </a>
    </div>
</form>