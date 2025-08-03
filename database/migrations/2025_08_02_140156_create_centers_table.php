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
        Schema::create('centers', function (Blueprint $table) {
            $table->id(); // Primary key (auto increment)
            $table->string('code', 10)->unique()->comment('Center Code (D, H, K)');
            $table->string('name', 50)->comment('Center Name (Darshiyat, Hifz, Qiraat)');
            $table->string('description', 255)->nullable()->comment('Optional description');
            $table->timestamps();

            // Indexes for better performance
            $table->index('code');
            $table->index('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('centers');
    }
};
