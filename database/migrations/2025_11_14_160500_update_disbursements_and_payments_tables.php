<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('disbursements', function (Blueprint $table) {
            if (!Schema::hasColumn('disbursements', 'prepared_by')) {
                $table->foreignId('prepared_by')->nullable()->after('loan_application_id')->constrained('users')->nullOnDelete();
            }

            if (!Schema::hasColumn('disbursements', 'otp_code_hash')) {
                $table->string('otp_code_hash')->nullable()->after('mpesa_originator_conversation_id');
            }

            if (!Schema::hasColumn('disbursements', 'otp_expires_at')) {
                $table->timestamp('otp_expires_at')->nullable()->after('otp_code_hash');
            }

            if (!Schema::hasColumn('disbursements', 'otp_verified_at')) {
                $table->timestamp('otp_verified_at')->nullable()->after('otp_expires_at');
            }

            if (!Schema::hasColumn('disbursements', 'otp_attempts')) {
                $table->unsignedTinyInteger('otp_attempts')->default(0)->after('otp_verified_at');
            }

            if (!Schema::hasColumn('disbursements', 'otp_sent_at')) {
                $table->timestamp('otp_sent_at')->nullable()->after('otp_attempts');
            }
        });

        Schema::table('stk_pushes', function (Blueprint $table) {
            if (!Schema::hasColumn('stk_pushes', 'loan_id')) {
                $table->foreignId('loan_id')->nullable()->after('initiated_by')->constrained()->nullOnDelete();
            }

            if (!Schema::hasColumn('stk_pushes', 'applied_at')) {
                $table->timestamp('applied_at')->nullable()->after('loan_id');
            }
        });

        Schema::table('c2b_transactions', function (Blueprint $table) {
            if (!Schema::hasColumn('c2b_transactions', 'loan_id')) {
                $table->foreignId('loan_id')->nullable()->after('callback_data')->constrained()->nullOnDelete();
            }

            if (!Schema::hasColumn('c2b_transactions', 'applied_at')) {
                $table->timestamp('applied_at')->nullable()->after('loan_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('disbursements', function (Blueprint $table) {
            if (Schema::hasColumn('disbursements', 'otp_sent_at')) {
                $table->dropColumn('otp_sent_at');
            }

            if (Schema::hasColumn('disbursements', 'otp_attempts')) {
                $table->dropColumn('otp_attempts');
            }

            if (Schema::hasColumn('disbursements', 'otp_verified_at')) {
                $table->dropColumn('otp_verified_at');
            }

            if (Schema::hasColumn('disbursements', 'otp_expires_at')) {
                $table->dropColumn('otp_expires_at');
            }

            if (Schema::hasColumn('disbursements', 'otp_code_hash')) {
                $table->dropColumn('otp_code_hash');
            }

            if (Schema::hasColumn('disbursements', 'prepared_by')) {
                $table->dropConstrainedForeignId('prepared_by');
            }
        });

        Schema::table('stk_pushes', function (Blueprint $table) {
            if (Schema::hasColumn('stk_pushes', 'loan_id')) {
                $table->dropConstrainedForeignId('loan_id');
            }

            if (Schema::hasColumn('stk_pushes', 'applied_at')) {
                $table->dropColumn('applied_at');
            }
        });

        Schema::table('c2b_transactions', function (Blueprint $table) {
            if (Schema::hasColumn('c2b_transactions', 'loan_id')) {
                $table->dropConstrainedForeignId('loan_id');
            }

            if (Schema::hasColumn('c2b_transactions', 'applied_at')) {
                $table->dropColumn('applied_at');
            }
        });
    }
};

