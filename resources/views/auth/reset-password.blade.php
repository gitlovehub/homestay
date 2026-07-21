<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Đặt lại mật khẩu | HomeStay</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-slate-50">

@include('partials.navbar')

<main class="flex min-h-[calc(100vh-80px)] items-center justify-center px-4 py-12">

    <div class="w-full max-w-md">

        <div class="rounded-3xl border border-slate-200 bg-white p-8 shadow-xl shadow-slate-200/60">

            <div class="mb-8 text-center">

                <div
                    class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-2xl bg-blue-600 text-2xl font-bold text-white">
                    H
                </div>

                <h1 class="text-3xl font-bold text-slate-900">
                    Đặt lại mật khẩu
                </h1>

                <p class="mt-3 text-sm text-slate-500">
                    Nhập mật khẩu mới để tiếp tục sử dụng tài khoản.
                </p>

            </div>

            <form method="POST" action="{{ route('password.store') }}" class="space-y-5">

                @csrf

                <input
                    type="hidden"
                    name="token"
                    value="{{ request()->route('token') }}">

                <div>

                    <label class="mb-2 block text-sm font-semibold text-slate-700">
                        Email
                    </label>

                    <input
                        type="email"
                        name="email"
                        value="{{ old('email', request('email')) }}"
                        required
                        class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:border-blue-500 focus:ring-4 focus:ring-blue-100">

                    @error('email')
                        <p class="mt-2 text-sm text-red-600">
                            {{ $message }}
                        </p>
                    @enderror

                </div>

                <div>

                    <label class="mb-2 block text-sm font-semibold text-slate-700">
                        Mật khẩu mới
                    </label>

                    <input
                        type="password"
                        name="password"
                        required
                        class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:border-blue-500 focus:ring-4 focus:ring-blue-100">

                    @error('password')
                        <p class="mt-2 text-sm text-red-600">
                            {{ $message }}
                        </p>
                    @enderror

                </div>

                <div>

                    <label class="mb-2 block text-sm font-semibold text-slate-700">
                        Xác nhận mật khẩu
                    </label>

                    <input
                        type="password"
                        name="password_confirmation"
                        required
                        class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:border-blue-500 focus:ring-4 focus:ring-blue-100">

                </div>

                <button
                    type="submit"
                    class="w-full rounded-xl bg-blue-600 py-3 font-semibold text-white transition hover:bg-blue-700">

                    Đặt lại mật khẩu

                </button>

            </form>

            <div class="mt-6 text-center">

                <a
                    href="{{ route('login') }}"
                    class="text-sm font-semibold text-blue-600 hover:text-blue-700">

                    ← Quay lại đăng nhập

                </a>

            </div>

        </div>

    </div>

</main>

</body>
</html>