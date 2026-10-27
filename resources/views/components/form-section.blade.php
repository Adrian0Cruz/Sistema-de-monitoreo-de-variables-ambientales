@props(['submit'])

<div {{ $attributes->merge(['class' => 'md:grid md:grid-cols-3 md:gap-6']) }}>
    <x-section-title>
        <x-slot name="title">{{ $title }}</x-slot>
        <x-slot name="description">{{ $description }}</x-slot>
    </x-section-title>

    <div class="mt-5 md:mt-0 md:col-span-2">
        <form wire:submit="{{ $submit }}">
            <div class="px-4 py-5 bg-surface-container sm:p-6 {{ isset($actions) ? 'rounded-tl-xl rounded-tr-xl' : 'rounded-xl' }} border border-outline-variant/30 {{ isset($actions) ? 'border-b-0' : '' }}">
                <div class="grid grid-cols-6 gap-6">
                    {{ $form }}
                </div>
            </div>

            @if (isset($actions))
                <div class="flex items-center justify-end px-4 py-3 bg-surface-container-high text-end sm:px-6 rounded-bl-xl rounded-br-xl border border-outline-variant/30 gap-3">
                    {{ $actions }}
                </div>
            @endif
        </form>
    </div>
</div>
