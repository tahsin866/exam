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
        Schema::table('markaz_agreements', function (Blueprint $table) {
            // Update existing status enum to include board_submitted
            DB::statement("ALTER TABLE markaz_agreements MODIFY COLUMN status ENUM('pending', 'approved', 'suspended', 'cancelled', 'board_returned', 'board_submitted') DEFAULT 'pending'");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('markaz_agreements', function (Blueprint $table) {
            // Revert status enum to exclude board_submitted
            DB::statement("ALTER TABLE markaz_agreements MODIFY COLUMN status ENUM('pending', 'approved', 'suspended', 'cancelled', 'board_returned') DEFAULT 'pending'");
        });
    }
};
