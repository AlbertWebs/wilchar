<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('loan_applications')
            ->where('approval_stage', 'collection_officer')
            ->update(['approval_stage' => 'credit_officer']);

        DB::table('loan_approvals')
            ->where('approval_level', 'collection_officer')
            ->update(['approval_level' => 'credit_officer']);
    }

    public function down(): void
    {
        DB::table('loan_applications')
            ->where('approval_stage', 'credit_officer')
            ->update(['approval_stage' => 'collection_officer']);

        DB::table('loan_approvals')
            ->where('approval_level', 'credit_officer')
            ->update(['approval_level' => 'collection_officer']);
    }
};

