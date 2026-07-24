<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Promotion extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'description',

        'discount_type',
        'discount_value',
        'max_discount',

        'min_order_value',

        'usage_limit',
        'used_count',

        'start_date',
        'end_date',

        'status',
    ];

    protected function casts(): array
    {
        return [
            'discount_value' => 'decimal:2',
            'max_discount' => 'integer',
            'min_order_value' => 'integer',

            'usage_limit' => 'integer',
            'used_count' => 'integer',

            'start_date' => 'datetime',
            'end_date' => 'datetime',
            'status' => 'boolean',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Quan hệ với Booking
    |--------------------------------------------------------------------------
    | Một mã giảm giá có thể được sử dụng cho nhiều Booking.
    */

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }
}