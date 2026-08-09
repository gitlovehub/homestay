@extends('layouts.admin')

@section('title', 'Quản lý danh mục | HomeStayGo')

@section('page-title', 'Quản lý danh mục')

@section('content')
    <div class="mx-auto max-w-screen-2xl">

        <x-alert />

        <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">

            <h2 class="text-lg font-semibold text-slate-900 dark:text-slate-100 md:text-2xl">
                Danh sách tất cả Danh mục trong hệ thống.
            </h2>

            <a href="{{ route('admin.categories.create') }}"
                class="inline-flex h-11 w-full items-center justify-center gap-2 rounded-xl bg-blue-600 px-5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-200 dark:bg-blue-500 dark:hover:bg-blue-600 dark:focus:ring-blue-900/50 sm:w-auto">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4" />
                </svg>
                <span>Thêm mới</span>
            </a>

        </div>

        {{-- Thống kê --}}
        <section class="mb-8 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <div class="flex items-center gap-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-800">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-blue-600 dark:bg-blue-950/50 dark:text-blue-400">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h10" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Tổng danh mục</p>
                    <p class="mt-1 text-2xl font-bold text-slate-900 dark:text-slate-100">
                        {{ number_format($statistics['total'], 0, ',', '.') }}
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-800">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600 dark:bg-emerald-950/50 dark:text-emerald-400">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m5 12 4 4L19 6" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Đang hoạt động</p>
                    <p class="mt-1 text-2xl font-bold text-slate-900 dark:text-slate-100">
                        {{ number_format($statistics['active'], 0, ',', '.') }}
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-800">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-red-50 text-red-600 dark:bg-red-950/50 dark:text-red-400">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M18.364 5.636a9 9 0 1 1-12.728 0M12 3v9" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Tạm khóa</p>
                    <p class="mt-1 text-2xl font-bold text-slate-900 dark:text-slate-100">
                        {{ number_format($statistics['inactive'], 0, ',', '.') }}
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-800">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-violet-50 text-violet-600 dark:bg-violet-950/50 dark:text-violet-400">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21h18M5 21V7l7-4 7 4v14" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Đang được sử dụng</p>
                    <p class="mt-1 text-2xl font-bold text-slate-900 dark:text-slate-100">
                        {{ number_format($statistics['in_use'], 0, ',', '.') }}
                    </p>
                </div>
            </div>
        </section>

        <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-800">
            {{-- Bộ lọc --}}
            <div class="border-b border-slate-200 bg-slate-50/70 p-5 dark:border-slate-700 dark:bg-slate-900/40">
                <form method="GET" action="{{ route('admin.categories.index') }}"
                    class="grid gap-4 lg:grid-cols-12">

                    <div class="lg:col-span-6">
                        <label for="search" class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-300">
                            Tìm kiếm
                        </label>
                        <input id="search" name="search" type="search" value="{{ request('search') }}"
                            placeholder="Tên, slug hoặc mô tả..."
                            class="h-11 w-full rounded-xl border border-slate-300 bg-white px-4 text-sm text-slate-900 shadow-sm outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100 dark:focus:border-blue-400 dark:focus:ring-blue-900/40">
                    </div>

                    <div class="lg:col-span-2">
                        <label for="status" class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-300">
                            Trạng thái
                        </label>
                        <select id="status" name="status"
                            class="h-11 w-full cursor-pointer rounded-xl border border-slate-300 bg-white px-4 text-sm text-slate-900 shadow-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100 dark:focus:border-blue-400 dark:focus:ring-blue-900/40">
                            <option value="">Tất cả trạng thái</option>
                            <option value="1" @selected(request('status') === '1')>Đang hoạt động</option>
                            <option value="0" @selected(request('status') === '0')>Tạm khóa</option>
                        </select>
                    </div>

                    <div class="lg:col-span-2">
                        <label for="sort" class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-300">
                            Sắp xếp
                        </label>
                        <select id="sort" name="sort"
                            class="h-11 w-full cursor-pointer rounded-xl border border-slate-300 bg-white px-4 text-sm text-slate-900 shadow-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100 dark:focus:border-blue-400 dark:focus:ring-blue-900/40">
                            <option value="latest" @selected(request('sort', 'latest') === 'latest')>Mới nhất</option>
                            <option value="oldest" @selected(request('sort') === 'oldest')>Cũ nhất</option>
                            <option value="name_asc" @selected(request('sort') === 'name_asc')>Tên A–Z</option>
                            <option value="name_desc" @selected(request('sort') === 'name_desc')>Tên Z–A</option>
                            <option value="most_used" @selected(request('sort') === 'most_used')>Dùng nhiều nhất</option>
                            <option value="least_used" @selected(request('sort') === 'least_used')>Dùng ít nhất</option>
                        </select>
                    </div>

                    <div class="flex items-end gap-3 lg:col-span-2">
                        {{-- Reset filters --}}
                        @if (request()->hasAny(['search', 'status', 'sort']))
                            <a href="{{ route('admin.categories.index') }}"
                            title="Xóa bộ lọc"
                            aria-label="Xóa bộ lọc"
                            class="inline-flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-blue-600 text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-200 dark:focus:ring-blue-900/40">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                            </a>
                        @else
                            <button type="button"
                                    disabled
                                    title="Chưa có bộ lọc"
                                    aria-label="Chưa có bộ lọc"
                                    class="inline-flex h-11 w-11 shrink-0 cursor-not-allowed items-center justify-center rounded-xl border border-slate-200 bg-slate-100 text-slate-400 dark:border-slate-700 dark:bg-slate-700 dark:text-slate-500">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
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
                    <thead class="bg-slate-50 dark:bg-slate-900/50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                                Danh mục
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                                Slug
                            </th>
                            <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                                Homestay
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                                Trạng thái
                            </th>
                            <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                                Thao tác
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-200 bg-white dark:divide-slate-700 dark:bg-slate-800">
                        @forelse ($categories as $category)
                            <tr class="transition hover:bg-slate-50 dark:hover:bg-slate-700/50">
                                <td class="px-6 py-4">
                                    <p class="font-semibold text-slate-900 dark:text-slate-100">
                                        {{ $category->name }}
                                    </p>
                                    <p class="mt-1 max-w-sm truncate text-xs text-slate-500 dark:text-slate-400">
                                        {{ $category->description ?: 'Chưa có mô tả' }}
                                    </p>
                                </td>

                                <td class="whitespace-nowrap px-6 py-4">
                                    <span class="inline-flex rounded-lg bg-blue-50 px-3 py-1.5 text-xs font-semibold text-blue-700 dark:bg-blue-950/50 dark:text-blue-300">
                                        {{ $category->slug }}
                                    </span>
                                </td>

                                <td class="whitespace-nowrap px-6 py-4 text-center">
                                    <span class="inline-flex min-w-10 items-center justify-center rounded-full bg-violet-50 px-3 py-1.5 text-xs font-semibold text-violet-700 dark:bg-violet-950/50 dark:text-violet-300">
                                        {{ number_format($category->homestays_count, 0, ',', '.') }}
                                    </span>
                                </td>

                                <td class="whitespace-nowrap px-6 py-4">
                                    @if ($category->status)
                                        <span class="inline-flex items-center gap-2 rounded-full border border-emerald-200 bg-emerald-50 px-4 py-1.5 text-xs font-semibold text-emerald-700 dark:border-emerald-800 dark:bg-emerald-950/50 dark:text-emerald-300">
                                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                            Hoạt động
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-2 rounded-full border border-red-200 bg-red-50 px-4 py-1.5 text-xs font-semibold text-red-700 dark:border-red-800 dark:bg-red-950/50 dark:text-red-300">
                                            <span class="h-1.5 w-1.5 rounded-full bg-red-500"></span>
                                            Tạm khóa
                                        </span>
                                    @endif
                                </td>

                                <td class="whitespace-nowrap px-6 py-4">
                                    <div
                                        x-data="{
                                            id: '{{ $category->id }}',
                                            open: false,
                                            top: 0,
                                            left: 0,

                                            toggle() {
                                                // Nếu đang mở thì bấm lại để đóng
                                                if (this.open) {
                                                    this.open = false;
                                                    return;
                                                }

                                                const rect = this.$refs.trigger.getBoundingClientRect();
                                                const menuWidth = 176;

                                                // Luôn mở xuống dưới
                                                this.top = rect.bottom + 8;

                                                // Canh phải theo nút 3 chấm
                                                this.left = rect.right - menuWidth;

                                                // Không tràn khỏi mép trái màn hình
                                                if (this.left < 8) {
                                                    this.left = 8;
                                                }

                                                // Báo cho tất cả menu khác đóng lại
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

                                        {{-- Nút 3 chấm --}}
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

                                            aria-label="Thao tác"
                                            :aria-expanded="open"
                                        >
                                            <svg
                                                class="h-5 w-5"
                                                fill="currentColor"
                                                viewBox="0 0 20 20"
                                                aria-hidden="true"
                                            >
                                                <path
                                                    d="M10 6a2 2 0 110-4 2 2 0 010 4z
                                                    M10 12a2 2 0 110-4 2 2 0 010 4z
                                                    M10 18a2 2 0 110-4 2 2 0 010 4z"
                                                />
                                            </svg>
                                        </button>


                                        {{-- Dropdown --}}
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
                                                    href="{{ route('admin.categories.show', $category) }}"
                                                    class="group flex items-center gap-3 px-3.5 py-2.5
                                                        text-sm font-medium text-slate-700 transition-colors
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
                                                                d="M2.5 12s3.5-6.5 9.5-6.5 9.5 6.5 9.5 6.5-3.5 6.5-9.5 6.5S2.5 12 2.5 12Z"
                                                            />
                                                            <circle cx="12" cy="12" r="2.5" />
                                                        </svg>
                                                    </span>

                                                    <span>Xem</span>
                                                </a>


                                                {{-- Sửa --}}
                                                <a
                                                    href="{{ route('admin.categories.edit', $category) }}"
                                                    class="group flex items-center gap-3 px-3.5 py-2.5
                                                        text-sm font-medium text-amber-600 transition-colors
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
                                                            <path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4Z" />
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
                                                    action="{{ route('admin.categories.destroy', $category) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('Bạn có chắc chắn muốn xóa danh mục {{ addslashes($category->name) }} không?\nHành động này không thể hoàn tác.')"
                                                >
                                                    @csrf
                                                    @method('DELETE')

                                                    <button
                                                        type="submit"
                                                        class="group flex w-full cursor-pointer items-center gap-3
                                                            px-3.5 py-2.5 text-left text-sm font-medium
                                                            text-red-600 transition-colors
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
                                                                    d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"
                                                                />

                                                                <path
                                                                    d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"
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
                                <td colspan="5" class="px-6 py-14 text-center">
                                    <p class="font-semibold text-slate-700 dark:text-slate-200">
                                        Chưa tìm thấy danh mục
                                    </p>
                                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                                        Hãy thay đổi bộ lọc hoặc thêm danh mục mới.
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($categories->hasPages())
                <div class="border-t border-slate-200 px-6 py-5 dark:border-slate-700">
                    {{ $categories->onEachSide(1)->links('components.pagination', [
                        'layout' => 'row',
                        'showInfo' => true,
                    ]) }}
                </div>
            @endif
        </section>
    </div>
@endsection