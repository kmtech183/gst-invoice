<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_moves', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('type', 20); // 'in' (purchase), 'out' (sale), 'adjustment', 'damage'
            $table->integer('quantity'); // Positive for 'in', negative for 'out'
            $table->integer('balance_after'); // Snapshot of stock balance after this movement
            $table->nullableMorphs('reference'); // Morph to Invoice, Purchase, etc.
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['business_id', 'product_id']);
            $table->index('type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_moves');
    }
};
