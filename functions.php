<?php

require_once get_template_directory() . '/inc/class-trendylux-nav-walker.php';

// Mise en file d'attente des assets Vite
function trendylux_vite_assets(): void {
    if (defined('IS_VITE_DEVELOPMENT') && IS_VITE_DEVELOPMENT === true) {
        $vite_dev_server_url = 'http://localhost:5173';

        // Client Vite pour le HMR
        wp_enqueue_script(
            'vite-client', // Un identifiant unique
            $vite_dev_server_url . '/@vite/client',
            [],
            null,
            true // true -> charger dans le footer
        );

        // Point d'entrée JS principal
        wp_enqueue_script(
            'trendylux-main-js', // Un identifiant unique
            $vite_dev_server_url . '/src/main.js',
            [],
            null,
            true // true -> charger dans le footer
        );
    } else {
        // --- MODE PRODUCTION / PRÉ-PRODUCTION ---
        $manifest_path = get_template_directory() . '/dist/manifest.json';
        if (!file_exists($manifest_path)) {
            return;
        }

        $manifest = json_decode(file_get_contents($manifest_path), true);
        if (!is_array($manifest)) {
            return;
        }

        // Cherche le point d'entrée 'src/main.js' dans le manifest
        if (isset($manifest['src/main.js'])) {
            $entry = $manifest['src/main.js'];

            // 1. Charge le fichier JavaScript principal
            if (isset($entry['file'])) {
                wp_enqueue_script(
                    'trendylux-main-js', // Le même identifiant qu'en dev
                    get_template_directory_uri() . '/dist/' . $entry['file'],
                    [],
                    null,
                    true
                );
            }

            // 2. Charge les fichiers CSS associés à ce point d'entrée
            if (isset($entry['css']) && is_array($entry['css'])) {
                foreach ($entry['css'] as $key => $css_file) {
                    wp_enqueue_style(
                        'trendylux-style-' . $key, // Identifiant unique pour chaque fichier CSS
                        get_template_directory_uri() . '/dist/' . $css_file
                    );
                }
            }
        }
    }
}
add_action('wp_enqueue_scripts', 'trendylux_vite_assets');


// Ajout de l'attribut type="module" aux scripts Vite
function trendylux_add_module_type_attribute($tag, $handle, $src) {
    // On ne modifie que les scripts que nous avons identifiés
    if (in_array($handle, ['vite-client', 'trendylux-main-js'])) {
        // On remplace <script src=... par <script type="module" src=...
        return '<script type="module" src="' . esc_url($src) . '" id="' . $handle . '-js"></script>';
    }
    return $tag;
}
add_filter('script_loader_tag', 'trendylux_add_module_type_attribute', 10, 3);

function trendylux_register_nav_menu(): void
{
    register_nav_menus( [
        'primary_menu' => __( 'Menu Principal', 'trendylux' ),
    ] );
}
add_action( 'after_setup_theme', 'trendylux_register_nav_menu' );
