<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Quản trị | HomeStayGo')</title>

    {{-- Khởi tạo giao diện trước khi CSS tải để tránh nháy sáng/tối --}}
    <script>
        (() => {
            const savedTheme = localStorage.getItem('theme');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            const darkMode = savedTheme ? savedTheme === 'dark' : prefersDark;

            document.documentElement.classList.toggle('dark', darkMode);
            document.documentElement.style.colorScheme = darkMode ? 'dark' : 'light';
        })();
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>

    @stack('styles')
</head>

<body class="min-h-screen bg-slate-100 text-slate-900 antialiased transition-colors duration-300 dark:bg-slate-950 dark:text-slate-100">

    <div x-data="{
        adminSidebarOpen: false,

        darkMode: document.documentElement.classList.contains('dark'),

        adminSidebarCollapsed: localStorage.getItem('adminSidebarCollapsed') === 'true',
    
        toggleAdminSidebar() {
            this.adminSidebarCollapsed = !this.adminSidebarCollapsed;

            localStorage.setItem(
                'adminSidebarCollapsed',
                this.adminSidebarCollapsed
            );
        },

        toggleDarkMode() {
            this.darkMode = !this.darkMode;

            document.documentElement.classList.toggle('dark', this.darkMode);
            document.documentElement.style.colorScheme = this.darkMode ? 'dark' : 'light';

            localStorage.setItem(
                'theme',
                this.darkMode ? 'dark' : 'light'
            );
        },

        closeMobileSidebar() {
            this.adminSidebarOpen = false;
        }
    }"
        x-effect="
            document.documentElement.classList.toggle(
                'overflow-hidden',
                adminSidebarOpen
            );

            document.body.classList.toggle(
                'overflow-hidden',
                adminSidebarOpen
            );
        "
        @keydown.escape.window="closeMobileSidebar()"
        @resize.window="
            if (window.innerWidth >= 1024) {
                closeMobileSidebar();
            }
        "
        class="min-h-screen">

        {{-- Overlay khi mở Sidebar trên điện thoại --}}
        <div x-show="adminSidebarOpen" x-cloak x-transition:enter="transition-opacity duration-300 ease-out"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity duration-200 ease-in" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0" @click="closeMobileSidebar()"
            class="fixed inset-0 z-40 bg-slate-950/40 backdrop-blur-sm lg:hidden" aria-hidden="true"></div>

        {{-- Sidebar --}}
        <aside
            class="fixed inset-y-0 left-0 z-50 w-60 transform transition-all duration-300 ease-in-out lg:translate-x-0"
            :class="[
                adminSidebarOpen ?
                'translate-x-0' :
                '-translate-x-full lg:translate-x-0',
            
                adminSidebarCollapsed ?
                'lg:w-20' :
                'lg:w-60'
            ]">
            @include('admin.partials.sidebar')
        </aside>

        {{-- Phần nội dung bên phải Sidebar --}}
        <div class="min-h-screen bg-slate-100 transition-all duration-300 ease-in-out dark:bg-slate-950"
            :class="adminSidebarCollapsed
                ?
                'lg:pl-20' :
                'lg:pl-60'">

            {{-- Header Admin --}}
            <header
                class="sticky top-0 z-30 flex h-20 items-center justify-between border-b border-slate-200 bg-white/95 px-4 backdrop-blur transition-colors duration-300 dark:border-slate-800 dark:bg-slate-900/95 sm:px-6 lg:px-8">
                {{-- Phần bên trái Header --}}
                <div class="flex min-w-0 items-center gap-3">

                    {{-- Nút mở Sidebar trên điện thoại --}}
                    <button type="button" @click="adminSidebarOpen = true"
                        class="flex h-10 w-10 shrink-0 cursor-pointer items-center justify-center rounded-xl border border-slate-200 text-slate-600 transition hover:border-blue-200 hover:bg-blue-50 hover:text-blue-600 dark:border-slate-700 dark:text-slate-300 dark:hover:border-blue-500/50 dark:hover:bg-blue-500/10 dark:hover:text-blue-400 lg:hidden"
                        aria-label="Mở menu quản trị">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>

                    {{-- Tiêu đề trang --}}
                    <div class="min-w-0">
                        <p class="text-xs font-semibold uppercase tracking-wider text-blue-600 dark:text-blue-400">
                            Trang quản trị
                        </p>

                        <h1 class="mt-0.5 truncate text-lg font-bold text-slate-900 dark:text-slate-100 sm:text-xl">
                            @yield('page-title', 'Quản trị hệ thống')
                        </h1>
                    </div>
                </div>

                {{-- Phần bên phải Header --}}
                <div class="flex shrink-0 items-center gap-2 sm:gap-3">

                    {{-- Nút chuyển giao diện sáng/tối --}}
                    <button type="button"
                        @click="toggleDarkMode()"
                        :title="darkMode ? 'Chuyển sang giao diện sáng' : 'Chuyển sang giao diện tối'"
                        :aria-label="darkMode ? 'Chuyển sang giao diện sáng' : 'Chuyển sang giao diện tối'"
                        class="flex h-11 w-11 shrink-0 cursor-pointer items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-600 shadow-sm transition hover:border-blue-200 hover:bg-blue-50 hover:text-blue-600 dark:border-slate-700 dark:bg-slate-800 dark:text-amber-300 dark:hover:border-amber-400/50 dark:hover:bg-amber-400/10 dark:hover:text-amber-200">

                        {{-- Biểu tượng mặt trăng khi đang ở giao diện sáng --}}
                        <svg x-show="!darkMode" x-cloak viewBox="0 0 24 24" class="h-5 w-5" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" aria-hidden="true">
                            <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79Z" />
                        </svg>

                        {{-- Biểu tượng mặt trời khi đang ở giao diện tối --}}
                        <svg x-show="darkMode" x-cloak viewBox="0 0 24 24" class="h-5 w-5" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" aria-hidden="true">
                            <circle cx="12" cy="12" r="4" />
                            <path d="M12 2v2M12 20v2M4.93 4.93l1.42 1.42M17.66 17.66l1.41 1.41M2 12h2M20 12h2M4.93 19.07l1.42-1.42M17.66 6.34l1.41-1.41" />
                        </svg>
                    </button>

                    {{-- Nút xem website --}}
                    <a href="{{ route('home') }}"
                        class="hidden h-11 items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 text-sm font-semibold text-slate-700 transition hover:border-blue-200 hover:bg-blue-50 hover:text-blue-700 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-200 dark:hover:border-blue-500/50 dark:hover:bg-blue-500/10 dark:hover:text-blue-400 sm:inline-flex">
                        <svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.75"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                            <polyline points="9 22 9 12 15 12 15 22" />
                        </svg>
                        <span>Xem website</span>
                    </a>

                    {{-- Thông tin tài khoản --}}
                    <div class="flex items-center gap-3">

                        {{-- Avatar --}}
                        <div
                            class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-600 font-bold text-white shadow-sm shadow-blue-600/25">
                            {{ mb_strtoupper(mb_substr(auth()->user()?->name ?? 'A', 0, 1)) }}
                        </div>

                        {{-- Tên tài khoản --}}
                        <div class="hidden sm:block">
                            <p class="max-w-36 truncate text-sm font-bold text-slate-900 dark:text-slate-100">
                                {{ auth()->user()?->name ?? 'Admin' }}
                            </p>

                            <p class="text-xs text-slate-400 dark:text-slate-500">
                                Quản trị viên
                            </p>
                        </div>
                    </div>
                </div>
            </header>

            {{-- Nội dung của từng trang Admin --}}
            <main class="p-4 transition-colors duration-300 sm:p-6 lg:p-8">
                @yield('content')
            </main>

        </div>
    </div>

    @stack('scripts')
</body>

</html>