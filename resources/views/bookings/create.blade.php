@extends('layouts.app')

@section('title', 'Đặt phòng ' . $room->name . ' | HomeStayGo')

@section('content')

    <main>

        {{-- Breadcrumb --}}
        <section class="border-b border-slate-200 bg-white">

            <div class="mx-auto max-w-7xl px-4 py-5 sm:px-6 lg:px-8">

                <nav
                    class="flex flex-wrap items-center gap-2 text-sm text-slate-500"
                    aria-label="Điều hướng"
                >
                    <a
                        href="{{ route('home') }}"
                        class="font-medium transition hover:text-blue-600"
                    >
                        Trang chủ
                    </a>

                    <span>/</span>

                    <a
                        href="{{ route('homestays.show', $room->homestay->slug) }}"
                        class="font-medium transition hover:text-blue-600"
                    >
                        {{ $room->homestay->name }}
                    </a>

                    <span>/</span>

                    <span class="font-semibold text-slate-800">
                        Đặt phòng
                    </span>
                </nav>

            </div>

        </section>

        {{-- Nội dung chính --}}
        <section class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">

            <div class="mb-8">

                <p class="font-semibold uppercase tracking-widest text-blue-600">
                    Xác nhận đặt phòng
                </p>

                <h1 class="mt-2 text-3xl font-bold tracking-tight text-slate-950 sm:text-4xl">
                    Hoàn tất thông tin của bạn
                </h1>

                <p class="mt-3 text-slate-500">
                    Kiểm tra thông tin phòng và chọn thời gian lưu trú phù hợp.
                </p>

            </div>

            <form
                action="{{ route('bookings.store') }}"
                method="POST"
                class="grid gap-8 lg:grid-cols-[minmax(0,1fr)_380px]"
            >
                @csrf

                <input
                    type="hidden"
                    name="room_id"
                    value="{{ $room->id }}"
                >

                {{-- Cột trái --}}
                <div class="min-w-0 space-y-8">

                    {{-- Thông tin phòng --}}
                    <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">

                        <div class="grid md:grid-cols-[250px_minmax(0,1fr)]">

                            <div class="bg-slate-100">

                                @if ($room->image)
                                    <img
                                        src="{{ Storage::url($room->image) }}"
                                        alt="{{ $room->name }}"
                                        class="h-64 w-full object-cover md:h-full"
                                    >
                                @else
                                    <div class="flex h-64 items-center justify-center text-center md:h-full">

                                        <div>
                                            <div class="text-6xl">
                                                🚪
                                            </div>

                                            <p class="mt-3 text-sm font-medium text-slate-400">
                                                Chưa có ảnh phòng
                                            </p>
                                        </div>

                                    </div>
                                @endif

                            </div>

                            <div class="p-6 sm:p-8">

                                <div class="flex flex-wrap items-center gap-2">

                                    <span class="rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-600">
                                        {{ $room->room_type }}
                                    </span>

                                    <span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-600">
                                        Còn phòng
                                    </span>

                                </div>

                                <h2 class="mt-4 text-2xl font-bold text-slate-900">
                                    {{ $room->name }}
                                </h2>

                                <p class="mt-2 text-sm font-medium text-slate-400">
                                    {{ $room->homestay->name }}
                                </p>

                                <div class="mt-5 grid grid-cols-2 gap-3 sm:grid-cols-3">

                                    <div class="rounded-xl bg-slate-50 p-3">
                                        <p class="text-xs text-slate-400">
                                            Sức chứa
                                        </p>

                                        <p class="mt-1 text-sm font-semibold text-slate-700">
                                            👤 {{ $room->capacity }} khách
                                        </p>
                                    </div>

                                    <div class="rounded-xl bg-slate-50 p-3">
                                        <p class="text-xs text-slate-400">
                                            Số giường
                                        </p>

                                        <p class="mt-1 text-sm font-semibold text-slate-700">
                                            🚪 {{ $room->number_of_beds }} giường
                                        </p>
                                    </div>

                                    <div class="rounded-xl bg-slate-50 p-3">
                                        <p class="text-xs text-slate-400">
                                            Diện tích
                                        </p>

                                        <p class="mt-1 text-sm font-semibold text-slate-700">
                                            📐 {{ $room->area ?? 0 }} m²
                                        </p>
                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                    {{-- Thông tin khách --}}
                    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">

                        <h2 class="text-2xl font-bold text-slate-900">
                            Thông tin khách hàng
                        </h2>

                        <p class="mt-2 text-slate-500">
                            Thông tin này sẽ được dùng để xác nhận đơn đặt phòng.
                        </p>

                        <div class="mt-6 grid gap-5 sm:grid-cols-2">

                            {{-- Họ tên --}}
                            <div class="sm:col-span-2">

                                <label
                                    for="customer_name"
                                    class="mb-2 block text-sm font-semibold text-slate-700"
                                >
                                    Họ và tên
                                    <span class="text-red-500">*</span>
                                </label>

                                <input
                                    id="customer_name"
                                    type="text"
                                    name="customer_name"
                                    value="{{ old('customer_name', auth()->user()->name) }}"
                                    required
                                    autocomplete="name"
                                    placeholder="Nhập họ và tên"
                                    class="h-12 w-full rounded-xl border px-4 py-3 outline-none transition placeholder:text-slate-400 focus:ring-4
                                        {{ $errors->has('customer_name')
                                            ? 'border-red-400 focus:border-red-500 focus:ring-red-100'
                                            : 'border-slate-300 focus:border-blue-500 focus:ring-blue-100' }}"
                                >

                                @error('customer_name')
                                    <p class="mt-2 text-sm font-medium text-red-500">
                                        {{ $message }}
                                    </p>
                                @enderror

                            </div>

                            {{-- Email --}}
                            <div>

                                <label
                                    for="customer_email"
                                    class="mb-2 block text-sm font-semibold text-slate-700"
                                >
                                    Email
                                    <span class="text-red-500">*</span>
                                </label>

                                <input
                                    id="customer_email"
                                    type="email"
                                    name="customer_email"
                                    value="{{ old('customer_email', auth()->user()->email) }}"
                                    required
                                    autocomplete="email"
                                    placeholder="example@email.com"
                                    class="h-12w-full rounded-xl border px-4 py-3 outline-none transition placeholder:text-slate-400 focus:ring-4
                                        {{ $errors->has('customer_email')
                                            ? 'border-red-400 focus:border-red-500 focus:ring-red-100'
                                            : 'border-slate-300 focus:border-blue-500 focus:ring-blue-100' }}"
                                >

                                @error('customer_email')
                                    <p class="mt-2 text-sm font-medium text-red-500">
                                        {{ $message }}
                                    </p>
                                @enderror

                            </div>

                            {{-- Số điện thoại --}}
                            <div>

                                <label
                                    for="customer_phone"
                                    class="mb-2 block text-sm font-semibold text-slate-700"
                                >
                                    Số điện thoại
                                    <span class="text-red-500">*</span>
                                </label>

                                <input
                                    id="customer_phone"
                                    type="tel"
                                    name="customer_phone"
                                    value="{{ old('customer_phone', auth()->user()->phone) }}"
                                    required
                                    autocomplete="tel"
                                    placeholder="Nhập số điện thoại"
                                    class="h-12w-full rounded-xl border px-4 py-3 outline-none transition placeholder:text-slate-400 focus:ring-4
                                        {{ $errors->has('customer_phone')
                                            ? 'border-red-400 focus:border-red-500 focus:ring-red-100'
                                            : 'border-slate-300 focus:border-blue-500 focus:ring-blue-100' }}"
                                >

                                @error('customer_phone')
                                    <p class="mt-2 text-sm font-medium text-red-500">
                                        {{ $message }}
                                    </p>
                                @enderror

                            </div>

                        </div>

                    </div>

                    {{-- Thời gian lưu trú --}}
                    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">

                        <h2 class="text-2xl font-bold text-slate-900">
                            Thời gian lưu trú
                        </h2>

                        <p class="mt-2 text-slate-500">
                            Ngày trả phòng phải sau ngày nhận phòng ít nhất 1 ngày.
                        </p>

                        <div class="mt-6 grid gap-5 sm:grid-cols-2">

                            {{-- Check-in --}}
                            <div>

                                <label
                                    for="check_in"
                                    class="mb-2 block text-sm font-semibold text-slate-700"
                                >
                                    Ngày nhận phòng
                                    <span class="text-red-500">*</span>
                                </label>

                                <input
                                    id="check_in"
                                    type="date"
                                    name="check_in"
                                    value="{{ old('check_in') }}"
                                    required
                                    class="h-12w-full cursor-pointer rounded-xl border px-4 py-3 text-slate-700 outline-none transition focus:ring-4
                                        {{ $errors->has('check_in')
                                            ? 'border-red-400 focus:border-red-500 focus:ring-red-100'
                                            : 'border-slate-300 focus:border-blue-500 focus:ring-blue-100' }}"
                                >

                                @error('check_in')
                                    <p class="mt-2 text-sm font-medium text-red-500">
                                        {{ $message }}
                                    </p>
                                @enderror

                            </div>

                            {{-- Check-out --}}
                            <div>

                                <label
                                    for="check_out"
                                    class="mb-2 block text-sm font-semibold text-slate-700"
                                >
                                    Ngày trả phòng
                                    <span class="text-red-500">*</span>
                                </label>

                                <input
                                    id="check_out"
                                    type="date"
                                    name="check_out"
                                    value="{{ old('check_out') }}"
                                    required
                                    class="h-12 w-full cursor-pointer rounded-xl border px-4 py-3 text-slate-700 outline-none transition focus:ring-4
                                        {{ $errors->has('check_out')
                                            ? 'border-red-400 focus:border-red-500 focus:ring-red-100'
                                            : 'border-slate-300 focus:border-blue-500 focus:ring-blue-100' }}"
                                >

                                @error('check_out')
                                    <p class="mt-2 text-sm font-medium text-red-500">
                                        {{ $message }}
                                    </p>
                                @enderror

                            </div>

                            {{-- Số khách --}}
                            <div class="sm:col-span-2">

                                <label
                                    for="number_of_guests"
                                    class="mb-2 block text-sm font-semibold text-slate-700"
                                >
                                    Số lượng khách
                                    <span class="text-red-500">*</span>
                                </label>

                                <select
                                    id="number_of_guests"
                                    name="number_of_guests"
                                    required
                                    class="w-full cursor-pointer rounded-xl border bg-white px-4 py-3 text-slate-700 outline-none transition focus:ring-4
                                        {{ $errors->has('number_of_guests')
                                            ? 'border-red-400 focus:border-red-500 focus:ring-red-100'
                                            : 'border-slate-300 focus:border-blue-500 focus:ring-blue-100' }}"
                                >
                                    @for ($guest = 1; $guest <= $room->capacity; $guest++)
                                        <option
                                            value="{{ $guest }}"
                                            @selected(old('number_of_guests', 1) == $guest)
                                        >
                                            {{ $guest }} khách
                                        </option>
                                    @endfor
                                </select>

                                <p class="mt-2 text-xs text-slate-400">
                                    Phòng chứa tối đa {{ $room->capacity }} khách.
                                </p>

                                @error('number_of_guests')
                                    <p class="mt-2 text-sm font-medium text-red-500">
                                        {{ $message }}
                                    </p>
                                @enderror

                            </div>

                        </div>

                    </div>

                    {{-- Ghi chú --}}
                    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">

                        <label
                            for="note"
                            class="block text-2xl font-bold text-slate-900"
                        >
                            Ghi chú
                        </label>

                        <p class="mt-2 text-slate-500">
                            Bạn có thể gửi yêu cầu đặc biệt cho Homestay.
                        </p>

                        <textarea
                            id="note"
                            name="note"
                            rows="5"
                            maxlength="2000"
                            placeholder="Ví dụ: Nhận phòng muộn, cần thêm gối..."
                            class="mt-6 w-full resize-none rounded-xl border px-4 py-3 leading-7 outline-none transition placeholder:text-slate-400 focus:ring-4
                                {{ $errors->has('note')
                                    ? 'border-red-400 focus:border-red-500 focus:ring-red-100'
                                    : 'border-slate-300 focus:border-blue-500 focus:ring-blue-100' }}"
                        >{{ old('note') }}</textarea>

                        <div class="mt-2 flex items-center justify-between gap-4">

                            @error('note')
                                <p class="text-sm font-medium text-red-500">
                                    {{ $message }}
                                </p>
                            @else
                                <p class="text-xs text-slate-400">
                                    Không bắt buộc.
                                </p>
                            @enderror

                            <span
                                id="note-counter"
                                class="text-xs font-medium text-slate-400"
                            >
                                0/2000
                            </span>

                        </div>

                    </div>

                </div>

                {{-- Cột tổng tiền --}}
                <aside class="lg:sticky lg:top-24 lg:self-start">

                    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-lg">

                        <div class="border-b border-slate-200 pb-5">

                            <p class="text-sm font-medium text-slate-500">
                                Giá phòng
                            </p>

                            <div class="mt-2 flex items-end gap-2">

                                <p class="text-3xl font-bold text-blue-600">
                                    {{ number_format(
                                        $room->price_per_night,
                                        0,
                                        ',',
                                        '.'
                                    ) }}đ
                                </p>

                                <span class="pb-1 text-sm text-slate-500">
                                    / đêm
                                </span>

                            </div>

                        </div>

                        <div class="mt-6 space-y-4">

                            <div class="flex items-center justify-between gap-4 text-sm">

                                <span class="text-slate-500">
                                    Ngày nhận
                                </span>

                                <span
                                    id="summary-check-in"
                                    class="font-semibold text-slate-800"
                                >
                                    Chưa chọn
                                </span>

                            </div>

                            <div class="flex items-center justify-between gap-4 text-sm">

                                <span class="text-slate-500">
                                    Ngày trả
                                </span>

                                <span
                                    id="summary-check-out"
                                    class="font-semibold text-slate-800"
                                >
                                    Chưa chọn
                                </span>

                            </div>

                            <div class="flex items-center justify-between gap-4 text-sm">

                                <span class="text-slate-500">
                                    Số đêm
                                </span>

                                <span
                                    id="summary-nights"
                                    class="font-semibold text-slate-800"
                                >
                                    0 đêm
                                </span>

                            </div>

                            <div class="flex items-center justify-between gap-4 text-sm">

                                <span class="text-slate-500">
                                    Số khách
                                </span>

                                <span
                                    id="summary-guests"
                                    class="font-semibold text-slate-800"
                                >
                                    1 khách
                                </span>

                            </div>

                        </div>

                        <div class="mt-6 border-t border-slate-200 pt-5">

                            <div class="flex items-center justify-between gap-4 text-sm">

                                <span class="text-slate-500">
                                    Tiền phòng
                                </span>

                                <span
                                    id="summary-subtotal"
                                    class="font-semibold text-slate-800"
                                >
                                    0đ
                                </span>

                            </div>

                            <div class="mt-4 flex items-center justify-between gap-4">

                                <span class="font-bold text-slate-900">
                                    Tổng cộng
                                </span>

                                <span
                                    id="summary-total"
                                    class="text-2xl font-bold text-blue-600"
                                >
                                    0đ
                                </span>

                            </div>

                        </div>

                        <button
                            id="submit-booking"
                            type="submit"
                            class="mt-6 inline-flex w-full cursor-pointer items-center justify-center rounded-xl bg-blue-600 px-6 py-3.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-200 disabled:cursor-not-allowed disabled:bg-slate-300 disabled:shadow-none"
                        >
                            Xác nhận đặt phòng
                        </button>

                        <p class="mt-4 text-center text-xs leading-5 text-slate-400">
                            Đơn đặt phòng sẽ ở trạng thái chờ xác nhận.
                        </p>

                    </div>

                </aside>

            </form>

        </section>

    </main>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const checkInInput = document.getElementById('check_in');
            const checkOutInput = document.getElementById('check_out');
            const guestsInput = document.getElementById('number_of_guests');
            const noteInput = document.getElementById('note');
            const noteCounter = document.getElementById('note-counter');
            const submitButton = document.getElementById('submit-booking');

            const summaryCheckIn = document.getElementById('summary-check-in');
            const summaryCheckOut = document.getElementById('summary-check-out');
            const summaryNights = document.getElementById('summary-nights');
            const summaryGuests = document.getElementById('summary-guests');
            const summarySubtotal = document.getElementById('summary-subtotal');
            const summaryTotal = document.getElementById('summary-total');

            const roomPrice = Number(@json($room->price_per_night));
            const today = new Date();
            today.setHours(0, 0, 0, 0);

            const formatInputDate = (date) => {
                const year = date.getFullYear();
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const day = String(date.getDate()).padStart(2, '0');

                return `${year}-${month}-${day}`;
            };

            const formatDisplayDate = (value) => {
                if (!value) {
                    return 'Chưa chọn';
                }

                return new Intl.DateTimeFormat('vi-VN').format(
                    new Date(`${value}T00:00:00`)
                );
            };

            const formatMoney = (value) => {
                return new Intl.NumberFormat('vi-VN').format(value) + 'đ';
            };

            const addDays = (value, days) => {
                const date = new Date(`${value}T00:00:00`);
                date.setDate(date.getDate() + days);

                return formatInputDate(date);
            };

            const calculateBooking = () => {
                const checkInValue = checkInInput.value;
                const checkOutValue = checkOutInput.value;
                const guests = Number(guestsInput.value || 1);

                summaryCheckIn.textContent = formatDisplayDate(checkInValue);
                summaryCheckOut.textContent = formatDisplayDate(checkOutValue);
                summaryGuests.textContent = `${guests} khách`;

                if (!checkInValue || !checkOutValue) {
                    summaryNights.textContent = '0 đêm';
                    summarySubtotal.textContent = '0đ';
                    summaryTotal.textContent = '0đ';
                    submitButton.disabled = true;

                    return;
                }

                const checkIn = new Date(`${checkInValue}T00:00:00`);
                const checkOut = new Date(`${checkOutValue}T00:00:00`);
                const millisecondsPerDay = 1000 * 60 * 60 * 24;
                const nights = Math.round(
                    (checkOut - checkIn) / millisecondsPerDay
                );

                if (nights <= 0) {
                    summaryNights.textContent = '0 đêm';
                    summarySubtotal.textContent = '0đ';
                    summaryTotal.textContent = '0đ';
                    submitButton.disabled = true;

                    return;
                }

                const subtotal = roomPrice * nights;

                summaryNights.textContent = `${nights} đêm`;
                summarySubtotal.textContent = formatMoney(subtotal);
                summaryTotal.textContent = formatMoney(subtotal);
                submitButton.disabled = false;
            };

            checkInInput.min = formatInputDate(today);

            if (checkInInput.value) {
                checkOutInput.min = addDays(checkInInput.value, 1);
            }

            checkInInput.addEventListener('change', () => {
                if (!checkInInput.value) {
                    checkOutInput.min = '';
                    checkOutInput.value = '';
                    calculateBooking();

                    return;
                }

                const minimumCheckOut = addDays(checkInInput.value, 1);
                checkOutInput.min = minimumCheckOut;

                if (
                    !checkOutInput.value
                    || checkOutInput.value < minimumCheckOut
                ) {
                    checkOutInput.value = minimumCheckOut;
                }

                calculateBooking();
            });

            checkOutInput.addEventListener('change', calculateBooking);
            guestsInput.addEventListener('change', calculateBooking);

            const updateNoteCounter = () => {
                noteCounter.textContent =
                    `${noteInput.value.length}/2000`;
            };

            noteInput.addEventListener('input', updateNoteCounter);

            updateNoteCounter();
            calculateBooking();
        });
    </script>

@endsection
