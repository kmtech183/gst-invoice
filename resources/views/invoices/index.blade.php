<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2
                class="font-semibold text-xl text-slate-800 dark:text-slate-200 leading-tight">
                {{ __('GST Invoices') }}
            </h2>
            <a href="{{ route('invoices.create') }}"
                class="px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white font-semibold rounded-xl text-sm transition-all shadow-md">
                + Create Invoice
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div
                class="bg-white dark:bg-slate-900 overflow-hidden shadow-sm rounded-3xl border border-slate-200 dark:border-slate-800">
                <table
                    class="min-w-full divide-y divide-slate-200 dark:divide-slate-800 text-left text-sm">
                    <thead
                        class="bg-slate-50 dark:bg-slate-950/50 text-xs font-semibold text-slate-500 uppercase">
                        <tr>
                            <th class="px-6 py-4">Invoice #
                            </th>
                            <th class="px-6 py-4">Customer
                            </th>
                            <th class="px-6 py-4">Date</th>
                            <th class="px-6 py-4">GST Total
                            </th>
                            <th class="px-6 py-4">Grand
                                Total</th>
                            <th class="px-6 py-4">Status
                            </th>
                            <th
                                class="px-6 py-4 text-right">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody
                        class="divide-y divide-slate-100 dark:divide-slate-800">
                        @forelse($invoices as $inv)
                            <tr
                                class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                                <td
                                    class="px-6 py-4 font-bold text-slate-900 dark:text-white">
                                    {{ $inv->invoice_number }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $inv->customer->name }}
                                </td>
                                <td
                                    class="px-6 py-4 text-slate-500">
                                    {{ $inv->invoice_date->format('d M Y') }}
                                </td>
                                <td
                                    class="px-6 py-4 text-emerald-600 font-medium">
                                    ₹{{ number_format($inv->total_gst, 2) }}
                                </td>
                                <td
                                    class="px-6 py-4 font-bold text-slate-900 dark:text-white">
                                    ₹{{ number_format($inv->grand_total, 2) }}
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="px-2.5 py-1 text-xs font-semibold rounded-full bg-emerald-50 dark:bg-emerald-950 text-emerald-600 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800">
                                        {{ ucfirst($inv->status) }}
                                    </span>
                                </td>
                                <td
                                    class="px-6 py-4 text-right space-x-2">
                                    <a href="{{ route('invoices.show', $inv) }}"
                                        class="text-emerald-600 hover:underline font-medium">View</a>
                                    <a href="{{ route('invoices.pdf', $inv) }}"
                                        class="text-slate-500 hover:text-slate-800 dark:hover:text-slate-200">PDF</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7"
                                    class="px-6 py-8 text-center text-slate-400">
                                    No invoices generated
                                    yet. Click "+ Create
                                    Invoice" to start!</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $invoices->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
