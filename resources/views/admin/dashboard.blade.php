<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Trang quản trị | HomeStay</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-slate-100">

    @include('partials.navbar')

    <main class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">

        <div class="mb-8">
            <h1 class="text-3xl font-bold text-slate-900">
                Trang quản trị
            </h1>

            <p class="mt-2 text-slate-500">
                Xin chào {{ auth()->user()->name }}. Bạn đang đăng nhập với quyền quản trị viên.
            </p>
        </div>

        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">

            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <p class="text-sm font-medium text-slate-500">
                    Người dùng
                </p>

                <p class="mt-3 text-3xl font-bold text-slate-900">
                    0
                </p>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <p class="text-sm font-medium text-slate-500">
                    Homestay
                </p>

                <p class="mt-3 text-3xl font-bold text-slate-900">
                    0
                </p>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <p class="text-sm font-medium text-slate-500">
                    Đơn đặt phòng
                </p>

                <p class="mt-3 text-3xl font-bold text-slate-900">
                    0
                </p>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <p class="text-sm font-medium text-slate-500">
                    Danh mục
                </p>

                <p class="mt-3 text-3xl font-bold text-slate-900">
                    0
                </p>
            </div>

        </div>

        <div class="mt-8 rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">

            <h2 class="text-xl font-bold text-slate-900">
                Chức năng quản trị
            </h2>

            <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">

                <a
                    href="{{ route('admin.categories.index') }}"
                    class="rounded-2xl border border-slate-200 p-5 transition hover:border-blue-300 hover:bg-blue-50"
                >
                    <h3 class="font-bold text-slate-900">
                        Quản lý danh mục
                    </h3>

                    <p class="mt-2 text-sm text-slate-500">
                        Thêm, sửa và xóa danh mục Homestay.
                    </p>
                </a>

                <a
                    href="{{ route('admin.homestays.index') }}"
                    class="rounded-2xl border border-slate-200 p-5 transition hover:border-blue-300 hover:bg-blue-50"
                >
                    <h3 class="font-bold text-slate-900">
                        Quản lý Homestay
                    </h3>

                    <p class="mt-2 text-sm text-slate-500">
                        Quản lý thông tin và trạng thái Homestay.
                    </p>
                </a>

                <a
                    href="#"
                    class="rounded-2xl border border-slate-200 p-5 transition hover:border-blue-300 hover:bg-blue-50"
                >
                    <h3 class="font-bold text-slate-900">
                        Quản lý đặt phòng
                    </h3>

                    <p class="mt-2 text-sm text-slate-500">
                        Theo dõi và xác nhận các đơn đặt phòng.
                    </p>
                </a>

            </div>

        </div>

    </main>

</body>

</html>