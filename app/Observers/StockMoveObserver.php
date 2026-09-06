<?php

namespace App\Observers;

use App\Models\StockMove;
use Illuminate\Support\Facades\Cache;

class StockMoveObserver
{
    public function created(StockMove $stockMove): void
    {
        // Flush low-stock warning cache
        Cache::forget("business.{$stockMove->business_id}.low_stock_count");
        Cache::forget("business.{$stockMove->business_id}.dashboard_stats");
    }
}
