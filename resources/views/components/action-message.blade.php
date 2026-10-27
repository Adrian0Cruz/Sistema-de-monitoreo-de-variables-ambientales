@props(['on'])

<div x-data="{ shown: false, timeout: null }"
    x-init="@this.on('{{ $on }}', () => { clearTimeout(timeout); shown = true; timeout = setTimeout(() => { shown = false }, 2000); })"
    x-show.transition.out.opacity.duration.1500ms="shown"
    x-transition:leave.opacity.duration.1500ms
    style="display: none;"
    {{ $attributes->merge(['class' => 'inline-flex items-center gap-1 font-label-sm text-label-sm text-tertiary']) }}>
    <span class="material-symbols-outlined text-[14px]">check_circle</span>
    {{ $slot->isEmpty() ? 'Saved.' : $slot }}
</div>
