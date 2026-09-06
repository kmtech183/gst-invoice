<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('businesses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('gstin', 15)->nullable()->unique();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->default('Maharashtra');
            $table->string('state_code', 2)->default('27');
            $table->string('pincode', 10)->nullable();
            $table->string('logo_path')->nullable();
            $table->string('invoice_prefix')->default('INV-');
            $table->unsignedInteger('next_invoice_number')->default(1);
            $table->text('terms_and_conditions')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('businesses');
    }
};
