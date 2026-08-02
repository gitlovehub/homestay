{{-- Quản lý vị trí bản đồ --}}
<section
    x-data="{ showCreateLocation: false }"
    class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm"
>
    {{-- Tiêu đề --}}
    <div
        class="flex flex-col gap-4 border-b border-slate-200 p-5 sm:flex-row sm:items-center sm:justify-between"
    >
        <div>
            <p class="text-sm font-semibold text-blue-600">
                Bản đồ trang liên hệ
            </p>

            <h2 class="mt-1 text-xl font-bold text-slate-900">
                Quản lý vị trí
            </h2>

            <p class="mt-2 text-sm text-slate-500">
                Đang có {{ $locations->count() }}/5 vị trí.
            </p>
        </div>

        @if ($locations->count() < 5)
            <button
                type="button"
                @click="showCreateLocation = !showCreateLocation"
                class="inline-flex cursor-pointer items-center justify-center gap-2 rounded-xl bg-blue-600 px-5 py-3 text-sm font-bold text-white transition hover:bg-blue-700"
            >
                <svg
                    class="h-5 w-5"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M12 4.5v15m7.5-7.5h-15"
                    />
                </svg>

                Thêm vị trí
            </button>
        @else
            <span
                class="rounded-xl bg-amber-50 px-4 py-3 text-sm font-semibold text-amber-700"
            >
                Đã đủ 5 vị trí
            </span>
        @endif
    </div>

    {{-- Form thêm --}}
    @if ($locations->count() < 5)
        <div
            x-show="showCreateLocation"
            x-cloak
            x-transition
            class="border-b border-slate-200 bg-blue-50/40 p-5"
        >
            <form
                action="{{ route('admin.contacts.locations.store') }}"
                method="POST"
                class="space-y-5"
            >
                @csrf

                <div class="grid gap-5 sm:grid-cols-2">
                    <div>
                        <label
                            for="location_label"
                            class="mb-2 block text-sm font-semibold text-slate-700"
                        >
                            Tên ngắn
                            <span class="text-red-500">*</span>
                        </label>

                        <input
                            id="location_label"
                            type="text"
                            name="label"
                            value="{{ old('label') }}"
                            placeholder="Ví dụ: Hà Nội"
                            maxlength="50"
                            required
                            class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                        >

                        @error('label')
                            <p class="mt-2 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div>
                        <label
                            for="location_name"
                            class="mb-2 block text-sm font-semibold text-slate-700"
                        >
                            Tên địa điểm
                            <span class="text-red-500">*</span>
                        </label>

                        <input
                            id="location_name"
                            type="text"
                            name="name"
                            value="{{ old('name') }}"
                            placeholder="Ví dụ: FPT Polytechnic Hà Nội"
                            maxlength="150"
                            required
                            class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                        >

                        @error('name')
                            <p class="mt-2 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label
                        for="location_address"
                        class="mb-2 block text-sm font-semibold text-slate-700"
                    >
                        Địa chỉ hiển thị
                        <span class="text-red-500">*</span>
                    </label>

                    <input
                        id="location_address"
                        type="text"
                        name="address"
                        value="{{ old('address') }}"
                        placeholder="Nhập địa chỉ đầy đủ"
                        maxlength="255"
                        required
                        class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                    >

                    @error('address')
                        <p class="mt-2 text-sm text-red-600">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div>
                    <label
                        for="location_map_query"
                        class="mb-2 block text-sm font-semibold text-slate-700"
                    >
                        Địa chỉ tìm kiếm Google Maps
                        <span class="text-red-500">*</span>
                    </label>

                    <input
                        id="location_map_query"
                        type="text"
                        name="map_query"
                        value="{{ old('map_query') }}"
                        placeholder="Ví dụ: Tòa nhà FPT Polytechnic, Trịnh Văn Bô, Hà Nội"
                        maxlength="255"
                        required
                        class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                    >

                    @error('map_query')
                        <p class="mt-2 text-sm text-red-600">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div class="grid gap-5 sm:grid-cols-2">
                    <div>
                        <label
                            for="location_sort_order"
                            class="mb-2 block text-sm font-semibold text-slate-700"
                        >
                            Thứ tự hiển thị
                        </label>

                        <select
                            id="location_sort_order"
                            name="sort_order"
                            class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                        >
                            @for ($index = 1; $index <= 5; $index++)
                                <option
                                    value="{{ $index }}"
                                    @selected(
                                        (int) old(
                                            'sort_order',
                                            $locations->count() + 1
                                        ) === $index
                                    )
                                >
                                    Vị trí {{ $index }}
                                </option>
                            @endfor
                        </select>
                    </div>

                    <div class="flex items-end">
                        <label
                            class="flex w-full cursor-pointer items-center gap-3 rounded-xl border border-slate-200 bg-white px-4 py-3"
                        >
                            <input
                                type="hidden"
                                name="is_active"
                                value="0"
                            >

                            <input
                                type="checkbox"
                                name="is_active"
                                value="1"
                                checked
                                class="h-5 w-5 rounded border-slate-300 text-blue-600 focus:ring-blue-500"
                            >

                            <span class="text-sm font-semibold text-slate-700">
                                Hiển thị trên trang liên hệ
                            </span>
                        </label>
                    </div>
                </div>

                <div class="flex gap-3">
                    <button
                        type="submit"
                        class="cursor-pointer rounded-xl bg-blue-600 px-6 py-3 text-sm font-bold text-white transition hover:bg-blue-700"
                    >
                        Lưu vị trí
                    </button>

                    <button
                        type="button"
                        @click="showCreateLocation = false"
                        class="cursor-pointer rounded-xl border border-slate-300 bg-white px-6 py-3 text-sm font-bold text-slate-600 transition hover:bg-slate-50"
                    >
                        Hủy
                    </button>
                </div>
            </form>
        </div>
    @endif

    {{-- Danh sách vị trí --}}
    <div class="grid gap-4 p-5 lg:grid-cols-2">
        @forelse ($locations as $location)
            <div
                x-data="{ editing: false }"
                class="rounded-2xl border border-slate-200 bg-white p-5"
            >
                {{-- Nội dung vị trí --}}
                <div x-show="!editing">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex min-w-0 items-start gap-3">
                            <span
                                class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-50 font-bold text-blue-600"
                            >
                                {{ $location->sort_order }}
                            </span>

                            <div class="min-w-0">
                                <p class="text-xs font-bold uppercase tracking-wider text-blue-600">
                                    {{ $location->label }}
                                </p>

                                <h3 class="mt-1 font-bold text-slate-900">
                                    {{ $location->name }}
                                </h3>

                                <p class="mt-2 text-sm leading-6 text-slate-500">
                                    {{ $location->address }}
                                </p>
                            </div>
                        </div>

                        @if ($location->is_active)
                            <span
                                class="shrink-0 rounded-full bg-green-50 px-3 py-1 text-xs font-bold text-green-600"
                            >
                                Đang hiện
                            </span>
                        @else
                            <span
                                class="shrink-0 rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-slate-500"
                            >
                                Đang ẩn
                            </span>
                        @endif
                    </div>

                    <div class="mt-5 flex flex-wrap gap-2">
                        <a
                            href="https://www.google.com/maps/search/?api=1&query={{ urlencode($location->map_query) }}"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="rounded-lg bg-green-50 px-3 py-2 text-xs font-bold text-green-700 transition hover:bg-green-600 hover:text-white"
                        >
                            Xem bản đồ
                        </a>

                        <button
                            type="button"
                            @click="editing = true"
                            class="cursor-pointer rounded-lg bg-blue-50 px-3 py-2 text-xs font-bold text-blue-600 transition hover:bg-blue-600 hover:text-white"
                        >
                            Sửa
                        </button>

                        <form
                            action="{{ route(
                                'admin.contacts.locations.destroy',
                                $location
                            ) }}"
                            method="POST"
                            onsubmit="return confirm('Bạn có chắc muốn xóa vị trí này?')"
                        >
                            @csrf
                            @method('DELETE')

                            <button
                                type="submit"
                                class="cursor-pointer rounded-lg bg-red-50 px-3 py-2 text-xs font-bold text-red-600 transition hover:bg-red-600 hover:text-white"
                            >
                                Xóa
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Form sửa --}}
                <form
                    x-show="editing"
                    x-cloak
                    action="{{ route(
                        'admin.contacts.locations.update',
                        $location
                    ) }}"
                    method="POST"
                    class="space-y-4"
                >
                    @csrf
                    @method('PUT')

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label class="mb-2 block text-xs font-bold text-slate-600">
                                Tên ngắn
                            </label>

                            <input
                                type="text"
                                name="label"
                                value="{{ $location->label }}"
                                maxlength="50"
                                required
                                class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                            >
                        </div>

                        <div>
                            <label class="mb-2 block text-xs font-bold text-slate-600">
                                Thứ tự
                            </label>

                            <select
                                name="sort_order"
                                class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                            >
                                @for ($index = 1; $index <= 5; $index++)
                                    <option
                                        value="{{ $index }}"
                                        @selected($location->sort_order === $index)
                                    >
                                        Vị trí {{ $index }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="mb-2 block text-xs font-bold text-slate-600">
                            Tên địa điểm
                        </label>

                        <input
                            type="text"
                            name="name"
                            value="{{ $location->name }}"
                            maxlength="150"
                            required
                            class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                        >
                    </div>

                    <div>
                        <label class="mb-2 block text-xs font-bold text-slate-600">
                            Địa chỉ hiển thị
                        </label>

                        <input
                            type="text"
                            name="address"
                            value="{{ $location->address }}"
                            maxlength="255"
                            required
                            class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                        >
                    </div>

                    <div>
                        <label class="mb-2 block text-xs font-bold text-slate-600">
                            Địa chỉ Google Maps
                        </label>

                        <input
                            type="text"
                            name="map_query"
                            value="{{ $location->map_query }}"
                            maxlength="255"
                            required
                            class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                        >
                    </div>

                    <label
                        class="flex cursor-pointer items-center gap-3 rounded-xl border border-slate-200 p-3"
                    >
                        <input
                            type="hidden"
                            name="is_active"
                            value="0"
                        >

                        <input
                            type="checkbox"
                            name="is_active"
                            value="1"
                            @checked($location->is_active)
                            class="h-5 w-5 rounded border-slate-300 text-blue-600 focus:ring-blue-500"
                        >

                        <span class="text-sm font-semibold text-slate-700">
                            Hiển thị trên trang liên hệ
                        </span>
                    </label>

                    <div class="flex gap-2">
                        <button
                            type="submit"
                            class="cursor-pointer rounded-lg bg-blue-600 px-4 py-2.5 text-xs font-bold text-white transition hover:bg-blue-700"
                        >
                            Lưu thay đổi
                        </button>

                        <button
                            type="button"
                            @click="editing = false"
                            class="cursor-pointer rounded-lg border border-slate-300 px-4 py-2.5 text-xs font-bold text-slate-600 transition hover:bg-slate-50"
                        >
                            Hủy
                        </button>
                    </div>
                </form>
            </div>
        @empty
            <div
                class="col-span-full rounded-2xl border border-dashed border-slate-300 px-6 py-12 text-center"
            >
                <h3 class="font-bold text-slate-900">
                    Chưa có vị trí bản đồ
                </h3>

                <p class="mt-2 text-sm text-slate-500">
                    Nhấn “Thêm vị trí” để tạo vị trí đầu tiên.
                </p>
            </div>
        @endforelse
    </div>
</section>