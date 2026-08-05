@extends('layouts.admin')

@section('title', 'Chi tiết phòng | HomeStayGo')

@section('page-title', 'Chi tiết phòng')

@section('content')
    <div class="mx-auto max-w-screen-2xl">

        <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">

            <p class="text-sm font-semibold md:text-lg text-slate-500">
                Xem toàn bộ thông tin của phòng trong hệ thống.
            </p>

            <a href="{{ route('admin.rooms.edit', $room) }}"
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

            {{-- Thông tin nổi bật --}}
            <section class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">

                <div class="grid lg:grid-cols-[1.2fr_0.8fr]">

                    <div class="min-h-80 bg-slate-100">

                        @if ($room->image)
                            <img src="{{ asset('storage/' . $room->image) }}" alt="{{ $room->name }}"
                                class="h-full min-h-80 w-full object-cover">
                        @else
                            <div class="flex h-full min-h-80 items-center justify-center">

                                <div class="text-center">
                                    <div class="text-6xl">
                                        🚪
                                    </div>

                                    <p class="mt-4 font-semibold text-slate-600">
                                        Chưa có ảnh phòng
                                    </p>
                                </div>

                            </div>
                        @endif

                    </div>

                    <div class="flex flex-col justify-between p-6 sm:p-8">

                        <div>

                            <div class="flex flex-wrap items-center gap-3">

                                <span class="rounded-full bg-blue-50 border border-blue-200 px-4 py-1.5 text-sm font-semibold text-blue-700">
                                    {{ $room->room_type }}
                                </span>

                                @switch($room->status)
                                    @case('available')
                                        <span class="rounded-full bg-emerald-50 border border-emerald-200 px-4 py-1.5 text-sm font-semibold text-emerald-700">
                                            Còn phòng
                                        </span>
                                    @break

                                    @case('maintenance')
                                        <span class="rounded-full bg-amber-50 border border-amber-200 px-4 py-1.5 text-sm font-semibold text-amber-700">
                                            Bảo trì
                                        </span>
                                    @break

                                    @default
                                        <span class="rounded-full bg-red-50 border border-red-200 px-4 py-1.5 text-sm font-semibold text-red-700">
                                            Ngừng hoạt động
                                        </span>
                                @endswitch

                            </div>

                            <h2 class="mt-5 text-3xl font-bold text-slate-900">
                                {{ $room->name }}
                            </h2>

                            <p class="mt-2 text-sm font-medium text-slate-400">
                                Mã phòng: {{ $room->room_code }}
                            </p>

                            <div class="mt-7">
                                <p class="text-sm font-medium text-slate-500">
                                    Giá mỗi đêm
                                </p>

                                <p class="mt-1 text-3xl font-bold text-blue-600">
                                    {{ number_format($room->price_per_night, 0, ',', '.') }} VNĐ
                                </p>
                            </div>

                        </div>

                        <div class="mt-8 rounded-2xl bg-slate-50 p-5">

                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                                Thuộc Homestay
                            </p>

                            <p class="mt-2 text-lg font-bold text-slate-900">
                                {{ $room->homestay?->name ?? 'Không xác định' }}
                            </p>

                            <p class="mt-1 flex items-center gap-1 text-sm text-slate-500">
                                <svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                  <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                                  <circle cx="12" cy="10" r="3"/>
                                </svg>
                                {{ $room->homestay?->address }}
                                <span>-</span>
                                {{ $room->homestay?->city }}
                            </p>

                        </div>

                    </div>

                </div>

            </section>

            {{-- Thông số --}}
            <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">

                <div class="mb-6">
                    <h2 class="text-xl font-bold text-slate-900">
                        Thông số phòng
                    </h2>

                    <p class="mt-1 text-sm text-slate-500">
                        Sức chứa, số giường và diện tích của phòng.
                    </p>
                </div>

                <div class="grid gap-5 sm:grid-cols-3">

                    <div class="rounded-2xl bg-slate-50 p-5">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                            Sức chứa
                        </p>

                        <p class="mt-2 text-xl font-bold text-slate-900">
                            {{ $room->capacity }} người
                        </p>
                    </div>

                    <div class="rounded-2xl bg-slate-50 p-5">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                            Số giường
                        </p>

                        <p class="mt-2 text-xl font-bold text-slate-900">
                            {{ $room->number_of_beds }} giường
                        </p>
                    </div>

                    <div class="rounded-2xl bg-slate-50 p-5">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                            Diện tích
                        </p>

                        <p class="mt-2 text-xl font-bold text-slate-900">
                            {{ $room->area ? $room->area . ' m²' : 'Chưa cập nhật' }}
                        </p>
                    </div>

                </div>

            </section>

            {{-- Mô tả --}}
            <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">

                <h2 class="text-xl font-bold text-slate-900">
                    Mô tả phòng
                </h2>

                @if ($room->description)
                    <div class="mt-5 leading-7 text-slate-600">
                        {{ $room->description }}
                    </div>
                @else
                    <p class="mt-5 text-sm italic text-slate-400">
                        Chưa có nội dung mô tả.
                    </p>
                @endif

            </section>

            {{-- Thông tin quản lý --}}
            <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">

                <h2 class="text-xl font-bold text-slate-900">
                    Thông tin quản lý
                </h2>

                <div class="mt-6 grid gap-5 sm:grid-cols-2">

                    <div class="rounded-2xl border border-slate-200 p-5">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                            Ngày tạo
                        </p>

                        <p class="mt-2 font-semibold text-slate-900">
                            {{ $room->created_at?->format('d/m/Y H:i') }}
                        </p>
                    </div>

                    <div class="rounded-2xl border border-slate-200 p-5">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                            Cập nhật gần nhất
                        </p>

                        <p class="mt-2 font-semibold text-slate-900">
                            {{ $room->updated_at?->format('d/m/Y H:i') }}
                        </p>
                    </div>

                </div>

            </section>

            {{-- Footer --}}
            <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">

                <a href="{{ route('admin.rooms.index') }}"
                    class="inline-flex items-center justify-center rounded-xl border border-slate-300 bg-white px-6 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-100">
                    Quay lại danh sách
                </a>

                <a href="{{ route('admin.rooms.edit', $room) }}"
                    class="inline-flex items-center justify-center rounded-xl bg-blue-600 px-6 py-3 text-sm font-semibold text-white transition hover:bg-blue-700">
                    Chỉnh sửa
                </a>

            </div>

        </div>

    </div>
@endsection
