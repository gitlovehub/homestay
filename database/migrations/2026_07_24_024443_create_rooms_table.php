<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();

            $table->foreignId('homestay_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('name');
            $table->string('room_code')->unique();
            $table->string('room_type');

            $table->text('description')->nullable();
            $table->string('image')->nullable();

            $table->unsignedBigInteger('price_per_night');

            $table->unsignedInteger('capacity')->default(2);
            $table->unsignedInteger('number_of_beds')->default(1);
            $table->decimal('area', 8, 2)->nullable();

            $table->enum('status', [
                'available',
                'maintenance',
                'inactive',
            ])->default('available');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
