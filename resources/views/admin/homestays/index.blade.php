<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Quản lý Homestay  | HomeStay</title>

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

        <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

            <div>
                <h1 class="text-3xl font-bold text-slate-900">
                    Quản lý Homestay 
                </h1>

                <p class="mt-2 text-slate-500">
                    Danh sách tất cả Homestay trong hệ thống.
                </p>
            </div>

            <div class="flex items-center justify-between gap-4">
                <form method="GET">
                    <input
                        type="search"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Tìm kiếm Homestay..."
                        class="w-full rounded-xl border border-slate-300 px-4 py-2"
                        onsearch="this.form.submit()"
                        oninput="if(this.value === '') this.form.submit()"
                    >
                </form>

                <a
                    href="{{ route('admin.homestays.create') }}"
                    class="inline-flex items-center justify-center rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-blue-700"
                >
                    + Thêm mới Homestay
                </a>
            </div>

        </div>

        <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">

            <div class="overflow-x-auto">

                <table class="min-w-full divide-y divide-slate-200">

                    <thead class="bg-slate-50">
                        <tr>

                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                                ID
                            </th>

                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Homestay
                            </th>

                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Danh mục
                            </th>

                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Chủ sở hữu
                            </th>

                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Địa chỉ
                            </th>

                            <th class="whitespace-nowrap px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Thành phố
                            </th>

                            <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Trạng thái
                            </th>

                            <th class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Thao tác
                            </th>

                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-200 bg-white">

                        @forelse ($homestays as $homestay)

                        <tr class="transition hover:bg-slate-50">

                            <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-slate-700">
                                {{ $homestay->id }}
                            </td>

                            <td class="px-6 py-4">

                                <div class="font-semibold text-slate-900">
                                    {{ $homestay->name }}
                                </div>

                                <div class="mt-1 text-xs text-slate-400">
                                    {{ $homestay->slug }}
                                </div>

                            </td>

                            <td class="whitespace-nowrap px-6 py-4">
                                <span class="inline-flex whitespace-nowrap rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-600">
                                    {{ $homestay->category?->name ?? 'Chưa phân loại' }}
                                </span>
                            </td>

                            <td class="px-6 py-4">

                                <div class="font-medium text-slate-800">
                                    {{ $homestay->owner?->name }}
                                </div>

                                <div class="text-xs text-slate-400">
                                    {{ $homestay->owner?->email }}
                                </div>

                            </td>

                            <td class="px-6 py-4 text-sm text-slate-500">
                                {{ $homestay->address }}
                            </td>

                            <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-500">
                                {{ $homestay->city ?: 'Chưa cập nhật' }}
                            </td>

                            <td class="whitespace-nowrap px-6 py-4 text-center">
                                @if ($homestay->status)
                                    <span class="inline-flex items-center gap-1.5 whitespace-nowrap rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">
                                        <span class="h-1.5 w-1.5 rounded-full bg-green-600"></span>
                                        Hoạt động
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 whitespace-nowrap rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-700">
                                        <span class="h-1.5 w-1.5 rounded-full bg-red-600"></span>
                                        Tạm khóa
                                    </span>
                                @endif
                            </td>

                            <td class="whitespace-nowrap px-6 py-4 text-right">

                                <div class="flex justify-end gap-2">

                                    <a
                                        href="{{ route('admin.homestays.show', $homestay) }}"
                                        class="rounded-lg border border-violet-300 px-3 py-2 font-semibold text-violet-600 transition hover:bg-violet-50"
                                    >
                                        Xem
                                    </a>

                                    <a
                                        href="{{ route('admin.homestays.edit', $homestay) }}"
                                        class="rounded-lg border border-amber-300 px-3 py-2 font-semibold text-amber-600 transition hover:bg-amber-50">
                                        Sửa
                                    </a>

                                    <form
                                        action="{{ route('admin.homestays.destroy', $homestay) }}"
                                        method="POST"
                                        onsubmit="return confirm('Bạn có chắc muốn xóa Homestay này không?')">

                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="cursor-pointer rounded-lg border border-red-300 px-3 py-2 font-semibold text-red-600 transition hover:bg-red-50">
                                            Xóa
                                        </button>
                                    </form>

                                </div>

                            </td>

                        </tr>

                        @empty

                            <tr>
                                <td colspan="5" class="px-6 py-14 text-center">

                                    <div class="mx-auto max-w-md">

                                        <h2 class="text-lg font-bold text-slate-900">
                                            Chưa có Homestay
                                        </h2>

                                        <p class="mt-2 text-sm text-slate-500">
                                            Hệ thống hiện chưa có Homestay nào.
                                        </p>

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

    </main>

</body>

</html>