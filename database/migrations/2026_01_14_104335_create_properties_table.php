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
        Schema::create('properties', function (Blueprint $blueprint) {
            $blueprint->id();
            $blueprint->string('title');
            $blueprint->text('description')->nullable();
            $blueprint->decimal('price', 15, 2);
            $blueprint->longText('image_url')->nullable();
            $blueprint->text('gallery_images')->nullable();
            $blueprint->enum('type', ['Rent', 'Sale']);
            $blueprint->string('address')->nullable();
            $blueprint->integer('bedrooms')->default(0);
            $blueprint->integer('bathrooms')->default(0);
            $blueprint->integer('sqft')->default(0);
            $blueprint->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
