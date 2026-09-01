<?php

use App\Models\Customer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;

new class extends Component
{
    public string $passport_number = '';
    public string $first_name = '';
    public string $father_name = '';
    public string $last_name = '';
    public string $email = '';
    public string $phone = '';
    public string $dob = '';

    public function mount(): void
    {
        $customer = Auth::guard('customer')->user();

        $this->passport_number = $customer->passport_number;
        $this->first_name = $customer->first_name;
        $this->father_name = $customer->father_name;
        $this->last_name = $customer->last_name;
        $this->email = $customer->email;
        $this->phone = $customer->phone ?? '';
        $this->dob = $customer->dob?->format('Y-m-d') ?? '';
    }

    public function updateProfileInformation(): void
    {
        $customer = Auth::guard('customer')->user();

        $validated = $this->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'father_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(Customer::class)->ignore($customer->id)],
            'phone' => ['nullable', 'string', 'max:15'],
            'dob' => ['nullable', 'date'],
        ]);

        $customer->fill($validated);
        $customer->save();

        $this->dispatch('profile-updated', name: $customer->first_name);
    }
}; ?>

<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form wire:submit="updateProfileInformation" class="mt-6 space-y-6">
        <div>
            <x-input-label for="passport_number" :value="__('Passport Number')" />
            <x-text-input id="passport_number" type="text" class="mt-1 block w-full bg-gray-100" value="{{ $passport_number }}" disabled readonly />
        </div>

        <div>
            <x-input-label for="first_name" :value="__('First Name')" />
            <x-text-input wire:model="first_name" id="first_name" name="first_name" type="text" class="mt-1 block w-full" required autofocus />
            <x-input-error class="mt-2" :messages="$errors->get('first_name')" />
        </div>

        <div>
            <x-input-label for="father_name" :value="__('Father Name')" />
            <x-text-input wire:model="father_name" id="father_name" name="father_name" type="text" class="mt-1 block w-full" required />
            <x-input-error class="mt-2" :messages="$errors->get('father_name')" />
        </div>

        <div>
            <x-input-label for="last_name" :value="__('Last Name')" />
            <x-text-input wire:model="last_name" id="last_name" name="last_name" type="text" class="mt-1 block w-full" required />
            <x-input-error class="mt-2" :messages="$errors->get('last_name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input wire:model="email" id="email" name="email" type="email" class="mt-1 block w-full" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />
        </div>

        <div>
            <x-input-label for="phone" :value="__('Phone')" />
            <x-text-input wire:model="phone" id="phone" name="phone" type="text" class="mt-1 block w-full" />
            <x-input-error class="mt-2" :messages="$errors->get('phone')" />
        </div>

        <div>
            <x-input-label for="dob" :value="__('Date of Birth')" />
            <x-text-input wire:model="dob" id="dob" name="dob" type="date" class="mt-1 block w-full" />
            <x-input-error class="mt-2" :messages="$errors->get('dob')" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            <x-action-message class="me-3" on="profile-updated">
                {{ __('Saved.') }}
            </x-action-message>
        </div>
    </form>
</section>