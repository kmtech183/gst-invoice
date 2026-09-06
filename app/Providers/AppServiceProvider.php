<?php

namespace App\Providers;

use App\Contracts\InvoiceNumberGeneratorInterface;
use App\Contracts\TaxCalculatorInterface;
use App\Models\Invoice;
use App\Models\StockMove;
use App\Models\User;
use App\Observers\InvoiceObserver;
use App\Observers\StockMoveObserver;
use App\Services\GstTaxCalculator;
use App\Services\SequentialInvoiceNumberGenerator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Bind Tax Calculator Interface
        $this->app->singleton(
            TaxCalculatorInterface::class,
            GstTaxCalculator::class
        );
        // Bind Sequential Invoice Generator Interface
        $this->app->bind(
            InvoiceNumberGeneratorInterface::class,
            SequentialInvoiceNumberGenerator::class
        );
    }


    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Admin Access Gate
        Gate::define('access-admin', function (User $user) {
            return $user->isAdmin();
        });
        // Accounting Access Gate
        Gate::define('access-reports', function (User $user) {
            return $user->hasRole('admin', 'accountant');
        });

        // Register Observers for Automatic Cache Flushing
        Invoice::observe(InvoiceObserver::class);
        StockMove::observe(StockMoveObserver::class);
    }
}
