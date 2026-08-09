<?php

namespace App\Services;

use App\Models\Payment;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Http;
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

    private string $apiUrl;

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

        $this->apiUrl = trim(
            (string) config(
                'services.vnpay.api_url',
                'https://sandbox.vnpayment.vn/merchant_webapi/api/transaction'
            )
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

        $payment->forceFill([
            'gateway_created_at' => $now,
        ])->save();

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
     * Gửi yêu cầu hoàn tiền sang VNPAY.
     *
     * - 02: hoàn toàn phần
     * - 03: hoàn một phần
     */
    public function refundPayment(
        Payment $payment,
        int $refundAmount,
        string $ipAddress,
        string $createdBy
    ): array {
        $this->ensureConfigured();

        if (!filter_var($this->apiUrl, FILTER_VALIDATE_URL)) {
            throw new RuntimeException(
                'VNPAY_API_URL không hợp lệ.'
            );
        }

        $payment->loadMissing('booking');

        if (!$payment->booking) {
            throw new RuntimeException(
                'Không tìm thấy đơn đặt phòng của giao dịch.'
            );
        }

        if ($payment->status !== 'paid') {
            throw new RuntimeException(
                'Chỉ có thể hoàn tiền cho giao dịch đã thanh toán.'
            );
        }

        $remainingRefundable = max(
            0,
            (int) $payment->amount - (int) $payment->refunded_amount
        );

        if (
            $refundAmount <= 0
            || $refundAmount > $remainingRefundable
        ) {
            throw new RuntimeException(
                'Số tiền hoàn không hợp lệ.'
            );
        }

        $now = CarbonImmutable::now(
            'Asia/Ho_Chi_Minh'
        );

        $requestId = 'RF'
            . $now->format('YmdHis')
            . strtoupper(Str::random(8));

        $transactionType = $refundAmount === (int) $payment->amount
            && (int) $payment->refunded_amount === 0
            ? '02'
            : '03';

        $transactionDate = $this->resolveOriginalTransactionDate(
            $payment
        );

        $orderInfo = $this->buildRefundOrderInfo(
            $payment
        );

        $createdBy = Str::limit(
            trim(Str::ascii($createdBy)),
            245,
            ''
        );

        if ($createdBy === '') {
            $createdBy = 'HomeStayGo';
        }

        $inputData = [
            'vnp_RequestId' => $requestId,
            'vnp_Version' => $this->version,
            'vnp_Command' => 'refund',
            'vnp_TmnCode' => $this->tmnCode,
            'vnp_TransactionType' => $transactionType,
            'vnp_TxnRef' => trim(
                (string) $payment->transaction_code
            ),
            'vnp_Amount' => (string) ($refundAmount * 100),
            'vnp_TransactionNo' => trim(
                (string) ($payment->gateway_transaction_code ?? '')
            ),
            'vnp_TransactionDate' => $transactionDate,
            'vnp_CreateBy' => $createdBy,
            'vnp_CreateDate' => $now->format('YmdHis'),
            'vnp_IpAddr' => $this->normalizeIpAddress($ipAddress),
            'vnp_OrderInfo' => $orderInfo,
        ];

        $checksumData = implode('|', [
            $inputData['vnp_RequestId'],
            $inputData['vnp_Version'],
            $inputData['vnp_Command'],
            $inputData['vnp_TmnCode'],
            $inputData['vnp_TransactionType'],
            $inputData['vnp_TxnRef'],
            $inputData['vnp_Amount'],
            $inputData['vnp_TransactionNo'],
            $inputData['vnp_TransactionDate'],
            $inputData['vnp_CreateBy'],
            $inputData['vnp_CreateDate'],
            $inputData['vnp_IpAddr'],
            $inputData['vnp_OrderInfo'],
        ]);

        $inputData['vnp_SecureHash'] = hash_hmac(
            'sha512',
            $checksumData,
            $this->hashSecret
        );

        $response = Http::asJson()
            ->acceptJson()
            ->timeout(20)
            ->post(
                $this->apiUrl,
                $inputData
            );

        if (!$response->successful()) {
            throw new RuntimeException(
                'VNPAY không phản hồi yêu cầu hoàn tiền hợp lệ.'
            );
        }

        $payload = $response->json();

        if (!is_array($payload)) {
            throw new RuntimeException(
                'Phản hồi hoàn tiền từ VNPAY không hợp lệ.'
            );
        }

        $signatureValid = $this->verifyRefundResponseSignature(
            $payload
        );

        $responseCode = (string) (
            $payload['vnp_ResponseCode'] ?? ''
        );

        $transactionStatus = (string) (
            $payload['vnp_TransactionStatus'] ?? ''
        );

        $state = match (true) {
            !$signatureValid => 'failed',

            $responseCode === '94' => 'processing',

            $responseCode !== '00' => 'failed',

            $transactionStatus === '00' => 'refunded',

            in_array(
                $transactionStatus,
                ['01', '05', '06'],
                true
            ) => 'processing',

            default => 'failed',
        };

        return [
            'request_id' => $requestId,
            'state' => $state,
            'signature_valid' => $signatureValid,
            'response_code' => $responseCode,
            'transaction_status' => $transactionStatus,
            'transaction_no' => $payload['vnp_TransactionNo'] ?? null,
            'payload' => $this->responseData($payload),
        ];
    }

    /**
     * Truy vấn lại trạng thái giao dịch/hoàn tiền tại VNPAY.
     */
    public function queryRefundStatus(
        Payment $payment,
        string $ipAddress
    ): array {
        $this->ensureConfigured();

        $payment->loadMissing('booking');

        if (!$payment->booking) {
            throw new RuntimeException(
                'Không tìm thấy Booking của giao dịch.'
            );
        }

        if ($payment->payment_method !== 'vnpay') {
            throw new RuntimeException(
                'Chỉ có thể kiểm tra giao dịch VNPAY.'
            );
        }

        if (
            !$payment->transaction_code
            || trim($payment->transaction_code) === ''
        ) {
            throw new RuntimeException(
                'Giao dịch chưa có mã tham chiếu VNPAY.'
            );
        }

        $now = CarbonImmutable::now(
            'Asia/Ho_Chi_Minh'
        );

        $requestId =
            'QR'
            . $now->format('YmdHis')
            . strtoupper(Str::random(8));

        $transactionDate =
            $this->resolveOriginalTransactionDate(
                $payment
            );

        $orderInfo = $this->buildRefundOrderInfo(
            $payment
        );

        $inputData = [
            'vnp_RequestId' => $requestId,
            'vnp_Version' => $this->version,
            'vnp_Command' => 'querydr',
            'vnp_TmnCode' => $this->tmnCode,

            'vnp_TxnRef' => trim(
                (string) $payment->transaction_code
            ),

            'vnp_OrderInfo' => $orderInfo,

            'vnp_TransactionDate' =>
                $transactionDate,

            'vnp_CreateDate' =>
                $now->format('YmdHis'),

            'vnp_IpAddr' =>
                $this->normalizeIpAddress(
                    $ipAddress
                ),
        ];

        if (
            $payment->gateway_transaction_code
            && trim(
                (string) $payment->gateway_transaction_code
            ) !== ''
        ) {
            $inputData['vnp_TransactionNo'] =
                trim(
                    (string) $payment
                        ->gateway_transaction_code
                );
        }

        /*
         * Thứ tự checksum QUERYDR theo tài liệu VNPAY.
         */
        $checksumData = implode('|', [
            $inputData['vnp_RequestId'],
            $inputData['vnp_Version'],
            $inputData['vnp_Command'],
            $inputData['vnp_TmnCode'],
            $inputData['vnp_TxnRef'],
            $inputData['vnp_TransactionDate'],
            $inputData['vnp_CreateDate'],
            $inputData['vnp_IpAddr'],
            $inputData['vnp_OrderInfo'],
        ]);

        $inputData['vnp_SecureHash'] =
            hash_hmac(
                'sha512',
                $checksumData,
                $this->hashSecret
            );

        $response = Http::asJson()
            ->acceptJson()
            ->timeout(20)
            ->post(
                $this->apiUrl,
                $inputData
            );

        if (!$response->successful()) {
            throw new RuntimeException(
                'VNPAY không phản hồi yêu cầu kiểm tra trạng thái.'
            );
        }

        $payload = $response->json();

        if (!is_array($payload)) {
            throw new RuntimeException(
                'Phản hồi truy vấn từ VNPAY không hợp lệ.'
            );
        }

        $signatureValid =
            $this->verifyQueryResponseSignature(
                $payload
            );

        $responseCode = (string) (
            $payload['vnp_ResponseCode']
            ?? ''
        );

        $transactionStatus = (string) (
            $payload['vnp_TransactionStatus']
            ?? ''
        );

        $transactionType = (string) (
            $payload['vnp_TransactionType']
            ?? ''
        );

        $state = match (true) {

            !$signatureValid => 'failed',

            $responseCode === '94' => 'processing',

            $responseCode !== '00' => 'failed',

            in_array(
                $transactionType,
                ['02', '03'],
                true
            )
            && $transactionStatus === '00' =>
            'refunded',

            in_array(
                $transactionType,
                ['02', '03'],
                true
            )
            && in_array(
                $transactionStatus,
                ['01', '05', '06'],
                true
            ) =>
            'processing',

            $transactionStatus === '09' =>
            'failed',

            /*
             * QUERYDR vẫn chỉ trả giao dịch PAY gốc
             * thì chưa coi là đã hoàn.
             */
            $transactionType === '01'
            && $transactionStatus === '00' =>
            'processing',

            default =>
            'failed',
        };

        return [
            'state' => $state,

            'signature_valid' =>
                $signatureValid,

            'response_code' =>
                $responseCode,

            'transaction_status' =>
                $transactionStatus,

            'transaction_type' =>
                $transactionType,

            'transaction_no' =>
                $payload['vnp_TransactionNo']
                ?? null,

            'amount' =>
                $this->convertVnpayAmountToVnd(
                    $payload['vnp_Amount']
                    ?? null
                ),

            'pay_date' =>
                $payload['vnp_PayDate']
                ?? null,

            'payload' =>
                $this->responseData(
                    $payload
                ),
        ];
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
            static fn(mixed $value): bool =>
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

    private function resolveOriginalTransactionDate(
        Payment $payment
    ): string {
        if ($payment->gateway_created_at) {
            return CarbonImmutable::instance(
                $payment->gateway_created_at
            )
                ->setTimezone('Asia/Ho_Chi_Minh')
                ->format('YmdHis');
        }

        if (
            is_string($payment->transaction_code)
            && preg_match(
                '/^VNP(\d{14})/',
                $payment->transaction_code,
                $matches
            )
        ) {
            return $matches[1];
        }

        if ($payment->created_at) {
            return CarbonImmutable::instance(
                $payment->created_at
            )
                ->setTimezone('Asia/Ho_Chi_Minh')
                ->format('YmdHis');
        }

        throw new RuntimeException(
            'Không xác định được thời gian giao dịch gốc để hoàn tiền.'
        );
    }

    private function buildRefundOrderInfo(
        Payment $payment
    ): string {
        $orderInfo = Str::ascii(
            'Hoan tien booking '
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

        return Str::limit(
            trim($orderInfo) ?: 'Hoan tien booking',
            255,
            ''
        );
    }

    private function verifyRefundResponseSignature(
        array $payload
    ): bool {
        $receivedHash = strtolower(
            trim(
                (string) ($payload['vnp_SecureHash'] ?? '')
            )
        );

        if ($receivedHash === '') {
            return false;
        }

        $checksumData = implode('|', [
            (string) ($payload['vnp_ResponseId'] ?? ''),
            (string) ($payload['vnp_Command'] ?? ''),
            (string) ($payload['vnp_ResponseCode'] ?? ''),
            (string) ($payload['vnp_Message'] ?? ''),
            (string) ($payload['vnp_TmnCode'] ?? ''),
            (string) ($payload['vnp_TxnRef'] ?? ''),
            (string) ($payload['vnp_Amount'] ?? ''),
            (string) ($payload['vnp_BankCode'] ?? ''),
            (string) ($payload['vnp_PayDate'] ?? ''),
            (string) ($payload['vnp_TransactionNo'] ?? ''),
            (string) ($payload['vnp_TransactionType'] ?? ''),
            (string) ($payload['vnp_TransactionStatus'] ?? ''),
            (string) ($payload['vnp_OrderInfo'] ?? ''),
        ]);

        $calculatedHash = hash_hmac(
            'sha512',
            $checksumData,
            $this->hashSecret
        );

        return hash_equals(
            strtolower($calculatedHash),
            $receivedHash
        );
    }

    /**
     * Kiểm tra checksum phản hồi QUERYDR.
     */
    private function verifyQueryResponseSignature(
        array $payload
    ): bool {
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

        $checksumData = implode('|', [
            (string) ($payload['vnp_ResponseId'] ?? ''),
            (string) ($payload['vnp_Command'] ?? ''),
            (string) ($payload['vnp_ResponseCode'] ?? ''),
            (string) ($payload['vnp_Message'] ?? ''),
            (string) ($payload['vnp_TmnCode'] ?? ''),
            (string) ($payload['vnp_TxnRef'] ?? ''),
            (string) ($payload['vnp_Amount'] ?? ''),
            (string) ($payload['vnp_BankCode'] ?? ''),
            (string) ($payload['vnp_PayDate'] ?? ''),
            (string) ($payload['vnp_TransactionNo'] ?? ''),
            (string) ($payload['vnp_TransactionType'] ?? ''),
            (string) ($payload['vnp_TransactionStatus'] ?? ''),
            (string) ($payload['vnp_OrderInfo'] ?? ''),
            (string) ($payload['vnp_PromotionCode'] ?? ''),
            (string) ($payload['vnp_PromotionAmount'] ?? ''),
        ]);

        $calculatedHash = hash_hmac(
            'sha512',
            $checksumData,
            $this->hashSecret
        );

        return hash_equals(
            strtolower($calculatedHash),
            $receivedHash
        );
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

        if (
            !in_array(
                $bankCode,
                self::ALLOWED_BANK_CODES,
                true
            )
        ) {
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
            'VNPAY_API_URL' => $this->apiUrl,
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

        if (
            !filter_var(
                $this->paymentUrl,
                FILTER_VALIDATE_URL
            )
        ) {
            throw new RuntimeException(
                'VNPAY_PAYMENT_URL không hợp lệ.'
            );
        }

        if (
            !filter_var(
                $this->apiUrl,
                FILTER_VALIDATE_URL
            )
        ) {
            throw new RuntimeException(
                'VNPAY_API_URL không hợp lệ.'
            );
        }

        if (
            !filter_var(
                $this->returnUrl,
                FILTER_VALIDATE_URL
            )
        ) {
            throw new RuntimeException(
                'VNPAY_RETURN_URL không hợp lệ.'
            );
        }
    }
}