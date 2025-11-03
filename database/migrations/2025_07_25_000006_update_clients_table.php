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
        Schema::table('clients', function (Blueprint $table) {
            // Add client_code if it doesn't exist
            if (!Schema::hasColumn('clients', 'client_code')) {
                $table->string('client_code')->unique()->after('id');
            }
            
            // Add more contact fields
            if (!Schema::hasColumn('clients', 'alternate_phone')) {
                $table->string('alternate_phone')->nullable();
            }
            if (!Schema::hasColumn('clients', 'mpesa_phone')) {
                $table->string('mpesa_phone')->nullable();
            }
            
            // Add KYC completion status
            if (!Schema::hasColumn('clients', 'kyc_completed')) {
                $table->boolean('kyc_completed')->default(false);
            }
            if (!Schema::hasColumn('clients', 'kyc_completed_at')) {
                $table->timestamp('kyc_completed_at')->nullable();
            }

            // Add credit score fields
            if (!Schema::hasColumn('clients', 'credit_score')) {
                $table->integer('credit_score')->nullable();
            }
            if (!Schema::hasColumn('clients', 'credit_score_updated_at')) {
                $table->timestamp('credit_score_updated_at')->nullable();
            }

            // Make created_by a foreign key if it's not already
            if (Schema::hasColumn('clients', 'created_by') && !Schema::hasColumn('clients', 'created_by_user_id')) {
                // Check if it's a string, convert to foreign key
                $table->foreignId('created_by_user_id')->nullable()->constrained('users')->onDelete('set null')->after('created_by');
            }
        });

        // Add indexes
        Schema::table('clients', function (Blueprint $table) {
            $table->index('client_code');
            $table->index('phone');
            $table->index('email');
            $table->index('status');
            $table->index('kyc_completed');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn([
                'client_code',
                'alternate_phone',
                'mpesa_phone',
                'kyc_completed',
                'kyc_completed_at',
                'credit_score',
                'credit_score_updated_at',
                'created_by_user_id'
            ]);
        });
    }
};

