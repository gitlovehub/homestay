<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentHistory extends Model
{
    use HasFactory;

    public const ACTOR_USER = 'user';

    public const ACTOR_ADMIN = 'admin';

    public const ACTOR_SYSTEM = 'system';

    public const ACTOR_VNPAY = 'vnpay';

    public const EVENT_CREATED = 'payment_created';

    public const EVENT_REDIRECTED = 'redirected_to_vnpay';

    public const EVENT_SUCCESS = 'payment_success';

    public const EVENT_FAILED = 'payment_failed';

    public const EVENT_CANCELLED = 'payment_cancelled';

    public const EVENT_EXPIRED = 'payment_expired';

    protected $fillable = [
        'payment_id',
        'actor_id',
        'actor_type',
        'event',
        'from_status',
        'to_status',
        'note',
        'payload',
    ];

    protected $casts = [
        'payload' => 'array',
    ];

    /**
     * Giao dịch thanh toán của lịch sử.
     */
    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }

    /**
     * Người thực hiện thay đổi.
     *
     * Có thể null khi sự kiện đến từ VNPAY hoặc hệ thống.
     */
    public function actor(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'actor_id'
        );
    }
}