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
        Schema::table('users', function (Blueprint $table) {
            $table->enum('user_type', ['admin', 'madrasa'])->default('madrasa')->after('email');
            $table->string('phone')->nullable()->after('user_type');
            $table->string('nid')->nullable()->after('phone');
            $table->string('brn')->nullable()->after('nid');
            $table->text('address')->nullable()->after('brn');
            $table->string('profile_image')->nullable()->after('address');
            $table->boolean('is_active')->default(true)->after('profile_image');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'user_type',
                'phone',
                'nid',
                'brn',
                'address',
                'profile_image',
                'is_active'
            ]);
        });
    }
};
