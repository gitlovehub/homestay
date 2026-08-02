<header class="sticky top-0 z-50 border-b border-slate-200 bg-white/95 backdrop-blur">
    <nav class="mx-auto flex max-w-7xl items-center justify-between px-4 py-5 sm:px-6 lg:px-8">
        {{-- Logo --}}
        <a href="{{ route('home') }}" class="flex items-center gap-3">
            <span
                class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-600 text-lg font-bold text-white sm:h-11 sm:w-11">
                H
            </span>
            <span class="text-xl font-bold text-slate-900 sm:text-2xl">
                HomeStay<span class="text-blue-600">Go</span>
            </span>
        </a>

        {{-- Desktop Menu --}}
        <div class="hidden items-center gap-8 md:flex">
            <a href="{{ route('home') }}" class="font-medium text-blue-600 transition hover:text-blue-700">
                Trang chủ
            </a>
            <a href="{{ route('homestays.index') }}" class="font-medium text-slate-600 transition hover:text-blue-600">
                Homestay
            </a>

            {{-- Danh mục Desktop dạng full width --}}
            <div
                x-data="{ categoryOpen: false }"
                class="static"
                @mouseenter="categoryOpen = true"
                @mouseleave="categoryOpen = false"
            >
                {{-- Nút Danh mục --}}
                <div
                    class="flex items-center rounded-lg px-3 py-2.5 relative after:absolute after:left-0 after:right-0 after:top-full after:h-5 after:content-['']"
                >
                    <a
                        href="{{ route('categories.index') }}"
                        class="font-medium transition
                            {{ request()->routeIs('categories.*')
                                ? 'text-blue-600'
                                : 'text-slate-600 hover:text-blue-600' }}"
                    >
                        Danh mục
                    </a>

                    <button
                        type="button"
                        @click="categoryOpen = !categoryOpen"
                        class="ml-1 flex cursor-pointer items-center text-slate-500 transition hover:text-blue-600"
                        aria-label="Mở danh sách danh mục"
                    >
                        <svg
                            class="h-4 w-4 transition duration-200"
                            :class="{ 'rotate-180': categoryOpen }"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M19 9l-7 7-7-7"
                            />
                        </svg>
                    </button>
                </div>

                {{-- Mega menu full width --}}
                <div
                    x-show="categoryOpen"
                    x-cloak
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 -translate-y-2"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 translate-y-0"
                    x-transition:leave-end="opacity-0 -translate-y-2"
                    @click.outside="categoryOpen = false"
                    class="absolute inset-x-0 top-full z-50 border-t border-slate-200 bg-white shadow-xl"
                >
                    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">

                        {{-- Tiêu đề --}}
                        <div class="mb-5 flex items-center justify-between gap-4">
                            <div>
                                <h2 class="text-lg font-bold text-slate-900">
                                    Danh mục Homestay
                                </h2>

                                <p class="mt-1 text-sm text-slate-500">
                                    Lựa chọn loại hình lưu trú phù hợp với chuyến đi của bạn.
                                </p>
                            </div>

                            <a
                                href="{{ route('categories.index') }}"
                                class="shrink-0 text-sm font-semibold text-blue-600 transition hover:text-blue-700 hover:translate-x-1"
                            >
                                Xem tất cả →
                            </a>
                        </div>

                        {{-- Danh sách danh mục chia nhiều cột --}}
                        <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-5">
                            @forelse ($navCategories as $category)
                                <a
                                    href="{{ route('categories.show', $category->slug) }}"
                                    class="group flex items-center justify-between rounded-xl border border-slate-200 bg-white px-4 py-3.5 transition hover:border-blue-200 hover:bg-blue-50 hover:shadow-sm"
                                >
                                    <span
                                        class="truncate text-sm font-semibold text-slate-700 transition group-hover:text-blue-600"
                                    >
                                        {{ $category->name }}
                                    </span>

                                    <svg
                                        class="h-4 w-4 shrink-0 text-slate-400 transition group-hover:translate-x-1 group-hover:text-blue-600"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M9 5l7 7-7 7"
                                        />
                                    </svg>
                                </a>
                            @empty
                                <div
                                    class="col-span-full rounded-xl border border-dashed border-slate-300 px-5 py-8 text-center"
                                >
                                    <p class="text-sm text-slate-500">
                                        Chưa có danh mục Homestay.
                                    </p>
                                </div>
                            @endforelse
                        </div>

                    </div>
                </div>
            </div>

            <a href="{{ route('about') }}"
                class="font-semibold transition
                    {{ request()->routeIs('about')
                        ? 'text-blue-600'
                        : 'text-slate-600 hover:text-blue-600' }}">
                Giới thiệu
            </a>
        <a
            href="{{ route('contact') }}"
            class="inline-block text-base font-medium transition
                {{ request()->routeIs('contact.*')
                    ? 'text-blue-600'
                    : 'text-slate-600 hover:text-blue-600' }}"
        >
            Liên hệ
        </a>
        </div>

        {{-- Right side (Desktop) --}}
        <div class="hidden items-center gap-3 md:flex">
            @auth
                {{-- User dropdown --}}
                <div class="relative" id="user-dropdown">
                    <button type="button" id="user-menu-button"
                        class="cursor-pointer flex items-center gap-2.5 focus:outline-none" aria-expanded="false"
                        aria-haspopup="true">
                        @if (auth()->user()->avatar)
                            <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="{{ auth()->user()->name }}"
                                class="h-9 w-9 rounded-full object-cover ring-2 ring-blue-100">
                        @else
                            <div
                                class="flex h-9 w-9 items-center justify-center rounded-full bg-blue-600 text-sm font-bold text-white ring-2 ring-blue-100">
                                {{ strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}
                            </div>
                        @endif

                        <span class="hidden max-w-35 truncate text-sm font-medium text-slate-600 lg:block">
                            {{ auth()->user()->name }}
                        </span>

                        <svg class="hidden h-4 w-4 text-slate-400 transition lg:block" id="user-chevron" fill="none"
                            viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                        </svg>
                    </button>

                    {{-- Dropdown panel --}}
                    <div id="user-menu"
                        class="absolute right-0 z-50 mt-2 hidden overflow-hidden w-64 origin-top-right rounded-2xl border border-slate-100 bg-white pt-2 pb-0 shadow-xl shadow-slate-200/60 ring-1 ring-black/5">
                        {{-- User info --}}
                        <div class="border-b border-slate-100 px-4 py-2.5">
                            <p class="truncate text-sm font-semibold text-slate-900">
                                {{ auth()->user()->name }}
                            </p>
                            <p class="truncate text-xs text-slate-500">
                                {{ auth()->user()->email }}
                            </p>
                        </div>

                        <div>
                            {{-- Hồ sơ cá nhân --}}
                            <a href="{{ route('profile.edit') }}"
                                class="group flex items-center gap-3 px-4 py-2.5 text-sm text-slate-700 transition hover:bg-slate-100 hover:text-blue-600">
                                <span
                                    class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-100 text-slate-500 transition group-hover:bg-blue-50 group-hover:text-blue-600">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                    </svg>
                                </span>
                                Hồ sơ cá nhân
                            </a>

                            {{-- Lịch sử đặt phòng --}}
                            <a href="{{ route('bookings.history') }}"
                                class="group flex items-center gap-3 px-4 py-2.5 text-sm text-slate-700 transition hover:bg-slate-100 hover:text-blue-600">
                                <span
                                    class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-100 text-slate-500 transition group-hover:bg-blue-50 group-hover:text-blue-600">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                                    </svg>
                                </span>
                                Lịch sử đặt phòng
                            </a>

                            @if (auth()->user()->role === 'admin')
                                <a href="{{ route('admin.dashboard') }}"
                                    class="group flex items-center gap-3 px-4 py-2.5 text-sm text-slate-700 transition hover:bg-slate-100 hover:text-blue-600">
                                    <span
                                        class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-100 text-slate-500 transition group-hover:bg-blue-50 group-hover:text-blue-600">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M9 17.25v1.007a3 3 0 01-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0115 18.257V17.25m6-12V15a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 15V5.25m18 0A2.25 2.25 0 0018.75 3H5.25A2.25 2.25 0 003 5.25m18 0V12a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 12V5.25" />
                                        </svg>
                                    </span>
                                    Quản trị
                                </a>
                            @endif
                        </div>

                        <div class="border-t border-slate-100">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="cursor-pointer group flex w-full items-center gap-3 px-4 py-2.5 text-sm text-red-600 transition hover:bg-red-50">
                                    <span
                                        class="flex h-8 w-8 items-center justify-center rounded-lg bg-red-50 text-red-500 transition group-hover:bg-red-100">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
                                        </svg>
                                    </span>
                                    Đăng xuất
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
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

        {{-- Mobile: Avatar + Hamburger --}}
        <div class="flex items-center gap-2 md:hidden">
            @auth
                @if (auth()->user()->avatar)
                    <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="{{ auth()->user()->name }}"
                        class="h-9 w-9 rounded-full object-cover ring-2 ring-blue-100">
                @else
                    <div
                        class="flex h-9 w-9 items-center justify-center rounded-full bg-blue-600 text-sm font-bold text-white ring-2 ring-blue-100">
                        {{ strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}
                    </div>
                @endif
            @endauth

            <button type="button" id="mobile-menu-button"
                class="inline-flex items-center justify-center rounded-lg p-2 text-slate-600 transition hover:bg-slate-100 hover:text-slate-900"
                aria-controls="mobile-sidebar" aria-expanded="false">
                <span class="sr-only">Mở menu</span>
                <svg id="icon-bars" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                </svg>
            </button>
        </div>
    </nav>
</header>

{{-- Overlay --}}
<div id="mobile-overlay"
    class="pointer-events-none fixed inset-0 z-60 opacity-0 transition-opacity duration-300 backdrop-blur-sm md:hidden">
</div>

{{-- Sidebar trượt từ phải sang trái --}}
<div id="mobile-sidebar"
    class="fixed top-0 right-0 z-70 h-full w-72 max-w-[85vw] translate-x-full transform bg-white shadow-2xl transition-transform duration-300 ease-in-out md:hidden">

    {{-- Header sidebar --}}
    <div class="flex items-center justify-between border-b border-slate-100 px-4 py-4">
        <span class="text-lg font-bold text-slate-900">Menu</span>
        <button type="button" id="close-sidebar-btn"
            class="rounded-lg p-2 text-slate-500 transition hover:bg-slate-100 hover:text-slate-800">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    {{-- Nội dung menu --}}
    <div class="flex h-[calc(100%-65px)] flex-col overflow-y-auto">
        <div class="space-y-1 px-3 py-4">
        
            <a href="{{ route('home') }}"
                class="block rounded-lg px-3 py-2.5 text-base font-medium text-blue-600 hover:bg-blue-50">
                Trang chủ
            </a>
            <a href="{{ route('homestays.index') }}"
                class="block rounded-lg px-3 py-2.5 text-base font-medium text-slate-600 hover:bg-slate-50 hover:text-blue-600">
                Homestay
            </a>

            {{-- Danh mục Mobile --}}
            <div
                x-data="{ categoryOpen: false }"
                class="overflow-hidden rounded-lg"
            >
                {{-- Bấm cả hàng để mở/đóng --}}
                <button
                    type="button"
                    @click="categoryOpen = !categoryOpen"
                    :aria-expanded="categoryOpen"
                    class="flex w-full cursor-pointer items-center justify-between rounded-lg px-3 py-2.5 text-left text-base font-medium transition hover:bg-slate-50 hover:text-blue-600"
                    :class="categoryOpen
                        ? 'bg-blue-50 text-blue-600'
                        : '{{ request()->routeIs('categories.*') ? 'text-blue-600' : 'text-slate-600' }}'"
                >
                    <span>Danh mục</span>

                    <svg
                        class="h-5 w-5 shrink-0 transition-transform duration-200"
                        :class="{ 'rotate-180': categoryOpen }"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M19 9l-7 7-7-7"
                        />
                    </svg>
                </button>

                {{-- Danh sách xổ xuống theo chiều dọc --}}
                <div
                    x-show="categoryOpen"
                    x-cloak
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 -translate-y-1"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 translate-y-0"
                    x-transition:leave-end="opacity-0 -translate-y-1"
                    class="mt-1 space-y-1 border-l-2 border-blue-100 pl-3"
                >
                    {{-- Trang tất cả danh mục --}}
                    <a
                        href="{{ route('categories.index') }}"
                        class="flex items-center justify-between rounded-lg px-3 py-2.5 text-sm font-semibold transition
                            {{ request()->routeIs('categories.index')
                                ? 'bg-blue-50 text-blue-600'
                                : 'text-slate-600 hover:bg-slate-50 hover:text-blue-600' }}"
                    >
                        <span>Tất cả danh mục</span>

                        <svg
                            class="h-4 w-4 shrink-0"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M9 5l7 7-7 7"
                            />
                        </svg>
                    </a>

                    {{-- Các danh mục từ database --}}
                    @forelse ($navCategories as $category)
                        <a
                            href="{{ route('categories.show', $category->slug) }}"
                            class="flex items-center justify-between rounded-lg px-3 py-2.5 text-sm font-semibold transition
                                {{ request()->routeIs('categories.show')
                                    && request()->route('category')?->is($category)
                                        ? 'bg-blue-50 font-semibold text-blue-600'
                                        : 'text-slate-600 hover:bg-slate-50 hover:text-blue-600' }}"
                        >
                            <span class="min-w-0 truncate">
                                {{ $category->name }}
                            </span>

                            <svg
                                class="h-4 w-4 shrink-0"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M9 5l7 7-7 7"
                                />
                            </svg>
                        </a>
                    @empty
                        <p class="px-3 py-2.5 text-sm text-slate-400">
                            Chưa có danh mục
                        </p>
                    @endforelse
                </div>
            </div>

            <a href="{{ route('about') }}"
                class="block rounded-lg px-3 py-2.5 text-base font-medium text-slate-600 hover:bg-slate-50 hover:text-blue-600">
                Giới thiệu
            </a>

            <a href="{{ route('contact') }}"
                class="block rounded-lg px-3 py-2.5 text-base font-medium text-slate-600 hover:bg-slate-50 hover:text-blue-600">
                Liên hệ
            </a>

        </div>

        <div class="mt-auto border-t border-slate-100 px-3 py-4">
            @auth
                <div class="mb-4 flex items-center gap-3 px-1">
                    @if (auth()->user()->avatar)
                        <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="{{ auth()->user()->name }}"
                            class="h-11 w-11 rounded-full object-cover ring-2 ring-blue-100">
                    @else
                        <div
                            class="flex h-11 w-11 items-center justify-center rounded-full bg-blue-600 text-base font-bold text-white ring-2 ring-blue-100">
                            {{ strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}
                        </div>
                    @endif
                    <div class="min-w-0">
                        <p class="truncate text-sm font-semibold text-slate-900">{{ auth()->user()->name }}</p>
                        <p class="truncate text-xs text-slate-500">{{ auth()->user()->email }}</p>
                    </div>
                </div>

                <a href="{{ route('profile.edit') }}"
                    class="mb-1 flex items-center gap-3 rounded-lg px-3 py-2.5 text-base font-medium text-slate-600 hover:bg-slate-50 hover:text-blue-600">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                    </svg>
                    Hồ sơ cá nhân
                </a>

                <a href="{{ route('bookings.history') }}"
                    class="mb-1 flex items-center gap-3 rounded-lg px-3 py-2.5 text-base font-medium text-slate-600 hover:bg-slate-50 hover:text-blue-600">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                    </svg>
                    Lịch sử đặt phòng
                </a>

                @if (auth()->user()->role === 'admin')
                    <a href="{{ route('admin.dashboard') }}"
                        class="mb-2 block rounded-lg px-3 py-2.5 text-base font-medium text-blue-600 hover:bg-blue-50">
                        Quản trị
                    </a>
                @endif

                <form method="POST" action="{{ route('logout') }}" class="mt-2">
                    @csrf
                    <button type="submit"
                        class="flex w-full items-center justify-center gap-2 rounded-xl bg-red-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-red-700">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
                        </svg>
                        Đăng xuất
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}"
                    class="mb-2 block rounded-lg px-3 py-2.5 text-center text-base font-medium text-slate-700 hover:bg-slate-50">
                    Đăng nhập
                </a>
                <a href="{{ route('register') }}"
                    class="block rounded-xl bg-blue-600 px-4 py-2.5 text-center text-sm font-semibold text-white transition hover:bg-blue-700">
                    Đăng ký
                </a>
            @endauth
        </div>
    </div>
</div>