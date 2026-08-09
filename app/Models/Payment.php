<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    public const PURPOSE_FULL_PAYMENT = 'full_payment';
    public const PURPOSE_DEPOSIT = 'deposit';
    public const PURPOSE_CASH_BALANCE = 'cash_balance';

    protected $fillable = [
        'booking_id',
        'transaction_code',
        'gateway_transaction_code',
        'payment_method',
        'payment_purpose',
        'bank_code',
        'amount',
        'status',
        'response_code',
        'transaction_status',
        'paid_at',
        'expired_at',
        'gateway_created_at',
        'response_data',

        'refunded_amount',
        'refund_status',
        'refund_request_id',
        'refund_transaction_code',
        'refunded_at',
        'refund_response_data',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'integer',
            'refunded_amount' => 'integer',
            'paid_at' => 'datetime',
            'expired_at' => 'datetime',
            'gateway_created_at' => 'datetime',
            'refunded_at' => 'datetime',
            'response_data' => 'array',
            'refund_response_data' => 'array',
        ];
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }
}
