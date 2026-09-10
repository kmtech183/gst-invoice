<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component {
    public LoginForm $form;

    /**
     * Auto-fill demo credentials for quick testing.
     */
    public function fillDemo(string $email, string $password = 'password'): void
    {
        $this->form->email = $email;
        $this->form->password = $password;
        $this->resetValidation();
    }

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div class="w-full">
    <!-- Header Badge & Title -->
    <div class="mb-8 text-center">
        <div
            class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-50 dark:bg-emerald-950/50 border border-emerald-200 dark:border-emerald-800/60 mb-4">
            <span
                class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
            <span
                class="text-xs font-semibold text-emerald-700 dark:text-emerald-400 uppercase tracking-wider">GST
                Portal v1.0</span>
        </div>
        <h2
            class="text-2xl font-extrabold tracking-tight text-slate-900 dark:text-white sm:text-3xl">
            Welcome Back
        </h2>
        <p
            class="mt-2 text-sm text-slate-600 dark:text-slate-400">
            Sign in to manage invoices, GST returns &
            inventory
        </p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4"
        :status="session('status')" />

    <!-- Demo Credentials Quick Selector -->
    <div class="mb-6 p-3.5 bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 rounded-2xl">
        <div class="flex items-center justify-between mb-2">
            <span class="text-[11px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
                Demo Credentials (Click to Fill)
            </span>
            <span class="text-[10px] text-slate-400">Pass: password</span>
        </div>
        <div class="grid grid-cols-3 gap-2">
            <button type="button" 
                wire:click="fillDemo('admin@apextech.in', 'password')"
                class="px-2.5 py-1.5 rounded-xl text-xs font-semibold text-center border transition-all bg-white dark:bg-slate-900 border-slate-200 dark:border-slate-800 hover:border-emerald-500 hover:text-emerald-600 dark:hover:text-emerald-400 text-slate-700 dark:text-slate-300 shadow-sm active:scale-95">
                <span class="block text-[11px] font-bold">Admin</span>
                <span class="block text-[9px] text-slate-400 truncate">admin@apex...</span>
            </button>
            <button type="button" 
                wire:click="fillDemo('sales@apextech.in', 'password')"
                class="px-2.5 py-1.5 rounded-xl text-xs font-semibold text-center border transition-all bg-white dark:bg-slate-900 border-slate-200 dark:border-slate-800 hover:border-emerald-500 hover:text-emerald-600 dark:hover:text-emerald-400 text-slate-700 dark:text-slate-300 shadow-sm active:scale-95">
                <span class="block text-[11px] font-bold">Sales</span>
                <span class="block text-[9px] text-slate-400 truncate">sales@apex...</span>
            </button>
            <button type="button" 
                wire:click="fillDemo('accountant@apextech.in', 'password')"
                class="px-2.5 py-1.5 rounded-xl text-xs font-semibold text-center border transition-all bg-white dark:bg-slate-900 border-slate-200 dark:border-slate-800 hover:border-emerald-500 hover:text-emerald-600 dark:hover:text-emerald-400 text-slate-700 dark:text-slate-300 shadow-sm active:scale-95">
                <span class="block text-[11px] font-bold">Accountant</span>
                <span class="block text-[9px] text-slate-400 truncate">accountant@...</span>
            </button>
        </div>
    </div>

    <form wire:submit="login" class="space-y-5">
        <!-- Email Address -->
        <div>
            <label for="email"
                class="block text-xs font-semibold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1">
                Business Email
            </label>
            <div class="relative">
                <div
                    class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <svg class="h-4 w-4" fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.206" />
                    </svg>
                </div>
                <input wire:model="form.email"
                    id="email" type="email"
                    name="email" required autofocus
                    autocomplete="username"
                    placeholder="you@company.in"
                    class="block w-full pl-10 pr-3.5 py-2.5 text-sm rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900/80 text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all shadow-sm" />
            </div>
            <x-input-error :messages="$errors->get('form.email')"
                class="mt-1.5" />
        </div>

        <!-- Password -->
        <div>
            <div
                class="flex items-center justify-between mb-1">
                <label for="password"
                    class="block text-xs font-semibold uppercase tracking-wider text-slate-700 dark:text-slate-300">
                    Password
                </label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}"
                        wire:navigate
                        class="text-xs font-medium text-emerald-600 dark:text-emerald-400 hover:text-emerald-500 transition-colors">
                        Forgot password?
                    </a>
                @endif
            </div>
            <div class="relative">
                <div
                    class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <svg class="h-4 w-4" fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
                <input wire:model="form.password"
                    id="password" type="password"
                    name="password" required
                    autocomplete="current-password"
                    placeholder="••••••••"
                    class="block w-full pl-10 pr-3.5 py-2.5 text-sm rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900/80 text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all shadow-sm" />
            </div>
            <x-input-error :messages="$errors->get('form.password')"
                class="mt-1.5" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between">
            <label for="remember"
                class="inline-flex items-center select-none cursor-pointer">
                <input wire:model="form.remember"
                    id="remember" type="checkbox"
                    class="rounded border-slate-300 dark:border-slate-700 text-emerald-600 shadow-sm focus:ring-emerald-500 dark:bg-slate-900">
                <span
                    class="ms-2 text-xs font-medium text-slate-600 dark:text-slate-400">Remember
                    for 30 days</span>
            </label>
        </div>

        <!-- Submit Button -->
        <div>
            <button type="submit"
                class="w-full relative flex justify-center items-center gap-2 py-2.5 px-4 border border-transparent rounded-xl text-sm font-semibold text-white bg-slate-900 dark:bg-emerald-600 hover:bg-slate-800 dark:hover:bg-emerald-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 shadow-md hover:shadow-lg transition-all active:scale-[0.99] disabled:opacity-50">
                <span wire:loading.remove
                    wire:target="login">Sign In to
                    Dashboard</span>
                <span wire:loading wire:target="login"
                    class="inline-flex items-center gap-2">
                    <svg class="animate-spin h-4 w-4 text-white"
                        fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25"
                            cx="12" cy="12"
                            r="10" stroke="currentColor"
                            stroke-width="4"></circle>
                        <path class="opacity-75"
                            fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                        </path>
                    </svg>
                    Authenticating...
                </span>
                <svg wire:loading.remove wire:target="login"
                    class="w-4 h-4 text-slate-400 dark:text-emerald-200"
                    fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M14 5l7 7m0 0l-7 7m7-7H3" />
                </svg>
            </button>
        </div>
    </form>

    <!-- Footer Switch to Register -->
    <div
        class="mt-8 pt-6 border-t border-slate-100 dark:border-slate-800/80 text-center">
        <p
            class="text-xs text-slate-500 dark:text-slate-400">
            Don't have a business account?
            <a href="{{ route('register') }}" wire:navigate
                class="font-semibold text-emerald-600 dark:text-emerald-400 hover:underline ms-1">
                Register company
            </a>
        </p>
    </div>
</div>
