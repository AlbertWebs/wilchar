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
        Schema::create('approval_email_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('loan_application_id')->constrained()->onDelete('cascade');
            $table->foreignId('sent_by')->nullable()->constrained('users')->onDelete('set null');
            $table->integer('sent_count')->default(0);
            $table->integer('total_recipients')->default(0);
            $table->json('recipients')->nullable(); // Store list of recipient emails
            $table->json('errors')->nullable(); // Store any errors
            $table->timestamp('sent_at');
            $table->timestamps();
            
            $table->index('loan_application_id');
            $table->index('sent_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('approval_email_logs');
    }
};
