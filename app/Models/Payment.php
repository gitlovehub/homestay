<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    /*
    |--------------------------------------------------------------------------
    | Trạng thái thanh toán
    |--------------------------------------------------------------------------
    */

    public const STATUS_PENDING = 'pending';

    public const STATUS_PAID = 'paid';

    public const STATUS_FAILED = 'failed';

    public const STATUS_CANCELLED = 'cancelled';

    public const STATUS_EXPIRED = 'expired';

    /*
    |--------------------------------------------------------------------------
    | Phương thức thanh toán
    |--------------------------------------------------------------------------
    */

    public const METHOD_VNPAY = 'vnpay';

    protected $fillable = [
        'booking_id',
        'user_id',
        'transaction_code',
        'payment_method',
        'amount',
        'paid_at',
        'status',
        'response_data',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'integer',
            'paid_at' => 'datetime',
            'response_data' => 'array',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Quan hệ với Booking
    |--------------------------------------------------------------------------
    */

    /**
     * Một giao dịch thanh toán thuộc về một đơn đặt phòng.
     */
    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Quan hệ với User
    |--------------------------------------------------------------------------
    */

    /**
     * Một giao dịch thanh toán thuộc về một người dùng.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Kiểm tra trạng thái
    |--------------------------------------------------------------------------
    */

    /**
     * Kiểm tra giao dịch đã thanh toán thành công chưa.
     */
    public function isPaid(): bool
    {
        return $this->status === self::STATUS_PAID;
    }

    /**
     * Kiểm tra giao dịch đang chờ thanh toán.
     */
    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }
}