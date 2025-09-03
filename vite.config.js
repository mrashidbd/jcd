// vite.config.js

import { defineConfig } from 'vite';
import { resolve } from 'path';

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
            output: {
                // Ensure consistent asset names for WordPress
                entryFileNames: '[name].[hash].js',
                chunkFileNames: '[name].[hash].js',
                assetFileNames: (assetInfo) => {
                    const info = assetInfo.name.split('.');
                    const extType = info[info.length - 1];
                    if (/\.(css)$/.test(assetInfo.name)) {
                        return `[name].[hash].css`;
                    }
                    if (/\.(png|jpe?g|svg|gif|tiff|bmp|ico)$/i.test(assetInfo.name)) {
                        return `images/[name].[hash].[ext]`;
                    }
                    if (/\.(woff2?|eot|ttf|otf)$/i.test(assetInfo.name)) {
                        return `fonts/[name].[hash].[ext]`;
                    }
                    return `[name].[hash].[ext]`;
                }
            }
        },

        // WordPress-specific optimizations
        target: 'es2017', // Support for older browsers
        minify: 'terser',
        terserOptions: {
            compress: {
                drop_console: true, // Remove console.log in production
                drop_debugger: true,
            },
            format: {
                comments: false, // Remove comments
            }
        },

        // CSS optimizations
        cssCodeSplit: true, // Keep CSS in one file for WordPress
    },

    server: {
        host: 'localhost',
        port: 5173,
        strictPort: true,
        cors: true,

        // WordPress integration
        proxy: {
            // Proxy requests to WordPress development server if needed
            '^(?!/(@vite|src|node_modules)).*': {
                target: 'http://localhost:8080', // Change to your WordPress dev URL
                changeOrigin: true,
                secure: false,
            }
        },

        // HMR configuration for WordPress
        hmr: {
            port: 5173,
            host: 'localhost'
        }
    },

    // CSS preprocessing
    css: {
        postcss: './postcss.config.js',
        devSourcemap: true, // Enable CSS source maps in development
    },

    // Plugin configuration
    plugins: [
        // Add any necessary plugins here
    ],

    // Resolve configuration
    resolve: {
        alias: {
            '@': resolve(__dirname, 'src'),
            '@css': resolve(__dirname, 'src/css'),
            '@js': resolve(__dirname, 'src/js'),
            '@images': resolve(__dirname, 'assets/images'),
        }
    },

    // Development optimizations
    optimizeDeps: {
        include: [
            // Include dependencies that need pre-bundling
        ]
    },

    // Build performance
    esbuild: {
        // Keep function names for WordPress compatibility
        keepNames: true,
    },

    // WordPress-specific environment variables
    define: {
        'process.env.WP_DEV': JSON.stringify(process.env.NODE_ENV === 'development'),
    }
});