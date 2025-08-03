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
        Schema::create('madrasha_centers', function (Blueprint $table) {
            $table->id(); // Primary key (auto increment)
            $table->string('madrasha_id', 50)->comment('Foreign key to schools(madrasha_id)');
            $table->unsignedBigInteger('center_id')->comment('Foreign key to centers(id)');
            $table->string('serial_number', 50)->nullable()->comment('Center serial number (H123, D456)');
            $table->timestamps();

            // Indexes for better performance
            $table->index('madrasha_id');
            $table->index('center_id');
            $table->index('serial_number');

            // Unique constraint to prevent duplicate madrasha-center combinations
            $table->unique(['madrasha_id', 'center_id'], 'unique_madrasha_center');

            // Foreign key constraints
            $table->foreign('madrasha_id')->references('madrasha_id')->on('schools')->onDelete('cascade');
            $table->foreign('center_id')->references('id')->on('centers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('madrasha_centers');
    }
};
