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
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            $table->string('loan_type');
            $table->decimal('amount_requested', 15, 2);
            $table->decimal('amount_approved', 15, 2)->nullable();
            $table->integer('term_months');
            $table->decimal('interest_rate', 5, 2);
            $table->string('repayment_frequency'); // e.g. monthly, weekly
            $table->enum('status', ['pending', 'approved', 'rejected', 'disbursed', 'closed'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loans');
    }
};
