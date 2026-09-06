<?php

namespace Database\Seeders;

use App\Models\Business;
use App\Models\Customer;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Default Enterprise Business
        $business = Business::create([
            'name'                  => 'Apex Technologies Pvt Ltd',
            'gstin'                 => '27AABCU9603R1ZM', // Maharashtra State Code 27
            'email'                 => 'billing@apextech.in',
            'phone'                 => '+91 98765 43210',
            'address'               => 'Plot 42, Hinjewadi Phase 1, IT Park',
            'city'                  => 'Pune',
            'state'                 => 'Maharashtra',
            'state_code'            => '27',
            'pincode'               => '411057',
            'invoice_prefix'        => 'APX/24-25/',
            'next_invoice_number'   => 101,
            'terms_and_conditions'  => '1. Goods once sold will not be taken back.\n2. Interest @ 18% p.a. will be charged if payment is delayed beyond 30 days.',
        ]);

        // 2. Create Users with different Roles
        User::create([
            'business_id' => $business->id,
            'name'        => 'Admin User',
            'email'       => 'admin@apextech.in',
            'password'    => Hash::make('password'),
            'role'        => 'admin',
        ]);

        User::create([
            'business_id' => $business->id,
            'name'        => 'Rahul Sales Executive',
            'email'       => 'sales@apextech.in',
            'password'    => Hash::make('password'),
            'role'        => 'sales',
        ]);

        User::create([
            'business_id' => $business->id,
            'name'        => 'Priya Chief Accountant',
            'email'       => 'accountant@apextech.in',
            'password'    => Hash::make('password'),
            'role'        => 'accountant',
        ]);

        // 3. Create Product Categories
        $electronics = ProductCategory::create([
            'business_id' => $business->id,
            'name'        => 'Computer Hardware',
            'slug'        => 'computer-hardware',
            'description' => 'Laptops, Monitors, and Accessories',
        ]);

        $software = ProductCategory::create([
            'business_id' => $business->id,
            'name'        => 'Enterprise Software',
            'slug'        => 'enterprise-software',
            'description' => 'SaaS licenses and productivity tools',
        ]);

        // 4. Create Products with standard GST Rates (18%, 12%, 5%)
        Product::create([
            'business_id'    => $business->id,
            'category_id'    => $electronics->id,
            'name'           => 'Dell UltraSharp 27" 4K Monitor',
            'slug'           => 'dell-ultrasharp-27-4k-monitor',
            'sku'            => 'HW-MON-001',
            'hsn_code'       => '847160',
            'purchase_price' => 28000.00,
            'selling_price'  => 36500.00,
            'gst_rate'       => 18,
            'stock'          => 15,
            'reorder_level'  => 3,
            'unit'           => 'pcs',
        ]);

        Product::create([
            'business_id'    => $business->id,
            'category_id'    => $electronics->id,
            'name'           => 'Logitech MX Master 3S Wireless Mouse',
            'slug'           => 'logitech-mx-master-3s-wireless-mouse',
            'sku'            => 'HW-MOU-002',
            'hsn_code'       => '847160',
            'purchase_price' => 6200.00,
            'selling_price'  => 8499.00,
            'gst_rate'       => 18,
            'stock'          => 2, // Low stock! Below reorder level (5)
            'reorder_level'  => 5,
            'unit'           => 'pcs',
        ]);

        Product::create([
            'business_id'    => $business->id,
            'category_id'    => $software->id,
            'name'           => 'Cloud ERP 1-Year Subscription',
            'slug'           => 'cloud-erp-1-year-subscription',
            'sku'            => 'SW-ERP-001',
            'hsn_code'       => '998314', // SAC Code for IT Software
            'purchase_price' => 12000.00,
            'selling_price'  => 18000.00,
            'gst_rate'       => 18,
            'stock'          => 999,
            'reorder_level'  => 10,
            'unit'           => 'pcs',
        ]);

        // 5. Create Customers (Intra-state Maharashtra & Inter-state Gujarat)
        Customer::create([
            'business_id'     => $business->id,
            'name'            => 'Suresh Enterprises',
            'company_name'    => 'Suresh Enterprises LLP',
            'gstin'           => '27BCDEG4567M1ZR', // Maharashtra (Same State -> CGST + SGST)
            'email'           => 'contact@sureshent.com',
            'phone'           => '+91 91234 56789',
            'billing_address' => 'Shop 12, LBS Marg, Ghatkopar West',
            'city'            => 'Mumbai',
            'state'           => 'Maharashtra',
            'state_code'      => '27',
            'pincode'         => '400086',
        ]);

        Customer::create([
            'business_id'     => $business->id,
            'name'            => 'Gujarat Logistics Hub',
            'company_name'    => 'Gujarat Logistics Pvt Ltd',
            'gstin'           => '24AAACG1234F1ZQ', // Gujarat (Inter-state -> IGST)
            'email'           => 'accounts@gujaratlogistics.in',
            'phone'           => '+91 98980 11223',
            'billing_address' => 'Plot 88, GIDC Industrial Estate',
            'city'            => 'Ahmedabad',
            'state'           => 'Gujarat',
            'state_code'      => '24',
            'pincode'         => '382445',
        ]);
    }
}
