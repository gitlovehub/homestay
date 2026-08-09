@extends('layouts.admin')

@section('title', 'Quản lý phòng | HomeStayGo')

@section('page-title', 'Quản lý phòng')

@section('content')
    <div class="mx-auto max-w-screen-2xl">

        <x-alert />

        <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

            <h2 class="text-lg font-semibold text-slate-900 dark:text-slate-100 md:text-2xl">
                Danh sách phòng thuộc các Homestay trong hệ thống.
            </h2>

            <a href="{{ route('admin.rooms.create') }}"
                class="inline-flex h-11 w-full items-center justify-center gap-2 rounded-xl bg-blue-600 px-5 text-xs font-semibold text-white transition hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-200 dark:focus:ring-blue-900 sm:w-auto sm:text-sm">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4" />
                </svg>
                Thêm mới
            </a>

        </div>

        {{-- Thống kê --}}
        <section class="mb-8 grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">

            {{-- Tổng số phòng --}}
            <div
                class="flex items-center gap-3 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md dark:border-slate-700 dark:bg-slate-800 sm:gap-4 sm:p-5">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-blue-600 dark:bg-blue-500/15 dark:text-blue-400 sm:h-14 sm:w-14">
                    <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 10.5 12 3l9 7.5M5 9.5V21h14V9.5M9 21v-6h6v6" />
                    </svg>
                </div>

                <div>
                    <p class="text-xs font-medium text-slate-500 dark:text-slate-400 sm:text-sm">Tổng số phòng</p>
                    <p class="mt-1 text-xl font-bold text-slate-900 dark:text-white sm:text-2xl">
                        {{ number_format($statistics['total'], 0, ',', '.') }}
                    </p>
                </div>
            </div>

            {{-- Còn trống --}}
            <div
                class="flex items-center gap-3 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md dark:border-slate-700 dark:bg-slate-800 sm:gap-4 sm:p-5">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600 dark:bg-emerald-500/15 dark:text-emerald-400 sm:h-14 sm:w-14">
                    <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                </div>

                <div>
                    <p class="text-xs font-medium text-slate-500 dark:text-slate-400 sm:text-sm">Còn trống</p>
                    <p class="mt-1 text-xl font-bold text-slate-900 dark:text-white sm:text-2xl">
                        {{ number_format($statistics['available'], 0, ',', '.') }}
                    </p>
                </div>
            </div>

            {{-- Bảo trì --}}
            <div
                class="flex items-center gap-3 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md dark:border-slate-700 dark:bg-slate-800 sm:gap-4 sm:p-5">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-amber-50 text-amber-600 dark:bg-amber-500/15 dark:text-amber-400 sm:h-14 sm:w-14">
                    <svg viewBox="0 0 24 24" class="h-7 w-7" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z" />
                    </svg>
                </div>

                <div>
                    <p class="text-xs font-medium text-slate-500 dark:text-slate-400 sm:text-sm">Bảo trì</p>
                    <p class="mt-1 text-xl font-bold text-slate-900 dark:text-white sm:text-2xl">
                        {{ number_format($statistics['maintenance'], 0, ',', '.') }}
                    </p>
                </div>
            </div>

            {{-- Ngừng hoạt động --}}
            <div
                class="flex items-center gap-3 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md dark:border-slate-700 dark:bg-slate-800 sm:gap-4 sm:p-5">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-rose-50 text-rose-600 dark:bg-rose-500/15 dark:text-rose-400 sm:h-14 sm:w-14">
                    <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M18.364 5.636a9 9 0 1 1-12.728 0M12 3v9" />
                    </svg>
                </div>

                <div>
                    <p class="text-xs font-medium text-slate-500 dark:text-slate-400 sm:text-sm">Ngừng hoạt động</p>
                    <p class="mt-1 text-xl font-bold text-slate-900 dark:text-white sm:text-2xl">
                        {{ number_format($statistics['inactive'], 0, ',', '.') }}
                    </p>
                </div>
            </div>

        </section>

        {{-- Danh sách phòng --}}
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-800">

            {{-- Bộ lọc --}}
            <div class="border-b border-slate-200 bg-slate-50/70 p-4 dark:border-slate-700 dark:bg-slate-900/50 sm:p-5">
                <form method="GET" action="{{ route('admin.rooms.index') }}" class="grid gap-4 sm:grid-cols-2 lg:grid-cols-12">

                    {{-- Tìm kiếm --}}
                    <div class="sm:col-span-2 lg:col-span-6">
                        <label for="search" class="mb-2 block text-xs font-semibold text-slate-700 dark:text-slate-200 sm:text-sm">
                            Tìm kiếm
                        </label>

                        <div class="relative">
                            <span
                                class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="m21 21-4.35-4.35M16.65 11A5.65 5.65 0 1 1 11 5.35 5.65 5.65 0 0 1 16.65 11Z" />
                                </svg>
                            </span>

                            <input id="search" name="search" type="search" value="{{ request('search') }}"
                                placeholder="Tên phòng, mã phòng, loại phòng, Homestay..."
                                class="h-11 w-full rounded-xl border border-slate-300 bg-white pl-11 pr-4 text-xs text-slate-900 shadow-sm outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100 dark:placeholder:text-slate-500 dark:focus:border-blue-400 dark:focus:ring-blue-900 sm:text-sm"
                                onsearch="this.form.submit()" oninput="if(this.value === '') this.form.submit()">
                        </div>
                    </div>

                    {{-- Trạng thái --}}
                    <div class="sm:col-span-1 lg:col-span-2">
                        <label for="status" class="mb-2 block text-xs font-semibold text-slate-700 dark:text-slate-200 sm:text-sm">
                            Trạng thái
                        </label>

                        <select id="status" name="status"
                            class="h-11 w-full cursor-pointer rounded-xl border border-slate-300 bg-white px-4 text-xs text-slate-900 shadow-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100 dark:focus:border-blue-400 dark:focus:ring-blue-900 sm:text-sm">
                            <option value="">Tất cả</option>
                            <option value="available" @selected(request('status') === 'available')>
                                Còn trống
                            </option>
                            <option value="maintenance" @selected(request('status') === 'maintenance')>
                                Bảo trì
                            </option>
                            <option value="inactive" @selected(request('status') === 'inactive')>
                                Ngừng hoạt động
                            </option>
                        </select>
                    </div>

                    {{-- Sắp xếp --}}
                    <div class="sm:col-span-1 lg:col-span-2">
                        <label for="sort" class="mb-2 block text-xs font-semibold text-slate-700 dark:text-slate-200 sm:text-sm">
                            Sắp xếp giá
                        </label>

                        <select id="sort" name="sort"
                            class="h-11 w-full cursor-pointer rounded-xl border border-slate-300 bg-white px-4 text-xs text-slate-900 shadow-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100 dark:focus:border-blue-400 dark:focus:ring-blue-900 sm:text-sm">
                            <option value="">Mới nhất</option>
                            <option value="price_desc" @selected(request('sort') === 'price_desc')>
                                Giá cao đến thấp
                            </option>
                            <option value="price_asc" @selected(request('sort') === 'price_asc')>
                                Giá thấp đến cao
                            </option>
                        </select>
                    </div>

                    <div class="flex items-end gap-3 lg:col-span-2">
                        @if (request()->hasAny(['search', 'status', 'sort']))
                            <a href="{{ route('admin.rooms.index') }}"
                                title="Xóa bộ lọc"
                                class="inline-flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-blue-600 text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-200 dark:focus:ring-blue-900/40">

                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                            </a>
                        @else
                            <button type="button"
                                disabled
                                title="Chưa có bộ lọc"
                                class="inline-flex h-11 w-11 shrink-0 cursor-not-allowed items-center justify-center rounded-xl border border-slate-200 bg-slate-100 text-slate-400 dark:border-slate-700 dark:bg-slate-700 dark:text-slate-500">

                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                            </button>
                        @endif

                        <button type="submit"
                            class="inline-flex h-11 w-full cursor-pointer items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-200 dark:focus:ring-blue-900/40">

                            <svg class="h-5 w-5 shrink-0" xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M4 5h16"></path>
                                <path d="M7 12h10"></path>
                                <path d="M10 19h4"></path>
                            </svg>

                            Lọc
                        </button>
                    </div>

                </form>
            </div>

            <div class="overflow-x-auto">

                <table class="min-h-120 w-full divide-y divide-slate-200 dark:divide-slate-700">

                    <thead class="bg-slate-50 dark:bg-slate-900/70">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                                Phòng
                            </th>

                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                                Homestay
                            </th>

                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                                Loại phòng
                            </th>

                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                                Giá mỗi đêm
                            </th>

                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                                Trạng thái
                            </th>

                            <th class="px-6 py-4 text-center text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                                Thao tác
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100 bg-white dark:divide-slate-700 dark:bg-slate-800">

                        @forelse ($rooms as $room)

                            <tr class="transition hover:bg-slate-50 dark:hover:bg-slate-700/50">

                                {{-- Phòng --}}
                                <td class="px-6 py-5">
                                    <div class="flex min-w-56 items-center gap-4">

                                        @if ($room->image)
                                            <img
                                                src="{{ asset('storage/' . $room->image) }}"
                                                alt="{{ $room->name }}"
                                                class="h-14 w-20 shrink-0 rounded-xl object-cover"
                                            >
                                        @else
                                            <div class="flex h-14 w-20 shrink-0 items-center justify-center rounded-xl bg-slate-100 text-2xl dark:bg-slate-700">
                                                🚪
                                            </div>
                                        @endif

                                        <div class="min-w-0">
                                            <p class="font-semibold text-slate-900 dark:text-slate-100">
                                                {{ $room->name }}
                                            </p>

                                            <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
                                                Mã: {{ $room->room_code }}
                                            </p>
                                        </div>

                                    </div>
                                </td>

                                {{-- Homestay --}}
                                <td class="whitespace-nowrap px-6 py-5 text-sm text-slate-600 dark:text-slate-300">
                                    {{ $room->homestay?->name ?? 'Không xác định' }}
                                </td>

                                {{-- Loại phòng --}}
                                <td class="whitespace-nowrap px-6 py-5">
                                    <span class="rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700 dark:bg-blue-500/15 dark:text-blue-300">
                                        {{ $room->room_type }}
                                    </span>
                                </td>

                                {{-- Giá --}}
                                <td class="whitespace-nowrap px-6 py-5">
                                    <p class="font-bold text-slate-900 dark:text-slate-100">
                                        {{ number_format($room->price_per_night, 0, ',', '.') }} VNĐ
                                    </p>

                                    <p class="mt-1 text-xs text-slate-400 dark:text-slate-500">
                                        mỗi đêm
                                    </p>
                                </td>

                                {{-- Trạng thái --}}
                                <td class="whitespace-nowrap px-6 py-5">

                                    @switch($room->status)

                                        @case('available')
                                            <span class="inline-flex items-center gap-2 rounded-full border border-emerald-200 bg-emerald-50 px-4 py-1.5 text-xs font-semibold text-emerald-700 dark:border-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-300">
                                                <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                                                Còn trống
                                            </span>
                                            @break

                                        @case('maintenance')
                                            <span class="inline-flex items-center gap-2 rounded-full border border-amber-200 bg-amber-50 px-4 py-1.5 text-xs font-semibold text-amber-700 dark:border-amber-700 dark:bg-amber-500/10 dark:text-amber-300">
                                                <span class="h-2 w-2 rounded-full bg-amber-500"></span>
                                                Bảo trì
                                            </span>
                                            @break

                                        @default
                                            <span class="inline-flex items-center gap-2 rounded-full border border-red-200 bg-red-50 px-4 py-1.5 text-xs font-semibold text-red-700 dark:border-red-700 dark:bg-red-500/10 dark:text-red-300">
                                                <span class="h-2 w-2 rounded-full bg-red-500"></span>
                                                Ngừng hoạt động
                                            </span>

                                    @endswitch

                                </td>

                                {{-- Thao tác --}}
                                <td class="whitespace-nowrap px-6 py-5">
                                    <div
                                        x-data="{
                                            id: 'room-{{ $room->id }}',
                                            open: false,
                                            top: 0,
                                            left: 0,

                                            toggle() {
                                                if (this.open) {
                                                    this.open = false;
                                                    return;
                                                }

                                                const rect = this.$refs.trigger.getBoundingClientRect();

                                                // w-44 = 176px
                                                const menuWidth = 176;

                                                // Luôn mở xuống dưới
                                                this.top = rect.bottom + 8;

                                                // Canh phải menu theo nút 3 chấm
                                                this.left = rect.right - menuWidth;

                                                // Không để tràn mép trái màn hình
                                                if (this.left < 8) {
                                                    this.left = 8;
                                                }

                                                // Đóng tất cả menu thao tác khác
                                                window.dispatchEvent(
                                                    new CustomEvent('action-menu-open', {
                                                        detail: {
                                                            id: this.id
                                                        }
                                                    })
                                                );

                                                this.open = true;
                                            },

                                            close() {
                                                this.open = false;
                                            }
                                        }"

                                        data-action-menu

                                        class="flex justify-center"

                                        @action-menu-open.window="
                                            if ($event.detail.id != id) {
                                                open = false
                                            }
                                        "

                                        @keydown.escape.window="close()"
                                        @resize.window="close()"
                                        @scroll.window="close()"
                                    >

                                        {{-- Nút ba chấm --}}
                                        <button
                                            x-ref="trigger"
                                            type="button"
                                            @click.stop="toggle()"

                                            class="inline-flex h-10 w-10 cursor-pointer items-center justify-center
                                                rounded-xl text-slate-500 transition
                                                hover:bg-slate-100 hover:text-slate-700
                                                focus:outline-none focus:ring-4 focus:ring-blue-100
                                                dark:text-slate-400
                                                dark:hover:bg-slate-700
                                                dark:hover:text-slate-200
                                                dark:focus:ring-blue-900/30"

                                            title="Thao tác"
                                            aria-label="Thao tác"
                                            :aria-expanded="open"
                                        >
                                            <svg
                                                viewBox="0 0 20 20"
                                                fill="currentColor"
                                                class="h-5 w-5"
                                                aria-hidden="true"
                                            >
                                                <path
                                                    d="M10 6a2 2 0 110-4 2 2 0 010 4z
                                                    M10 12a2 2 0 110-4 2 2 0 010 4z
                                                    M10 18a2 2 0 110-4 2 2 0 010 4z"
                                                />
                                            </svg>
                                        </button>


                                        {{-- Menu thao tác --}}
                                        <template x-teleport="body">
                                            <div
                                                x-show="open"
                                                x-cloak

                                                @click.outside="close()"

                                                x-transition:enter="transition ease-out duration-150"
                                                x-transition:enter-start="opacity-0 scale-95"
                                                x-transition:enter-end="opacity-100 scale-100"

                                                x-transition:leave="transition ease-in duration-100"
                                                x-transition:leave-start="opacity-100 scale-100"
                                                x-transition:leave-end="opacity-0 scale-95"

                                                :style="`
                                                    position: fixed;
                                                    top: ${top}px;
                                                    left: ${left}px;
                                                    width: 176px;
                                                `"

                                                class="z-[9999] origin-top-right overflow-hidden
                                                    rounded-2xl border border-slate-200/80 bg-white
                                                    shadow-xl shadow-slate-200/50 ring-1 ring-black/5
                                                    dark:border-slate-700/80
                                                    dark:bg-slate-800
                                                    dark:shadow-black/40"
                                            >

                                                {{-- Xem --}}
                                                <a
                                                    href="{{ route('admin.rooms.show', $room) }}"

                                                    class="group flex items-center gap-3 px-3.5 py-2.5
                                                        text-sm font-medium text-slate-700
                                                        transition-colors
                                                        hover:bg-slate-50
                                                        focus:bg-slate-50 focus:outline-none
                                                        dark:text-slate-200
                                                        dark:hover:bg-slate-700/60
                                                        dark:focus:bg-slate-700/60"
                                                >
                                                    <span
                                                        class="flex h-8 w-8 shrink-0 items-center justify-center
                                                            rounded-xl bg-slate-100 text-slate-500
                                                            transition-colors
                                                            group-hover:bg-slate-200/80
                                                            group-hover:text-slate-700
                                                            dark:bg-slate-700/70
                                                            dark:text-slate-400
                                                            dark:group-hover:bg-slate-600
                                                            dark:group-hover:text-slate-200"
                                                    >
                                                        <svg
                                                            xmlns="http://www.w3.org/2000/svg"
                                                            viewBox="0 0 24 24"
                                                            fill="none"
                                                            stroke="currentColor"
                                                            stroke-width="1.75"
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            class="h-4 w-4"
                                                        >
                                                            <path
                                                                d="M2.5 12s3.5-6.5 9.5-6.5
                                                                9.5 6.5 9.5 6.5
                                                                -3.5 6.5-9.5 6.5
                                                                S2.5 12 2.5 12Z"
                                                            />
                                                            <circle cx="12" cy="12" r="2.5" />
                                                        </svg>
                                                    </span>

                                                    <span>Xem</span>
                                                </a>


                                                {{-- Sửa --}}
                                                <a
                                                    href="{{ route('admin.rooms.edit', $room) }}"

                                                    class="group flex items-center gap-3 px-3.5 py-2.5
                                                        text-sm font-medium text-amber-600
                                                        transition-colors
                                                        hover:bg-amber-50
                                                        focus:bg-amber-50 focus:outline-none
                                                        dark:text-amber-400
                                                        dark:hover:bg-amber-950/50
                                                        dark:focus:bg-amber-950/50"
                                                >
                                                    <span
                                                        class="flex h-8 w-8 shrink-0 items-center justify-center
                                                            rounded-xl bg-amber-50 text-amber-500
                                                            transition-colors
                                                            group-hover:bg-amber-100
                                                            dark:bg-amber-950/50
                                                            dark:text-amber-400
                                                            dark:group-hover:bg-amber-950/70"
                                                    >
                                                        <svg
                                                            xmlns="http://www.w3.org/2000/svg"
                                                            viewBox="0 0 24 24"
                                                            fill="none"
                                                            stroke="currentColor"
                                                            stroke-width="1.75"
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            class="h-4 w-4"
                                                        >
                                                            <path d="M12 20h9" />

                                                            <path
                                                                d="M16.5 3.5a2.12 2.12 0 0 1 3 3
                                                                L7 19l-4 1 1-4Z"
                                                            />
                                                        </svg>
                                                    </span>

                                                    <span>Sửa</span>
                                                </a>


                                                {{-- Đường ngăn --}}
                                                <div
                                                    class="mx-3 border-t border-slate-100
                                                        dark:border-slate-700/80"
                                                ></div>


                                                {{-- Xóa --}}
                                                <form
                                                    action="{{ route('admin.rooms.destroy', $room) }}"
                                                    method="POST"

                                                    onsubmit="return confirm(
                                                        'Bạn có chắc muốn xóa phòng {{ addslashes($room->name) }} không?\nHành động này không thể hoàn tác.'
                                                    )"
                                                >
                                                    @csrf
                                                    @method('DELETE')

                                                    <button
                                                        type="submit"

                                                        class="group flex w-full cursor-pointer items-center gap-3
                                                            px-3.5 py-2.5
                                                            text-left text-sm font-medium text-red-600
                                                            transition-colors
                                                            hover:bg-red-50
                                                            focus:bg-red-50 focus:outline-none
                                                            dark:text-red-400
                                                            dark:hover:bg-red-950/50
                                                            dark:focus:bg-red-950/50"
                                                    >
                                                        <span
                                                            class="flex h-8 w-8 shrink-0 items-center justify-center
                                                                rounded-xl bg-red-50 text-red-500
                                                                transition-colors
                                                                group-hover:bg-red-100
                                                                dark:bg-red-950/60
                                                                dark:text-red-400
                                                                dark:group-hover:bg-red-950/80"
                                                        >
                                                            <svg
                                                                xmlns="http://www.w3.org/2000/svg"
                                                                viewBox="0 0 24 24"
                                                                fill="none"
                                                                stroke="currentColor"
                                                                stroke-width="1.75"
                                                                stroke-linecap="round"
                                                                stroke-linejoin="round"
                                                                class="h-4 w-4"
                                                            >
                                                                <path d="M3 6h18" />

                                                                <path
                                                                    d="M8 6V4a2 2 0 0 1 2-2h4
                                                                    a2 2 0 0 1 2 2v2"
                                                                />

                                                                <path
                                                                    d="M19 6v14a2 2 0 0 1-2 2H7
                                                                    a2 2 0 0 1-2-2V6"
                                                                />

                                                                <line
                                                                    x1="10"
                                                                    y1="11"
                                                                    x2="10"
                                                                    y2="17"
                                                                />

                                                                <line
                                                                    x1="14"
                                                                    y1="11"
                                                                    x2="14"
                                                                    y2="17"
                                                                />
                                                            </svg>
                                                        </span>

                                                        <span>Xóa</span>
                                                    </button>
                                                </form>

                                            </div>
                                        </template>
                                    </div>
                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td
                                    colspan="6"
                                    class="px-6 py-16 text-center"
                                >
                                    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-slate-100 text-3xl dark:bg-slate-700">
                                        🚪
                                    </div>

                                    <p class="mt-4 font-semibold text-slate-700 dark:text-slate-200">
                                        Chưa có phòng
                                    </p>

                                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                                        Chưa tìm thấy phòng phù hợp trong hệ thống.
                                    </p>

                                    @if (request()->hasAny(['search', 'status', 'sort']))
                                        <a href="{{ route('admin.rooms.index') }}"
                                            class="mt-5 inline-flex h-11 items-center justify-center rounded-xl bg-blue-600 px-5 text-xs font-semibold text-white transition hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-200 dark:focus:ring-blue-900 sm:text-sm">
                                            Xóa bộ lọc
                                        </a>
                                    @else
                                        <a href="{{ route('admin.rooms.create') }}"
                                            class="mt-5 inline-flex h-11 items-center justify-center rounded-xl bg-blue-600 px-5 text-xs font-semibold text-white transition hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-200 dark:focus:ring-blue-900 sm:text-sm">
                                            Thêm phòng mới
                                        </a>
                                    @endif
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

            @if ($rooms->hasPages())
                <div class="border-t border-slate-200 px-4 py-5 dark:border-slate-700 sm:px-6">
                    {{ $rooms->onEachSide(1)->links('components.pagination', [
                        'layout' => 'row',
                        'showInfo' => true,
                    ]) }}
                </div>
            @endif

        </div>

    </div>
@endsection