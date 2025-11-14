<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('loan_applications', function (Blueprint $table) {
            if (!Schema::hasColumn('loan_applications', 'repayment_frequency')) {
                $table->string('repayment_frequency')->nullable()->after('interest_rate_type');
            }

            if (!Schema::hasColumn('loan_applications', 'repayment_interval_weeks')) {
                $table->unsignedTinyInteger('repayment_interval_weeks')->nullable()->after('repayment_frequency');
            }

            if (!Schema::hasColumn('loan_applications', 'repayment_cycle_amount')) {
                $table->decimal('repayment_cycle_amount', 12, 2)->nullable()->after('weekly_payment_amount');
            }
        });
    }

    public function down(): void
    {
        Schema::table('loan_applications', function (Blueprint $table) {
            if (Schema::hasColumn('loan_applications', 'repayment_frequency')) {
                $table->dropColumn('repayment_frequency');
            }

            if (Schema::hasColumn('loan_applications', 'repayment_interval_weeks')) {
                $table->dropColumn('repayment_interval_weeks');
            }

            if (Schema::hasColumn('loan_applications', 'repayment_cycle_amount')) {
                $table->dropColumn('repayment_cycle_amount');
            }
        });
    }
};

