@php

    $unreadContactCount =
        \Illuminate\Support\Facades\Schema::hasTable('contact_messages')
            ? \App\Models\ContactMessage::query()
                ->where('status', 'unread')
                ->count()
            : 0;

    $adminMenus = [
        [
            'label' => 'Tổng quan',
            'route' => 'admin.dashboard',
            'active' => 'admin.dashboard',
            'icon' => 'dashboard',
        ],
        [
            'label' => 'Người dùng',
            'route' => 'admin.users.index',
            'active' => 'admin.users.*',
            'icon' => 'users',
        ],
        [
            'label' => 'Homestay',
            'route' => 'admin.homestays.index',
            'active' => 'admin.homestays.*',
            'icon' => 'homestays',
        ],
        [
            'label' => 'Phòng',
            'route' => 'admin.rooms.index',
            'active' => 'admin.rooms.*',
            'icon' => 'rooms',
        ],
        [
            'label' => 'Booking',
            'route' => 'admin.bookings.index',
            'active' => 'admin.bookings.*',
            'icon' => 'bookings',
        ],
        [
            'label' => 'Danh mục',
            'route' => 'admin.categories.index',
            'active' => 'admin.categories.*',
            'icon' => 'categories',
        ],
        [
            'label' => 'Tiện ích',
            'route' => 'admin.amenities.index',
            'active' => 'admin.amenities.*',
            'icon' => 'amenities',
        ],
        [
            'label' => 'Đánh giá',
            'route' => 'admin.reviews.index',
            'active' => 'admin.reviews.*',
            'icon' => 'reviews',
        ],
        [
            'label' => 'Liên hệ',
            'route' => 'admin.contact-messages.index',
            'active' => 'admin.contact-messages.*',
            'icon' => 'contacts',
            'badge' => $unreadContactCount,
        ],
    ];
@endphp

<div class="relative flex h-full w-full flex-col border-r border-slate-200 bg-white text-slate-700">

    {{-- Logo và nút co giãn --}}
    <div class="relative flex h-16 shrink-0 items-center border-b border-slate-100"
        :class="adminSidebarCollapsed
            ?
            'justify-center px-3' :
            'justify-start px-4'">
        {{-- Logo --}}
        <a href="{{ Route::has('admin.dashboard') ? route('admin.dashboard') : '#' }}"
            class="flex min-w-0 items-center gap-3 overflow-hidden"
            :class="adminSidebarCollapsed
                ?
                'justify-center' :
                ''">
            <div
                class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-blue-600 text-white shadow-sm shadow-blue-600/25">
                <span class="text-lg font-black tracking-tighter">
                    H
                </span>
            </div>

            <div x-show="!adminSidebarCollapsed" x-cloak x-transition:enter="transition duration-200 ease-out"
                x-transition:enter-start="-translate-x-2 opacity-0" x-transition:enter-end="translate-x-0 opacity-100"
                class="flex min-w-0 flex-col whitespace-nowrap leading-none">
                <span class="text-[15px] font-semibold tracking-tight text-slate-900">
                    HomeStayGo
                </span>

                <span class="mt-0.5 text-[11px] font-medium text-slate-400">
                    Admin
                </span>
            </div>
        </a>

        {{-- Co giãn Sidebar desktop --}}
        <button type="button" @click="toggleAdminSidebar()"
            class="absolute -right-3 top-1/2 z-20 hidden h-7 w-7 -translate-y-1/2 cursor-pointer items-center justify-center rounded-full border border-slate-200 bg-white text-slate-400 shadow-md transition hover:border-blue-200 hover:bg-blue-50 hover:text-blue-600 lg:flex"
            :title="adminSidebarCollapsed
                ?
                'Mở rộng Sidebar' :
                'Thu gọn Sidebar'"
            aria-label="Co giãn Sidebar">
            <svg class="h-4 w-4 transition-transform duration-300"
                :class="adminSidebarCollapsed
                    ?
                    'rotate-180' :
                    ''"
                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 18l-6-6 6-6" />
            </svg>
        </button>

        {{-- Đóng Sidebar mobile --}}
        <button type="button" @click="adminSidebarOpen = false"
            class="ml-auto flex h-8 w-8 cursor-pointer items-center justify-center rounded-lg text-slate-400 transition hover:bg-red-50 hover:text-red-500 lg:hidden"
            aria-label="Đóng menu">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 overflow-y-auto overflow-x-hidden px-2.5 py-3">
        <div class="space-y-0.5">
            @foreach ($adminMenus as $menu)
                @php
                    $routeExists = Route::has($menu['route']);
                    $menuUrl = $routeExists ? route($menu['route']) : '#';
                    $isActive = request()->routeIs($menu['active']);
                @endphp

                <a href="{{ $menuUrl }}" @click="adminSidebarOpen = false"
                    class="group relative flex items-center gap-3 rounded-xl px-3 py-2.5 text-[13.5px] font-medium transition-all duration-150
                          {{ $isActive ? 'bg-blue-50 text-blue-700' : 'text-slate-600 hover:bg-blue-50 hover:text-blue-700' }}"
                    :class="adminSidebarCollapsed ? 'justify-center px-0' : ''"
                    :title="adminSidebarCollapsed ? '{{ $menu['label'] }}' : ''">

                    <span
                        class="flex h-5 w-5 shrink-0 items-center justify-center transition
                                 {{ $isActive ? 'text-blue-600' : 'text-slate-400 group-hover:text-blue-600' }}">
                        @switch($menu['icon'])
                            @case('dashboard')
                                <svg class="h-[20px] w-[20px]" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M4 5a1 1 0 011-1h4a1 1 0 011 1v5a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM14 5a1 1 0 011-1h4a1 1 0 011 1v2a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zM4 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1v-4zM14 12a1 1 0 011-1h4a1 1 0 011 1v7a1 1 0 01-1 1h-4a1 1 0 01-1-1v-7z" />
                                </svg>
                            @break

                            @case('users')
                                <svg class="h-[20px] w-[20px]" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M16 21v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2M9 11a4 4 0 100-8 4 4 0 000 8zm13 10v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75" />
                                </svg>
                            @break

                            @case('homestays')
                                <svg viewBox="0 0 24 24" class="h-[20px] w-[20px]" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M3 21h18" />
                                    <path d="M5 21V7l8-4v18" />
                                    <path d="M19 21V11l-6-4" />
                                    <path d="M9 9v.01" />
                                    <path d="M9 12v.01" />
                                    <path d="M9 15v.01" />
                                    <path d="M9 18v.01" />
                                </svg>
                            @break

                            @case('rooms')
                                <svg viewBox="0 0 24 24" class="h-[20px] w-[20px]" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M2 4v16" />
                                    <path d="M2 8h18a2 2 0 0 1 2 2v10" />
                                    <path d="M2 17h20" />
                                    <path d="M6 8v9" />
                                </svg>
                            @break

                            @case('bookings')
                                <svg class="h-[20px] w-[20px]" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            @break

                            @case('categories')
                                <svg class="h-[20px] w-[20px]" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h10" />
                                </svg>
                            @break

                            @case('amenities')
                                <svg class="h-[20px] w-[20px]" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                                </svg>
                            @break

                            @case('reviews')
                                <svg class="h-[20px] w-[20px]" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                                </svg>
                            @break

                            @case('contacts')
                                <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                  <rect x="2" y="4" width="20" height="16" rx="2"/>
                                  <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/>
                                </svg>
                            @break
                        @endswitch
                    </span>

                    <span x-show="!adminSidebarCollapsed" x-transition:enter="transition ease-out duration-150"
                        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                        class="truncate whitespace-nowrap">
                        {{ $menu['label'] }}
                    </span>

                    @if (($menu['badge'] ?? 0) > 0)
                        {{-- Badge khi Sidebar mở rộng --}}
                        <span
                            x-show="!adminSidebarCollapsed"
                            class="ml-auto inline-flex min-w-5 items-center justify-center rounded-full bg-red-500 px-1.5 py-0.5 text-[10px] font-semibold text-white"
                        >
                            {{ $menu['badge'] > 99 ? '99+' : $menu['badge'] }}
                        </span>

                        {{-- Chấm đỏ khi Sidebar thu gọn --}}
                        <span
                            x-show="adminSidebarCollapsed"
                            class="absolute right-2 top-2 h-2 w-2 rounded-full bg-red-500 ring-2 ring-white"
                        ></span>
                    @endif

                    @if (!$routeExists)
                        <span x-show="!adminSidebarCollapsed"
                            class="ml-auto rounded-md bg-slate-100 px-1.5 py-0.5 text-[10px] font-medium text-slate-400">
                            Soon
                        </span>
                    @endif
                </a>
            @endforeach
        </div>
    </nav>

    {{-- Bottom section --}}
    <div class="shrink-0 border-t border-slate-100 px-2.5 py-3">

        {{-- Actions --}}
        <div class="space-y-0.5">
            <a href="{{ route('home') }}"
                class="group flex items-center gap-3 rounded-xl px-3 py-2.5 text-[13.5px] font-medium text-slate-600 transition hover:bg-blue-50 hover:text-blue-700"
                :class="adminSidebarCollapsed ? 'justify-center px-0' : ''"
                :title="adminSidebarCollapsed ? 'Quay về website' : ''">
                <svg class="h-[18px] w-[18px] shrink-0 text-slate-400 group-hover:text-blue-600" fill="none"
                    stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                <span x-show="!adminSidebarCollapsed" class="whitespace-nowrap">Quay về website</span>
            </a>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="group cursor-pointer flex w-full items-center gap-3 rounded-xl px-3 py-2.5 text-[13.5px] font-medium text-slate-600 transition hover:bg-red-50 hover:text-red-600"
                    :class="adminSidebarCollapsed ? 'justify-center px-0' : ''"
                    :title="adminSidebarCollapsed ? 'Đăng xuất' : ''">
                    <svg class="h-[18px] w-[18px] shrink-0 text-slate-400 group-hover:text-red-500" fill="none"
                        stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    <span x-show="!adminSidebarCollapsed" class="whitespace-nowrap">Đăng xuất</span>
                </button>
            </form>
        </div>
    </div>
</div>
