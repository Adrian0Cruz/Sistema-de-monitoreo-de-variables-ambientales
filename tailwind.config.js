import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            colors: {
                // AetherSens Enterprise Telemetry – DESIGN.md palette
                'surface':                  '#0f131c',
                'surface-dim':              '#0f131c',
                'surface-bright':           '#353943',
                'surface-container-lowest': '#0a0e17',
                'surface-container-low':    '#181c24',
                'surface-container':        '#1c2029',
                'surface-container-high':   '#262a33',
                'surface-container-highest':'#31353e',
                'surface-variant':          '#31353e',
                'surface-tint':             '#a4c9ff',
                'on-surface':               '#dfe2ef',
                'on-surface-variant':       '#c1c7d3',
                'inverse-surface':          '#dfe2ef',
                'inverse-on-surface':       '#2c303a',
                'background':               '#0f131c',
                'on-background':            '#dfe2ef',
                'outline':                  '#8b919d',
                'outline-variant':          '#414751',

                // Primary – Steel Blue
                'primary':                  '#a4c9ff',
                'on-primary':               '#00315d',
                'primary-container':        '#4d93e5',
                'on-primary-container':     '#002a51',
                'inverse-primary':          '#0060ac',
                'primary-fixed':            '#d4e3ff',
                'primary-fixed-dim':        '#a4c9ff',
                'on-primary-fixed':         '#001c39',
                'on-primary-fixed-variant': '#004883',

                // Secondary – Teal
                'secondary':                '#77d6d3',
                'on-secondary':             '#003736',
                'secondary-container':      '#007d7b',
                'on-secondary-container':   '#c6fffd',
                'secondary-fixed':          '#94f2f0',
                'secondary-fixed-dim':      '#77d6d3',
                'on-secondary-fixed':       '#00201f',
                'on-secondary-fixed-variant':'#00504e',

                // Tertiary – Emerald (Normal/Nominal status)
                'tertiary':                 '#74db9d',
                'on-tertiary':              '#00391f',
                'tertiary-container':       '#3aa36b',
                'on-tertiary-container':    '#00311a',
                'tertiary-fixed':           '#91f8b8',
                'tertiary-fixed-dim':       '#74db9d',
                'on-tertiary-fixed':        '#002110',
                'on-tertiary-fixed-variant':'#00522f',

                // Error – Muted Coral
                'error':                    '#ffb4ab',
                'on-error':                 '#690005',
                'error-container':          '#93000a',
                'on-error-container':       '#ffdad6',
            },

            borderRadius: {
                'sm':      '0.25rem',
                'DEFAULT': '0.5rem',
                'md':      '0.75rem',
                'lg':      '1rem',
                'xl':      '1.5rem',
                'full':    '9999px',
            },

            spacing: {
                'space-xs':   '0.25rem',
                'space-sm':   '0.5rem',
                'space-md':   '1rem',
                'space-lg':   '1.5rem',
                'space-xl':   '2rem',
                'gutter':     '1rem',
                'gutter-lg':  '1.5rem',
                'margin':     '1rem',
                'margin-md':  '1.5rem',
                'margin-lg':  '2rem',
            },

            fontFamily: {
                sans:               ["'Geist'", ...defaultTheme.fontFamily.sans],
                mono:               ["'JetBrains Mono'", ...defaultTheme.fontFamily.mono],
                // Semantic font families matching DESIGN.md
                'display-lg':       ["'Geist'", 'sans-serif'],
                'headline-lg':      ["'Geist'", 'sans-serif'],
                'headline-md':      ["'Geist'", 'sans-serif'],
                'headline-sm':      ["'Geist'", 'sans-serif'],
                'telemetry-metric': ["'Geist'", 'sans-serif'],
                'body-lg':          ["'Geist'", 'sans-serif'],
                'body-md':          ["'Geist'", 'sans-serif'],
                'body-sm':          ["'Geist'", 'sans-serif'],
                'label-md':         ["'Geist'", 'sans-serif'],
                'label-sm':         ["'Geist'", 'sans-serif'],
                'code-sm':          ["'JetBrains Mono'", 'monospace'],
            },

            fontSize: {
                'display-lg':       ['32px', { lineHeight: '40px', letterSpacing: '-0.02em',  fontWeight: '600' }],
                'headline-lg':      ['24px', { lineHeight: '32px', letterSpacing: '-0.015em', fontWeight: '600' }],
                'headline-md':      ['20px', { lineHeight: '28px', letterSpacing: '-0.01em',  fontWeight: '600' }],
                'headline-sm':      ['16px', { lineHeight: '24px', letterSpacing: '-0.005em', fontWeight: '600' }],
                'telemetry-metric': ['28px', { lineHeight: '32px', letterSpacing: '-0.02em',  fontWeight: '500' }],
                'body-lg':          ['16px', { lineHeight: '24px',                            fontWeight: '400' }],
                'body-md':          ['14px', { lineHeight: '20px',                            fontWeight: '400' }],
                'body-sm':          ['12px', { lineHeight: '16px',                            fontWeight: '400' }],
                'label-md':         ['13px', { lineHeight: '18px',                            fontWeight: '500' }],
                'label-sm':         ['11px', { lineHeight: '14px', letterSpacing: '0.02em',   fontWeight: '500' }],
                'code-sm':          ['12px', { lineHeight: '16px',                            fontWeight: '400' }],
            },

            boxShadow: {
                'card':    '0 1px 3px 0 rgba(0,0,0,0.25), 0 1px 2px -1px rgba(0,0,0,0.2)',
                'card-lg': '0 4px 6px -1px rgba(0,0,0,0.35), 0 2px 4px -2px rgba(0,0,0,0.3)',
            },
        },
    },

    plugins: [forms, typography],
};
