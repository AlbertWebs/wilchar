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
        Schema::table('loan_approvals', function (Blueprint $table) {
            // Add new column first
            if (!Schema::hasColumn('loan_approvals', 'loan_application_id')) {
                $table->foreignId('loan_application_id')->nullable()->after('id');
            }
            if (!Schema::hasColumn('loan_approvals', 'approval_level')) {
                $table->enum('approval_level', ['loan_officer', 'credit_officer', 'director'])->after('loan_application_id');
            }
            if (!Schema::hasColumn('loan_approvals', 'previous_level')) {
                $table->enum('previous_level', ['loan_officer', 'credit_officer', 'director'])->nullable();
            }
            if (!Schema::hasColumn('loan_approvals', 'is_current_level')) {
                $table->boolean('is_current_level')->default(true);
            }
            if (!Schema::hasColumn('loan_approvals', 'rejection_reason')) {
                $table->text('rejection_reason')->nullable();
            }

            // Update level to approval_level if it exists
            if (Schema::hasColumn('loan_approvals', 'level')) {
                $table->dropColumn('level');
            }
        });

        // Add indexes
        Schema::table('loan_approvals', function (Blueprint $table) {
            $table->index('loan_application_id');
            $table->index('approval_level');
            $table->index('status');
            $table->index('approved_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('loan_approvals', function (Blueprint $table) {
            if (Schema::hasColumn('loan_approvals', 'loan_application_id')) {
                $table->renameColumn('loan_application_id', 'loan_id');
            }
            $table->dropColumn([
                'approval_level',
                'previous_level',
                'is_current_level',
                'rejection_reason'
            ]);
            if (!Schema::hasColumn('loan_approvals', 'level')) {
                $table->tinyInteger('level');
            }
        });
    }
};

