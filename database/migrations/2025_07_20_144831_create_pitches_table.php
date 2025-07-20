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
        Schema::create('pitches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stadium_id')->constrained('stadiums')->onDelete('cascade');
            $table->string('name');
            $table->enum('type', ['football', 'basketball', 'tennis', 'volleyball', 'other'])->default('football');
            $table->enum('surface', ['grass', 'artificial', 'clay', 'concrete'])->default('grass');
            $table->decimal('hourly_rate_60', 8, 2)->default(0); // Rate for 60-minute slots
            $table->decimal('hourly_rate_90', 8, 2)->default(0); // Rate for 90-minute slots
            $table->enum('status', ['available', 'unavailable', 'maintenance'])->default('available');
            $table->integer('capacity')->default(22); // Number of players
            $table->text('description')->nullable();
            $table->text('amenities')->nullable(); // JSON string of amenities
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pitches');
    }
};
