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
        Schema::create('stk_pushes', function (Blueprint $table) {
            $table->id();
            $table->string('phone_number'); // Customer phone number
            $table->decimal('amount', 15, 2);
            $table->string('account_reference')->nullable();
            $table->string('transaction_desc')->nullable();
            $table->string('merchant_request_id')->unique()->nullable();
            $table->string('checkout_request_id')->unique()->nullable();
            $table->string('mpesa_receipt_number')->nullable();
            $table->string('result_code')->nullable();
            $table->string('result_desc')->nullable();
            $table->integer('result_type')->nullable();
            $table->decimal('balance', 15, 2)->nullable();
            $table->string('transaction_date')->nullable();
            $table->enum('status', ['pending', 'success', 'failed', 'cancelled'])->default('pending');
            $table->json('callback_data')->nullable();
            $table->foreignId('initiated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            
            $table->index('phone_number');
            $table->index('status');
            $table->index('checkout_request_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stk_pushes');
    }
};
