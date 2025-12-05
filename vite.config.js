import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import tailwindcss from "@tailwindcss/vite";
import react from "@vitejs/plugin-react-swc";

export default defineConfig({
    plugins: [
        laravel({
            input: ["resources/css/app.css", "resources/js/App.jsx"],
            refresh: true,
        }),
        react(),
        tailwindcss(),
    ],
    server: {
        host: true,
        port: 5173,
        hmr: {
            host: "localhost",
        },
    },
});
