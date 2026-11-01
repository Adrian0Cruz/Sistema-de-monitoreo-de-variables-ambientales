<x-app-layout>
    <x-slot name="header">
        <h1 class="font-headline-lg text-headline-lg font-bold text-on-surface">
            {{ __('API Tokens') }}
        </h1>
    </x-slot>

    <div>
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            @livewire('api.api-token-manager')
        </div>
    </div>
</x-app-layout>
