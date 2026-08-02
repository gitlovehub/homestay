@extends('layouts.app')

@section('title', 'Liên hệ | HomeStayGo')

@section('content')
    {{-- Banner --}}
    <section class="relative overflow-hidden bg-gradient-to-br from-blue-50 via-white to-indigo-50">
        <div
            class="pointer-events-none absolute -right-24 -top-24 h-80 w-80 rounded-full bg-blue-200/40 blur-3xl">
        </div>

        <div class="relative mx-auto max-w-7xl px-4 py-16 text-center sm:px-6 lg:px-8 lg:py-20">
            <p class="font-semibold uppercase tracking-widest text-blue-600">
                HomeStayGo
            </p>

            <h1 class="mt-3 text-4xl font-bold tracking-tight text-slate-950 sm:text-5xl">
                Liên hệ với chúng tôi
            </h1>

            <p class="mx-auto mt-5 max-w-2xl text-lg leading-8 text-slate-600">
                HomeStayGo luôn sẵn sàng hỗ trợ các vấn đề liên quan đến tài khoản,
                Homestay và quá trình đặt phòng.
            </p>
        </div>
    </section>

    {{-- Nội dung --}}
    <section class="bg-slate-50 py-16 lg:py-20">
        <div class="mx-auto grid max-w-7xl gap-10 px-4 sm:px-6 lg:grid-cols-5 lg:px-8">

{{-- Thông tin liên hệ --}}
<div class="lg:col-span-2">

{{-- Bản đồ vị trí HomeStayGo tại Hà Nội --}}
<div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-lg">
    <div class="p-5">
        <p class="text-sm font-semibold text-blue-600">
            Vị trí của chúng tôi
        </p>

        <h3 class="mt-1 text-xl font-bold text-slate-900">
            HomeStayGo Hà Nội
        </h3>

        <p class="mt-2 text-sm text-slate-500">
            Trung tâm Hà Nội, Việt Nam
        </p>
    </div>

    <div class="h-[350px] w-full">
        <iframe
            src="https://www.google.com/maps?q=21.028511,105.804817&z=16&output=embed"
            class="h-full w-full"
            style="border: 0;"
            loading="lazy"
            allowfullscreen
            referrerpolicy="no-referrer-when-downgrade"
            title="Vị trí HomeStayGo tại Hà Nội">
        </iframe>
    </div>

    <div class="p-5">
        <a
            href="https://www.google.com/maps/search/?api=1&query=21.028511,105.804817"
            target="_blank"
            rel="noopener noreferrer"
            class="inline-flex w-full items-center justify-center gap-2 rounded-2xl
                   bg-blue-600 px-5 py-3.5 text-sm font-bold text-white
                   transition hover:bg-blue-700"
        >
            Mở vị trí trên Google Maps
        </a>
    </div>
</div>

    <p class="font-semibold uppercase tracking-widest text-blue-600">
        Thông tin hỗ trợ
    </p>

    <h2 class="mt-3 text-3xl font-bold tracking-tight text-slate-950">
        Chúng tôi luôn sẵn sàng hỗ trợ
    </h2>

    <p class="mt-5 leading-7 text-slate-600">
        Bạn có thể liên hệ với HomeStayGo qua email, hotline hoặc gửi yêu cầu
        trực tiếp bằng biểu mẫu bên cạnh. Đội ngũ hỗ trợ sẽ phản hồi trong
        thời gian sớm nhất.
    </p>


                <div class="mt-8 rounded-3xl bg-slate-950 p-7 text-white shadow-xl">
                    <h3 class="text-sm font-bold uppercase tracking-wider">
                        Liên hệ
                    </h3>

                    <div class="mt-7 space-y-6">

                        {{-- Email --}}
                        <div class="flex items-center gap-4">
                            <span
                                class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-slate-800 text-blue-400">
                                <svg
                                    class="h-5 w-5"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="1.8"
                                        d="M3 6.75A2.25 2.25 0 0 1 5.25 4.5h13.5A2.25 2.25 0 0 1 21 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 17.25V6.75Z"
                                    />

                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="1.8"
                                        d="m3.75 7.5 7.012 4.675a2.25 2.25 0 0 0 2.476 0L20.25 7.5"
                                    />
                                </svg>
                            </span>

                            <div class="min-w-0">
                                <p class="text-xs text-slate-500">
                                    Email
                                </p>

                                <a
                                    href="mailto:support@homestaygo.vn"
                                    class="mt-1 block break-all text-sm font-medium text-white transition hover:text-blue-400"
                                >
                                    support@homestaygo.vn
                                </a>
                            </div>
                        </div>

                        {{-- Hotline --}}
                        <div class="flex items-center gap-4">
                            <span
                                class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-slate-800 text-blue-400">
                                <svg
                                    class="h-5 w-5"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="1.8"
                                        d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106a1.125 1.125 0 0 0-1.173.417l-.97 1.293a1.125 1.125 0 0 1-1.21.38 12.035 12.035 0 0 1-7.143-7.143 1.125 1.125 0 0 1 .38-1.21l1.293-.97c.37-.278.54-.75.417-1.173L6.963 3.102A1.125 1.125 0 0 0 5.872 2.25H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z"
                                    />
                                </svg>
                            </span>

                            <div>
                                <p class="text-xs text-slate-500">
                                    Hotline
                                </p>

                                <a
                                    href="tel:0123456789"
                                    class="mt-1 block text-sm font-medium text-white transition hover:text-blue-400"
                                >
                                    0123 456 789
                                </a>
                            </div>
                        </div>

                        {{-- Địa chỉ --}}
                        <div class="flex items-center gap-4">
                            <span
                                class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-slate-800 text-blue-400">
                                <svg
                                    class="h-5 w-5"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="1.8"
                                        d="M12 21s7-5.686 7-12a7 7 0 1 0-14 0c0 6.314 7 12 7 12Z"
                                    />

                                    <circle
                                        cx="12"
                                        cy="9"
                                        r="2.5"
                                        stroke-width="1.8"
                                    />
                                </svg>
                            </span>

                            <div>
                                <p class="text-xs text-slate-500">
                                    Địa chỉ
                                </p>

                                <p class="mt-1 text-sm font-medium text-white">
                                    Việt Nam
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Thời gian hỗ trợ --}}
                <div class="mt-6 rounded-3xl border border-blue-200 bg-blue-50 p-6">
                    <div class="flex items-start gap-4">
                        <span
                            class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-blue-100 text-blue-600">
                            <svg
                                class="h-5 w-5"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="1.8"
                                    d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"
                                />
                            </svg>
                        </span>

                        <div>
                            <h3 class="font-bold text-blue-900">
                                Thời gian hỗ trợ
                            </h3>

                            <p class="mt-1 text-sm leading-6 text-blue-700">
                                Thứ Hai – Chủ Nhật, từ 08:00 đến 22:00.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Form liên hệ --}}
            <div class="lg:col-span-3">
                <div class="rounded-3xl border border-slate-200 bg-white p-7 shadow-xl shadow-slate-900/5 sm:p-9">
                    <p class="font-semibold uppercase tracking-widest text-blue-600">
                        Yêu cầu hỗ trợ
                    </p>

                    <h2 class="mt-3 text-3xl font-bold tracking-tight text-slate-950">
                        Gửi thư cho HomeStayGo
                    </h2>

                    <p class="mt-4 leading-7 text-slate-600">
                        Vui lòng điền đầy đủ thông tin bên dưới. Quản trị viên sẽ tiếp
                        nhận và phản hồi yêu cầu của bạn trong thời gian sớm nhất.
                    </p>

                    {{-- Thông báo gửi thành công --}}
                    @if (session('success'))
                        <div
                            class="mt-6 flex items-start gap-3 rounded-2xl border border-green-200 bg-green-50 px-5 py-4 text-green-700">
                            <svg
                                class="mt-0.5 h-5 w-5 shrink-0"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="m4.5 12.75 6 6 9-13.5"
                                />
                            </svg>

                            <p class="text-sm font-medium">
                                {{ session('success') }}
                            </p>
                        </div>
                    @endif

                    {{-- Danh sách lỗi --}}
                    @if ($errors->any())
                        <div class="mt-6 rounded-2xl border border-red-200 bg-red-50 px-5 py-4">
                            <div class="flex items-start gap-3">
                                <svg
                                    class="mt-0.5 h-5 w-5 shrink-0 text-red-600"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z"
                                    />
                                </svg>

                                <div>
                                    <p class="font-semibold text-red-700">
                                        Vui lòng kiểm tra lại thông tin.
                                    </p>

                                    <ul class="mt-2 list-inside list-disc space-y-1 text-sm text-red-600">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif

                    <form
                        action="{{ route('contact.store') }}"
                        method="POST"
                        class="mt-8 space-y-6"
                    >
                        @csrf

                        {{-- Họ tên và email --}}
                        <div class="grid gap-6 sm:grid-cols-2">

                            {{-- Họ và tên --}}
                            <div>
                                <label
                                    for="name"
                                    class="mb-2 block text-sm font-semibold text-slate-700"
                                >
                                    Họ và tên
                                    <span class="text-red-500">*</span>
                                </label>

                                <input
                                    id="name"
                                    type="text"
                                    name="name"
                                    value="{{ old('name', auth()->user()->name ?? '') }}"
                                    placeholder="Nhập họ và tên"
                                    maxlength="100"
                                    required
                                    autocomplete="name"
                                    class="w-full rounded-xl border bg-white px-4 py-3 text-sm text-slate-900 outline-none transition placeholder:text-slate-400
                                        {{ $errors->has('name')
                                            ? 'border-red-400 focus:border-red-500 focus:ring-4 focus:ring-red-100'
                                            : 'border-slate-300 focus:border-blue-500 focus:ring-4 focus:ring-blue-100' }}"
                                >

                                @error('name')
                                    <p class="mt-2 text-sm font-medium text-red-600">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            {{-- Email --}}
                            <div>
                                <label
                                    for="email"
                                    class="mb-2 block text-sm font-semibold text-slate-700"
                                >
                                    Email
                                    <span class="text-red-500">*</span>
                                </label>

                                <input
                                    id="email"
                                    type="email"
                                    name="email"
                                    value="{{ old('email', auth()->user()->email ?? '') }}"
                                    placeholder="example@gmail.com"
                                    maxlength="255"
                                    required
                                    autocomplete="email"
                                    class="w-full rounded-xl border bg-white px-4 py-3 text-sm text-slate-900 outline-none transition placeholder:text-slate-400
                                        {{ $errors->has('email')
                                            ? 'border-red-400 focus:border-red-500 focus:ring-4 focus:ring-red-100'
                                            : 'border-slate-300 focus:border-blue-500 focus:ring-4 focus:ring-blue-100' }}"
                                >

                                @error('email')
                                    <p class="mt-2 text-sm font-medium text-red-600">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>

                        {{-- Số điện thoại --}}
                        <div>
                            <label
                                for="phone"
                                class="mb-2 block text-sm font-semibold text-slate-700"
                            >
                                Số điện thoại

                                <span class="font-normal text-slate-400">
                                    (không bắt buộc)
                                </span>
                            </label>

                            <input
                                id="phone"
                                type="tel"
                                name="phone"
                                value="{{ old('phone') }}"
                                placeholder="Ví dụ: 0912345678"
                                maxlength="20"
                                autocomplete="tel"
                                class="w-full rounded-xl border bg-white px-4 py-3 text-sm text-slate-900 outline-none transition placeholder:text-slate-400
                                    {{ $errors->has('phone')
                                        ? 'border-red-400 focus:border-red-500 focus:ring-4 focus:ring-red-100'
                                        : 'border-slate-300 focus:border-blue-500 focus:ring-4 focus:ring-blue-100' }}"
                            >

                            @error('phone')
                                <p class="mt-2 text-sm font-medium text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- Chủ đề --}}
                        <div>
                            <label
                                for="subject"
                                class="mb-2 block text-sm font-semibold text-slate-700"
                            >
                                Chủ đề cần hỗ trợ
                                <span class="text-red-500">*</span>
                            </label>

                            <select
                                id="subject"
                                name="subject"
                                required
                                class="w-full rounded-xl border bg-white px-4 py-3 text-sm text-slate-900 outline-none transition
                                    {{ $errors->has('subject')
                                        ? 'border-red-400 focus:border-red-500 focus:ring-4 focus:ring-red-100'
                                        : 'border-slate-300 focus:border-blue-500 focus:ring-4 focus:ring-blue-100' }}"
                            >
                                <option value="">
                                    -- Chọn chủ đề cần hỗ trợ --
                                </option>

                                <option
                                    value="Hỗ trợ đặt phòng"
                                    @selected(old('subject') === 'Hỗ trợ đặt phòng')
                                >
                                    Hỗ trợ đặt phòng
                                </option>

                                <option
                                    value="Hỗ trợ tài khoản"
                                    @selected(old('subject') === 'Hỗ trợ tài khoản')
                                >
                                    Hỗ trợ tài khoản
                                </option>

                                <option
                                    value="Thanh toán và hoàn tiền"
                                    @selected(old('subject') === 'Thanh toán và hoàn tiền')
                                >
                                    Thanh toán và hoàn tiền
                                </option>

                                <option
                                    value="Khiếu nại dịch vụ"
                                    @selected(old('subject') === 'Khiếu nại dịch vụ')
                                >
                                    Khiếu nại dịch vụ
                                </option>

                                <option
                                    value="Góp ý cho HomeStayGo"
                                    @selected(old('subject') === 'Góp ý cho HomeStayGo')
                                >
                                    Góp ý cho HomeStayGo
                                </option>

                                <option
                                    value="Vấn đề khác"
                                    @selected(old('subject') === 'Vấn đề khác')
                                >
                                    Vấn đề khác
                                </option>
                            </select>

                            @error('subject')
                                <p class="mt-2 text-sm font-medium text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- Nội dung --}}
                        <div>
                            <div class="mb-2 flex items-center justify-between gap-4">
                                <label
                                    for="message"
                                    class="block text-sm font-semibold text-slate-700"
                                >
                                    Nội dung liên hệ
                                    <span class="text-red-500">*</span>
                                </label>

                                <span
                                    id="message-count"
                                    class="text-xs font-medium text-slate-400"
                                >
                                    0/5000
                                </span>
                            </div>

                            <textarea
                                id="message"
                                name="message"
                                rows="7"
                                maxlength="5000"
                                required
                                placeholder="Hãy mô tả rõ vấn đề bạn đang gặp phải. Bạn có thể cung cấp mã đặt phòng nếu có..."
                                class="w-full resize-none rounded-xl border bg-white px-4 py-3 text-sm leading-6 text-slate-900 outline-none transition placeholder:text-slate-400
                                    {{ $errors->has('message')
                                        ? 'border-red-400 focus:border-red-500 focus:ring-4 focus:ring-red-100'
                                        : 'border-slate-300 focus:border-blue-500 focus:ring-4 focus:ring-blue-100' }}"
                            >{{ old('message') }}</textarea>

                            <div class="mt-2 flex items-start justify-between gap-4">
                                @error('message')
                                    <p class="text-sm font-medium text-red-600">
                                        {{ $message }}
                                    </p>
                                @else
                                    <p class="text-xs leading-5 text-slate-400">
                                        Không cung cấp mật khẩu hoặc thông tin thanh toán nhạy cảm.
                                    </p>
                                @enderror
                            </div>
                        </div>

                        {{-- Ghi chú --}}
                        <div class="rounded-2xl border border-blue-200 bg-blue-50 p-5">
                            <div class="flex items-start gap-3">
                                <svg
                                    class="mt-0.5 h-5 w-5 shrink-0 text-blue-600"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="1.8"
                                        d="M11.25 11.25 11.291 11.229a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.022M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z"
                                    />
                                </svg>

                                <p class="text-sm leading-6 text-blue-700">
                                    Sau khi gửi, nội dung sẽ được lưu vào hệ thống để quản trị viên
                                    HomeStayGo tiếp nhận và xử lý.
                                </p>
                            </div>
                        </div>

                        {{-- Nút gửi --}}
                        <button
                            id="contact-submit-button"
                            type="submit"
                            class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-blue-600 px-6 py-3.5 font-semibold text-white shadow-lg shadow-blue-600/20 transition hover:-translate-y-0.5 hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-200 disabled:cursor-not-allowed disabled:opacity-70 sm:w-auto"
                        >
                            <svg
                                class="h-5 w-5"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="1.8"
                                    d="m6 12-3.269-9.53A59.769 59.769 0 0 1 21.485 12 59.768 59.768 0 0 1 2.731 21.53L6 12Zm0 0h7.5"
                                />
                            </svg>

                            <span id="contact-submit-text">
                                Gửi yêu cầu hỗ trợ
                            </span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const messageInput = document.getElementById('message');
            const messageCount = document.getElementById('message-count');
            const contactForm = document.querySelector(
                'form[action="{{ route('contact.store') }}"]'
            );
            const submitButton = document.getElementById(
                'contact-submit-button'
            );
            const submitText = document.getElementById(
                'contact-submit-text'
            );

            function updateMessageCount() {
                if (!messageInput || !messageCount) {
                    return;
                }

                messageCount.textContent =
                    messageInput.value.length + '/5000';
            }

            updateMessageCount();

            if (messageInput) {
                messageInput.addEventListener(
                    'input',
                    updateMessageCount
                );
            }

            if (contactForm && submitButton && submitText) {
                contactForm.addEventListener('submit', function () {
                    submitButton.disabled = true;
                    submitText.textContent = 'Đang gửi...';
                });
            }
        });
    </script>
@endsection