<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ContactMessage extends Model
{
    /**
     * Các trường được phép thêm và cập nhật hàng loạt.
     */
    protected $fillable = [
        'user_id',
        'name',
        'email',
        'phone',
        'subject',
        'message',
        'status',
        'read_at',
        'replied_at',
    ];

    /**
     * Ép kiểu dữ liệu.
     */
    protected function casts(): array
    {
        return [
            'read_at' => 'datetime',
            'replied_at' => 'datetime',
        ];
    }

    /**
     * Tài khoản đã gửi thư.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Danh sách phản hồi của thư.
     */
    public function replies(): HasMany
    {
        return $this->hasMany(ContactReply::class)
            ->orderBy('sent_at');
    }

    /**
     * Kiểm tra thư chưa được admin đọc.
     */
    public function isUnread(): bool
    {
        return $this->status === 'unread';
    }

    /**
     * Kiểm tra thư đã được phản hồi.
     */
    public function isReplied(): bool
    {
        return $this->status === 'replied';
    }
}