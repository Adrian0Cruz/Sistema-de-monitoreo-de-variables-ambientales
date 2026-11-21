@props([
    'title',
    'value',
    'unit'          => '',
    'status'        => 'normal',
    'statusLabel'   => 'Normal',
    'range'         => '',
    'rangeId'       => null,
    'icon'          => 'thermostat',
    'iconColor'     => 'text-primary',
    'valueColor'    => 'text-on-surface',
    'valueClass'    => '',
    'valueId'       => null,
    'statusBadgeId' => null,
])

@php
$statusClasses = match($status) {
    'warning'  => 'bg-secondary/10 text-secondary',
    'critical' => 'bg-error/10 text-error',
    default    => 'bg-tertiary/10 text-tertiary',  // normal / nominal / seguro
};
$dotClasses = match($status) {
    'warning'  => 'bg-secondary',
    'critical' => 'bg-error',
    default    => 'bg-tertiary',
};
@endphp

<div class="rounded-xl bg-surface-container p-4 sm:p-5 xl:p-6 2xl:p-8 flex flex-col justify-between shadow-card transition-colors hover:bg-surface-container-high border border-outline-variant/30">

    {{-- Header: Icon + Title + Status Badge --}}
    <div class="flex items-center justify-between mb-4 sm:mb-6">
        <div class="flex items-center gap-2 sm:gap-2.5 min-w-0">
            <div class="w-8 h-8 rounded-lg bg-surface-container-high flex items-center justify-center shrink-0 {{ $iconColor }}">
                <span class="material-symbols-outlined text-[18px] sm:text-[20px]" aria-hidden="true">{{ $icon }}</span>
            </div>
            <span class="font-headline-sm text-sm sm:text-headline-sm text-on-surface truncate">{{ $title }}</span>
        </div>

        <span @if($statusBadgeId) id="{{ $statusBadgeId }}" @endif class="px-2 sm:px-2.5 py-0.5 sm:py-1 rounded-md {{ $statusClasses }} font-label-sm text-xs sm:text-label-sm flex items-center gap-1.5 shrink-0">
            <span class="w-1.5 h-1.5 rounded-full {{ $dotClasses }}" aria-hidden="true"></span>
            <span class="status-text">{{ $statusLabel }}</span>
        </span>
    </div>

    {{-- Metric Value + Unit --}}
    <div>
        <div class="flex items-baseline gap-2">
            <span @if($valueId) id="{{ $valueId }}" @endif class="font-display-lg text-display-lg tracking-tight {{ $valueColor }} {{ $valueClass }} font-semibold">
                {!! $value !!}
            </span>
            @if($unit)
                <span class="font-headline-md text-headline-md text-outline">{!! $unit !!}</span>
            @endif
        </div>

        @if($range)
            <p @if($rangeId) id="{{ $rangeId }}" @endif class="font-body-md text-body-md text-outline mt-3">{!! $range !!}</p>
        @endif

        {{-- Allow extra content slot (e.g. smoke text value) --}}
        {{ $slot }}
    </div>

</div>
