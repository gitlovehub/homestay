<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Quản lý đánh giá | HomeStay</title>

    @vite([
        'resources/css/app.css',
        'resources/js/app.js',
    ])
</head>

<body class="min-h-screen bg-slate-50 text-slate-900 antialiased">

    @include('partials.navbar')

    <main class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">

        <x-alert />

        <div class="mb-8">

            <a
                href="{{ route('admin.dashboard') }}"
                class="mb-4 block text-sm font-semibold text-blue-600 transition hover:text-blue-700"
            >
                ← Quay lại bảng điều khiển
            </a>

            <h1 class="text-3xl font-bold text-slate-900">
                Quản lý đánh giá
            </h1>

            <p class="mt-2 text-slate-500">
                Kiểm duyệt, theo dõi và quản lý đánh giá của khách hàng sau khi lưu trú.
            </p>

        </div>

        {{-- Thống kê --}}
        <section class="mb-8 grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">

            {{-- Tổng đánh giá --}}
            <div class="flex items-center gap-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">

                <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
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
                            d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"
                        />
                    </svg>
                </div>

                <div>
                    <p class="text-sm font-medium text-slate-500">
                        Tổng đánh giá
                    </p>

                    <p class="mt-1 text-2xl font-bold text-slate-900">
                        {{ number_format($statistics['total'], 0, ',', '.') }}
                    </p>
                </div>

            </div>

            {{-- Điểm trung bình --}}
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
                            d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118L3.077 10.1c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"
                        />
                    </svg>
                </div>

                <div>
                    <p class="text-sm font-medium text-slate-500">
                        Điểm trung bình
                    </p>

                    <p class="mt-1 text-2xl font-bold text-slate-900">
                        {{ number_format($statistics['average_rating'], 1) }}
                        <span class="text-base text-slate-500">/ 5</span>
                    </p>
                </div>

            </div>

            {{-- Chờ duyệt --}}
            <div class="flex items-center gap-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">

                <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-xl bg-amber-50 text-amber-600">
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
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                        />
                    </svg>
                </div>

                <div>
                    <p class="text-sm font-medium text-slate-500">
                        Chờ duyệt
                    </p>

                    <p class="mt-1 text-2xl font-bold text-slate-900">
                        {{ number_format($statistics['pending'], 0, ',', '.') }}
                    </p>
                </div>

            </div>

            {{-- Đã ẩn --}}
            <div class="flex items-center gap-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">

                <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-xl bg-rose-50 text-rose-600">
                    <svg
                        class="h-7 w-7"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                        stroke-width="1.5"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88"
                        />
                    </svg>
                </div>

                <div>
                    <p class="text-sm font-medium text-slate-500">
                        Đã ẩn
                    </p>

                    <p class="mt-1 text-2xl font-bold text-slate-900">
                        {{ number_format($statistics['hidden'], 0, ',', '.') }}
                    </p>
                </div>

            </div>

        </section>

        {{-- Danh sách đánh giá --}}
        <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

            {{-- Bộ lọc --}}
            <div class="border-b border-slate-200 bg-slate-50/70 p-5">

                <form
                    method="GET"
                    action="{{ route('admin.reviews.index') }}"
                    class="grid gap-4 lg:grid-cols-12"
                >

                    {{-- Search --}}
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
                                placeholder="Tìm tiêu đề, nội dung, khách hàng, Homestay hoặc mã đơn..."
                                class="w-full rounded-xl border border-slate-300 bg-white py-3 pl-11 pr-4 text-sm text-slate-900 shadow-sm outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                                onsearch="this.form.submit()"
                                oninput="if(this.value === '') this.form.submit()"
                            >

                        </div>

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
                            <option value="">Tất cả</option>

                            <option
                                value="pending"
                                @selected(request('status') === 'pending')
                            >
                                Chờ duyệt
                            </option>

                            <option
                                value="approved"
                                @selected(request('status') === 'approved')
                            >
                                Đã duyệt
                            </option>

                            <option
                                value="hidden"
                                @selected(request('status') === 'hidden')
                            >
                                Đã ẩn
                            </option>
                        </select>

                    </div>

                    {{-- Rating --}}
                    <div class="lg:col-span-2">

                        <label
                            for="rating"
                            class="mb-2 block text-sm font-semibold text-slate-700"
                        >
                            Số sao
                        </label>

                        <select
                            id="rating"
                            name="rating"
                            class="w-full cursor-pointer rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                        >
                            <option value="">Tất cả</option>

                            @for ($star = 5; $star >= 1; $star--)
                                <option
                                    value="{{ $star }}"
                                    @selected((string) request('rating') === (string) $star)
                                >
                                    {{ $star }} sao
                                </option>
                            @endfor
                        </select>

                    </div>

                    {{-- Reset --}}
                    <div class="flex items-end lg:col-span-1">

                        @if (request()->hasAny(['search', 'status', 'rating']))
                            <a
                                href="{{ route('admin.reviews.index') }}"
                                class="inline-flex w-full items-center justify-center rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm font-semibold text-slate-600 shadow-sm transition hover:bg-slate-50"
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
                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"
                                    />
                                </svg>
                            </a>
                        @else
                            <button
                                type="button"
                                disabled
                                class="inline-flex w-full cursor-not-allowed items-center justify-center rounded-xl border border-slate-200 bg-slate-100 px-4 py-3 text-sm font-semibold text-slate-400"
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

            @if ($reviews->count())

                {{-- Table --}}
                <div class="overflow-x-auto">

                    <table class="w-full border-collapse text-left min-h-90">

                        <thead>

                            <tr class="border-b border-slate-200 bg-slate-50/70 text-xs font-bold uppercase tracking-wider text-slate-500">

                                <th class="px-6 py-4">
                                    Khách hàng
                                </th>

                                <th class="px-6 py-4">
                                    Đánh giá
                                </th>

                                <th class="px-6 py-4">
                                    Nội dung
                                </th>

                                <th class="px-6 py-4">
                                    Homestay
                                </th>

                                <th class="px-6 py-4">
                                    Trạng thái
                                </th>

                                <th class="px-6 py-4">
                                    Thời gian
                                </th>

                            </tr>

                        </thead>

                        <tbody class="divide-y divide-slate-200 text-sm">

                            @foreach ($reviews as $review)

                                @php
                                    $userName = $review->user?->name ?? 'Không xác định';

                                    $nameParts = preg_split('/\s+/', trim($userName));

                                    $avatarText = collect($nameParts)
                                        ->filter()
                                        ->take(2)
                                        ->map(fn ($part) => mb_strtoupper(mb_substr($part, 0, 1)))
                                        ->implode('');
                                @endphp

                                <tr class="transition hover:bg-slate-50/80">

                                    {{-- User --}}
                                    <td class="whitespace-nowrap px-6 py-5">

                                        <div class="flex items-center gap-3">

                                            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-blue-100 text-sm font-semibold text-blue-700">
                                                {{ $avatarText ?: '?' }}
                                            </div>

                                            <div class="min-w-0">

                                                <p class="max-w-[160px] truncate font-semibold text-slate-900">
                                                    {{ $userName }}
                                                </p>

                                                <p class="mt-0.5 max-w-[160px] truncate text-xs text-slate-500">
                                                    {{ $review->user?->email ?? 'Không có email' }}
                                                </p>

                                            </div>

                                        </div>

                                    </td>

                                    {{-- Rating --}}
                                    <td class="whitespace-nowrap px-6 py-5">

                                        <div class="flex items-center gap-2">

                                            <div
                                                class="flex items-center"
                                                aria-label="{{ $review->rating }} trên 5 sao"
                                            >
                                                @for ($star = 1; $star <= 5; $star++)
                                                    <span
                                                        class="text-lg leading-none {{ $star <= $review->rating
                                                            ? 'text-amber-400'
                                                            : 'text-slate-200' }}"
                                                    >
                                                        ★
                                                    </span>
                                                @endfor
                                            </div>

                                            <span class="text-xs font-semibold text-slate-500">
                                                {{ number_format($review->rating, 1) }}
                                            </span>

                                        </div>

                                        <p class="mt-1 text-xs text-slate-400">
                                            Lần {{ $review->review_number }}
                                        </p>

                                    </td>

                                    {{-- Content --}}
                                    <td class="px-6 py-5">

                                        <div class="max-w-sm">

                                            <p class="truncate font-semibold text-slate-900">
                                                {{ $review->title ?: 'Không có tiêu đề' }}
                                            </p>

                                            <p class="mt-1 line-clamp-2 text-xs leading-5 text-slate-500">
                                                {{ $review->content ?: 'Khách hàng không nhập nội dung đánh giá.' }}
                                            </p>

                                        </div>

                                    </td>

                                    {{-- Homestay --}}
                                    <td class="px-6 py-5">

                                        <div class="max-w-[180px]">

                                            <p class="truncate font-semibold text-slate-900">
                                                {{ $review->homestay?->name ?? 'Không xác định' }}
                                            </p>

                                            @if ($review->booking?->booking_code)
                                                <p class="mt-1 truncate text-xs text-slate-500">
                                                    Mã đơn:
                                                    <span class="font-semibold text-blue-600">
                                                        {{ $review->booking->booking_code }}
                                                    </span>
                                                </p>
                                            @endif

                                        </div>

                                    </td>

                                    {{-- Status --}}
                                    <td class="whitespace-nowrap px-6 py-5">

                                        @switch($review->status)

                                            @case('pending')
                                                <span class="inline-flex items-center gap-2 rounded-full border border-amber-100 bg-amber-50 px-3 py-1.5 text-xs font-semibold text-amber-700">
                                                    <span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span>
                                                    Chờ duyệt
                                                </span>
                                                @break

                                            @case('approved')
                                                <span class="inline-flex items-center gap-2 rounded-full border border-emerald-100 bg-emerald-50 px-3 py-1.5 text-xs font-semibold text-emerald-700">
                                                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                                    Đã duyệt
                                                </span>
                                                @break

                                            @case('hidden')
                                                <span class="inline-flex items-center gap-2 rounded-full border border-red-200 bg-red-50 px-3 py-1.5 text-xs font-semibold text-red-700">
                                                    <span class="h-1.5 w-1.5 rounded-full bg-red-500"></span>
                                                    Đã ẩn
                                                </span>
                                                @break

                                            @default
                                                <span class="inline-flex rounded-full bg-rose-50 px-3 py-1.5 text-xs font-semibold text-rose-600">
                                                    Không xác định
                                                </span>

                                        @endswitch

                                    </td>

                                    {{-- Time --}}
                                    <td class="whitespace-nowrap px-6 py-5">

                                        <p class="font-semibold text-slate-800">
                                            {{ $review->created_at->format('d/m/Y') }}
                                        </p>

                                        <p class="mt-1 text-xs text-slate-500">
                                            {{ $review->created_at->format('H:i') }}
                                        </p>

                                    </td>

                                    {{-- Actions --}}
                                    <td class="whitespace-nowrap px-6 py-5 text-right">

                                        <details class="review-action-menu relative inline-block text-left">

                                            <summary class="flex h-9 w-9 cursor-pointer list-none items-center justify-center rounded-lg border border-slate-300 bg-white text-lg font-bold text-slate-500 shadow-sm transition hover:bg-slate-50 hover:text-slate-700">
                                                ⋮
                                            </summary>

                                            <div class="absolute right-0 z-40 mt-2 w-48 overflow-hidden rounded-xl border border-slate-200 bg-white text-left shadow-xl">

                                                <a
                                                    href="{{ route('admin.reviews.show', $review) }}"
                                                    class="flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-100"
                                                >
                                                    <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-slate-100">
                                                        👁
                                                    </span>
                                                    Xem chi tiết
                                                </a>

                                                @if ($review->status === 'pending')

                                                    {{-- Duyệt đánh giá --}}
                                                    <form
                                                        method="POST"
                                                        action="{{ route('admin.reviews.update-status', $review) }}"
                                                    >
                                                        @csrf
                                                        @method('PATCH')

                                                        <input
                                                            type="hidden"
                                                            name="status"
                                                            value="approved"
                                                        >

                                                        <button
                                                            type="submit"
                                                            onclick="return confirm('Bạn có chắc muốn duyệt đánh giá này không?')"
                                                            class="flex w-full cursor-pointer items-center gap-2 px-4 py-2.5 text-left text-sm font-medium text-emerald-700 transition hover:bg-emerald-50"
                                                        >
                                                            <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-emerald-50">
                                                                ✓
                                                            </span>

                                                            Duyệt đánh giá
                                                        </button>
                                                    </form>

                                                    {{-- Ẩn đánh giá --}}
                                                    <form
                                                        method="POST"
                                                        action="{{ route('admin.reviews.update-status', $review) }}"
                                                    >
                                                        @csrf
                                                        @method('PATCH')

                                                        <input
                                                            type="hidden"
                                                            name="status"
                                                            value="hidden"
                                                        >

                                                        <button
                                                            type="submit"
                                                            onclick="return confirm('Bạn có chắc muốn ẩn đánh giá này không?')"
                                                            class="flex w-full cursor-pointer items-center gap-2 px-4 py-2.5 text-left text-sm font-medium text-red-700 transition hover:bg-red-50"
                                                        >
                                                            <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-red-50">
                                                                🚫
                                                            </span>

                                                            Ẩn đánh giá
                                                        </button>
                                                    </form>

                                                @elseif ($review->status === 'approved')

                                                    {{-- Ẩn đánh giá --}}
                                                    <form
                                                        method="POST"
                                                        action="{{ route('admin.reviews.update-status', $review) }}"
                                                    >
                                                        @csrf
                                                        @method('PATCH')

                                                        <input
                                                            type="hidden"
                                                            name="status"
                                                            value="hidden"
                                                        >

                                                        <button
                                                            type="submit"
                                                            onclick="return confirm('Bạn có chắc muốn ẩn đánh giá này không?')"
                                                            class="flex w-full cursor-pointer items-center gap-2 px-4 py-2.5 text-left text-sm font-medium text-red-700 transition hover:bg-red-50"
                                                        >
                                                            <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-red-50">
                                                                🚫
                                                            </span>

                                                            Ẩn đánh giá
                                                        </button>
                                                    </form>

                                                @elseif ($review->status === 'hidden')

                                                    {{-- Hiển thị lại --}}
                                                    <form
                                                        method="POST"
                                                        action="{{ route('admin.reviews.update-status', $review) }}"
                                                    >
                                                        @csrf
                                                        @method('PATCH')

                                                        <input
                                                            type="hidden"
                                                            name="status"
                                                            value="approved"
                                                        >

                                                        <button
                                                            type="submit"
                                                            onclick="return confirm('Bạn có chắc muốn hiển thị lại đánh giá này không?')"
                                                            class="flex w-full cursor-pointer items-center gap-2 px-4 py-2.5 text-left text-sm font-medium text-emerald-700 transition hover:bg-emerald-50"
                                                        >
                                                            <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-emerald-50">
                                                                ✓
                                                            </span>

                                                            Hiển thị lại
                                                        </button>
                                                    </form>

                                                @endif

                                            </div>

                                        </details>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

                {{-- Pagination --}}
                <div class="border-t border-slate-200 px-6 py-4">
                    {{ $reviews->links() }}
                </div>

            @else

                {{-- Empty --}}
                <div class="px-6 py-20 text-center">

                    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-slate-100 text-3xl text-slate-400">
                        ☆
                    </div>

                    <h2 class="mt-5 text-lg font-bold text-slate-900">
                        Chưa có đánh giá phù hợp
                    </h2>

                    <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-slate-500">
                        Không tìm thấy đánh giá phù hợp với nội dung tìm kiếm hoặc bộ lọc hiện tại.
                    </p>

                    @if (request()->hasAny(['search', 'status', 'rating']))
                        <a
                            href="{{ route('admin.reviews.index') }}"
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
        const reviewMenus = document.querySelectorAll('.review-action-menu');

        reviewMenus.forEach((menu) => {
            menu.addEventListener('toggle', () => {
                if (!menu.open) {
                    return;
                }

                reviewMenus.forEach((otherMenu) => {
                    if (otherMenu !== menu) {
                        otherMenu.removeAttribute('open');
                    }
                });
            });
        });

        document.addEventListener('click', (event) => {
            reviewMenus.forEach((menu) => {
                if (menu.open && !menu.contains(event.target)) {
                    menu.removeAttribute('open');
                }
            });
        });
    </script>

</body>

</html>