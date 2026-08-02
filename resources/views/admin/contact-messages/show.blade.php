@extends('layouts.admin')

@section('title', 'Chi tiết liên hệ | HomeStayGo')

@section('page-title', 'Chi tiết liên hệ')

@section('content')
    @php
        $statusConfig = [
            'unread' => [
                'label' => 'Chưa đọc',
                'badge' => 'border-red-200 bg-red-50 text-red-700',
                'dot' => 'bg-red-500',
            ],

            'read' => [
                'label' => 'Đã đọc',
                'badge' => 'border-blue-200 bg-blue-50 text-blue-700',
                'dot' => 'bg-blue-500',
            ],

            'replied' => [
                'label' => 'Đã phản hồi',
                'badge' => 'border-emerald-200 bg-emerald-50 text-emerald-700',
                'dot' => 'bg-emerald-500',
            ],
        ];

        $currentStatus = $statusConfig[$contactMessage->status] ?? [
            'label' => 'Không xác định',
            'badge' => 'border-slate-200 bg-slate-50 text-slate-700',
            'dot' => 'bg-slate-400',
        ];

        $nameParts = preg_split(
            '/\s+/',
            trim($contactMessage->name)
        );

        $avatarText = collect($nameParts)
            ->filter()
            ->take(2)
            ->map(
                fn ($part) =>
                    mb_strtoupper(
                        mb_substr($part, 0, 1)
                    )
            )
            ->implode('');
    @endphp

    <div class="mx-auto max-w-screen-2xl">

        <x-alert />

        {{-- Phần tiêu đề --}}
        <section class="mb-6 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-200 bg-slate-50/70 px-5 py-5 sm:px-6">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

                    <div class="min-w-0">
                        <div class="flex flex-wrap items-center gap-3">
                            <h2 class="text-2xl font-bold text-slate-900 sm:text-3xl">
                                Yêu cầu hỗ trợ #{{ $contactMessage->id }}
                            </h2>

                            <span
                                class="inline-flex items-center gap-2 rounded-full border px-3 py-1.5 text-xs font-semibold {{ $currentStatus['badge'] }}"
                            >
                                <span
                                    class="h-2 w-2 rounded-full {{ $currentStatus['dot'] }}"
                                ></span>

                                {{ $currentStatus['label'] }}
                            </span>
                        </div>

                        <p class="mt-2 text-sm text-slate-500">
                            Được gửi lúc
                            {{ $contactMessage->created_at->format('H:i, d/m/Y') }}
                        </p>
                    </div>

                    <a
                        href="{{ route('admin.contact-messages.index') }}"
                        class="inline-flex shrink-0 items-center justify-center gap-2 rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:border-blue-300 hover:bg-blue-50 hover:text-blue-600"
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

                        Danh sách liên hệ
                    </a>
                </div>
            </div>

            {{-- Thông tin tổng quan --}}
            <div class="grid divide-y divide-slate-200 sm:grid-cols-2 sm:divide-x sm:divide-y-0 xl:grid-cols-4">

                <div class="px-5 py-4 sm:px-6">
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                        Người gửi
                    </p>

                    <p class="mt-2 truncate font-bold text-slate-900">
                        {{ $contactMessage->name }}
                    </p>

                    <p class="mt-1 truncate text-sm text-slate-500">
                        {{ $contactMessage->email }}
                    </p>
                </div>

                <div class="px-5 py-4 sm:px-6">
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                        Chủ đề
                    </p>

                    <p class="mt-2 line-clamp-2 font-bold text-slate-900">
                        {{ $contactMessage->subject }}
                    </p>
                </div>

                <div class="px-5 py-4 sm:px-6">
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                        Thời gian gửi
                    </p>

                    <p class="mt-2 font-bold text-slate-900">
                        {{ $contactMessage->created_at->format('d/m/Y') }}
                    </p>

                    <p class="mt-1 text-sm text-slate-500">
                        {{ $contactMessage->created_at->format('H:i') }}
                    </p>
                </div>

                <div class="px-5 py-4 sm:px-6">
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                        Số phản hồi
                    </p>

                    <p class="mt-2 text-xl font-bold text-blue-600">
                        {{ $contactMessage->replies->count() }}
                    </p>

                    <p class="mt-1 text-sm text-slate-500">
                        phản hồi đã gửi
                    </p>
                </div>

            </div>
        </section>

        <div class="grid gap-6 xl:grid-cols-12">

            {{-- Cột nội dung chính --}}
            <div class="space-y-6 xl:col-span-8">

                {{-- Nội dung yêu cầu --}}
                <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                    <div class="flex items-center gap-3 border-b border-slate-200 bg-slate-50 px-5 py-4 sm:px-6">

                        <span
                            class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-100 text-blue-600"
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
                                    d="M4 4h16v13H8l-4 4V4Z"
                                />
                            </svg>
                        </span>

                        <div>
                            <h3 class="font-bold text-slate-900">
                                Nội dung yêu cầu hỗ trợ
                            </h3>

                            <p class="mt-0.5 text-sm text-slate-500">
                                Nội dung do người dùng gửi đến HomeStayGo.
                            </p>
                        </div>
                    </div>

                    <div class="space-y-5 p-5 sm:p-6">

                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                                Chủ đề cần hỗ trợ
                            </p>

                            <p class="mt-2 text-lg font-bold text-slate-900">
                                {{ $contactMessage->subject }}
                            </p>
                        </div>

                        <div class="border-t border-slate-200 pt-5">
                            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                                Nội dung liên hệ
                            </p>

                            <div
                                class="mt-3 whitespace-pre-line break-words rounded-xl border border-slate-200 bg-slate-50 p-5 text-sm leading-7 text-slate-700 sm:text-base"
                            >{{ $contactMessage->message }}</div>
                        </div>

                    </div>
                </section>

                {{-- Thông tin người gửi --}}
                <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                    <div class="flex items-center gap-3 border-b border-slate-200 bg-slate-50 px-5 py-4 sm:px-6">

                        <span
                            class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-violet-100 text-violet-600"
                        >
                            <svg
                                class="h-5 w-5"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <circle cx="12" cy="8" r="4" stroke-width="1.8" />
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="1.8"
                                    d="M4 21a8 8 0 0 1 16 0"
                                />
                            </svg>
                        </span>

                        <div>
                            <h3 class="font-bold text-slate-900">
                                Thông tin người gửi
                            </h3>

                            <p class="mt-0.5 text-sm text-slate-500">
                                Thông tin tài khoản gửi yêu cầu.
                            </p>
                        </div>
                    </div>

                    <div class="grid gap-5 p-5 sm:grid-cols-2 sm:p-6">

                        <div class="rounded-xl border border-slate-200 bg-slate-50/50 p-4">
                            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                                Họ và tên
                            </p>

                            <p class="mt-2 font-semibold text-slate-900">
                                {{ $contactMessage->name }}
                            </p>
                        </div>

                        <div class="rounded-xl border border-slate-200 bg-slate-50/50 p-4">
                            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                                Email nhận phản hồi
                            </p>

                            <p class="mt-2 break-all font-semibold text-blue-600">
                                {{ $contactMessage->email }}
                            </p>
                        </div>

                        <div class="rounded-xl border border-slate-200 bg-slate-50/50 p-4">
                            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                                Số điện thoại
                            </p>

                            <p class="mt-2 font-semibold text-slate-900">
                                {{ $contactMessage->phone ?: 'Không cung cấp' }}
                            </p>
                        </div>

                        <div class="rounded-xl border border-slate-200 bg-slate-50/50 p-4">
                            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                                Trạng thái
                            </p>

                            @if ($contactMessage->user)
                                <div class="mt-2">
                                    @if ($contactMessage->user->status === 'active')
                                        <span
                                            class="inline-flex items-center gap-2 rounded-full border border-emerald-200 bg-emerald-50 px-3 py-1.5 text-xs font-semibold text-emerald-700"
                                        >
                                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                            Đang hoạt động
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center gap-2 rounded-full border border-red-200 bg-red-50 px-3 py-1.5 text-xs font-semibold text-red-700"
                                        >
                                            <span class="h-1.5 w-1.5 rounded-full bg-red-500"></span>
                                            Không hoạt động
                                        </span>
                                    @endif
                                </div>
                            @else
                                <span
                                    class="mt-2 inline-flex items-center gap-2 rounded-full border border-slate-200 bg-slate-50 px-3 py-1.5 text-xs font-semibold text-slate-600"
                                >
                                    <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>
                                    Tài khoản không còn tồn tại
                                </span>
                            @endif
                        </div>

                    </div>
                </section>

                {{-- Lịch sử phản hồi --}}
                <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                    <div class="flex items-center gap-3 border-b border-slate-200 bg-slate-50 px-5 py-4 sm:px-6">

                        <span
                            class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-emerald-100 text-emerald-600"
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
                                    d="m5 12 4 4L19 6"
                                />
                            </svg>
                        </span>

                        <div>
                            <h3 class="font-bold text-slate-900">
                                Lịch sử phản hồi
                            </h3>

                            <p class="mt-0.5 text-sm text-slate-500">
                                Các phản hồi đã gửi đến người dùng.
                            </p>
                        </div>
                    </div>

                    <div class="p-5 sm:p-6">
                        @forelse ($contactMessage->replies as $reply)
                            <div
                                class="border-b border-slate-200 py-5 first:pt-0 last:border-b-0 last:pb-0"
                            >
                                <div class="flex flex-col gap-2 sm:flex-row sm:items-start sm:justify-between">

                                    <div>
                                        <p class="font-bold text-slate-900">
                                            {{ $reply->subject }}
                                        </p>

                                        <p class="mt-1 text-xs text-slate-500">
                                            Phản hồi bởi
                                            {{ $reply->admin?->name ?? 'Admin không còn tồn tại' }}
                                        </p>
                                    </div>

                                    <p class="shrink-0 text-xs font-medium text-slate-400">
                                        {{ $reply->sent_at?->format('H:i d/m/Y') }}
                                    </p>
                                </div>

                                <div
                                    class="mt-4 whitespace-pre-line break-words rounded-xl border border-emerald-100 bg-emerald-50/60 p-4 text-sm leading-7 text-slate-700"
                                >{{ $reply->message }}</div>
                            </div>
                        @empty
                            <div
                                class="rounded-xl border border-dashed border-slate-300 bg-slate-50 px-5 py-10 text-center"
                            >
                                <div
                                    class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-slate-100 text-slate-400"
                                >
                                    <svg
                                        class="h-6 w-6"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="1.8"
                                            d="M4 6h16v12H4zM4 8l8 5 8-5"
                                        />
                                    </svg>
                                </div>

                                <p class="mt-4 font-semibold text-slate-700">
                                    Chưa có phản hồi
                                </p>

                                <p class="mt-1 text-sm text-slate-500">
                                    Yêu cầu này chưa được quản trị viên phản hồi.
                                </p>
                            </div>
                        @endforelse
                    </div>
                </section>

            </div>

            {{-- Cột bên phải --}}
            <aside class="space-y-6 xl:col-span-4">
                <div class="space-y-6 xl:sticky xl:top-24">

                    {{-- Form phản hồi --}}
                    <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                        <div class="border-b border-slate-200 bg-slate-50 px-5 py-4">
                            <h3 class="font-bold text-slate-900">
                                Phản hồi người dùng
                            </h3>

                            <p class="mt-1 text-sm text-slate-500">
                                Email sẽ được gửi đến địa chỉ của người dùng.
                            </p>
                        </div>

                        <form
                            id="contact-reply-form"
                            method="POST"
                            action="{{ route('admin.contact-messages.reply', $contactMessage) }}"
                            class="space-y-4 p-5"
                        >
                            @csrf

                            {{-- Người nhận --}}
                            <div>
                                <label
                                    for="reply_email"
                                    class="mb-2 block text-sm font-semibold text-slate-700"
                                >
                                    Người nhận
                                </label>

                                <input
                                    id="reply_email"
                                    type="email"
                                    value="{{ $contactMessage->email }}"
                                    readonly
                                    class="w-full cursor-not-allowed rounded-xl border border-slate-200 bg-slate-100 px-4 py-3 text-sm text-slate-600"
                                >
                            </div>

                            {{-- Tiêu đề --}}
                            <div>
                                <label
                                    for="reply_subject"
                                    class="mb-2 block text-sm font-semibold text-slate-700"
                                >
                                    Tiêu đề phản hồi
                                    <span class="text-red-500">*</span>
                                </label>

                                <input
                                    id="reply_subject"
                                    type="text"
                                    name="reply_subject"
                                    maxlength="255"
                                    value="{{ old(
                                        'reply_subject',
                                        'Phản hồi: ' . $contactMessage->subject
                                    ) }}"
                                    placeholder="Nhập tiêu đề phản hồi"
                                    class="w-full rounded-xl border bg-white px-4 py-3 text-sm text-slate-900 outline-none transition placeholder:text-slate-400
                                        {{ $errors->has('reply_subject')
                                            ? 'border-red-400 focus:border-red-500 focus:ring-4 focus:ring-red-100'
                                            : 'border-slate-300 focus:border-blue-500 focus:ring-4 focus:ring-blue-100' }}"
                                >

                                @error('reply_subject')
                                    <p class="mt-1.5 text-sm font-medium text-red-600">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            {{-- Nội dung --}}
                            <div>
                                <div class="mb-2 flex items-center justify-between gap-3">
                                    <label
                                        for="reply_message"
                                        class="block text-sm font-semibold text-slate-700"
                                    >
                                        Nội dung phản hồi
                                        <span class="text-red-500">*</span>
                                    </label>

                                    <span
                                        id="reply-message-count"
                                        class="text-xs font-medium text-slate-400"
                                    >
                                        {{ mb_strlen(old('reply_message', '')) }}/5000
                                    </span>
                                </div>

                                <textarea
                                    id="reply_message"
                                    name="reply_message"
                                    rows="8"
                                    maxlength="5000"
                                    placeholder="Nhập nội dung phản hồi cho người dùng..."
                                    class="w-full resize-none rounded-xl border bg-white px-4 py-3 text-sm leading-6 text-slate-900 outline-none transition placeholder:text-slate-400
                                        {{ $errors->has('reply_message')
                                            ? 'border-red-400 focus:border-red-500 focus:ring-4 focus:ring-red-100'
                                            : 'border-slate-300 focus:border-blue-500 focus:ring-4 focus:ring-blue-100' }}"
                                >{{ old('reply_message') }}</textarea>

                                @error('reply_message')
                                    <p class="mt-1.5 text-sm font-medium text-red-600">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            {{-- Cảnh báo --}}
                            <div class="rounded-xl border border-blue-200 bg-blue-50 p-4">
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
                                        Sau khi gửi thành công, phản hồi sẽ được lưu vào lịch sử
                                        và trạng thái yêu cầu chuyển sang “Đã phản hồi”.
                                    </p>
                                </div>
                            </div>

                            {{-- Nút gửi --}}
                            <button
                                id="contact-reply-button"
                                type="submit"
                                class="inline-flex w-full cursor-pointer items-center justify-center gap-2 rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-200 disabled:cursor-not-allowed disabled:opacity-70"
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

                                <span id="contact-reply-button-text">
                                    Gửi phản hồi
                                </span>
                            </button>
                        </form>
                    </section>

                    {{-- Trạng thái xử lý --}}
                    <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                        <div class="border-b border-slate-200 bg-slate-50 px-5 py-4">
                            <h3 class="font-bold text-slate-900">
                                Trạng thái xử lý
                            </h3>
                        </div>

                        <div class="p-5">
                            <span
                                class="inline-flex items-center gap-2 rounded-full border px-4 py-2 text-sm font-semibold {{ $currentStatus['badge'] }}"
                            >
                                <span
                                    class="h-2 w-2 rounded-full {{ $currentStatus['dot'] }}"
                                ></span>

                                {{ $currentStatus['label'] }}
                            </span>

                            <div class="mt-5 space-y-4">

                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                                        Thời gian gửi
                                    </p>

                                    <p class="mt-1 font-semibold text-slate-900">
                                        {{ $contactMessage->created_at->format('H:i d/m/Y') }}
                                    </p>
                                </div>

                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                                        Thời gian đọc
                                    </p>

                                    <p class="mt-1 font-semibold text-slate-900">
                                        {{ $contactMessage->read_at
                                            ? $contactMessage->read_at->format('H:i d/m/Y')
                                            : 'Chưa đọc' }}
                                    </p>
                                </div>

                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                                        Phản hồi gần nhất
                                    </p>

                                    <p class="mt-1 font-semibold text-slate-900">
                                        {{ $contactMessage->replied_at
                                            ? $contactMessage->replied_at->format('H:i d/m/Y')
                                            : 'Chưa phản hồi' }}
                                    </p>
                                </div>

                            </div>
                        </div>
                    </section>

                </div>
            </aside>

        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const replyForm = document.getElementById(
                'contact-reply-form'
            );

            const replyMessage = document.getElementById(
                'reply_message'
            );

            const replyMessageCount = document.getElementById(
                'reply-message-count'
            );

            const replyButton = document.getElementById(
                'contact-reply-button'
            );

            const replyButtonText = document.getElementById(
                'contact-reply-button-text'
            );

            function updateReplyMessageCount() {
                if (!replyMessage || !replyMessageCount) {
                    return;
                }

                replyMessageCount.textContent =
                    replyMessage.value.length + '/5000';
            }

            updateReplyMessageCount();

            if (replyMessage) {
                replyMessage.addEventListener(
                    'input',
                    updateReplyMessageCount
                );
            }

            if (replyForm && replyButton && replyButtonText) {
                replyForm.addEventListener('submit', function () {
                    replyButton.disabled = true;
                    replyButtonText.textContent = 'Đang gửi...';
                });
            }
        });
    </script>
@endpush