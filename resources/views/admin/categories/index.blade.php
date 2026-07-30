<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Quản lý danh mục | HomeStay</title>

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

        <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">

            <div>
                <h1 class="text-3xl font-bold text-slate-900">
                    Quản lý danh mục
                </h1>

                <p class="mt-2 text-slate-500">
                    Danh sách các loại Homestay trong hệ thống.
                </p>
            </div>

            <div class="flex items-center justify-between gap-4">
                <form method="GET">
                    <input
                        type="search"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Tìm kiếm danh mục..."
                        class="w-full rounded-xl border border-slate-300 px-4 py-2"
                        onsearch="this.form.submit()"
                        oninput="if(this.value === '') this.form.submit()"
                    >
                </form>

                <a
                    href="{{ route('admin.categories.create') }}"
                    class="inline-flex items-center justify-center rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-blue-700"
                >
                    + Thêm danh mục
                </a>
            </div>

        </div>

        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

            <div class="overflow-x-auto">

                <table class="min-w-full divide-y divide-slate-200">

                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Tên danh mục
                            </th>

                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Slug
                            </th>

                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Mô tả
                            </th>

                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Trạng thái
                            </th>

                            <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Thao tác
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-200 bg-white">

                        @forelse ($categories as $category)

                            <tr class="transition hover:bg-slate-50">

                                <td class="whitespace-nowrap px-6 py-4">
                                    <div class="text-sm font-semibold text-slate-900">
                                        {{ $category->name }}
                                    </div>
                                </td>

                                <td class="whitespace-nowrap px-6 py-4">
                                    <span class="rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-600">
                                        {{ $category->slug }}
                                    </span>
                                </td>

                                <td class="max-w-md px-6 py-4 text-sm text-slate-500">
                                    {{ $category->description ?: 'Chưa có mô tả' }}
                                </td>

                                <td class="whitespace-nowrap px-6 py-4">
                                    @if ($category->status)
                                        <span class="inline-flex items-center gap-2 rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700">
                                            <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                                            Hoạt động
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-2 rounded-full bg-red-50 px-3 py-1 text-xs font-semibold text-red-700">
                                            <span class="h-2 w-2 rounded-full bg-red-500"></span>
                                            Tạm khóa
                                        </span>
                                    @endif
                                </td>

                                <td class="whitespace-nowrap px-6 py-4 text-center">

                                    <details class="group relative inline-block text-left">

                                        {{-- Nút ba chấm --}}
                                        <summary
                                            class="flex h-9 w-9 cursor-pointer list-none items-center justify-center rounded-lg border border-slate-200 bg-white text-lg font-bold text-slate-600 transition hover:border-blue-300 hover:bg-blue-50 hover:text-blue-600"
                                            title="Thao tác"
                                        >
                                            ⋮
                                        </summary>

                                        {{-- Menu thao tác --}}
                                        <div class="absolute right-0 z-50 mt-2 w-48 overflow-hidden rounded-xl border border-slate-200 bg-white text-left shadow-xl">

                                            {{-- Xem chi tiết --}}
                                            <a
                                                href="{{ route('admin.categories.show', $category) }}"
                                                class="flex items-center gap-3 px-4 py-3 text-sm font-medium text-slate-700 transition hover:bg-slate-100"
                                            >
                                                <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-slate-100">
                                                    👁
                                                </span>

                                                Xem chi tiết
                                            </a>

                                            {{-- Sửa danh mục --}}
                                            <a
                                                href="{{ route('admin.categories.edit', $category) }}"
                                                class="flex items-center gap-3 px-4 py-3 text-sm font-medium text-amber-700 transition hover:bg-amber-50"
                                            >
                                                <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-amber-50">
                                                    ✏️
                                                </span>

                                                Chỉnh sửa
                                            </a>

                                            <div class="border-t border-slate-100"></div>

                                            {{-- Xóa danh mục --}}
                                            <form
                                                action="{{ route('admin.categories.destroy', $category) }}"
                                                method="POST"
                                                onsubmit="return confirm('Bạn có chắc chắn muốn xóa danh mục {{ $category->name }} không?\nHành động này không thể hoàn tác.')"
                                            >
                                                @csrf
                                                @method('DELETE')

                                                <button
                                                    type="submit"
                                                    class="flex w-full cursor-pointer items-center gap-3 px-4 py-3 text-left text-sm font-medium text-red-700 transition hover:bg-red-50"
                                                >
                                                    <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-red-50">
                                                        🗑
                                                    </span>

                                                    Xóa danh mục
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
                                            Chưa có danh mục
                                        </h2>

                                        <p class="mt-2 text-sm text-slate-500">
                                            Hệ thống hiện chưa có danh mục Homestay nào.
                                        </p>

                                    </div>

                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

            @if ($categories->hasPages())
                <div class="border-t border-slate-200 px-6 py-4">
                    {{ $categories->links() }}
                </div>
            @endif

        </div>

    </main>

</body>

</html>