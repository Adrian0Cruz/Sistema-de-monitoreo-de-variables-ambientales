@props([
    'type'        => 'text',
    'label'       => '',
    'id',
    'name'        => null,
    'placeholder' => '',
    'value'       => '',
    'autofocus'   => false,
    'autocomplete'=> '',
    'labelClass'  => '',
])

<div class="flex flex-col">
    @if($label)
        <label
            class="font-label-sm text-label-sm uppercase tracking-wider text-outline mb-1.5 font-medium {{ $labelClass }}"
            for="{{ $id }}"
        >
            {{ $label }}
        </label>
    @endif

    <div class="relative flex items-center">
        {{ $attributes->merge([
            'class' => 'w-full bg-surface-container-lowest text-on-surface font-body-md text-body-md
                        rounded-lg px-4 py-3
                        placeholder:text-outline/60
                        outline-none transition-all shadow-inner
                        focus:bg-surface-container-low
                        border border-transparent focus:border-primary/40',
            'type'         => $type,
            'id'           => $id,
            'name'         => $name ?? $id,
            'value'        => $value,
            'placeholder'  => $placeholder,
            'autocomplete' => $autocomplete,
        ])->except(['label', 'labelClass']) }}

        @if($autofocus)
            autofocus
        @endif

        {{-- Optional trailing slot (e.g. password toggle button) --}}
        {{ $slot }}
    </div>
</div>
