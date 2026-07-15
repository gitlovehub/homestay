<header class="sticky top-0 z-50 border-b border-slate-200 bg-white/95 backdrop-blur">
    <nav class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
        <a
            href="{{ route('home') }}"
            class="flex items-center gap-2"
        >
            <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-600 text-xl text-white">
                H
            </span>

            <span class="text-xl font-bold tracking-tight text-slate-900">
                HomeStay<span class="text-blue-600">Go</span>
            </span>
        </a>

        <div class="hidden items-center gap-8 md:flex">
            <a
                href="{{ route('home') }}"
                class="font-medium text-blue-600"
            >
                Trang chủ
            </a>

            <a
                href="#featured"
                class="font-medium text-slate-600 transition hover:text-blue-600"
            >
                Homestay
            </a>

            <a
                href="#about"
                class="font-medium text-slate-600 transition hover:text-blue-600"
            >
                Giới thiệu
            </a>

            <a
                href="#contact"
                class="font-medium text-slate-600 transition hover:text-blue-600"
            >
                Liên hệ
            </a>
        </div>

        <div class="flex items-center gap-3">
            @auth
                <span class="hidden text-sm font-medium text-slate-600 sm:block">
                    Xin chào, {{ auth()->user()->name }}
                </span>

                @if (Route::has('dashboard'))
                    <a
                        href="{{ route('dashboard') }}"
                        class="rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-700"
                    >
                        Tài khoản
                    </a>
                @endif
            @else
                @if (Route::has('login'))
                    <a
                        href="{{ route('login') }}"
                        class="hidden rounded-xl px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-100 sm:inline-flex"
                    >
                        Đăng nhập
                    </a>
                @endif

                @if (Route::has('register'))
                    <a
                        href="{{ route('register') }}"
                        class="rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-700"
                    >
                        Đăng ký
                    </a>
                @endif
            @endauth
        </div>
    </nav>
</header>