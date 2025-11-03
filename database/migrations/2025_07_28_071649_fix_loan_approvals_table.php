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
        // First, migrate data if loan_id exists
        if (Schema::hasColumn('loan_approvals', 'loan_id')) {
            DB::statement('UPDATE loan_approvals SET loan_application_id = loan_id WHERE loan_application_id IS NULL');
        }

        Schema::table('loan_approvals', function (Blueprint $table) {
            // Drop old foreign key if it exists
            if (Schema::hasColumn('loan_approvals', 'loan_id')) {
                $table->dropForeign(['loan_id']);
            }
            
            // Make loan_application_id not nullable and add foreign key
            if (Schema::hasColumn('loan_approvals', 'loan_application_id')) {
                $table->foreignId('loan_application_id')->nullable(false)->change();
                $table->foreign('loan_application_id')->references('id')->on('loan_applications')->onDelete('cascade');
            }
            
            // Drop old loan_id column
            if (Schema::hasColumn('loan_approvals', 'loan_id')) {
                $table->dropColumn('loan_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('loan_approvals', function (Blueprint $table) {
            if (Schema::hasColumn('loan_approvals', 'loan_application_id')) {
                $table->dropForeign(['loan_application_id']);
            }
            if (!Schema::hasColumn('loan_approvals', 'loan_id')) {
                $table->foreignId('loan_id')->after('id');
            }
        });
    }
};

