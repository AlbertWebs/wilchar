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
        Schema::create('mpesa_account_balances', function (Blueprint $table) {
            $table->id();
            $table->string('initiator_name');
            $table->string('command_id')->default('AccountBalance');
            $table->string('party_a'); // Shortcode
            $table->string('identifier_type')->default('4'); // 4 = Organization
            $table->string('queue_timeout_url');
            $table->string('result_url');
            $table->string('originator_conversation_id')->unique()->nullable();
            $table->string('conversation_id')->nullable();
            $table->integer('result_code')->nullable();
            $table->string('result_desc')->nullable();
            $table->decimal('working_account_balance', 15, 2)->nullable();
            $table->decimal('utility_account_balance', 15, 2)->nullable();
            $table->decimal('charges_paid_account_balance', 15, 2)->nullable();
            $table->string('account_balance_time')->nullable();
            $table->enum('status', ['pending', 'success', 'failed'])->default('pending');
            $table->json('response_data')->nullable();
            $table->foreignId('requested_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            
            $table->index('originator_conversation_id');
            $table->index('status');
            $table->index('party_a');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mpesa_account_balances');
    }
};
