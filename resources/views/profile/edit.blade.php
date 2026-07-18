<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Hồ sơ cá nhân | HomeStay</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-slate-50">

    @include('partials.navbar')

    <main class="mx-auto max-w-5xl px-4 py-12 sm:px-6 lg:px-8">

        <div class="mb-8">
            <h1 class="text-3xl font-bold text-slate-900">
                Hồ sơ cá nhân
            </h1>

            <p class="mt-2 text-slate-500">
                Quản lý thông tin tài khoản và mật khẩu của bạn.
            </p>
        </div>

        @if (session('status') === 'profile-updated')
            <div class="mb-6 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm font-medium text-green-700">
                Cập nhật thông tin thành công.
            </div>
        @endif

        @if (session('status') === 'password-updated')
            <div class="mb-6 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm font-medium text-green-700">
                Đổi mật khẩu thành công.
            </div>
        @endif

        <div class="grid gap-8 lg:grid-cols-3">

            {{-- Thông tin tài khoản --}}
            <aside class="lg:col-span-1">
                <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">

                    <div class="flex flex-col items-center text-center">

                        <div class="flex h-24 w-24 items-center justify-center rounded-full bg-blue-600 text-3xl font-bold text-white">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>

                        <h2 class="mt-4 text-xl font-bold text-slate-900">
                            {{ auth()->user()->name }}
                        </h2>

                        <p class="mt-1 text-sm text-slate-500">
                            {{ auth()->user()->email }}
                        </p>

                        <span class="mt-4 rounded-full bg-blue-50 px-4 py-2 text-sm font-semibold text-blue-600">
                            {{ auth()->user()->role === 'admin' ? 'Quản trị viên' : 'Người dùng' }}
                        </span>

                    </div>

                    <div class="mt-6 border-t border-slate-200 pt-6">

                        <div class="flex items-center justify-between text-sm">
                            <span class="text-slate-500">
                                Ngày tham gia
                            </span>

                            <span class="font-semibold text-slate-700">
                                {{ auth()->user()->created_at->format('d/m/Y') }}
                            </span>
                        </div>

                    </div>

                </div>
            </aside>

            <div class="space-y-8 lg:col-span-2">

                {{-- Cập nhật thông tin --}}
                <section class="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">

                    <div class="mb-6">
                        <h2 class="text-xl font-bold text-slate-900">
                            Thông tin cá nhân
                        </h2>

                        <p class="mt-2 text-sm text-slate-500">
                            Cập nhật họ tên và địa chỉ email của bạn.
                        </p>
                    </div>

                    <form method="POST" action="{{ route('profile.update') }}" class="space-y-5">

                        @csrf
                        @method('PATCH')

                        <div>
                            <label for="name" class="mb-2 block text-sm font-semibold text-slate-700">
                                Họ và tên
                            </label>

                            <input
                                id="name"
                                name="name"
                                type="text"
                                value="{{ old('name', auth()->user()->name) }}"
                                required
                                autocomplete="name"
                                class="w-full rounded-xl border border-slate-300 px-4 py-3 text-slate-900 outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                            >

                            @error('name')
                                <p class="mt-2 text-sm font-medium text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div>
                            <label for="email" class="mb-2 block text-sm font-semibold text-slate-700">
                                Địa chỉ email
                            </label>

                            <input
                                id="email"
                                name="email"
                                type="email"
                                value="{{ old('email', auth()->user()->email) }}"
                                required
                                autocomplete="username"
                                class="w-full rounded-xl border border-slate-300 px-4 py-3 text-slate-900 outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                            >

                            @error('email')
                                <p class="mt-2 text-sm font-medium text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div class="flex justify-end">
                            <button
                                type="submit"
                                class="rounded-xl bg-blue-600 px-6 py-3 font-semibold text-white transition hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-200"
                            >
                                Lưu thay đổi
                            </button>
                        </div>

                    </form>

                </section>

                {{-- Đổi mật khẩu --}}
                <section class="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">

                    <div class="mb-6">
                        <h2 class="text-xl font-bold text-slate-900">
                            Đổi mật khẩu
                        </h2>

                        <p class="mt-2 text-sm text-slate-500">
                            Sử dụng mật khẩu mạnh để bảo vệ tài khoản.
                        </p>
                    </div>

                    <form method="POST" action="{{ route('password.update') }}" class="space-y-5">

                        @csrf
                        @method('PUT')

                        <div>
                            <label for="current_password" class="mb-2 block text-sm font-semibold text-slate-700">
                                Mật khẩu hiện tại
                            </label>

                            <input
                                id="current_password"
                                name="current_password"
                                type="password"
                                autocomplete="current-password"
                                class="w-full rounded-xl border border-slate-300 px-4 py-3 outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                            >

                            @error('current_password', 'updatePassword')
                                <p class="mt-2 text-sm font-medium text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div>
                            <label for="password" class="mb-2 block text-sm font-semibold text-slate-700">
                                Mật khẩu mới
                            </label>

                            <input
                                id="password"
                                name="password"
                                type="password"
                                autocomplete="new-password"
                                class="w-full rounded-xl border border-slate-300 px-4 py-3 outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                            >

                            @error('password', 'updatePassword')
                                <p class="mt-2 text-sm font-medium text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div>
                            <label for="password_confirmation" class="mb-2 block text-sm font-semibold text-slate-700">
                                Xác nhận mật khẩu mới
                            </label>

                            <input
                                id="password_confirmation"
                                name="password_confirmation"
                                type="password"
                                autocomplete="new-password"
                                class="w-full rounded-xl border border-slate-300 px-4 py-3 outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                            >
                        </div>

                        <div class="flex justify-end">
                            <button
                                type="submit"
                                class="rounded-xl bg-blue-600 px-6 py-3 font-semibold text-white transition hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-200"
                            >
                                Cập nhật mật khẩu
                            </button>
                        </div>

                    </form>

                </section>

            </div>

        </div>

    </main>

</body>

</html>