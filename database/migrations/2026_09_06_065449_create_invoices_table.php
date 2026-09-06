<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained()->cascadeOnDelete();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('invoice_number');
            $table->date('invoice_date');
            $table->date('due_date');
            $table->string('place_of_supply', 50)->default('Maharashtra');
            $table->boolean('is_interstate')->default(false); // true = IGST, false = CGST+SGST
            $table->decimal('subtotal', 12, 2)->default(0.00);
            $table->decimal('cgst_total', 12, 2)->default(0.00);
            $table->decimal('sgst_total', 12, 2)->default(0.00);
            $table->decimal('igst_total', 12, 2)->default(0.00);
            $table->decimal('total_gst', 12, 2)->default(0.00);
            $table->decimal('shipping_charges', 12, 2)->default(0.00);
            $table->decimal('discount_amount', 12, 2)->default(0.00);
            $table->decimal('grand_total', 12, 2)->default(0.00);
            $table->string('status', 20)->default('draft'); // 'draft', 'sent', 'paid', 'cancelled'
            $table->string('payment_mode', 30)->nullable(); // 'cash', 'upi', 'bank_transfer', 'cheque'
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['business_id', 'invoice_number']);
            $table->index(['business_id', 'status']);
            $table->index(['business_id', 'invoice_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
