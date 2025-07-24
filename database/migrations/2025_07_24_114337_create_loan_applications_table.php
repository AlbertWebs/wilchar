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
        Schema::create('loan_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 15, 2);
            $table->decimal('interest_rate', 5, 2)->default(12.0); // Default rate
            $table->integer('duration_months');
            $table->enum('status', ['submitted', 'under_review', 'approved', 'rejected'])->default('submitted');
            $table->text('purpose')->nullable();
            $table->string('supporting_documents')->nullable(); // e.g. file path or folder
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null'); // loan officer
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loan_applications');
    }
};
