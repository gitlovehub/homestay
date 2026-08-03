@extends('layouts.admin')

@section('title', 'Quản lý phòng | HomeStayGo')

@section('page-title', 'Quản lý phòng')

@section('content')
    <div class="mx-auto max-w-screen-2xl">

        <x-alert />

        <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">

            <p class="text-sm font-semibold md:text-lg text-slate-500">
                Danh sách phòng thuộc các Homestay trong hệ thống.
            </p>

            <div class="flex items-center justify-between gap-4">
                <form method="GET">
                    <input type="search" name="search" value="{{ request('search') }}" placeholder="Tìm kiếm phòng..."
                        class="h-12 w-full rounded-xl border border-slate-300 px-4 py-2" onsearch="this.form.submit()"
                        oninput="if(this.value === '') this.form.submit()">
                </form>

                <a href="{{ route('admin.rooms.create') }}"
                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-blue-700">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4" />
                    </svg>
                    <span class="hidden lg:block">Thêm mới</span>
                </a>
            </div>

        </div>

        {{-- Bảng --}}
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

            <div class="overflow-x-auto">

                <table class="w-full min-h-120 divide-y divide-slate-200">

                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">
                                Phòng
                            </th>

                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">
                                Homestay
                            </th>

                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">
                                Loại phòng
                            </th>

                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">
                                Giá mỗi đêm
                            </th>

                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">
                                Trạng thái
                            </th>

                            <th class="px-6 py-4 text-center text-xs font-bold uppercase tracking-wider text-slate-500">
                                Thao tác
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100 bg-white">

                        @forelse ($rooms as $room)

                            <tr class="transition hover:bg-slate-50">

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
                                            <div class="flex h-14 w-20 shrink-0 items-center justify-center rounded-xl bg-slate-100 text-2xl">
                                                🚪
                                            </div>
                                        @endif

                                        <div class="min-w-0">
                                            <p class="font-semibold text-slate-900">
                                                {{ $room->name }}
                                            </p>

                                            <p class="mt-1 text-xs text-slate-500">
                                                Mã: {{ $room->room_code }}
                                            </p>
                                        </div>

                                    </div>
                                </td>

                                {{-- Homestay --}}
                                <td class="whitespace-nowrap px-6 py-5 text-sm text-slate-600">
                                    {{ $room->homestay?->name ?? 'Không xác định' }}
                                </td>

                                {{-- Loại phòng --}}
                                <td class="whitespace-nowrap px-6 py-5">
                                    <span class="rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700">
                                        {{ $room->room_type }}
                                    </span>
                                </td>

                                {{-- Giá --}}
                                <td class="whitespace-nowrap px-6 py-5">
                                    <p class="font-bold text-slate-900">
                                        {{ number_format($room->price_per_night, 0, ',', '.') }} VNĐ
                                    </p>

                                    <p class="mt-1 text-xs text-slate-400">
                                        mỗi đêm
                                    </p>
                                </td>

                                {{-- Trạng thái --}}
                                <td class="whitespace-nowrap px-6 py-5">

                                    @switch($room->status)

                                        @case('available')
                                            <span class="inline-flex items-center gap-2 rounded-full bg-emerald-50 border border-emerald-200 px-4 py-1.5 text-xs font-semibold text-emerald-700">
                                                <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                                                Còn trống
                                            </span>
                                            @break

                                        @case('maintenance')
                                            <span class="inline-flex items-center gap-2 rounded-full bg-amber-50 border border-amber-200 px-4 py-1.5 text-xs font-semibold text-amber-700">
                                                <span class="h-2 w-2 rounded-full bg-amber-500"></span>
                                                Bảo trì
                                            </span>
                                            @break

                                        @default
                                            <span class="inline-flex items-center gap-2 rounded-full bg-red-50 border border-red-200 px-4 py-1.5 text-xs font-semibold text-red-700">
                                                <span class="h-2 w-2 rounded-full bg-red-500"></span>
                                                Ngừng hoạt động
                                            </span>

                                    @endswitch

                                </td>

                                {{-- Thao tác --}}
                                <td class="whitespace-nowrap px-6 py-5 text-center">

                                    <details data-action-menu class="group relative inline-block text-left">

                                        {{-- Nút ba chấm --}}
                                        <summary
                                            class="flex h-9 w-9 cursor-pointer list-none items-center justify-center rounded-lg border border-slate-200 bg-white text-lg font-bold text-slate-600 transition hover:border-blue-300 hover:bg-blue-50 hover:text-blue-600"
                                            title="Thao tác"
                                        >
                                            ⋮
                                        </summary>

                                        {{-- Menu thao tác --}}
                                        <div class="absolute right-0 z-50 mt-2 w-35 overflow-hidden rounded-xl border border-slate-200 bg-white text-left shadow-xl">

                                            {{-- Xem chi tiết --}}
                                            <a
                                                href="{{ route('admin.rooms.show', $room) }}"
                                                class="flex items-center gap-3 px-4 py-3 text-sm font-medium text-blue-700 transition hover:bg-blue-50"
                                            >
                                                <svg viewBox="0 0 24 24" class="h-4 w-4" fill="none"
                                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round">
                                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                                                    <circle cx="12" cy="12" r="3" />
                                                </svg>
                                                Xem
                                            </a>

                                            {{-- Chỉnh sửa --}}
                                            <a
                                                href="{{ route('admin.rooms.edit', $room) }}"
                                                class="flex items-center gap-3 px-4 py-3 text-sm font-medium text-amber-500 transition hover:bg-amber-50"
                                            >
                                                <svg viewBox="0 0 24 24" class="h-4 w-4" fill="none"
                                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round">
                                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                                                </svg>
                                                Sửa
                                            </a>

                                            <div class="border-t border-slate-100"></div>

                                            {{-- Xóa phòng --}}
                                            <form
                                                action="{{ route('admin.rooms.destroy', $room) }}"
                                                method="POST"
                                                onsubmit="return confirm('Bạn có chắc muốn xóa phòng {{ $room->name }} không?\nHành động này không thể hoàn tác.')"
                                            >
                                                @csrf
                                                @method('DELETE')

                                                <button
                                                    type="submit"
                                                    class="flex w-full cursor-pointer items-center gap-3 px-4 py-3 text-left text-sm font-medium text-red-600 transition hover:bg-red-50"
                                                >
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

                        @empty

                            <tr>
                                <td
                                    colspan="7"
                                    class="px-6 py-16 text-center"
                                >
                                    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-slate-100 text-3xl">
                                        🚪
                                    </div>

                                    <p class="mt-4 font-semibold text-slate-700">
                                        Chưa có phòng
                                    </p>

                                    <p class="mt-1 text-sm text-slate-500">
                                        Chưa tìm thấy phòng phù hợp trong hệ thống.
                                    </p>

                                    <a
                                        href="{{ route('admin.rooms.create') }}"
                                        class="mt-5 inline-flex rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-blue-700"
                                    >
                                        Thêm phòng mới
                                    </a>
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

            @if ($rooms->hasPages())
                <div class="border-t border-slate-200 px-6 py-5">
                    {{ $rooms->links() }}
                </div>
            @endif

        </div>

    </div>
@endsection