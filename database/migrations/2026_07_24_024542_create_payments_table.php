<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tạo bảng lưu các lần thanh toán VNPAY.
     */
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();

            /*
             * Một booking có thể có nhiều lần thử thanh toán.
             */
            $table->foreignId('booking_id')
                ->constrained('bookings')
                ->cascadeOnDelete();

            /*
             * Người thực hiện thanh toán.
             */
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            /*
             * Mã giao dịch do HomeStayGo tự tạo.
             * Giá trị gửi sang VNPAY tại vnp_TxnRef.
             */
            $table->string('transaction_ref', 100)
                ->unique();

            /*
             * Tổng tiền thực tế tính từ booking.
             * Đơn vị lưu trong database là VNĐ.
             */
            $table->unsignedBigInteger('amount');

            $table->char('currency', 3)
                ->default('VND');

            /*
             * pending   = đang chờ thanh toán
             * paid      = đã thanh toán
             * failed    = thanh toán thất bại
             * cancelled = người dùng hủy
             * expired   = giao dịch hết hạn
             */
            $table->string('status', 20)
                ->default('pending')
                ->index();

            /*
             * Mã giao dịch do VNPAY trả về.
             */
            $table->string('vnp_transaction_no', 100)
                ->nullable()
                ->index();

            /*
             * Mã phản hồi thanh toán.
             */
            $table->string('vnp_response_code', 20)
                ->nullable();

            /*
             * Trạng thái giao dịch tại VNPAY.
             */
            $table->string('vnp_transaction_status', 20)
                ->nullable();

            /*
             * Ngân hàng hoặc phương thức thanh toán.
             */
            $table->string('vnp_bank_code', 30)
                ->nullable();

            $table->string('vnp_card_type', 30)
                ->nullable();

            /*
             * Thời gian thanh toán do VNPAY trả về.
             * Thường có dạng yyyyMMddHHmmss.
             */
            $table->string('vnp_pay_date', 20)
                ->nullable();

            /*
             * Địa chỉ IP khách thanh toán.
             */
            $table->ipAddress('ip_address')
                ->nullable();

            /*
             * Dữ liệu gửi sang VNPAY.
             */
            $table->json('request_data')
                ->nullable();

            /*
             * Dữ liệu Return hoặc IPN trả về.
             */
            $table->json('response_data')
                ->nullable();

            /*
             * Thời gian giao dịch hết hạn.
             */
            $table->timestamp('expires_at')
                ->nullable();

            /*
             * Thời điểm xác nhận thanh toán thành công.
             */
            $table->timestamp('paid_at')
                ->nullable();

            /*
             * Thời điểm hệ thống xử lý kết quả cuối cùng.
             */
            $table->timestamp('processed_at')
                ->nullable();

            $table->timestamps();

            $table->index([
                'booking_id',
                'status',
            ]);
        });
    }

    /**
     * Xóa bảng.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};  