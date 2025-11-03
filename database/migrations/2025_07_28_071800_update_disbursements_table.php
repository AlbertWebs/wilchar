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
        Schema::table('disbursements', function (Blueprint $table) {
            // Add new column first
            if (!Schema::hasColumn('disbursements', 'loan_application_id')) {
                $table->foreignId('loan_application_id')->nullable()->after('id');
            }

            // M-Pesa B2C specific fields
            if (!Schema::hasColumn('disbursements', 'mpesa_request_id')) {
                $table->string('mpesa_request_id')->nullable()->unique()->after('reference');
            }
            if (!Schema::hasColumn('disbursements', 'mpesa_response_code')) {
                $table->string('mpesa_response_code')->nullable();
            }
            if (!Schema::hasColumn('disbursements', 'mpesa_response_description')) {
                $table->text('mpesa_response_description')->nullable();
            }
            if (!Schema::hasColumn('disbursements', 'mpesa_result_code')) {
                $table->string('mpesa_result_code')->nullable();
            }
            if (!Schema::hasColumn('disbursements', 'mpesa_result_description')) {
                $table->text('mpesa_result_description')->nullable();
            }
            if (!Schema::hasColumn('disbursements', 'mpesa_originator_conversation_id')) {
                $table->string('mpesa_originator_conversation_id')->nullable();
            }
            if (!Schema::hasColumn('disbursements', 'mpesa_conversation_id')) {
                $table->string('mpesa_conversation_id')->nullable();
            }
            if (!Schema::hasColumn('disbursements', 'recipient_phone')) {
                $table->string('recipient_phone')->nullable();
            }
            if (!Schema::hasColumn('disbursements', 'transaction_receipt')) {
                $table->string('transaction_receipt')->nullable();
            }
            if (!Schema::hasColumn('disbursements', 'transaction_amount')) {
                $table->decimal('transaction_amount', 15, 2)->nullable();
            }
            if (!Schema::hasColumn('disbursements', 'mpesa_callback_data')) {
                $table->json('mpesa_callback_data')->nullable();
            }
            if (!Schema::hasColumn('disbursements', 'retry_count')) {
                $table->integer('retry_count')->default(0);
            }
            if (!Schema::hasColumn('disbursements', 'last_retry_at')) {
                $table->timestamp('last_retry_at')->nullable();
            }

            // Update method to be enum
            if (Schema::hasColumn('disbursements', 'method')) {
                $table->enum('method', ['mpesa_b2c', 'bank_transfer', 'cash', 'cheque'])->default('mpesa_b2c')->change();
            }
        });

        // Add indexes
        Schema::table('disbursements', function (Blueprint $table) {
            $table->index('loan_application_id');
            $table->index('status');
            $table->index('method');
            $table->index('mpesa_request_id');
            $table->index('recipient_phone');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('disbursements', function (Blueprint $table) {
            if (Schema::hasColumn('disbursements', 'loan_application_id')) {
                $table->renameColumn('loan_application_id', 'loan_id');
            }
            $table->dropColumn([
                'mpesa_request_id',
                'mpesa_response_code',
                'mpesa_response_description',
                'mpesa_result_code',
                'mpesa_result_description',
                'mpesa_originator_conversation_id',
                'mpesa_conversation_id',
                'recipient_phone',
                'transaction_receipt',
                'transaction_amount',
                'mpesa_callback_data',
                'retry_count',
                'last_retry_at'
            ]);
        });
    }
};

