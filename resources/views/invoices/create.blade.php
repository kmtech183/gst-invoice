<x-app-layout>
    <x-slot name="header">
        <h2
            class="font-semibold text-xl text-slate-800 dark:text-slate-200 leading-tight">
            {{ __('New GST Invoice') }}
        </h2>
    </x-slot>

    <livewire:invoices.invoice-create />
</x-app-layout>
