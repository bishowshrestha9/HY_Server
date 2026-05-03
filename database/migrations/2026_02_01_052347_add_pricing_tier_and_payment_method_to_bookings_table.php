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
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('pricing_tier_label')->nullable()->after('remainingAmount');
            $table->text('pricing_tier_description')->nullable()->after('pricing_tier_label');
            $table->string('payment_method')->nullable()->after('pricing_tier_description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['pricing_tier_label', 'pricing_tier_description', 'payment_method']);
        });
    }
};
