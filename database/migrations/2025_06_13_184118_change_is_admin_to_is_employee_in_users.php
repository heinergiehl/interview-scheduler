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
            // Change is_admin field to is_applicant
            $table->renameColumn('is_admin', 'is_applicant');
            // Set default value to false
            $table->boolean('is_applicant')->default(false)->change();
            // Add a comment for clarity
            $table->comment = 'Indicates if the user is an employee (true) or not (false)';
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Rename is_applicant back to is_admin
            $table->renameColumn('is_applicant', 'is_admin');
            // Set default value to false
            $table->boolean('is_admin')->default(false)->change();
            // Add a comment for clarity
            $table->comment = 'Indicates if the user is an applicant (true) or not (false)';
        });
    }
};
