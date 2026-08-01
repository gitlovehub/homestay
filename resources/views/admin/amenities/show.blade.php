@extends('layouts.admin')

@section('title', 'Chi tiết tiện ích | HomeStayGo')

@section('page-title', 'Chi tiết tiện ích')

@section('content')
    <div class="mx-auto max-w-screen-2xl">

        @php
            $defaultAmenityIcon = '
            <svg
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke-width="1.8"
                stroke="currentColor"
                class="h-8 w-8"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M9.813 15.904 9 18.75l-.813-2.846a4.5 4.5 0 0 0-3.09-3.09L2.25 12l2.847-.813a4.5 4.5 0 0 0 3.09-3.09L9 5.25l.813 2.847a4.5 4.5 0 0 0 3.09 3.09L15.75 12l-2.847.813a4.5 4.5 0 0 0-3.09 3.09ZM18.259 8.715 18 9.75l-.259-1.035a3.375 3.375 0 0 0-2.456-2.456L14.25 6l1.035-.259a3.375 3.375 0 0 0 2.456-2.456L18 2.25l.259 1.035a3.375 3.375 0 0 0 2.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 0 0-2.456 2.456Z"
                />
            </svg>
        ';
        @endphp

        <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">

            <p class="text-sm font-semibold md:text-lg text-slate-500">
                Xem thông tin chi tiết của tiện ích trong hệ thống.
            </p>

            <a href="{{ route('admin.amenities.edit', $amenity) }}"
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

            {{-- Thông tin tổng quan --}}
            <section class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">

                <div class="p-6 sm:p-8">

                    <div class="flex flex-col gap-6 sm:flex-row sm:items-center sm:justify-between">

                        <div class="flex items-center gap-5">

                            {{-- Icon --}}
                            <div
                                class="flex h-20 w-20 shrink-0 items-center justify-center rounded-2xl border border-blue-100 bg-blue-50 text-4xl text-blue-600">

                                @if (!empty($amenity->icon))
                                    <span>
                                        {{ $amenity->icon }}
                                    </span>
                                @else
                                    {!! $defaultAmenityIcon !!}
                                @endif

                            </div>

                            <div class="min-w-0">

                                <p class="text-sm font-medium text-slate-500">
                                    Tiện ích
                                </p>

                                <h2 class="mt-1 break-words text-2xl font-bold text-slate-900">
                                    {{ $amenity->name }}
                                </h2>

                                <p class="mt-2 text-sm text-slate-500">
                                    Mã tiện ích:
                                    <span class="font-semibold text-slate-700">
                                        #{{ $amenity->id }}
                                    </span>
                                </p>

                            </div>

                        </div>

                        {{-- Trạng thái --}}
                        <div>

                            @if ($amenity->status)
                                <span
                                    class="inline-flex items-center gap-2 rounded-full border border-emerald-200 bg-emerald-50 px-4 py-2 text-sm font-semibold text-emerald-700">

                                    <span class="h-2 w-2 rounded-full bg-emerald-500"></span>

                                    Hoạt động
                                </span>
                            @else
                                <span
                                    class="inline-flex items-center gap-2 rounded-full border border-red-200 bg-red-50 px-4 py-2 text-sm font-semibold text-red-700">

                                    <span class="h-2 w-2 rounded-full bg-red-500"></span>

                                    Tạm khóa
                                </span>
                            @endif

                        </div>

                    </div>

                </div>

            </section>

            {{-- Thông tin chi tiết --}}
            <section class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">

                <div class="border-b border-slate-200 px-6 py-5 sm:px-8">

                    <h2 class="text-xl font-bold text-slate-900">
                        Thông tin tiện ích
                    </h2>

                    <p class="mt-1 text-sm text-slate-500">
                        Thông tin được lưu trong hệ thống.
                    </p>

                </div>

                <div class="grid gap-6 p-6 sm:grid-cols-2 sm:p-8">

                    {{-- Tên tiện ích --}}
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5">

                        <p class="text-sm font-medium text-slate-500">
                            Tên tiện ích
                        </p>

                        <p class="mt-2 break-words font-semibold text-slate-900">
                            {{ $amenity->name }}
                        </p>

                    </div>

                    {{-- Slug --}}
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5">

                        <p class="text-sm font-medium text-slate-500">
                            Slug
                        </p>

                        <p class="mt-2 break-all font-semibold text-slate-900">
                            {{ $amenity->slug }}
                        </p>

                    </div>

                    {{-- Homestay sử dụng --}}
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5">

                        <p class="text-sm font-medium text-slate-500">
                            Homestay sử dụng
                        </p>

                        <p class="mt-2 text-2xl font-bold text-violet-600">
                            {{ number_format($amenity->homestays_count, 0, ',', '.') }}
                        </p>

                        <p class="mt-1 text-xs text-slate-500">
                            Số Homestay đang được gắn với tiện ích này.
                        </p>

                    </div>

                    {{-- Trạng thái --}}
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5">

                        <p class="text-sm font-medium text-slate-500">
                            Trạng thái
                        </p>

                        @if ($amenity->status)
                            <p class="mt-2 font-semibold text-emerald-600">
                                Hoạt động
                            </p>

                            <p class="mt-1 text-xs text-slate-500">
                                Tiện ích được phép hiển thị và sử dụng.
                            </p>
                        @else
                            <p class="mt-2 font-semibold text-red-600">
                                Tạm khóa
                            </p>

                            <p class="mt-1 text-xs text-slate-500">
                                Tiện ích chưa được phép hiển thị hoặc sử dụng.
                            </p>
                        @endif

                    </div>

                    {{-- Ngày tạo --}}
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5">

                        <p class="text-sm font-medium text-slate-500">
                            Ngày tạo
                        </p>

                        <p class="mt-2 font-semibold text-slate-900">
                            {{ $amenity->created_at->format('d/m/Y') }}
                        </p>

                        <p class="mt-1 text-xs text-slate-500">
                            {{ $amenity->created_at->format('H:i') }}
                        </p>

                    </div>

                    {{-- Ngày cập nhật --}}
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5">

                        <p class="text-sm font-medium text-slate-500">
                            Cập nhật lần cuối
                        </p>

                        <p class="mt-2 font-semibold text-slate-900">
                            {{ $amenity->updated_at->format('d/m/Y') }}
                        </p>

                        <p class="mt-1 text-xs text-slate-500">
                            {{ $amenity->updated_at->format('H:i') }}
                        </p>

                    </div>

                </div>

            </section>

            {{-- Mô tả --}}
            <section class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">

                <div class="border-b border-slate-200 px-6 py-5 sm:px-8">

                    <h2 class="text-xl font-bold text-slate-900">
                        Mô tả
                    </h2>

                    <p class="mt-1 text-sm text-slate-500">
                        Nội dung mô tả của tiện ích.
                    </p>

                </div>

                <div class="p-6 sm:p-8">

                    @if (!empty($amenity->description))
                        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5">

                            <p class="whitespace-pre-line break-words text-sm leading-7 text-slate-700">
                                {{ $amenity->description }}</p>

                        </div>
                    @else
                        <div class="rounded-2xl border border-dashed border-slate-300 bg-slate-50 px-6 py-10 text-center">

                            <p class="text-sm font-medium text-slate-500">
                                Tiện ích này chưa có mô tả.
                            </p>

                        </div>
                    @endif

                </div>

            </section>

        </div>

    </div>
@endsection
