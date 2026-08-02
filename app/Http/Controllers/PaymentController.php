<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PaymentController extends Controller
{
    /**
     * Hiển thị trang thanh toán của booking.
     */
    public function checkout(Booking $booking): View
    {
        $this->authorizeBookingPayment($booking);

        $booking->load([
            'user',
            'room.homestay',
            'latestPayment',
            'paidPayment',
        ]);

        $amount = (int) $booking->total_price;

        return view('payments.checkout', compact(
            'booking',
            'amount'
        ));
    }

    /**
     * Tạo giao dịch thanh toán và chuyển sang VNPAY.
     */
    public function createVnpayPayment(
        Request $request,
        Booking $booking
    ): RedirectResponse {
        $this->authorizeBookingPayment($booking);

        /*
        |--------------------------------------------------------------------------
        | Kiểm tra trạng thái booking
        |--------------------------------------------------------------------------
        */

        if ($booking->isPaid() || $booking->payment_status === 'paid') {
            return redirect()
                ->route('payments.checkout', $booking)
                ->with('success', 'Đơn đặt phòng này đã được thanh toán.');
        }

        if ($booking->status === 'cancelled') {
            return redirect()
                ->route('payments.checkout', $booking)
                ->with('error', 'Không thể thanh toán đơn đặt phòng đã bị hủy.');
        }

        $amount = (int) $booking->total_price;

        if ($amount <= 0) {
            return redirect()
                ->route('payments.checkout', $booking)
                ->with('error', 'Số tiền thanh toán không hợp lệ.');
        }

        /*
        |--------------------------------------------------------------------------
        | Lấy cấu hình VNPAY
        |--------------------------------------------------------------------------
        */

        $tmnCode = config('services.vnpay.tmn_code');
        $hashSecret = config('services.vnpay.hash_secret');
        $paymentUrl = config('services.vnpay.payment_url');
        $returnUrl = config('services.vnpay.return_url')
            ?: route('payments.vnpay.return');

        if (
            blank($tmnCode)
            || blank($hashSecret)
            || blank($paymentUrl)
            || blank($returnUrl)
        ) {
            return redirect()
                ->route('payments.checkout', $booking)
                ->with(
                    'error',
                    'Cấu hình VNPAY chưa đầy đủ. Vui lòng kiểm tra file .env.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Tạo mã giao dịch
        |--------------------------------------------------------------------------
        */

        $transactionRef = sprintf(
            'HSG%s%s%s',
            $booking->id,
            now()->format('YmdHis'),
            random_int(1000, 9999)
        );

        $createDate = now();
        $expireDate = now()->addMinutes(15);

        /*
        |--------------------------------------------------------------------------
        | Dữ liệu gửi sang VNPAY
        |--------------------------------------------------------------------------
        */

        $vnpayData = [
            'vnp_Version' => '2.1.0',
            'vnp_Command' => 'pay',
            'vnp_TmnCode' => $tmnCode,
            'vnp_Amount' => $amount * 100,
            'vnp_CurrCode' => 'VND',
            'vnp_TxnRef' => $transactionRef,
            'vnp_OrderInfo' => 'Thanh toan booking ' . $booking->booking_code,
            'vnp_OrderType' => 'other',
            'vnp_Locale' => 'vn',
            'vnp_ReturnUrl' => $returnUrl,
            'vnp_IpAddr' => $request->ip(),
            'vnp_CreateDate' => $createDate->format('YmdHis'),
            'vnp_ExpireDate' => $expireDate->format('YmdHis'),
        ];

        /*
        |--------------------------------------------------------------------------
        | Lưu giao dịch vào database
        |--------------------------------------------------------------------------
        |
        | Đoạn này sử dụng các cột trong migration payments của bạn:
        | transaction_ref, user_id, request_data, expires_at...
        |
        */

        DB::table('payments')->insert([
            'booking_id' => $booking->id,
            'user_id' => $booking->user_id,
            'transaction_ref' => $transactionRef,
            'amount' => $amount,
            'currency' => 'VND',
            'status' => Payment::STATUS_PENDING,
            'ip_address' => $request->ip(),
            'request_data' => json_encode(
                $vnpayData,
                JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
            ),
            'expires_at' => $expireDate,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        /*
        |--------------------------------------------------------------------------
        | Tạo chữ ký bảo mật
        |--------------------------------------------------------------------------
        */

        $hashData = $this->buildVnpayHashData($vnpayData);

        $secureHash = hash_hmac(
            'sha512',
            $hashData,
            $hashSecret
        );

        $redirectUrl = rtrim($paymentUrl, '?')
            . '?'
            . $hashData
            . '&vnp_SecureHash='
            . $secureHash;

        return redirect()->away($redirectUrl);
    }

    /**
     * Nhận kết quả khi VNPAY chuyển người dùng trở lại website.
     */
    public function vnpayReturn(Request $request): RedirectResponse
    {
        $transactionRef = (string) $request->input('vnp_TxnRef');

        if ($transactionRef === '') {
            return redirect()
                ->route('home')
                ->with('error', 'Không tìm thấy mã giao dịch VNPAY.');
        }

        $payment = Payment::query()
            ->where('transaction_ref', $transactionRef)
            ->first();

        if (! $payment) {
            return redirect()
                ->route('home')
                ->with('error', 'Giao dịch thanh toán không tồn tại.');
        }

        $booking = Booking::query()->find($payment->booking_id);

        if (! $booking) {
            return redirect()
                ->route('home')
                ->with('error', 'Không tìm thấy đơn đặt phòng.');
        }

        /*
        |--------------------------------------------------------------------------
        | Nếu giao dịch đã được xử lý thành công trước đó
        |--------------------------------------------------------------------------
        */

        if ($payment->status === Payment::STATUS_PAID) {
            return redirect()
                ->route('payments.checkout', $booking)
                ->with('success', 'Đơn đặt phòng đã được thanh toán thành công.');
        }

        /*
        |--------------------------------------------------------------------------
        | Kiểm tra chữ ký VNPAY
        |--------------------------------------------------------------------------
        */

        $receivedHash = (string) $request->input('vnp_SecureHash');

        $vnpayData = $this->getVnpayReturnData($request);

        $hashSecret = config('services.vnpay.hash_secret');

        if (blank($hashSecret)) {
            return redirect()
                ->route('payments.checkout', $booking)
                ->with('error', 'Không tìm thấy mã bảo mật VNPAY.');
        }

        $expectedHash = hash_hmac(
            'sha512',
            $this->buildVnpayHashData($vnpayData),
            $hashSecret
        );

        if (
            $receivedHash === ''
            || ! hash_equals($expectedHash, $receivedHash)
        ) {
            return redirect()
                ->route('payments.checkout', $booking)
                ->with(
                    'error',
                    'Chữ ký giao dịch VNPAY không hợp lệ.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Kiểm tra số tiền
        |--------------------------------------------------------------------------
        */

        $vnpayAmount = (int) $request->input('vnp_Amount', 0);

        $paidAmount = (int) ($vnpayAmount / 100);

        if ($paidAmount !== (int) $payment->amount) {
            DB::table('payments')
                ->where('id', $payment->id)
                ->update([
                    'status' => Payment::STATUS_FAILED,
                    'vnp_response_code' => $request->input(
                        'vnp_ResponseCode'
                    ),
                    'vnp_transaction_status' => $request->input(
                        'vnp_TransactionStatus'
                    ),
                    'response_data' => json_encode(
                        $request->all(),
                        JSON_UNESCAPED_UNICODE
                        | JSON_UNESCAPED_SLASHES
                    ),
                    'processed_at' => now(),
                    'updated_at' => now(),
                ]);

            return redirect()
                ->route('payments.checkout', $booking)
                ->with(
                    'error',
                    'Số tiền thanh toán không khớp với đơn đặt phòng.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Kiểm tra kết quả thanh toán
        |--------------------------------------------------------------------------
        */

        $responseCode = (string) $request->input('vnp_ResponseCode');

        $transactionStatus = (string) $request->input(
            'vnp_TransactionStatus'
        );

        $isSuccessful = $responseCode === '00'
            && $transactionStatus === '00';

        if (! $isSuccessful) {
            DB::table('payments')
                ->where('id', $payment->id)
                ->update([
                    'status' => Payment::STATUS_FAILED,
                    'vnp_transaction_no' => $request->input(
                        'vnp_TransactionNo'
                    ),
                    'vnp_response_code' => $responseCode,
                    'vnp_transaction_status' => $transactionStatus,
                    'vnp_bank_code' => $request->input('vnp_BankCode'),
                    'vnp_card_type' => $request->input('vnp_CardType'),
                    'vnp_pay_date' => $request->input('vnp_PayDate'),
                    'response_data' => json_encode(
                        $request->all(),
                        JSON_UNESCAPED_UNICODE
                        | JSON_UNESCAPED_SLASHES
                    ),
                    'processed_at' => now(),
                    'updated_at' => now(),
                ]);

            return redirect()
                ->route('payments.checkout', $booking)
                ->with(
                    'error',
                    'Thanh toán không thành công hoặc đã bị hủy.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Cập nhật thanh toán thành công
        |--------------------------------------------------------------------------
        */

        DB::transaction(function () use (
            $payment,
            $booking,
            $request,
            $responseCode,
            $transactionStatus
        ): void {
            DB::table('payments')
                ->where('id', $payment->id)
                ->update([
                    'status' => Payment::STATUS_PAID,
                    'vnp_transaction_no' => $request->input(
                        'vnp_TransactionNo'
                    ),
                    'vnp_response_code' => $responseCode,
                    'vnp_transaction_status' => $transactionStatus,
                    'vnp_bank_code' => $request->input('vnp_BankCode'),
                    'vnp_card_type' => $request->input('vnp_CardType'),
                    'vnp_pay_date' => $request->input('vnp_PayDate'),
                    'response_data' => json_encode(
                        $request->all(),
                        JSON_UNESCAPED_UNICODE
                        | JSON_UNESCAPED_SLASHES
                    ),
                    'paid_at' => now(),
                    'processed_at' => now(),
                    'updated_at' => now(),
                ]);

            DB::table('bookings')
                ->where('id', $booking->id)
                ->update([
                    'payment_status' => 'paid',
                    'updated_at' => now(),
                ]);
        });

        return redirect()
            ->route('payments.checkout', $booking)
            ->with(
                'success',
                'Thanh toán VNPAY thành công.'
            );
    }

    /**
     * Kiểm tra quyền thanh toán booking.
     *
     * Chủ booking hoặc admin được phép truy cập.
     */
    private function authorizeBookingPayment(Booking $booking): void
    {
        $user = Auth::user();

        abort_unless(
            $user !== null,
            401,
            'Bạn cần đăng nhập để thanh toán.'
        );

        $isBookingOwner =
            (int) $booking->user_id === (int) $user->id;

        $isAdmin =
            strtolower(trim((string) $user->role)) === 'admin';

        abort_unless(
            $isBookingOwner || $isAdmin,
            403,
            'Bạn không có quyền thanh toán booking này.'
        );
    }
    /**
     * Tạo chuỗi dữ liệu dùng để ký VNPAY.
     */
    private function buildVnpayHashData(array $data): string
    {
        ksort($data);

        $parts = [];

        foreach ($data as $key => $value) {
            if ($value === null || $value === '') {
                continue;
            }

            $parts[] = urlencode((string) $key)
                . '='
                . urlencode((string) $value);
        }

        return implode('&', $parts);
    }

    /**
     * Lấy dữ liệu VNPAY trả về, loại bỏ trường chữ ký.
     */
    private function getVnpayReturnData(Request $request): array
    {
        $data = [];

        foreach ($request->all() as $key => $value) {
            if (! str_starts_with($key, 'vnp_')) {
                continue;
            }

            if (
                in_array(
                    $key,
                    ['vnp_SecureHash', 'vnp_SecureHashType'],
                    true
                )
            ) {
                continue;
            }

            $data[$key] = $value;
        }

        return $data;
    }
}