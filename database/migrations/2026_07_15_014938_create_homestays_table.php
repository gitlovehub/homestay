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
        Schema::create('homestays', function (Blueprint $table) {
            $table->id();

            $table->foreignId('category_id')
                ->constrained('categories')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreignId('owner_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->string('name');
            $table->string('slug')->unique();
            $table->string('address');
            $table->string('city');
            $table->string('phone', 20)->nullable();
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->boolean('status')->default(true);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('homestays');
    }
};
