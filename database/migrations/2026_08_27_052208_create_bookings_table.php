<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_reference')->unique();
            $table->foreignId('customer_id')->constrained()->onDelete('restrict');
            $table->foreignId('flight_id')->constrained()->onDelete('restrict');
            $table->foreignId('seat_id')->constrained()->onDelete('restrict');
            $table->unsignedBigInteger('total_cost');
            $table->unsignedInteger('overweight')->default(0);
            $table->dateTime('booking_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
