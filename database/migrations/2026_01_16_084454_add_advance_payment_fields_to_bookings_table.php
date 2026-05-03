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
            $table->decimal('paidAmount', 10, 2)->nullable()->after('price');
            $table->string('paymentType')->nullable()->after('paidAmount');
            $table->decimal('advancePercentage', 5, 2)->nullable()->after('paymentType');
            $table->decimal('remainingAmount', 10, 2)->nullable()->after('advancePercentage');
            $table->string('phone')->nullable()->after('remainingAmount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['paidAmount', 'paymentType', 'advancePercentage', 'remainingAmount', 'phone']);
        });
    }
};
