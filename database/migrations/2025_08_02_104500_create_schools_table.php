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
        Schema::create('schools', function (Blueprint $table) {
            $table->id(); // Primary key (auto increment)
            $table->string('madrasha_id', 50)->unique(); // Madrasha unique ID
            $table->integer('MType')->comment('Madrasha Type');
            $table->string('ElhaqNo', 50)->nullable()->comment('Elhaq Number');
            $table->integer('Stage')->nullable()->comment('Stage');
            $table->integer('StageSerial')->nullable()->comment('Stage Serial');
            $table->string('MNName', 100)->nullable()->comment('Madrasha Short Name');
            $table->string('Ara_MName', 350)->nullable()->comment('Arabic Name');
            $table->string('MName', 250)->comment('Full Name');

            // Address and contact fields
            $table->unsignedBigInteger('DID')->nullable()->comment('Division ID');
            $table->unsignedBigInteger('DES_ID')->nullable()->comment('District ID');
            $table->unsignedBigInteger('TID')->nullable()->comment('Thana ID');
            $table->string('Village', 200)->nullable()->comment('Village');
            $table->string('Post', 100)->nullable()->comment('Post Office');
            $table->string('mobile', 15)->nullable()->comment('Mobile Number');

            // Status and metadata
            $table->boolean('EnableDisable')->default(true)->comment('Active status');
            $table->year('Year')->nullable()->comment('Year');
            $table->string('MMLabel', 100)->nullable()->comment('MM Label');
            $table->timestamp('EditDate')->nullable()->comment('Edit Date');

            // Laravel timestamps
            $table->timestamps();

            // Indexes for better performance
            $table->index('MType');
            $table->index('Stage');
            $table->index('DID');
            $table->index('DES_ID');
            $table->index('TID');
            $table->index('EnableDisable');
            $table->index('Year');

            // Foreign key constraints (uncomment if you have these tables)
            // $table->foreign('DID')->references('id')->on('divisions')->onDelete('set null');
            // $table->foreign('DES_ID')->references('id')->on('districts')->onDelete('set null');
            // $table->foreign('TID')->references('id')->on('thanas')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schools');
    }
};
