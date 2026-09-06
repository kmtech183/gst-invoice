<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component {
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        event(new Registered(($user = User::create($validated))));

        Auth::login($user);

        $this->redirect(route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div class="w-full">
    <!-- Header Badge & Title -->
    <div class="mb-8 text-center">
        <div
            class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-50 dark:bg-emerald-950/50 border border-emerald-200 dark:border-emerald-800/60 mb-4">
            <span
                class="w-2 h-2 rounded-full bg-emerald-500"></span>
            <span
                class="text-xs font-semibold text-emerald-700 dark:text-emerald-400 uppercase tracking-wider">New
                Business Onboarding</span>
        </div>
        <h2
            class="text-2xl font-extrabold tracking-tight text-slate-900 dark:text-white sm:text-3xl">
            Create Business Account
        </h2>
        <p
            class="mt-2 text-sm text-slate-600 dark:text-slate-400">
            Start issuing GST compliant invoices & tracking
            stock in minutes
        </p>
    </div>

    <form wire:submit="register" class="space-y-4">
        <!-- Full Name -->
        <div>
            <label for="name"
                class="block text-xs font-semibold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1">
                Full Name / Owner Name
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
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <input wire:model="name" id="name"
                    type="text" name="name" required
                    autofocus autocomplete="name"
                    placeholder="Rahul Sharma"
                    class="block w-full pl-10 pr-3.5 py-2.5 text-sm rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900/80 text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all shadow-sm" />
            </div>
            <x-input-error :messages="$errors->get('name')"
                class="mt-1.5" />
        </div>

        <!-- Email Address -->
        <div>
            <label for="email"
                class="block text-xs font-semibold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1">
                Work Email Address
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
                <input wire:model="email" id="email"
                    type="email" name="email" required
                    autocomplete="username"
                    placeholder="rahul@enterprises.in"
                    class="block w-full pl-10 pr-3.5 py-2.5 text-sm rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900/80 text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all shadow-sm" />
            </div>
            <x-input-error :messages="$errors->get('email')"
                class="mt-1.5" />
        </div>

        <!-- Password -->
        <div>
            <label for="password"
                class="block text-xs font-semibold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1">
                Password
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
                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
                <input wire:model="password" id="password"
                    type="password" name="password" required
                    autocomplete="new-password"
                    placeholder="••••••••"
                    class="block w-full pl-10 pr-3.5 py-2.5 text-sm rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900/80 text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all shadow-sm" />
            </div>
            <x-input-error :messages="$errors->get('password')"
                class="mt-1.5" />
        </div>

        <!-- Confirm Password -->
        <div>
            <label for="password_confirmation"
                class="block text-xs font-semibold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1">
                Confirm Password
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
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <input wire:model="password_confirmation"
                    id="password_confirmation"
                    type="password"
                    name="password_confirmation" required
                    autocomplete="new-password"
                    placeholder="••••••••"
                    class="block w-full pl-10 pr-3.5 py-2.5 text-sm rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900/80 text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all shadow-sm" />
            </div>
            <x-input-error :messages="$errors->get(
                'password_confirmation',
            )"
                class="mt-1.5" />
        </div>

        <!-- Submit Button -->
        <div class="pt-2">
            <button type="submit"
                class="w-full relative flex justify-center items-center gap-2 py-2.5 px-4 border border-transparent rounded-xl text-sm font-semibold text-white bg-slate-900 dark:bg-emerald-600 hover:bg-slate-800 dark:hover:bg-emerald-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 shadow-md hover:shadow-lg transition-all active:scale-[0.99] disabled:opacity-50">
                <span wire:loading.remove
                    wire:target="register">Create Enterprise
                    Account</span>
                <span wire:loading wire:target="register"
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
                    Setting up workspace...
                </span>
            </button>
        </div>
    </form>

    <!-- Footer Switch to Login -->
    <div
        class="mt-6 pt-6 border-t border-slate-100 dark:border-slate-800/80 text-center">
        <p
            class="text-xs text-slate-500 dark:text-slate-400">
            Already have an account?
            <a href="{{ route('login') }}" wire:navigate
                class="font-semibold text-emerald-600 dark:text-emerald-400 hover:underline ms-1">
                Sign in here
            </a>
        </p>
    </div>
</div>
