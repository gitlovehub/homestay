@extends('layouts.admin')

@section('title', 'Chi tiết đặt phòng | HomeStayGo')

@section('page-title', 'Chi tiết đặt phòng')

@section('content')
    @php
        $statusConfig = [
            'pending' => [
                'label' => 'Chờ xác nhận',
                'badge' => 'bg-amber-50 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-800 text-amber-700 dark:text-amber-300',
                'dot' => 'bg-amber-500',
            ],
            'confirmed' => [
                'label' => 'Đã xác nhận',
                'badge' => 'bg-blue-50 dark:bg-blue-950/40 border border-blue-200 dark:border-blue-800 text-blue-700 dark:text-blue-300',
                'dot' => 'bg-blue-500',
            ],
            'checked_in' => [
                'label' => 'Đã nhận phòng',
                'badge' => 'bg-violet-50 dark:bg-violet-950/40 border border-violet-200 dark:border-violet-800 text-violet-700 dark:text-violet-300',
                'dot' => 'bg-violet-500',
            ],
            'completed' => [
                'label' => 'Đã hoàn thành',
                'badge' => 'bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-300',
                'dot' => 'bg-emerald-500',
            ],
            'cancelled' => [
                'label' => 'Đã hủy',
                'badge' => 'bg-red-50 dark:bg-red-950/40 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-300',
                'dot' => 'bg-red-500',
            ],
        ];

        $paymentConfig = [
            'unpaid' => [
                'label' => 'Chưa thanh toán',
                'box' => 'border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900',
                'title' => 'text-slate-700 dark:text-slate-300',
                'description' => 'text-slate-500 dark:text-slate-400',
                'message' => 'Booking chưa ghi nhận thanh toán.',
            ],
            'pending' => [
                'label' => 'Đang xử lý',
                'box' => 'border-amber-200 dark:border-amber-800 bg-amber-50 dark:bg-amber-950/40',
                'title' => 'text-amber-700 dark:text-amber-300',
                'description' => 'text-amber-600 dark:text-amber-400',
                'message' => 'Giao dịch đang được hệ thống xử lý.',
            ],
            'paid' => [
                'label' => 'Đã thanh toán',
                'box' => 'border-emerald-200 dark:border-emerald-800 bg-emerald-50 dark:bg-emerald-950/40',
                'title' => 'text-emerald-700 dark:text-emerald-300',
                'description' => 'text-emerald-600 dark:text-emerald-400',
                'message' => 'Booking đã được thanh toán thành công.',
            ],
            'refunded' => [
                'label' => 'Đã hoàn tiền',
                'box' => 'border-violet-200 dark:border-violet-800 bg-violet-50 dark:bg-violet-950/40',
                'title' => 'text-violet-700 dark:text-violet-300',
                'description' => 'text-violet-600 dark:text-violet-400',
                'message' => 'Khoản thanh toán đã được hoàn lại.',
            ],
            'failed' => [
                'label' => 'Thanh toán thất bại',
                'box' => 'border-red-200 dark:border-red-800 bg-red-50 dark:bg-red-950/40',
                'title' => 'text-red-700 dark:text-red-300',
                'description' => 'text-red-600 dark:text-red-400',
                'message' => 'Giao dịch chưa được thực hiện thành công.',
            ],
        ];

        $currentStatus = $statusConfig[$booking->status] ?? [
            'label' => 'Không xác định',
            'badge' => 'bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-400',
            'dot' => 'bg-slate-400',
        ];

        $currentPayment = $paymentConfig[$booking->payment_status] ?? [
            'label' => 'Không xác định',
            'box' => 'border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900',
            'title' => 'text-slate-700 dark:text-slate-300',
            'description' => 'text-slate-500 dark:text-slate-400',
            'message' => 'Không xác định được trạng thái thanh toán.',
        ];

        $room = $booking->room;
        $homestay = $room?->homestay;
    @endphp

    <div class="mx-auto max-w-screen-2xl">
        <x-alert />

        <div class="mb-8">
            <h2 class="text-lg font-semibold text-slate-900 dark:text-slate-100 md:text-2xl">
                Thông tin chi tiết Booking.
            </h2>

            <a href="{{ route('admin.bookings.index') }}"
                class="mt-1 inline-flex items-center gap-1 text-xs font-medium text-blue-600 transition hover:text-blue-700 dark:hover:text-blue-300 dark:text-blue-400 sm:text-sm">
                ←
                Trở về danh sách đặt phòng
            </a>
        </div>

        {{-- Tiêu đề --}}
        <div class="mb-6 overflow-hidden rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 shadow-sm">
            <div class="border-b border-slate-200 dark:border-slate-700 bg-slate-50/70 dark:bg-slate-900/70 px-5 py-5 sm:px-6">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div class="min-w-0">
                        <div class="flex flex-wrap items-center gap-3">
                            <h2 class="break-all text-2xl font-bold text-slate-900 dark:text-slate-100 sm:text-3xl">
                                {{ $booking->booking_code }}
                            </h2>

                            <span
                                class="inline-flex items-center gap-2 rounded-full px-3 py-1.5 text-xs font-semibold {{ $currentStatus['badge'] }}">
                                <span class="h-2 w-2 rounded-full {{ $currentStatus['dot'] }}"></span>

                                {{ $currentStatus['label'] }}
                            </span>
                        </div>

                        <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">
                            Được tạo lúc {{ $booking->created_at->format('H:i, d/m/Y') }}
                        </p>
                    </div>

                </div>
            </div>

            {{-- Thông tin tổng quan --}}
            <div class="grid divide-y divide-slate-200 dark:divide-slate-700 sm:grid-cols-2 sm:divide-x sm:divide-y-0 xl:grid-cols-4">
                <div class="px-5 py-4 sm:px-6">
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">
                        Khách hàng
                    </p>

                    <p class="mt-2 truncate font-bold text-slate-900 dark:text-slate-100">
                        {{ $booking->customer_name ?: 'Chưa cập nhật' }}
                    </p>

                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                        {{ $booking->customer_phone ?: 'Chưa có số điện thoại' }}
                    </p>
                </div>

                <div class="px-5 py-4 sm:px-6">
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">
                        Thời gian lưu trú
                    </p>

                    <p class="mt-2 font-bold text-slate-900 dark:text-slate-100">
                        {{ $booking->check_in->format('d/m/Y') }}
                        <span class="mx-1 text-slate-400 dark:text-slate-500">→</span>
                        {{ $booking->check_out->format('d/m/Y') }}
                    </p>

                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                        {{ $booking->number_of_nights }} đêm ·
                        {{ $booking->number_of_guests }} khách
                    </p>
                </div>

                <div class="px-5 py-4 sm:px-6">
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">
                        Phòng
                    </p>

                    <p class="mt-2 truncate font-bold text-slate-900 dark:text-slate-100">
                        {{ $room?->name ?? 'Phòng không tồn tại' }}
                    </p>

                    <p class="mt-1 truncate text-sm text-slate-500 dark:text-slate-400">
                        {{ $homestay?->name ?? 'Homestay không xác định' }}
                    </p>
                </div>

                <div class="px-5 py-4 sm:px-6">
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">
                        Tổng thanh toán
                    </p>

                    <p class="mt-2 text-xl font-bold text-blue-600 dark:text-blue-400">
                        {{ number_format((float) ($booking->total_price ?? 0), 0, ',', '.') }}đ
                    </p>

                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                        {{ $currentPayment['label'] }}
                    </p>
                </div>
            </div>
        </div>

        <div class="grid gap-6 xl:grid-cols-12">
            {{-- Nội dung chính --}}
            <div class="space-y-6 xl:col-span-8">
                {{-- Thông tin khách hàng --}}
                <section class="overflow-hidden rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 shadow-sm">
                    <div class="flex items-center gap-3 border-b border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 px-5 py-4 sm:px-6">
                        <span
                            class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-100 dark:bg-blue-900/50 text-blue-600 dark:text-blue-400">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="1.8" class="h-5 w-5">
                                <circle cx="12" cy="8" r="4"></circle>
                                <path d="M4 21a8 8 0 0 1 16 0"></path>
                            </svg>
                        </span>

                        <div>
                            <h3 class="font-bold text-slate-900 dark:text-slate-100">
                                Thông tin khách hàng
                            </h3>

                            <p class="mt-0.5 text-sm text-slate-500 dark:text-slate-400">
                                Thông tin người thực hiện đặt phòng.
                            </p>
                        </div>
                    </div>

                    <div class="grid gap-5 p-5 sm:grid-cols-2 sm:p-6">
                        <div class="rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-900/50 p-4">
                            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">
                                Họ và tên
                            </p>

                            <p class="mt-2 font-semibold text-slate-900 dark:text-slate-100">
                                {{ $booking->customer_name ?: 'Chưa cập nhật' }}
                            </p>
                        </div>

                        <div class="rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-900/50 p-4">
                            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">
                                Số điện thoại
                            </p>

                            @if ($booking->customer_phone)
                                <a href="tel:{{ $booking->customer_phone }}"
                                    class="mt-2 inline-block font-semibold text-blue-600 dark:text-blue-400 transition hover:text-blue-700 dark:hover:text-blue-300 hover:underline">
                                    {{ $booking->customer_phone }}
                                </a>
                            @else
                                <p class="mt-2 font-semibold text-slate-500 dark:text-slate-400">
                                    Chưa cập nhật
                                </p>
                            @endif
                        </div>

                        <div class="rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-900/50 p-4">
                            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">
                                Email
                            </p>

                            @if ($booking->customer_email)
                                <a href="mailto:{{ $booking->customer_email }}"
                                    class="mt-2 inline-block break-all font-semibold text-blue-600 dark:text-blue-400 transition hover:text-blue-700 dark:hover:text-blue-300 hover:underline">
                                    {{ $booking->customer_email }}
                                </a>
                            @else
                                <p class="mt-2 font-semibold text-slate-500 dark:text-slate-400">
                                    Chưa cập nhật
                                </p>
                            @endif
                        </div>

                        <div class="rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-900/50 p-4">
                            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">
                                Tài khoản đặt phòng
                            </p>

                            <p class="mt-2 font-semibold text-slate-900 dark:text-slate-100">
                                {{ $booking->user ? '#' . $booking->user->id : 'Không tồn tại' }}
                            </p>
                        </div>
                    </div>
                </section>

                {{-- Thông tin lưu trú --}}
                <section class="overflow-hidden rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 shadow-sm">
                    <div class="flex items-center gap-3 border-b border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 px-5 py-4 sm:px-6">
                        <span
                            class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-violet-100 dark:bg-violet-900/50 text-violet-600 dark:text-violet-400">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="1.8" class="h-5 w-5">
                                <rect x="3" y="5" width="18" height="16" rx="2"></rect>
                                <path d="M16 3v4"></path>
                                <path d="M8 3v4"></path>
                                <path d="M3 10h18"></path>
                            </svg>
                        </span>

                        <div>
                            <h3 class="font-bold text-slate-900 dark:text-slate-100">
                                Thông tin lưu trú
                            </h3>

                            <p class="mt-0.5 text-sm text-slate-500 dark:text-slate-400">
                                Thời gian lưu trú và số lượng khách.
                            </p>
                        </div>
                    </div>

                    <div class="grid gap-5 p-5 sm:grid-cols-2 lg:grid-cols-3 sm:p-6">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">
                                Ngày nhận phòng
                            </p>

                            <p class="mt-2 text-lg font-bold text-slate-900 dark:text-slate-100">
                                {{ $booking->check_in->format('d/m/Y') }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">
                                Ngày trả phòng
                            </p>

                            <p class="mt-2 text-lg font-bold text-slate-900 dark:text-slate-100">
                                {{ $booking->check_out->format('d/m/Y') }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">
                                Tổng số đêm
                            </p>

                            <p class="mt-2 text-lg font-bold text-slate-900 dark:text-slate-100">
                                {{ $booking->number_of_nights }} đêm
                            </p>
                        </div>

                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">
                                Số lượng khách
                            </p>

                            <p class="mt-2 text-lg font-bold text-slate-900 dark:text-slate-100">
                                {{ $booking->number_of_guests }} khách
                            </p>
                        </div>

                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">
                                Thời gian tạo đơn
                            </p>

                            <p class="mt-2 font-semibold text-slate-900 dark:text-slate-100">
                                {{ $booking->created_at->format('H:i d/m/Y') }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">
                                Cập nhật lần cuối
                            </p>

                            <p class="mt-2 font-semibold text-slate-900 dark:text-slate-100">
                                {{ $booking->updated_at->format('H:i d/m/Y') }}
                            </p>
                        </div>
                    </div>
                </section>

                {{-- Thông tin phòng --}}
                <section class="overflow-hidden rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 shadow-sm">
                    <div class="flex items-center gap-3 border-b border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 px-5 py-4 sm:px-6">
                        <span
                            class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-emerald-100 dark:bg-emerald-900/50 text-emerald-600 dark:text-emerald-400">
                            <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                                <polyline points="9 22 9 12 15 12 15 22" />
                            </svg>
                        </span>

                        <div>
                            <h3 class="font-bold text-slate-900 dark:text-slate-100">
                                Thông tin phòng
                            </h3>

                            <p class="mt-0.5 text-sm text-slate-500 dark:text-slate-400">
                                Phòng và Homestay được khách lựa chọn.
                            </p>
                        </div>
                    </div>

                    <div class="p-5 sm:p-6">
                        @if ($room)
                            <div class="grid gap-5 sm:grid-cols-2">
                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">
                                        Tên phòng
                                    </p>

                                    <p class="mt-2 text-lg font-bold text-slate-900 dark:text-slate-100">
                                        {{ $room->name }}
                                    </p>
                                </div>

                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">
                                        Loại phòng
                                    </p>

                                    <p class="mt-2 font-semibold text-slate-900 dark:text-slate-100">
                                        {{ $room->room_type ?: 'Chưa cập nhật' }}
                                    </p>
                                </div>

                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">
                                        Homestay
                                    </p>

                                    <p class="mt-2 font-semibold text-slate-900 dark:text-slate-100">
                                        {{ $homestay?->name ?? 'Không xác định' }}
                                    </p>
                                </div>

                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">
                                        Sức chứa
                                    </p>

                                    <p class="mt-2 font-semibold text-slate-900 dark:text-slate-100">
                                        {{ $room->capacity ? $room->capacity . ' khách' : 'Chưa cập nhật' }}
                                    </p>
                                </div>

                                <div class="sm:col-span-2">
                                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">
                                        Địa chỉ Homestay
                                    </p>

                                    <div class="mt-2 flex items-start gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="1.8"
                                            class="mt-0.5 h-5 w-5 shrink-0 text-slate-400 dark:text-slate-500">
                                            <path d="M20 10c0 5-8 11-8 11S4 15 4 10a8 8 0 1 1 16 0Z"></path>
                                            <circle cx="12" cy="10" r="2.5"></circle>
                                        </svg>

                                        <p class="font-semibold leading-7 text-slate-900 dark:text-slate-100">
                                            {{ $homestay?->address ?? 'Chưa cập nhật địa chỉ' }},

                                            @if ($homestay?->city)
                                                {{ $homestay->city }}
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="rounded-xl border border-dashed border-red-200 dark:border-red-800 bg-red-50 dark:bg-red-950/40 px-6 py-10 text-center">
                                <span
                                    class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-red-100 dark:bg-red-900/50 text-red-600 dark:text-red-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="1.8" class="h-6 w-6">
                                        <circle cx="12" cy="12" r="9"></circle>
                                        <path d="M12 8v5"></path>
                                        <path d="M12 16h.01"></path>
                                    </svg>
                                </span>

                                <p class="mt-4 font-semibold text-red-700 dark:text-red-300">
                                    Phòng không còn tồn tại
                                </p>

                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">
                                    Dữ liệu phòng của Booking này đã bị xóa hoặc không tìm thấy.
                                </p>
                            </div>
                        @endif
                    </div>
                </section>

                {{-- Ghi chú --}}
                <section class="overflow-hidden rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 shadow-sm">
                    <div class="flex items-center gap-3 border-b border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 px-5 py-4 sm:px-6">
                        <span
                            class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-amber-100 dark:bg-amber-900/50 text-amber-600 dark:text-amber-400">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="1.8" class="h-5 w-5">
                                <path d="M4 4h16v13H8l-4 4V4Z"></path>
                            </svg>
                        </span>

                        <div>
                            <h3 class="font-bold text-slate-900 dark:text-slate-100">
                                Ghi chú của khách hàng
                            </h3>

                            <p class="mt-0.5 text-sm text-slate-500 dark:text-slate-400">
                                Yêu cầu hoặc thông tin khách hàng gửi kèm.
                            </p>
                        </div>
                    </div>

                    <div class="p-5 sm:p-6">
                        @if ($booking->note)
                            <div class="rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 p-4">
                                <p class="leading-7 text-slate-700 dark:text-slate-300">
                                    {{ $booking->note }}
                                </p>
                            </div>
                        @else
                            <div
                                class="rounded-xl border border-dashed border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-900 px-5 py-7 text-center">
                                <p class="text-sm text-slate-500 dark:text-slate-400">
                                    Khách hàng không để lại ghi chú.
                                </p>
                            </div>
                        @endif
                    </div>
                </section>

                {{-- Thông tin hủy --}}
                @if ($booking->status === 'cancelled')
                    <section class="hidden overflow-hidden rounded-2xl border border-red-200 dark:border-red-800 bg-white dark:bg-slate-800 shadow-sm xl:block">
                        <div class="flex items-center gap-3 border-b border-red-100 dark:border-red-900 bg-red-50 dark:bg-red-950/40 px-5 py-4 sm:px-6">
                            <span
                                class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-red-100 dark:bg-red-900/50 text-red-600 dark:text-red-400">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="1.8" class="h-5 w-5">
                                    <circle cx="12" cy="12" r="9"></circle>
                                    <path d="m9 9 6 6"></path>
                                    <path d="m15 9-6 6"></path>
                                </svg>
                            </span>

                            <div>
                                <h3 class="font-bold text-red-700 dark:text-red-300">
                                    Thông tin hủy Booking
                                </h3>

                                <p class="mt-0.5 text-sm text-red-600 dark:text-red-400">
                                    Chi tiết liên quan đến việc hủy đơn.
                                </p>
                            </div>
                        </div>

                        <div class="grid gap-5 p-5 sm:grid-cols-2 sm:p-6">
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">
                                    Thời gian hủy
                                </p>

                                <p class="mt-2 font-semibold text-slate-900 dark:text-slate-100">
                                    {{ $booking->cancelled_at ? $booking->cancelled_at->format('H:i d/m/Y') : 'Chưa cập nhật' }}
                                </p>
                            </div>

                            <div>
                                <p class="text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">
                                    Lý do hủy
                                </p>

                                <p class="mt-2 leading-7 text-slate-700 dark:text-slate-300">
                                    {{ $booking->cancellation_reason ?: 'Không có lý do hủy.' }}
                                </p>
                            </div>
                        </div>
                    </section>
                @endif
            </div>

            {{-- Cột bên phải --}}
            <aside class="space-y-6 xl:col-span-4">
                <div class="space-y-6 xl:sticky xl:top-6">
                    {{-- Quản lý Booking --}}
                    <section class="overflow-hidden rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 shadow-sm">
                        <div class="border-b border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 px-5 py-4">
                            <h3 class="font-bold text-slate-900 dark:text-slate-100">
                                Quản lý Booking
                            </h3>

                            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                                Cập nhật trạng thái xử lý của đơn.
                            </p>
                        </div>

                        <div class="space-y-3 p-5">
                            @if ($booking->status === 'pending')
                                <form method="POST" action="{{ route('admin.bookings.update-status', $booking) }}"
                                    onsubmit="return confirm('Bạn có chắc muốn xác nhận Booking {{ $booking->booking_code }} không?')">
                                    @csrf
                                    @method('PATCH')

                                    <input type="hidden" name="status" value="confirmed">

                                    <button type="submit"
                                        class="inline-flex w-full cursor-pointer items-center justify-center gap-2 rounded-xl bg-blue-600 dark:bg-blue-500 h-11 px-5 text-sm font-semibold text-white transition hover:bg-blue-700 dark:hover:bg-blue-600 focus:outline-none focus:ring-4 focus:ring-blue-200 dark:focus:ring-blue-900/50">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2" class="h-5 w-5">
                                            <path d="m5 12 4 4L19 6"></path>
                                        </svg>
                                        <span>Xác nhận Booking</span>
                                    </button>
                                </form>

                                <form method="POST" action="{{ route('admin.bookings.update-status', $booking) }}"
                                    onsubmit="return confirm('Bạn có chắc muốn hủy Booking {{ $booking->booking_code }} không?')">
                                    @csrf
                                    @method('PATCH')

                                    <input type="hidden" name="status" value="cancelled">

                                    <button type="submit"
                                        class="inline-flex w-full cursor-pointer items-center justify-center gap-2 rounded-xl border border-red-200 dark:border-red-800 bg-red-50 dark:bg-red-950/40 h-11 px-5 text-sm font-semibold text-red-600 dark:text-red-400 transition hover:bg-red-100 dark:hover:bg-red-900/60 focus:outline-none focus:ring-4 focus:ring-red-100 dark:focus:ring-red-900/50">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2" class="h-5 w-5">
                                            <path d="M6 6l12 12"></path>
                                            <path d="M18 6 6 18"></path>
                                        </svg>
                                        <span>Hủy Booking</span>
                                    </button>
                                </form>
                            @elseif ($booking->status === 'confirmed')
                                <form method="POST" action="{{ route('admin.bookings.update-status', $booking) }}"
                                    onsubmit="return confirm('Xác nhận khách đã nhận phòng?')">
                                    @csrf
                                    @method('PATCH')

                                    <input type="hidden" name="status" value="checked_in">

                                    <button type="submit"
                                        class="inline-flex w-full cursor-pointer items-center justify-center gap-2 rounded-xl bg-violet-600 dark:bg-violet-500 h-11 px-5 text-sm font-semibold text-white transition hover:bg-violet-700 dark:hover:bg-violet-600 focus:outline-none focus:ring-4 focus:ring-violet-200 dark:focus:ring-violet-900/50">
                                        <svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" />
                                            <circle cx="12" cy="10" r="3" />
                                        </svg>
                                        <span>Khách đã nhận phòng</span>
                                    </button>
                                </form>

                                <form method="POST" action="{{ route('admin.bookings.update-status', $booking) }}"
                                    onsubmit="return confirm('Bạn có chắc muốn hủy Booking {{ $booking->booking_code }} không?')">
                                    @csrf
                                    @method('PATCH')

                                    <input type="hidden" name="status" value="cancelled">

                                    <button type="submit"
                                        class="inline-flex w-full cursor-pointer items-center justify-center gap-2 rounded-xl border border-red-200 dark:border-red-800 bg-red-50 dark:bg-red-950/40 h-11 px-5 text-sm font-semibold text-red-600 dark:text-red-400 transition hover:bg-red-100 dark:hover:bg-red-900/60 focus:outline-none focus:ring-4 focus:ring-red-100 dark:focus:ring-red-900/50">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2" class="h-5 w-5">
                                            <path d="M6 6l12 12"></path>
                                            <path d="M18 6 6 18"></path>
                                        </svg>
                                        <span>Hủy Booking</span>
                                    </button>
                                </form>
                            @elseif ($booking->status === 'checked_in')
                                <form method="POST" action="{{ route('admin.bookings.update-status', $booking) }}"
                                    onsubmit="return confirm('Xác nhận Booking {{ $booking->booking_code }} đã hoàn thành?')">
                                    @csrf
                                    @method('PATCH')

                                    <input type="hidden" name="status" value="completed">

                                    <button type="submit"
                                        class="inline-flex w-full cursor-pointer items-center justify-center gap-2 rounded-xl bg-emerald-600 dark:bg-emerald-500 h-11 px-5 text-sm font-semibold text-white transition hover:bg-emerald-700 dark:hover:bg-emerald-600 focus:outline-none focus:ring-4 focus:ring-emerald-200 dark:focus:ring-emerald-900/50">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2" class="h-5 w-5">
                                            <path d="m5 12 4 4L19 6"></path>
                                        </svg>

                                        <span>Hoàn thành</span>
                                    </button>
                                </form>
                            @elseif ($booking->status === 'completed')
                                <div class="rounded-xl border border-emerald-200 dark:border-emerald-800 bg-emerald-50 dark:bg-emerald-950/40 p-4">
                                    <div class="flex items-start gap-3">
                                        <span
                                            class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-emerald-100 dark:bg-emerald-900/50 text-emerald-600 dark:text-emerald-400">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="2" class="h-5 w-5">
                                                <path d="m5 12 4 4L19 6"></path>
                                            </svg>
                                        </span>

                                        <div>
                                            <p class="font-semibold text-emerald-700 dark:text-emerald-300">
                                                Booking đã hoàn thành
                                            </p>

                                            <p class="mt-1 text-sm leading-6 text-emerald-600 dark:text-emerald-400">
                                                Booking này không còn thao tác cập nhật.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @elseif ($booking->status === 'cancelled')
                                <div class="rounded-xl border border-red-200 dark:border-red-800 bg-red-50 dark:bg-red-950/40 p-4">
                                    <div class="flex items-start gap-3">
                                        <span
                                            class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-red-100 dark:bg-red-900/50 text-red-600 dark:text-red-400">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="2" class="h-5 w-5">
                                                <path d="M6 6l12 12"></path>
                                                <path d="M18 6 6 18"></path>
                                            </svg>
                                        </span>

                                        <div>
                                            <p class="font-semibold text-red-700 dark:text-red-300">
                                                Booking đã bị hủy
                                            </p>

                                            <p class="mt-1 text-sm leading-6 text-red-600 dark:text-red-400">
                                                Booking này không còn thao tác cập nhật.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 p-4">
                                    <p class="font-semibold text-slate-700 dark:text-slate-300">
                                        Không có thao tác
                                    </p>

                                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                                        Trạng thái Booking hiện tại không hợp lệ.
                                    </p>
                                </div>
                            @endif
                        </div>
                    </section>

                    {{-- Chi tiết thanh toán --}}
                    <section class="overflow-hidden rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 shadow-sm">
                        <div class="border-b border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 px-5 py-4">
                            <h3 class="font-bold text-slate-900 dark:text-slate-100">
                                Chi tiết thanh toán
                            </h3>

                            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                                Các khoản phí của đơn đặt phòng.
                            </p>
                        </div>

                        <div class="space-y-4 p-5">
                            <div class="flex items-start justify-between gap-4">
                                <span class="text-sm text-slate-500 dark:text-slate-400">
                                    Giá phòng mỗi đêm
                                </span>

                                <span class="whitespace-nowrap text-sm font-semibold text-slate-900 dark:text-slate-100">
                                    {{ number_format((float) ($booking->room_price ?? 0), 0, ',', '.') }}đ
                                </span>
                            </div>

                            <div class="flex items-start justify-between gap-4">
                                <span class="text-sm text-slate-500 dark:text-slate-400">
                                    Số đêm
                                </span>

                                <span class="text-sm font-semibold text-slate-900 dark:text-slate-100">
                                    {{ $booking->number_of_nights }}
                                </span>
                            </div>

                            <div class="flex items-start justify-between gap-4">
                                <span class="text-sm text-slate-500 dark:text-slate-400">
                                    Tạm tính
                                </span>

                                <span class="whitespace-nowrap text-sm font-semibold text-slate-900 dark:text-slate-100">
                                    {{ number_format((float) ($booking->subtotal ?? 0), 0, ',', '.') }}đ
                                </span>
                            </div>

                            <div class="flex items-start justify-between gap-4">
                                <span class="text-sm text-slate-500 dark:text-slate-400">
                                    Phí dịch vụ
                                </span>

                                <span class="whitespace-nowrap text-sm font-semibold text-slate-900 dark:text-slate-100">
                                    {{ number_format((float) ($booking->service_fee ?? 0), 0, ',', '.') }}đ
                                </span>
                            </div>

                            <div class="flex items-start justify-between gap-4">
                                <span class="text-sm text-slate-500 dark:text-slate-400">
                                    Giảm giá
                                </span>

                                <span class="whitespace-nowrap text-sm font-semibold text-emerald-600 dark:text-emerald-400">
                                    -{{ number_format((float) ($booking->discount_amount ?? 0), 0, ',', '.') }}đ
                                </span>
                            </div>

                            <div class="border-t border-dashed border-slate-300 dark:border-slate-600 pt-4">
                                <div class="flex items-end justify-between gap-4">
                                    <span class="font-bold text-slate-900 dark:text-slate-100">
                                        Tổng thanh toán
                                    </span>

                                    <span class="whitespace-nowrap text-xl font-bold text-blue-600 dark:text-blue-400">
                                        {{ number_format((float) ($booking->total_price ?? 0), 0, ',', '.') }}đ
                                    </span>
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- Trạng thái thanh toán --}}
                    <section class="overflow-hidden rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 shadow-sm">
                        <div class="border-b border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 px-5 py-4">
                            <h3 class="font-bold text-slate-900 dark:text-slate-100">
                                Trạng thái thanh toán
                            </h3>
                        </div>

                        <div class="p-5">
                            <div class="rounded-xl border p-4 {{ $currentPayment['box'] }}">
                                <p class="font-semibold {{ $currentPayment['title'] }}">
                                    {{ $currentPayment['label'] }}
                                </p>

                                <p class="mt-1 text-sm leading-6 {{ $currentPayment['description'] }}">
                                    {{ $currentPayment['message'] }}
                                </p>
                            </div>
                        </div>
                    </section>

                    {{-- Thông tin nhanh --}}
                    <section class="overflow-hidden rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 shadow-sm">
                        <div class="border-b border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 px-5 py-4">
                            <h3 class="font-bold text-slate-900 dark:text-slate-100">
                                Thông tin nhanh
                            </h3>
                        </div>

                        <div class="divide-y divide-slate-100 dark:divide-slate-700 px-5">
                            <div class="py-4">
                                <p class="text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">
                                    Mã Booking
                                </p>

                                <p class="mt-2 break-all font-bold text-blue-600 dark:text-blue-400">
                                    {{ $booking->booking_code }}
                                </p>
                            </div>

                            <div class="py-4">
                                <p class="text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">
                                    Trạng thái Booking
                                </p>

                                <div class="mt-2">
                                    <span
                                        class="inline-flex items-center gap-2 rounded-full px-3 py-1.5 text-xs font-semibold {{ $currentStatus['badge'] }}">
                                        <span class="h-2 w-2 rounded-full {{ $currentStatus['dot'] }}"></span>

                                        {{ $currentStatus['label'] }}
                                    </span>
                                </div>
                            </div>

                            <div class="py-4">
                                <p class="text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">
                                    Thanh toán
                                </p>

                                <p class="mt-2 font-semibold text-slate-900 dark:text-slate-100">
                                    {{ $currentPayment['label'] }}
                                </p>
                            </div>

                            <div class="py-4">
                                <p class="text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">
                                    Cập nhật lần cuối
                                </p>

                                <p class="mt-2 font-semibold text-slate-900 dark:text-slate-100">
                                    {{ $booking->updated_at->format('H:i d/m/Y') }}
                                </p>
                            </div>
                        </div>
                    </section>

                    {{-- Thông tin hủy --}}
                    @if ($booking->status === 'cancelled')
                        <section class="block overflow-hidden rounded-2xl border border-red-200 dark:border-red-800 bg-white dark:bg-slate-800 shadow-sm xl:hidden">
                            <div class="flex items-center gap-3 border-b border-red-100 dark:border-red-900 bg-red-50 dark:bg-red-950/40 px-5 py-4 sm:px-6">
                                <span
                                    class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-red-100 dark:bg-red-900/50 text-red-600 dark:text-red-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="1.8" class="h-5 w-5">
                                        <circle cx="12" cy="12" r="9"></circle>
                                        <path d="m9 9 6 6"></path>
                                        <path d="m15 9-6 6"></path>
                                    </svg>
                                </span>

                                <div>
                                    <h3 class="font-bold text-red-700 dark:text-red-300">
                                        Thông tin hủy Booking
                                    </h3>

                                    <p class="mt-0.5 text-sm text-red-600 dark:text-red-400">
                                        Chi tiết liên quan đến việc hủy đơn.
                                    </p>
                                </div>
                            </div>

                            <div class="grid gap-5 p-5 sm:grid-cols-2 sm:p-6">
                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">
                                        Thời gian hủy
                                    </p>

                                    <p class="mt-2 font-semibold text-slate-900 dark:text-slate-100">
                                        {{ $booking->cancelled_at ? $booking->cancelled_at->format('H:i d/m/Y') : 'Chưa cập nhật' }}
                                    </p>
                                </div>

                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">
                                        Lý do hủy
                                    </p>

                                    <p class="mt-2 leading-7 text-slate-700 dark:text-slate-300">
                                        {{ $booking->cancellation_reason ?: 'Không có lý do hủy.' }}
                                    </p>
                                </div>
                            </div>
                        </section>
                    @endif
                </div>
            </aside>
        </div>
    </div>
@endsection