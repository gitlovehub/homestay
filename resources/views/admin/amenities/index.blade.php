@extends('layouts.admin')

@section('title', 'Quản lý tiện ích | HomeStayGo')

@section('page-title', 'Quản lý tiện ích')

@section('content')
    <div class="mx-auto max-w-screen-2xl">

        <x-alert />

        <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">

            <h2 class="text-lg font-semibold text-slate-900 dark:text-slate-100 md:text-2xl">
                Quản lý các tiện ích được sử dụng trong hệ thống Homestay.
            </h2>

            <a href="{{ route('admin.amenities.create') }}"
                class="inline-flex h-11 w-full items-center justify-center gap-2 rounded-xl bg-blue-600 px-5 text-sm font-semibold text-white transition hover:bg-blue-700 sm:w-auto">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                        d="M12 4v16m8-8H4" />
                </svg>
                Thêm mới
            </a>

        </div>

        {{-- Thống kê --}}
        <section class="mb-8 grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">

            {{-- Tổng tiện ích --}}
            <div
                class="flex items-center gap-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md dark:border-slate-700 dark:bg-slate-800">
                <div
                    class="flex h-14 w-14 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-blue-600 dark:bg-blue-950/40 dark:text-blue-400">
                    <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M5 3a2 2 0 00-2 2v4a2 2 0 002 2h4a2 2 0 002-2V5a2 2 0 00-2-2H5zm10 0a2 2 0 00-2 2v4a2 2 0 002 2h4a2 2 0 002-2V5a2 2 0 00-2-2h-4zM5 13a2 2 0 00-2 2v4a2 2 0 002 2h4a2 2 0 002-2v-4a2 2 0 00-2-2H5zm10 0a2 2 0 00-2 2v4a2 2 0 002 2h4a2 2 0 002-2v-4a2 2 0 00-2-2h-4z" />
                    </svg>
                </div>

                <div>
                    <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Tổng tiện ích</p>
                    <p class="mt-1 text-2xl font-bold text-slate-900 dark:text-slate-100">
                        {{ number_format($statistics['total'], 0, ',', '.') }}
                    </p>
                </div>
            </div>

            {{-- Đang hoạt động --}}
            <div
                class="flex items-center gap-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md dark:border-slate-700 dark:bg-slate-800">
                <div
                    class="flex h-14 w-14 shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600 dark:bg-emerald-950/40 dark:text-emerald-400">
                    <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>

                <div>
                    <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Đang hoạt động</p>
                    <p class="mt-1 text-2xl font-bold text-slate-900 dark:text-slate-100">
                        {{ number_format($statistics['active'], 0, ',', '.') }}
                    </p>
                </div>
            </div>

            {{-- Tạm khóa --}}
            <div
                class="flex items-center gap-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md dark:border-slate-700 dark:bg-slate-800">
                <div
                    class="flex h-14 w-14 shrink-0 items-center justify-center rounded-xl bg-rose-50 text-rose-600 dark:bg-rose-950/40 dark:text-rose-400">
                    <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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

            {{-- Đang được sử dụng --}}
            <div
                class="flex items-center gap-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md dark:border-slate-700 dark:bg-slate-800">
                <div
                    class="flex h-14 w-14 shrink-0 items-center justify-center rounded-xl bg-violet-50 text-violet-600 dark:bg-violet-950/40 dark:text-violet-400">
                    <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 21h18M5 21V7l7-4 7 4v14M9 21v-6h6v6M9 9h.01M15 9h.01M9 12h.01M15 12h.01" />
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

        {{-- Danh sách tiện ích --}}
        <section
            class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-800">

            {{-- Bộ lọc --}}
            <div class="border-b border-slate-200 bg-slate-50/70 p-5 dark:border-slate-700 dark:bg-slate-900/50">

                <form method="GET" action="{{ route('admin.amenities.index') }}"
                    class="grid gap-4 lg:grid-cols-12">

                    {{-- Tìm kiếm --}}
                    <div class="lg:col-span-5">
                        <label for="search"
                            class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-300">
                            Tìm kiếm
                        </label>

                        <div class="relative">
                            <span
                                class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400 dark:text-slate-500">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="m21 21-4.35-4.35M16.65 11A5.65 5.65 0 1 1 11 5.35 5.65 5.65 0 0 1 16.65 11Z" />
                                </svg>
                            </span>

                            <input id="search" name="search" type="search" value="{{ request('search') }}"
                                placeholder="Tên tiện ích, slug hoặc mô tả..."
                                class="h-11 w-full rounded-xl border border-slate-300 bg-white pl-11 pr-4 text-sm text-slate-900 shadow-sm outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100 dark:placeholder:text-slate-500 dark:focus:border-blue-400 dark:focus:ring-blue-900/40"
                                onsearch="this.form.submit()" oninput="if(this.value === '') this.form.submit()">
                        </div>
                    </div>

                    {{-- Trạng thái --}}
                    <div class="lg:col-span-2">
                        <label for="status"
                            class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-300">
                            Trạng thái
                        </label>

                        <select id="status" name="status"
                            class="h-11 w-full cursor-pointer rounded-xl border border-slate-300 bg-white px-4 text-sm text-slate-900 shadow-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100 dark:focus:border-blue-400 dark:focus:ring-blue-900/40">
                            <option value="">Tất cả</option>
                            <option value="1" @selected(request('status') === '1')>Đang hoạt động</option>
                            <option value="0" @selected(request('status') === '0')>Tạm khóa</option>
                        </select>
                    </div>

                    {{-- Sắp xếp --}}
                    <div class="lg:col-span-3">
                        <label for="sort"
                            class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-300">
                            Sắp xếp
                        </label>

                        <select id="sort" name="sort"
                            class="h-11 w-full cursor-pointer rounded-xl border border-slate-300 bg-white px-4 text-sm text-slate-900 shadow-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100 dark:focus:border-blue-400 dark:focus:ring-blue-900/40">
                            <option value="newest" @selected(request('sort', 'newest') === 'newest')>Mới nhất</option>
                            <option value="oldest" @selected(request('sort') === 'oldest')>Cũ nhất</option>
                            <option value="most_used" @selected(request('sort') === 'most_used')>Được dùng nhiều nhất</option>
                            <option value="least_used" @selected(request('sort') === 'least_used')>Được dùng ít nhất</option>
                            <option value="name_asc" @selected(request('sort') === 'name_asc')>Tên A đến Z</option>
                            <option value="name_desc" @selected(request('sort') === 'name_desc')>Tên Z đến A</option>
                        </select>
                    </div>

                    {{-- Reset --}}
                    <div class="flex items-end lg:col-span-1">
                        @if (request()->hasAny(['search', 'status', 'sort']))
                            <a href="{{ route('admin.amenities.index') }}" title="Đặt lại bộ lọc"
                                class="inline-flex h-11 w-full items-center justify-center rounded-xl border border-slate-300 bg-white px-4 text-sm font-semibold text-slate-600 shadow-sm transition hover:bg-slate-50 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-300 dark:hover:bg-slate-700">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 0 0 4.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 0 1-15.357-2m15.357 2H15" />
                                </svg>
                            </a>
                        @else
                            <button type="button" disabled title="Chưa có bộ lọc cần đặt lại"
                                class="inline-flex h-11 w-full cursor-not-allowed items-center justify-center rounded-xl border border-slate-200 bg-slate-100 px-4 text-sm font-semibold text-slate-400 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-600">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 0 0 4.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 0 1-15.357-2m15.357 2H15" />
                                </svg>
                            </button>
                        @endif
                    </div>

                    {{-- Nút lọc --}}
                    <div class="flex items-end lg:col-span-1">
                        <button type="submit"
                            class="inline-flex h-11 w-full cursor-pointer items-center justify-center rounded-xl bg-blue-600 px-4 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700">
                            Lọc
                        </button>
                    </div>

                </form>
            </div>

            @if ($amenities->count())

                {{-- Bảng --}}
                <div class="overflow-x-auto">

                    <table class="w-full min-h-120 border-collapse text-left">

                        <thead>
                            <tr
                                class="border-b border-slate-200 bg-slate-50/70 text-xs font-bold uppercase tracking-wider text-slate-500 dark:border-slate-700 dark:bg-slate-900/70 dark:text-slate-400">

                                <th class="px-6 py-4">
                                    Tiện ích
                                </th>

                                <th class="px-6 py-4">
                                    Slug
                                </th>

                                <th class="px-6 py-4 text-center">
                                    Homestay sử dụng
                                </th>

                                <th class="px-6 py-4">
                                    Trạng thái
                                </th>

                                <th class="px-6 py-4">
                                    Ngày tạo
                                </th>

                                <th class="px-6 py-4 text-right">
                                    Thao tác
                                </th>

                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-200 bg-white text-sm dark:divide-slate-700 dark:bg-slate-800">

                            @foreach ($amenities as $amenity)
                                <tr class="transition hover:bg-slate-50/80 dark:hover:bg-slate-700/50">

                                    {{-- Tiện ích --}}
                                    <td class="whitespace-nowrap px-6 py-5">

                                        <div class="flex items-center gap-3">

                                            <div
                                                class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl border border-blue-100 bg-blue-50 text-xl text-blue-600 dark:border-blue-900/60 dark:bg-blue-950/40 dark:text-blue-400">
                                                {{ $amenity->icon ?: '✨' }}
                                            </div>

                                            <div class="min-w-0">

                                                <p class="max-w-[180px] truncate font-semibold text-slate-900 dark:text-slate-100"
                                                    title="{{ $amenity->name }}">
                                                    {{ $amenity->name }}
                                                </p>

                                                <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
                                                    {{ $amenity->description ?: 'Chưa có mô tả.' }}
                                                </p>

                                            </div>

                                        </div>

                                    </td>

                                    {{-- Slug --}}
                                    <td class="px-6 py-5">

                                        <span
                                            class="inline-flex max-w-[180px] truncate rounded-lg bg-slate-100 px-3 py-1.5 text-xs font-medium text-slate-600 dark:bg-slate-700 dark:text-slate-300"
                                            title="{{ $amenity->slug }}">
                                            {{ $amenity->slug }}
                                        </span>

                                    </td>

                                    {{-- Số Homestay --}}
                                    <td class="whitespace-nowrap px-6 py-5 text-center">

                                        <span
                                            class="inline-flex min-w-10 items-center justify-center rounded-full border border-violet-100 bg-violet-100 px-3 py-1.5 text-xs font-semibold text-violet-700 dark:border-violet-900/60 dark:bg-violet-950/40 dark:text-violet-300">
                                            {{ number_format($amenity->homestays_count, 0, ',', '.') }}
                                        </span>

                                    </td>

                                    {{-- Trạng thái --}}
                                    <td class="whitespace-nowrap px-6 py-5">

                                        @if ($amenity->status)
                                            <span
                                                class="inline-flex items-center gap-2 rounded-full border border-emerald-200 bg-emerald-50 px-4 py-1.5 text-xs font-semibold text-emerald-700 dark:border-emerald-900/60 dark:bg-emerald-950/40 dark:text-emerald-300">
                                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-600"></span>
                                                Đang hoạt động
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-slate-100 px-4 py-1.5 text-xs font-semibold text-slate-600 dark:border-red-900/60 dark:bg-red-950/40 dark:text-red-300">
                                                <span class="h-1.5 w-1.5 rounded-full bg-slate-600"></span>
                                                Ngừng hoạt động
                                            </span>
                                        @endif

                                    </td>

                                    {{-- Ngày tạo --}}
                                    <td class="whitespace-nowrap px-6 py-5">

                                        <p class="font-semibold text-slate-800 dark:text-slate-100">
                                            {{ $amenity->created_at->format('d/m/Y') }}
                                        </p>

                                        <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
                                            {{ $amenity->created_at->format('H:i') }}
                                        </p>

                                    </td>

                                    {{-- Thao tác --}}
                                    <td class="whitespace-nowrap px-6 py-5 text-right">

                                        <details data-action-menu class="relative inline-block text-left">

                                            <summary
                                                class="flex h-9 w-9 cursor-pointer list-none items-center justify-center rounded-lg border border-slate-300 bg-white text-lg font-bold text-slate-500 shadow-sm transition hover:bg-slate-50 hover:text-slate-700 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-400 dark:hover:bg-slate-700 dark:hover:text-slate-100">
                                                ⋮
                                            </summary>

                                            <div
                                                class="absolute right-0 z-40 mt-2 w-35 overflow-hidden rounded-xl border border-slate-200 bg-white text-left shadow-xl dark:border-slate-700 dark:bg-slate-800">

                                                {{-- Xem --}}
                                                <a href="{{ route('admin.amenities.show', $amenity) }}"
                                                    class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-blue-700 transition hover:bg-blue-50 dark:text-blue-400 dark:hover:bg-blue-950/40">
                                                    <svg viewBox="0 0 24 24" class="h-4 w-4" fill="none"
                                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                        stroke-linejoin="round">
                                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                                                        <circle cx="12" cy="12" r="3" />
                                                    </svg>
                                                    Xem
                                                </a>

                                                {{-- Sửa --}}
                                                <a href="{{ route('admin.amenities.edit', $amenity) }}"
                                                    class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-amber-500 transition hover:bg-amber-50 dark:text-amber-400 dark:hover:bg-amber-950/40">
                                                    <svg viewBox="0 0 24 24" class="h-4 w-4" fill="none"
                                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                        stroke-linejoin="round">
                                                        <path
                                                            d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                                                    </svg>
                                                    Sửa
                                                </a>

                                                {{-- Xóa --}}
                                                <form method="POST"
                                                    action="{{ route('admin.amenities.destroy', $amenity) }}">
                                                    @csrf
                                                    @method('DELETE')

                                                    <button type="submit"
                                                        onclick="return confirm('Bạn có chắc chắn muốn xóa tiện ích {{ addslashes($amenity->name) }} không?')"
                                                        class="flex w-full cursor-pointer items-center gap-3 px-4 py-2.5 text-left text-sm font-medium text-red-600 transition hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-950/40">
                                                        <svg viewBox="0 0 24 24" class="h-4 w-4" fill="none"
                                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round">
                                                            <polyline points="3 6 5 6 21 6" />
                                                            <path
                                                                d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2" />
                                                        </svg>
                                                        Xóa
                                                    </button>

                                                </form>

                                            </div>

                                        </details>

                                    </td>

                                </tr>
                            @endforeach

                        </tbody>

                    </table>

                </div>

                {{-- Phân trang --}}
                <div class="border-t border-slate-200 px-6 py-5 dark:border-slate-700">
                    {{ $amenities->onEachSide(1)->links('components.pagination', [
                        'layout' => 'row',
                        'showInfo' => true,
                    ]) }}
                </div>
            @else
                {{-- Không có dữ liệu --}}
                <div class="px-6 py-20 text-center">

                    <div
                        class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-slate-100 text-3xl text-slate-400 dark:bg-slate-700 dark:text-slate-300">
                        ✨
                    </div>

                    <h2 class="mt-5 text-lg font-bold text-slate-900 dark:text-slate-100">

                        @if (request()->hasAny(['search', 'status', 'sort']))
                            Không tìm thấy tiện ích phù hợp
                        @else
                            Chưa có tiện ích
                        @endif

                    </h2>

                    <p class="mx-auto mt-2 text-sm leading-6 text-slate-500 dark:text-slate-400">

                        @if (request()->hasAny(['search', 'status', 'sort']))
                            Không có tiện ích nào phù hợp với nội dung tìm kiếm hoặc bộ lọc hiện tại.
                        @else
                            Hệ thống hiện chưa có tiện ích nào. Hãy thêm tiện ích đầu tiên.
                        @endif

                    </p>

                    @if (request()->hasAny(['search', 'status', 'sort']))
                        <a href="{{ route('admin.amenities.index') }}"
                            class="mt-5 inline-flex h-11 items-center justify-center rounded-xl bg-blue-600 px-5 text-sm font-semibold text-white transition hover:bg-blue-700">
                            Xóa bộ lọc
                        </a>
                    @else
                        <a href="{{ route('admin.amenities.create') }}"
                            class="mt-5 inline-flex h-11 items-center justify-center gap-2 rounded-xl bg-blue-600 px-5 text-sm font-semibold text-white transition hover:bg-blue-700">
                            <span class="text-lg">+</span>
                            Thêm tiện ích
                        </a>
                    @endif

                </div>

            @endif


        </section>

    </div>
@endsection