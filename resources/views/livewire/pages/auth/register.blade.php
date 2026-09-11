<?php

use App\Models\Customer;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $passport_number = '';
    public string $first_name = '';
    public string $father_name = '';
    public string $last_name = '';
    public string $email = '';
    public string $phone = '';
    public string $dob = '';
    public string $password = '';
    public string $password_confirmation = '';

    public function register(): void
    {
        $validated = $this->validate([
            'passport_number' => ['required', 'string', 'max:9', 'unique:customers,passport_number'],
            'first_name' => ['required', 'string', 'max:255'],
            'father_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:customers,email'],
            'phone' => ['nullable', 'string', 'max:15'],
            'dob' => ['nullable', 'date'],
            'password' => ['required', 'string', 'confirmed', \Illuminate\Validation\Rules\Password::defaults()],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        $customer = Customer::create($validated);

        event(new Registered($customer));

        Auth::guard('customer')->login($customer);

        $this->redirect(route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div>
    <h2 class="text-xl font-bold text-gray-900 mb-1">{{ __('app.create_account') }}</h2>
    <p class="text-sm text-gray-500 mb-6">{{ __('app.register_subtitle') }}</p>

    <form wire:submit="register" class="space-y-5">
        <div>
            <x-input-label for="passport_number" :value="__('app.passport_number')" />
            <x-text-input wire:model="passport_number" id="passport_number" class="block mt-1 w-full" type="text" name="passport_number" required />
            <x-input-error :messages="$errors->get('passport_number')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="first_name" :value="__('app.first_name')" />
            <x-text-input wire:model="first_name" id="first_name" class="block mt-1 w-full" type="text" name="first_name" required autofocus />
            <x-input-error :messages="$errors->get('first_name')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="father_name" :value="__('app.father_name')" />
            <x-text-input wire:model="father_name" id="father_name" class="block mt-1 w-full" type="text" name="father_name" required />
            <x-input-error :messages="$errors->get('father_name')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="last_name" :value="__('app.last_name')" />
            <x-text-input wire:model="last_name" id="last_name" class="block mt-1 w-full" type="text" name="last_name" required />
            <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="email" :value="__('app.email')" />
            <x-text-input wire:model="email" id="email" class="block mt-1 w-full" type="email" name="email" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="phone" :value="__('app.phone')" />
            <x-text-input wire:model="phone" id="phone" class="block mt-1 w-full" type="text" name="phone" />
            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="dob" :value="__('app.date_of_birth')" />
            <x-text-input wire:model="dob" id="dob" class="block mt-1 w-full" type="date" name="dob" />
            <x-input-error :messages="$errors->get('dob')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password" :value="__('app.password')" />
            <x-text-input wire:model="password" id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password_confirmation" :value="__('app.confirm_password')" />
            <x-text-input wire:model="password_confirmation" id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between pt-2">
            <a class="text-sm text-gray-600 hover:text-emerald-600 underline" href="{{ route('login') }}" wire:navigate>
                {{ __('app.already_registered') }}
            </a>

            <x-primary-button>
                {{ __('app.register') }}
            </x-primary-button>
        </div>
    </form>
</div>