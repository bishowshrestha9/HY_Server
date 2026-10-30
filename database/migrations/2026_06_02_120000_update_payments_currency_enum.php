<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Normalize existing currency values to uppercase
        DB::statement("UPDATE payments SET currency = UPPER(currency) WHERE currency IS NOT NULL");

        // Map any unknown currencies to the default 'NRS' so enum conversion won't fail
        DB::statement("UPDATE payments SET currency = 'NRS' WHERE UPPER(currency) NOT IN ('USD','NRS') OR currency IS NULL");

        // Alter the column to use an ENUM type (MySQL) with only USD and NRS.
        DB::statement("ALTER TABLE payments MODIFY currency ENUM('USD','NRS') NOT NULL DEFAULT 'NRS'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to a short string column with previous default
        DB::statement("ALTER TABLE payments MODIFY currency VARCHAR(3) DEFAULT 'aud'");
    }
};
