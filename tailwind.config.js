const defaultTheme = require('tailwindcss/defaultTheme');

/** @type {import('tailwindcss').Config} */
module.exports = {
    darkMode: 'class',

    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.jsx',
    ],

    theme: {
        extend: {
            colors: {
                canvas: 'rgb(var(--color-canvas) / <alpha-value>)',
                surface: 'rgb(var(--color-surface) / <alpha-value>)',
                'surface-muted': 'rgb(var(--color-surface-muted) / <alpha-value>)',
                'surface-strong': 'rgb(var(--color-surface-strong) / <alpha-value>)',
                ink: 'rgb(var(--color-ink) / <alpha-value>)',
                'ink-muted': 'rgb(var(--color-ink-muted) / <alpha-value>)',
                line: 'rgb(var(--color-line) / <alpha-value>)',
                sidebar: 'rgb(var(--color-sidebar) / <alpha-value>)',
                'sidebar-muted': 'rgb(var(--color-sidebar-muted) / <alpha-value>)',
                'sidebar-active': 'rgb(var(--color-sidebar-active) / <alpha-value>)',
                'sidebar-ink': 'rgb(var(--color-sidebar-ink) / <alpha-value>)',
                'sidebar-ink-muted': 'rgb(var(--color-sidebar-ink-muted) / <alpha-value>)',
                'sidebar-line': 'rgb(var(--color-sidebar-line) / <alpha-value>)',
                rx: {
                    blue: '#00c9ff',
                    yellow: '#f4f900',
                },
            },
            fontFamily: {
                sans: ['Poppins', ...defaultTheme.fontFamily.sans],
                heading: ['Poppins', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [require('@tailwindcss/forms')],
};
