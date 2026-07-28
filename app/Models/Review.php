<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'user_id',
        'homestay_id',
        'review_number',
        'rating',
        'title',
        'content',
        'status',
        'edited_at',
    ];

    protected function casts(): array
    {
        return [
            'review_number' => 'integer',
            'rating' => 'integer',
            'edited_at' => 'datetime',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Quan hệ với Booking
    |--------------------------------------------------------------------------
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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Quan hệ với Homestay
    |--------------------------------------------------------------------------
    */

    public function homestay(): BelongsTo
    {
        return $this->belongsTo(Homestay::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Kiểm tra đánh giá đã chỉnh sửa
    |--------------------------------------------------------------------------
    */

    public function isEdited(): bool
    {
        return $this->edited_at !== null;
    }
}