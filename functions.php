<?php

require_once get_template_directory() . '/inc/class-trendylux-nav-walker.php';

function trendylux_register_nav_menu(): void
{
    register_nav_menus( [
        'primary_menu' => __( 'Menu Principal', 'trendylux' ),
    ] );
}
add_action( 'after_setup_theme', 'trendylux_register_nav_menu' );

function trendylux_add_woocommerce_support(): void
{
    // Déclarer le support de WooCommerce
    add_theme_support( 'woocommerce' );

    // Active le support pour les fonctionnalités de la galerie produit
    add_theme_support( 'wc-product-gallery-zoom' );
    add_theme_support( 'wc-product-gallery-lightbox' );
    add_theme_support( 'wc-product-gallery-slider' );
}
add_action( 'after_setup_theme', 'trendylux_add_woocommerce_support' );

// --- HOOK 'INIT' POUR LES CONFIGURATIONS TARDIVES ---
function trendylux_init(): void
{
    // Définir les tailles d'images custom après l'initialisation de WordPress.
    // Cela résout la notice "traduction déclenchée trop tôt".
    add_theme_support( 'woocommerce', array(
        'gallery_thumbnail_image_width' => 64,
        'thumbnail_image_width'         => 64,
    ) );
}
add_action( 'init', 'trendylux_init' );

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

// --- PERSONNALISATIONS WOOCOMMERCE ---

// 1. Désactiver tous les styles par défaut de WooCommerce.
add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );

// 2. S'assurer que les scripts AJAX du panier sont bien chargés.
function trendylux_enqueue_wc_cart_fragments(): void
{
    wp_enqueue_script( 'wc-cart-fragments' );
}
add_action( 'wp_enqueue_scripts', 'trendylux_enqueue_wc_cart_fragments' );

// 3. Faire fonctionner les boutons + et - de l'input quantité.

if ( ! function_exists( 'woocommerce_quantity_input' ) ) {
    function woocommerce_quantity_input( $args = array(), $product = null, $echo = true ) {
        if ( is_null( $product ) ) {
            $product = $GLOBALS['product'];
        }

        $defaults = array(
            'input_id'     => uniqid( 'quantity_' ),
            'input_name'   => 'quantity',
            'input_value'  => '1',
            'max_value'    => apply_filters( 'woocommerce_quantity_input_max', -1, $product ),
            'min_value'    => apply_filters( 'woocommerce_quantity_input_min', 0, $product ),
            'step'         => apply_filters( 'woocommerce_quantity_input_step', 1, $product ),
            'pattern'      => apply_filters( 'woocommerce_quantity_input_pattern', '[0-9]*' ),
            'inputmode'    => apply_filters( 'woocommerce_quantity_input_inputmode', 'numeric' ),
            'product_name' => $product ? $product->get_name() : '',
            'classes'      => apply_filters( 'woocommerce_quantity_input_classes', array( 'input', 'input-bordered', 'join-item', 'w-16', 'text-center', 'qty' ), $product ),
            // L'argument manquant qui causait l'erreur
            'placeholder'  => apply_filters( 'woocommerce_quantity_input_placeholder', '', $product ),
        );

        $args = apply_filters( 'woocommerce_quantity_input_args', wp_parse_args( $args, $defaults ), $product );

        $min_value = max( 0, (int) $args['min_value'] );
        $input_value = max( $min_value, (int) $args['input_value'] );

        ob_start();
        ?>
        <div class="quantity join">
            <button type="button" class="btn join-item js-quantity-btn" data-action="minus" aria-label="<?php esc_attr_e('Decrease quantity', 'woocommerce'); ?>">-</button>
            <input
                type="number"
                id="<?php echo esc_attr( $args['input_id'] ); ?>"
                class="<?php echo esc_attr( implode( ' ', (array) $args['classes'] ) ); ?>"
                name="<?php echo esc_attr( $args['input_name'] ); ?>"
                value="<?php echo esc_attr( $input_value ); ?>"
                title="<?php echo esc_attr_x( 'Qty', 'Product quantity input tooltip', 'woocommerce' ); ?>"
                size="4"
                min="<?php echo esc_attr( $min_value ); ?>"
                max="<?php echo esc_attr( 0 < $args['max_value'] ? $args['max_value'] : '' ); ?>"
                step="<?php echo esc_attr( $args['step'] ); ?>"
                placeholder="<?php echo esc_attr( $args['placeholder'] ); ?>"
                inputmode="<?php echo esc_attr( $args['inputmode'] ); ?>"
                autocomplete="<?php echo esc_attr( isset( $args['autocomplete'] ) ? $args['autocomplete'] : 'on' ); ?>"
            />
            <button type="button" class="btn join-item js-quantity-btn" data-action="plus" aria-label="<?php esc_attr_e('Increase quantity', 'woocommerce'); ?>">+</button>
        </div>
        <?php
        if ( $echo ) {
            echo ob_get_clean(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        } else {
            return ob_get_clean(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        }
    }
}

/**
 * Autorise les attributs Alpine.js dans le système de sanitization de WordPress (wp_kses).
 * C'est nécessaire pour que les attributs comme x-data ne soient pas supprimés.
 */
function trendylux_add_alpine_attributes_to_kses( $allowed_tags ) {
    // Définit la liste des attributs Alpine.js à autoriser
    $alpine_attributes = [
        'x-data'                    => true,
        'x-init'                    => true,
        'x-show'                    => true,
        'x-transition:enter'        => true,
        'x-transition:enter-start'  => true,
        'x-transition:enter-end'    => true,
        'x-transition:leave'        => true,
        'x-transition:leave-start'  => true,
        'x-transition:leave-end'    => true,
    ];

    // Ajoute ces attributs à la balise 'div'
    if ( ! isset( $allowed_tags['div'] ) ) {
        $allowed_tags['div'] = [];
    }
    $allowed_tags['div'] = array_merge( $allowed_tags['div'], $alpine_attributes );

    return $allowed_tags;
}
add_filter( 'wp_kses_allowed_html', 'trendylux_add_alpine_attributes_to_kses' );

/**
 * Personnalise les arguments des champs du formulaire de paiement de WooCommerce
 * pour y ajouter les classes de DaisyUI et Tailwind. (Version corrigée)
 */
function trendylux_checkout_field_args( $args, $key, $value ) {
    // Classes pour le conteneur du champ. Ajout de 'mb-4' pour l'espacement vertical.
    $args['class'][] = 'form-control w-full mb-10';

    // Classes pour le label
    $args['label_class'] = array('label');

    // Classes pour l'input lui-même
    $args['input_class'] = array('input', 'input-bordered', 'w-full');

    // Adapter les classes pour les types de champs spécifiques
    if ( 'select' === $args['type'] ) {
        $args['input_class'] = array('select', 'select-bordered', 'w-full');
    }

    if ( 'textarea' === $args['type'] ) {
        $args['input_class'] = array('textarea', 'textarea-bordered', 'w-full', 'h-24');
    }

    // Enveloppe le texte du label dans un span avec la classe DaisyUI.
    // On retire la logique qui ajoutait un deuxième astérisque.
    if ( $args['label'] ) {
        $args['label'] = '<span class="label-text">' . $args['label'] . '</span>';
    }

    return $args;
}
add_filter( 'woocommerce_form_field_args', 'trendylux_checkout_field_args', 10, 3 );

/**
 * Déplace le formulaire de code promo de la page de paiement.
 * On le retire de sa position par défaut (en haut) pour le réinsérer
 * juste avant la section des moyens de paiement.
 */
function trendylux_move_checkout_coupon_form(): void
{
    // 1. On le "décroche" de son emplacement d'origine.
    remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10 );

    // 2. On le "raccroche" juste avant le bloc de paiement.
    add_action( 'woocommerce_review_order_before_payment', 'woocommerce_checkout_coupon_form', 10 );
}
add_action( 'init', 'trendylux_move_checkout_coupon_form' );