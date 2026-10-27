@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'w-full bg-surface-container-lowest text-on-surface font-body-md text-body-md rounded-lg px-4 py-2.5 placeholder:text-outline/60 outline-none border border-outline-variant/40 focus:border-primary focus:ring-1 focus:ring-primary transition-all shadow-inner']) !!}>
