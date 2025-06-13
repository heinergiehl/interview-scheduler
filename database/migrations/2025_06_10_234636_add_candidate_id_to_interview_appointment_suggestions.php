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
        Schema::table('interview_appointment_suggestions', function (Blueprint $table) {
            $table->unsignedBigInteger('candidate_id')->nullable();
            $table->foreign('candidate_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
            // Optionally, you can add an index for better performance
            $table->index('candidate_id');
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('interview_appointment_suggestions', function (Blueprint $table) {
            $table->dropForeign(['candidate_id']);
            $table->dropIndex(['candidate_id']);
            $table->dropColumn('candidate_id');
        });
    }
};
