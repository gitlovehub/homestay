@extends('layouts.admin')

@section('title', 'Quản lý liên hệ | HomeStayGo')

@section('page-title', 'Quản lý liên hệ')

@section('content')
    @php
        $statusConfig = [
            'unread' => [
                'label' => 'Chưa đọc',
                'badge' => 'border-red-200 bg-red-50 text-red-700 dark:border-red-800 dark:bg-red-950/40 dark:text-red-300',
                'dot' => 'bg-red-500',
            ],

            'read' => [
                'label' => 'Đã đọc',
                'badge' => 'border-blue-200 bg-blue-50 text-blue-700 dark:border-blue-800 dark:bg-blue-950/40 dark:text-blue-300',
                'dot' => 'bg-blue-500',
            ],

            'replied' => [
                'label' => 'Đã phản hồi',
                'badge' => 'border-emerald-200 bg-emerald-50 text-emerald-700 dark:border-emerald-800 dark:bg-emerald-950/40 dark:text-emerald-300',
                'dot' => 'bg-emerald-500',
            ],
        ];
    @endphp

    <div class="mx-auto max-w-screen-2xl">

        <x-alert />

        <div class="mb-8">
            <h2 class="text-lg font-semibold text-slate-900 dark:text-slate-100 md:text-2xl">
                Tiếp nhận và quản lý các yêu cầu hỗ trợ do người dùng gửi đến HomeStayGo.
            </h2>
        </div>

        {{-- Thống kê --}}
        <section class="mb-8 grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">

            {{-- Tổng thư --}}
            <div
                class="flex items-center gap-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md dark:border-slate-700 dark:bg-slate-800"
            >
                <div
                    class="flex h-14 w-14 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-blue-600 dark:bg-blue-950/40 dark:text-blue-400"
                >
                    <svg
                        class="h-7 w-7"
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
                </div>

                <div>
                    <p class="text-sm font-medium text-slate-500 dark:text-slate-400">
                        Tổng yêu cầu
                    </p>

                    <p class="mt-1 text-2xl font-bold text-slate-900 dark:text-slate-100">
                        {{ number_format($statistics['total'], 0, ',', '.') }}
                    </p>
                </div>
            </div>

            {{-- Chưa đọc --}}
            <div
                class="flex items-center gap-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md dark:border-slate-700 dark:bg-slate-800"
            >
                <div
                    class="flex h-14 w-14 shrink-0 items-center justify-center rounded-xl bg-red-50 text-red-600 dark:bg-red-950/40 dark:text-red-400"
                >
                    <svg
                        class="h-7 w-7"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1.8"
                            d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 4.5h.008v.008H12V16.5Z"
                        />
                    </svg>
                </div>

                <div>
                    <p class="text-sm font-medium text-slate-500 dark:text-slate-400">
                        Chưa đọc
                    </p>

                    <p class="mt-1 text-2xl font-bold text-red-600 dark:text-red-400">
                        {{ number_format($statistics['unread'], 0, ',', '.') }}
                    </p>
                </div>
            </div>

            {{-- Đã đọc --}}
            <div
                class="flex items-center gap-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md dark:border-slate-700 dark:bg-slate-800"
            >
                <div
                    class="flex h-14 w-14 shrink-0 items-center justify-center rounded-xl bg-amber-50 text-amber-600 dark:bg-amber-950/40 dark:text-amber-400"
                >
                    <svg
                        class="h-7 w-7"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1.8"
                            d="M2.25 12s3.75-6.75 9.75-6.75S21.75 12 21.75 12 18 18.75 12 18.75 2.25 12 2.25 12Z"
                        />
                        <circle cx="12" cy="12" r="2.75" stroke-width="1.8" />
                    </svg>
                </div>

                <div>
                    <p class="text-sm font-medium text-slate-500 dark:text-slate-400">
                        Đã đọc
                    </p>

                    <p class="mt-1 text-2xl font-bold text-amber-600 dark:text-amber-400">
                        {{ number_format($statistics['read'], 0, ',', '.') }}
                    </p>
                </div>
            </div>

            {{-- Đã phản hồi --}}
            <div
                class="flex items-center gap-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md dark:border-slate-700 dark:bg-slate-800"
            >
                <div
                    class="flex h-14 w-14 shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600 dark:bg-emerald-950/40 dark:text-emerald-400"
                >
                    <svg
                        class="h-7 w-7"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2.5"
                            d="m5 12 4 4L19 6"
                        />
                    </svg>
                </div>

                <div>
                    <p class="text-sm font-medium text-slate-500 dark:text-slate-400">
                        Đã phản hồi
                    </p>

                    <p class="mt-1 text-2xl font-bold text-emerald-600 dark:text-emerald-400">
                        {{ number_format($statistics['replied'], 0, ',', '.') }}
                    </p>
                </div>
            </div>

        </section>

        {{-- Danh sách --}}
        <section
            class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-800"
        >

            {{-- Bộ lọc --}}
            <div class="border-b border-slate-200 bg-slate-50/70 p-5 dark:border-slate-700 dark:bg-slate-900/40">
                <form
                    method="GET"
                    action="{{ route('admin.contact-messages.index') }}"
                    class="grid gap-4 lg:grid-cols-12"
                >
                    {{-- Tìm kiếm --}}
                    <div class="lg:col-span-6">
                        <label
                            for="search"
                            class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-300"
                        >
                            Tìm kiếm
                        </label>

                        <div class="relative">
                            <span
                                class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400 dark:text-slate-500"
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
                                        stroke-width="2"
                                        d="m21 21-4.35-4.35M16.65 11A5.65 5.65 0 1 1 11 5.35 5.65 5.65 0 0 1 16.65 11Z"
                                    />
                                </svg>
                            </span>

                            <input
                                id="search"
                                name="search"
                                type="search"
                                value="{{ request('search') }}"
                                placeholder="Tên, email, số điện thoại, chủ đề..."
                                class="h-11 w-full rounded-xl border border-slate-300 bg-white pl-11 pr-4 text-sm text-slate-900 shadow-sm outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100 dark:placeholder:text-slate-500 dark:focus:border-blue-400 dark:focus:ring-blue-900/40"
                                onsearch="this.form.submit()"
                                oninput="if (this.value === '') this.form.submit()"
                            >
                        </div>
                    </div>

                    {{-- Trạng thái --}}
                    <div class="lg:col-span-2">
                        <label
                            for="status"
                            class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-300"
                        >
                            Trạng thái
                        </label>

                        <select
                            id="status"
                            name="status"
                            class="h-11 w-full cursor-pointer rounded-xl border border-slate-300 bg-white px-4 text-sm text-slate-900 shadow-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100 dark:focus:border-blue-400 dark:focus:ring-blue-900/40"
                        >
                            <option value="">
                                Tất cả trạng thái
                            </option>

                            <option
                                value="unread"
                                @selected(request('status') === 'unread')
                            >
                                Chưa đọc
                            </option>

                            <option
                                value="read"
                                @selected(request('status') === 'read')
                            >
                                Đã đọc
                            </option>

                            <option
                                value="replied"
                                @selected(request('status') === 'replied')
                            >
                                Đã phản hồi
                            </option>
                        </select>
                    </div>

                    {{-- Sắp xếp --}}
                    <div class="lg:col-span-2">
                        <label for="sort" class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-300">
                            Sắp xếp
                        </label>

                        <select id="sort" name="sort"
                            class="h-11 w-full cursor-pointer rounded-xl border border-slate-300 bg-white px-4 text-sm text-slate-900 shadow-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100 dark:focus:border-blue-400 dark:focus:ring-blue-900/40">
                            <option value="">Mới nhất</option>
                            <option value="oldest" @selected(request('sort') === 'oldest')>Cũ nhất</option>
                            <option value="unread_first" @selected(request('sort') === 'unread_first')>Chưa đọc trước</option>
                            <option value="replied_first" @selected(request('sort') === 'replied_first')>Đã phản hồi trước</option>
                        </select>
                    </div>

                    {{-- Xóa bộ lọc --}}
                    <div class="flex items-end lg:col-span-1">
                        @if (request()->hasAny(['search', 'status', 'sort']))
                            <a
                                href="{{ route('admin.contact-messages.index') }}"
                                title="Xóa bộ lọc"
                                class="inline-flex h-11 w-full items-center justify-center rounded-xl bg-blue-600 px-4 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-200 dark:focus:ring-blue-900/40"
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
                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 0 0 4.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 0 1-15.357-2m15.357 2H15"
                                    />
                                </svg>
                            </a>
                        @else
                            <button
                                type="button"
                                disabled
                                class="inline-flex h-11 w-full cursor-not-allowed items-center justify-center rounded-xl border border-slate-200 bg-slate-100 px-4 text-slate-400 dark:border-slate-700 dark:bg-slate-700 dark:text-slate-500"
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
                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 0 0 4.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 0 1-15.357-2m15.357 2H15"
                                    />
                                </svg>
                            </button>
                        @endif
                    </div>

                    {{-- Nút lọc --}}
                    <div class="flex items-end lg:col-span-1">
                        <button
                            type="submit"
                            class="inline-flex h-11 w-full cursor-pointer items-center justify-center rounded-xl bg-blue-600 px-4 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-200 dark:focus:ring-blue-900/40"
                        >
                            Lọc
                        </button>
                    </div>
                </form>
            </div>

            @if ($messages->count())
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[1050px] border-collapse text-left">

                        <thead>
                            <tr
                                class="border-b border-slate-200 bg-slate-50/70 text-xs font-bold uppercase tracking-wider text-slate-500 dark:border-slate-700 dark:bg-slate-900/40 dark:text-slate-400"
                            >
                                <th class="px-6 py-4">
                                    Người gửi
                                </th>

                                <th class="px-6 py-4">
                                    Chủ đề và nội dung
                                </th>

                                <th class="px-6 py-4">
                                    Điện thoại
                                </th>

                                <th class="px-6 py-4 text-center">
                                    Trạng thái
                                </th>

                                <th class="px-6 py-4">
                                    Thời gian gửi
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-200 text-sm dark:divide-slate-700">
                            @foreach ($messages as $contactMessage)
                                @php
                                    $currentStatus = $statusConfig[$contactMessage->status] ?? [
                                        'label' => 'Không xác định',
                                        'badge' => 'border-slate-200 bg-slate-50 text-slate-700 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-300',
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

                                <tr
                                    class="transition hover:bg-slate-50/80 dark:hover:bg-slate-700/40
                                        {{ $contactMessage->status === 'unread'
                                            ? 'bg-blue-50/30 dark:bg-blue-950/10'
                                            : '' }}"
                                >
                                    {{-- Người gửi --}}
                                    <td class="whitespace-nowrap px-6 py-5">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full text-sm font-bold
                                                    {{ $contactMessage->status === 'unread'
                                                        ? 'bg-blue-600 text-white'
                                                        : 'bg-blue-100 text-blue-700' }}"
                                            >
                                                {{ $avatarText ?: '?' }}
                                            </div>

                                            <div class="min-w-0">
                                                <div class="flex items-center gap-2">
                                                    <p
                                                        class="max-w-[180px] truncate font-semibold text-slate-900 dark:text-slate-100"
                                                    >
                                                        {{ $contactMessage->name }}
                                                    </p>

                                                    @if ($contactMessage->status === 'unread')
                                                        <span
                                                            class="h-2 w-2 shrink-0 rounded-full bg-red-500"
                                                            title="Thư chưa đọc"
                                                        ></span>
                                                    @endif
                                                </div>

                                                <p
                                                    class="mt-0.5 max-w-[200px] truncate text-xs text-slate-500 dark:text-slate-400"
                                                >
                                                    {{ $contactMessage->email }}
                                                </p>
                                            </div>
                                        </div>
                                    </td>

                                    {{-- Chủ đề và nội dung --}}
                                    <td class="px-6 py-5">
                                        <div class="max-w-md">
                                            <p
                                                class="truncate font-semibold
                                                    {{ $contactMessage->status === 'unread'
                                                        ? 'text-blue-700'
                                                        : 'text-slate-900' }}"
                                            >
                                                {{ $contactMessage->subject }}
                                            </p>

                                            <details class="mt-2">
                                                <summary
                                                    class="inline-flex cursor-pointer list-none items-center text-xs font-semibold text-blue-600 transition hover:text-blue-700 dark:hover:text-blue-300 dark:text-blue-400"
                                                >
                                                    Xem nhanh nội dung
                                                </summary>

                                                <div
                                                    class="mt-3 max-w-lg rounded-xl border border-slate-200 bg-slate-50 p-4 text-sm leading-6 text-slate-700 dark:border-slate-600 dark:bg-slate-700/50 dark:text-slate-300 dark:border-slate-700 dark:bg-slate-900/40"
                                                >
                                                    {{ $contactMessage->message }}
                                                </div>
                                            </details>
                                        </div>
                                    </td>

                                    {{-- Số điện thoại --}}
                                    <td class="whitespace-nowrap px-6 py-5">
                                        @if ($contactMessage->phone)
                                            <a
                                                href="tel:{{ $contactMessage->phone }}"
                                                class="font-semibold text-blue-600 transition hover:text-blue-700 hover:underline dark:hover:text-blue-300 dark:text-blue-400"
                                            >
                                                {{ $contactMessage->phone }}
                                            </a>
                                        @else
                                            <span class="text-slate-400 dark:text-slate-500">
                                                Không cung cấp
                                            </span>
                                        @endif
                                    </td>

                                    {{-- Trạng thái --}}
                                    <td class="whitespace-nowrap px-6 py-5 text-center">
                                        <span
                                            class="inline-flex items-center gap-2 rounded-full border px-4 py-1.5 text-xs font-semibold {{ $currentStatus['badge'] }}"
                                        >
                                            <span
                                                class="h-1.5 w-1.5 rounded-full {{ $currentStatus['dot'] }}"
                                            ></span>

                                            {{ $currentStatus['label'] }}
                                        </span>
                                    </td>

                                    {{-- Thời gian --}}
                                    <td class="whitespace-nowrap px-6 py-5">
                                        <p class="font-semibold text-slate-800 dark:text-slate-200">
                                            {{ $contactMessage->created_at->format('d/m/Y') }}
                                        </p>

                                        <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
                                            {{ $contactMessage->created_at->format('H:i') }}
                                        </p>

                                        <p class="mt-1 text-xs text-slate-400 dark:text-slate-500">
                                            {{ $contactMessage->created_at->diffForHumans() }}
                                        </p>
                                    </td>

                                    {{-- Thao tác --}}
                                    <td class="whitespace-nowrap px-6 py-5 text-right">
                                        <a
                                            href="{{ route('admin.contact-messages.show', $contactMessage) }}"
                                            class="inline-flex h-10 items-center justify-center gap-2 rounded-xl border border-blue-200 bg-blue-50 px-4 text-sm font-semibold text-blue-700 transition hover:border-blue-300 hover:bg-blue-100 dark:border-blue-800 dark:bg-blue-950/40 dark:text-blue-300 dark:hover:border-blue-700 dark:hover:bg-blue-950/60"
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
                                                    stroke-width="1.8"
                                                    d="M2.25 12s3.75-6.75 9.75-6.75S21.75 12 21.75 12 18 18.75 12 18.75 2.25 12 2.25 12Z"
                                                />
                                                <circle
                                                    cx="12"
                                                    cy="12"
                                                    r="2.75"
                                                    stroke-width="1.8"
                                                />
                                            </svg>

                                            Xem chi tiết
                                        </a>
                                    </td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Phân trang --}}
                <div class="border-t border-slate-200 px-6 py-4 dark:border-slate-700">
                    {{ $messages->onEachSide(1)->links('components.pagination', [
                        'layout' => 'row',
                        'showInfo' => true,
                    ]) }}
                </div>
            @else
                <div class="px-6 py-20 text-center">
                    <div
                        class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-slate-100 text-slate-400 dark:bg-slate-700 dark:text-slate-500"
                    >
                        <svg
                            class="h-8 w-8"
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
                    </div>

                    <h2 class="mt-5 text-lg font-bold text-slate-900 dark:text-slate-100">
                        Chưa có yêu cầu liên hệ phù hợp
                    </h2>

                    <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-slate-500 dark:text-slate-400">
                        Không tìm thấy thư phù hợp với nội dung tìm kiếm hoặc
                        trạng thái đang được lựa chọn.
                    </p>

                    @if (request()->hasAny(['search', 'status', 'sort']))
                        <a
                            href="{{ route('admin.contact-messages.index') }}"
                            class="mt-5 inline-flex h-11 items-center justify-center rounded-xl bg-blue-600 px-5 text-sm font-semibold text-white transition hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-200 dark:focus:ring-blue-900/40"
                        >
                            Xóa bộ lọc
                        </a>
                    @endif
                </div>
            @endif
        </section>
    </div>
@endsection