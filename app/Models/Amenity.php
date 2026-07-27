<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Amenity extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'icon',
        'status',
    ];

    /*
    |--------------------------------------------------------------------------
    | Quan hệ với Homestay
    |--------------------------------------------------------------------------
    | Một tiện nghi có thể thuộc nhiều Homestay.
    */

    public function homestays(): BelongsToMany
    {
        return $this->belongsToMany(
            Homestay::class,
            'homestay_amenity',
            'amenity_id',
            'homestay_id'
        );
    }

    protected function casts(): array
    {
        return [
            'status' => 'boolean',
        ];
    }
}