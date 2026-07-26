<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Quản lý phòng | HomeStay</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-slate-100">

    @include('partials.navbar')

    <main class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">

        <x-alert />

        <a
            href="{{ route('admin.dashboard') }}"
            class="block mb-4 text-sm font-semibold text-blue-600 transition hover:text-blue-700"
        >
            ← Quay lại bảng điều khiển
        </a>
        
        
        
        
        <div class="mb-8 flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">

            <div>
                <h1 class="text-3xl font-bold text-slate-900">
                    Quản lý phòng
                </h1>

                <p class="mt-2 text-slate-500">
                    Danh sách phòng thuộc các Homestay trong hệ thống.
                </p>
            </div>

            <div class="flex flex-col gap-3 sm:flex-row sm:items-center">

                <form method="GET" action="{{ route('admin.rooms.index') }}">
                    <input
                        type="search"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Tìm kiếm phòng..."
                        class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 sm:w-72"
                        onsearch="this.form.submit()"
                        oninput="if(this.value === '') this.form.submit()"
                    >
                </form>

                <a
                    href="{{ route('admin.rooms.create') }}"
                    class="inline-flex items-center justify-center whitespace-nowrap rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-blue-700"
                >
                    + Thêm phòng mới
                </a>

            </div>

        </div>

        {{-- Bảng --}}
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

            <div class="overflow-x-auto">

                <table class="min-w-full divide-y divide-slate-200">

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

                            <th class="px-6 py-4 text-right text-xs font-bold uppercase tracking-wider text-slate-500">
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
                                            <span class="inline-flex items-center gap-2 rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700">
                                                <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                                                Còn trống
                                            </span>
                                            @break

                                        @case('maintenance')
                                            <span class="inline-flex items-center gap-2 rounded-full bg-amber-50 px-3 py-1 text-xs font-semibold text-amber-700">
                                                <span class="h-2 w-2 rounded-full bg-amber-500"></span>
                                                Bảo trì
                                            </span>
                                            @break

                                        @default
                                            <span class="inline-flex items-center gap-2 rounded-full bg-red-50 px-3 py-1 text-xs font-semibold text-red-700">
                                                <span class="h-2 w-2 rounded-full bg-red-500"></span>
                                                Ngừng hoạt động
                                            </span>

                                    @endswitch

                                </td>

                                {{-- Thao tác --}}
                                <td class="whitespace-nowrap px-6 py-5">

                                    <div class="flex items-center justify-end gap-2">

                                        <a
                                            href="{{ route('admin.rooms.show', $room) }}"
                                            class="rounded-lg bg-slate-100 px-3 py-2 text-xs font-semibold text-slate-700 transition hover:bg-slate-200"
                                        >
                                            Xem
                                        </a>

                                        <a
                                            href="{{ route('admin.rooms.edit', $room) }}"
                                            class="rounded-lg bg-amber-50 px-3 py-2 text-xs font-semibold text-amber-700 transition hover:bg-amber-100"
                                        >
                                            Sửa
                                        </a>

                                        <form
                                            action="{{ route('admin.rooms.destroy', $room) }}"
                                            method="POST"
                                            onsubmit="return confirm('Bạn có chắc muốn xóa phòng {{ $room->name }} không?')"
                                        >
                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="cursor-pointer rounded-lg bg-red-50 px-3 py-2 text-xs font-semibold text-red-700 transition hover:bg-red-100"
                                            >
                                                Xóa
                                            </button>
                                        </form>

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="6" class="px-6 py-14 text-center">

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

    </main>

</body>

</html>