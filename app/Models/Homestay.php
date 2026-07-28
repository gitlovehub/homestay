<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
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
        'base_price',
        'latitude',
        'longitude',
        'check_in_time',
        'check_out_time',
        'policy',
        'thumbnail',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'base_price' => 'integer',
            'latitude' => 'decimal:7',
            'longitude' => 'decimal:7',
            'status' => 'boolean',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Quan hệ với Category
    |--------------------------------------------------------------------------
    | Một Homestay thuộc một danh mục.
    */

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Quan hệ với User
    |--------------------------------------------------------------------------
    | Một Homestay có thể thuộc quyền quản lý của một người dùng.
    */

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Quan hệ với Amenity
    |--------------------------------------------------------------------------
    | Một Homestay có nhiều tiện nghi.
    */

    public function amenities(): BelongsToMany
    {
        return $this->belongsToMany(
            Amenity::class,
            'homestay_amenity',
            'homestay_id',
            'amenity_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Quan hệ với Room
    |--------------------------------------------------------------------------
    | Một Homestay có nhiều phòng.
    */

    public function rooms(): HasMany
    {
        return $this->hasMany(Room::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Quan hệ với HomestayImage
    |--------------------------------------------------------------------------
    | Một Homestay có nhiều hình ảnh.
    */

    public function images(): HasMany
    {
        return $this->hasMany(HomestayImage::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Quan hệ với Review
    |--------------------------------------------------------------------------
    | Một Homestay có nhiều đánh giá.
    */

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function bookings(): HasManyThrough
    {
        return $this->hasManyThrough(
            Booking::class,
            Room::class,
            'homestay_id',
            'room_id',
            'id',
            'id'
        );
    }
}