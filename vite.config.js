import { defineConfig } from 'vite';
import path from 'path';
import inject from '@rollup/plugin-inject';

export default defineConfig({
    plugins: [
        inject({
            $: 'jquery',
            jQuery: 'jquery',
        }),
    ],
    root: 'public',
    build: {
        outDir: 'dist',
        emptyOutDir: true,
        cssCodeSplit: false,
        rollupOptions: {
            input: {
                main: path.resolve(__dirname, 'public/js/main.js'),
            },
            output: {
                entryFileNames: `assets/main.js`,
                assetFileNames: `assets/[name].[ext]`,
            },
        },
    },
});