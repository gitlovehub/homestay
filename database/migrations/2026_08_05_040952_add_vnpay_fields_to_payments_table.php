<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->string('gateway_transaction_code')
                ->nullable()
                ->unique()
                ->after('transaction_code');

            $table->string('bank_code', 50)
                ->nullable()
                ->after('payment_method');

            $table->string('response_code', 20)
                ->nullable()
                ->after('status');

            $table->string('transaction_status', 20)
                ->nullable()
                ->after('response_code');

            $table->dateTime('expired_at')
                ->nullable()
                ->after('paid_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropUnique([
                'gateway_transaction_code',
            ]);

            $table->dropColumn([
                'gateway_transaction_code',
                'bank_code',
                'response_code',
                'transaction_status',
                'expired_at',
            ]);
        });
    }
};