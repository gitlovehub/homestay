<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tạo bảng thư liên hệ.
     */
    public function up(): void
    {
        Schema::create('contact_messages', function (Blueprint $table) {
            $table->id();

            $table->string('name', 100);

            $table->string('email');

            $table->string('phone', 20)->nullable();

            $table->string('subject');

            $table->text('message');

            $table->enum('status', [
                'unread',
                'read',
            ])->default('unread');

            $table->timestamps();
        });
    }

    /**
     * Xóa bảng thư liên hệ.
     */
    public function down(): void
    {
        Schema::dropIfExists('contact_messages');
    }
};