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
        Schema::create('interview_appointment_suggestions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employer_id')->constrained('users')->onUpdate('cascade')->onDelete('cascade');
            $table->dateTime('suggested_date_time');
            $table->enum('appointment_status', ['pending', 'confirmed', 'declined'])->default('pending');
            $table->dateTime('responded_at')->nullable();
            $table->timestamps();
            $table->unique(['employer_id', 'suggested_date_time'], 'unique_employer_suggestion');
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('interview_appointment_suggestions');
    }
};
