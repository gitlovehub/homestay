<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tạo bảng lịch sử thanh toán.
     */
    public function up(): void
    {
        Schema::create(
            'payment_histories',
            function (Blueprint $table) {
                $table->id();

                $table->foreignId('payment_id')
                    ->constrained('payments')
                    ->cascadeOnDelete();

                /*
                 * Người thực hiện.
                 * Null khi sự kiện đến từ VNPAY hoặc hệ thống.
                 */
                $table->foreignId('actor_id')
                    ->nullable()
                    ->constrained('users')
                    ->nullOnDelete();

                /*
                 * user   = người dùng
                 * admin  = quản trị viên
                 * vnpay  = cổng VNPAY
                 * system = hệ thống
                 */
                $table->string('actor_type', 20)
                    ->index();

                /*
                 * Một số sự kiện:
                 * payment_created
                 * redirected_to_vnpay
                 * payment_success
                 * payment_failed
                 * payment_cancelled
                 * payment_expired
                 */
                $table->string('event', 100)
                    ->index();

                $table->string('from_status', 20)
                    ->nullable();

                $table->string('to_status', 20)
                    ->nullable();

                $table->text('note')
                    ->nullable();

                /*
                 * Dữ liệu bổ sung của sự kiện.
                 */
                $table->json('payload')
                    ->nullable();

                $table->timestamps();

                $table->index([
                    'payment_id',
                    'created_at',
                ]);
            }
        );
    }

    /**
     * Xóa bảng.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_histories');
    }
};