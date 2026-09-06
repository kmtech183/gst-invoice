<?php

namespace App\Livewire\Invoices;

use App\Jobs\SendInvoiceEmailJob;
use App\Contracts\InvoiceNumberGeneratorInterface;
use App\Contracts\TaxCalculatorInterface;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\StockMove;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class InvoiceCreate extends Component
{
    public ?int $customer_id = null;
    public string $invoice_date;
    public string $due_date;
    public string $place_of_supply = 'Maharashtra';
    public string $payment_mode = 'upi';
    public ?string $notes = null;

    public $shipping_charges = 0.00;
    public $discount_amount = 0.00;

    public array $items = [];

    // Calculated summary properties
    public float $subtotal = 0.00;
    public float $cgst_total = 0.00;
    public float $sgst_total = 0.00;
    public float $igst_total = 0.00;
    public float $total_gst = 0.00;
    public float $grand_total = 0.00;
    public bool $is_interstate = false;

    public function mount(): void
    {
        $this->invoice_date = now()->format('Y-m-d');
        $this->due_date = now()->addDays(15)->format('Y-m-d');
        $this->addItem(); // Add initial blank row
    }

    public function addItem(): void
    {
        $this->items[] = [
            'product_id'     => '',
            'product_name'   => '',
            'hsn_code'       => '',
            'quantity'       => 1,
            'unit'           => 'pcs',
            'unit_price'     => 0.00,
            'gst_rate'       => 18,
            'taxable_amount' => 0.00,
            'cgst_amount'    => 0.00,
            'sgst_amount'    => 0.00,
            'igst_amount'    => 0.00,
            'total_amount'   => 0.00,
            'available_stock' => 0,
        ];
    }

    public function removeItem(int $index): void
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
        $this->recalculate();
    }

    public function updatedCustomerId(): void
    {
        if ($this->customer_id) {
            $customer = Customer::find($this->customer_id);
            if ($customer) {
                $this->place_of_supply = $customer->state;
            }
        }
        $this->recalculate();
    }

    public function updatedItems($value, $key): void
    {
        // If product was selected, auto-fill unit_price, hsn_code, gst_rate, etc.
        if (str_ends_with($key, 'product_id')) {
            $parts = explode('.', $key);
            $index = $parts[0];
            $productId = $this->items[$index]['product_id'];

            if ($productId) {
                $product = Product::find($productId);
                if ($product) {
                    $this->items[$index]['product_name']    = $product->name;
                    $this->items[$index]['hsn_code']        = $product->hsn_code ?? '';
                    $this->items[$index]['unit_price']      = (float)$product->selling_price;
                    $this->items[$index]['gst_rate']        = (int)$product->gst_rate;
                    $this->items[$index]['unit']            = $product->unit;
                    $this->items[$index]['available_stock'] = $product->stock;
                }
            }
        }

        $this->recalculate();
    }

    public function updatedShippingCharges(): void
    {
        $this->recalculate();
    }
    public function updatedDiscountAmount(): void
    {
        $this->recalculate();
    }

    public function recalculate(): void
    {
        $user = Auth::user();
        $business = $user->business;
        $customer = $this->customer_id ? Customer::find($this->customer_id) : null;

        $sellerStateCode = $business->state_code ?? '27';
        $buyerStateCode = $customer->state_code ?? $sellerStateCode;

        $this->is_interstate = ($sellerStateCode !== $buyerStateCode);

        $taxCalculator = app(TaxCalculatorInterface::class);

        $this->subtotal = 0.00;
        $this->cgst_total = 0.00;
        $this->sgst_total = 0.00;
        $this->igst_total = 0.00;
        $this->total_gst = 0.00;

        foreach ($this->items as $i => $item) {
            if (empty($item['product_id']) || (int)$item['quantity'] <= 0) {
                continue;
            }

            $taxResult = $taxCalculator->calculateItemTax(
                (float)$item['unit_price'],
                (int)$item['quantity'],
                (int)$item['gst_rate'],
                $sellerStateCode,
                $buyerStateCode
            );

            $this->items[$i]['taxable_amount'] = $taxResult['taxable_amount'];
            $this->items[$i]['cgst_amount']    = $taxResult['cgst_amount'];
            $this->items[$i]['sgst_amount']    = $taxResult['sgst_amount'];
            $this->items[$i]['igst_amount']    = $taxResult['igst_amount'];
            $this->items[$i]['total_amount']   = $taxResult['total_amount'];

            $this->subtotal   += $taxResult['taxable_amount'];
            $this->cgst_total += $taxResult['cgst_amount'];
            $this->sgst_total += $taxResult['sgst_amount'];
            $this->igst_total += $taxResult['igst_amount'];
            $this->total_gst  += $taxResult['total_tax'];
        }

        $shipping = is_numeric($this->shipping_charges) ? (float)$this->shipping_charges : 0.00;
        $discount = is_numeric($this->discount_amount) ? (float)$this->discount_amount : 0.00;

        $this->grand_total = round(
            $this->subtotal + $this->total_gst + $shipping - $discount,
            2
        );
    }

    public function saveInvoice(): void
    {
        $this->validate([
            'customer_id'        => 'required|exists:customers,id',
            'invoice_date'       => 'required|date',
            'due_date'           => 'required|date|after_or_equal:invoice_date',
            'place_of_supply'    => 'required|string|max:100',
            'payment_mode'       => 'required|string',
            'items'              => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity'   => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ], [
            'customer_id.required'        => 'Please select a customer.',
            'items.*.product_id.required' => 'Please choose a product for every item line.',
            'items.*.quantity.min'        => 'Quantity must be at least 1.',
        ]);

        $user = Auth::user();
        $business = $user->business;
        $numberGenerator = app(InvoiceNumberGeneratorInterface::class);

        try {
            $invoice = DB::transaction(function () use ($business, $user, $numberGenerator) {
                // Generate sequential invoice number
                $invoiceNumber = $numberGenerator->generate($business);

                $shipping = is_numeric($this->shipping_charges) ? (float)$this->shipping_charges : 0.00;
                $discount = is_numeric($this->discount_amount) ? (float)$this->discount_amount : 0.00;

                $createdInvoice = Invoice::create([
                    'business_id'      => $business->id,
                    'customer_id'      => $this->customer_id,
                    'created_by'       => $user->id,
                    'invoice_number'   => $invoiceNumber,
                    'invoice_date'     => $this->invoice_date,
                    'due_date'         => $this->due_date,
                    'place_of_supply'  => $this->place_of_supply,
                    'is_interstate'    => $this->is_interstate,
                    'subtotal'         => $this->subtotal,
                    'cgst_total'       => $this->cgst_total,
                    'sgst_total'       => $this->sgst_total,
                    'igst_total'       => $this->igst_total,
                    'total_gst'        => $this->total_gst,
                    'shipping_charges' => $shipping,
                    'discount_amount'  => $discount,
                    'grand_total'      => $this->grand_total,
                    'status'           => 'paid',
                    'payment_mode'     => $this->payment_mode,
                    'notes'            => $this->notes,
                ]);

                foreach ($this->items as $itemData) {
                    $product = Product::where('id', $itemData['product_id'])->lockForUpdate()->first();

                    if ($product->stock < (int)$itemData['quantity']) {
                        throw new \Exception("Insufficient stock for '{$product->name}'. Available: {$product->stock}");
                    }

                    $createdInvoice->items()->create([
                        'product_id'     => $product->id,
                        'product_name'   => $product->name,
                        'hsn_code'       => $itemData['hsn_code'] ?: $product->hsn_code,
                        'quantity'       => $itemData['quantity'],
                        'unit'           => $itemData['unit'],
                        'unit_price'     => $itemData['unit_price'],
                        'gst_rate'       => $itemData['gst_rate'],
                        'taxable_amount' => $itemData['taxable_amount'],
                        'cgst_amount'    => $itemData['cgst_amount'],
                        'sgst_amount'    => $itemData['sgst_amount'],
                        'igst_amount'    => $itemData['igst_amount'],
                        'total_amount'   => $itemData['total_amount'],
                    ]);

                    $newBalance = $product->stock - (int)$itemData['quantity'];
                    $product->update(['stock' => $newBalance]);

                    StockMove::create([
                        'business_id'    => $business->id,
                        'product_id'     => $product->id,
                        'user_id'        => $user->id,
                        'type'           => 'out',
                        'quantity'       => -(int)$itemData['quantity'],
                        'balance_after'  => $newBalance,
                        'reference_type' => Invoice::class,
                        'reference_id'   => $createdInvoice->id,
                        'notes'          => "Sold on Invoice #{$invoiceNumber}",
                    ]);
                }

                return $createdInvoice;
            });

            SendInvoiceEmailJob::dispatch($invoice)->afterResponse();
            session()->flash('success', "Invoice #{$invoice->invoice_number} created successfully!");

            $this->redirect(route('invoices.show', $invoice));
        } catch (\Exception $e) {
            $this->addError('stock_error', $e->getMessage());
        }
    }

    public function render()
    {
        $businessId = Auth::user()->business_id;
        $customers = Customer::where('business_id', $businessId)->orderBy('name')->get();
        $products = Product::where('business_id', $businessId)->active()->orderBy('name')->get();

        return view('livewire.invoices.invoice-create', [
            'customers' => $customers,
            'products'  => $products,
        ]);
    }
}
