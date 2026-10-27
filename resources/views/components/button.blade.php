<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center px-4 py-2.5 bg-primary-container hover:bg-primary text-on-primary-container font-headline-sm text-sm font-semibold rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-primary/40 focus:ring-offset-2 focus:ring-offset-surface disabled:opacity-50 transition duration-150']) }}>
    {{ $slot }}
</button>
