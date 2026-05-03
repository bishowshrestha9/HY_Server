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
        Schema::table('services', function (Blueprint $table) {
            $table->string('duration')->nullable()->after('capacity'); // Numeric minutes for internal sorting
            $table->json('pricing_options')->nullable()->after('price'); // JSON array of pricing variants
            $table->json('available_time_slots')->nullable()->after('class_schedule'); // JSON object with dates and times
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn(['duration', 'pricing_options', 'available_time_slots']);
        });
    }
};
