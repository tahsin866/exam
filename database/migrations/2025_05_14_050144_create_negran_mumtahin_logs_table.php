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
        Schema::create('negran_mumtahin_logs', function (Blueprint $table) {
           $table->id();
            $table->unsignedBigInteger('application_id');
            $table->unsignedBigInteger('admin_id');
            $table->unsignedBigInteger('user_id');
            $table->enum('status', ['বোর্ড দাখিল', 'অনুমোদন', 'বোর্ড ফেরত', 'বাতিল']);
            $table->string('actor_type');
            $table->string('admin_name');
            $table->string('user_name');
            $table->string('user_position');
            $table->string('admin_position');
            $table->text('admin_message')->nullable();
            $table->string('admin_feedback_image')->nullable();
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('application_id')
                  ->references('id')
                  ->on('neg_mumtahin_aplications')
                  ->onDelete('cascade');

            // Unique constraints
            $table->unique('admin_id');
            $table->unique('user_id');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('negran_mumtahin_logs');
    }
};
