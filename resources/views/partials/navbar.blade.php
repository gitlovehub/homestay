<header class="sticky top-0 z-50 border-b border-slate-200 bg-white/95 backdrop-blur">
    <nav class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8">

        {{-- Logo --}}
        <a href="{{ route('home') }}" class="flex items-center gap-3">
            <span
                class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-600 text-lg font-bold text-white">
                H
            </span>

            <span class="text-2xl font-bold text-slate-900">
                HomeStay<span class="text-blue-600">Go</span>
            </span>
        </a>

        {{-- Menu --}}
        <div class="hidden items-center gap-8 md:flex">
            <a href="{{ route('home') }}"
                class="font-medium text-blue-600 transition hover:text-blue-700">
                Trang chủ
            </a>

            <a href="#featured"
                class="font-medium text-slate-600 transition hover:text-blue-600">
                Homestay
            </a>

            <a href="#about"
                class="font-medium text-slate-600 transition hover:text-blue-600">
                Giới thiệu
            </a>

            <a href="#contact"
                class="font-medium text-slate-600 transition hover:text-blue-600">
                Liên hệ
            </a>
        </div>

        {{-- Right --}}
        <div class="flex items-center gap-3">

            @auth

                <span class="hidden text-sm font-medium text-slate-600 lg:block">
                    Xin chào, {{ auth()->user()->name }}
                </span>

                @if(Route::has('dashboard'))
                    <a href="{{ route('dashboard') }}"
                        class="rounded-xl border border-blue-600 px-4 py-2 text-sm font-semibold text-blue-600 transition hover:bg-blue-50">
                        Quản trị
                    </a>
                @endif

                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <button
                        type="submit"
                        class="rounded-xl bg-red-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-red-700">
                        Đăng xuất
                    </button>
                </form>

            @else

                <a href="{{ route('login') }}"
                    class="rounded-xl px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-100">
                    Đăng nhập
                </a>

                <a href="{{ route('register') }}"
                    class="rounded-xl bg-blue-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-blue-700">
                    Đăng ký
                </a>

            @endauth

        </div>

    </nav>
</header>