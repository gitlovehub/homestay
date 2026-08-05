<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PaymentController extends Controller
{
    /**
     * Danh sách giao dịch thanh toán.
     */
    public function index(Request $request): View
    {
        $search = trim((string) $request->input('search'));
        $status = $request->input('status');
        $bankCode = $request->input('bank_code');

        $payments = Payment::query()
            ->with([
                'booking.room.homestay',
            ])

            // Tìm kiếm
            ->when(
                $search !== '',
                function ($query) use ($search) {
                    $query->where(
                        function ($subQuery) use ($search) {
                            $subQuery
                                ->where(
                                    'transaction_code',
                                    'like',
                                    "%{$search}%"
                                )
                                ->orWhere(
                                    'gateway_transaction_code',
                                    'like',
                                    "%{$search}%"
                                )
                                ->orWhere(
                                    'bank_code',
                                    'like',
                                    "%{$search}%"
                                )
                                ->orWhereHas(
                                    'booking',
                                    function ($bookingQuery) use ($search) {
                                        $bookingQuery
                                            ->where(
                                                'booking_code',
                                                'like',
                                                "%{$search}%"
                                            )
                                            ->orWhere(
                                                'customer_name',
                                                'like',
                                                "%{$search}%"
                                            )
                                            ->orWhere(
                                                'customer_email',
                                                'like',
                                                "%{$search}%"
                                            )
                                            ->orWhere(
                                                'customer_phone',
                                                'like',
                                                "%{$search}%"
                                            );
                                    }
                                );
                        }
                    );
                }
            )

            // Lọc trạng thái thanh toán
            ->when(
                in_array(
                    $status,
                    [
                        'pending',
                        'paid',
                        'failed',
                        'cancelled',
                        'expired',
                        'refunded',
                    ],
                    true
                ),
                fn ($query) => $query->where(
                    'status',
                    $status
                )
            )

            // Lọc ngân hàng
            ->when(
                filled($bankCode),
                fn ($query) => $query->where(
                    'bank_code',
                    $bankCode
                )
            )

            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        /*
         * Các ngân hàng đã xuất hiện trong giao dịch.
         */
        $bankCodes = Payment::query()
            ->whereNotNull('bank_code')
            ->where('bank_code', '!=', '')
            ->distinct()
            ->orderBy('bank_code')
            ->pluck('bank_code');

        /*
         * Thống kê tổng quan.
         */
        $statistics = [
            'total' => Payment::query()->count(),

            'pending' => Payment::query()
                ->where('status', 'pending')
                ->count(),

            'paid' => Payment::query()
                ->where('status', 'paid')
                ->count(),

            'failed' => Payment::query()
                ->whereIn(
                    'status',
                    [
                        'failed',
                        'cancelled',
                        'expired',
                    ]
                )
                ->count(),

            'total_paid_amount' => Payment::query()
                ->where('status', 'paid')
                ->sum('amount'),
        ];

        return view(
            'admin.payments.index',
            compact(
                'payments',
                'bankCodes',
                'statistics'
            )
        );
    }

    /**
     * Chi tiết một giao dịch.
     */
    public function show(Payment $payment): View
    {
        $payment->load([
            'booking.room.homestay',
        ]);

        return view(
            'admin.payments.show',
            compact('payment')
        );
    }
}