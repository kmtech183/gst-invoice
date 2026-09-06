<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2
                class="font-semibold text-xl text-slate-800 dark:text-slate-200 leading-tight">
                Invoice #{{ $invoice->invoice_number }}
            </h2>
            <div class="space-x-3">
                <a href="{{ route('invoices.pdf', $invoice) }}"
                    class="px-4 py-2 bg-slate-900 dark:bg-emerald-600 hover:bg-slate-800 text-white font-semibold rounded-xl text-sm transition-all shadow-md">
                    Download Official PDF
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div
                class="bg-white dark:bg-slate-900 shadow-xl rounded-3xl border border-slate-200 dark:border-slate-800 p-8">
                <!-- Invoice Header -->
                <div
                    class="flex justify-between items-start border-b border-slate-200 dark:border-slate-800 pb-8">
                    <div>
                        <h1
                            class="text-2xl font-extrabold text-slate-900 dark:text-white">
                            {{ $invoice->business->name }}
                        </h1>
                        <p
                            class="text-xs text-slate-500 mt-1">
                            {{ $invoice->business->address }},
                            {{ $invoice->business->city }} -
                            {{ $invoice->business->pincode }}
                        </p>
                        <p
                            class="text-xs font-semibold text-emerald-600 mt-1">
                            GSTIN:
                            {{ $invoice->business->gstin }}
                        </p>
                    </div>
                    <div class="text-right">
                        <span
                            class="inline-block px-3 py-1 rounded-full text-xs font-bold uppercase bg-emerald-100 text-emerald-800 mb-2">
                            TAX INVOICE
                        </span>
                        <p
                            class="text-sm font-bold text-slate-800 dark:text-slate-200">
                            #{{ $invoice->invoice_number }}
                        </p>
                        <p class="text-xs text-slate-500">
                            Date:
                            {{ $invoice->invoice_date->format('d/m/Y') }}
                        </p>
                    </div>
                </div>

                <!-- Bill To -->
                <div
                    class="py-6 border-b border-slate-200 dark:border-slate-800 grid grid-cols-2 gap-4">
                    <div>
                        <h3
                            class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                            Billed To:</h3>
                        <p
                            class="text-sm font-bold text-slate-900 dark:text-white mt-1">
                            {{ $invoice->customer->name }}
                        </p>
                        <p class="text-xs text-slate-500">
                            {{ $invoice->customer->billing_address }}
                        </p>
                        <p
                            class="text-xs font-semibold text-slate-700 dark:text-slate-300 mt-1">
                            GSTIN:
                            {{ $invoice->customer->gstin ?: 'Unregistered' }}
                        </p>
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-slate-500">
                            Place of Supply: <strong
                                class="text-slate-800 dark:text-slate-200">{{ $invoice->place_of_supply }}</strong>
                        </p>
                        <p
                            class="text-xs text-slate-500 mt-1">
                            Supply Type:
                            <strong>{{ $invoice->is_interstate ? 'Inter-State (IGST)' : 'Intra-State (CGST + SGST)' }}</strong>
                        </p>
                    </div>
                </div>

                <!-- Items Table -->
                <table
                    class="w-full my-6 text-left text-sm">
                    <thead>
                        <tr
                            class="border-b border-slate-200 dark:border-slate-800 text-xs uppercase text-slate-500">
                            <th class="py-3">Item</th>
                            <th class="py-3 text-center">HSN
                            </th>
                            <th class="py-3 text-center">Qty
                            </th>
                            <th class="py-3 text-right">Rate
                            </th>
                            <th class="py-3 text-center">GST
                            </th>
                            <th class="py-3 text-right">
                                Total</th>
                        </tr>
                    </thead>
                    <tbody
                        class="divide-y divide-slate-100 dark:divide-slate-800">
                        @foreach ($invoice->items as $item)
                            <tr>
                                <td
                                    class="py-3 font-medium text-slate-900 dark:text-white">
                                    {{ $item->product_name }}
                                </td>
                                <td
                                    class="py-3 text-center text-xs text-slate-500">
                                    {{ $item->hsn_code }}
                                </td>
                                <td
                                    class="py-3 text-center">
                                    {{ $item->quantity }}
                                    {{ $item->unit }}
                                </td>
                                <td class="py-3 text-right">
                                    ₹{{ number_format($item->unit_price, 2) }}
                                </td>
                                <td
                                    class="py-3 text-center text-xs">
                                    {{ $item->gst_rate }}%
                                </td>
                                <td
                                    class="py-3 text-right font-bold">
                                    ₹{{ number_format($item->total_amount, 2) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Grand Total & Words -->
                <div
                    class="pt-6 border-t border-slate-200 dark:border-slate-800 flex justify-between items-center">
                    <div>
                        <p
                            class="text-xs uppercase font-semibold text-slate-400">
                            Amount in Words:</p>
                        <p
                            class="text-sm font-semibold text-slate-700 dark:text-slate-300 italic">
                            {{ $amountInWords }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-slate-500">
                            Total Invoice Value</p>
                        <p
                            class="text-3xl font-extrabold text-emerald-600 dark:text-emerald-400">
                            ₹{{ number_format($invoice->grand_total, 2) }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
