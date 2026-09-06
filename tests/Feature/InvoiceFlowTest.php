<?php

use App\Models\Business;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function createTestSetup(): array
{
    $business = Business::create([
        'name'                => 'Test Enterprise Pvt Ltd',
        'gstin'               => '27AABCU9603R1ZM',
        'state'               => 'Maharashtra',
        'state_code'          => '27',
        'invoice_prefix'      => 'TST/24-25/',
        'next_invoice_number' => 1,
    ]);

    $user = User::create([
        'business_id' => $business->id,
        'name'        => 'Sales Executive',
        'email'       => 'sales@test.in',
        'password'    => bcrypt('password'),
        'role'        => 'sales',
    ]);

    $customer = Customer::create([
        'business_id' => $business->id,
        'name'        => 'Local Mumbai Client',
        'gstin'       => '27BCDEG4567M1ZR',
        'state'       => 'Maharashtra',
        'state_code'  => '27',
        'email'       => 'client@mumbai.in',
    ]);

    $product = Product::create([
        'business_id'    => $business->id,
        'name'           => 'Wireless Barcode Scanner',
        'slug'           => 'wireless-barcode-scanner',
        'selling_price'  => 2500.00,
        'gst_rate'       => 18,
        'stock'          => 10,
        'reorder_level'  => 2,
        'unit'           => 'pcs',
        'is_active'      => true,
    ]);

    return compact('business', 'user', 'customer', 'product');
}

it('allows authenticated users to view dashboard and invoices list', function () {
    $setup = createTestSetup();

    $this->actingAs($setup['user'])
        ->get(route('dashboard'))
        ->assertOk()
        ->assertSee('Executive Dashboard');

    $this->actingAs($setup['user'])
        ->get(route('invoices.index'))
        ->assertOk()
        ->assertSee('GST Invoices');
});

it('blocks unauthenticated visitors from accessing invoice creation', function () {
    $this->get(route('invoices.create'))
        ->assertRedirect(route('login'));
});

it('forbids cross-tenant invoice access', function () {
    $setup = createTestSetup();

    // Another business
    $otherBusiness = Business::create([
        'name'       => 'Competitor Corp',
        'state'      => 'Delhi',
        'state_code' => '07',
    ]);

    $otherUser = User::create([
        'business_id' => $otherBusiness->id,
        'name'        => 'Other User',
        'email'       => 'other@competitor.in',
        'password'    => bcrypt('password'),
        'role'        => 'sales',
    ]);

    $invoice = Invoice::create([
        'business_id'     => $setup['business']->id,
        'customer_id'     => $setup['customer']->id,
        'invoice_number'  => 'TST/24-25/00001',
        'invoice_date'    => now(),
        'due_date'        => now()->addDays(15),
        'place_of_supply' => 'Maharashtra',
        'subtotal'        => 2500.00,
        'grand_total'     => 2950.00,
    ]);

    // Competitor user tries to view this invoice -> MUST be 403 Forbidden
    $this->actingAs($otherUser)
        ->get(route('invoices.show', $invoice))
        ->assertForbidden();
});

it('correctly serves the products API endpoint', function () {
    createTestSetup();

    $this->getJson('/api/products')
        ->assertOk()
        ->assertJsonStructure([
            'data' => [
                '*' => ['id', 'name', 'selling_price', 'gst_rate', 'stock']
            ]
        ]);
});
