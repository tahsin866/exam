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
        Schema::table('markaz_agreements', function (Blueprint $table) {
            $table->enum('status', ['pending', 'approved', 'suspended', 'cancelled', 'board_returned'])
                  ->default('pending')
                  ->after('committee_resolution');

            $table->unsignedBigInteger('created_by')->nullable()->after('status');
            $table->unsignedBigInteger('approved_by')->nullable()->after('created_by');
            $table->timestamp('approved_at')->nullable()->after('approved_by');
            $table->text('status_comment')->nullable()->comment('কারণ/মন্তব্য')->after('approved_at');

            // Foreign key constraints
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('approved_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('markaz_agreements', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropForeign(['approved_by']);
            $table->dropColumn(['status', 'created_by', 'approved_by', 'approved_at', 'status_comment']);
        });
    }
};
