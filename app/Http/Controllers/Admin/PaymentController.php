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
        $paymentMethod = $request->input('payment_method');
        $bankCode = trim((string) $request->input('bank_code'));
        $sort = $request->input('sort');

        $paymentMethods = $this->paymentMethods();

        $allowedStatuses = [
            'pending',
            'paid',
            'failed',
            'refunded',
        ];

        $allowedSorts = [
            'oldest',
            'amount_desc',
            'amount_asc',
        ];

        if (! array_key_exists((string) $paymentMethod, $paymentMethods)) {
            $paymentMethod = null;
        }

        if (! in_array($status, $allowedStatuses, true)) {
            $status = null;
        }

        if (! in_array($sort, $allowedSorts, true)) {
            $sort = null;
        }

        $query = Payment::query()
            ->with([
                'booking.room.homestay',
            ])
            ->when(
                $search !== '',
                function ($query) use ($search) {
                    $query->where(function ($subQuery) use ($search) {
                        $subQuery
                            ->where('transaction_code', 'like', "%{$search}%")
                            ->orWhere('gateway_transaction_code', 'like', "%{$search}%")
                            ->orWhere('bank_code', 'like', "%{$search}%")
                            ->orWhere('payment_method', 'like', "%{$search}%")
                            ->orWhere('response_code', 'like', "%{$search}%")
                            ->orWhere('transaction_status', 'like', "%{$search}%")
                            ->orWhereHas('booking', function ($bookingQuery) use ($search) {
                                $bookingQuery
                                    ->where('booking_code', 'like', "%{$search}%")
                                    ->orWhere('customer_name', 'like', "%{$search}%")
                                    ->orWhere('customer_email', 'like', "%{$search}%")
                                    ->orWhere('customer_phone', 'like', "%{$search}%");
                            });
                    });
                }
            )
            ->when(
                $paymentMethod !== null,
                fn ($query) => $query->where('payment_method', $paymentMethod)
            )
            ->when(
                $status !== null,
                fn ($query) => $query->where('status', $status)
            )
            ->when(
                $bankCode !== '',
                fn ($query) => $query->where('bank_code', $bankCode)
            );

        switch ($sort) {
            case 'oldest':
                $query->orderBy('created_at')->orderBy('id');
                break;

            case 'amount_desc':
                $query->orderByDesc('amount')->orderByDesc('id');
                break;

            case 'amount_asc':
                $query->orderBy('amount')->orderByDesc('id');
                break;

            default:
                $query->orderByDesc('created_at')->orderByDesc('id');
                break;
        }

        $payments = $query
            ->paginate(10)
            ->withQueryString();

        /*
         * Chỉ lấy mã ngân hàng từ các giao dịch thực sự có bank_code.
         * Không ép tiền mặt hoặc MoMo phải có ngân hàng.
         */
        $bankCodes = Payment::query()
            ->whereNotNull('bank_code')
            ->where('bank_code', '!=', '')
            ->distinct()
            ->orderBy('bank_code')
            ->pluck('bank_code');

        $statistics = [
            'total' => Payment::count(),

            'total_refunded_amount' => Payment::query()
                ->where('status', 'refunded')
                ->sum('amount'),

            'pending' => Payment::query()
                ->where('status', 'pending')
                ->count(),

            'total_paid_amount' => Payment::query()
                ->where('status', 'paid')
                ->sum('amount'),
        ];

        return view(
            'admin.payments.index',
            compact(
                'payments',
                'paymentMethods',
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

        $paymentMethods = $this->paymentMethods();

        $paymentMethod = $paymentMethods[$payment->payment_method]
            ?? [
                'label' => 'Không xác định',
                'uses_gateway' => false,
                'uses_bank' => false,
            ];

        return view(
            'admin.payments.show',
            compact(
                'payment',
                'paymentMethod'
            )
        );
    }

    /**
     * Cấu hình giao diện theo từng phương thức thanh toán.
     */
    private function paymentMethods(): array
    {
        return [
            'cash' => [
                'label' => 'Tiền mặt',
                'uses_gateway' => false,
                'uses_bank' => false,
            ],

            'bank_transfer' => [
                'label' => 'Banking',
                'uses_gateway' => false,
                'uses_bank' => true,
            ],

            'vnpay' => [
                'label' => 'VNPAY',
                'uses_gateway' => true,
                'uses_bank' => true,
            ],

            'momo' => [
                'label' => 'MoMo',
                'uses_gateway' => true,
                'uses_bank' => false,
            ],
        ];
    }
}