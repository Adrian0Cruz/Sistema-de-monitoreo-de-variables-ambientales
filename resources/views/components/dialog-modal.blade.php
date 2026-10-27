@props(['id' => null, 'maxWidth' => null])

<x-modal :id="$id" :maxWidth="$maxWidth" {{ $attributes }}>
    <div class="px-6 py-4">
        <div class="font-headline-md text-headline-md font-semibold text-on-surface">
            {{ $title }}
        </div>

        <div class="mt-4 font-body-md text-body-md text-outline">
            {{ $content }}
        </div>
    </div>

    <div class="flex flex-row justify-end items-center gap-2 px-6 py-4 bg-surface-container-high border-t border-outline-variant/30 text-end">
        {{ $footer }}
    </div>
</x-modal>
