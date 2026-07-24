<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'homestay_id',
        'name',
        'room_code',
        'room_type',
        'description',
        'image',
        'price_per_night',
        'capacity',
        'number_of_beds',
        'area',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'price_per_night' => 'integer',
            'capacity' => 'integer',
            'number_of_beds' => 'integer',
            'area' => 'decimal:2',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Quan hệ với Homestay
    |--------------------------------------------------------------------------
    | Một phòng thuộc một Homestay.
    */

    public function homestay(): BelongsTo
    {
        return $this->belongsTo(Homestay::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Quan hệ với Booking
    |--------------------------------------------------------------------------
    | Một phòng có thể xuất hiện trong nhiều đơn đặt phòng theo thời gian.
    */

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }
}