<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained()->cascadeOnDelete();
            $table->foreignId('category_id')->nullable()->constrained('product_categories')->nullOnDelete();
            $table->string('name');
            $table->string('slug');
            $table->string('sku')->nullable();
            $table->string('hsn_code', 8)->nullable(); // Indian HSN/SAC Code
            $table->text('description')->nullable();
            $table->decimal('purchase_price', 12, 2)->default(0.00);
            $table->decimal('selling_price', 12, 2)->default(0.00);
            $table->unsignedTinyInteger('gst_rate')->default(18); // 0, 5, 12, 18, 28
            $table->integer('stock')->default(0);
            $table->integer('reorder_level')->default(5);
            $table->string('unit', 20)->default('pcs'); // pcs, kg, ltr, box, meter
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['business_id', 'is_active']);
            $table->index(['business_id', 'stock']);
            $table->index('hsn_code');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
