<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('payment_option', 30)
                ->default('vnpay_full')
                ->after('payment_status');

            $table->unsignedBigInteger('refund_amount')
                ->default(0)
                ->after('payment_option');
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->string('payment_purpose', 30)
                ->default('full_payment')
                ->after('payment_method');

            $table->timestamp('gateway_created_at')
                ->nullable()
                ->after('expired_at');

            $table->unsignedBigInteger('refunded_amount')
                ->default(0)
                ->after('response_data');

            $table->string('refund_status', 30)
                ->nullable()
                ->after('refunded_amount');

            $table->string('refund_request_id', 32)
                ->nullable()
                ->after('refund_status');

            $table->string('refund_transaction_code', 100)
                ->nullable()
                ->after('refund_request_id');

            $table->timestamp('refunded_at')
                ->nullable()
                ->after('refund_transaction_code');

            $table->json('refund_response_data')
                ->nullable()
                ->after('refunded_at');
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn([
                'payment_purpose',
                'gateway_created_at',
                'refunded_amount',
                'refund_status',
                'refund_request_id',
                'refund_transaction_code',
                'refunded_at',
                'refund_response_data',
            ]);
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn([
                'payment_option',
                'refund_amount',
            ]);
        });
    }
};