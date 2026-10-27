<x-guest-layout>
    <div class="pt-4 bg-surface">
        <div class="min-h-screen flex flex-col items-center pt-6 sm:pt-0">
            <div>
                <x-authentication-card-logo />
            </div>

            <div class="w-full sm:max-w-2xl mt-6 p-6 bg-surface-container border border-outline-variant/30 shadow-lg overflow-hidden rounded-xl prose prose-invert">
                {!! $policy !!}
            </div>
        </div>
    </div>
</x-guest-layout>
