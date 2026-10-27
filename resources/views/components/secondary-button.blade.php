<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center justify-center px-4 py-2.5 bg-surface-container-high hover:bg-surface-container-highest border border-outline-variant/40 rounded-lg font-headline-sm text-sm font-medium text-on-surface shadow-sm focus:outline-none focus:ring-2 focus:ring-primary/40 focus:ring-offset-2 focus:ring-offset-surface disabled:opacity-30 transition duration-150']) }}>
    {{ $slot }}
</button>
