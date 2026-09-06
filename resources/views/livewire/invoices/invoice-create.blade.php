<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div
        class="md:flex md:items-center md:justify-between mb-8">
        <div class="min-w-0 flex-1">
            <h2
                class="text-2xl font-bold leading-7 text-slate-900 dark:text-white sm:truncate sm:text-3xl">
                Create GST Tax Invoice
            </h2>
            <p class="mt-1 text-sm text-slate-500">
                Generate compliant tax invoice with
                real-time CGST, SGST & IGST calculation
            </p>
        </div>
        <div class="mt-4 flex md:ml-4 md:mt-0">
            <a href="{{ route('invoices.index') }}"
                class="inline-flex items-center px-4 py-2 border border-slate-300 dark:border-slate-700 rounded-xl shadow-sm text-sm font-medium text-slate-700 dark:text-slate-300 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700">
                Back to Invoices
            </a>
        </div>
    </div>

    @if ($errors->any())
        <div
            class="mb-6 p-4 rounded-2xl bg-amber-50 dark:bg-amber-950/50 border border-amber-200 dark:border-amber-800 text-amber-800 dark:text-amber-300 text-xs space-y-1">
            <strong class="block font-bold">Please check the
                following errors:</strong>
            @foreach ($errors->all() as $error)
                <p>• {{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form wire:submit="saveInvoice">
        <!-- Top Details Card -->
        <div
            class="bg-white dark:bg-slate-900 shadow-sm border border-slate-200 dark:border-slate-800 rounded-3xl p-6 mb-8">
            <div
                class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Customer Selection -->
                <div>
                    <label
                        class="block text-xs font-semibold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-2">
                        Customer / Client <span
                            class="text-rose-500">*</span>
                    </label>
                    <select wire:model.live="customer_id"
                        class="w-full rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-sm py-2.5 px-3 focus:ring-2 focus:ring-emerald-500">
                        <option value="">-- Select
                            Customer --</option>
                        @foreach ($customers as $c)
                            <option
                                value="{{ $c->id }}">
                                {{ $c->name }}
                                ({{ $c->state }} -
                                GSTIN:
                                {{ $c->gstin ?: 'Unregistered' }})
                            </option>
                        @endforeach
                    </select>
                    @error('customer_id')
                        <span
                            class="text-xs text-rose-500 mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Invoice Date -->
                <div>
                    <label
                        class="block text-xs font-semibold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-2">
                        Invoice Date
                    </label>
                    <input type="date"
                        wire:model.live="invoice_date"
                        class="w-full rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-sm py-2.5 px-3 focus:ring-2 focus:ring-emerald-500">
                </div>

                <!-- Due Date -->
                <div>
                    <label
                        class="block text-xs font-semibold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-2">
                        Payment Due Date
                    </label>
                    <input type="date"
                        wire:model.live="due_date"
                        class="w-full rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-sm py-2.5 px-3 focus:ring-2 focus:ring-emerald-500">
                </div>

                <!-- Place of Supply -->
                <div>
                    <label
                        class="block text-xs font-semibold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-2">
                        Place of Supply (State)
                    </label>
                    <input type="text"
                        wire:model.live="place_of_supply"
                        class="w-full rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-sm py-2.5 px-3 focus:ring-2 focus:ring-emerald-500">
                </div>

                <!-- Payment Mode -->
                <div>
                    <label
                        class="block text-xs font-semibold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-2">
                        Payment Mode
                    </label>
                    <select wire:model.live="payment_mode"
                        class="w-full rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-sm py-2.5 px-3 focus:ring-2 focus:ring-emerald-500">
                        <option value="upi">UPI / QR Code
                        </option>
                        <option value="bank_transfer">NEFT /
                            RTGS / IMPS</option>
                        <option value="cash">Cash</option>
                        <option value="cheque">Cheque
                        </option>
                    </select>
                </div>

                <!-- Tax Supply Type Badge -->
                <div class="flex items-end pb-1">
                    <div
                        class="w-full p-2.5 rounded-xl border {{ $is_interstate ? 'bg-indigo-50 dark:bg-indigo-950/40 border-indigo-200 dark:border-indigo-800' : 'bg-emerald-50 dark:bg-emerald-950/40 border-emerald-200 dark:border-emerald-800' }}">
                        <span
                            class="text-xs font-bold uppercase tracking-wider {{ $is_interstate ? 'text-indigo-700 dark:text-indigo-400' : 'text-emerald-700 dark:text-emerald-400' }}">
                            {{ $is_interstate ? 'Inter-State Supply (IGST Applicable)' : 'Intra-State Supply (CGST + SGST Applicable)' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Line Items Card -->
        <div
            class="bg-white dark:bg-slate-900 shadow-sm border border-slate-200 dark:border-slate-800 rounded-3xl p-6 mb-8 overflow-x-auto">
            <div
                class="flex items-center justify-between mb-4">
                <h3
                    class="text-base font-bold text-slate-900 dark:text-white">
                    Items & Services</h3>
                <button type="button" wire:click="addItem"
                    class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-semibold bg-emerald-50 dark:bg-emerald-950/50 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800 hover:bg-emerald-100 transition-colors">
                    <svg class="w-4 h-4" fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M12 4v16m8-8H4" />
                    </svg>
                    Add Line Item
                </button>
            </div>

            <table
                class="w-full text-left border-collapse min-w-[700px]">
                <thead>
                    <tr
                        class="border-b border-slate-200 dark:border-slate-800 text-xs uppercase font-semibold text-slate-500">
                        <th class="py-3 px-2 w-1/3">Product
                            / Service</th>
                        <th class="py-3 px-2 w-24">HSN</th>
                        <th class="py-3 px-2 w-24">Qty</th>
                        <th class="py-3 px-2 w-28">Rate (₹)
                        </th>
                        <th class="py-3 px-2 w-20">GST</th>
                        <th
                            class="py-3 px-2 w-32 text-right">
                            Taxable</th>
                        <th
                            class="py-3 px-2 w-32 text-right">
                            Total (₹)</th>
                        <th
                            class="py-3 px-2 w-12 text-center">
                        </th>
                    </tr>
                </thead>
                <tbody
                    class="divide-y divide-slate-100 dark:divide-slate-800 text-sm">
                    @foreach ($items as $index => $item)
                        <tr
                            wire:key="item-row-{{ $index }}">
                            <td class="py-3 px-2">
                                <select
                                    wire:model.live="items.{{ $index }}.product_id"
                                    class="w-full rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-sm py-2 px-2.5">
                                    <option value="">
                                        -- Choose Product --
                                    </option>
                                    @foreach ($products as $p)
                                        <option
                                            value="{{ $p->id }}">
                                            {{ $p->name }}
                                            (Stock:
                                            {{ $p->stock }})
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="py-3 px-2">
                                <input type="text"
                                    wire:model="items.{{ $index }}.hsn_code"
                                    placeholder="HSN"
                                    class="w-full rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-xs py-2 px-2 text-center">
                            </td>
                            <td class="py-3 px-2">
                                <input type="number"
                                    min="1"
                                    wire:model.live.debounce.300ms="items.{{ $index }}.quantity"
                                    class="w-full rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-sm py-2 px-2 text-center">
                            </td>
                            <td class="py-3 px-2">
                                <input type="number"
                                    step="0.01"
                                    wire:model.live.debounce.300ms="items.{{ $index }}.unit_price"
                                    class="w-full rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-sm py-2 px-2 text-right">
                            </td>
                            <td class="py-3 px-2">
                                <select
                                    wire:model.live="items.{{ $index }}.gst_rate"
                                    class="w-full rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-xs py-2 px-1 text-center">
                                    <option value="0">
                                        0%</option>
                                    <option value="5">
                                        5%</option>
                                    <option value="12">
                                        12%</option>
                                    <option value="18">
                                        18%</option>
                                    <option value="28">
                                        28%</option>
                                </select>
                            </td>
                            <td
                                class="py-3 px-2 text-right font-medium text-slate-700 dark:text-slate-300">
                                ₹{{ number_format($item['taxable_amount'], 2) }}
                            </td>
                            <td
                                class="py-3 px-2 text-right font-bold text-slate-900 dark:text-white">
                                ₹{{ number_format($item['total_amount'], 2) }}
                            </td>
                            <td
                                class="py-3 px-2 text-center">
                                @if (count($items) > 1)
                                    <button type="button"
                                        wire:click="removeItem({{ $index }})"
                                        class="text-slate-400 hover:text-rose-500 transition-colors p-1">
                                        <svg class="w-5 h-5"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Summary & Actions Card -->
        <div
            class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
            <!-- Notes -->
            <div
                class="bg-white dark:bg-slate-900 shadow-sm border border-slate-200 dark:border-slate-800 rounded-3xl p-6">
                <label
                    class="block text-xs font-semibold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-2">
                    Customer Notes / Terms
                </label>
                <textarea wire:model="notes" rows="4"
                    placeholder="Thank you for your business. Please remit payment within due date."
                    class="w-full rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-sm p-3 focus:ring-2 focus:ring-emerald-500"></textarea>
            </div>

            <!-- Tax Summary Table -->
            <div
                class="bg-white dark:bg-slate-900 shadow-sm border border-slate-200 dark:border-slate-800 rounded-3xl p-6 space-y-3">
                <div
                    class="flex justify-between text-sm text-slate-600 dark:text-slate-400">
                    <span>Taxable Subtotal</span>
                    <span
                        class="font-semibold text-slate-900 dark:text-white">₹{{ number_format($subtotal, 2) }}</span>
                </div>

                @if ($is_interstate)
                    <div
                        class="flex justify-between text-sm text-indigo-600 dark:text-indigo-400">
                        <span>Integrated Tax (IGST)</span>
                        <span
                            class="font-semibold">₹{{ number_format($igst_total, 2) }}</span>
                    </div>
                @else
                    <div
                        class="flex justify-between text-sm text-emerald-600 dark:text-emerald-400">
                        <span>Central Tax (CGST)</span>
                        <span
                            class="font-semibold">₹{{ number_format($cgst_total, 2) }}</span>
                    </div>
                    <div
                        class="flex justify-between text-sm text-emerald-600 dark:text-emerald-400">
                        <span>State Tax (SGST)</span>
                        <span
                            class="font-semibold">₹{{ number_format($sgst_total, 2) }}</span>
                    </div>
                @endif

                <div
                    class="flex justify-between text-sm text-slate-600 dark:text-slate-400 items-center">
                    <span>Shipping Charges (₹)</span>
                    <input type="number" step="0.01"
                        wire:model.live.debounce.300ms="shipping_charges"
                        class="w-28 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-xs py-1.5 px-2 text-right">
                </div>

                <div
                    class="flex justify-between text-sm text-slate-600 dark:text-slate-400 items-center">
                    <span>Discount (₹)</span>
                    <input type="number" step="0.01"
                        wire:model.live.debounce.300ms="discount_amount"
                        class="w-28 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-xs py-1.5 px-2 text-right">
                </div>

                <div
                    class="pt-4 border-t border-slate-200 dark:border-slate-800 flex justify-between items-baseline">
                    <span
                        class="text-base font-bold text-slate-900 dark:text-white">Grand
                        Total</span>
                    <span
                        class="text-2xl font-extrabold text-emerald-600 dark:text-emerald-400">₹{{ number_format($grand_total, 2) }}</span>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="flex justify-end gap-4">
            <button type="submit"
                class="px-8 py-3.5 rounded-2xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold shadow-lg shadow-emerald-600/30 transition-all active:scale-[0.98]">
                Generate & Finalize GST Invoice
            </button>
        </div>
    </form>
</div>
