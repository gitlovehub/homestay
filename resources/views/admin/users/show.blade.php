<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi tiết tài khoản | HomeStay</title>
    @vite([
        'resources/css/app.css',
        'resources/js/app.js',
    ])
</head>
<body class="min-h-screen bg-slate-50 text-slate-900 antialiased">
    @include('partials.navbar')

    @php
        $avatarUrl = null;
        if (!empty($user->avatar)) {
            $avatarUrl = \Illuminate\Support\Str::startsWith($user->avatar, ['http://', 'https://'])
                ? $user->avatar
                : asset('storage/' . ltrim($user->avatar, '/'));
        }

        $nameParts = preg_split('/\s+/u', trim($user->name));
        $firstNamePart = $nameParts[0] ?? '';
        $lastNamePart = $nameParts[count($nameParts) - 1] ?? '';
        $avatarText = mb_strtoupper(mb_substr($firstNamePart, 0, 1) . mb_substr($lastNamePart, 0, 1));
        $isCurrentUser = (int) auth()->id() === (int) $user->id;

        $bookingStatusLabels = [
            'pending'    => 'Chờ xác nhận',
            'confirmed'  => 'Đã xác nhận',
            'checked_in' => 'Đã nhận phòng',
            'completed'  => 'Hoàn thành',
            'cancelled'  => 'Đã hủy',
        ];
        $bookingStatusClasses = [
            'pending'    => 'border-amber-200 bg-amber-50 text-amber-700',
            'confirmed'  => 'border-blue-200 bg-blue-50 text-blue-700',
            'checked_in' => 'border-violet-200 bg-violet-50 text-violet-700',
            'completed'  => 'border-emerald-200 bg-emerald-50 text-emerald-700',
            'cancelled'  => 'border-red-200 bg-red-50 text-red-700',
        ];

        $paymentStatusLabels = [
            'unpaid'   => 'Chưa thanh toán',
            'pending'  => 'Đang xử lý',
            'paid'     => 'Đã thanh toán',
            'refunded' => 'Đã hoàn tiền',
            'failed'   => 'Thất bại',
        ];
        $paymentStatusClasses = [
            'unpaid'   => 'border-slate-200 bg-slate-100 text-slate-600',
            'pending'  => 'border-amber-200 bg-amber-50 text-amber-700',
            'paid'     => 'border-emerald-200 bg-emerald-50 text-emerald-700',
            'refunded' => 'border-violet-200 bg-violet-50 text-violet-700',
            'failed'   => 'border-red-200 bg-red-50 text-red-700',
        ];

        $paymentMethodLabels = [
            'cash'          => 'Tiền mặt',
            'bank_transfer' => 'Chuyển khoản ngân hàng',
            'vnpay'         => 'VNPay',
            'momo'          => 'MoMo',
        ];
    @endphp

    <main class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <x-alert />

        {{-- Header --}}
        <div class="mb-8 flex flex-col gap-5 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <a
                    href="{{ route('admin.users.index') }}"
                    class="mb-4 block text-sm font-semibold text-blue-600 transition hover:text-blue-700"
                >
                    ← Quay lại danh sách tài khoản
                </a>
                <h1 class="text-3xl font-bold tracking-tight text-slate-900">Hồ sơ khách hàng</h1>
                <p class="mt-2 text-sm text-slate-500">Quản lý thông tin, booking và thanh toán của tài khoản.</p>
            </div>

            <a href="#booking-history"
               class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-blue-300 hover:bg-blue-50 hover:text-blue-700">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M8 7V3m8 4V3M5 11h14M6 21h12a2 2 0 002-2V7a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                Lịch sử đặt phòng
            </a>
        </div>

        {{-- Profile Overview --}}
        <section class="relative mb-8 overflow-hidden rounded-3xl bg-gradient-to-br from-slate-900 via-blue-950 to-indigo-950 shadow-xl">
            <div class="pointer-events-none absolute -right-16 -top-20 h-64 w-64 rounded-full bg-blue-500/25 blur-3xl"></div>
            <div class="pointer-events-none absolute -bottom-24 left-1/4 h-64 w-64 rounded-full bg-violet-500/20 blur-3xl"></div>

            <div class="relative flex flex-col gap-8 p-6 sm:p-8 lg:flex-row lg:items-center lg:justify-between">
                {{-- Left: Avatar + Info --}}
                <div class="flex min-w-0 flex-col gap-5 sm:flex-row sm:items-center">
                    @if ($avatarUrl)
                        <img src="{{ $avatarUrl }}" alt="{{ $user->name }}"
                             class="h-24 w-24 shrink-0 rounded-2xl border-4 border-white/20 object-cover shadow-lg">
                    @else
                        <div class="flex h-24 w-24 shrink-0 items-center justify-center rounded-2xl border border-white/20 bg-white/10 text-3xl font-bold text-white shadow-lg backdrop-blur">
                            {{ $avatarText ?: '?' }}
                        </div>
                    @endif

                    <div class="min-w-0">
                        <div class="flex flex-wrap items-center gap-2">
                            @if ($user->role === 'admin')
                                <span class="inline-flex items-center rounded-full border border-violet-300/40 bg-violet-400/20 px-3 py-1 text-xs font-bold uppercase tracking-wide text-violet-100">
                                    Quản trị viên
                                </span>
                            @else
                                <span class="inline-flex items-center rounded-full border border-blue-300/40 bg-blue-400/20 px-3 py-1 text-xs font-bold uppercase tracking-wide text-blue-100">
                                    Khách hàng
                                </span>
                            @endif

                            @if ($user->status === 'active')
                                <span class="inline-flex items-center gap-1.5 rounded-full border border-emerald-300/40 bg-emerald-400/20 px-3 py-1 text-xs font-semibold text-emerald-100">
                                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-400"></span>
                                    Đang hoạt động
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 rounded-full border border-red-300/40 bg-red-400/20 px-3 py-1 text-xs font-semibold text-red-100">
                                    <span class="h-1.5 w-1.5 rounded-full bg-red-400"></span>
                                    Tạm khóa
                                </span>
                            @endif

                            @if ($isCurrentUser)
                                <span class="rounded-full border border-white/25 bg-white/15 px-3 py-1 text-xs font-semibold text-white">
                                    Tài khoản của bạn
                                </span>
                            @endif
                        </div>

                        <h2 class="mt-3 break-words text-2xl font-bold text-white sm:text-3xl">{{ $user->name }}</h2>
                        <p class="mt-1 break-all text-sm text-slate-300">{{ $user->email }}</p>

                        <div class="mt-4 flex flex-wrap gap-x-6 gap-y-2 text-sm text-slate-300">
                            <span class="inline-flex items-center gap-2">
                                <svg class="h-4 w-4 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M3 5a2 2 0 012-2h3l2 5-2 1a16 16 0 007 7l1-2 5 2v3a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                                {{ $user->phone ?: 'Chưa cập nhật SĐT' }}
                            </span>
                            <span class="inline-flex items-center gap-2">
                                <svg class="h-4 w-4 text-orange-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M12 21s7-4.35 7-11a7 7 0 10-14 0c0 6.65 7 11 7 11z"/>
                                    <circle cx="12" cy="10" r="2" stroke-width="2"/>
                                </svg>
                                {{ $user->address ?: 'Chưa cập nhật địa chỉ' }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Right: Member since + Action --}}
                <div class="w-full shrink-0 lg:w-72">
                    <div class="rounded-2xl border border-white/15 bg-white/10 p-5 backdrop-blur">
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Thành viên từ</p>
                        <p class="mt-1.5 text-xl font-bold text-white">{{ $user->created_at->format('d/m/Y') }}</p>
                        <p class="mt-1 text-xs text-slate-400">
                            Cập nhật: {{ $user->updated_at->format('d/m/Y H:i') }}
                        </p>

                        <div class="mt-5">
                            @if ($isCurrentUser)
                                <div class="rounded-xl border border-blue-300/25 bg-blue-400/15 px-4 py-3 text-center text-sm font-medium text-blue-100">
                                    Không thể tự khóa tài khoản hiện tại
                                </div>
                            @else
                                <form action="{{ route('admin.users.update-status', $user) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status"
                                           value="{{ $user->status === 'active' ? 'inactive' : 'active' }}">
                                    <button type="submit"
                                            onclick="return confirm('{{ $user->status === 'active' ? 'Bạn có chắc muốn khóa tài khoản này không?' : 'Bạn có chắc muốn mở khóa tài khoản này không?' }}')"
                                            class="cursor-pointer inline-flex w-full items-center justify-center gap-2 rounded-xl px-5 py-3 text-sm font-semibold text-white shadow-lg transition
                                                {{ $user->status === 'active' ? 'bg-red-500 hover:bg-red-600' : 'bg-emerald-500 hover:bg-emerald-600' }}">
                                        @if ($user->status === 'active')
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M5 11V8a7 7 0 0114 0v3m-1 0H6a2 2 0 00-2 2v6a2 2 0 002 2h12a2 2 0 002-2v-6a2 2 0 00-2-2z"/>
                                            </svg>
                                            Khóa tài khoản
                                        @else
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M8 11V8a4 4 0 018 0m2 3H6a2 2 0 00-2 2v6a2 2 0 002 2h12a2 2 0 002-2v-6a2 2 0 00-2-2z"/>
                                            </svg>
                                            Mở khóa tài khoản
                                        @endif
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- Stats --}}
        <section class="mb-8 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            {{-- Booking --}}
            <div class="rounded-2xl border border-blue-100 bg-gradient-to-br from-white to-blue-50/70 p-5 shadow-sm transition hover:shadow-md">
                <div class="flex items-center justify-between">
                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-100 text-blue-600">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M8 7V3m8 4V3M5 11h14M6 21h12a2 2 0 002-2V7a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <span class="rounded-full bg-blue-100/80 px-2.5 py-1 text-xs font-semibold text-blue-700">Booking</span>
                </div>
                <p class="mt-4 text-3xl font-bold text-slate-900">{{ number_format($user->bookings_count, 0, ',', '.') }}</p>
                <p class="mt-0.5 text-sm text-slate-500">Tổng đơn đặt phòng</p>
            </div>

            {{-- Reviews --}}
            <div class="rounded-2xl border border-amber-100 bg-gradient-to-br from-white to-amber-50/70 p-5 shadow-sm transition hover:shadow-md">
                <div class="flex items-center justify-between">
                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-amber-100 text-amber-600">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="m12 3 2.8 5.7 6.2.9-4.5 4.4 1.1 6.2L12 17.3l-5.6 2.9 1.1-6.2L3 9.6l6.2-.9L12 3z"/>
                        </svg>
                    </div>
                    <span class="rounded-full bg-amber-100/80 px-2.5 py-1 text-xs font-semibold text-amber-700">Đánh giá</span>
                </div>
                <p class="mt-4 text-3xl font-bold text-slate-900">{{ number_format($user->reviews_count, 0, ',', '.') }}</p>
                <p class="mt-0.5 text-sm text-slate-500">Tổng đánh giá đã gửi</p>
            </div>

            {{-- Successful payments --}}
            <div class="rounded-2xl border border-emerald-100 bg-gradient-to-br from-white to-emerald-50/70 p-5 shadow-sm transition hover:shadow-md">
                <div class="flex items-center justify-between">
                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-emerald-100 text-emerald-600">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <span class="rounded-full bg-emerald-100/80 px-2.5 py-1 text-xs font-semibold text-emerald-700">Thành công</span>
                </div>
                <p class="mt-4 text-3xl font-bold text-slate-900">
                    {{ number_format($paymentStatistics['successful_transactions'] ?? 0, 0, ',', '.') }}
                </p>
                <p class="mt-0.5 text-sm text-slate-500">Giao dịch thành công</p>
            </div>

            {{-- Total paid --}}
            <div class="rounded-2xl border border-violet-100 bg-gradient-to-br from-white to-violet-50/70 p-5 shadow-sm transition hover:shadow-md">
                <div class="flex items-center justify-between">
                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-violet-100 text-violet-600">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M3 7h18M5 5h14a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z"/>
                        </svg>
                    </div>
                    <span class="rounded-full bg-violet-100/80 px-2.5 py-1 text-xs font-semibold text-violet-700">Tổng chi</span>
                </div>
                <p class="mt-4 text-2xl font-bold text-slate-900">
                    {{ number_format($paymentStatistics['total_paid'] ?? 0, 0, ',', '.') }} ₫
                </p>
                <p class="mt-0.5 text-sm text-slate-500">Tổng tiền đã thanh toán</p>
            </div>
        </section>

        {{-- Main Content --}}
        <div class="grid items-start gap-6 lg:grid-cols-12">
            {{-- Booking History --}}
            <section id="booking-history"
                     class="min-w-0 scroll-mt-28 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm lg:col-span-8">
                <div class="flex flex-col gap-3 border-b border-slate-100 px-6 py-5 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h2 class="text-lg font-bold text-slate-900">Lịch sử đặt phòng</h2>
                        <p class="mt-0.5 text-sm text-slate-500">Sắp xếp từ mới nhất đến cũ nhất</p>
                    </div>
                    <span class="inline-flex w-fit items-center rounded-full border border-blue-200 bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700">
                        {{ number_format($user->bookings_count, 0, ',', '.') }} đơn
                    </span>
                </div>

                @if ($bookings->count())
                    <div class="overflow-x-auto">
                        <table class="w-full border-collapse text-left">
                            <thead>
                                <tr class="border-b border-slate-100 bg-slate-50/80 text-xs font-semibold uppercase tracking-wider text-slate-500">
                                    <th class="px-5 py-3.5">Mã đơn</th>
                                    <th class="px-5 py-3.5">Homestay</th>
                                    <th class="px-5 py-3.5">Tổng tiền</th>
                                    <th class="px-5 py-3.5">Trạng thái</th>
                                    <th class="px-5 py-3.5 text-right">Chi tiết</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-sm">
                                @foreach ($bookings as $booking)
                                    @php
                                        $bookingClass = $bookingStatusClasses[$booking->status] ?? 'border-slate-200 bg-slate-100 text-slate-600';
                                        $paymentClass = $paymentStatusClasses[$booking->payment_status] ?? 'border-slate-200 bg-slate-100 text-slate-600';
                                    @endphp
                                    <tr class="transition hover:bg-slate-50/80">
                                        <td class="whitespace-nowrap px-5 py-4">
                                            <p class="font-semibold text-blue-600">{{ $booking->booking_code }}</p>
                                            <p class="mt-0.5 text-xs text-slate-400">{{ $booking->created_at->format('d/m/Y H:i') }}</p>
                                        </td>
                                        <td class="px-5 py-4">
                                            <p class="max-w-[180px] truncate font-medium text-slate-900"
                                               title="{{ $booking->room?->homestay?->name }}">
                                                {{ $booking->room?->homestay?->name ?? 'Không xác định' }}
                                            </p>
                                            <p class="mt-0.5 max-w-[180px] truncate text-xs text-slate-500"
                                               title="{{ $booking->room?->name }}">
                                                {{ $booking->room?->name ?? 'Không xác định' }}
                                            </p>
                                        </td>
                                        <td class="whitespace-nowrap px-5 py-4">
                                            <p class="font-semibold text-slate-900">
                                                {{ number_format($booking->total_price, 0, ',', '.') }} ₫
                                            </p>
                                            <p class="mt-0.5 text-xs text-slate-500">
                                                {{ $paymentStatusLabels[$booking->payment_status] ?? 'Không xác định' }}
                                            </p>
                                        </td>
                                        <td class="whitespace-nowrap px-5 py-4">
                                            <span class="inline-flex rounded-full border px-2.5 py-1 text-xs font-medium {{ $bookingClass }}">
                                                {{ $bookingStatusLabels[$booking->status] ?? $booking->status }}
                                            </span>
                                        </td>
                                        <td class="whitespace-nowrap px-5 py-4 text-right">
                                            <a href="{{ route('admin.bookings.show', $booking) }}"
                                               class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-500 transition hover:border-blue-200 hover:bg-blue-50 hover:text-blue-600"
                                               title="Xem chi tiết đơn">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                                </svg>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="border-t border-slate-100 px-6 py-4">
                        {{ $bookings->links() }}
                    </div>
                @else
                    <div class="px-6 py-16 text-center">
                        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-blue-50 text-blue-400">
                            <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                      d="M8 7V3m8 4V3M5 11h14M6 21h12a2 2 0 002-2V7a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <h3 class="mt-4 text-base font-semibold text-slate-900">Chưa có đơn đặt phòng</h3>
                        <p class="mt-1 text-sm text-slate-500">Tài khoản này chưa từng đặt phòng trên hệ thống.</p>
                    </div>
                @endif
            </section>

            {{-- Sidebar --}}
            <aside class="min-w-0 space-y-6 lg:col-span-4">
                {{-- Account Info --}}
                <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                    <div class="border-b border-slate-100 px-5 py-4">
                        <h2 class="text-base font-bold text-slate-900">Thông tin tài khoản</h2>
                        <p class="mt-0.5 text-sm text-slate-500">Quản trị & xác minh</p>
                    </div>
                    <div class="divide-y divide-slate-100 px-5">
                        <div class="flex items-center justify-between gap-4 py-3.5">
                            <p class="text-sm text-slate-500">Mã tài khoản</p>
                            <p class="font-semibold text-slate-900">#{{ $user->id }}</p>
                        </div>
                        <div class="flex items-center justify-between gap-4 py-3.5">
                            <p class="text-sm text-slate-500">Xác minh email</p>
                            @if ($user->email_verified_at)
                                <span class="rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700">Đã xác minh</span>
                            @else
                                <span class="rounded-full bg-amber-50 px-2.5 py-1 text-xs font-semibold text-amber-700">Chưa xác minh</span>
                            @endif
                        </div>
                        <div class="flex items-center justify-between gap-4 py-3.5">
                            <p class="text-sm text-slate-500">Booking đang xử lý</p>
                            <span class="rounded-full bg-amber-50 px-2.5 py-1 text-xs font-semibold text-amber-700">
                                {{ number_format($paymentStatistics['pending_transactions'] ?? 0, 0, ',', '.') }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between gap-4 py-3.5">
                            <p class="text-sm text-slate-500">Tổng đã hoàn tiền</p>
                            <p class="font-semibold text-violet-600">
                                {{ number_format($paymentStatistics['total_refunded'] ?? 0, 0, ',', '.') }} ₫
                            </p>
                        </div>
                    </div>
                </section>

                {{-- Latest Payment --}}
                <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                    <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4">
                        <div>
                            <h2 class="text-base font-bold text-slate-900">Giao dịch gần nhất</h2>
                            <p class="mt-0.5 text-sm text-slate-500">Thanh toán mới nhất</p>
                        </div>
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M3 7h18M5 5h14a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z"/>
                            </svg>
                        </div>
                    </div>

                    @if ($latestPayment)
                        @php
                            $latestPaymentClass = $paymentStatusClasses[$latestPayment->status] ?? 'border-slate-200 bg-slate-100 text-slate-600';
                        @endphp
                        <div class="p-5">
                            <div class="rounded-xl bg-gradient-to-br from-emerald-50 to-blue-50 p-4">
                                <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Số tiền</p>
                                <p class="mt-1 text-2xl font-bold text-emerald-600">
                                    {{ number_format($latestPayment->amount, 0, ',', '.') }} ₫
                                </p>
                                <span class="mt-3 inline-flex rounded-full border px-2.5 py-1 text-xs font-medium {{ $latestPaymentClass }}">
                                    {{ $paymentStatusLabels[$latestPayment->status] ?? $latestPayment->status }}
                                </span>
                            </div>

                            <dl class="mt-4 space-y-3">
                                <div class="flex justify-between gap-3">
                                    <dt class="text-sm text-slate-500">Phương thức</dt>
                                    <dd class="text-right text-sm font-medium text-slate-800">
                                        {{ $paymentMethodLabels[$latestPayment->payment_method] ?? $latestPayment->payment_method }}
                                    </dd>
                                </div>
                                <div class="flex justify-between gap-3">
                                    <dt class="text-sm text-slate-500">Mã giao dịch</dt>
                                    <dd class="max-w-[160px] break-all text-right text-sm font-medium text-slate-800">
                                        {{ $latestPayment->transaction_code ?: 'Chưa có mã' }}
                                    </dd>
                                </div>
                                <div class="flex justify-between gap-3">
                                    <dt class="text-sm text-slate-500">Thời gian</dt>
                                    <dd class="text-right text-sm font-medium text-slate-800">
                                        {{ $latestPayment->paid_at
                                            ? $latestPayment->paid_at->format('d/m/Y H:i')
                                            : $latestPayment->created_at->format('d/m/Y H:i') }}
                                    </dd>
                                </div>
                            </dl>
                        </div>
                    @else
                        <div class="px-5 py-12 text-center">
                            <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-xl bg-slate-100 text-slate-400">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                          d="M3 7h18M5 5h14a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z"/>
                                </svg>
                            </div>
                            <h3 class="mt-3 text-sm font-semibold text-slate-800">Chưa có giao dịch</h3>
                            <p class="mt-1 text-sm text-slate-500">Tài khoản chưa phát sinh thanh toán.</p>
                        </div>
                    @endif
                </section>
            </aside>
        </div>
    </main>
</body>
</html>