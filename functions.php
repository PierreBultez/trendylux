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

function trendylux_add_woocommerce_support(): void
{
    add_theme_support( 'woocommerce' );

    // Active le support pour les fonctionnalités de la galerie produit
    add_theme_support( 'wc-product-gallery-zoom' );
    add_theme_support( 'wc-product-gallery-lightbox' );
    add_theme_support( 'wc-product-gallery-slider' );
}
add_action( 'after_setup_theme', 'trendylux_add_woocommerce_support' );

// Remplace le <ul> de WooCommerce par une div avec nos classes de grille
function trendylux_woocommerce_product_loop_start(): string
{
    return '<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">';
}
add_filter('woocommerce_product_loop_start', 'trendylux_woocommerce_product_loop_start');

function trendylux_woocommerce_product_loop_end(): string
{
    return '</div>';
}
add_filter('woocommerce_product_loop_end', 'trendylux_woocommerce_product_loop_end');

// Enlève le <li> autour de chaque produit, car notre grille n'en a pas besoin
remove_action('woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10);
remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5);

// Retire les wrappers par défaut de WooCommerce pour qu'on puisse utiliser les nôtres
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );
