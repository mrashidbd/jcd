// vite.config.js

import { defineConfig } from 'vite';

export default defineConfig({
    root: '',
    base: process.env.NODE_ENV === 'production'
        ? '/wp-content/themes/jcd-ducsu/dist/'
        : '/',
    build: {
        outDir: 'dist',
        emptyOutDir: true,
        assetsDir: '',
        manifest: true,
        rollupOptions: {
            input: {
                main: 'src/js/main.js',
                css: 'src/css/input.css',
            },
        },
    },
});