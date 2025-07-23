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
            $table->unsignedBigInteger('user_id');
            $table->enum('previous_status', ['pending', 'approved', 'suspended', 'cancelled', 'board_returned', 'board_submitted'])->nullable();
            $table->enum('new_status', ['pending', 'approved', 'suspended', 'cancelled', 'board_returned', 'board_submitted']);
            $table->string('action_type', 50); // 'created', 'approved', 'suspended', 'cancelled', 'board_returned', 'board_submitted'
            $table->text('comments')->nullable(); // মন্তব্য/কারণ
            $table->timestamps();

            // Indexes for better performance
            $table->index(['markaz_agreement_id', 'created_at']);
            $table->index('user_id');

            // Foreign key constraints
            $table->foreign('markaz_agreement_id')->references('id')->on('markaz_agreements')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
