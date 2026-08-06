@extends('layouts.admin')

@section('title', 'Chi tiết Homestay | HomeStayGo')

@section('page-title', 'Chi tiết Homestay')

@section('content')
    <div class="mx-auto max-w-screen-2xl">

        <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">

            <div>
                <h2 class="text-lg font-semibold text-slate-900 dark:text-slate-100 md:text-2xl">
                    Xem toàn bộ thông tin của Homestay trong hệ thống.
                </h2>

                <a href="{{ route('admin.homestays.index') }}"
                    class="mt-1 inline-flex items-center gap-1 text-xs font-medium text-blue-600 transition hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 sm:text-sm">
                    ←
                    Trở về danh sách Homestay
                </a>
            </div>

            <a href="{{ route('admin.homestays.edit', $homestay) }}"
                class="inline-flex h-11 w-full items-center justify-center gap-2 rounded-xl bg-amber-500 px-5 text-sm font-semibold text-white transition hover:bg-amber-600 focus:outline-none focus:ring-4 focus:ring-amber-200 dark:bg-amber-500 dark:hover:bg-amber-600 dark:focus:ring-amber-900/50 sm:w-auto">
                <svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round">
                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                </svg>
                Chỉnh sửa
            </a>

        </div>

        <div class="space-y-6">

            {{-- Ảnh và thông tin nổi bật --}}
            <section class="overflow-hidden rounded-3xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 shadow-sm">

                <div class="grid lg:grid-cols-[1.2fr_0.8fr]">

                    {{-- Thumbnail --}}
                    <div class="min-h-80 bg-slate-100 dark:bg-slate-700">

                        @if ($homestay->thumbnail)
                            <img src="{{ asset('storage/' . $homestay->thumbnail) }}" alt="{{ $homestay->name }}"
                                class="h-full min-h-80 w-full object-cover">
                        @else
                            <div class="flex min-h-80 h-full items-center justify-center px-6 text-center">

                                <div>
                                    <div
                                        class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-white dark:bg-slate-800 text-3xl shadow-sm">
                                        🏡
                                    </div>

                                    <p class="mt-4 font-semibold text-slate-700 dark:text-slate-300">
                                        Chưa có ảnh đại diện
                                    </p>

                                    <p class="mt-1 text-sm text-slate-400 dark:text-slate-500">
                                        Homestay này chưa được cập nhật ảnh.
                                    </p>
                                </div>

                            </div>
                        @endif

                    </div>

                    {{-- Thông tin nổi bật --}}
                    <div class="flex flex-col justify-between p-6 sm:p-8">

                        <div>

                            <div class="flex flex-wrap items-center gap-3">

                                <span
                                    class="inline-flex rounded-full bg-blue-50 border border-blue-200 dark:border-blue-800 dark:bg-blue-950/40 px-4 py-1.5 text-xs font-semibold text-blue-700 dark:border-blue-800 dark:bg-blue-950/40 dark:text-blue-400">
                                    {{ $homestay->category?->name ?? 'Chưa phân loại' }}
                                </span>

                                @if ($homestay->status)
                                    <span
                                        class="inline-flex items-center gap-2 rounded-full border border-emerald-200 bg-emerald-50 dark:bg-emerald-950/40 px-4 py-1.5 text-xs font-semibold text-emerald-700 dark:border-emerald-800 dark:bg-emerald-950/40 dark:text-emerald-400">
                                        <span class="h-2 w-2 rounded-full bg-emerald-50 dark:bg-emerald-950/400"></span>
                                        Hoạt động
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center gap-2 rounded-full border border-red-200 bg-red-50 dark:bg-red-950/40 px-4 py-1.5 text-xs font-semibold text-red-700 dark:border-red-800 dark:bg-red-950/40 dark:text-red-400">
                                        <span class="h-2 w-2 rounded-full bg-red-50 dark:bg-red-950/400"></span>
                                        Tạm khóa
                                    </span>
                                @endif

                            </div>

                            <h2 class="mt-5 text-3xl font-bold leading-tight text-slate-900 dark:text-slate-100">
                                {{ $homestay->name }}
                            </h2>

                            <p class="mt-2 text-sm text-slate-400 dark:text-slate-500">
                                {{ $homestay->slug }}
                            </p>

                            <div class="mt-7">

                                <p class="text-sm font-medium text-slate-500 dark:text-slate-400">
                                    Giá cơ bản
                                </p>

                                <p class="mt-1 text-3xl font-bold text-blue-600 dark:text-blue-400">
                                    {{ number_format($homestay->base_price, 0, ',', '.') }} VNĐ
                                </p>

                            </div>

                        </div>

                        <div class="mt-8 grid grid-cols-2 gap-3">

                            <div class="rounded-2xl bg-slate-50 dark:bg-slate-900/60 p-4">

                                <p class="text-xs font-semibold uppercase tracking-wide text-slate-400 dark:text-slate-500">
                                    Nhận phòng
                                </p>

                                <p class="mt-2 font-bold text-slate-900 dark:text-slate-100">
                                    {{ \Carbon\Carbon::createFromFormat('H:i:s', $homestay->check_in_time)->format('h:i A') ?: 'Chưa cập nhật' }}
                                </p>

                            </div>

                            <div class="rounded-2xl bg-slate-50 dark:bg-slate-900/60 p-4">

                                <p class="text-xs font-semibold uppercase tracking-wide text-slate-400 dark:text-slate-500">
                                    Trả phòng
                                </p>

                                <p class="mt-2 font-bold text-slate-900 dark:text-slate-100">
                                    {{ \Carbon\Carbon::createFromFormat('H:i:s', $homestay->check_out_time)->format('h:i A') ?: 'Chưa cập nhật' }}
                                </p>

                            </div>

                        </div>

                    </div>

                </div>

            </section>

            {{-- Thông tin cơ bản --}}
            <section class="rounded-3xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-6 shadow-sm sm:p-8">

                <div class="mb-6">

                    <h2 class="text-xl font-bold text-slate-900 dark:text-slate-100">
                        Thông tin cơ bản
                    </h2>

                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                        Thông tin quản lý và liên hệ của Homestay.
                    </p>

                </div>

                <div class="grid gap-5 md:grid-cols-2 lg:grid-cols-3">

                    {{-- Chủ sở hữu --}}
                    <div class="rounded-2xl border border-slate-200 dark:border-slate-700 p-5">

                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400 dark:text-slate-500">
                            Chủ sở hữu
                        </p>

                        <p class="mt-2 font-semibold text-slate-900 dark:text-slate-100">
                            {{ $homestay->owner?->name ?? 'Chưa xác định' }}
                        </p>

                        <p class="mt-1 break-all text-sm text-slate-500 dark:text-slate-400">
                            {{ $homestay->owner?->email ?? 'Chưa có email' }}
                        </p>

                    </div>

                    {{-- Số điện thoại --}}
                    <div class="rounded-2xl border border-slate-200 dark:border-slate-700 p-5">

                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400 dark:text-slate-500">
                            Số điện thoại
                        </p>

                        <p class="mt-2 font-semibold text-slate-900 dark:text-slate-100">
                            {{ $homestay->phone ?: 'Chưa cập nhật' }}
                        </p>

                    </div>

                    {{-- Thành phố --}}
                    <div class="rounded-2xl border border-slate-200 dark:border-slate-700 p-5">

                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400 dark:text-slate-500">
                            Thành phố
                        </p>

                        <p class="mt-2 font-semibold text-slate-900 dark:text-slate-100">
                            {{ $homestay->city ?: 'Chưa cập nhật' }}
                        </p>

                    </div>

                    {{-- Địa chỉ --}}
                    <div class="rounded-2xl border border-slate-200 dark:border-slate-700 p-5 md:col-span-2">

                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400 dark:text-slate-500">
                            Địa chỉ
                        </p>

                        <p class="mt-2 leading-6 text-slate-900 dark:text-slate-100">
                            {{ $homestay->address ?: 'Chưa cập nhật' }}
                        </p>

                    </div>

                    {{-- Ngày tạo --}}
                    <div class="rounded-2xl border border-slate-200 dark:border-slate-700 p-5">

                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400 dark:text-slate-500">
                            Ngày tạo
                        </p>

                        <p class="mt-2 font-semibold text-slate-900 dark:text-slate-100">
                            {{ $homestay->created_at?->format('d/m/Y H:i') }}
                        </p>

                    </div>

                </div>

            </section>

            {{-- Vị trí --}}
            <section class="rounded-3xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-6 shadow-sm sm:p-8">

                <div class="mb-6">

                    <h2 class="text-xl font-bold text-slate-900 dark:text-slate-100">
                        Vị trí
                    </h2>

                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                        Tọa độ được sử dụng để xác định vị trí Homestay.
                    </p>

                </div>

                <div class="grid gap-5 sm:grid-cols-2">

                    <div class="rounded-2xl bg-slate-50 dark:bg-slate-900/60 p-5">

                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400 dark:text-slate-500">
                            Vĩ độ
                        </p>

                        <p class="mt-2 font-mono font-semibold text-slate-900 dark:text-slate-100">
                            {{ $homestay->latitude ?? 'Chưa cập nhật' }}
                        </p>

                    </div>

                    <div class="rounded-2xl bg-slate-50 dark:bg-slate-900/60 p-5">

                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400 dark:text-slate-500">
                            Kinh độ
                        </p>

                        <p class="mt-2 font-mono font-semibold text-slate-900 dark:text-slate-100">
                            {{ $homestay->longitude ?? 'Chưa cập nhật' }}
                        </p>

                    </div>

                </div>

            </section>

            {{-- Tiện ích --}}
            <section class="rounded-3xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-6 shadow-sm sm:p-8">

                <div class="mb-6">

                    <h2 class="text-xl font-bold text-slate-900 dark:text-slate-100">
                        Tiện ích
                    </h2>

                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                        Các tiện ích hiện có tại Homestay.
                    </p>

                </div>

                @if ($homestay->amenities->isNotEmpty())

                    <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3">

                        @foreach ($homestay->amenities as $amenity)
                            <div class="flex items-center gap-3 rounded-2xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/60 p-4">

                                <div
                                    class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-white dark:bg-slate-800 text-xl shadow-sm">
                                    {{ $amenity->icon ?: '✓' }}
                                </div>

                                <div class="min-w-0">

                                    <p class="font-semibold text-slate-900 dark:text-slate-100">
                                        {{ $amenity->name }}
                                    </p>

                                    @if ($amenity->description)
                                        <p class="mt-1 line-clamp-1 text-sm text-slate-500 dark:text-slate-400">
                                            {{ $amenity->description }}
                                        </p>
                                    @endif

                                </div>

                            </div>
                        @endforeach

                    </div>
                @else
                    <div class="rounded-2xl border border-dashed border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-900/60 px-6 py-10 text-center">

                        <p class="font-semibold text-slate-700 dark:text-slate-300">
                            Chưa có tiện ích
                        </p>

                        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                            Homestay này chưa được gắn tiện ích nào.
                        </p>

                    </div>

                @endif

            </section>

            {{-- Mô tả --}}
            <section class="rounded-3xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-6 shadow-sm sm:p-8">

                <div class="mb-5">

                    <h2 class="text-xl font-bold text-slate-900 dark:text-slate-100">
                        Mô tả Homestay
                    </h2>

                </div>

                @if ($homestay->description)
                    <div class="leading-7 text-slate-600 dark:text-slate-400">
                        {{ $homestay->description }}
                    </div>
                @else
                    <p class="text-sm italic text-slate-400 dark:text-slate-500">
                        Chưa có nội dung mô tả.
                    </p>
                @endif

            </section>

            {{-- Chính sách --}}
            <section class="rounded-3xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-6 shadow-sm sm:p-8">

                <div class="mb-5">

                    <h2 class="text-xl font-bold text-slate-900 dark:text-slate-100">
                        Chính sách lưu trú
                    </h2>

                </div>

                @if ($homestay->policy)
                    <div class="leading-7 text-slate-600 dark:text-slate-400">
                        {{ $homestay->policy }}
                    </div>
                @else
                    <p class="text-sm italic text-slate-400 dark:text-slate-500">
                        Chưa có chính sách lưu trú.
                    </p>
                @endif

            </section>

        </div>

    </div>
@endsection