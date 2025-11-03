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
        Schema::table('loan_applications', function (Blueprint $table) {
            // Add new columns if they don't exist
            if (!Schema::hasColumn('loan_applications', 'application_number')) {
                $table->string('application_number')->unique()->after('id');
            }
            if (!Schema::hasColumn('loan_applications', 'loan_type')) {
                $table->string('loan_type')->nullable()->after('client_id');
            }
            if (!Schema::hasColumn('loan_applications', 'approval_stage')) {
                $table->enum('approval_stage', ['loan_officer', 'credit_officer', 'director', 'completed'])->default('loan_officer')->after('status');
            }
            if (!Schema::hasColumn('loan_applications', 'loan_officer_id')) {
                $table->foreignId('loan_officer_id')->nullable()->constrained('users')->onDelete('set null')->after('created_by');
            }
            if (!Schema::hasColumn('loan_applications', 'credit_officer_id')) {
                $table->foreignId('credit_officer_id')->nullable()->constrained('users')->onDelete('set null');
            }
            if (!Schema::hasColumn('loan_applications', 'director_id')) {
                $table->foreignId('director_id')->nullable()->constrained('users')->onDelete('set null');
            }
            if (!Schema::hasColumn('loan_applications', 'background_check_status')) {
                $table->enum('background_check_status', ['pending', 'passed', 'failed'])->default('pending')->after('approval_stage');
            }
            if (!Schema::hasColumn('loan_applications', 'background_check_notes')) {
                $table->text('background_check_notes')->nullable();
            }
            if (!Schema::hasColumn('loan_applications', 'amount_approved')) {
                $table->decimal('amount_approved', 15, 2)->nullable()->after('amount');
            }
            if (!Schema::hasColumn('loan_applications', 'rejection_reason')) {
                $table->text('rejection_reason')->nullable();
            }
            if (!Schema::hasColumn('loan_applications', 'approved_at')) {
                $table->timestamp('approved_at')->nullable();
            }
            if (!Schema::hasColumn('loan_applications', 'rejected_at')) {
                $table->timestamp('rejected_at')->nullable();
            }

            // Update status enum to include more states
            // Note: This might require dropping and recreating the column in production
        });

        // Add indexes
        Schema::table('loan_applications', function (Blueprint $table) {
            $table->index('application_number');
            $table->index('status');
            $table->index('approval_stage');
            $table->index('client_id');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('loan_applications', function (Blueprint $table) {
            $table->dropForeign(['loan_officer_id']);
            $table->dropForeign(['credit_officer_id']);
            $table->dropForeign(['director_id']);
            $table->dropColumn([
                'application_number',
                'loan_type',
                'approval_stage',
                'loan_officer_id',
                'credit_officer_id',
                'director_id',
                'background_check_status',
                'background_check_notes',
                'amount_approved',
                'rejection_reason',
                'approved_at',
                'rejected_at'
            ]);
        });
    }
};

