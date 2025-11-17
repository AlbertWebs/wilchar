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
        if (Schema::hasColumn('loan_approvals', 'approval_level')) {
            DB::statement("ALTER TABLE `loan_approvals` MODIFY COLUMN `approval_level` ENUM('loan_officer', 'credit_officer', 'finance_officer', 'director') NOT NULL");
        }
        
        if (Schema::hasColumn('loan_approvals', 'previous_level')) {
            DB::statement("ALTER TABLE `loan_approvals` MODIFY COLUMN `previous_level` ENUM('loan_officer', 'credit_officer', 'finance_officer', 'director') NULL");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove finance_officer from enum (but keep existing data by converting to credit_officer first)
        if (Schema::hasColumn('loan_approvals', 'approval_level')) {
            // Convert any finance_officer values to credit_officer before removing from enum
            DB::table('loan_approvals')
                ->where('approval_level', 'finance_officer')
                ->update(['approval_level' => 'credit_officer']);
            
            DB::statement("ALTER TABLE `loan_approvals` MODIFY COLUMN `approval_level` ENUM('loan_officer', 'credit_officer', 'director') NOT NULL");
        }
        
        if (Schema::hasColumn('loan_approvals', 'previous_level')) {
            // Convert any finance_officer values to credit_officer before removing from enum
            DB::table('loan_approvals')
                ->where('previous_level', 'finance_officer')
                ->update(['previous_level' => 'credit_officer']);
            
            DB::statement("ALTER TABLE `loan_approvals` MODIFY COLUMN `previous_level` ENUM('loan_officer', 'credit_officer', 'director') NULL");
        }
    }
};
