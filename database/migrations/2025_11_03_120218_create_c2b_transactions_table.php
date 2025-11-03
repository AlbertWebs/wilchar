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
        Schema::create('c2b_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_type')->nullable(); // Pay Bill or Buy Goods
            $table->string('trans_id')->unique();
            $table->string('trans_time');
            $table->decimal('trans_amount', 15, 2);
            $table->string('business_short_code');
            $table->string('bill_ref_number')->nullable();
            $table->string('invoice_number')->nullable();
            $table->string('org_account_balance')->nullable();
            $table->string('third_party_trans_id')->nullable();
            $table->string('msisdn'); // Customer phone number
            $table->string('first_name')->nullable();
            $table->string('middle_name')->nullable();
            $table->string('last_name')->nullable();
            $table->enum('status', ['pending', 'completed', 'failed'])->default('pending');
            $table->json('callback_data')->nullable();
            $table->timestamps();
            
            $table->index('trans_id');
            $table->index('msisdn');
            $table->index('status');
            $table->index('trans_time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('c2b_transactions');
    }
};
