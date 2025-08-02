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
        // First, update the existing table to use VARCHAR instead of ENUM for better flexibility
        DB::statement("ALTER TABLE markaz_agreement_logs MODIFY COLUMN previous_status VARCHAR(50) NULL");
        DB::statement("ALTER TABLE markaz_agreement_logs MODIFY COLUMN new_status VARCHAR(50) NOT NULL");

        // Also add the additional_data column if it doesn't exist
        if (!Schema::hasColumn('markaz_agreement_logs', 'additional_data')) {
            Schema::table('markaz_agreement_logs', function (Blueprint $table) {
                $table->json('additional_data')->nullable()->after('comments');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to enum (though this might cause data loss if new statuses are used)
        DB::statement("ALTER TABLE markaz_agreement_logs MODIFY COLUMN previous_status ENUM('pending', 'approved', 'suspended', 'cancelled', 'board_returned', 'board_submitted') NULL");
        DB::statement("ALTER TABLE markaz_agreement_logs MODIFY COLUMN new_status ENUM('pending', 'approved', 'suspended', 'cancelled', 'board_returned', 'board_submitted') NOT NULL");

        // Remove additional_data column
        if (Schema::hasColumn('markaz_agreement_logs', 'additional_data')) {
            Schema::table('markaz_agreement_logs', function (Blueprint $table) {
                $table->dropColumn('additional_data');
            });
        }
    }
};
