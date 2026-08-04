@extends('layouts.app')

@section('title', 'Đăng ký - HomeStayGo')

@section('content')

<section class="relative overflow-hidden bg-slate-50 py-14 sm:py-20">
    <div class="absolute -left-28 top-16 h-72 w-72 rounded-full bg-blue-200/40 blur-3xl"></div>
    <div class="absolute -right-28 bottom-0 h-80 w-80 rounded-full bg-indigo-200/40 blur-3xl"></div>

    <div class="relative mx-auto flex max-w-6xl justify-center px-4 sm:px-6 lg:px-8">

        {{-- Form Đăng ký --}}
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

                    <h2 class="mt-7 text-3xl font-bold tracking-tight text-slate-950">Tạo tài khoản</h2>
                    <p class="mt-2 text-sm text-slate-500">
                        Đăng ký để tìm kiếm và đặt Homestay dễ dàng hơn.
                    </p>
                </div>

                <form method="POST" action="{{ route('register') }}" class="mt-8 space-y-5">
                    @csrf

                    {{-- Họ và tên --}}
                    <div>
                        <label for="name" class="mb-2 block text-sm font-semibold text-slate-700">
                            Họ và tên
                        </label>
                        <input
                            id="name"
                            name="name"
                            type="text"
                            value="{{ old('name') }}"
                            autofocus
                            autocomplete="name"
                            placeholder="Nhập họ và tên"
                            class="h-11 w-full rounded-xl border px-4 text-sm text-slate-900 outline-none transition placeholder:text-slate-400
                                {{ $errors->has('name')
                                    ? 'border-red-400 bg-red-50 focus:border-red-500 focus:ring-4 focus:ring-red-100'
                                    : 'border-slate-300 focus:border-blue-500 focus:ring-4 focus:ring-blue-100' }}"
                        >
                        @error('name')
                            <p class="mt-2 text-sm font-medium text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div>
                        <label for="email" class="mb-2 block text-sm font-semibold text-slate-700">
                            Địa chỉ email
                        </label>
                        <input
                            id="email"
                            name="email"
                            type="email"
                            value="{{ old('email') }}"
                            autocomplete="username"
                            placeholder="example@gmail.com"
                            class="h-11 w-full rounded-xl border px-4 text-sm text-slate-900 outline-none transition placeholder:text-slate-400
                                {{ $errors->has('email')
                                    ? 'border-red-400 bg-red-50 focus:border-red-500 focus:ring-4 focus:ring-red-100'
                                    : 'border-slate-300 focus:border-blue-500 focus:ring-4 focus:ring-blue-100' }}"
                        >
                        @error('email')
                            <p class="mt-2 text-sm font-medium text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Mật khẩu --}}
                    <div>
                        <label for="password" class="mb-2 block text-sm font-semibold text-slate-700">
                            Mật khẩu
                        </label>
                        <div class="relative" x-data="{ show: false }">
                            <input
                                id="password"
                                name="password"
                                :type="show ? 'text' : 'password'"
                                autocomplete="new-password"
                                placeholder="Tối thiểu 8 ký tự"
                                class="h-11 w-full rounded-xl border px-4 pr-11 text-sm text-slate-900 outline-none transition placeholder:text-slate-400
                                    {{ $errors->has('password')
                                        ? 'border-red-400 bg-red-50 focus:border-red-500 focus:ring-4 focus:ring-red-100'
                                        : 'border-slate-300 focus:border-blue-500 focus:ring-4 focus:ring-blue-100' }}"
                            >
                            <button
                                type="button"
                                @click="show = !show"
                                class="absolute inset-y-0 right-0 flex w-11 cursor-pointer items-center justify-center text-slate-400 transition hover:text-blue-600"
                                :aria-label="show ? 'Ẩn mật khẩu' : 'Hiện mật khẩu'"
                            >
                                <svg x-show="!show" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <svg x-show="show" x-cloak class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                                </svg>
                            </button>
                        </div>
                        @error('password')
                            <p class="mt-2 text-sm font-medium text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Xác nhận mật khẩu --}}
                    <div>
                        <label for="password_confirmation" class="mb-2 block text-sm font-semibold text-slate-700">
                            Xác nhận mật khẩu
                        </label>
                        <div class="relative" x-data="{ show: false }">
                            <input
                                id="password_confirmation"
                                name="password_confirmation"
                                :type="show ? 'text' : 'password'"
                                autocomplete="new-password"
                                placeholder="Nhập lại mật khẩu"
                                class="h-11 w-full rounded-xl border border-slate-300 px-4 pr-11 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                            >
                            <button
                                type="button"
                                @click="show = !show"
                                class="absolute inset-y-0 right-0 flex w-11 cursor-pointer items-center justify-center text-slate-400 transition hover:text-blue-600"
                                :aria-label="show ? 'Ẩn mật khẩu' : 'Hiện mật khẩu'"
                            >
                                <svg x-show="!show" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <svg x-show="show" x-cloak class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    {{-- Điều khoản --}}
                    <div>
                        <label class="flex cursor-pointer items-start gap-3">
                            <input
                                type="checkbox"
                                name="terms"
                                value="1"
                                {{ old('terms') ? 'checked' : '' }}
                                class="mt-1 h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500"
                            >
                            <span class="text-sm leading-6 text-slate-600">
                                Tôi đồng ý với
                                <a href="#" class="font-semibold text-blue-600 hover:text-blue-700">điều khoản sử dụng</a>
                                và
                                <a href="#" class="font-semibold text-blue-600 hover:text-blue-700">chính sách bảo mật</a>.
                            </span>
                        </label>
                        @error('terms')
                            <p class="mt-2 text-sm font-medium text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <button
                        type="submit"
                        class="w-full cursor-pointer rounded-xl bg-blue-600 px-5 py-3.5 text-sm font-semibold text-white shadow-lg shadow-blue-600/20 transition hover:-translate-y-0.5 hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-200"
                    >
                        Đăng ký tài khoản
                    </button>
                </form>

                <p class="mt-7 text-center text-sm text-slate-500">
                    Đã có tài khoản?
                    <a href="{{ route('login') }}" class="font-bold text-blue-600 hover:text-blue-700">
                        Đăng nhập ngay
                    </a>
                </p>

                <div class="mt-7 border-t border-slate-200 pt-6 text-center">
                    <a href="{{ route('home') }}" class="text-sm font-semibold text-slate-500 hover:text-blue-600">
                        ← Quay về trang chủ
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
