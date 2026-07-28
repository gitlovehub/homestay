<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Chi tiết Booking | HomeStay</title>

    @vite([
        'resources/css/app.css',
        'resources/js/app.js',
    ])
</head>

<body class="min-h-screen bg-slate-100">

    @include('partials.navbar')

    @php
        $statusLabels = [
            'pending' => 'Chờ xác nhận',
            'confirmed' => 'Đã xác nhận',
            'checked_in' => 'Đã nhận phòng',
            'completed' => 'Đã hoàn thành',
            'cancelled' => 'Đã hủy',
        ];

        $paymentLabels = [
            'unpaid' => 'Chưa thanh toán',
            'pending' => 'Đang xử lý',
            'paid' => 'Đã thanh toán',
            'refunded' => 'Đã hoàn tiền',
            'failed' => 'Thanh toán thất bại',
        ];
    @endphp

    <main class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">

        <x-alert />

        {{-- Quay lại --}}
        <a
            href="{{ route('admin.bookings.index') }}"
            class="mb-4 inline-flex items-center gap-2 text-sm font-semibold text-blue-600 transition hover:text-blue-700"
        >
            <span>←</span>
            Quay lại danh sách Booking
        </a>

        {{-- Tiêu đề --}}
        <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">

            <div>
                <p class="text-sm font-semibold uppercase tracking-wider text-blue-600">
                    Chi tiết đơn đặt phòng
                </p>

                <h1 class="mt-2 text-3xl font-bold text-slate-900">
                    {{ $booking->booking_code }}
                </h1>

                <p class="mt-2 text-sm text-slate-500">
                    Được tạo lúc
                    {{ $booking->created_at->format('H:i, d/m/Y') }}
                </p>
            </div>

            <div>
                @switch($booking->status)

                    @case('pending')
                        <span class="inline-flex items-center gap-2 rounded-full bg-amber-100 px-4 py-2 text-sm font-semibold text-amber-700">
                            <span class="h-2.5 w-2.5 rounded-full bg-amber-500"></span>
                            Chờ xác nhận
                        </span>
                        @break

                    @case('confirmed')
                        <span class="inline-flex items-center gap-2 rounded-full bg-blue-100 px-4 py-2 text-sm font-semibold text-blue-700">
                            <span class="h-2.5 w-2.5 rounded-full bg-blue-500"></span>
                            Đã xác nhận
                        </span>
                        @break

                    @case('checked_in')
                        <span class="inline-flex items-center gap-2 rounded-full bg-violet-100 px-4 py-2 text-sm font-semibold text-violet-700">
                            <span class="h-2.5 w-2.5 rounded-full bg-violet-500"></span>
                            Đã nhận phòng
                        </span>
                        @break

                    @case('completed')
                        <span class="inline-flex items-center gap-2 rounded-full bg-emerald-100 px-4 py-2 text-sm font-semibold text-emerald-700">
                            <span class="h-2.5 w-2.5 rounded-full bg-emerald-500"></span>
                            Đã hoàn thành
                        </span>
                        @break

                    @case('cancelled')
                        <span class="inline-flex items-center gap-2 rounded-full bg-red-100 px-4 py-2 text-sm font-semibold text-red-700">
                            <span class="h-2.5 w-2.5 rounded-full bg-red-500"></span>
                            Đã hủy
                        </span>
                        @break

                    @default
                        <span class="inline-flex items-center gap-2 rounded-full bg-slate-100 px-4 py-2 text-sm font-semibold text-slate-600">
                            <span class="h-2.5 w-2.5 rounded-full bg-slate-400"></span>
                            Không xác định
                        </span>

                @endswitch
            </div>

        </div>

        <div class="grid gap-6 lg:grid-cols-3">

            {{-- Nội dung chính --}}
            <div class="space-y-6 lg:col-span-2">

                {{-- Thông tin khách hàng --}}
                <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

                    <div class="border-b border-slate-200 bg-slate-50 px-6 py-4">
                        <h2 class="text-lg font-bold text-slate-900">
                            Thông tin khách hàng
                        </h2>

                        <p class="mt-1 text-sm text-slate-500">
                            Thông tin người thực hiện đặt phòng.
                        </p>
                    </div>

                    <div class="grid gap-6 p-6 sm:grid-cols-2">

                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                                Họ và tên
                            </p>

                            <p class="mt-2 font-semibold text-slate-900">
                                {{ $booking->customer_name }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                                Số điện thoại
                            </p>

                            <p class="mt-2 font-semibold text-slate-900">
                                {{ $booking->customer_phone }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                                Email
                            </p>

                            <p class="mt-2 break-all font-semibold text-slate-900">
                                {{ $booking->customer_email }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                                Mã tài khoản
                            </p>

                            <p class="mt-2 font-semibold text-slate-900">
                                {{ $booking->user ? '#' . $booking->user->id : 'Không tồn tại' }}
                            </p>
                        </div>

                    </div>

                </section>

                {{-- Thông tin lưu trú --}}
                <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

                    <div class="border-b border-slate-200 bg-slate-50 px-6 py-4">
                        <h2 class="text-lg font-bold text-slate-900">
                            Thông tin lưu trú
                        </h2>

                        <p class="mt-1 text-sm text-slate-500">
                            Thời gian và số lượng khách trong Booking.
                        </p>
                    </div>

                    <div class="grid gap-6 p-6 sm:grid-cols-2 lg:grid-cols-3">

                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                                Ngày nhận phòng
                            </p>

                            <p class="mt-2 text-lg font-bold text-slate-900">
                                {{ $booking->check_in->format('d/m/Y') }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                                Ngày trả phòng
                            </p>

                            <p class="mt-2 text-lg font-bold text-slate-900">
                                {{ $booking->check_out->format('d/m/Y') }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                                Số đêm
                            </p>

                            <p class="mt-2 text-lg font-bold text-slate-900">
                                {{ $booking->number_of_nights }} đêm
                            </p>
                        </div>

                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                                Số khách
                            </p>

                            <p class="mt-2 text-lg font-bold text-slate-900">
                                {{ $booking->number_of_guests }} khách
                            </p>
                        </div>

                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                                Thời gian tạo đơn
                            </p>

                            <p class="mt-2 font-semibold text-slate-900">
                                {{ $booking->created_at->format('H:i d/m/Y') }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                                Cập nhật lần cuối
                            </p>

                            <p class="mt-2 font-semibold text-slate-900">
                                {{ $booking->updated_at->format('H:i d/m/Y') }}
                            </p>
                        </div>

                    </div>

                </section>

                {{-- Thông tin phòng --}}
                <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

                    <div class="border-b border-slate-200 bg-slate-50 px-6 py-4">
                        <h2 class="text-lg font-bold text-slate-900">
                            Thông tin phòng
                        </h2>

                        <p class="mt-1 text-sm text-slate-500">
                            Phòng và Homestay được khách lựa chọn.
                        </p>
                    </div>

                    <div class="p-6">

                        @if ($booking->room)

                            <div class="grid gap-6 sm:grid-cols-2">

                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                                        Tên phòng
                                    </p>

                                    <p class="mt-2 text-lg font-bold text-slate-900">
                                        {{ $booking->room->name }}
                                    </p>
                                </div>

                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                                        Loại phòng
                                    </p>

                                    <p class="mt-2 font-semibold text-slate-900">
                                        {{ $booking->room->room_type ?? 'Chưa cập nhật' }}
                                    </p>
                                </div>

                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                                        Homestay
                                    </p>

                                    <p class="mt-2 font-semibold text-slate-900">
                                        {{ $booking->room->homestay?->name ?? 'Không xác định' }}
                                    </p>
                                </div>

                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                                        Sức chứa
                                    </p>

                                    <p class="mt-2 font-semibold text-slate-900">
                                        {{ $booking->room->capacity ?? 'Chưa cập nhật' }} khách
                                    </p>
                                </div>

                                <div class="sm:col-span-2">
                                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                                        Địa chỉ Homestay
                                    </p>

                                    <p class="mt-2 font-semibold leading-7 text-slate-900">
                                        {{ $booking->room->homestay?->address ?? 'Chưa cập nhật địa chỉ' }}
                                    </p>
                                </div>

                            </div>

                        @else

                            <div class="rounded-xl border border-dashed border-slate-300 bg-slate-50 px-6 py-10 text-center">
                                <p class="font-semibold text-slate-700">
                                    Phòng không còn tồn tại
                                </p>

                                <p class="mt-1 text-sm text-slate-500">
                                    Dữ liệu phòng của Booking này đã bị xóa hoặc không tìm thấy.
                                </p>
                            </div>

                        @endif

                    </div>

                </section>

                {{-- Ghi chú --}}
                <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

                    <div class="border-b border-slate-200 bg-slate-50 px-6 py-4">
                        <h2 class="text-lg font-bold text-slate-900">
                            Ghi chú của khách hàng
                        </h2>
                    </div>

                    <div class="p-6">

                        @if ($booking->note)
                            <p class="whitespace-pre-line leading-7 text-slate-700">
                                {{ $booking->note }}
                            </p>
                        @else
                            <p class="text-sm text-slate-500">
                                Khách hàng không để lại ghi chú.
                            </p>
                        @endif

                    </div>

                </section>

                {{-- Thông tin hủy --}}
                @if ($booking->status === 'cancelled')
                    <section class="overflow-hidden rounded-2xl border border-red-200 bg-white shadow-sm">

                        <div class="border-b border-red-100 bg-red-50 px-6 py-4">
                            <h2 class="text-lg font-bold text-red-700">
                                Thông tin hủy Booking
                            </h2>
                        </div>

                        <div class="grid gap-6 p-6 sm:grid-cols-2">

                            <div>
                                <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                                    Thời gian hủy
                                </p>

                                <p class="mt-2 font-semibold text-slate-900">
                                    {{ $booking->cancelled_at
                                        ? $booking->cancelled_at->format('H:i d/m/Y')
                                        : 'Chưa cập nhật' }}
                                </p>
                            </div>

                            <div>
                                <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                                    Lý do hủy
                                </p>

                                <p class="mt-2 leading-7 text-slate-700">
                                    {{ $booking->cancellation_reason ?: 'Không có lý do hủy.' }}
                                </p>
                            </div>

                        </div>

                    </section>
                @endif

            </div>

            {{-- Cột bên phải --}}
            <aside class="space-y-6">

                {{-- Quản lý Booking --}}
                <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

                    <div class="border-b border-slate-200 bg-slate-50 px-6 py-4">
                        <h2 class="text-lg font-bold text-slate-900">
                            Quản lý Booking
                        </h2>

                        <p class="mt-1 text-sm text-slate-500">
                            Cập nhật trạng thái xử lý của đơn đặt phòng.
                        </p>
                    </div>

                    <div class="space-y-3 p-6">

                        @if ($booking->status === 'pending')

                            <form
                                method="POST"
                                action="{{ route('admin.bookings.update-status', $booking) }}"
                            >
                                @csrf
                                @method('PATCH')

                                <input
                                    type="hidden"
                                    name="status"
                                    value="confirmed"
                                >

                                <button
                                    type="submit"
                                    onclick="return confirm('Xác nhận Booking này?')"
                                    class="inline-flex w-full cursor-pointer items-center justify-center rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-blue-700"
                                >
                                    Xác nhận Booking
                                </button>
                            </form>

                            <form
                                method="POST"
                                action="{{ route('admin.bookings.update-status', $booking) }}"
                            >
                                @csrf
                                @method('PATCH')

                                <input
                                    type="hidden"
                                    name="status"
                                    value="cancelled"
                                >

                                <button
                                    type="submit"
                                    onclick="return confirm('Bạn có chắc muốn hủy Booking này?')"
                                    class="inline-flex w-full cursor-pointer items-center justify-center rounded-xl border border-red-200 bg-red-50 px-5 py-3 text-sm font-semibold text-red-600 transition hover:bg-red-100"
                                >
                                    Hủy Booking
                                </button>
                            </form>

                        @elseif ($booking->status === 'confirmed')

                            <form
                                method="POST"
                                action="{{ route('admin.bookings.update-status', $booking) }}"
                            >
                                @csrf
                                @method('PATCH')

                                <input
                                    type="hidden"
                                    name="status"
                                    value="checked_in"
                                >

                                <button
                                    type="submit"
                                    onclick="return confirm('Xác nhận khách đã nhận phòng?')"
                                    class="inline-flex w-full cursor-pointer items-center justify-center rounded-xl bg-violet-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-violet-700"
                                >
                                    Khách đã nhận phòng
                                </button>
                            </form>

                            <form
                                method="POST"
                                action="{{ route('admin.bookings.update-status', $booking) }}"
                            >
                                @csrf
                                @method('PATCH')

                                <input
                                    type="hidden"
                                    name="status"
                                    value="cancelled"
                                >

                                <button
                                    type="submit"
                                    onclick="return confirm('Bạn có chắc muốn hủy Booking này?')"
                                    class="inline-flex w-full cursor-pointer items-center justify-center rounded-xl border border-red-200 bg-red-50 px-5 py-3 text-sm font-semibold text-red-600 transition hover:bg-red-100"
                                >
                                    Hủy Booking
                                </button>
                            </form>

                        @elseif ($booking->status === 'checked_in')

                            <form
                                method="POST"
                                action="{{ route('admin.bookings.update-status', $booking) }}"
                            >
                                @csrf
                                @method('PATCH')

                                <input
                                    type="hidden"
                                    name="status"
                                    value="completed"
                                >

                                <button
                                    type="submit"
                                    onclick="return confirm('Xác nhận Booking đã hoàn thành?')"
                                    class="inline-flex w-full cursor-pointer items-center justify-center rounded-xl bg-emerald-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-emerald-700"
                                >
                                    Hoàn thành Booking
                                </button>
                            </form>

                        @elseif ($booking->status === 'completed')

                            <div class="rounded-xl bg-emerald-50 p-4">
                                <p class="font-semibold text-emerald-700">
                                    Booking đã hoàn thành
                                </p>

                                <p class="mt-1 text-sm text-emerald-600">
                                    Booking này không còn thao tác cập nhật.
                                </p>
                            </div>

                        @elseif ($booking->status === 'cancelled')

                            <div class="rounded-xl bg-red-50 p-4">
                                <p class="font-semibold text-red-700">
                                    Booking đã bị hủy
                                </p>

                                <p class="mt-1 text-sm text-red-600">
                                    Booking này không còn thao tác cập nhật.
                                </p>
                            </div>

                        @endif

                    </div>

                </section>

                {{-- Chi tiết thanh toán --}}
                <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

                    <div class="border-b border-slate-200 bg-slate-50 px-6 py-4">
                        <h2 class="text-lg font-bold text-slate-900">
                            Chi tiết thanh toán
                        </h2>
                    </div>

                    <div class="space-y-4 p-6">

                        <div class="flex items-center justify-between gap-4">
                            <span class="text-sm text-slate-500">
                                Giá phòng mỗi đêm
                            </span>

                            <span class="text-sm font-semibold text-slate-900">
                                {{ number_format($booking->room_price, 0, ',', '.') }}đ
                            </span>
                        </div>

                        <div class="flex items-center justify-between gap-4">
                            <span class="text-sm text-slate-500">
                                Số đêm
                            </span>

                            <span class="text-sm font-semibold text-slate-900">
                                {{ $booking->number_of_nights }}
                            </span>
                        </div>

                        <div class="flex items-center justify-between gap-4">
                            <span class="text-sm text-slate-500">
                                Tạm tính
                            </span>

                            <span class="text-sm font-semibold text-slate-900">
                                {{ number_format($booking->subtotal, 0, ',', '.') }}đ
                            </span>
                        </div>

                        <div class="flex items-center justify-between gap-4">
                            <span class="text-sm text-slate-500">
                                Phí dịch vụ
                            </span>

                            <span class="text-sm font-semibold text-slate-900">
                                {{ number_format($booking->service_fee, 0, ',', '.') }}đ
                            </span>
                        </div>

                        <div class="flex items-center justify-between gap-4">
                            <span class="text-sm text-slate-500">
                                Giảm giá
                            </span>

                            <span class="text-sm font-semibold text-emerald-600">
                                -{{ number_format($booking->discount_amount, 0, ',', '.') }}đ
                            </span>
                        </div>

                        <div class="border-t border-slate-200 pt-4">

                            <div class="flex items-end justify-between gap-4">
                                <span class="font-bold text-slate-900">
                                    Tổng thanh toán
                                </span>

                                <span class="text-2xl font-bold text-blue-600">
                                    {{ number_format($booking->total_price, 0, ',', '.') }}đ
                                </span>
                            </div>

                        </div>

                    </div>

                </section>

                {{-- Trạng thái thanh toán --}}
                <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

                    <div class="border-b border-slate-200 bg-slate-50 px-6 py-4">
                        <h2 class="text-lg font-bold text-slate-900">
                            Trạng thái thanh toán
                        </h2>
                    </div>

                    <div class="p-6">

                        @switch($booking->payment_status)

                            @case('paid')
                                <div class="rounded-xl bg-emerald-50 p-4">
                                    <p class="font-semibold text-emerald-700">
                                        Đã thanh toán
                                    </p>

                                    <p class="mt-1 text-sm text-emerald-600">
                                        Booking đã được thanh toán thành công.
                                    </p>
                                </div>
                                @break

                            @case('pending')
                                <div class="rounded-xl bg-amber-50 p-4">
                                    <p class="font-semibold text-amber-700">
                                        Đang xử lý
                                    </p>

                                    <p class="mt-1 text-sm text-amber-600">
                                        Giao dịch đang được hệ thống xử lý.
                                    </p>
                                </div>
                                @break

                            @case('refunded')
                                <div class="rounded-xl bg-blue-50 p-4">
                                    <p class="font-semibold text-blue-700">
                                        Đã hoàn tiền
                                    </p>

                                    <p class="mt-1 text-sm text-blue-600">
                                        Khoản thanh toán đã được hoàn lại.
                                    </p>
                                </div>
                                @break

                            @case('failed')
                                <div class="rounded-xl bg-red-50 p-4">
                                    <p class="font-semibold text-red-700">
                                        Thanh toán thất bại
                                    </p>

                                    <p class="mt-1 text-sm text-red-600">
                                        Giao dịch chưa được thực hiện thành công.
                                    </p>
                                </div>
                                @break

                            @default
                                <div class="rounded-xl bg-slate-100 p-4">
                                    <p class="font-semibold text-slate-700">
                                        Chưa thanh toán
                                    </p>

                                    <p class="mt-1 text-sm text-slate-500">
                                        Booking chưa ghi nhận thanh toán.
                                    </p>
                                </div>

                        @endswitch

                    </div>

                </section>

                {{-- Thông tin nhanh --}}
                <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

                    <div class="border-b border-slate-200 bg-slate-50 px-6 py-4">
                        <h2 class="text-lg font-bold text-slate-900">
                            Thông tin nhanh
                        </h2>
                    </div>

                    <div class="space-y-4 p-6">

                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                                Mã Booking
                            </p>

                            <p class="mt-2 font-bold text-blue-600">
                                {{ $booking->booking_code }}
                            </p>
                        </div>

                        <div class="border-t border-slate-100 pt-4">
                            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                                Trạng thái Booking
                            </p>

                            <p class="mt-2 font-semibold text-slate-900">
                                {{ $statusLabels[$booking->status] ?? 'Không xác định' }}
                            </p>
                        </div>

                        <div class="border-t border-slate-100 pt-4">
                            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                                Trạng thái thanh toán
                            </p>

                            <p class="mt-2 font-semibold text-slate-900">
                                {{ $paymentLabels[$booking->payment_status] ?? 'Không xác định' }}
                            </p>
                        </div>

                    </div>

                </section>

            </aside>

        </div>

    </main>

</body>

</html>