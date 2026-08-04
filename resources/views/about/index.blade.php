@extends('layouts.app')

@section('title', 'Giới thiệu | HomeStayGo')

@section('content')
    {{-- Banner đầu trang --}}
    <section class="bg-gradient-to-br from-blue-50 via-white to-indigo-50">
        <div class="mx-auto max-w-7xl px-4 py-16 text-center sm:px-6 lg:px-8 lg:py-20">
            <p class="font-semibold uppercase tracking-widest text-blue-600">
                HomeStayGo
            </p>

            <h1 class="mt-3 text-4xl font-bold tracking-tight text-slate-950 sm:text-5xl">
                Giới thiệu về HomeStayGo
            </h1>

            <p class="mx-auto mt-5 max-w-3xl text-lg leading-8 text-slate-600">
                HomeStayGo là nền tảng hỗ trợ khách hàng tìm kiếm và đặt Homestay
                nhanh chóng, thuận tiện với thông tin rõ ràng và nhiều lựa chọn phù hợp.
            </p>
        </div>
    </section>

    {{-- Giới thiệu chung --}}
    <section class="bg-white py-16 lg:py-20">
        <div class="mx-auto grid max-w-7xl items-center gap-12 px-4 sm:px-6 lg:grid-cols-2 lg:px-8">
            <div>
                <p class="font-semibold uppercase tracking-widest text-blue-600">
                    Về chúng tôi
                </p>

                <h2 class="mt-3 text-3xl font-bold tracking-tight text-slate-950 sm:text-4xl">
                    Kết nối bạn với những Homestay phù hợp
                </h2>

                <p class="mt-6 leading-7 text-slate-600">
                    HomeStayGo được xây dựng nhằm giúp khách hàng dễ dàng tìm kiếm
                    nơi lưu trú phù hợp cho mỗi chuyến đi. Người dùng có thể xem
                    thông tin Homestay, phòng, tiện ích, giá cả và đánh giá trước
                    khi thực hiện đặt phòng.
                </p>

                <p class="mt-4 leading-7 text-slate-600">
                    Chúng tôi hướng tới một quy trình đặt phòng đơn giản, minh bạch
                    và thuận tiện, giúp khách hàng tiết kiệm thời gian trong quá
                    trình tìm kiếm nơi nghỉ dưỡng.
                </p>

                <a href="{{ route('homestays.index') }}"
                    class="mt-8 inline-flex items-center justify-center rounded-xl bg-blue-600 px-6 py-3.5 font-semibold text-white shadow-lg shadow-blue-600/20 transition hover:-translate-y-0.5 hover:bg-blue-700">
                    Khám phá Homestay
                </a>
            </div>

            <div class="overflow-hidden rounded-3xl bg-white p-3 shadow-xl">
                <img
                    src="https://plus.unsplash.com/premium_photo-1683586218149-e3b33ff9c02a?w=600&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1yZWxhdGVkfDM4fHx8ZW58MHx8fHx8"
                    alt="Giới thiệu HomeStayGo"
                    class="h-[420px] w-full rounded-2xl object-cover"
                >
            </div>
        </div>
    </section>

    {{-- Lý do lựa chọn --}}
    <section class="bg-slate-100 py-16 lg:py-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-2xl text-center">
                <p class="font-semibold uppercase tracking-widest text-blue-600">
                    HomeStayGo
                </p>

                <h2 class="mt-2 text-3xl font-bold tracking-tight text-slate-950 sm:text-4xl">
                    Vì sao nên lựa chọn chúng tôi?
                </h2>

                <p class="mt-4 leading-7 text-slate-600">
                    Quy trình đơn giản, nhiều lựa chọn và thông tin rõ ràng
                    cho mọi chuyến đi.
                </p>
            </div>

            @php
                $benefits = [
                    [
                        'icon' => '🏠',
                        'title' => 'Đa dạng Homestay',
                        'description' => 'Nhiều loại hình lưu trú tại các địa điểm du lịch.',
                    ],
                    [
                        'icon' => '💰',
                        'title' => 'Giá minh bạch',
                        'description' => 'Thông tin rõ ràng, dễ dàng so sánh lựa chọn.',
                    ],
                    [
                        'icon' => '⚡',
                        'title' => 'Đặt phòng nhanh',
                        'description' => 'Quy trình đặt phòng đơn giản và thuận tiện.',
                    ],
                    [
                        'icon' => '🛡️',
                        'title' => 'Thông tin an toàn',
                        'description' => 'Dữ liệu khách hàng được quản lý bảo mật.',
                    ],
                ];
            @endphp

            <div class="mt-12 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($benefits as $benefit)
                    <div
                        class="rounded-3xl border border-slate-200 bg-white p-7 shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-xl">
                        <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-blue-50 text-3xl">
                            {{ $benefit['icon'] }}
                        </div>

                        <h3 class="mt-5 text-lg font-bold text-slate-950">
                            {{ $benefit['title'] }}
                        </h3>

                        <p class="mt-2 leading-6 text-slate-500">
                            {{ $benefit['description'] }}
                        </p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endsection
