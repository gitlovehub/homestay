<?php

namespace App\Services;

use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use RuntimeException;

class VnpayService
{
    /**
     * Tạo URL chuyển hướng người dùng sang VNPAY.
     */
    public function createPaymentUrl(
        Payment $payment,
        Request $request
    ): string {
        $this->ensureConfigured();

        $now = Carbon::now(
            'Asia/Ho_Chi_Minh'
        );

        $expireAt = $now
            ->copy()
            ->addMinutes(
                (int) config(
                    'services.vnpay.expire_minutes',
                    15
                )
            );

        $params = [
            'vnp_Version' => config(
                'services.vnpay.version',
                '2.1.0'
            ),

            'vnp_Command' => 'pay',

            'vnp_TmnCode' => config(
                'services.vnpay.tmn_code'
            ),

            /*
             * VNPAY yêu cầu số tiền nhân 100.
             */
            'vnp_Amount' =>
                (int) $payment->amount * 100,

            'vnp_CurrCode' => 'VND',

            'vnp_TxnRef' =>
                $payment->transaction_ref,

            'vnp_OrderInfo' =>
                'Thanh toan booking '
                . $payment->booking_id,

            'vnp_OrderType' => config(
                'services.vnpay.order_type',
                'other'
            ),

            'vnp_Locale' => config(
                'services.vnpay.locale',
                'vn'
            ),

            'vnp_ReturnUrl' => config(
                'services.vnpay.return_url'
            ),

            'vnp_IpAddr' =>
                $request->ip()
                ?: '127.0.0.1',

            'vnp_CreateDate' =>
                $now->format('YmdHis'),

            'vnp_ExpireDate' =>
                $expireAt->format('YmdHis'),
        ];

        $params = array_filter(
            $params,
            static fn (mixed $value): bool =>
                $value !== null
                && $value !== ''
        );

        ksort($params);

        $hashData =
            $this->buildQueryString($params);

        $secureHash = hash_hmac(
            'sha512',
            $hashData,
            (string) config(
                'services.vnpay.hash_secret'
            )
        );

        return rtrim(
            (string) config(
                'services.vnpay.payment_url'
            ),
            '?'
        )
            . '?'
            . $hashData
            . '&vnp_SecureHash='
            . $secureHash;
    }

    /**
     * Kiểm tra chữ ký Return URL hoặc IPN.
     */
    public function verifySignature(
        array $responseData
    ): bool {
        $this->ensureConfigured();

        $responseData =
            $this->onlyVnpayData(
                $responseData
            );

        $receivedHash = (string) (
            $responseData['vnp_SecureHash']
            ?? ''
        );

        if ($receivedHash === '') {
            return false;
        }

        unset(
            $responseData['vnp_SecureHash'],
            $responseData[
                'vnp_SecureHashType'
            ]
        );

        $responseData = array_filter(
            $responseData,
            static fn (mixed $value): bool =>
                $value !== null
                && $value !== ''
        );

        ksort($responseData);

        $hashData =
            $this->buildQueryString(
                $responseData
            );

        $calculatedHash = hash_hmac(
            'sha512',
            $hashData,
            (string) config(
                'services.vnpay.hash_secret'
            )
        );

        return hash_equals(
            strtolower($calculatedHash),
            strtolower($receivedHash)
        );
    }

    /**
     * Chỉ lấy tham số có tiền tố vnp_.
     */
    public function onlyVnpayData(
        array $data
    ): array {
        return array_filter(
            $data,
            static fn (
                string $key
            ): bool => str_starts_with(
                $key,
                'vnp_'
            ),
            ARRAY_FILTER_USE_KEY
        );
    }

    /**
     * Tạo chuỗi tham số dùng để ký.
     */
    private function buildQueryString(
        array $params
    ): string {
        $parts = [];

        foreach ($params as $key => $value) {
            $parts[] =
                urlencode((string) $key)
                . '='
                . urlencode((string) $value);
        }

        return implode('&', $parts);
    }

    /**
     * Kiểm tra cấu hình bắt buộc.
     */
    private function ensureConfigured(): void
    {
        $requiredConfigs = [
            'services.vnpay.tmn_code',
            'services.vnpay.hash_secret',
            'services.vnpay.payment_url',
            'services.vnpay.return_url',
        ];

        foreach ($requiredConfigs as $key) {
            $value = config($key);

            if (
                ! is_string($value)
                || trim($value) === ''
            ) {
                throw new RuntimeException(
                    'Thiếu cấu hình VNPAY: '
                    . $key
                );
            }
        }
    }
}