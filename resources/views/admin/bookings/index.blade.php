<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Quản lý Booking | HomeStay</title>

    @vite([
        'resources/css/app.css',
        'resources/js/app.js',
    ])
</head>

<body class="min-h-screen bg-slate-100">

    @include('partials.navbar')

    @php
        $currentYear = now()->year;
    @endphp

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
            href="{{ route('admin.dashboard') }}"
            class="mb-4 block text-sm font-semibold text-blue-600 transition hover:text-blue-700"
        >
            ← Quay lại bảng điều khiển
        </a>

        {{-- Tiêu đề và bộ lọc --}}
        <div class="mb-8 flex flex-col gap-5 xl:flex-row xl:items-end xl:justify-between">

            <div>
                <h1 class="text-3xl font-bold text-slate-900">
                    Quản lý Booking
                </h1>

                <p class="mt-2 text-slate-500">
                    Danh sách các đơn đặt phòng trong hệ thống.
                </p>
            </div>

            <form
                method="GET"
                action="{{ route('admin.bookings.index') }}"
                class="flex flex-col gap-3 sm:flex-row sm:flex-wrap sm:items-center"
            >

                {{-- Tìm kiếm --}}
                <input
                    type="search"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Mã đơn, tên khách, SĐT..."
                    class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 sm:w-64"
                    onsearch="this.form.submit()"
                    oninput="if(this.value === '') this.form.submit()"
                >

                {{-- Lọc trạng thái Booking --}}
                <select
                    name="status"
                    class="cursor-pointer rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                    onchange="this.form.submit()"
                >
                    <option value="">
                        Tất cả trạng thái
                    </option>

                    @foreach ($statusLabels as $value => $label)
                        <option
                            value="{{ $value }}"
                            @selected(request('status') === $value)
                        >
                            {{ $label }}
                        </option>
                    @endforeach
                </select>

                {{-- Lọc thanh toán --}}
                <select
                    name="payment_status"
                    class="cursor-pointer rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                    onchange="this.form.submit()"
                >
                    <option value="">
                        Tất cả thanh toán
                    </option>

                    @foreach ($paymentLabels as $value => $label)
                        <option
                            value="{{ $value }}"
                            @selected(request('payment_status') === $value)
                        >
                            {{ $label }}
                        </option>
                    @endforeach
                </select>

                {{-- Đặt lại --}}
                @if (
                    request()->filled('search')
                    || request()->filled('status')
                    || request()->filled('payment_status')
                )
                    <a
                        href="{{ route('admin.bookings.index') }}"
                        class="inline-flex items-center justify-center whitespace-nowrap rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm font-semibold text-slate-600 transition hover:border-blue-400 hover:text-blue-600"
                    >
                        Đặt lại
                    </a>
                @endif

            </form>

        </div>

        {{-- Bảng Booking --}}
        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">

            <div class="overflow-x-auto rounded-2xl">

                <table class="min-w-full min-h-120 divide-y divide-slate-200">

                    <thead class="bg-slate-50">

                        <tr>
                            <th class="px-5 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">
                                Mã Booking
                            </th>

                            <th class="px-5 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">
                                Khách hàng
                            </th>

                            <th class="px-5 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">
                                Phòng
                            </th>

                            <th class="px-5 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">
                                Lưu trú
                            </th>

                            <th class="px-5 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">
                                Tổng tiền
                            </th>

                            <th class="px-5 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">
                                Trạng thái
                            </th>

                            <th class="px-5 py-4 text-center text-xs font-bold uppercase tracking-wider text-slate-500">
                                Thao tác
                            </th>

                        </tr>

                    </thead>

                    <tbody class="divide-y divide-slate-100 bg-white">

                        @forelse ($bookings as $booking)

                            <tr class="transition hover:bg-slate-50">

                                {{-- Mã Booking --}}
                                <td class="whitespace-nowrap px-5 py-5">

                                    <p class="font-bold text-blue-600">
                                        {{ $booking->booking_code }}
                                    </p>

                                    <p class="mt-1 text-xs text-slate-400">
                                        {{ $booking->created_at->year == $currentYear
                                            ? $booking->created_at->format('H:i d/m')
                                            : $booking->created_at->format('H:i d/m/Y') }}
                                    </p>

                                </td>

                                {{-- Khách hàng --}}
                                <td class="px-5 py-5">

                                    <div class="min-w-36">

                                        <p class="font-semibold text-slate-900">
                                            {{ $booking->customer_name }}
                                        </p>

                                        <p class="mt-1 text-xs text-slate-500">
                                            {{ $booking->customer_phone }}
                                        </p>

                                    </div>

                                </td>

                                {{-- Phòng --}}
                                <td class="px-5 py-5">

                                    <div class="max-w-52">

                                        <p class="truncate font-semibold text-slate-900">
                                            {{ $booking->room?->name ?? 'Phòng không tồn tại' }}
                                        </p>

                                        <p class="mt-1 truncate text-xs text-slate-500">
                                            {{ $booking->room?->homestay?->name ?? 'Homestay không xác định' }}
                                        </p>

                                    </div>

                                </td>

                                {{-- Thời gian lưu trú --}}
                                <td class="whitespace-nowrap px-5 py-5">

                                    <p class="text-sm font-semibold text-slate-700">
                                        {{ $booking->check_in->year == $currentYear
                                            ? $booking->check_in->format('d/m')
                                            : $booking->check_in->format('d/m/Y') }}

                                        <span class="mx-1 text-slate-400">
                                            →
                                        </span>

                                        {{ $booking->check_out->year == $currentYear
                                            ? $booking->check_out->format('d/m')
                                            : $booking->check_out->format('d/m/Y') }}
                                    </p>

                                    <p class="mt-1 text-xs text-slate-400">
                                        {{ $booking->number_of_nights }} đêm ·
                                        {{ $booking->number_of_guests }} khách
                                    </p>

                                </td>

                                {{-- Tổng tiền --}}
                                <td class="whitespace-nowrap px-5 py-5">

                                    <p class="font-bold text-slate-900">
                                        {{ number_format($booking->total_price, 0, ',', '.') }}đ
                                    </p>

                                    <p class="mt-1 text-xs text-slate-400">
                                        {{ $paymentLabels[$booking->payment_status] ?? 'Không xác định' }}
                                    </p>

                                </td>

                                {{-- Trạng thái --}}
                                <td class="whitespace-nowrap px-5 py-5">

                                    @switch($booking->status)

                                        @case('pending')
                                            <span class="inline-flex items-center gap-2 rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700">
                                                <span class="h-2 w-2 rounded-full bg-amber-500"></span>
                                                Chờ xác nhận
                                            </span>
                                            @break

                                        @case('confirmed')
                                            <span class="inline-flex items-center gap-2 rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-700">
                                                <span class="h-2 w-2 rounded-full bg-blue-500"></span>
                                                Đã xác nhận
                                            </span>
                                            @break

                                        @case('checked_in')
                                            <span class="inline-flex items-center gap-2 rounded-full bg-violet-100 px-3 py-1 text-xs font-semibold text-violet-700">
                                                <span class="h-2 w-2 rounded-full bg-violet-500"></span>
                                                Đã nhận phòng
                                            </span>
                                            @break

                                        @case('completed')
                                            <span class="inline-flex items-center gap-2 rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">
                                                <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                                                Đã hoàn thành
                                            </span>
                                            @break

                                        @case('cancelled')
                                            <span class="inline-flex items-center gap-2 rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-700">
                                                <span class="h-2 w-2 rounded-full bg-red-500"></span>
                                                Đã hủy
                                            </span>
                                            @break

                                        @default
                                            <span class="inline-flex items-center gap-2 rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600">
                                                <span class="h-2 w-2 rounded-full bg-slate-400"></span>
                                                Không xác định
                                            </span>

                                    @endswitch

                                </td>

                                {{-- Menu thao tác --}}
                                <td class="whitespace-nowrap px-5 py-5 text-center">

                                    <details class="group relative inline-block text-left">

                                        {{-- Nút ba chấm --}}
                                        <summary
                                            class="flex h-9 w-9 cursor-pointer list-none items-center justify-center rounded-lg border border-slate-200 bg-white text-lg font-bold text-slate-600 transition hover:border-blue-300 hover:bg-blue-50 hover:text-blue-600"
                                            title="Thao tác"
                                        >
                                            ⋮
                                        </summary>

                                        {{-- Menu --}}
                                        <div class="absolute right-0 z-50 mt-2 w-48 overflow-hidden rounded-xl border border-slate-200 bg-white text-left shadow-xl">

                                            {{-- Xem chi tiết --}}
                                            <a
                                                href="{{ route('admin.bookings.show', $booking) }}"
                                                class="flex items-center gap-3 px-4 py-3 text-sm font-medium text-slate-700 transition hover:bg-slate-100"
                                            >
                                                <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-slate-100">
                                                    👁
                                                </span>

                                                Xem chi tiết
                                            </a>

                                            {{-- Trạng thái Pending --}}
                                            @if ($booking->status === 'pending')

                                                <div class="my-1 border-t border-slate-100"></div>

                                                <form
                                                    method="POST"
                                                    action="{{ route('admin.bookings.update-status', $booking) }}"
                                                    onsubmit="return confirm('Bạn có chắc muốn xác nhận đơn {{ $booking->booking_code }} không?')"
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
                                                        class="flex w-full cursor-pointer items-center gap-3 px-4 py-3 text-left text-sm font-medium text-blue-700 transition hover:bg-blue-50"
                                                    >
                                                        <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-blue-50">
                                                            ✓
                                                        </span>

                                                        Xác nhận đơn
                                                    </button>

                                                </form>

                                                <form
                                                    method="POST"
                                                    action="{{ route('admin.bookings.update-status', $booking) }}"
                                                    onsubmit="return confirm('Bạn có chắc muốn hủy đơn {{ $booking->booking_code }} không?')"
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
                                                        class="flex w-full cursor-pointer items-center gap-3 px-4 py-3 text-left text-sm font-medium text-red-700 transition hover:bg-red-50"
                                                    >
                                                        <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-red-50">
                                                            ✕
                                                        </span>

                                                        Hủy Booking
                                                    </button>

                                                </form>

                                            @endif

                                            {{-- Trạng thái Confirmed --}}
                                            @if ($booking->status === 'confirmed')

                                                <div class="my-1 border-t border-slate-100"></div>

                                                <form
                                                    method="POST"
                                                    action="{{ route('admin.bookings.update-status', $booking) }}"
                                                    onsubmit="return confirm('Xác nhận khách đã nhận phòng?')"
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
                                                        class="flex w-full cursor-pointer items-center gap-3 px-4 py-3 text-left text-sm font-medium text-violet-700 transition hover:bg-violet-50"
                                                    >
                                                        <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-violet-50">
                                                            🏨
                                                        </span>

                                                        Đã nhận phòng
                                                    </button>

                                                </form>

                                                <form
                                                    method="POST"
                                                    action="{{ route('admin.bookings.update-status', $booking) }}"
                                                    onsubmit="return confirm('Bạn có chắc muốn hủy đơn {{ $booking->booking_code }} không?')"
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
                                                        class="flex w-full cursor-pointer items-center gap-3 px-4 py-3 text-left text-sm font-medium text-red-700 transition hover:bg-red-50"
                                                    >
                                                        <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-red-50">
                                                            ✕
                                                        </span>

                                                        Hủy Booking
                                                    </button>

                                                </form>

                                            @endif

                                            {{-- Trạng thái Checked In --}}
                                            @if ($booking->status === 'checked_in')

                                                <div class="my-1 border-t border-slate-100"></div>

                                                <form
                                                    method="POST"
                                                    action="{{ route('admin.bookings.update-status', $booking) }}"
                                                    onsubmit="return confirm('Xác nhận đơn này đã hoàn thành?')"
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
                                                        class="flex w-full cursor-pointer items-center gap-3 px-4 py-3 text-left text-sm font-medium text-emerald-700 transition hover:bg-emerald-50"
                                                    >
                                                        <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-emerald-50">
                                                            ✓
                                                        </span>

                                                        Hoàn thành
                                                    </button>

                                                </form>

                                            @endif

                                        </div>

                                    </details>

                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td
                                    colspan="7"
                                    class="px-6 py-14 text-center"
                                >

                                    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-slate-100 text-3xl">
                                        📅
                                    </div>

                                    <p class="mt-4 font-semibold text-slate-700">
                                        Chưa có đơn đặt phòng
                                    </p>

                                    <p class="mt-1 text-sm text-slate-500">
                                        Chưa tìm thấy Booking phù hợp trong hệ thống.
                                    </p>

                                    @if (
                                        request()->filled('search')
                                        || request()->filled('status')
                                        || request()->filled('payment_status')
                                    )
                                        <a
                                            href="{{ route('admin.bookings.index') }}"
                                            class="mt-5 inline-flex rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-blue-700"
                                        >
                                            Xóa bộ lọc
                                        </a>
                                    @endif

                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

            {{-- Phân trang --}}
            @if ($bookings->hasPages())
                <div class="border-t border-slate-200 px-6 py-5">
                    {{ $bookings->links() }}
                </div>
            @endif

        </div>

    </main>

    {{-- Đóng menu khác khi mở một menu --}}
    <script>
        document.querySelectorAll('details').forEach((details) => {
            details.addEventListener('toggle', () => {
                if (!details.open) {
                    return;
                }

                document.querySelectorAll('details[open]').forEach((item) => {
                    if (item !== details) {
                        item.removeAttribute('open');
                    }
                });
            });
        });

        document.addEventListener('click', (event) => {
            document.querySelectorAll('details[open]').forEach((details) => {
                if (!details.contains(event.target)) {
                    details.removeAttribute('open');
                }
            });
        });
    </script>

</body>

</html>