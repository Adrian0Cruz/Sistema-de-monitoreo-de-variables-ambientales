@if ($errors->any())
    <div {{ $attributes->merge(['class' => 'p-space-md rounded-lg bg-error-container/20 border border-error/30 text-error mb-4']) }}>
        <div class="flex items-center gap-2 font-headline-sm text-sm font-medium">
            <span class="material-symbols-outlined text-base">error</span>
            <span>{{ __('Error al procesar la solicitud') }}</span>
        </div>

        <ul class="mt-2 list-disc list-inside text-body-sm text-xs space-y-1 text-on-surface-variant">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
