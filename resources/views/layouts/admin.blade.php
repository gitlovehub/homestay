<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Quản trị | HomeStayGo')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>

    @stack('styles')
</head>

<body class="min-h-screen bg-slate-100 text-slate-900 antialiased">

    <div x-data="{
        adminSidebarOpen: false,
    
        adminSidebarCollapsed: localStorage.getItem('adminSidebarCollapsed') === 'true',
    
        toggleAdminSidebar() {
            this.adminSidebarCollapsed = !this.adminSidebarCollapsed;
    
            localStorage.setItem(
                'adminSidebarCollapsed',
                this.adminSidebarCollapsed
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
        <div class="min-h-screen transition-all duration-300 ease-in-out"
            :class="adminSidebarCollapsed
                ?
                'lg:pl-20' :
                'lg:pl-60'">

            {{-- Header Admin --}}
            <header
                class="sticky top-0 z-30 flex h-20 items-center justify-between border-b border-slate-200 bg-white/95 px-4 backdrop-blur sm:px-6 lg:px-8">
                {{-- Phần bên trái Header --}}
                <div class="flex min-w-0 items-center gap-3">

                    {{-- Nút mở Sidebar trên điện thoại --}}
                    <button type="button" @click="adminSidebarOpen = true"
                        class="flex h-10 w-10 shrink-0 cursor-pointer items-center justify-center rounded-xl border border-slate-200 text-slate-600 transition hover:border-blue-200 hover:bg-blue-50 hover:text-blue-600 lg:hidden"
                        aria-label="Mở menu quản trị">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>

                    {{-- Tiêu đề trang --}}
                    <div class="min-w-0">
                        <p class="text-xs font-semibold uppercase tracking-wider text-blue-600">
                            Trang quản trị
                        </p>

                        <h1 class="mt-0.5 truncate text-xl font-bold text-slate-900">
                            @yield('page-title', 'Quản trị hệ thống')
                        </h1>
                    </div>
                </div>

                {{-- Phần bên phải Header --}}
                <div class="flex shrink-0 items-center gap-3">

                    {{-- Nút xem website --}}
                    <a href="{{ route('home') }}"
                        class="hidden items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:border-blue-200 hover:bg-blue-50 hover:text-blue-700 sm:inline-flex">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7m-14 0v10a1 1 0 001 1h3m10-11v10a1 1 0 01-1 1h-3m-6 0h6" />
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
                            <p class="max-w-36 truncate text-sm font-bold text-slate-900">
                                {{ auth()->user()?->name ?? 'Admin' }}
                            </p>

                            <p class="text-xs text-slate-400">
                                Quản trị viên
                            </p>
                        </div>
                    </div>
                </div>
            </header>

            {{-- Nội dung của từng trang Admin --}}
            <main class="p-4 sm:p-6 lg:p-8">
                @yield('content')
            </main>

        </div>
    </div>

    @stack('scripts')
</body>

</html>
