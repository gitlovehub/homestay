<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tạo bảng lưu lịch sử phản hồi thư liên hệ.
     */
    public function up(): void
    {
        Schema::create('contact_replies', function (Blueprint $table) {
            $table->id();

            $table->foreignId('contact_message_id')
                ->constrained('contact_messages')
                ->cascadeOnDelete();

            $table->foreignId('admin_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->string('subject');

            $table->text('message');

            $table->timestamp('sent_at');

            $table->timestamps();
        });
    }

    /**
     * Xóa bảng khi rollback.
     */
    public function down(): void
    {
        Schema::dropIfExists('contact_replies');
    }
};