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
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('owner_id')->constrained('users')->onDelete('cascade')->after('id');
            $table->string('plate_number', 10)->unique();
            $table->string('brand', 50);
            $table->string('model', 50);
            $table->year('year');
            $table->string('color', 30);
            $table->integer('mileage')->nullable();
            $table->decimal('daily_rental_rate', 10, 2);
            $table->enum('status', ['available', 'rented', 'maintenance', 'not_available'])->default('not_available');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cars');
    }
};
