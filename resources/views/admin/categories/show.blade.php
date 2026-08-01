@extends('layouts.admin')

@section('title', 'Chi tiết danh mục | HomeStayGo')

@section('page-title', 'Chi tiết danh mục')

@section('content')
    <div class="mx-auto max-w-screen-2xl">

        <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">

            <p class="text-sm font-semibold md:text-lg text-slate-500">
                Xem toàn bộ thông tin của danh mục Homestay.
            </p>

            <a href="{{ route('admin.categories.edit', $category) }}"
                class="inline-flex items-center justify-center gap-2 rounded-xl bg-amber-500 px-5 py-3 text-sm font-semibold text-white transition hover:bg-amber-600">
                <svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round">
                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                </svg>
                Chỉnh sửa
            </a>

        </div>

        <div class="space-y-6">

            {{-- Thông tin chính --}}
            <section class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">

                <div class="border-b border-slate-200 bg-slate-50 px-6 py-5 sm:px-8">

                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                                Tên danh mục
                            </p>

                            <h2 class="mt-2 text-3xl font-bold text-slate-900">
                                {{ $category->name }}
                            </h2>
                        </div>

                        <div>
                            @if ($category->status)
                                <span
                                    class="inline-flex items-center gap-2 rounded-full border border-emerald-200 bg-emerald-50 px-4 py-1.5 text-sm font-semibold text-emerald-700">
                                    <span class="h-2.5 w-2.5 rounded-full bg-emerald-500"></span>
                                    Hoạt động
                                </span>
                            @else
                                <span
                                    class="inline-flex items-center gap-2 rounded-full border border-red-200 bg-red-50 px-4 py-1.5 text-sm font-semibold text-red-700">
                                    <span class="h-2.5 w-2.5 rounded-full bg-red-500"></span>
                                    Tạm khóa
                                </span>
                            @endif
                        </div>

                    </div>

                </div>

                <div class="grid gap-6 p-6 sm:grid-cols-2 sm:p-8">

                    {{-- ID --}}
                    <div class="rounded-2xl bg-slate-50 p-5">

                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                            Mã danh mục
                        </p>

                        <p class="mt-2 text-lg font-bold text-slate-900">
                            #{{ $category->id }}
                        </p>

                    </div>

                    {{-- Slug --}}
                    <div class="rounded-2xl bg-slate-50 p-5">

                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                            Slug
                        </p>

                        <div class="mt-2">
                            <span
                                class="inline-flex rounded-full text-lg font-semibold text-blue-700">
                                {{ $category->slug }}
                            </span>
                        </div>

                    </div>

                </div>

            </section>

            {{-- Mô tả --}}
            <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">

                <h2 class="text-xl font-bold text-slate-900">
                    Mô tả danh mục
                </h2>

                <p class="mt-1 text-sm text-slate-500">
                    Nội dung giới thiệu và mô tả về danh mục này.
                </p>

                @if ($category->description)
                    <div class="mt-6 rounded-2xl bg-slate-50 p-5 leading-7 text-slate-600">
                        {{ $category->description }}
                    </div>
                @else
                    <div class="mt-6 rounded-2xl border border-dashed border-slate-300 bg-slate-50 p-6 text-center">

                        <div class="text-3xl">
                            📝
                        </div>

                        <p class="mt-3 text-sm italic text-slate-400">
                            Danh mục này chưa có nội dung mô tả.
                        </p>

                    </div>
                @endif

            </section>

            {{-- Thông tin quản lý --}}
            <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">

                <div>
                    <h2 class="text-xl font-bold text-slate-900">
                        Thông tin quản lý
                    </h2>

                    <p class="mt-1 text-sm text-slate-500">
                        Thời gian danh mục được tạo và cập nhật.
                    </p>
                </div>

                <div class="mt-6 grid gap-5 sm:grid-cols-2">

                    {{-- Ngày tạo --}}
                    <div class="rounded-2xl border border-slate-200 p-5">

                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                            Ngày tạo
                        </p>

                        <p class="mt-2 font-semibold text-slate-900">
                            {{ $category->created_at?->format('d/m/Y H:i') ?? 'Không xác định' }}
                        </p>

                    </div>

                    {{-- Ngày cập nhật --}}
                    <div class="rounded-2xl border border-slate-200 p-5">

                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                            Cập nhật gần nhất
                        </p>

                        <p class="mt-2 font-semibold text-slate-900">
                            {{ $category->updated_at?->format('d/m/Y H:i') ?? 'Không xác định' }}
                        </p>

                    </div>

                </div>

            </section>

        </div>

    </div>
@endsection
