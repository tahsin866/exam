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
        Schema::create('markaz_agreement_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('markaz_agreement_id');
            $table->unsignedBigInteger('user_id')->comment('যে action নিয়েছে');
            $table->enum('previous_status', ['pending', 'approved', 'suspended', 'cancelled', 'board_returned'])->nullable();
            $table->enum('new_status', ['pending', 'approved', 'suspended', 'cancelled', 'board_returned']);
            $table->string('action_type', 50)->comment('created, approved, suspended, cancelled, board_returned');
            $table->text('comments')->nullable()->comment('মন্তব্য/কারণ');
            $table->json('additional_data')->nullable()->comment('অতিরিক্ত তথ্য যদি দরকার হয়');
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('markaz_agreement_id')->references('id')->on('markaz_agreements')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            // Indexes for better performance
            $table->index(['markaz_agreement_id', 'created_at']);
            $table->index(['user_id', 'action_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('markaz_agreement_logs');
    }
};
