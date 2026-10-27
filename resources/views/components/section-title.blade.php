<div class="md:col-span-1 flex justify-between">
    <div class="px-4 sm:px-0">
        <h3 class="font-headline-sm text-headline-sm font-semibold text-on-surface">{{ $title }}</h3>

        <p class="mt-1 font-body-md text-body-md text-outline">
            {{ $description }}
        </p>
    </div>

    <div class="px-4 sm:px-0">
        {{ $aside ?? '' }}
    </div>
</div>
