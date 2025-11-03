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
        Schema::create('kyc_documents', function (Blueprint $table) {
            $table->id();
            // Use explicit unsignedBigInteger to ensure type matching
            $table->unsignedBigInteger('loan_application_id');
            $table->enum('document_type', ['id', 'passport', 'selfie', 'proof_of_address', 'business_license', 'bank_statement', 'other'])->default('id');
            $table->string('document_name');
            $table->string('file_path');
            $table->string('file_type')->nullable(); // mime type
            $table->unsignedBigInteger('file_size')->nullable(); // in bytes
            $table->enum('verification_status', ['pending', 'verified', 'rejected'])->default('pending');
            $table->text('rejection_reason')->nullable();
            $table->unsignedBigInteger('verified_by')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();

            // Add foreign key constraints separately
            $table->foreign('loan_application_id', 'kyc_documents_loan_application_id_foreign')
                  ->references('id')
                  ->on('loan_applications')
                  ->onDelete('cascade');
            
            $table->foreign('verified_by', 'kyc_documents_verified_by_foreign')
                  ->references('id')
                  ->on('users')
                  ->onDelete('set null');

            // Indexes for performance
            $table->index('loan_application_id');
            $table->index('document_type');
            $table->index('verification_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kyc_documents');
    }
};

