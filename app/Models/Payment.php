<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
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
    | Một thanh toán thuộc về một đơn đặt phòng.
    */

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }
}