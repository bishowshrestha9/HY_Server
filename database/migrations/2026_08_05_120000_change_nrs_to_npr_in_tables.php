<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update payments table records and alter schema
        if (Schema::hasColumn('payments', 'currency')) {
            // Step 1: Temporarily allow both NRS and NPR
            DB::statement("ALTER TABLE payments MODIFY currency ENUM('USD','NRS','NPR') NOT NULL DEFAULT 'NPR'");
            // Step 2: Update all NRS records to NPR
            DB::statement("UPDATE payments SET currency = 'NPR' WHERE currency = 'NRS'");
            // Step 3: Remove NRS from the ENUM definition
            DB::statement("ALTER TABLE payments MODIFY currency ENUM('USD','NPR') NOT NULL DEFAULT 'NPR'");
        }

        // Update services table records and alter schema
        if (Schema::hasColumn('services', 'currency')) {
            // Step 1: Temporarily allow both NRS and NPR
            DB::statement("ALTER TABLE services MODIFY currency ENUM('USD','NRS','NPR') NOT NULL DEFAULT 'NPR'");
            // Step 2: Update all NRS records to NPR
            DB::statement("UPDATE services SET currency = 'NPR' WHERE currency = 'NRS'");
            // Step 3: Remove NRS from the ENUM definition
            DB::statement("ALTER TABLE services MODIFY currency ENUM('USD','NPR') NOT NULL DEFAULT 'NPR'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert payments table
        if (Schema::hasColumn('payments', 'currency')) {
            // Step 1: Temporarily allow both NRS and NPR
            DB::statement("ALTER TABLE payments MODIFY currency ENUM('USD','NRS','NPR') NOT NULL DEFAULT 'NRS'");
            // Step 2: Update all NPR records to NRS
            DB::statement("UPDATE payments SET currency = 'NRS' WHERE currency = 'NPR'");
            // Step 3: Remove NPR from the ENUM definition
            DB::statement("ALTER TABLE payments MODIFY currency ENUM('USD','NRS') NOT NULL DEFAULT 'NRS'");
        }

        // Revert services table
        if (Schema::hasColumn('services', 'currency')) {
            // Step 1: Temporarily allow both NRS and NPR
            DB::statement("ALTER TABLE services MODIFY currency ENUM('USD','NRS','NPR') NOT NULL DEFAULT 'NRS'");
            // Step 2: Update all NPR records to NRS
            DB::statement("UPDATE services SET currency = 'NRS' WHERE currency = 'NPR'");
            // Step 3: Remove NPR from the ENUM definition
            DB::statement("ALTER TABLE services MODIFY currency ENUM('USD','NRS') NOT NULL DEFAULT 'NRS'");
        }
    }
};
