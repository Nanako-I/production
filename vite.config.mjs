import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ["resources/css/app.css", "resources/js/app.js"],
            refresh: true,
        }),
    ],
    optimizeDeps: {
        include: ['@fullcalendar/core', '@fullcalendar/daygrid', '@fullcalendar/interaction']
    },
    resolve: {
        alias: {
            '@': '/resources/js',
        },
    },
    server: {
        hmr: {
            host: "localhost",
        },
    },
});
