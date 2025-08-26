/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        "./**/*.{php,html,js}",
    ],
    theme: {
        extend: {
            colors: {
                'primary-green': '#0B8645',
                'primary-red': '#E8252C',
                'primary-blue': '#2B1473',
            },
            fontFamily: {
                'bangla': ['Hind Siliguri', 'Noto Sans Bengali', 'Arial', 'sans-serif'],
            },
        },
    },
    plugins: [],
}