import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    mode: 'development',
    build: {
        minify: false,  // Tắt minify khi build cho dev
      },
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js', 'resources/css/style.css'],
            refresh: true,
        }),
    ],
});
