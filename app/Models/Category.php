<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'status',
    ];

    /*
    |--------------------------------------------------------------------------
    | Quan hệ với Homestay
    |--------------------------------------------------------------------------
    | Một Category có nhiều Homestay.
    */

    public function homestays(): HasMany
    {
        return $this->hasMany(Homestay::class);
    }

    protected function casts(): array
    {
        return [
            'status' => 'boolean',
        ];
    }
}