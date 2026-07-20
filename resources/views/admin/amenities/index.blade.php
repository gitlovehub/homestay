<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Quản lý tiện ích | HomeStay</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-slate-100">

    @include('partials.navbar')

    <main class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">

        <a
            href="{{ route('admin.dashboard') }}"
            class="mb-4 inline-flex text-sm font-semibold text-blue-600 hover:text-blue-700"
        >
            ← Quay lại bảng điều khiển
        </a>

        <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h1 class="text-3xl font-bold text-slate-900">
                    Quản lý tiện ích
                </h1>

                <p class="mt-2 text-slate-500">
                    Danh sách tiện ích có trong các Homestay.
                </p>
            </div>

            <a
                href="{{ route('admin.amenities.create') }}"
                class="inline-flex items-center justify-center rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white hover:bg-blue-700"
            >
                + Thêm tiện ích
            </a>
        </div>

        @if (session('success'))
            <div class="mb-6 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm font-semibold text-green-700">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-semibold text-red-700">
                {{ session('error') }}
            </div>
        @endif

        <form method="GET" class="mb-6">
            <div class="flex flex-col gap-3 sm:flex-row">
                <input
                    type="search"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Tìm kiếm tiện ích..."
                    class="w-full rounded-xl border border-slate-300 px-4 py-3 outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                >

                <button
                    type="submit"
                    class="rounded-xl bg-slate-800 px-6 py-3 text-sm font-semibold text-white hover:bg-slate-900"
                >
                    Tìm kiếm
                </button>
            </div>
        </form>

        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase text-slate-500">
                                Tiện ích
                            </th>

                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase text-slate-500">
                                Mô tả
                            </th>

                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase text-slate-500">
                                Trạng thái
                            </th>

                            <th class="px-6 py-4 text-right text-xs font-semibold uppercase text-slate-500">
                                Hành động
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-200">
                        @forelse ($amenities as $amenity)
                            <tr class="hover:bg-slate-50">
                                <td class="whitespace-nowrap px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <span class="flex h-10 w-10 items-center justify-center p-2 rounded-lg bg-blue-50 text-xl">
                                            {{ $amenity->icon ?: '✅' }}
                                        </span>

                                        <span class="font-semibold text-slate-900">
                                            {{ $amenity->name }}
                                        </span>
                                    </div>
                                </td>

                                <td class="max-w-md px-6 py-4 text-sm text-slate-600">
                                    {{ $amenity->description ?: 'Chưa có mô tả' }}
                                </td>

                                <td class="whitespace-nowrap px-6 py-4">
                                    @if ($amenity->status)
                                        <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">
                                            Hoạt động
                                        </span>
                                    @else
                                        <span class="rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-700">
                                            Tạm khóa
                                        </span>
                                    @endif
                                </td>

                                <td class="whitespace-nowrap px-6 py-4">
                                    <div class="flex justify-end gap-2">
                                        <a
                                            href="{{ route('admin.amenities.show', $amenity) }}"
                                            class="rounded-lg bg-blue-500 px-3 py-2 text-sm font-semibold text-white hover:bg-blue-600"
                                        >
                                            Xem
                                        </a>

                                        <a
                                            href="{{ route('admin.amenities.edit', $amenity) }}"
                                            class="rounded-lg bg-amber-500 px-3 py-2 text-sm font-semibold text-white hover:bg-amber-600"
                                        >
                                            Sửa
                                        </a>

                                        <form
                                            action="{{ route('admin.amenities.destroy', $amenity) }}"
                                            method="POST"
                                            onsubmit="return confirm('Bạn có chắc chắn muốn xóa tiện ích này không?')"
                                        >
                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="rounded-lg bg-red-500 px-3 py-2 text-sm font-semibold text-white hover:bg-red-600"
                                            >
                                                Xóa
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td
                                    colspan="4"
                                    class="px-6 py-12 text-center text-slate-500"
                                >
                                    Chưa có tiện ích nào.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($amenities->hasPages())
                <div class="border-t border-slate-200 px-6 py-4">
                    {{ $amenities->links() }}
                </div>
            @endif
        </div>

    </main>

</body>

</html>