/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './**/*.php',
        './src/**/*.js',
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['"Noto Sans"', 'sans-serif'],
                serif: ['"Noto Serif"', 'serif'],
            },
        },
    },
    plugins: [],
}