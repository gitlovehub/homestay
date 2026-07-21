<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Quên mật khẩu | HomeStay</title>

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
                        Quên mật khẩu?
                    </h1>

                    <p class="mt-3 text-sm leading-6 text-slate-500">
                        Nhập địa chỉ email đã đăng ký. Chúng tôi sẽ gửi cho bạn liên kết để tạo mật khẩu mới.
                    </p>

                </div>

                @if (session('status'))
                    <div
                        class="mb-6 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm font-medium text-green-700">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}" class="space-y-6">

                    @csrf

                    <div>
                        <label for="email" class="mb-2 block text-sm font-semibold text-slate-700">
                            Địa chỉ email
                        </label>

                        <input
                            id="email"
                            name="email"
                            type="email"
                            value="{{ old('email') }}"
                            required
                            autofocus
                            autocomplete="email"
                            placeholder="example@gmail.com"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3 text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                        >

                        @error('email')
                            <p class="mt-2 text-sm font-medium text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <button
                        type="submit"
                        class="w-full rounded-xl bg-blue-600 px-4 py-3 font-semibold text-white transition hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-200">
                        Gửi liên kết đặt lại mật khẩu
                    </button>

                </form>

                <div class="mt-6 text-center">

                    <a
                        href="{{ route('login') }}"
                        class="inline-flex items-center gap-2 text-sm font-semibold text-blue-600 transition hover:text-blue-700">

                        <span>←</span>
                        Quay lại đăng nhập

                    </a>

                </div>

            </div>

        </div>

    </main>

</body>

</html>