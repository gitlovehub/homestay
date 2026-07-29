@php
    $prefix = $prefix ?? 'filter';

    $filterAction = $filterAction
        ?? route('homestays.index');

    $resetUrl = $resetUrl
        ?? route('homestays.index');
@endphp

<form method="GET" action="{{ $filterAction }}" class="space-y-4">
    <div>
        <label for="search" class="text-sm font-semibold text-slate-700">Tên Homestay</label>
        <input type="text" id="search" name="search" value="{{ request('search') }}"
            placeholder="Nhập tên..."
            class="mt-1 w-full rounded-xl border border-slate-300 px-3 py-2 text-sm outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-100">
    </div>

    <div>
        <label for="location" class="text-sm font-semibold text-slate-700">Địa điểm</label>
        <input type="text" id="location" name="location" value="{{ request('location') }}"
            placeholder="Thành phố..."
            class="mt-1 w-full rounded-xl border border-slate-300 px-3 py-2 text-sm outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-100">
    </div>

    <div>
        <p class="text-sm font-semibold text-slate-700">Khoảng giá / đêm</p>
        <div class="mt-2 grid grid-cols-2 gap-3">

            <input
                type="number"
                name="min_price"
                value="{{ request('min_price') }}"
                min="0"
                step="50000"
                placeholder="Giá từ"
                class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
            >

            <input
                type="number"
                name="max_price"
                value="{{ request('max_price') }}"
                min="0"
                step="50000"
                placeholder="Giá đến"
                class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
            >

        </div>
    </div>

    <div>
        <label for="guests" class="text-sm font-semibold text-slate-700">Số khách</label>
        <input type="number" id="guests" name="guests" value="{{ request('guests') }}"
            min="1" max="50" placeholder="Ví dụ: 4"
            class="mt-1 w-full rounded-xl border border-slate-300 px-3 py-2 text-sm outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-100">
    </div>

    <div>
        <label for="room-type" class="text-sm font-semibold text-slate-700">Loại phòng</label>
        <select id="room-type" name="room_type"
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
        <label for="rating" class="text-sm font-semibold text-slate-700">Đánh giá</label>
        <select id="rating" name="rating"
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
                    <label class="flex cursor-pointer items-center gap-2 rounded-lg px-1.5 py-1.5 hover:bg-slate-50">
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