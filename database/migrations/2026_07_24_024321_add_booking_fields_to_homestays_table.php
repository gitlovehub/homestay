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
        Schema::table('homestays', function (Blueprint $table) {
            $table->unsignedBigInteger('base_price')
                ->default(0)
                ->after('description');

            $table->decimal('latitude', 10, 7)
                ->nullable()
                ->after('base_price');

            $table->decimal('longitude', 10, 7)
                ->nullable()
                ->after('latitude');

            $table->time('check_in_time')
                ->nullable()
                ->after('longitude');

            $table->time('check_out_time')
                ->nullable()
                ->after('check_in_time');

            $table->text('policy')
                ->nullable()
                ->after('check_out_time');

            $table->string('thumbnail')
                ->nullable()
                ->after('policy');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('homestays', function (Blueprint $table) {
            $table->dropColumn([
                'base_price',
                'latitude',
                'longitude',
                'check_in_time',
                'check_out_time',
                'policy',
                'thumbnail',
            ]);
        });
    }
};
