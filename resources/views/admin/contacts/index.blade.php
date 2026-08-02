@extends('layouts.admin')

@section('title', 'Quản lý liên hệ | HomeStayGo')

@section('page-title', 'Quản lý liên hệ')

@section('content')
    <div class="space-y-6">

        {{-- Tiêu đề --}}
        <div
            class="flex flex-col gap-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:flex-row sm:items-center sm:justify-between"
        >
            <div>
                <p class="text-sm font-semibold text-blue-600">
                    Hộp thư hỗ trợ
                </p>

                <h2 class="mt-1 text-2xl font-bold text-slate-900">
                    Thư liên hệ từ người dùng
                </h2>

                <p class="mt-2 text-sm leading-6 text-slate-500">
                    Theo dõi và xử lý các yêu cầu hỗ trợ được gửi từ
                    trang liên hệ của HomeStayGo.
                </p>
            </div>

            @if ($statistics['unread'] > 0)
                <div
                    class="inline-flex w-fit items-center gap-2 rounded-xl bg-red-50 px-4 py-3 text-sm font-semibold text-red-600"
                >
                    <span
                        class="h-2.5 w-2.5 animate-pulse rounded-full bg-red-500"
                    ></span>

                    {{ $statistics['unread'] }} thư chưa đọc
                </div>
            @endif
        </div>

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

        {{-- Thống kê --}}
        <div class="grid gap-4 sm:grid-cols-3">

            {{-- Tổng số thư --}}
            <a
                href="{{ route('admin.contacts.index') }}"
                class="group rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:border-blue-200 hover:shadow-md"
            >
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <p class="text-sm font-medium text-slate-500">
                            Tổng thư
                        </p>

                        <p class="mt-2 text-3xl font-bold text-slate-900">
                            {{ $statistics['total'] }}
                        </p>
                    </div>

                    <span
                        class="flex h-12 w-12 items-center justify-center rounded-2xl bg-blue-50 text-blue-600 transition group-hover:bg-blue-600 group-hover:text-white"
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
                </div>
            </a>

            {{-- Chưa đọc --}}
            <a
                href="{{ route('admin.contacts.index', ['status' => 'unread']) }}"
                class="group rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:border-red-200 hover:shadow-md"
            >
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <p class="text-sm font-medium text-slate-500">
                            Chưa đọc
                        </p>

                        <p class="mt-2 text-3xl font-bold text-red-600">
                            {{ $statistics['unread'] }}
                        </p>
                    </div>

                    <span
                        class="flex h-12 w-12 items-center justify-center rounded-2xl bg-red-50 text-red-600 transition group-hover:bg-red-600 group-hover:text-white"
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
                                d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z"
                            />
                        </svg>
                    </span>
                </div>
            </a>

            {{-- Đã đọc --}}
            <a
                href="{{ route('admin.contacts.index', ['status' => 'read']) }}"
                class="group rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:border-green-200 hover:shadow-md"
            >
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <p class="text-sm font-medium text-slate-500">
                            Đã đọc
                        </p>

                        <p class="mt-2 text-3xl font-bold text-green-600">
                            {{ $statistics['read'] }}
                        </p>
                    </div>

                    <span
                        class="flex h-12 w-12 items-center justify-center rounded-2xl bg-green-50 text-green-600 transition group-hover:bg-green-600 group-hover:text-white"
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
                                d="m4.5 12.75 6 6 9-13.5"
                            />
                        </svg>
                    </span>
                </div>
            </a>
        </div>

        {{-- Bộ lọc --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <form
                action="{{ route('admin.contacts.index') }}"
                method="GET"
                class="grid gap-4 lg:grid-cols-[1fr_220px_auto]"
            >
                {{-- Tìm kiếm --}}
                <div>
                    <label
                        for="search"
                        class="mb-2 block text-sm font-semibold text-slate-700"
                    >
                        Tìm kiếm
                    </label>

                    <div class="relative">
                        <svg
                            class="pointer-events-none absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1.8"
                                d="m21 21-4.35-4.35m2.1-5.4a7.5 7.5 0 1 1-15 0 7.5 7.5 0 0 1 15 0Z"
                            />
                        </svg>

                        <input
                            id="search"
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Tên, email, số điện thoại, tiêu đề..."
                            class="w-full rounded-xl border border-slate-300 bg-white py-3 pl-12 pr-4 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                        >
                    </div>
                </div>

                {{-- Trạng thái --}}
                <div>
                    <label
                        for="status"
                        class="mb-2 block text-sm font-semibold text-slate-700"
                    >
                        Trạng thái
                    </label>

                    <select
                        id="status"
                        name="status"
                        class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
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
                    </select>
                </div>

                {{-- Nút --}}
                <div class="flex items-end gap-2">
                    <button
                        type="submit"
                        class="inline-flex flex-1 cursor-pointer items-center justify-center gap-2 rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-blue-700 lg:flex-none"
                    >
                        Lọc
                    </button>

                    @if (request()->filled('search') || request()->filled('status'))
                        <a
                            href="{{ route('admin.contacts.index') }}"
                            class="inline-flex items-center justify-center rounded-xl border border-slate-300 bg-white px-5 py-3 text-sm font-semibold text-slate-600 transition hover:border-red-200 hover:bg-red-50 hover:text-red-600"
                        >
                            Xóa lọc
                        </a>
                    @endif
                </div>
            </form>
        </div>

        {{-- Danh sách --}}
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-[1050px] w-full">
                    <thead class="border-b border-slate-200 bg-slate-50">
                        <tr>
                            <th
                                class="px-5 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500"
                            >
                                Người gửi
                            </th>

                            <th
                                class="px-5 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500"
                            >
                                Chủ đề
                            </th>

                            <th
                                class="px-5 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500"
                            >
                                Nội dung
                            </th>

                            <th
                                class="px-5 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500"
                            >
                                Trạng thái
                            </th>

                            <th
                                class="px-5 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500"
                            >
                                Ngày gửi
                            </th>

                            <th
                                class="px-5 py-4 text-right text-xs font-bold uppercase tracking-wider text-slate-500"
                            >
                                Thao tác
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100">
                        @forelse ($contacts as $contact)
                            <tr
                                class="transition hover:bg-slate-50
                                    {{ $contact->status === 'unread'
                                        ? 'bg-blue-50/40'
                                        : 'bg-white' }}"
                            >
                                {{-- Người gửi --}}
                                <td class="px-5 py-4 align-top">
                                    <div class="flex items-start gap-3">
                                        <div
                                            class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl font-bold
                                                {{ $contact->status === 'unread'
                                                    ? 'bg-blue-600 text-white'
                                                    : 'bg-slate-100 text-slate-600' }}"
                                        >
                                            {{ mb_strtoupper(mb_substr($contact->name, 0, 1)) }}
                                        </div>

                                        <div class="min-w-0">
                                            <div class="flex items-center gap-2">
                                                @if ($contact->status === 'unread')
                                                    <span
                                                        class="h-2 w-2 shrink-0 rounded-full bg-blue-600"
                                                        title="Thư chưa đọc"
                                                    ></span>
                                                @endif

                                                <p
                                                    class="max-w-48 truncate text-sm font-bold text-slate-900"
                                                >
                                                    {{ $contact->name }}
                                                </p>
                                            </div>

                                            <p
                                                class="mt-1 max-w-52 truncate text-xs text-slate-500"
                                            >
                                                {{ $contact->email }}
                                            </p>

                                            @if ($contact->phone)
                                                <p class="mt-1 text-xs text-slate-400">
                                                    {{ $contact->phone }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                {{-- Chủ đề --}}
                                <td class="px-5 py-4 align-top">
                                    <p
                                        class="max-w-52 text-sm font-semibold leading-6 text-slate-800"
                                    >
                                        {{ $contact->subject }}
                                    </p>
                                </td>

                                {{-- Nội dung --}}
                                <td class="px-5 py-4 align-top">
                                    <p
                                        class="max-w-72 text-sm leading-6 text-slate-500"
                                    >
                                        {{ \Illuminate\Support\Str::limit(
                                            $contact->message,
                                            90
                                        ) }}
                                    </p>
                                </td>

                                {{-- Trạng thái --}}
                                <td class="px-5 py-4 align-top">
                                    @if ($contact->status === 'unread')
                                        <span
                                            class="inline-flex items-center gap-1.5 rounded-full bg-red-50 px-3 py-1.5 text-xs font-bold text-red-600"
                                        >
                                            <span
                                                class="h-1.5 w-1.5 rounded-full bg-red-500"
                                            ></span>

                                            Chưa đọc
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center gap-1.5 rounded-full bg-green-50 px-3 py-1.5 text-xs font-bold text-green-600"
                                        >
                                            <span
                                                class="h-1.5 w-1.5 rounded-full bg-green-500"
                                            ></span>

                                            Đã đọc
                                        </span>
                                    @endif
                                </td>

                                {{-- Ngày gửi --}}
                                <td class="px-5 py-4 align-top">
                                    <p class="text-sm font-semibold text-slate-700">
                                        {{ $contact->created_at->format('d/m/Y') }}
                                    </p>

                                    <p class="mt-1 text-xs text-slate-400">
                                        {{ $contact->created_at->format('H:i') }}
                                    </p>
                                </td>

                                {{-- Thao tác --}}
                                <td class="px-5 py-4 align-top">
                                    <div class="flex justify-end gap-2">
                                        <a
                                            href="{{ route('admin.contacts.show', $contact) }}"
                                            class="inline-flex items-center justify-center rounded-lg bg-blue-50 px-3 py-2 text-xs font-bold text-blue-600 transition hover:bg-blue-600 hover:text-white"
                                        >
                                            Xem
                                        </a>

                                        <form
                                            action="{{ route('admin.contacts.destroy', $contact) }}"
                                            method="POST"
                                            onsubmit="return confirm('Bạn có chắc muốn xóa thư liên hệ này?')"
                                        >
                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="inline-flex cursor-pointer items-center justify-center rounded-lg bg-red-50 px-3 py-2 text-xs font-bold text-red-600 transition hover:bg-red-600 hover:text-white"
                                            >
                                                Xóa
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td
                                    colspan="6"
                                    class="px-6 py-16 text-center"
                                >
                                    <div
                                        class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-slate-100 text-slate-400"
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
                                                stroke-width="1.6"
                                                d="M3 6.75A2.25 2.25 0 0 1 5.25 4.5h13.5A2.25 2.25 0 0 1 21 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 17.25V6.75Z"
                                            />

                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="1.6"
                                                d="m3.75 7.5 7.012 4.675a2.25 2.25 0 0 0 2.476 0L20.25 7.5"
                                            />
                                        </svg>
                                    </div>

                                    <h3
                                        class="mt-4 text-lg font-bold text-slate-900"
                                    >
                                        Không tìm thấy thư liên hệ
                                    </h3>

                                    <p class="mt-2 text-sm text-slate-500">
                                        Chưa có thư hoặc không có kết quả phù hợp
                                        với bộ lọc hiện tại.
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($contacts->hasPages())
                <div class="border-t border-slate-200 px-5 py-4">
                    {{ $contacts->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection