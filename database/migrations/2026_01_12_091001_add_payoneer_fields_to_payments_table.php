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
        Schema::table('payments', function (Blueprint $table) {
            // Payment Gateway Identifier
            $table->string('payment_gateway')->default('stripe')->after('payment_intent_id');
            
            // Payoneer-specific fields
            $table->string('payoneer_long_id')->nullable()->unique()->after('payment_gateway');
            $table->string('payoneer_transaction_id')->nullable()->after('payoneer_long_id');
            $table->text('payoneer_redirect_url')->nullable()->after('payoneer_transaction_id');
            
            // Add index for Payoneer fields
            $table->index('payoneer_long_id');
            $table->index('payoneer_transaction_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropIndex(['payoneer_long_id']);
            $table->dropIndex(['payoneer_transaction_id']);
            $table->dropColumn([
                'payment_gateway',
                'payoneer_long_id',
                'payoneer_transaction_id',
                'payoneer_redirect_url'
            ]);
        });
    }
};
