@extends('layouts.app')

@section('title', 'Quên mật khẩu - HomeStayGo')

@section('content')
<section class="relative overflow-hidden bg-slate-50 py-14 sm:py-20">
    <div class="absolute -left-28 top-16 h-72 w-72 rounded-full bg-blue-200/40 blur-3xl"></div>
    <div class="absolute -right-28 bottom-0 h-80 w-80 rounded-full bg-indigo-200/40 blur-3xl"></div>

    <div class="relative mx-auto flex max-w-6xl justify-center px-4 sm:px-6 lg:px-8">
        <div class="w-full max-w-lg">
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
                        Quên mật khẩu?
                    </h2>
                    <p class="mt-2 text-sm leading-6 text-slate-500">
                        Nhập địa chỉ email đã đăng ký. Chúng tôi sẽ gửi liên kết để tạo mật khẩu mới.
                    </p>
                </div>

                @if (session('status'))
                    <div class="mt-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}" class="mt-8 space-y-5">
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
                            autofocus
                            autocomplete="email"
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

                    <button
                        type="submit"
                        class="w-full cursor-pointer rounded-xl bg-blue-600 px-5 py-3.5 text-sm font-semibold text-white shadow-lg shadow-blue-600/20 transition hover:-translate-y-0.5 hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-200"
                    >
                        Gửi liên kết đặt lại mật khẩu
                    </button>
                </form>

                <div class="mt-7 border-t border-slate-200 pt-6 text-center">
                    <a href="{{ route('login') }}" class="text-sm font-semibold text-slate-500 hover:text-blue-600">
                        ← Quay lại đăng nhập
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
