import forms from '@tailwindcss/forms'

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],
    darkMode: 'class',
	theme: {
		extend: {
			opacity: {
				35: '0.35'
			},
			colors: {
				'primary': 'rgba(var(--primary-color), 1)',
				'secondary': 'rgba(var(--secondary-color), 1)',
				'accent': 'rgba(var(--accent-color), 1)',

				'error': 'rgba(var(--error-color), 1)',
				'success': 'rgba(var(--success-color), 1)',

				'base': 'rgba(var(--base-bg-color), 1)',
				'card': 'rgba(var(--card-color), 1)',
				'card-accent': 'rgba(var(--card-color-accent), 1)',
				'border': 'rgba(var(--border-color), 1)',
				'message': 'rgba(var(--message-color), 1)',
				'dropdown': 'rgba(var(--dropdown-color), 1)',
				'dropdown-select': 'rgba(var(--dropdown-select-color), 1)',
				
				'base-text': 'rgba(var(--base-text-color), 1)',
				'secondary-text': 'rgba(var(--secondary-text-color), 1)',
				'dropdown-text': 'rgba(var(--dropdown-text-color), 1)',
			}
		}
	},
	safelist: [
		'text-xs',
		'text-sm',
		'text-md',
		'text-lg',
		'text-xl',
		{
			pattern: /(bg|text|border)-(primary|secondary|accent|error|success|base|secondary-text|back|white)/,
			variants: ['hover']
		},
		{
			pattern: new RegExp('(bg|text|border)-(primary|secondary|accent|error|success|base|secondary-text|back|white)/\d\d'),
			variants: ['hover']
		},
		{
			pattern: /(bg|text)-(red|orange|yellow|green|teal|blue|indigo|purple|pink|cyan|sky|lime|amber|violet|rose|fuchsia|emerald)-500/
		}
	],
    plugins: [forms],
}
