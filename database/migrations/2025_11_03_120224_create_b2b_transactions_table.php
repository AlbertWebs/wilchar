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
        Schema::create('b2b_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('initiator_name');
            $table->string('command_id')->default('BusinessPayment');
            $table->decimal('amount', 15, 2);
            $table->string('party_a'); // Sender shortcode
            $table->string('party_b'); // Receiver shortcode
            $table->string('account_reference')->nullable();
            $table->string('remarks')->nullable();
            $table->string('queue_timeout_url');
            $table->string('result_url');
            $table->string('originator_conversation_id')->unique()->nullable();
            $table->string('conversation_id')->nullable();
            $table->string('transaction_receipt')->nullable();
            $table->integer('result_code')->nullable();
            $table->string('result_desc')->nullable();
            $table->decimal('transaction_amount', 15, 2)->nullable();
            $table->string('transaction_date')->nullable();
            $table->enum('status', ['pending', 'success', 'failed'])->default('pending');
            $table->json('callback_data')->nullable();
            $table->foreignId('initiated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            
            $table->index('originator_conversation_id');
            $table->index('status');
            $table->index('party_b');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('b2b_transactions');
    }
};
