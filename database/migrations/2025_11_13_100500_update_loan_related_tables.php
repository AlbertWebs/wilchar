<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('loan_applications', function (Blueprint $table) {
            if (!Schema::hasColumn('loan_applications', 'loan_product_id')) {
                $table->unsignedBigInteger('loan_product_id')->nullable()->after('client_id');
            }
        });
        
        // Drop existing foreign key if it exists
        $foreignKeys = DB::select("
            SELECT CONSTRAINT_NAME 
            FROM information_schema.KEY_COLUMN_USAGE 
            WHERE TABLE_SCHEMA = DATABASE() 
            AND TABLE_NAME = 'loan_applications' 
            AND COLUMN_NAME = 'loan_product_id' 
            AND REFERENCED_TABLE_NAME IS NOT NULL
        ");
        
        foreach ($foreignKeys as $foreignKey) {
            Schema::table('loan_applications', function (Blueprint $table) use ($foreignKey) {
                $table->dropForeign($foreignKey->CONSTRAINT_NAME);
            });
        }
        
        Schema::table('loan_applications', function (Blueprint $table) {
            // Add foreign key constraint explicitly
            $table->foreign('loan_product_id')->references('id')->on('loan_products')->nullOnDelete();
            if (!Schema::hasColumn('loan_applications', 'team_id')) {
                $table->foreignId('team_id')->nullable()->after('client_id')->constrained()->nullOnDelete();
            }
            if (!Schema::hasColumn('loan_applications', 'collection_officer_id')) {
                $table->foreignId('collection_officer_id')->nullable()->after('loan_officer_id')->constrained('users')->nullOnDelete();
            }
            if (!Schema::hasColumn('loan_applications', 'finance_officer_id')) {
                $table->foreignId('finance_officer_id')->nullable()->after('credit_officer_id')->constrained('users')->nullOnDelete();
            }
            if (!Schema::hasColumn('loan_applications', 'business_type')) {
                $table->string('business_type')->nullable()->after('loan_type');
            }
            if (!Schema::hasColumn('loan_applications', 'business_location')) {
                $table->string('business_location')->nullable()->after('business_type');
            }
            if (!Schema::hasColumn('loan_applications', 'registration_fee')) {
                $table->decimal('registration_fee', 15, 2)->default(0)->after('duration_months');
            }
            if (!Schema::hasColumn('loan_applications', 'loan_form_path')) {
                $table->string('loan_form_path')->nullable()->after('supporting_documents');
            }
            if (!Schema::hasColumn('loan_applications', 'mpesa_statement_path')) {
                $table->string('mpesa_statement_path')->nullable()->after('loan_form_path');
            }
            if (!Schema::hasColumn('loan_applications', 'business_photo_path')) {
                $table->string('business_photo_path')->nullable()->after('mpesa_statement_path');
            }
            if (!Schema::hasColumn('loan_applications', 'interest_amount')) {
                $table->decimal('interest_amount', 15, 2)->nullable()->after('amount_approved');
            }
            if (!Schema::hasColumn('loan_applications', 'total_repayment_amount')) {
                $table->decimal('total_repayment_amount', 15, 2)->nullable()->after('interest_amount');
            }
            if (!Schema::hasColumn('loan_applications', 'onboarding_data')) {
                $table->json('onboarding_data')->nullable()->after('business_photo_path');
            }
        });

        Schema::table('loans', function (Blueprint $table) {
            if (!Schema::hasColumn('loans', 'loan_application_id')) {
                $table->foreignId('loan_application_id')->nullable()->after('client_id')->constrained()->nullOnDelete();
            }
            if (!Schema::hasColumn('loans', 'loan_product_id')) {
                $table->unsignedBigInteger('loan_product_id')->nullable()->after('loan_type');
            }
        });
        
        // Drop existing foreign key if it exists for loans table
        $loansForeignKeys = DB::select("
            SELECT CONSTRAINT_NAME 
            FROM information_schema.KEY_COLUMN_USAGE 
            WHERE TABLE_SCHEMA = DATABASE() 
            AND TABLE_NAME = 'loans' 
            AND COLUMN_NAME = 'loan_product_id' 
            AND REFERENCED_TABLE_NAME IS NOT NULL
        ");
        
        foreach ($loansForeignKeys as $foreignKey) {
            Schema::table('loans', function (Blueprint $table) use ($foreignKey) {
                $table->dropForeign($foreignKey->CONSTRAINT_NAME);
            });
        }
        
        Schema::table('loans', function (Blueprint $table) {
            // Add foreign key constraint explicitly
            $table->foreign('loan_product_id')->references('id')->on('loan_products')->nullOnDelete();
            if (!Schema::hasColumn('loans', 'team_id')) {
                $table->foreignId('team_id')->nullable()->after('loan_product_id')->constrained()->nullOnDelete();
            }
            if (!Schema::hasColumn('loans', 'interest_amount')) {
                $table->decimal('interest_amount', 15, 2)->default(0)->after('amount_approved');
            }
            if (!Schema::hasColumn('loans', 'total_amount')) {
                $table->decimal('total_amount', 15, 2)->default(0)->after('interest_amount');
            }
            if (!Schema::hasColumn('loans', 'outstanding_balance')) {
                $table->decimal('outstanding_balance', 15, 2)->default(0)->after('total_amount');
            }
            if (!Schema::hasColumn('loans', 'collection_officer_id')) {
                $table->foreignId('collection_officer_id')->nullable()->after('repayment_frequency')->constrained('users')->nullOnDelete();
            }
            if (!Schema::hasColumn('loans', 'recovery_officer_id')) {
                $table->foreignId('recovery_officer_id')->nullable()->after('collection_officer_id')->constrained('users')->nullOnDelete();
            }
            if (!Schema::hasColumn('loans', 'finance_officer_id')) {
                $table->foreignId('finance_officer_id')->nullable()->after('recovery_officer_id')->constrained('users')->nullOnDelete();
            }
            if (!Schema::hasColumn('loans', 'processing_fee')) {
                $table->decimal('processing_fee', 15, 2)->default(0)->after('outstanding_balance');
            }
            if (!Schema::hasColumn('loans', 'late_fee_accrued')) {
                $table->decimal('late_fee_accrued', 15, 2)->default(0)->after('processing_fee');
            }
            if (!Schema::hasColumn('loans', 'next_due_date')) {
                $table->date('next_due_date')->nullable()->after('late_fee_accrued');
            }
        });

        Schema::table('disbursements', function (Blueprint $table) {
            if (!Schema::hasColumn('disbursements', 'processing_fee')) {
                $table->decimal('processing_fee', 15, 2)->default(0)->after('transaction_amount');
            }
            if (!Schema::hasColumn('disbursements', 'processing_notes')) {
                $table->text('processing_notes')->nullable()->after('processing_fee');
            }
            if (!Schema::hasColumn('disbursements', 'approved_by')) {
                $table->foreignId('approved_by')->nullable()->after('disbursed_by')->constrained('users')->nullOnDelete();
            }
            if (!Schema::hasColumn('disbursements', 'approved_at')) {
                $table->dateTime('approved_at')->nullable()->after('approved_by');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('loan_applications', function (Blueprint $table) {
            $table->dropConstrainedForeignIdIfExists('loan_product_id');
            $table->dropConstrainedForeignIdIfExists('team_id');
            $table->dropConstrainedForeignIdIfExists('collection_officer_id');
            $table->dropConstrainedForeignIdIfExists('finance_officer_id');
            $table->dropColumn([
                'business_type',
                'business_location',
                'registration_fee',
                'loan_form_path',
                'mpesa_statement_path',
                'business_photo_path',
                'interest_amount',
                'total_repayment_amount',
                'onboarding_data',
            ]);
        });

        Schema::table('loans', function (Blueprint $table) {
            $table->dropConstrainedForeignIdIfExists('loan_application_id');
            $table->dropConstrainedForeignIdIfExists('loan_product_id');
            $table->dropConstrainedForeignIdIfExists('team_id');
            $table->dropConstrainedForeignIdIfExists('collection_officer_id');
            $table->dropConstrainedForeignIdIfExists('recovery_officer_id');
            $table->dropConstrainedForeignIdIfExists('finance_officer_id');
            $table->dropColumn([
                'interest_amount',
                'total_amount',
                'outstanding_balance',
                'processing_fee',
                'late_fee_accrued',
                'next_due_date',
            ]);
        });

        Schema::table('disbursements', function (Blueprint $table) {
            $table->dropColumn(['processing_fee', 'processing_notes']);
            $table->dropConstrainedForeignIdIfExists('approved_by');
            $table->dropColumn('approved_at');
        });
    }
};

