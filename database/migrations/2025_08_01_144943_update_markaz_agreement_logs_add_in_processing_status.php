<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update the enum columns to include 'in_processing'
        DB::statement("ALTER TABLE `markaz_agreement_logs` MODIFY COLUMN `previous_status` ENUM('pending', 'approved', 'suspended', 'cancelled', 'board_returned', 'board_submitted', 'in_processing') NULL");
        DB::statement("ALTER TABLE `markaz_agreement_logs` MODIFY COLUMN `new_status` ENUM('pending', 'approved', 'suspended', 'cancelled', 'board_returned', 'board_submitted', 'in_processing') NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to original enum values
        DB::statement("ALTER TABLE `markaz_agreement_logs` MODIFY COLUMN `previous_status` ENUM('pending', 'approved', 'suspended', 'cancelled', 'board_returned', 'board_submitted') NULL");
        DB::statement("ALTER TABLE `markaz_agreement_logs` MODIFY COLUMN `new_status` ENUM('pending', 'approved', 'suspended', 'cancelled', 'board_returned', 'board_submitted') NOT NULL");
    }
};
