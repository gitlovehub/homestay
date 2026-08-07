@extends('layouts.admin')

@section('title', 'Quản lý danh mục | HomeStayGo')

@section('page-title', 'Quản lý danh mục')

@section('content')
    <div class="mx-auto max-w-screen-2xl">

        <x-alert />

        {{-- Tiêu đề và nút thêm mới --}}
        <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">

            <h2 class="text-lg font-semibold text-slate-900 dark:text-slate-100 md:text-2xl">
                Quản lý các loại Homestay đang có trong hệ thống.
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

                    <div class="lg:col-span-5">
                        <label for="search" class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-300">
                            Tìm kiếm
                        </label>
                        <input id="search" name="search" type="search" value="{{ request('search') }}"
                            placeholder="Tên, slug hoặc mô tả..."
                            class="h-11 w-full rounded-xl border border-slate-300 bg-white px-4 text-sm text-slate-900 shadow-sm outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100 dark:focus:border-blue-400 dark:focus:ring-blue-900/40">
                    </div>

                    <div class="lg:col-span-3">
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
                        {{-- Reset --}}
                        @if (request()->hasAny(['search', 'status', 'sort']))
                            <a href="{{ route('admin.categories.index') }}"
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
                            class="inline-flex cursor-pointer h-11 flex-1 items-center justify-center rounded-xl bg-blue-600 px-4 text-sm font-semibold text-white transition hover:bg-blue-700">
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

                                <td class="whitespace-nowrap px-6 py-4 text-center">
                                    <details data-action-menu class="group relative inline-block text-left">
                                        <summary
                                            class="flex h-9 w-9 cursor-pointer list-none items-center justify-center rounded-lg border border-slate-200 bg-white text-lg font-bold text-slate-600 transition hover:border-blue-300 hover:bg-blue-50 hover:text-blue-600 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-300 dark:hover:border-blue-500 dark:hover:bg-blue-950/40 dark:hover:text-blue-400">
                                            ⋮
                                        </summary>

                                        <div class="absolute right-0 z-50 mt-2 w-36 overflow-hidden rounded-xl border border-slate-200 bg-white text-left shadow-xl dark:border-slate-700 dark:bg-slate-800">
                                            <a href="{{ route('admin.categories.show', $category) }}"
                                                class="flex items-center gap-3 px-4 py-3 text-sm font-medium text-blue-700 transition hover:bg-blue-50 dark:text-blue-400 dark:hover:bg-blue-950/40">
                                                Xem
                                            </a>

                                            <a href="{{ route('admin.categories.edit', $category) }}"
                                                class="flex items-center gap-3 px-4 py-3 text-sm font-medium text-amber-600 transition hover:bg-amber-50 dark:text-amber-400 dark:hover:bg-amber-950/40">
                                                Sửa
                                            </a>

                                            <form action="{{ route('admin.categories.destroy', $category) }}" method="POST"
                                                onsubmit="return confirm('Bạn có chắc chắn muốn xóa danh mục {{ addslashes($category->name) }} không?\nHành động này không thể hoàn tác.')">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit"
                                                    class="flex w-full cursor-pointer items-center gap-3 px-4 py-3 text-left text-sm font-medium text-red-600 transition hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-950/40">
                                                    Xóa
                                                </button>
                                            </form>
                                        </div>
                                    </details>
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