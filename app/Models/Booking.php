<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Booking extends Model
{
    use HasFactory;

    public const PAYMENT_OPTION_VNPAY_FULL = 'vnpay_full';
    public const PAYMENT_OPTION_CASH_DEPOSIT = 'cash_deposit';
    public const DEPOSIT_PERCENT = 10;
    public const FULL_VNPAY_REQUIRED_FROM_DAYS = 30;

    protected $fillable = [
        'booking_code',

        'user_id',
        'room_id',
        'promotion_id',

        'customer_name',
        'customer_email',
        'customer_phone',

        'check_in',
        'check_out',

        'number_of_guests',
        'number_of_nights',

        'room_price',
        'subtotal',
        'service_fee',
        'discount_amount',
        'total_price',

        'note',
        'cancellation_reason',
        'cancelled_at',

        'status',
        'payment_status',
        'payment_option',
        'refund_amount',
    ];

    protected function casts(): array
    {
        return [
            'check_in' => 'date',
            'check_out' => 'date',
            'cancelled_at' => 'datetime',

            'room_price' => 'integer',
            'subtotal' => 'integer',
            'service_fee' => 'integer',
            'discount_amount' => 'integer',
            'total_price' => 'integer',
            'refund_amount' => 'integer',
        ];
    }

    public function isCashDepositOption(): bool
    {
        return $this->payment_option === self::PAYMENT_OPTION_CASH_DEPOSIT;
    }

    /**
     * Đơn được tạo trước ngày check-in từ 30 ngày trở lên
     * bắt buộc thanh toán toàn bộ qua VNPAY.
     *
     * Với booking đã lưu, dùng created_at làm mốc để chính sách
     * không thay đổi chỉ vì thời gian trôi qua.
     */
    public function requiresFullVnpayPayment(): bool
    {
        if (!$this->check_in) {
            return false;
        }

        $bookingDate = $this->created_at
            ? $this->created_at
                ->copy()
                ->setTimezone('Asia/Ho_Chi_Minh')
                ->startOfDay()
            : now('Asia/Ho_Chi_Minh')->startOfDay();

        $checkIn = $this->check_in
            ->copy()
            ->setTimezone('Asia/Ho_Chi_Minh')
            ->startOfDay();

        return $checkIn->greaterThanOrEqualTo(
            $bookingDate
                ->copy()
                ->addDays(self::FULL_VNPAY_REQUIRED_FROM_DAYS)
        );
    }

    public function canUseCashDepositOption(): bool
    {
        return !$this->requiresFullVnpayPayment();
    }

    public function amountRequiredForVnpay(): int
    {
        if ($this->isCashDepositOption()) {
            return max(
                1,
                (int) round(
                    (int) $this->total_price * self::DEPOSIT_PERCENT / 100
                )
            );
        }

        return (int) $this->total_price;
    }

    public function remainingCashAmount(): int
    {
        if (!$this->isCashDepositOption()) {
            return 0;
        }

        return max(
            0,
            (int) $this->total_price - $this->amountRequiredForVnpay()
        );
    }

    /**
     * Chính sách hủy đối với đơn thanh toán toàn bộ qua VNPAY:
     * - >= 30 ngày: hoàn 100%
     * - 7 - 29 ngày: hoàn 50%
     * - < 7 ngày: không hoàn
     *
     * Đơn chọn thanh toán tại homestay chỉ cọc 10%, khách chủ động hủy
     * thì tiền cọc không được hoàn.
     */
    public function customerCancellationRefundPercentage(): int
    {
        if ($this->isCashDepositOption()) {
            return 0;
        }

        if (!$this->check_in) {
            return 0;
        }

        $today = now('Asia/Ho_Chi_Minh')->startOfDay();
        $checkIn = $this->check_in->copy()->startOfDay();
        $daysUntilCheckIn = $today->diffInDays($checkIn, false);

        if ($daysUntilCheckIn >= 30) {
            return 100;
        }

        if ($daysUntilCheckIn >= 7) {
            return 50;
        }

        return 0;
    }

    /*
    |--------------------------------------------------------------------------
    | User
    |--------------------------------------------------------------------------
    */

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Room
    |--------------------------------------------------------------------------
    */

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Promotion
    |--------------------------------------------------------------------------
    */

    public function promotion(): BelongsTo
    {
        return $this->belongsTo(Promotion::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Payment
    |--------------------------------------------------------------------------
    */

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class)->latestOfMany();
    }

    /*
    |--------------------------------------------------------------------------
    | Review
    |--------------------------------------------------------------------------
    */

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }
}
