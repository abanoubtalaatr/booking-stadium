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
        Schema::table('pitches', function (Blueprint $table) {
            $table->time('operating_start_time')->default('06:00:00')->after('amenities');
            $table->time('operating_end_time')->default('23:00:00')->after('operating_start_time');
            $table->json('operating_days')->default('["monday","tuesday","wednesday","thursday","friday","saturday","sunday"]')->after('operating_end_time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pitches', function (Blueprint $table) {
            $table->dropColumn(['operating_start_time', 'operating_end_time', 'operating_days']);
        });
    }
};
