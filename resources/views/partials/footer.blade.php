<footer
    id="contact"
    class="bg-slate-950 text-slate-300"
>
    <div class="mx-auto grid max-w-7xl gap-10 px-4 py-14 sm:px-6 md:grid-cols-3 lg:px-8">
        <div>
            <h2 class="text-2xl font-bold text-white">
                HomeStay<span class="text-blue-500">Go</span>
            </h2>

            <p class="mt-4 max-w-sm leading-7 text-slate-400">
                Nền tảng giúp khách hàng tìm kiếm và đặt Homestay
                nhanh chóng, thuận tiện và an toàn.
            </p>
        </div>

        <div>
            <h3 class="font-semibold text-white">
                Liên kết
            </h3>

            <div class="mt-4 flex flex-col gap-3">
                <a href="{{ route('home') }}" class="hover:text-white">
                    Trang chủ
                </a>

                <a href="#featured" class="hover:text-white">
                    Homestay nổi bật
                </a>

                <a href="#about" class="hover:text-white">
                    Giới thiệu
                </a>
            </div>
        </div>

        <div>
            <h3 class="font-semibold text-white">
                Liên hệ
            </h3>

            <div class="mt-4 space-y-3 text-slate-400">
                <p>Email: support@homestaygo.vn</p>
                <p>Hotline: 0123 456 789</p>
                <p>Địa chỉ: Việt Nam</p>
            </div>
        </div>
    </div>

    <div class="border-t border-slate-800">
        <div class="mx-auto max-w-7xl px-4 py-6 text-center text-sm text-slate-500">
            © {{ date('Y') }} HomeStayGo. All rights reserved.
        </div>
    </div>
</footer>