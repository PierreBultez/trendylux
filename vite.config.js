import { defineConfig } from 'vite';
import tailwindcss from '@tailwindcss/vite';

// Notre plugin custom pour le rechargement des fichiers PHP
const phpRefreshPlugin = {
    name: 'php-refresh',
    handleHotUpdate({ file, server }) {
        if (file.endsWith('.php')) {
            console.log('Fichier PHP modifié, rechargement complet...');
            server.ws.send({
                type: 'full-reload',
                path: '*',
            });
        }
    },
};

// https://vitejs.dev/config/
export default defineConfig({
    plugins: [
        tailwindcss(),
        phpRefreshPlugin,
    ],
    build: {
        // Le dossier de sortie pour les fichiers compilés
        outDir: 'dist',
        // Générer un manifest.json pour l'intégration PHP
        manifest: 'manifest.json',
        rollupOptions: {
            // Spécifier les points d'entrée
            input: {
                main: 'src/main.js',
            }
        },
    },
    server: {
        cors: true,
        // Indispensable pour que WordPress puisse charger les assets depuis Vite
        origin: 'http://localhost:5173',
        // Port du serveur de développement Vite
        port: 5173,
        // Activer le HMR
        hmr: {
            host: 'localhost',
        },
    },
});
