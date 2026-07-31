<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        @yield('title', 'Quản trị | HomeStayGo')
    </title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        [x-cloak] {
            display: none !important;
        }

        /*
        |--------------------------------------------------------------------------
        | Co giãn Sidebar và nội dung
        |--------------------------------------------------------------------------
        */

        .admin-sidebar-desktop {
            transition:
                width 300ms ease;
        }

        .admin-content-wrapper {
            transition:
                padding-left 300ms ease;
        }

        @media (min-width: 1024px) {
            .admin-sidebar-desktop {
                width: var(--admin-sidebar-width);
            }

            .admin-content-wrapper {
                padding-left: var(--admin-sidebar-width);
            }
        }
    </style>
</head>

<body x-data="{
    adminSidebarOpen: false,

    adminSidebarCollapsed: localStorage.getItem('adminSidebarCollapsed') === 'true',

    toggleAdminSidebar() {
        this.adminSidebarCollapsed = !this.adminSidebarCollapsed;

        localStorage.setItem(
            'adminSidebarCollapsed',
            String(this.adminSidebarCollapsed)
        );

        /*
         * Yêu cầu Chart.js tính lại kích thước
         * sau khi Sidebar co giãn hoàn tất.
         */
        setTimeout(() => {
            window.dispatchEvent(
                new Event('resize')
            );
        }, 320);
    },

    openMobileSidebar() {
        this.adminSidebarOpen = true;
    },

    closeMobileSidebar() {
        this.adminSidebarOpen = false;
    }
}"
    :style="{
        '--admin-sidebar-width': adminSidebarCollapsed ?
            '72px' :
            '240px'
    }"
    @keydown.escape.window="closeMobileSidebar()" class="min-h-screen overflow-x-hidden bg-slate-100 text-slate-900">
    {{-- ========================================================= --}}
    {{-- SIDEBAR MOBILE --}}
    {{-- ========================================================= --}}

    <div x-show="adminSidebarOpen" x-cloak class="fixed inset-0 z-50 lg:hidden">
        {{-- Overlay --}}
        <button type="button" x-transition.opacity @click="closeMobileSidebar()"
            class="absolute inset-0 cursor-default bg-slate-950/60 backdrop-blur-sm"
            aria-label="Đóng menu quản trị"></button>

        {{-- Sidebar mobile luôn mở rộng --}}
        <aside x-data="{
            adminSidebarCollapsed: false
        }" x-show="adminSidebarOpen"
            x-transition:enter="transform transition duration-300 ease-out" x-transition:enter-start="-translate-x-full"
            x-transition:enter-end="translate-x-0" x-transition:leave="transform transition duration-200 ease-in"
            x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full"
            class="absolute inset-y-0 left-0 w-[280px] max-w-[86vw] bg-white shadow-2xl">
            @include('admin.partials.sidebar')
        </aside>
    </div>

    {{-- ========================================================= --}}
    {{-- SIDEBAR DESKTOP --}}
    {{-- ========================================================= --}}

    <aside class="admin-sidebar-desktop fixed inset-y-0 left-0 z-40 hidden bg-white shadow-sm lg:block">
        @include('admin.partials.sidebar')
    </aside>

    {{-- ========================================================= --}}
    {{-- NỘI DUNG BÊN PHẢI --}}
    {{-- ========================================================= --}}

    <div class="admin-content-wrapper min-h-screen min-w-0">
        {{-- Topbar Admin --}}
        <header class="sticky top-0 z-30 border-b border-slate-200 bg-white/95 backdrop-blur">
            <div class="flex min-h-18 items-center justify-between gap-4 px-4 sm:px-6 lg:px-8">
                {{-- Bên trái --}}
                <div class="flex min-w-0 items-center gap-3">
                    {{-- Mở Sidebar mobile --}}
                    <button type="button" @click="openMobileSidebar()"
                        class="flex h-10 w-10 shrink-0 cursor-pointer items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-600 transition hover:border-blue-200 hover:bg-blue-50 hover:text-blue-600 lg:hidden"
                        aria-label="Mở menu quản trị">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>

                    <div class="min-w-0">
                        <p class="text-xs font-semibold uppercase tracking-wider text-blue-600">
                            Trang quản trị
                        </p>

                        <h1 class="truncate text-lg font-bold text-slate-950 sm:text-xl">
                            @yield('page-title', 'Tổng quan')
                        </h1>
                    </div>
                </div>

                {{-- Bên phải --}}
                <div class="flex items-center gap-3">
                    {{-- Xem website --}}
                    <a href="{{ route('home') }}" target="_blank" rel="noopener noreferrer"
                        class="hidden items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:border-blue-200 hover:bg-blue-50 hover:text-blue-600 sm:inline-flex">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 11.5 12 4l9 7.5M5 10v10h14V10M9 20v-6h6v6" />
                        </svg>

                        Xem website
                    </a>

                    {{-- Tài khoản --}}
                    <div class="flex items-center gap-3">
                        <div
                            class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-600 text-sm font-bold text-white shadow-md shadow-blue-600/20">
                            {{ mb_strtoupper(mb_substr(auth()->user()?->name ?? 'A', 0, 1)) }}
                        </div>

                        <div class="hidden min-w-0 md:block">
                            <p class="max-w-36 truncate text-sm font-bold text-slate-900">
                                {{ auth()->user()?->name ?? 'Quản trị viên' }}
                            </p>

                            <p class="text-xs text-slate-500">
                                Quản trị viên
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        {{-- Nội dung từng trang Admin --}}
        <main class="min-w-0 px-4 py-6 sm:px-6 lg:px-8 lg:py-8">
            @yield('content')
        </main>
    </div>

    @stack('scripts')
</body>

</html>
