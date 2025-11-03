<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Migrate data if loan_id exists
        if (Schema::hasColumn('disbursements', 'loan_id') && Schema::hasColumn('disbursements', 'loan_application_id')) {
            // This would need a join through loans table - for now, we'll leave it nullable
            // In production, you'd need to map loan_id -> loan_application_id properly
        }

        Schema::table('disbursements', function (Blueprint $table) {
            // Drop old foreign key if it exists
            if (Schema::hasColumn('disbursements', 'loan_id')) {
                $table->dropForeign(['loan_id']);
            }
            
            // Make loan_application_id not nullable and add foreign key
            if (Schema::hasColumn('disbursements', 'loan_application_id')) {
                $table->foreignId('loan_application_id')->nullable(false)->change();
                $table->foreign('loan_application_id')->references('id')->on('loan_applications')->onDelete('cascade');
            }
            
            // Drop old loan_id column if it exists
            if (Schema::hasColumn('disbursements', 'loan_id')) {
                $table->dropColumn('loan_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('disbursements', function (Blueprint $table) {
            if (Schema::hasColumn('disbursements', 'loan_application_id')) {
                $table->dropForeign(['loan_application_id']);
            }
            if (!Schema::hasColumn('disbursements', 'loan_id')) {
                $table->foreignId('loan_id')->after('id');
            }
        });
    }
};

