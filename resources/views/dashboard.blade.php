<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2
                    class="font-bold text-2xl text-slate-800 dark:text-white leading-tight">
                    {{ __('Executive Dashboard') }}
                </h2>
                <p class="text-xs text-slate-500 mt-1">
                    Welcome back, {{ auth()->user()->name }}
                    ({{ $business->name ?? 'GST ERP' }})
                </p>
            </div>
            <a href="{{ route('invoices.create') }}"
                class="inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white font-bold rounded-2xl text-sm transition-all shadow-lg shadow-emerald-600/30 active:scale-95">
                <svg class="w-4 h-4" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M12 4v16m8-8H4" />
                </svg>
                Create GST Invoice
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div
            class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            <!-- Metrics Cards Grid -->
            <div
                class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <!-- Revenue Card -->
                <div
                    class="bg-white dark:bg-slate-900 p-6 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm flex flex-col justify-between">
                    <div>
                        <span
                            class="text-xs uppercase font-bold text-slate-400 tracking-wider">Total
                            Sales (Turnover)</span>
                        <div
                            class="text-2xl font-extrabold text-slate-900 dark:text-white mt-2">
                            ₹{{ number_format($stats['total_revenue'], 2) }}
                        </div>
                    </div>
                    <div
                        class="mt-4 text-xs font-semibold text-emerald-600 flex items-center gap-1">
                        <span>Cached & Live Synced</span>
                    </div>
                </div>

                <!-- GST Collected Card -->
                <div
                    class="bg-white dark:bg-slate-900 p-6 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm flex flex-col justify-between">
                    <div>
                        <span
                            class="text-xs uppercase font-bold text-slate-400 tracking-wider">Total
                            GST Collected</span>
                        <div
                            class="text-2xl font-extrabold text-emerald-600 dark:text-emerald-400 mt-2">
                            ₹{{ number_format($stats['total_gst'], 2) }}
                        </div>
                    </div>
                    <div
                        class="mt-4 text-xs text-slate-400">
                        CGST + SGST + IGST</div>
                </div>

                <!-- Invoices Count Card -->
                <div
                    class="bg-white dark:bg-slate-900 p-6 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm flex flex-col justify-between">
                    <div>
                        <span
                            class="text-xs uppercase font-bold text-slate-400 tracking-wider">Invoices
                            Issued</span>
                        <div
                            class="text-2xl font-extrabold text-slate-900 dark:text-white mt-2">
                            {{ $stats['total_invoices'] }}
                        </div>
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('invoices.index') }}"
                            class="text-xs font-semibold text-emerald-600 hover:underline">View
                            all invoices &rarr;</a>
                    </div>
                </div>

                <!-- Low Stock Alert Card -->
                <div
                    class="bg-white dark:bg-slate-900 p-6 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm flex flex-col justify-between">
                    <div>
                        <span
                            class="text-xs uppercase font-bold text-slate-400 tracking-wider">Low
                            Stock Warnings</span>
                        <div
                            class="text-2xl font-extrabold {{ $stats['low_stock_count'] > 0 ? 'text-amber-500' : 'text-slate-900 dark:text-white' }} mt-2">
                            {{ $stats['low_stock_count'] }}
                            Item(s)
                        </div>
                    </div>
                    <div
                        class="mt-4 text-xs text-slate-400">
                        Below minimum reorder level</div>
                </div>
            </div>

            <!-- Recent Invoices Table -->
            <div
                class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden p-6">
                <div
                    class="flex justify-between items-center mb-6">
                    <h3
                        class="text-base font-bold text-slate-900 dark:text-white">
                        Recent Tax Invoices</h3>
                    <a href="{{ route('invoices.index') }}"
                        class="text-xs font-semibold text-emerald-600 hover:underline">View
                        All &rarr;</a>
                </div>

                <div class="overflow-x-auto">
                    <table
                        class="min-w-full divide-y divide-slate-100 dark:divide-slate-800 text-left text-sm">
                        <thead>
                            <tr
                                class="text-xs uppercase text-slate-400">
                                <th class="pb-3">Invoice #
                                </th>
                                <th class="pb-3">Customer
                                </th>
                                <th class="pb-3">Date</th>
                                <th class="pb-3 text-right">
                                    Taxable</th>
                                <th class="pb-3 text-right">
                                    GST</th>
                                <th class="pb-3 text-right">
                                    Total Amount</th>
                                <th class="pb-3 text-right">
                                    Action</th>
                            </tr>
                        </thead>
                        <tbody
                            class="divide-y divide-slate-100 dark:divide-slate-800">
                            @forelse($recentInvoices as $inv)
                                <tr>
                                    <td
                                        class="py-3.5 font-bold text-slate-900 dark:text-white">
                                        {{ $inv->invoice_number }}
                                    </td>
                                    <td class="py-3.5">
                                        {{ $inv->customer->name }}
                                    </td>
                                    <td
                                        class="py-3.5 text-slate-500">
                                        {{ $inv->invoice_date->format('d M Y') }}
                                    </td>
                                    <td
                                        class="py-3.5 text-right font-medium">
                                        ₹{{ number_format($inv->subtotal, 2) }}
                                    </td>
                                    <td
                                        class="py-3.5 text-right text-emerald-600">
                                        ₹{{ number_format($inv->total_gst, 2) }}
                                    </td>
                                    <td
                                        class="py-3.5 text-right font-bold text-slate-900 dark:text-white">
                                        ₹{{ number_format($inv->grand_total, 2) }}
                                    </td>
                                    <td
                                        class="py-3.5 text-right">
                                        <a href="{{ route('invoices.show', $inv) }}"
                                            class="text-xs font-semibold text-emerald-600 hover:underline">View</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7"
                                        class="py-8 text-center text-slate-400">
                                        No invoices created
                                        yet. Click <a
                                            href="{{ route('invoices.create') }}"
                                            class="text-emerald-600 font-semibold underline">here</a>
                                        to create your first
                                        GST invoice.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
