@extends('layouts.admin')

@section('title', 'Chi tiết danh mục | HomeStayGo')

@section('page-title', 'Chi tiết danh mục')

@section('content')
    <div class="mx-auto max-w-screen-2xl">
        <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h2 class="text-lg font-semibold text-slate-900 dark:text-slate-100 md:text-2xl">
                    Chi tiết danh mục
                </h2>

                <a href="{{ route('admin.categories.index') }}"
                    class="mt-1 inline-flex items-center gap-1 text-xs font-medium text-blue-600 transition hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 sm:text-sm">
                    ←
                    Trở về danh sách danh mục
                </a>
            </div>

            <a href="{{ route('admin.categories.edit', $category) }}"
                class="inline-flex h-11 items-center justify-center gap-2 rounded-xl bg-amber-500 px-5 text-sm font-semibold text-white transition hover:bg-amber-600">
                <svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                  <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                </svg>
                Chỉnh sửa
            </a>
        </div>

        <div class="space-y-6">
            <section class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-800">
                <div class="border-b border-slate-200 bg-slate-50 px-6 py-5 sm:px-8 dark:border-slate-700 dark:bg-slate-900/50">
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">
                                Tên danh mục
                            </p>
                            <h3 class="mt-2 text-2xl font-bold text-slate-900 dark:text-slate-100 sm:text-3xl">
                                {{ $category->name }}
                            </h3>
                        </div>

                        @if ($category->status)
                            <span class="inline-flex items-center gap-2 self-start rounded-full border border-emerald-200 bg-emerald-50 px-4 py-1.5 text-sm font-semibold text-emerald-700 dark:border-emerald-800 dark:bg-emerald-950/50 dark:text-emerald-300">
                                <span class="h-2.5 w-2.5 rounded-full bg-emerald-500"></span>
                                Hoạt động
                            </span>
                        @else
                            <span class="inline-flex items-center gap-2 self-start rounded-full border border-red-200 bg-red-50 px-4 py-1.5 text-sm font-semibold text-red-700 dark:border-red-800 dark:bg-red-950/50 dark:text-red-300">
                                <span class="h-2.5 w-2.5 rounded-full bg-red-500"></span>
                                Tạm khóa
                            </span>
                        @endif
                    </div>
                </div>

                <div class="grid gap-5 p-6 sm:grid-cols-3 sm:p-8">
                    <div class="rounded-2xl bg-slate-50 p-5 dark:bg-slate-900/60">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400 dark:text-slate-500">
                            Mã danh mục
                        </p>
                        <p class="mt-2 text-lg font-bold text-slate-900 dark:text-slate-100">
                            #{{ $category->id }}
                        </p>
                    </div>

                    <div class="rounded-2xl bg-slate-50 p-5 dark:bg-slate-900/60">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400 dark:text-slate-500">
                            Slug
                        </p>
                        <p class="mt-2 break-all font-semibold text-blue-700 dark:text-blue-400">
                            {{ $category->slug }}
                        </p>
                    </div>

                    <div class="rounded-2xl bg-slate-50 p-5 dark:bg-slate-900/60">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400 dark:text-slate-500">
                            Homestay sử dụng
                        </p>
                        <p class="mt-2 text-2xl font-bold text-violet-600 dark:text-violet-400">
                            {{ number_format($category->homestays_count, 0, ',', '.') }}
                        </p>
                    </div>
                </div>
            </section>

            <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8 dark:border-slate-700 dark:bg-slate-800">
                <h3 class="text-xl font-bold text-slate-900 dark:text-slate-100">
                    Mô tả danh mục
                </h3>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                    Nội dung giới thiệu và mô tả của danh mục.
                </p>

                @if ($category->description)
                    <div class="mt-6 rounded-2xl bg-slate-50 p-5 leading-7 text-slate-600 dark:bg-slate-900/60 dark:text-slate-300">
                        {{ $category->description }}
                    </div>
                @else
                    <div class="mt-6 rounded-2xl border border-dashed border-slate-300 bg-slate-50 p-6 text-center dark:border-slate-600 dark:bg-slate-900/60">
                        <p class="text-sm italic text-slate-400 dark:text-slate-500">
                            Danh mục này chưa có nội dung mô tả.
                        </p>
                    </div>
                @endif
            </section>

            <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8 dark:border-slate-700 dark:bg-slate-800">
                <h3 class="text-xl font-bold text-slate-900 dark:text-slate-100">
                    Thông tin quản lý
                </h3>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                    Thời gian danh mục được tạo và cập nhật.
                </p>

                <div class="mt-6 grid gap-5 sm:grid-cols-2">
                    <div class="rounded-2xl border border-slate-200 p-5 dark:border-slate-700 dark:bg-slate-900/40">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400 dark:text-slate-500">
                            Ngày tạo
                        </p>
                        <p class="mt-2 font-semibold text-slate-900 dark:text-slate-100">
                            {{ $category->created_at?->format('d/m/Y H:i') ?? 'Không xác định' }}
                        </p>
                    </div>

                    <div class="rounded-2xl border border-slate-200 p-5 dark:border-slate-700 dark:bg-slate-900/40">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400 dark:text-slate-500">
                            Cập nhật gần nhất
                        </p>
                        <p class="mt-2 font-semibold text-slate-900 dark:text-slate-100">
                            {{ $category->updated_at?->format('d/m/Y H:i') ?? 'Không xác định' }}
                        </p>
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection