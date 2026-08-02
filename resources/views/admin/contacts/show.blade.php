@extends('layouts.admin')

@section('title', 'Chi tiết liên hệ | HomeStayGo')

@section('page-title', 'Chi tiết liên hệ')

@section('content')
    <div class="space-y-6">

        {{-- Thông báo --}}
        @if (session('success'))
            <div
                class="flex items-start gap-3 rounded-2xl border border-green-200 bg-green-50 px-5 py-4 text-green-700"
            >
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


        @if (session('error'))
            <div class="rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-sm font-medium text-red-700">
                {{ session('error') }}
            </div>
        @endif

        {{-- Điều hướng --}}
        <div
            class="flex flex-col gap-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:flex-row sm:items-center sm:justify-between"
        >
            <div>
                <a
                    href="{{ route('admin.contacts.index') }}"
                    class="inline-flex items-center gap-2 text-sm font-semibold text-slate-500 transition hover:text-blue-600"
                >
                    <svg
                        class="h-4 w-4"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="m15 18-6-6 6-6"
                        />
                    </svg>

                    Quay lại danh sách
                </a>

                <h2 class="mt-3 text-2xl font-bold text-slate-900">
                    {{ $contact->subject }}
                </h2>

                <p class="mt-2 text-sm text-slate-500">
                    Gửi lúc
                    {{ $contact->created_at->format('H:i, d/m/Y') }}
                </p>
            </div>

            @if ($contact->status === 'unread')
                <span
                    class="inline-flex w-fit items-center gap-2 rounded-full bg-red-50 px-4 py-2 text-sm font-bold text-red-600"
                >
                    <span
                        class="h-2 w-2 rounded-full bg-red-500"
                    ></span>

                    Chưa đọc
                </span>
            @else
                <span
                    class="inline-flex w-fit items-center gap-2 rounded-full bg-green-50 px-4 py-2 text-sm font-bold text-green-600"
                >
                    <span
                        class="h-2 w-2 rounded-full bg-green-500"
                    ></span>

                    Đã đọc
                </span>
            @endif
        </div>

        <div class="grid gap-6 xl:grid-cols-[minmax(0,1fr)_340px]">

            {{-- Nội dung chính --}}
            <div class="space-y-6">

                {{-- Người gửi --}}
                <div
                    class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm"
                >
                    <div class="flex items-start gap-4">
                        <div
                            class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-blue-600 text-xl font-bold text-white shadow-lg shadow-blue-600/20"
                        >
                            {{ mb_strtoupper(mb_substr($contact->name, 0, 1)) }}
                        </div>

                        <div class="min-w-0">
                            <p class="text-xs font-bold uppercase tracking-wider text-blue-600">
                                Người gửi
                            </p>

                            <h3 class="mt-1 text-xl font-bold text-slate-900">
                                {{ $contact->name }}
                            </h3>

                            <p class="mt-1 break-all text-sm text-slate-500">
                                {{ $contact->email }}
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Nội dung thư --}}
                <div
                    class="rounded-2xl border border-slate-200 bg-white shadow-sm"
                >
                    <div
                        class="border-b border-slate-200 px-6 py-5"
                    >
                        <p class="text-xs font-bold uppercase tracking-wider text-blue-600">
                            Nội dung yêu cầu
                        </p>

                        <h3 class="mt-1 text-xl font-bold text-slate-900">
                            {{ $contact->subject }}
                        </h3>
                    </div>

                    <div class="p-6">
                        <div
                            class="whitespace-pre-line break-words text-[15px] leading-8 text-slate-700"
                        >{{ $contact->message }}</div>
                    </div>
                </div>

                {{-- Cảnh báo --}}
                <div
                    class="rounded-2xl border border-amber-200 bg-amber-50 p-5"
                >
                    <div class="flex items-start gap-3">
                        <svg
                            class="mt-0.5 h-5 w-5 shrink-0 text-amber-600"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1.8"
                                d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z"
                            />
                        </svg>

                        <div>
                            <h3 class="font-bold text-amber-900">
                                Lưu ý khi phản hồi
                            </h3>

                            <p class="mt-1 text-sm leading-6 text-amber-700">
                                Kiểm tra kỹ thông tin tài khoản và mã đặt phòng
                                trước khi hỗ trợ. Không yêu cầu người dùng cung
                                cấp mật khẩu hoặc thông tin thanh toán nhạy cảm.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Thanh bên --}}
            <div class="space-y-6">

                {{-- Thông tin liên hệ --}}
                <div
                    class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm"
                >
                    <h3 class="text-lg font-bold text-slate-900">
                        Thông tin liên hệ
                    </h3>

                    <div class="mt-5 space-y-5">

                        {{-- Họ tên --}}
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                                Họ và tên
                            </p>

                            <p class="mt-1 break-words text-sm font-semibold text-slate-800">
                                {{ $contact->name }}
                            </p>
                        </div>

                        {{-- Email --}}
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                                Email
                            </p>

                            <p class="mt-1 break-all text-sm font-semibold text-blue-600">
                                {{ $contact->email }}
                            </p>
                        </div>

                        {{-- Điện thoại --}}
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                                Số điện thoại
                            </p>

                            @if ($contact->phone)
                                <a
                                    href="tel:{{ $contact->phone }}"
                                    class="mt-1 block text-sm font-semibold text-blue-600 transition hover:text-blue-700"
                                >
                                    {{ $contact->phone }}
                                </a>
                            @else
                                <p class="mt-1 text-sm text-slate-400">
                                    Không cung cấp
                                </p>
                            @endif
                        </div>

                        {{-- Chủ đề --}}
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                                Chủ đề
                            </p>

                            <p class="mt-1 break-words text-sm font-semibold leading-6 text-slate-800">
                                {{ $contact->subject }}
                            </p>
                        </div>

                        {{-- Thời gian --}}
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                                Ngày gửi
                            </p>

                            <p class="mt-1 text-sm font-semibold text-slate-800">
                                {{ $contact->created_at->format('d/m/Y H:i') }}
                            </p>
                        </div>
                    </div>

                    <form
                        action="{{ route('admin.contacts.reply', $contact) }}"
                        method="POST"
                        class="mt-6 space-y-4 border-t border-slate-100 pt-6"
                    >
                        @csrf

                        <div>
                            <label for="reply_subject" class="mb-2 block text-sm font-bold text-slate-700">
                                Tiêu đề phản hồi
                            </label>

                            <input
                                type="text"
                                id="reply_subject"
                                name="reply_subject"
                                value="{{ old('reply_subject', $contact->reply_subject ?: 'Phản hồi: ' . $contact->subject) }}"
                                class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                            >

                            @error('reply_subject')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="reply_message" class="mb-2 block text-sm font-bold text-slate-700">
                                Nội dung phản hồi
                            </label>

                            <textarea
                                id="reply_message"
                                name="reply_message"
                                rows="7"
                                placeholder="Nhập nội dung phản hồi cho người dùng..."
                                class="w-full resize-y rounded-xl border border-slate-300 px-4 py-3 text-sm leading-6 outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                            >{{ old('reply_message', $contact->reply_message) }}</textarea>

                            @error('reply_message')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <button
                            type="submit"
                            class="inline-flex w-full cursor-pointer items-center justify-center gap-2 rounded-xl bg-blue-600 px-5 py-3 text-sm font-bold text-white transition hover:bg-blue-700"
                        >
                            Gửi phản hồi ngay trên website
                        </button>
                    </form>

                    @if ($contact->replied_at)
                        <div class="mt-5 rounded-xl border border-emerald-200 bg-emerald-50 p-4">
                            <p class="text-sm font-bold text-emerald-700">
                                Đã phản hồi lúc {{ $contact->replied_at->format('H:i, d/m/Y') }}
                            </p>
                        </div>
                    @endif
                </div>

                {{-- Trạng thái --}}
                <div
                    class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm"
                >
                    <h3 class="text-lg font-bold text-slate-900">
                        Xử lý thư
                    </h3>

                    <p class="mt-2 text-sm leading-6 text-slate-500">
                        Thay đổi trạng thái hoặc xóa thư liên hệ này.
                    </p>

                    <div class="mt-5 space-y-3">
                        @if ($contact->status === 'read')
                            <form
                                action="{{ route('admin.contacts.update-status', $contact) }}"
                                method="POST"
                            >
                                @csrf
                                @method('PATCH')

                                <input
                                    type="hidden"
                                    name="status"
                                    value="unread"
                                >

                                <button
                                    type="submit"
                                    class="inline-flex w-full cursor-pointer items-center justify-center gap-2 rounded-xl border border-amber-200 bg-amber-50 px-5 py-3 text-sm font-bold text-amber-700 transition hover:bg-amber-100"
                                >
                                    Đánh dấu chưa đọc
                                </button>
                            </form>
                        @else
                            <form
                                action="{{ route('admin.contacts.update-status', $contact) }}"
                                method="POST"
                            >
                                @csrf
                                @method('PATCH')

                                <input
                                    type="hidden"
                                    name="status"
                                    value="read"
                                >

                                <button
                                    type="submit"
                                    class="inline-flex w-full cursor-pointer items-center justify-center gap-2 rounded-xl border border-green-200 bg-green-50 px-5 py-3 text-sm font-bold text-green-700 transition hover:bg-green-100"
                                >
                                    Đánh dấu đã đọc
                                </button>
                            </form>
                        @endif

                        <form
                            action="{{ route('admin.contacts.destroy', $contact) }}"
                            method="POST"
                            onsubmit="return confirm('Bạn có chắc muốn xóa thư liên hệ này? Hành động này không thể hoàn tác.')"
                        >
                            @csrf
                            @method('DELETE')

                            <button
                                type="submit"
                                class="inline-flex w-full cursor-pointer items-center justify-center gap-2 rounded-xl border border-red-200 bg-red-50 px-5 py-3 text-sm font-bold text-red-600 transition hover:bg-red-600 hover:text-white"
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
                                        d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.166L18.16 19.673A2.25 2.25 0 0 1 15.916 21H8.084a2.25 2.25 0 0 1-2.244-1.327L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0V4.477c0-1.09-.89-1.977-1.98-2.012a52.564 52.564 0 0 0-3.54 0c-1.09.035-1.98.922-1.98 2.012v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"
                                    />
                                </svg>

                                Xóa thư liên hệ
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection