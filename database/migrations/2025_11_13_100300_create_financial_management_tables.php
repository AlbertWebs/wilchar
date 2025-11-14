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
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('category')->nullable();
            $table->date('purchase_date')->nullable();
            $table->decimal('purchase_price', 15, 2)->default(0);
            $table->decimal('current_value', 15, 2)->default(0);
            $table->enum('depreciation_method', ['straight_line', 'declining_balance', 'none'])->default('straight_line');
            $table->unsignedInteger('useful_life_months')->nullable();
            $table->decimal('residual_value', 15, 2)->default(0);
            $table->decimal('monthly_depreciation', 15, 2)->default(0);
            $table->text('notes')->nullable();
            $table->foreignId('assigned_team_id')->nullable()->constrained('teams')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();
            $table->string('category');
            $table->string('description')->nullable();
            $table->date('expense_date');
            $table->decimal('amount', 15, 2);
            $table->foreignId('account_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('team_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('recorded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->json('metadata')->nullable();
            $table->timestamps();
        });

        Schema::create('liabilities', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('creditor')->nullable();
            $table->decimal('amount', 15, 2);
            $table->decimal('outstanding_balance', 15, 2)->default(0);
            $table->date('issued_on')->nullable();
            $table->date('due_date')->nullable();
            $table->enum('status', ['pending', 'active', 'settled', 'overdue'])->default('pending');
            $table->text('notes')->nullable();
            $table->foreignId('team_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('shareholders', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->decimal('shares_owned', 15, 2)->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('shareholder_contributions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shareholder_id')->constrained()->cascadeOnDelete();
            $table->date('contribution_date');
            $table->decimal('amount', 15, 2);
            $table->string('reference')->nullable();
            $table->string('description')->nullable();
            $table->timestamps();
        });

        Schema::create('trial_balances', function (Blueprint $table) {
            $table->id();
            $table->date('period_start');
            $table->date('period_end');
            $table->decimal('total_debits', 18, 2)->default(0);
            $table->decimal('total_credits', 18, 2)->default(0);
            $table->json('snapshot')->nullable();
            $table->foreignId('generated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('trial_balance_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trial_balance_id')->constrained()->cascadeOnDelete();
            $table->foreignId('account_id')->nullable()->constrained()->nullOnDelete();
            $table->string('account_name');
            $table->decimal('debit', 15, 2)->default(0);
            $table->decimal('credit', 15, 2)->default(0);
            $table->timestamps();
        });

        Schema::create('account_balances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('account_id')->constrained()->cascadeOnDelete();
            $table->date('balance_date');
            $table->decimal('opening_balance', 15, 2)->default(0);
            $table->decimal('closing_balance', 15, 2)->default(0);
            $table->decimal('credits', 15, 2)->default(0);
            $table->decimal('debits', 15, 2)->default(0);
            $table->timestamps();
            $table->unique(['account_id', 'balance_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('account_balances');
        Schema::dropIfExists('trial_balance_entries');
        Schema::dropIfExists('trial_balances');
        Schema::dropIfExists('shareholder_contributions');
        Schema::dropIfExists('shareholders');
        Schema::dropIfExists('liabilities');
        Schema::dropIfExists('expenses');
        Schema::dropIfExists('assets');
    }
};

