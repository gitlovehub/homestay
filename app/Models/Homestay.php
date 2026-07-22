<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Homestay extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'owner_id',
        'name',
        'slug',
        'address',
        'city',
        'phone',
        'description',
        'image',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'status' => 'boolean',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function amenities(): BelongsToMany
    {
        return $this->belongsToMany(
            Amenity::class,
            'homestay_amenity',
            'homestay_id',
            'amenity_id'
        );
    }
    public function rooms(): HasMany
    {
        return $this->hasMany(Room::class);
    }
}
