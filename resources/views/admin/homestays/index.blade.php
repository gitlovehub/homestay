@extends('layouts.admin')

@section('title', 'Quản lý Homestay | HomeStayGo')

@section('page-title', 'Quản lý Homestay')

@section('content')
    <div class="mx-auto max-w-screen-2xl">

        <x-alert />

        <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">

            <p class="text-sm font-semibold md:text-lg text-slate-500">
                Danh sách tất cả Homestay trong hệ thống.
            </p>

            <div class="flex items-center justify-between gap-4">
                <form method="GET">
                    <input type="search" name="search" value="{{ request('search') }}" placeholder="Tìm kiếm Homestays..."
                        class="h-12 w-full rounded-xl border border-slate-300 px-4 py-2" onsearch="this.form.submit()"
                        oninput="if(this.value === '') this.form.submit()">
                </form>

                <a href="{{ route('admin.homestays.create') }}"
                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-blue-700">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4" />
                    </svg>
                    <span class="hidden lg:block">Thêm mới</span>
                </a>
            </div>

        </div>

        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

            <div class="overflow-x-auto">

                <table class="w-full min-h-120 divide-y divide-slate-200">

                    <thead class="bg-slate-50">
                        <tr>

                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Homestay
                            </th>

                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Danh mục
                            </th>

                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Giá cơ bản
                            </th>

                            <th
                                class="whitespace-nowrap px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Thành phố
                            </th>

                            <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Trạng thái
                            </th>

                            <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Thao tác
                            </th>

                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-200 bg-white">

                        @forelse ($homestays as $homestay)
                            <tr class="transition hover:bg-slate-50">

                                {{-- Homestay --}}
                                <td class="px-6 py-4">

                                    <div class="flex min-w-64 items-center gap-4">

                                        @if ($homestay->thumbnail)
                                            <img src="{{ asset('storage/' . $homestay->thumbnail) }}"
                                                alt="{{ $homestay->name }}" class="h-14 w-20 rounded-xl object-cover">
                                        @else
                                            <div
                                                class="flex h-14 w-20 shrink-0 items-center justify-center rounded-xl bg-slate-100 text-center text-xs font-medium text-slate-400">
                                                Chưa có ảnh
                                            </div>
                                        @endif

                                        <div>
                                            <div class="font-semibold text-slate-900">
                                                {{ $homestay->name }}
                                            </div>

                                            <div class="mt-1 text-xs text-slate-400">
                                                Tạo ngày {{ $homestay->created_at->format('d/m/Y') }}
                                            </div>
                                        </div>

                                    </div>

                                </td>

                                {{-- Danh mục --}}
                                <td class="whitespace-nowrap px-6 py-4">

                                    <span
                                        class="inline-flex whitespace-nowrap rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-600">
                                        {{ $homestay->category?->name ?? 'Chưa phân loại' }}
                                    </span>

                                </td>

                                {{-- Giá cơ bản --}}
                                <td class="whitespace-nowrap px-6 py-4">

                                    <div class="font-semibold text-slate-900">
                                        {{ number_format($homestay->base_price, 0, ',', '.') }} VNĐ
                                    </div>

                                    <div class="mt-1 text-xs text-slate-400">
                                        Giá từ
                                    </div>

                                </td>

                                {{-- Thành phố --}}
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-500">
                                    {{ $homestay->city ?: 'Chưa cập nhật' }}
                                </td>

                                {{-- Trạng thái --}}
                                <td class="whitespace-nowrap px-6 py-4 text-center">

                                    @if ($homestay->status)
                                        <span
                                            class="inline-flex items-center gap-1.5 whitespace-nowrap rounded-full border border-emerald-200 bg-green-50 px-4 py-1.5 text-xs font-semibold text-emerald-700">
                                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-600"></span>
                                            Hoạt động
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center gap-1.5 whitespace-nowrap rounded-full border border-red-200 bg-red-50 px-4 py-1.5 text-xs font-semibold text-red-700">
                                            <span class="h-1.5 w-1.5 rounded-full bg-red-600"></span>
                                            Tạm khóa
                                        </span>
                                    @endif

                                </td>

                                {{-- Thao tác --}}
                                <td class="whitespace-nowrap px-6 py-4 text-center">

                                    <details data-action-menu class="group relative inline-block text-left">

                                        {{-- Nút ba chấm --}}
                                        <summary
                                            class="flex h-9 w-9 cursor-pointer list-none items-center justify-center rounded-lg border border-slate-200 bg-white text-lg font-bold text-slate-600 transition hover:border-blue-300 hover:bg-blue-50 hover:text-blue-600"
                                            title="Thao tác">
                                            ⋮
                                        </summary>

                                        {{-- Menu thao tác --}}
                                        <div
                                            class="absolute right-0 z-50 mt-2 w-35 overflow-hidden rounded-xl border border-slate-200 bg-white text-left shadow-xl">

                                            {{-- Xem chi tiết --}}
                                            <a href="{{ route('admin.homestays.show', $homestay) }}"
                                                class="flex items-center gap-3 px-4 py-3 text-sm font-medium text-blue-700 transition hover:bg-blue-50">
                                                <svg viewBox="0 0 24 24" class="h-4 w-4" fill="none"
                                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round">
                                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                                                    <circle cx="12" cy="12" r="3" />
                                                </svg>
                                                Xem
                                            </a>

                                            {{-- Chỉnh sửa --}}
                                            <a href="{{ route('admin.homestays.edit', $homestay) }}"
                                                class="flex items-center gap-3 px-4 py-3 text-sm font-medium text-amber-500 transition hover:bg-amber-50">
                                                <svg viewBox="0 0 24 24" class="h-4 w-4" fill="none"
                                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round">
                                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                                                </svg>
                                                Sửa
                                            </a>

                                            <div class="border-t border-slate-100"></div>

                                            {{-- Xóa Homestay --}}
                                            <form action="{{ route('admin.homestays.destroy', $homestay) }}" method="POST"
                                                onsubmit="return confirm('Bạn có chắc chắn muốn xóa Homestay {{ $homestay->name }} không?\nHành động này không thể hoàn tác.')">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit"
                                                    class="flex w-full cursor-pointer items-center gap-3 px-4 py-3 text-left text-sm font-medium text-red-600 transition hover:bg-red-50">
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
                                <td colspan="6" class="px-6 py-14 text-center">

                                    <div class="mx-auto max-w-md">

                                        <h2 class="text-lg font-bold text-slate-900">
                                            Chưa có Homestay
                                        </h2>

                                        <p class="mt-2 text-sm text-slate-500">
                                            Hệ thống hiện chưa có Homestay nào.
                                        </p>

                                        <a href="{{ route('admin.homestays.create') }}"
                                            class="mt-5 inline-flex items-center justify-center rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-blue-700">
                                            + Thêm Homestay đầu tiên
                                        </a>

                                    </div>

                                </td>
                            </tr>
                        @endforelse

                    </tbody>

                </table>

            </div>

            @if ($homestays->hasPages())
                <div class="border-t border-slate-200 px-6 py-4">
                    {{ $homestays->links() }}
                </div>
            @endif

        </div>

    </div>
    
@endsection