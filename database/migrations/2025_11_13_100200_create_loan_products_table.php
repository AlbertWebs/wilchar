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
        Schema::create('loan_products', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->decimal('min_amount', 15, 2)->default(0);
            $table->decimal('max_amount', 15, 2)->nullable();
            $table->unsignedInteger('min_duration_months')->default(1);
            $table->unsignedInteger('max_duration_months')->nullable();
            $table->decimal('base_interest_rate', 5, 2)->default(0);
            $table->decimal('interest_rate_per_month', 5, 2)->default(0);
            $table->decimal('processing_fee_rate', 5, 2)->default(0);
            $table->decimal('late_fee_rate', 5, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loan_products');
    }
};

