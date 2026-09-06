<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    class="h-full bg-slate-50 dark:bg-slate-950">

<head>
    <meta charset="utf-8">
    <meta name="viewport"
        content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>
        {{ config('app.name', 'GST Invoice & Inventory') }}
    </title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link
        href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap"
        rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body
    class="font-sans antialiased text-slate-800 dark:text-slate-200 min-h-screen flex flex-col justify-center py-12 sm:px-6 lg:px-8 bg-[radial-gradient(ellipse_80%_80%_at_50%_-20%,rgba(16,185,129,0.15),rgba(255,255,255,0))] dark:bg-[radial-gradient(ellipse_80%_80%_at_50%_-20%,rgba(16,185,129,0.1),rgba(10,15,29,1))]">

    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <!-- Brand Logo -->
        <div class="flex justify-center mb-2">
            <a href="/" wire:navigate
                class="flex items-center gap-2 group">
                <div
                    class="w-11 h-11 rounded-2xl bg-gradient-to-tr from-emerald-600 to-teal-400 flex items-center justify-center text-white shadow-lg shadow-emerald-500/25 group-hover:scale-105 transition-transform">
                    <svg class="w-6 h-6" fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2.5"
                            d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                    </svg>
                </div>
                <div class="text-left">
                    <span
                        class="block text-lg font-bold leading-tight text-slate-900 dark:text-white tracking-tight">GST<span
                            class="text-emerald-500">Flow</span></span>
                    <span
                        class="block text-[10px] uppercase font-semibold text-slate-400 tracking-wider">Invoice
                        & Stock</span>
                </div>
            </a>
        </div>
    </div>

    <div
        class="mt-6 sm:mx-auto sm:w-full sm:max-w-md px-4 sm:px-0">
        <div
            class="bg-white/90 dark:bg-slate-900/90 backdrop-blur-xl py-8 px-6 sm:px-10 shadow-2xl shadow-slate-900/5 dark:shadow-black/40 rounded-3xl border border-slate-200/80 dark:border-slate-800/80">
            {{ $slot }}
        </div>
        <p
            class="mt-6 text-center text-xs text-slate-400 dark:text-slate-500">
            &copy; {{ date('Y') }} GSTFlow Systems. GST
            Compliant ERP Architecture.
        </p>
    </div>
</body>

</html>
