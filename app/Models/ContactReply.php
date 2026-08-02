<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContactReply extends Model
{
    /**
     * Các trường được phép thêm và cập nhật hàng loạt.
     */
    protected $fillable = [
        'contact_message_id',
        'admin_id',
        'subject',
        'message',
        'sent_at',
    ];

    /**
     * Ép kiểu dữ liệu.
     */
    protected function casts(): array
    {
        return [
            'sent_at' => 'datetime',
        ];
    }

    /**
     * Thư liên hệ được phản hồi.
     */
    public function contactMessage(): BelongsTo
    {
        return $this->belongsTo(ContactMessage::class);
    }

    /**
     * Admin thực hiện phản hồi.
     */
    public function admin(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'admin_id'
        );
    }
}