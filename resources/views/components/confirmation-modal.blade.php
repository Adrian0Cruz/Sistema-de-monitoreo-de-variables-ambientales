@props(['id' => null, 'maxWidth' => null])

<x-modal :id="$id" :maxWidth="$maxWidth" {{ $attributes }}>
    <div class="px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
        <div class="sm:flex sm:items-start">
            <div class="mx-auto shrink-0 flex items-center justify-center size-10 rounded-full bg-error/15 border border-error/30 sm:mx-0">
                <span class="material-symbols-outlined text-error text-[20px]">warning</span>
            </div>

            <div class="mt-3 text-center sm:mt-0 sm:ms-4 sm:text-start">
                <h3 class="font-headline-md text-headline-md font-semibold text-on-surface">
                    {{ $title }}
                </h3>

                <div class="mt-4 font-body-md text-body-md text-outline">
                    {{ $content }}
                </div>
            </div>
        </div>
    </div>

    <div class="flex flex-row justify-end items-center gap-2 px-6 py-4 bg-surface-container-high border-t border-outline-variant/30 text-end">
        {{ $footer }}
    </div>
</x-modal>
