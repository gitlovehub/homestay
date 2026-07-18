@extends('layouts.app')

@section('title', 'Đăng nhập - HomeStayGo')

@section('content')
<section class="relative overflow-hidden bg-slate-50 py-14 sm:py-20">
    <div class="absolute -left-28 top-16 h-72 w-72 rounded-full bg-blue-200/40 blur-3xl"></div>
    <div class="absolute -right-28 bottom-0 h-80 w-80 rounded-full bg-indigo-200/40 blur-3xl"></div>

    <div class="relative mx-auto grid max-w-6xl items-center gap-12 px-4 sm:px-6 lg:grid-cols-2 lg:px-8">
        {{-- Phần giới thiệu --}}
        <div class="hidden lg:block">
            <span class="inline-flex rounded-full bg-blue-100 px-4 py-2 text-sm font-semibold text-blue-700">
                Chào mừng bạn quay lại
            </span>

            <h1 class="mt-6 text-4xl font-bold leading-tight tracking-tight text-slate-950 xl:text-5xl">
                Đăng nhập để tiếp tục cùng
                <span class="text-blue-600">HomeStayGo</span>
            </h1>

            <p class="mt-5 max-w-lg text-lg leading-8 text-slate-600">
                Quản lý lịch sử đặt phòng, cập nhật thông tin cá nhân và tiếp tục
                khám phá những Homestay phù hợp với chuyến đi của bạn.
            </p>

            <div class="mt-10 space-y-5">
                <div class="flex items-start gap-4">
                    <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-blue-100 text-xl">
                        🏠
                    </span>

                    <div>
                        <h3 class="font-bold text-slate-900">Nhiều lựa chọn lưu trú</h3>
                        <p class="mt-1 text-sm leading-6 text-slate-500">
                            Dễ dàng tìm Homestay phù hợp theo địa điểm và nhu cầu.
                        </p>
                    </div>
                </div>

                <div class="flex items-start gap-4">
                    <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-emerald-100 text-xl">
                        ✓
                    </span>

                    <div>
                        <h3 class="font-bold text-slate-900">Theo dõi đặt phòng</h3>
                        <p class="mt-1 text-sm leading-6 text-slate-500">
                            Xem trạng thái và lịch sử các đơn đã đặt.
                        </p>
                    </div>
                </div>

                <div class="flex items-start gap-4">
                    <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-amber-100 text-xl">
                        🔒
                    </span>

                    <div>
                        <h3 class="font-bold text-slate-900">Bảo mật thông tin</h3>
                        <p class="mt-1 text-sm leading-6 text-slate-500">
                            Thông tin tài khoản được quản lý an toàn.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Form Login --}}
        <div class="mx-auto w-full max-w-lg">
            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-xl shadow-slate-900/10 sm:p-9">
                <div class="text-center">
                    <a href="{{ route('home') }}" class="inline-flex items-center gap-2">
                        <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-blue-600 text-xl font-bold text-white">
                            H
                        </span>

                        <span class="text-2xl font-bold text-slate-950">
                            HomeStay<span class="text-blue-600">Go</span>
                        </span>
                    </a>

                    <h2 class="mt-7 text-3xl font-bold tracking-tight text-slate-950">
                        Đăng nhập
                    </h2>

                    <p class="mt-2 text-sm text-slate-500">
                        Nhập thông tin tài khoản để tiếp tục.
                    </p>
                </div>

                @if (session('status'))
                    <div class="mt-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="mt-8 space-y-5">
                    @csrf

                    {{-- Email --}}
                    <div>
                        <label for="email" class="mb-2 block text-sm font-semibold text-slate-700">
                            Email
                        </label>

                        <input
                            id="email"
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            placeholder="example@gmail.com"
                            autocomplete="email"
                            required
                            autofocus
                            class="w-full rounded-xl border px-4 py-3.5 text-slate-900 outline-none transition placeholder:text-slate-400
                                {{ $errors->has('email')
                                    ? 'border-red-400 bg-red-50 focus:border-red-500 focus:ring-4 focus:ring-red-100'
                                    : 'border-slate-300 focus:border-blue-500 focus:ring-4 focus:ring-blue-100' }}"
                        >

                        @error('email')
                            <p class="mt-2 text-sm font-medium text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div>
                        <div class="mb-2 flex items-center justify-between">
                            <label for="password" class="text-sm font-semibold text-slate-700">
                                Mật khẩu
                            </label>

                            @if (Route::has('password.request'))
                                <a
                                    href="{{ route('password.request') }}"
                                    class="text-sm font-semibold text-blue-600 hover:text-blue-700"
                                >
                                    Quên mật khẩu?
                                </a>
                            @endif
                        </div>

                        <div class="relative">
                            <input
                                id="password"
                                type="password"
                                name="password"
                                placeholder="Nhập mật khẩu"
                                autocomplete="current-password"
                                required
                                class="w-full rounded-xl border border-slate-300 px-4 py-3.5 pr-16 text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                            >

                            <button
                                id="togglePassword"
                                type="button"
                                class="absolute inset-y-0 right-0 px-4 text-sm font-semibold text-slate-500 hover:text-blue-600"
                            >
                                Hiện
                            </button>
                        </div>

                        @error('password')
                            <p class="mt-2 text-sm font-medium text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Remember --}}
                    <label class="flex cursor-pointer items-center gap-3">
                        <input
                            type="checkbox"
                            name="remember"
                            class="h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500"
                            @checked(old('remember'))
                        >

                        <span class="text-sm text-slate-600">
                            Ghi nhớ đăng nhập
                        </span>
                    </label>

                    <button
                        type="submit"
                        class="w-full rounded-xl bg-blue-600 px-5 py-3.5 font-semibold text-white shadow-lg shadow-blue-600/20 transition hover:-translate-y-0.5 hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-200 cursor-pointer"
                    >
                        Đăng nhập
                    </button>
                </form>

                @if (Route::has('register'))
                    <p class="mt-7 text-center text-sm text-slate-500">
                        Chưa có tài khoản?

                        <a
                            href="{{ route('register') }}"
                            class="font-bold text-blue-600 hover:text-blue-700"
                        >
                            Đăng ký ngay
                        </a>
                    </p>
                @endif

                <div class="mt-7 border-t border-slate-200 pt-6 text-center">
                    <a
                        href="{{ route('home') }}"
                        class="text-sm font-semibold text-slate-500 hover:text-blue-600"
                    >
                        ← Quay về trang chủ
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const passwordInput = document.getElementById('password');
        const toggleButton = document.getElementById('togglePassword');

        if (!passwordInput || !toggleButton) {
            return;
        }

        toggleButton.addEventListener('click', function () {
            const hidden = passwordInput.type === 'password';

            passwordInput.type = hidden ? 'text' : 'password';
            toggleButton.textContent = hidden ? 'Ẩn' : 'Hiện';
        });
    });
</script>
@endpush