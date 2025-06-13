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
            // Drop the existing column and recreate it with new enum values
            $table->dropColumn('appointment_status');
        });
        Schema::table('interview_appointment_suggestions', function (Blueprint $table) {
            $table->enum('appointment_status', ['draft', 'pending', 'confirmed', 'accepted', 'declined'])
                ->default('draft');
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('interview_appointment_suggestions', function (Blueprint $table) {
            $table->dropColumn('appointment_status');
        });
        Schema::table('interview_appointment_suggestions', function (Blueprint $table) {
            $table->enum('appointment_status', ['pending', 'confirmed', 'accepted', 'declined'])
                ->default('pending');
        });
    }
};
