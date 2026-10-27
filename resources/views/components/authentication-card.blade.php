<div class="w-full flex flex-col items-center justify-center py-space-xl">
    <div class="relative w-full max-w-[460px]">
        @isset($logo)
            <div class="flex justify-center mb-6">
                {{ $logo }}
            </div>
        @endisset

        <div class="w-full bg-surface-container rounded-xl shadow-card-lg p-space-xl flex flex-col border border-outline-variant/30">
            {{ $slot }}
        </div>
    </div>
</div>
