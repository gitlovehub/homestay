<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'address',
        'avatar',
        'role',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Homestay do người dùng sở hữu
    |--------------------------------------------------------------------------
    */

    public function homestays(): HasMany
    {
        return $this->hasMany(Homestay::class, 'owner_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Đơn đặt phòng của người dùng
    |--------------------------------------------------------------------------
    */

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Các giao dịch VNPAY của người dùng
    |--------------------------------------------------------------------------
    */

    public function payments(): HasMany
    {
        return $this->hasMany(
            Payment::class,
            'user_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Lịch sử thanh toán do người dùng thực hiện
    |--------------------------------------------------------------------------
    */

    public function paymentHistories(): HasMany
    {
        return $this->hasMany(
            PaymentHistory::class,
            'actor_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Đánh giá của người dùng
    |--------------------------------------------------------------------------
    */

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Kiểm tra quyền Admin
    |--------------------------------------------------------------------------
    */

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /*
    |--------------------------------------------------------------------------
    | Kiểm tra quyền User
    |--------------------------------------------------------------------------
    */

    public function isUser(): bool
    {
        return $this->role === 'user';
    }

    /*
    |--------------------------------------------------------------------------
    | Kiểm tra tài khoản hoạt động
    |--------------------------------------------------------------------------
    */

    public function isActive(): bool
    {
        return $this->status === 'active';
    }
}