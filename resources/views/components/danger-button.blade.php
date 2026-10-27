<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center justify-center px-4 py-2.5 bg-error/10 border border-error/30 rounded-lg font-headline-sm text-sm font-medium text-error hover:bg-error/20 focus:outline-none focus:ring-2 focus:ring-error/40 focus:ring-offset-2 focus:ring-offset-surface disabled:opacity-40 transition duration-150']) }}>
    {{ $slot }}
</button>
