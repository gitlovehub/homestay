<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Room extends Model
{
    protected $fillable = [
        'homestay_id',
        'name',
        'room_type',
        'price',
        'capacity',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'status' => 'boolean',
        ];
    }

    public function homestay(): BelongsTo
    {
        return $this->belongsTo(Homestay::class);
    }
}