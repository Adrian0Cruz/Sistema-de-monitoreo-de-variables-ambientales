@props([
    'status' => 'normal',
    'label'  => 'Normal',
    'pill'   => false,   // true = rounded-full (table), false = rounded-md (card header)
])

@php
$shape = $pill ? 'rounded-full' : 'rounded-md';

$classes = match($status) {
    'warning',  'alerta'  => "bg-secondary-container/20 text-secondary {$shape}",
    'critical', 'critico' => "bg-error/10 text-error {$shape}",
    default                => "bg-tertiary/10 text-tertiary {$shape}",  // normal / seguro
};
$dot = match($status) {
    'warning',  'alerta'  => 'bg-secondary',
    'critical', 'critico' => 'bg-error',
    default                => 'bg-tertiary',
};
@endphp

<span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 {{ $classes }} font-label-sm text-label-sm">
    <span class="w-1.5 h-1.5 rounded-full {{ $dot }}"></span>
    {{ $label }}
</span>
