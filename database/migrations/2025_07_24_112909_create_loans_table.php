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
            $table->decimal('amount', 15, 2);
            $table->decimal('interest_rate', 5, 2); // e.g. 12.5%
            $table->integer('duration_months'); // e.g. 12 months
            $table->enum('status', ['pending', 'approved', 'disbursed', 'rejected', 'completed'])->default('pending');
            $table->date('application_date')->nullable();
            $table->date('approval_date')->nullable();
            $table->date('disbursement_date')->nullable();
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
