<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Đăng ký | HomeStay</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-slate-50">

    @include('partials.navbar')

    <main class="flex min-h-[calc(100vh-80px)] items-center justify-center px-4 py-12">

        <div class="w-full max-w-lg">

            <div class="rounded-3xl border border-slate-200 bg-white p-8 shadow-xl shadow-slate-200/60 sm:p-10">

                <div class="mb-8 text-center">

                    <div
                        class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-2xl bg-blue-600 text-2xl font-bold text-white">
                        H
                    </div>

                    <h1 class="text-3xl font-bold text-slate-900">
                        Tạo tài khoản
                    </h1>

                    <p class="mt-3 text-sm leading-6 text-slate-500">
                        Đăng ký tài khoản để tìm kiếm và đặt Homestay dễ dàng hơn.
                    </p>

                </div>

                <form method="POST" action="{{ route('register') }}" class="space-y-5">

                    @csrf

                    {{-- Họ và tên --}}
                    <div>
                        <label for="name" class="mb-2 block text-sm font-semibold text-slate-700">
                            Họ và tên
                        </label>

                        <input id="name" name="name" type="text" value="{{ old('name') }}" autofocus
                            autocomplete="name" placeholder="Nhập họ và tên"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3 text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:ring-4 focus:ring-blue-100">

                        @error('name')
                            <p class="mt-2 text-sm font-medium text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div>
                        <label for="email" class="mb-2 block text-sm font-semibold text-slate-700">
                            Địa chỉ email
                        </label>

                        <input id="email" name="email" type="email" value="{{ old('email') }}"
                            autocomplete="username" placeholder="example@gmail.com"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3 text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:ring-4 focus:ring-blue-100">

                        @error('email')
                            <p class="mt-2 text-sm font-medium text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Mật khẩu --}}
                    <div>
                        <label for="password" class="mb-2 block text-sm font-semibold text-slate-700">
                            Mật khẩu
                        </label>

                        <input id="password" name="password" type="password" autocomplete="new-password"
                            placeholder="Tối thiểu 8 ký tự"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3 text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:ring-4 focus:ring-blue-100">

                        @error('password')
                            <p class="mt-2 text-sm font-medium text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Xác nhận mật khẩu --}}
                    <div>
                        <label for="password_confirmation" class="mb-2 block text-sm font-semibold text-slate-700">
                            Xác nhận mật khẩu
                        </label>

                        <input id="password_confirmation" name="password_confirmation" type="password"
                            autocomplete="new-password" placeholder="Nhập lại mật khẩu"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3 text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:ring-4 focus:ring-blue-100">
                    </div>

                    {{-- Điều khoản --}}
                    <div>
                        <label class="flex items-start gap-3">

                            <input
                                type="checkbox"
                                name="terms"
                                value="1"
                                {{ old('terms') ? 'checked' : '' }}
                                class="mt-1 h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500"
                            >

                            <span class="text-sm leading-6 text-slate-600">
                                Tôi đồng ý với

                                <a href="#" class="font-semibold text-blue-600 hover:text-blue-700">
                                    điều khoản sử dụng
                                </a>

                                và

                                <a href="#" class="font-semibold text-blue-600 hover:text-blue-700">
                                    chính sách bảo mật
                                </a>.
                            </span>

                        </label>

                        @error('terms')
                            <p class="mt-2 text-sm font-medium text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <button type="submit"
                        class="w-full rounded-xl bg-blue-600 px-4 py-3 font-semibold text-white transition hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-200">
                        Đăng ký tài khoản
                    </button>

                </form>

                <div class="mt-6 text-center">

                    <p class="text-sm text-slate-600">
                        Đã có tài khoản?

                        <a href="{{ route('login') }}"
                            class="font-semibold text-blue-600 transition hover:text-blue-700">
                            Đăng nhập ngay
                        </a>
                    </p>

                </div>

            </div>

        </div>

    </main>

</body>

</html>
