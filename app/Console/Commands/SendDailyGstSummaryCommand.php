<?php

namespace App\Console\Commands;

use App\Models\Business;
use App\Models\Invoice;
use App\Models\Product;
use Illuminate\Console\Command;

class SendDailyGstSummaryCommand extends Command
{
    protected $signature = 'gst:daily-summary {--business= : Specific business ID}';
    protected $description = 'Generate and output the daily GST collection summary across businesses';

    public function handle(): int
    {
        $businessId = $this->option('business');

        $businesses = $businessId
            ? Business::where('id', $businessId)->get()
            : Business::all();

        $this->info("=================================================");
        $this->info("   DAILY GST COLLECTION & INVENTORY SUMMARY      ");
        $this->info("=================================================");

        foreach ($businesses as $business) {
            $todayInvoices = Invoice::where('business_id', $business->id)
                ->whereDate('invoice_date', today())
                ->get();

            $todayRevenue = $todayInvoices->sum('grand_total');
            $todayGst = $todayInvoices->sum('total_gst');
            $lowStockCount = Product::where('business_id', $business->id)->lowStock()->count();

            $this->line("<comment>Company:</comment> {$business->name} (GSTIN: {$business->gstin})");
            $this->line("• Invoices Today: <info>{$todayInvoices->count()}</info>");
            $this->line("• Total Turnover: <info>₹" . number_format($todayRevenue, 2) . "</info>");
            $this->line("• GST Collected:  <info>₹" . number_format($todayGst, 2) . "</info>");
            $this->line("• Low Stock Items: " . ($lowStockCount > 0 ? "<error>{$lowStockCount}</error>" : "<info>0</info>"));
            $this->newLine();
        }

        return Command::SUCCESS;
    }
}
