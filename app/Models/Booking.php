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
        ];
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

/**
 * Các lần thanh toán VNPAY của booking.
 */
/**
 * Tất cả các lần thử thanh toán VNPAY.
 */
/*
|--------------------------------------------------------------------------
| Payments
|--------------------------------------------------------------------------
*/

/**
 * Tất cả các lần thử thanh toán của booking.
 */
public function payments(): HasMany
{
    return $this->hasMany(Payment::class, 'booking_id');
}

/**
 * Lần thanh toán mới nhất của booking.
 */
public function latestPayment(): HasOne
{
    return $this->hasOne(Payment::class, 'booking_id')
        ->latestOfMany();
}

/**
 * Giao dịch thanh toán thành công mới nhất.
 */
public function paidPayment(): HasOne
{
    return $this->hasOne(Payment::class, 'booking_id')
        ->where('status', Payment::STATUS_PAID)
        ->latestOfMany();
}

/**
 * Kiểm tra booking đã thanh toán thành công hay chưa.
 */
public function isPaid(): bool
{
    return $this->payments()
        ->where('status', Payment::STATUS_PAID)
        ->exists();
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