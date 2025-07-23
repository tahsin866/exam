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
            // Add missing columns used in the model
            $table->string('madrasha_Name')->nullable()->after('user_id');
            $table->string('exam_name')->nullable()->after('exam_id');
            $table->string('division')->nullable()->after('madrasha_code');
            $table->integer('division_id')->nullable()->after('division');
            $table->string('district')->nullable()->after('division_id');
            $table->integer('district_id')->nullable()->after('district');
            $table->string('thana_uni')->nullable()->after('district_id');
            $table->integer('tid')->nullable()->after('thana_uni');
            $table->integer('mtype')->nullable()->after('tid');
            $table->integer('Stage')->nullable()->after('mtype');
            $table->string('Elhaq_no')->nullable()->after('Stage');
            $table->string('user_name')->nullable()->after('Elhaq_no');
            $table->text('admin_feedback')->nullable()->after('status_comment');
            $table->timestamp('processed_at')->nullable()->after('admin_feedback');
            $table->string('feedback_image')->nullable()->after('processed_at');
            $table->timestamp('submitted_at')->nullable()->after('feedback_image');
            $table->string('admin_name')->nullable()->after('submitted_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('markaz_agreements', function (Blueprint $table) {
            $table->dropColumn([
                'madrasha_Name', 'exam_name', 'division', 'division_id', 
                'district', 'district_id', 'thana_uni', 'tid', 'mtype', 
                'Stage', 'Elhaq_no', 'user_name', 'admin_feedback', 
                'processed_at', 'feedback_image', 'submitted_at', 'admin_name'
            ]);
        });
    }
};
