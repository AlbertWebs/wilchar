<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('loan_applications', function (Blueprint $table) {
            if (!Schema::hasColumn('loan_applications', 'interest_rate_type')) {
                $table->string('interest_rate_type')->default('annual')->after('interest_rate');
            }

            if (!Schema::hasColumn('loan_applications', 'weekly_payment_amount')) {
                $table->decimal('weekly_payment_amount', 12, 2)->nullable()->after('total_repayment_amount');
            }
        });
    }

    public function down(): void
    {
        Schema::table('loan_applications', function (Blueprint $table) {
            if (Schema::hasColumn('loan_applications', 'interest_rate_type')) {
                $table->dropColumn('interest_rate_type');
            }

            if (Schema::hasColumn('loan_applications', 'weekly_payment_amount')) {
                $table->dropColumn('weekly_payment_amount');
            }
        });
    }
};

