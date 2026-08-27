<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('flights', function (Blueprint $table) {
            $table->id();
            $table->string('flight_number')->unique();
            $table->string('departure_city');
            $table->string('destination_city');
            $table->dateTime('departure_time');
            $table->unsignedSmallInteger('trip_duration_minutes');
            $table->unsignedInteger('seats_count');
            $table->enum('status', ['upcoming', 'departed', 'arrived', 'cancelled'])
                ->default('upcoming');
            $table->unsignedBigInteger('price');
            $table->unsignedInteger('overweight_charge');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flights');
    }
};
