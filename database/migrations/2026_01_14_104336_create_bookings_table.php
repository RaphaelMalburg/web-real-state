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
        Schema::create('bookings', function (Blueprint $blueprint) {
            $blueprint->id();
            $blueprint->foreignId('property_id')->constrained()->onDelete('cascade');
            $blueprint->string('user_name');
            $blueprint->string('user_email');
            $blueprint->date('booking_date');
            $blueprint->string('status')->default('Pending');
            $blueprint->timestamps();
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
