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
        Schema::create('mpesa_transaction_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('initiator_name');
            $table->string('command_id')->default('TransactionStatusQuery');
            $table->string('transaction_id'); // M-Pesa transaction ID
            $table->string('party_a'); // Shortcode
            $table->string('identifier_type')->default('4');
            $table->string('queue_timeout_url');
            $table->string('result_url');
            $table->string('remarks')->nullable();
            $table->string('occasion')->nullable();
            $table->string('originator_conversation_id')->unique()->nullable();
            $table->string('conversation_id')->nullable();
            $table->integer('result_code')->nullable();
            $table->string('result_desc')->nullable();
            $table->decimal('transaction_amount', 15, 2)->nullable();
            $table->string('transaction_date')->nullable();
            $table->string('transaction_type')->nullable();
            $table->string('receipt_number')->nullable();
            $table->enum('status', ['pending', 'found', 'not_found', 'failed'])->default('pending');
            $table->json('response_data')->nullable();
            $table->foreignId('requested_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            
            $table->index('transaction_id');
            $table->index('originator_conversation_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mpesa_transaction_statuses');
    }
};
