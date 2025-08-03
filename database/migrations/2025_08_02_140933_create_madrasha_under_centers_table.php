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
        Schema::create('madrasha_under_centers', function (Blueprint $table) {
            $table->id(); // Primary key (auto increment)
            $table->string('parent_madrasha_id', 50)->comment('Center Madrasha ID (FK to schools.madrasha_id)');
            $table->unsignedBigInteger('center_id')->comment('Center ID (FK to centers.id) - Darshiyat/Hifz/Qiraat');
            $table->string('child_madrasha_id', 50)->comment('Under Madrasha ID (FK to schools.madrasha_id)');
            $table->timestamps();

            // Indexes for better performance
            $table->index('parent_madrasha_id');
            $table->index('center_id');
            $table->index('child_madrasha_id');

            // Composite index for parent-center-child combination
            $table->index(['parent_madrasha_id', 'center_id', 'child_madrasha_id'], 'parent_center_child_idx');

            // Unique constraint to prevent duplicate parent-center-child combinations
            $table->unique(['parent_madrasha_id', 'center_id', 'child_madrasha_id'], 'unique_parent_center_child');

            // Foreign key constraints
            $table->foreign('parent_madrasha_id')->references('madrasha_id')->on('schools')->onDelete('cascade');
            $table->foreign('center_id')->references('id')->on('centers')->onDelete('cascade');
            $table->foreign('child_madrasha_id')->references('madrasha_id')->on('schools')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('madrasha_under_centers');
    }
};
