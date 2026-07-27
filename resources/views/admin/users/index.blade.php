<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Quản lý tài khoản | HomeStay</title>

    @vite([
        'resources/css/app.css',
        'resources/js/app.js',
    ])
</head>

<body class="min-h-screen bg-slate-50 text-slate-900 antialiased">

    @include('partials.navbar')

    @php
        $hasFilters =
            request()->filled('search')
            || request()->filled('booking_activity')
            || request()->filled('status');
    @endphp

    <main class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">

        <x-alert />

        {{-- Tiêu đề --}}
        <div class="mb-8">

            <a
                href="{{ route('admin.dashboard') }}"
                class="mb-4 block text-sm font-semibold text-blue-600 transition hover:text-blue-700"
            >
                ← Quay lại bảng điều khiển
            </a>

            <h1 class="text-3xl font-bold text-slate-900">
                Quản lý tài khoản
            </h1>

            <p class="mt-2 text-slate-500">
                Theo dõi, phân quyền và quản lý trạng thái tài khoản trong hệ thống.
            </p>

        </div>

        {{-- Thống kê --}}
        <section class="mb-8 grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">

            {{-- Tổng tài khoản --}}
            <div class="flex items-center gap-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">

                <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-blue-600">

                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        class="h-7 w-7"
                        aria-hidden="true"
                    >
                        <path
                            d="M3 19V18C3 15.7909 4.79086 14 7 14H11C13.2091 14 15 15.7909 15 18V19M15 11C16.6569 11 18 9.65685 18 8C18 6.34315 16.6569 5 15 5M21 19V18C21 15.7909 19.2091 14 17 14H16.5M12 8C12 9.65685 10.6569 11 9 11C7.34315 11 6 9.65685 6 8C6 6.34315 7.34315 5 9 5C10.6569 5 12 6.34315 12 8Z"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        />
                    </svg>

                </div>

                <div>

                    <p class="text-sm font-medium text-slate-500">
                        Tổng tài khoản
                    </p>

                    <p class="mt-1 text-2xl font-bold text-slate-900">
                        {{ number_format($statistics['total'], 0, ',', '.') }}
                    </p>

                </div>

            </div>

            {{-- Tài khoản mới tháng này --}}
            <div class="flex items-center gap-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">

                <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-xl bg-amber-50 text-amber-600">

                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" style="vertical-align: middle;fill: currentColor;overflow: hidden;" viewBox="0 0 1024 1024" version="1.1">
                        <path d="M895.2 750.7H792.5V648c0-19.3-15.7-35-35-35s-35 15.7-35 35v102.7H619.7c-19.3 0-35 15.7-35 35s15.7 35 35 35h102.7v102.7c0 19.3 15.7 35 35 35s35-15.7 35-35V820.7h102.7c19.3 0 35-15.7 35-35s-15.6-35-34.9-35z"/>
                        <path d="M498.7 863.1H165.2v-5.7C165.5 666.3 321.1 511 512 511c72.4 0 141.7 22.1 200.5 63.8 15.8 11.2 37.6 7.5 48.8-8.3 11.2-15.8 7.5-37.6-8.3-48.8-37.5-26.6-78.5-46.6-121.8-59.4 13.1-8.4 25.4-18.3 36.7-29.6 41.4-41.4 64.2-96.5 64.2-155.1S709.4 159.9 668 118.4C626.5 77 571.5 54.2 512.9 54.2c-58.6 0-113.7 22.8-155.1 64.2-41.4 41.4-64.2 96.5-64.2 155.1s22.8 113.7 64.2 155.1c11.2 11.2 23.3 20.9 36.2 29.3-14.9 4.4-29.6 9.7-44.1 15.8-49.6 21-94.1 50.9-132.4 89.1-38.3 38.2-68.3 82.7-89.4 132.2-21.8 51.3-32.9 105.8-33 162.1V898c0 19.3 15.7 35 35 35h368.5c19.3 0 35-15.7 35-35s-15.6-34.9-34.9-34.9z m14.2-738.9c82.4 0 149.4 67 149.4 149.4S595.3 423 512.9 423s-149.4-67-149.4-149.4 67-149.4 149.4-149.4z"/>
                    </svg>

                </div>

                <div>

                    <p class="text-sm font-medium text-slate-500">
                        Tài khoản mới tuần này
                    </p>

                    <p class="mt-1 text-2xl font-bold text-slate-900">
                        {{ number_format(
                            $statistics['new_this_week'],
                            0,
                            ',',
                            '.'
                        ) }}
                    </p>

                </div>

            </div>

            {{-- Đang hoạt động --}}
            <div class="flex items-center gap-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">

                <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">

                    <svg
                        class="h-7 w-7"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                        />
                    </svg>

                </div>

                <div>

                    <p class="text-sm font-medium text-slate-500">
                        Đang hoạt động
                    </p>

                    <p class="mt-1 text-2xl font-bold text-slate-900">
                        {{ number_format($statistics['active'], 0, ',', '.') }}
                    </p>

                </div>

            </div>

            {{-- Tạm khóa --}}
            <div class="flex items-center gap-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">

                <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-xl bg-rose-50 text-rose-600">

                    <svg
                        class="h-7 w-7 text-red-600"
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                    >
                        <circle
                            cx="12"
                            cy="12"
                            r="9"
                            stroke-width="2"
                        />

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M6.5 6.5l11 11"
                        />
                    </svg>

                </div>

                <div>

                    <p class="text-sm font-medium text-slate-500">
                        Tạm khóa
                    </p>

                    <p class="mt-1 text-2xl font-bold text-slate-900">
                        {{ number_format($statistics['inactive'], 0, ',', '.') }}
                    </p>

                </div>

            </div>

        </section>

        {{-- Danh sách tài khoản --}}
        <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

            {{-- Bộ lọc --}}
            <div class="border-b border-slate-200 bg-slate-50/70 p-5">

                <form
                    method="GET"
                    action="{{ route('admin.users.index') }}"
                    class="grid gap-4 lg:grid-cols-12"
                >

                    {{-- Tìm kiếm --}}
                    <div class="lg:col-span-6">

                        <label
                            for="search"
                            class="mb-2 block text-sm font-semibold text-slate-700"
                        >
                            Tìm kiếm
                        </label>

                        <div class="relative">

                            <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">

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
                                placeholder="Nhập tên hoặc email..."
                                class="w-full rounded-xl border border-slate-300 bg-white py-3 pl-11 pr-4 text-sm text-slate-900 shadow-sm outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                                onsearch="this.form.submit()"
                                oninput="if(this.value === '') this.form.submit()"
                            >

                        </div>

                    </div>

                    {{-- Hoạt động đặt phòng --}}
                    <div class="lg:col-span-2">

                        <label
                            for="booking_activity"
                            class="mb-2 block text-sm font-semibold text-slate-700"
                        >
                            Đặt phòng
                        </label>

                        <select
                            id="booking_activity"
                            name="booking_activity"
                            class="w-full cursor-pointer rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                        >
                            <option value="">
                                Tất cả
                            </option>

                            <option
                                value="has_booking"
                                @selected(request('booking_activity') === 'has_booking')
                            >
                                Đã từng đặt phòng
                            </option>

                            <option
                                value="no_booking"
                                @selected(request('booking_activity') === 'no_booking')
                            >
                                Chưa từng đặt phòng
                            </option>

                        </select>

                    </div>

                    {{-- Trạng thái --}}
                    <div class="lg:col-span-2">

                        <label
                            for="status"
                            class="mb-2 block text-sm font-semibold text-slate-700"
                        >
                            Trạng thái
                        </label>

                        <select
                            id="status"
                            name="status"
                            class="w-full cursor-pointer rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                        >
                            <option value="">
                                Tất cả
                            </option>

                            <option
                                value="active"
                                @selected(request('status') === 'active')
                            >
                                Hoạt động
                            </option>

                            <option
                                value="inactive"
                                @selected(request('status') === 'inactive')
                            >
                                Tạm khóa
                            </option>
                        </select>

                    </div>

                    {{-- Xóa bộ lọc --}}
                    <div class="flex items-end lg:col-span-1">

                        @if ($hasFilters)

                            <a
                                href="{{ route('admin.users.index') }}"
                                title="Xóa bộ lọc"
                                class="inline-flex w-full items-center justify-center rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm font-semibold text-slate-600 shadow-sm transition hover:bg-slate-50"
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
                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"
                                    />
                                </svg>
                            </a>

                        @else

                            <button
                                type="button"
                                disabled
                                title="Chưa sử dụng bộ lọc"
                                class="inline-flex w-full cursor-not-allowed items-center justify-center rounded-xl border border-slate-200 bg-slate-100 px-4 py-3 text-sm font-semibold text-slate-400"
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
                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"
                                    />
                                </svg>
                            </button>

                        @endif

                    </div>

                    {{-- Nút lọc --}}
                    <div class="flex items-end lg:col-span-1">

                        <button
                            type="submit"
                            class="inline-flex w-full cursor-pointer items-center justify-center rounded-xl bg-blue-600 px-4 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700"
                        >
                            Lọc
                        </button>

                    </div>

                </form>

            </div>

            @if ($users->count())

                {{-- Bảng tài khoản --}}
                <div class="overflow-x-auto">

                    <table class="w-full min-w-[1150px] border-collapse text-left">

                        <thead>

                            <tr class="border-b border-slate-200 bg-slate-50/70 text-xs font-bold uppercase tracking-wider text-slate-500">

                                <th class="px-6 py-4">
                                    Tài khoản
                                </th>

                                <th class="px-6 py-4">
                                    Liên hệ
                                </th>

                                <th class="px-6 py-4">
                                    Dữ liệu liên quan
                                </th>

                                <th class="px-6 py-4">
                                    Trạng thái
                                </th>

                                <th class="px-6 py-4">
                                    Đã thanh toán
                                </th>

                                <th class="px-6 py-4 text-right">
                                    Thao tác
                                </th>

                            </tr>

                        </thead>

                        <tbody class="divide-y divide-slate-200 text-sm">

                            @foreach ($users as $user)

                                @php
                                    $nameParts = preg_split(
                                        '/\s+/',
                                        trim($user->name)
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

                                    $isCurrentUser =
                                        (int) auth()->id()
                                        === (int) $user->id;

                                    $avatarUrl = null;

                                    if (!empty($user->avatar)) {
                                        $avatarUrl =
                                            \Illuminate\Support\Str::startsWith(
                                                $user->avatar,
                                                ['http://', 'https://']
                                            )
                                                ? $user->avatar
                                                : asset(
                                                    'storage/'
                                                    . ltrim($user->avatar, '/')
                                                );
                                    }
                                @endphp

                                <tr class="transition hover:bg-slate-50/80">

                                    {{-- Tài khoản --}}
                                    <td class="whitespace-nowrap px-6 py-5">

                                        <div class="flex items-center gap-3">

                                            @if ($avatarUrl)

                                                <img
                                                    src="{{ $avatarUrl }}"
                                                    alt="{{ $user->name }}"
                                                    class="h-11 w-11 shrink-0 rounded-full border border-slate-200 object-cover"
                                                >

                                            @else

                                                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-blue-100 text-sm font-bold text-blue-700">
                                                    {{ $avatarText ?: '?' }}
                                                </div>

                                            @endif

                                            <div class="min-w-0">

                                                <div class="flex items-center gap-2">

                                                    <p
                                                        class="max-w-[170px] truncate font-semibold text-slate-900"
                                                        title="{{ $user->name }}"
                                                    >
                                                        {{ $user->name }}
                                                    </p>

                                                    @if ($isCurrentUser)

                                                        <span class="rounded-full bg-blue-50 px-2 py-0.5 text-[10px] font-bold uppercase tracking-wide text-blue-600">
                                                            Bạn
                                                        </span>

                                                    @endif

                                                </div>

                                                <p class="max-w-[170px] truncate mt-1 text-xs text-slate-400">
                                                    {{ $user->email }}
                                                </p>

                                            </div>

                                        </div>

                                    </td>

                                    {{-- Liên hệ --}}
                                    <td class="px-6 py-5">

                                        <p class="font-medium text-slate-700">
                                            {{ $user->phone ?: 'Chưa cập nhật' }}
                                        </p>

                                        <p
                                            class="mt-1 max-w-[190px] truncate text-xs text-slate-400"
                                            title="{{ $user->address }}"
                                        >
                                            {{ $user->address ?: 'Chưa có địa chỉ' }}
                                        </p>

                                    </td>

                                    {{-- Dữ liệu liên quan --}}
                                    <td class="px-6 py-5">

                                        <div class="flex flex-wrap gap-2">

                                            <span
                                                class="rounded-lg bg-blue-50 px-2.5 py-1.5 text-xs font-semibold text-blue-700"
                                                title="Số đơn đặt phòng"
                                            >
                                                {{ $user->bookings_count }} đơn
                                            </span>

                                            <span
                                                class="rounded-lg bg-amber-50 px-2.5 py-1.5 text-xs font-semibold text-amber-700"
                                                title="Số đánh giá"
                                            >
                                                {{ $user->reviews_count }} đánh giá
                                            </span>

                                        </div>

                                    </td>

                                    {{-- Trạng thái --}}
                                    <td class="whitespace-nowrap px-6 py-5">

                                        @if ($user->status === 'active')

                                            <span class="inline-flex items-center gap-2 rounded-full border border-emerald-100 bg-emerald-50 px-3 py-1.5 text-xs font-semibold text-emerald-700">

                                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>

                                                Hoạt động
                                            </span>

                                        @else

                                            <span class="inline-flex items-center gap-2 rounded-full border border-red-200 bg-red-50 px-3 py-1.5 text-xs font-semibold text-red-700">

                                                <span class="h-1.5 w-1.5 rounded-full bg-red-500"></span>

                                                Tạm khóa
                                            </span>

                                        @endif

                                    </td>

                                    {{-- Thanh toán --}}
                                    <td class="whitespace-nowrap px-6 py-5">

                                        @php
                                            $totalPaid = (int) ($user->total_paid ?? 0);
                                            $successfulPayments =
                                                (int) ($user->successful_payments_count ?? 0);
                                        @endphp

                                        @if ($totalPaid > 0)

                                            <p class="font-bold text-emerald-600">
                                                {{ number_format($totalPaid, 0, ',', '.') }} ₫
                                            </p>

                                            <p class="mt-1 text-xs text-slate-500">
                                                {{ number_format($successfulPayments, 0, ',', '.') }}
                                                giao dịch thành công
                                            </p>

                                        @else

                                            <p class="font-semibold text-slate-500">
                                                0 ₫
                                            </p>

                                            <p class="mt-1 text-xs text-slate-400">
                                                Chưa có thanh toán
                                            </p>

                                        @endif

                                    </td>

                                    {{-- Thao tác --}}
                                    <td class="whitespace-nowrap px-6 py-5 text-right">

                                        <details class="user-action-menu relative inline-block text-left">

                                            <summary class="flex h-9 w-9 cursor-pointer list-none items-center justify-center rounded-lg border border-slate-300 bg-white text-lg font-bold text-slate-500 shadow-sm transition hover:bg-slate-50 hover:text-slate-700">
                                                ⋮
                                            </summary>

                                            <div class="absolute right-0 z-50 mt-2 w-60 overflow-hidden rounded-xl border border-slate-200 bg-white text-left shadow-xl">

                                                {{-- Xem chi tiết --}}
                                                <a
                                                    href="{{ route('admin.users.show', $user) }}"
                                                    class="flex items-center gap-3 px-4 py-3 text-sm font-medium text-slate-700 transition hover:bg-slate-100"
                                                >
                                                    <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-100">
                                                        👁
                                                    </span>

                                                    Xem chi tiết
                                                </a>

                                                <div class="border-t border-slate-100"></div>

                                                {{-- Khóa hoặc mở khóa --}}
                                                @if (!$isCurrentUser)

                                                    <form
                                                        method="POST"
                                                        action="{{ route('admin.users.update-status', $user) }}"
                                                    >
                                                        @csrf
                                                        @method('PATCH')

                                                        <input
                                                            type="hidden"
                                                            name="status"
                                                            value="{{ $user->status === 'active' ? 'inactive' : 'active' }}"
                                                        >

                                                        <button
                                                            type="submit"
                                                            onclick="return confirm(
                                                                '{{ $user->status === 'active'
                                                                    ? 'Bạn có chắc muốn khóa tài khoản này không?'
                                                                    : 'Bạn có chắc muốn mở khóa tài khoản này không?' }}'
                                                            )"
                                                            class="flex w-full cursor-pointer items-center gap-3 px-4 py-3 text-left text-sm font-medium transition
                                                                {{ $user->status === 'active'
                                                                    ? 'text-red-700 hover:bg-red-50'
                                                                    : 'text-emerald-700 hover:bg-emerald-50' }}"
                                                        >
                                                            <span
                                                                class="flex h-8 w-8 items-center justify-center rounded-lg
                                                                    {{ $user->status === 'active'
                                                                        ? 'bg-red-50'
                                                                        : 'bg-emerald-50' }}"
                                                            >
                                                                {{ $user->status === 'active' ? '🔒' : '🗝️' }}
                                                            </span>

                                                            {{ $user->status === 'active'
                                                                ? 'Khóa tài khoản'
                                                                : 'Mở khóa tài khoản' }}
                                                        </button>
                                                    </form>

                                                @else

                                                    <div class="flex items-center gap-3 px-4 py-3 text-sm font-medium text-blue-600">
                                                        <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-blue-50">
                                                            ✓
                                                        </span>

                                                        Tài khoản hiện tại
                                                    </div>

                                                @endif

                                            </div>

                                        </details>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

                {{-- Phân trang --}}
                <div class="border-t border-slate-200 px-6 py-4">
                    {{ $users->links() }}
                </div>

            @else

                {{-- Không có dữ liệu --}}
                <div class="px-6 py-20 text-center">

                    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-slate-100 text-slate-400">

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            class="h-7 w-7"
                            aria-hidden="true"
                        >
                            <path
                                d="M3 19V18C3 15.7909 4.79086 14 7 14H11C13.2091 14 15 15.7909 15 18V19M15 11C16.6569 11 18 9.65685 18 8C18 6.34315 16.6569 5 15 5M21 19V18C21 15.7909 19.2091 14 17 14H16.5M12 8C12 9.65685 10.6569 11 9 11C7.34315 11 6 9.65685 6 8C6 6.34315 7.34315 5 9 5C10.6569 5 12 6.34315 12 8Z"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            />
                        </svg>

                    </div>

                    <h2 class="mt-5 text-lg font-bold text-slate-900">

                        @if ($hasFilters)
                            Không tìm thấy tài khoản phù hợp
                        @else
                            Chưa có tài khoản
                        @endif

                    </h2>

                    <p class="mx-auto mt-2 text-sm leading-6 text-slate-500">

                        @if ($hasFilters)
                            Không có tài khoản nào phù hợp với nội dung tìm kiếm hoặc bộ lọc hiện tại.
                        @else
                            Hệ thống hiện chưa có tài khoản nào.
                        @endif

                    </p>

                    @if ($hasFilters)

                        <a
                            href="{{ route('admin.users.index') }}"
                            class="mt-5 inline-flex items-center justify-center rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-blue-700"
                        >
                            Xóa bộ lọc
                        </a>

                    @endif

                </div>

            @endif

        </section>

    </main>

    <script>
        const userMenus = document.querySelectorAll(
            '.user-action-menu'
        );

        userMenus.forEach((menu) => {
            menu.addEventListener('toggle', () => {
                if (!menu.open) {
                    return;
                }

                userMenus.forEach((otherMenu) => {
                    if (otherMenu !== menu) {
                        otherMenu.removeAttribute('open');
                    }
                });
            });
        });

        document.addEventListener('click', (event) => {
            userMenus.forEach((menu) => {
                if (
                    menu.open
                    && !menu.contains(event.target)
                ) {
                    menu.removeAttribute('open');
                }
            });
        });
    </script>

</body>

</html>