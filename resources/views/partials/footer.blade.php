<footer class="relative overflow-hidden bg-slate-950 text-slate-300">
    {{-- Decorative background --}}
    <div class="pointer-events-none absolute inset-0">
        <div class="absolute -left-32 -top-32 h-72 w-72 rounded-full bg-blue-600/10 blur-3xl"></div>
        <div class="absolute -bottom-24 -right-24 h-80 w-80 rounded-full bg-indigo-600/10 blur-3xl"></div>
    </div>

    <div class="relative mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
        <div class="grid gap-12 md:grid-cols-2 lg:grid-cols-4">
            {{-- Brand --}}
            <div class="lg:col-span-1">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-3">
                    <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 text-lg font-bold text-white shadow-lg shadow-blue-500/25">
                        H
                    </span>
                    <span class="text-2xl font-bold text-white">
                        HomeStay<span class="text-blue-400">Go</span>
                    </span>
                </a>

                <p class="mt-5 max-w-xs leading-relaxed text-slate-400">
                    Nền tảng giúp bạn tìm kiếm và đặt Homestay nhanh chóng, thuận tiện và an toàn trên khắp Việt Nam.
                </p>

                {{-- Social --}}
                <div class="mt-6 flex items-center gap-3">
                    <a href="#" aria-label="Facebook"
                        class="flex h-10 w-10 items-center justify-center rounded-xl bg-slate-800 text-slate-400 transition hover:bg-blue-600 hover:text-white">
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M22 12a10 10 0 1 0-11.5 9.9v-7h-2.2V12h2.2V9.8c0-2.2 1.3-3.4 3.3-3.4.9 0 1.9.2 1.9.2v2.1h-1.1c-1.1 0-1.4.7-1.4 1.4V12h2.4l-.4 2.9h-2v7A10 10 0 0 0 22 12z"/>
                        </svg>
                    </a>
                    <a href="#" aria-label="Instagram"
                        class="flex h-10 w-10 items-center justify-center rounded-xl bg-slate-800 text-slate-400 transition hover:bg-gradient-to-br hover:from-pink-500 hover:to-orange-400 hover:text-white">
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2.2c3.2 0 3.6 0 4.9.1 1.2.1 1.9.2 2.3.4.6.2 1 .5 1.5 1s.8.9 1 1.5c.2.4.4 1.1.4 2.3.1 1.3.1 1.7.1 4.9s0 3.6-.1 4.9c-.1 1.2-.2 1.9-.4 2.3-.2.6-.5 1-1 1.5s-.9.8-1.5 1c-.4.2-1.1.4-2.3.4-1.3.1-1.7.1-4.9.1s-3.6 0-4.9-.1c-1.2-.1-1.9-.2-2.3-.4-.6-.2-1-.5-1.5-1s-.8-.9-1-1.5c-.2-.4-.4-1.1-.4-2.3C2.2 15.6 2.2 15.2 2.2 12s0-3.6.1-4.9c.1-1.2.2-1.9.4-2.3.2-.6.5-1 1-1.5s.9-.8 1.5-1c.4-.2 1.1-.4 2.3-.4C8.4 2.2 8.8 2.2 12 2.2zm0 1.8c-3.1 0-3.5 0-4.7.1-1.1.1-1.7.2-2.1.4-.5.2-.8.4-1.1.7-.3.3-.5.6-.7 1.1-.2.4-.3 1-.4 2.1-.1 1.2-.1 1.6-.1 4.7s0 3.5.1 4.7c.1 1.1.2 1.7.4 2.1.2.5.4.8.7 1.1.3.3.6.5 1.1.7.4.2 1 .3 2.1.4 1.2.1 1.6.1 4.7.1s3.5 0 4.7-.1c1.1-.1 1.7-.2 2.1-.4.5-.2.8-.4 1.1-.7.3-.3.5-.6.7-1.1.2-.4.3-1 .4-2.1.1-1.2.1-1.6.1-4.7s0-3.5-.1-4.7c-.1-1.1-.2-1.7-.4-2.1-.2-.5-.4-.8-.7-1.1-.3-.3-.6-.5-1.1-.7-.4-.2-1-.3-2.1-.4-1.2-.1-1.6-.1-4.7-.1zm0 3.1a5.1 5.1 0 1 1 0 10.2 5.1 5.1 0 0 1 0-10.2zm0 8.4a3.3 3.3 0 1 0 0-6.6 3.3 3.3 0 0 0 0 6.6zm6.5-8.6a1.2 1.2 0 1 1-2.4 0 1.2 1.2 0 0 1 2.4 0z"/>
                        </svg>
                    </a>
                    <a href="#" aria-label="TikTok"
                        class="flex h-10 w-10 items-center justify-center rounded-xl bg-slate-800 text-slate-400 transition hover:bg-black hover:text-white">
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M19.6 6.8a4.8 4.8 0 0 1-2.8-2.6V4h-2.5v11.3a3.1 3.1 0 1 1-2.2-3V9.6a5.6 5.6 0 1 0 4.7 5.5V9.3a7.3 7.3 0 0 0 4.2 1.3V8a4.9 4.9 0 0 1-1.4-.2z"/>
                        </svg>
                    </a>
                    <a href="#" aria-label="YouTube"
                        class="flex h-10 w-10 items-center justify-center rounded-xl bg-slate-800 text-slate-400 transition hover:bg-red-600 hover:text-white">
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M23.5 6.2a3 3 0 0 0-2.1-2.1C19.5 3.5 12 3.5 12 3.5s-7.5 0-9.4.6A3 3 0 0 0 .5 6.2 31.5 31.5 0 0 0 0 12a31.5 31.5 0 0 0 .5 5.8 3 3 0 0 0 2.1 2.1c1.9.6 9.4.6 9.4.6s7.5 0 9.4-.6a3 3 0 0 0 2.1-2.1A31.5 31.5 0 0 0 24 12a31.5 31.5 0 0 0-.5-5.8zM9.8 15.5v-7l6.3 3.5-6.3 3.5z"/>
                        </svg>
                    </a>
                </div>
            </div>

            {{-- Liên kết --}}
            <div>
                <h3 class="text-sm font-semibold uppercase tracking-wider text-white">
                    Liên kết
                </h3>
                <ul class="mt-5 space-y-3">
                    <li>
                        <a href="{{ route('home') }}" class="group inline-flex items-center gap-2 text-slate-400 transition hover:text-white">
                            <span class="h-px w-0 bg-blue-500 transition-all group-hover:w-3"></span>
                            Trang chủ
                        </a>
                    </li>
                    <li>
                        <a href="#featured" class="group inline-flex items-center gap-2 text-slate-400 transition hover:text-white">
                            <span class="h-px w-0 bg-blue-500 transition-all group-hover:w-3"></span>
                            Homestay nổi bật
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('about') }}" class="group inline-flex items-center gap-2 text-slate-400 transition hover:text-white">
                            <span class="h-px w-0 bg-blue-500 transition-all group-hover:w-3"></span>
                            Giới thiệu
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('login') }}" class="group inline-flex items-center gap-2 text-slate-400 transition hover:text-white">
                            <span class="h-px w-0 bg-blue-500 transition-all group-hover:w-3"></span>
                            Đăng nhập
                        </a>
                    </li>
                </ul>
            </div>

            {{-- Hỗ trợ --}}
            <div>
                <h3 class="text-sm font-semibold uppercase tracking-wider text-white">
                    Hỗ trợ
                </h3>
                <ul class="mt-5 space-y-3">
                    <li>
                        <a href="#" class="group inline-flex items-center gap-2 text-slate-400 transition hover:text-white">
                            <span class="h-px w-0 bg-blue-500 transition-all group-hover:w-3"></span>
                            Câu hỏi thường gặp
                        </a>
                    </li>
                    <li>
                        <a href="#" class="group inline-flex items-center gap-2 text-slate-400 transition hover:text-white">
                            <span class="h-px w-0 bg-blue-500 transition-all group-hover:w-3"></span>
                            Chính sách bảo mật
                        </a>
                    </li>
                    <li>
                        <a href="#" class="group inline-flex items-center gap-2 text-slate-400 transition hover:text-white">
                            <span class="h-px w-0 bg-blue-500 transition-all group-hover:w-3"></span>
                            Điều khoản sử dụng
                        </a>
                    </li>
                    <li>
                        <a href="#" class="group inline-flex items-center gap-2 text-slate-400 transition hover:text-white">
                            <span class="h-px w-0 bg-blue-500 transition-all group-hover:w-3"></span>
                            Hướng dẫn đặt phòng
                        </a>
                    </li>
                </ul>
            </div>
            {{-- Liên hệ --}}
            <div>
                <a href="{{ route('contact') }}"
                    class="group inline-flex items-center gap-2 text-sm font-semibold uppercase tracking-wider text-white transition hover:text-blue-400">
                    Liên hệ

                <svg class="h-4 w-4 transition group-hover:translate-x-1"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke-width="1.5"
                    stroke="currentColor">
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"
                    />
                </svg>
                </a>

                <ul class="mt-5 space-y-4">
                    <li class="flex items-start gap-3">
                        <span class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-slate-800 text-blue-400">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                            </svg>
                        </span>

                        <div>
                            <p class="text-xs text-slate-500">Email</p>
                            <a href="mailto:support@homestaygo.vn"
                                class="text-sm text-slate-300 transition hover:text-white">
                                support@homestaygo.vn
                            </a>
                        </div>
                    </li>

                    <li class="flex items-start gap-3">
                        <span class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-slate-800 text-blue-400">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z" />
                            </svg>
                        </span>

                        <div>
                            <p class="text-xs text-slate-500">Hotline</p>
                            <a href="tel:0123456789"
                                class="text-sm text-slate-300 transition hover:text-white">
                                0123 456 789
                            </a>
                        </div>
                    </li>

                    <li class="flex items-start gap-3">
                        <span class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-slate-800 text-blue-400">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                            </svg>
                        </span>

                        <div>
                            <p class="text-xs text-slate-500">Địa chỉ</p>
                            <p class="text-sm text-slate-300">Việt Nam</p>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    {{-- Bottom bar --}}
    <div class="relative border-t border-slate-800/80">
        <div class="mx-auto flex max-w-7xl flex-col items-center justify-between gap-4 px-4 py-6 sm:flex-row sm:px-6 lg:px-8">
            <p class="text-sm text-slate-500">
                © {{ date('Y') }} <span class="font-medium text-slate-400">HomeStayGo</span>. All rights reserved.
            </p>
            <div class="flex items-center gap-6 text-sm text-slate-500">
                <a href="#" class="transition hover:text-slate-300">Chính sách bảo mật</a>
                <a href="#" class="transition hover:text-slate-300">Điều khoản</a>
            </div>
        </div>
    </div>
</footer>