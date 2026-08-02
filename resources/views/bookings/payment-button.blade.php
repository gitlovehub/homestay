{{-- Chép vào trang chi tiết booking phía người dùng. --}}

@php
    $hasPaidPayment = $booking->payments()
        ->where(
            'status',
            \App\Models\Payment::STATUS_PAID
        )
        ->exists();
@endphp

@if (
    !$hasPaidPayment
    && !in_array(
        $booking->status,
        ['cancelled', 'checked_in', 'completed'],
        true
    )
)
    <a
        href="{{ route('payments.checkout', $booking) }}"
        class="inline-flex w-full items-center justify-center rounded-xl bg-blue-600 px-5 py-3 text-sm font-bold text-white transition hover:bg-blue-700"
    >
        Thanh toán qua VNPAY
    </a>
@elseif ($hasPaidPayment)
    <div
        class="rounded-xl border border-green-200 bg-green-50 px-5 py-3 text-center text-sm font-bold text-green-700"
    >
        Đã thanh toán
    </div>
@endif
