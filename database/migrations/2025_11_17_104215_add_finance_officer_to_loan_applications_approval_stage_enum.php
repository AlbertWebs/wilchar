<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // MySQL doesn't support directly modifying ENUM, so we need to use raw SQL
        if (Schema::hasColumn('loan_applications', 'approval_stage')) {
            DB::statement("ALTER TABLE `loan_applications` MODIFY COLUMN `approval_stage` ENUM('loan_officer', 'credit_officer', 'finance_officer', 'director', 'completed') NOT NULL DEFAULT 'loan_officer'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove finance_officer from enum (but keep existing data by converting to credit_officer first)
        if (Schema::hasColumn('loan_applications', 'approval_stage')) {
            // Convert any finance_officer values to credit_officer before removing from enum
            DB::table('loan_applications')
                ->where('approval_stage', 'finance_officer')
                ->update(['approval_stage' => 'credit_officer']);
            
            DB::statement("ALTER TABLE `loan_applications` MODIFY COLUMN `approval_stage` ENUM('loan_officer', 'credit_officer', 'director', 'completed') NOT NULL DEFAULT 'loan_officer'");
        }
    }
};
