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
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('middle_name')->nullable();
            $table->date('date_of_birth');
            $table->string('gender');
            $table->string('nationality');
            $table->string('id_number')->unique();
            //pasport photo
            $table->string('photo')->nullable();
            //photos of business premises
            $table->string('phone')->unique();
            $table->string('email')->nullable()->unique();
            $table->string('address')->nullable();
            $table->string('occupation')->nullable();
            $table->string('employer')->nullable();
            $table->enum('status', ['active', 'inactive', 'blacklisted'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
