<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('contact_messages', function (Blueprint $table) {
            $table->foreignId('user_id')
                ->nullable()
                ->after('id')
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('read_at')
                ->nullable()
                ->after('status');

            $table->timestamp('replied_at')
                ->nullable()
                ->after('read_at');

            $table->index(
                'status',
                'contact_messages_status_index'
            );
        });
    }

    public function down(): void
    {
        Schema::table('contact_messages', function (Blueprint $table) {
            $table->dropForeign(['user_id']);

            $table->dropIndex(
                'contact_messages_status_index'
            );

            $table->dropColumn([
                'user_id',
                'read_at',
                'replied_at',
            ]);
        });
    }
};