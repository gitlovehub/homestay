<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Kiểm tra Tailwind</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-slate-100">
    <main class="mx-auto max-w-5xl px-6 py-20">
        <div class="rounded-3xl bg-white p-10 shadow-xl">
            <p class="font-semibold uppercase tracking-widest text-blue-600">
                DashboardStayGo
            </p>

            <h1 class="mt-3 text-5xl font-bold text-slate-900">
                Tailwind CSS đã chạy thành công
            </h1>

            <p class="mt-5 text-lg text-slate-600">
                Dự án Laravel đã sẵn sàng để xây dựng giao diện trang chủ.
            </p>

            <button
                class="mt-8 rounded-xl bg-blue-600 px-6 py-3 font-semibold text-white hover:bg-blue-700"
            >
                Kiểm tra giao diện
            </button>
        </div>
    </main>
</body>
</html>