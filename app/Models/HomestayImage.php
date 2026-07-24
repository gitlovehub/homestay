<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HomestayImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'homestay_id',
        'image_path',
        'alt_text',
        'is_primary',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_primary' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Quan hệ với Homestay
    |--------------------------------------------------------------------------
    | Một hình ảnh thuộc về một Homestay.
    */

    public function homestay(): BelongsTo
    {
        return $this->belongsTo(Homestay::class);
    }
}