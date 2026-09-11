<?php

use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Rule;
use Livewire\Volt\Component;

new class extends Component
{
    #[Rule('required|string|current_password:customer')]
    public string $password = '';

    public function deleteCustomer(Logout $logout): void
    {
        $this->validate();

        $customer = Auth::guard('customer')->user();

        $logout();

        $customer->delete();

        $this->redirect('/', navigate: true);
    }
}; ?>

<section class="space-y-6">
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('app.delete_account_title') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('app.delete_account_desc') }}
        </p>
    </header>

    <x-danger-button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-customer-deletion')"
    >{{ __('app.delete_account_title') }}</x-danger-button>

    <x-modal name="confirm-customer-deletion" :show="$errors->isNotEmpty()" focusable>
        <form wire:submit="deleteCustomer" class="p-6">
            <h2 class="text-lg font-medium text-gray-900">
                {{ __('app.delete_account_confirm_title') }}
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                {{ __('app.delete_account_confirm_desc') }}
            </p>

            <div class="mt-6">
                <x-input-label for="password" value="{{ __('app.password') }}" class="sr-only" />

                <x-text-input
                    wire:model="password"
                    id="password"
                    name="password"
                    type="password"
                    class="mt-1 block w-3/4"
                    placeholder="{{ __('app.password') }}"
                />

                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')">
                    {{ __('app.cancel') }}
                </x-secondary-button>

                <x-danger-button class="ms-3">
                    {{ __('app.delete_account_title') }}
                </x-danger-button>
            </div>
        </form>
    </x-modal>
</section>