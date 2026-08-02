<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contact_locations', function (Blueprint $table) {
            $table->id();

            // Tên ngắn hiển thị trên nút: Hà Nội, TP.HCM...
            $table->string('label', 50);

            // Tên đầy đủ của địa điểm
            $table->string('name', 150);

            // Địa chỉ hiển thị
            $table->string('address');

            // Nội dung Google Maps dùng để tìm vị trí
            $table->string('map_query');

            $table->unsignedTinyInteger('sort_order')->default(1);
            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contact_locations');
    }
};