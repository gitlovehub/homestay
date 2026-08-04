<?php

namespace App\Services;

use App\Models\Payment;
use Carbon\CarbonImmutable;
use Illuminate\Support\Str;
use InvalidArgumentException;
use RuntimeException;

class VnpayService
{
    /**
     * Các phương thức thanh toán được VNPAY hỗ trợ trong giao diện hiện tại.
     */
    private const ALLOWED_BANK_CODES = [
        'VNPAYQR',
        'VNBANK',
        'INTCARD',
    ];

    private string $version;

    private string $tmnCode;

    private string $hashSecret;

    private string $paymentUrl;

    private string $returnUrl;

    private string $locale;

    private int $expireMinutes;

    public function __construct()
    {
        $this->version = trim(
            (string) config(
                'services.vnpay.version',
                '2.1.0'
            )
        );

        $this->tmnCode = trim(
            (string) config(
                'services.vnpay.tmn_code'
            )
        );

        $this->hashSecret = trim(
            (string) config(
                'services.vnpay.hash_secret'
            )
        );

        $this->paymentUrl = rtrim(
            trim(
                (string) config(
                    'services.vnpay.payment_url'
                )
            ),
            '?&'
        );

        $this->returnUrl = trim(
            (string) config(
                'services.vnpay.return_url'
            )
        );

        $this->locale = $this->normalizeLocale(
            (string) config(
                'services.vnpay.locale',
                'vn'
            )
        );

        $this->expireMinutes = max(
            1,
            (int) config(
                'services.vnpay.expire_minutes',
                15
            )
        );
    }

    /**
     * Tạo URL chuyển người dùng sang cổng thanh toán VNPAY.
     */
    public function createPaymentUrl(
        Payment $payment,
        string $ipAddress,
        ?string $bankCode = null
    ): string {
        $this->ensureConfigured();

        $payment->loadMissing('booking');

        if (!$payment->booking) {
            throw new RuntimeException(
                'Không tìm thấy đơn đặt phòng của giao dịch.'
            );
        }

        if ($payment->status !== 'pending') {
            throw new RuntimeException(
                'Giao dịch không còn ở trạng thái chờ thanh toán.'
            );
        }

        if (
            !$payment->transaction_code
            || trim($payment->transaction_code) === ''
        ) {
            throw new RuntimeException(
                'Giao dịch chưa có mã tham chiếu.'
            );
        }

        if ((int) $payment->amount <= 0) {
            throw new RuntimeException(
                'Số tiền thanh toán không hợp lệ.'
            );
        }

        $bankCode = $this->normalizeBankCode(
            $bankCode
        );

        $now = CarbonImmutable::now(
            'Asia/Ho_Chi_Minh'
        );

        $expiresAt = $payment->expired_at
            ? CarbonImmutable::instance(
                $payment->expired_at
            )->setTimezone('Asia/Ho_Chi_Minh')
            : $now->addMinutes(
                $this->expireMinutes
            );

        if ($expiresAt->lessThanOrEqualTo($now)) {
            throw new RuntimeException(
                'Giao dịch thanh toán đã hết hạn.'
            );
        }

        $inputData = [
            'vnp_Version' => $this->version,
            'vnp_Command' => 'pay',
            'vnp_TmnCode' => $this->tmnCode,

            /*
             * VNPAY yêu cầu số tiền được nhân với 100.
             */
            'vnp_Amount' => (string) (
                (int) $payment->amount * 100
            ),

            'vnp_CreateDate' => $now->format(
                'YmdHis'
            ),

            'vnp_CurrCode' => 'VND',

            'vnp_IpAddr' => $this->normalizeIpAddress(
                $ipAddress
            ),

            'vnp_Locale' => $this->locale,

            'vnp_OrderInfo' => $this->buildOrderInfo(
                $payment
            ),

            'vnp_OrderType' => 'other',

            'vnp_ReturnUrl' => $this->returnUrl,

            'vnp_TxnRef' => trim(
                $payment->transaction_code
            ),

            'vnp_ExpireDate' => $expiresAt->format(
                'YmdHis'
            ),
        ];

        if ($bankCode !== null) {
            $inputData['vnp_BankCode'] = $bankCode;
        }

        return $this->buildSignedUrl(
            $inputData
        );
    }

    /**
     * Xác minh chữ ký dữ liệu do VNPAY gửi về Return URL hoặc IPN.
     */
    public function verifySignature(
        array $payload
    ): bool {
        $this->ensureConfigured();

        $receivedHash = strtolower(
            trim(
                (string) (
                    $payload['vnp_SecureHash']
                    ?? ''
                )
            )
        );

        if ($receivedHash === '') {
            return false;
        }

        $signedData = $this->extractSignedData(
            $payload
        );

        if ($signedData === []) {
            return false;
        }

        $calculatedHash = hash_hmac(
            'sha512',
            $this->buildQueryString(
                $signedData
            ),
            $this->hashSecret
        );

        return hash_equals(
            strtolower($calculatedHash),
            $receivedHash
        );
    }

    /**
     * Kiểm tra VNPAY xác nhận giao dịch thành công.
     */
    public function isSuccessful(
        array $payload
    ): bool {
        return (
            $payload['vnp_ResponseCode']
            ?? null
        ) === '00'
            && (
                $payload['vnp_TransactionStatus']
                ?? null
            ) === '00';
    }

    /**
     * Chuyển số tiền VNPAY trả về thành số tiền VND ban đầu.
     */
    public function convertVnpayAmountToVnd(
        int|string|null $amount
    ): int {
        if (
            $amount === null
            || !is_numeric($amount)
        ) {
            return 0;
        }

        return intdiv(
            (int) $amount,
            100
        );
    }

    /**
     * Lấy dữ liệu VNPAY an toàn để lưu vào response_data.
     *
     * Không lưu vnp_SecureHash và vnp_SecureHashType.
     */
    public function responseData(
        array $payload
    ): array {
        $responseData = [];

        foreach ($payload as $key => $value) {
            if (
                !is_string($key)
                || !str_starts_with(
                    $key,
                    'vnp_'
                )
                || in_array(
                    $key,
                    [
                        'vnp_SecureHash',
                        'vnp_SecureHashType',
                    ],
                    true
                )
                || !is_scalar($value)
            ) {
                continue;
            }

            $responseData[$key] = (string) $value;
        }

        return $responseData;
    }

    /**
     * Tạo URL thanh toán kèm chữ ký HMAC SHA-512.
     */
    private function buildSignedUrl(
        array $inputData
    ): string {
        $inputData = array_filter(
            $inputData,
            static fn (mixed $value): bool =>
                $value !== null
                && $value !== ''
        );

        ksort($inputData);

        $queryString = $this->buildQueryString(
            $inputData
        );

        $secureHash = hash_hmac(
            'sha512',
            $queryString,
            $this->hashSecret
        );

        return $this->paymentUrl
            . '?'
            . $queryString
            . '&vnp_SecureHash='
            . $secureHash;
    }

    /**
     * Lấy các trường vnp_* tham gia quá trình xác minh chữ ký.
     */
    private function extractSignedData(
        array $payload
    ): array {
        $signedData = [];

        foreach ($payload as $key => $value) {
            if (
                !is_string($key)
                || !str_starts_with(
                    $key,
                    'vnp_'
                )
                || in_array(
                    $key,
                    [
                        'vnp_SecureHash',
                        'vnp_SecureHashType',
                    ],
                    true
                )
                || $value === null
                || $value === ''
                || !is_scalar($value)
            ) {
                continue;
            }

            $signedData[$key] = (string) $value;
        }

        ksort($signedData);

        return $signedData;
    }

    /**
     * Tạo query string theo định dạng dùng để ký dữ liệu VNPAY.
     */
    private function buildQueryString(
        array $data
    ): string {
        return http_build_query(
            $data,
            '',
            '&',
            PHP_QUERY_RFC1738
        );
    }

    /**
     * Tạo nội dung thanh toán từ Booking trong database.
     */
    private function buildOrderInfo(
        Payment $payment
    ): string {
        $orderInfo = Str::ascii(
            'Thanh toan booking '
            . $payment->booking->booking_code
        );

        $orderInfo = preg_replace(
            '/[^A-Za-z0-9\s\-.:]/',
            '',
            $orderInfo
        ) ?? '';

        $orderInfo = preg_replace(
            '/\s+/',
            ' ',
            $orderInfo
        ) ?? '';

        $orderInfo = Str::limit(
            trim($orderInfo),
            255,
            ''
        );

        return $orderInfo !== ''
            ? $orderInfo
            : 'Thanh toan booking';
    }

    /**
     * Chuẩn hóa phương thức thanh toán.
     */
    private function normalizeBankCode(
        ?string $bankCode
    ): ?string {
        if (
            $bankCode === null
            || trim($bankCode) === ''
        ) {
            return null;
        }

        $bankCode = strtoupper(
            trim($bankCode)
        );

        if (!in_array(
            $bankCode,
            self::ALLOWED_BANK_CODES,
            true
        )) {
            throw new InvalidArgumentException(
                'Phương thức thanh toán VNPAY không hợp lệ.'
            );
        }

        return $bankCode;
    }

    /**
     * Chuẩn hóa ngôn ngữ giao diện VNPAY.
     */
    private function normalizeLocale(
        string $locale
    ): string {
        $locale = strtolower(
            trim($locale)
        );

        return in_array(
            $locale,
            ['vn', 'en'],
            true
        )
            ? $locale
            : 'vn';
    }

    /**
     * Chuẩn hóa địa chỉ IP người dùng.
     */
    private function normalizeIpAddress(
        string $ipAddress
    ): string {
        $ipAddress = trim(
            $ipAddress
        );

        return filter_var(
            $ipAddress,
            FILTER_VALIDATE_IP
        )
            ? $ipAddress
            : '127.0.0.1';
    }

    /**
     * Kiểm tra các cấu hình VNPAY bắt buộc.
     */
    private function ensureConfigured(): void
    {
        $requiredConfiguration = [
            'VNPAY_TMN_CODE' => $this->tmnCode,
            'VNPAY_HASH_SECRET' => $this->hashSecret,
            'VNPAY_PAYMENT_URL' => $this->paymentUrl,
            'VNPAY_RETURN_URL' => $this->returnUrl,
        ];

        foreach (
            $requiredConfiguration
            as $name => $value
        ) {
            if (trim($value) === '') {
                throw new RuntimeException(
                    "Thiếu cấu hình {$name}."
                );
            }
        }

        if (!filter_var(
            $this->paymentUrl,
            FILTER_VALIDATE_URL
        )) {
            throw new RuntimeException(
                'VNPAY_PAYMENT_URL không hợp lệ.'
            );
        }

        if (!filter_var(
            $this->returnUrl,
            FILTER_VALIDATE_URL
        )) {
            throw new RuntimeException(
                'VNPAY_RETURN_URL không hợp lệ.'
            );
        }
    }
}