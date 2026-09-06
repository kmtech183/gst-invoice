<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('company_name')->nullable();
            $table->string('gstin', 15)->nullable();
            $table->string('email')->nullable();
            $table->string('phone', 20)->nullable();
            $table->text('billing_address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->default('Maharashtra');
            $table->string('state_code', 2)->default('27');
            $table->string('pincode', 10)->nullable();
            $table->timestamps();

            $table->index(['business_id', 'name']);
            $table->index(['business_id', 'gstin']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
