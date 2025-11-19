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

        if (is_post_type_archive('product') || is_tax('product_cat')) {
            wp_enqueue_script(
                'trendylux-filters-js', // Un identifiant unique
                $vite_dev_server_url . '/src/filters.js',
                [],
                null,
                true // true -> charger dans le footer
            );
        }
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

        if ((is_post_type_archive('product') || is_tax('product_cat')) && isset($manifest['src/filters.js'])) {
            $entry = $manifest['src/filters.js'];

            // 1. Charge le fichier JavaScript principal
            if (isset($entry['file'])) {
                wp_enqueue_script(
                    'trendylux-filters-js', // Le même identifiant qu'en dev
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
                        'trendylux-filters-style-' . $key, // Identifiant unique pour chaque fichier CSS
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

/**
 * Redirige wp-login.php vers la page Mon Compte de WooCommerce
 */
function trendylux_redirect_wp_login(): void
{
    global $pagenow;

    // Si on est sur la page de login par défaut ET que l'action n'est pas une déconnexion
    if ( 'wp-login.php' == $pagenow && ! is_user_logged_in() && ! isset( $_GET['action'] ) ) {
        wp_redirect( get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) );
        exit();
    }
}
add_action( 'init', 'trendylux_redirect_wp_login' );

/**
 * Désactiver la barre d'admin pour tout le monde sauf les administrateurs
 */
function trendylux_disable_admin_bar(): void
{
    if ( ! current_user_can( 'administrator' ) && ! is_admin() ) {
        show_admin_bar( false );
    }
}
add_action( 'after_setup_theme', 'trendylux_disable_admin_bar' );

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

// =========================================================================
// == PERSONNALISATION DE LA PAGE PRODUIT UNIQUE (single-product.php)
// =========================================================================

/**
 * 1. Supprimer la loupe (zoom) de la galerie d'images.
 */
function trendylux_remove_image_zoom_support(): void
{
    remove_theme_support( 'wc-product-gallery-zoom' );
}
add_action( 'wp', 'trendylux_remove_image_zoom_support', 100 );

/**
 * Déplace la section "Produits Similaires" pour qu'elle s'affiche en pleine largeur
 * en bas de page, au lieu d'être dans la colonne de droite.
 */
function trendylux_restructure_related_products(): void
{
    // 1. On DÉCROCHE la fonction de son emplacement d'origine (dans la colonne de droite)
    remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );

    // 2. On la RACCROCHE plus bas, en pleine largeur, juste avant la fin du contenu principal.
    add_action( 'woocommerce_after_main_content', 'woocommerce_output_related_products', 5 );
}
add_action( 'init', 'trendylux_restructure_related_products' );

/**
 * 2. Définir le nombre de produits similaires à afficher.
 *    Le style de la grille sera géré en CSS pour plus de fiabilité.
 */
function trendylux_related_products_args( $args ) {
    $args['posts_per_page'] = 4; // Nombre de produits à afficher
    $args['columns'] = 4;        // Indique à WooCommerce de s'attendre à 4 colonnes
    $args['order'] = 'rand'; // ou 'date'
    return $args;
}
add_filter( 'woocommerce_output_related_products_args', 'trendylux_related_products_args', 20 );

/**
 * 3. Styliser le badge "Promo !" (sale flash)
 *    On utilise un filtre pour envelopper le badge dans un div avec les bonnes classes.
 */
function trendylux_style_sale_flash( $html, $post, $product ): string
{
    // On garde le texte par défaut mais on l'enveloppe avec nos classes Tailwind/DaisyUI
    return '<div class="badge badge-dash badge-error mb-5 p-5 font-bold z-10">' . $html . '</div>';
}
add_filter( 'woocommerce_sale_flash', 'trendylux_style_sale_flash', 10, 3 );

/**
 * 4. Afficher les étoiles de notation avec des SVG et des classes DaisyUI.
 *    Remplace la fonction par défaut de WooCommerce pour s'affranchir de leur CSS.
 */
function trendylux_display_star_rating(): void
{
    if ( ! wc_review_ratings_enabled() ) {
        return;
    }

    global $product;
    $rating_count = $product->get_rating_count();
    $review_count = $product->get_review_count();
    $average_rating = (float) $product->get_average_rating();

    if ( $rating_count > 0 ) { ?>
        <div class="flex items-center gap-2">
            <div class="rating">
                <?php
                $rounded_rating = round( $average_rating ); // Arrondir à l'entier le plus proche
                for ( $i = 1; $i <= 5; $i++ ) {
                    // Appliquer la couleur primaire si l'étoile est dans la note, sinon gris
                    $color_class = ( $i <= $rounded_rating ) ? 'bg-primary opacity-100' : 'bg-primary';
                    // Les classes h-5 et w-5 sont nécessaires pour donner une taille au masque
                    echo '<div class="mask mask-star-2 h-5 w-5 ' . $color_class . '"></div>';
                }
                ?>
            </div>
            <a href="#reviews" class="text-xs text-gray-500 hover:underline" rel="nofollow">
                (<?php printf( _n( '%s avis', '%s avis', $review_count, 'trendylux' ), $review_count ); ?>)
            </a>
        </div>
        <?php
    }
}

// =========================================================================
// == PERSONNALISATION DU FORMULAIRE D'AVIS PRODUIT
// =========================================================================

/**
 * 1. Supprime le champ de notation par défaut de WooCommerce (qui est un <select>).
 */
function trendylux_remove_wc_rating_filter(): void
{
    remove_filter( 'comment_form_field_comment', 'woocommerce_comment_form_field_comment' );
}
add_action( 'after_setup_theme', 'trendylux_remove_wc_rating_filter' );

/**
 * 2. Ajoute un champ de notation personnalisé utilisant le composant "Rating" de DaisyUI.
 */
function trendylux_add_daisyui_review_rating_field(): void
{
    if ( ! wc_review_ratings_enabled() ) {
        return;
    }
    ?>
    <div class="comment-form-rating mb-4">
        <label class="label">
            <span class="label-text"><?php esc_html_e( 'Votre note', 'trendylux' ); ?><?php if ( wc_review_ratings_required() ) : ?>&nbsp;<span class="required text-error">*</span><?php endif; ?></span>
        </label>
        <div class="rating rating-lg">
            <!-- Le name "rating" est ce que WooCommerce attend pour traiter la note -->
            <input type="radio" name="rating" value="1" class="mask mask-star-2 bg-primary" required />
            <input type="radio" name="rating" value="2" class="mask mask-star-2 bg-primary" />
            <input type="radio" name="rating" value="3" class="mask mask-star-2 bg-primary" />
            <input type="radio" name="rating" value="4" class="mask mask-star-2 bg-primary" />
            <input type="radio" name="rating" value="5" class="mask mask-star-2 bg-primary" />
        </div>
    </div>
    <?php
}
add_action( 'comment_form_top', 'trendylux_add_daisyui_review_rating_field' );

/**
 * 3. Style les champs du formulaire de commentaire (Nom, Email) avec DaisyUI.
 */
function trendylux_style_comment_form_fields( array $fields ): array
{
    $commenter = wp_get_current_commenter();
    $req = get_option( 'require_name_email' );
    $label_class = 'label';
    $input_class = 'input input-bordered w-full';

    $fields['author'] = sprintf(
        '<div class="form-control w-full"><label for="author" class="%s"><span class="label-text">%s%s</span></label>%s</div>',
        $label_class,
        esc_html__( 'Name', 'woocommerce' ),
        ( $req ? '&nbsp;<span class="required text-error">*</span>' : '' ),
        sprintf(
            '<input id="author" name="author" type="text" value="%s" class="%s" required="required" />',
            esc_attr( $commenter['comment_author'] ),
            $input_class
        )
    );

    $fields['email'] = sprintf(
        '<div class="form-control w-full"><label for="email" class="%s"><span class="label-text">%s%s</span></label>%s</div>',
        $label_class,
        esc_html__( 'Email', 'woocommerce' ),
        ( $req ? '&nbsp;<span class="required text-error">*</span>' : '' ),
        sprintf(
            '<input id="email" name="email" type="email" value="%s" class="%s" required="required" />',
            esc_attr( $commenter['comment_author_email'] ),
            $input_class
        )
    );

    // On supprime le champ URL qui est souvent inutile
    $fields['url'] = '';

    return $fields;
}
add_filter( 'comment_form_default_fields', 'trendylux_style_comment_form_fields' );

/**
 * 4. Style le champ de texte principal (textarea) du formulaire de commentaire.
 */
function trendylux_style_comment_textarea( string $comment_field ): string
{
    return sprintf(
        '<div class="form-control w-full"><label for="comment" class="label"><span class="label-text">%s</span></label>%s</div>',
        esc_html__( 'Votre avis', 'trendylux' ),
        '<textarea id="comment" name="comment" class="textarea textarea-bordered w-full h-24" required="required"></textarea>'
    );
}
// On utilise une priorité de 20 pour s'assurer que notre filtre s'exécute APRÈS celui de WooCommerce (qui est à 10).
add_filter( 'comment_form_field_comment', 'trendylux_style_comment_textarea', 20 );

// =========================================================================
// == PERSONNALISATION DES PRODUITS VARIABLES
// =========================================================================

/**
 * Remplace le bouton "Ajouter au panier" des produits variables pour qu'il corresponde
 * au style des produits simples, en appelant un template personnalisé.
 */
function trendylux_custom_variation_add_to_cart_button(): void
{
    // Appelle notre template personnalisé pour afficher le bouton et le champ quantité.
    wc_get_template( 'single-product/add-to-cart/variation-add-to-cart-button.php' );
}

/**
 * Décroche la fonction par défaut de WooCommerce et la remplace par notre fonction personnalisée.
 */
function trendylux_replace_variation_add_to_cart_button(): void
{
    remove_action( 'woocommerce_single_variation', 'woocommerce_single_variation_add_to_cart_button', 20 );
    add_action( 'woocommerce_single_variation', 'trendylux_custom_variation_add_to_cart_button', 20 );
}
add_action( 'init', 'trendylux_replace_variation_add_to_cart_button' );

function trendylux_filter_products(): void
{
    $data = json_decode(file_get_contents('php://input'), true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        wp_send_json_error(['message' => 'Invalid JSON received.']);
        wp_die();
    }
    
    $filters = $data['filters'] ?? [];
    $tax_query = ['relation' => 'AND'];

    foreach ($filters as $key => $value) {
        if (strpos($key, 'pa_') === 0 && !empty($value)) {
             $tax_query[] = [
                'taxonomy' => str_replace('[]', '', $key),
                'field'    => 'slug',
                'terms'    => $value,
                'operator' => 'IN',
            ];
        }
    }
    
    $paged = isset($_POST['page']) ? intval($_POST['page']) : 1;

    $args = [
        'post_type'      => 'product',
        'posts_per_page' => 16,
        'paged'          => $paged,
        'tax_query'      => $tax_query,
    ];

    $query = new WP_Query($args);
    if (is_wp_error($query)) {
        wp_send_json_error(['message' => $query->get_error_message()]);
        wp_die();
    }
    
    if ( function_exists('wc_set_loop_prop') ) {
        wc_set_loop_prop( 'total', $query->found_posts );
        wc_set_loop_prop( 'per_page', $query->get('posts_per_page') );
        wc_set_loop_prop( 'current_page', $paged );
        wc_set_loop_prop( 'total_pages', $query->max_num_pages );
    }

    $GLOBALS['wp_query'] = $query;

    ob_start();

    if ($query->have_posts()) {
        echo '<ul class="products grid grid-cols-2 md:grid-cols-4 gap-x-2 gap-y-8 md:gap-x-6 md:gap-y-10">';
        while ($query->have_posts()) {
            $query->the_post();
            wc_get_template_part('content', 'product');
        }
        echo '</ul>';
    } else {
        echo '<p class="woocommerce-info">' . esc_html__( 'No products were found matching your selection.', 'woocommerce' ) . '</p>';
    }

    $products_html = ob_get_clean();

    ob_start();
    woocommerce_result_count();
    $result_count_html = ob_get_clean();

    wp_send_json_success([
        'products'     => $products_html,
        'result_count' => $result_count_html,
    ]);

    wp_die();
}
add_action('wp_ajax_filter_products', 'trendylux_filter_products');
add_action('wp_ajax_nopriv_filter_products', 'trendylux_filter_products');

function trendylux_localize_scripts(): void
{
    wp_localize_script('trendylux-filters-js', 'trendylux_ajax', [
        'ajax_url' => admin_url('admin-ajax.php'),
    ]);
}
add_action('wp_enqueue_scripts', 'trendylux_localize_scripts');

function trendylux_pre_get_posts_search_products( $query ): void {
// Check if it's the main query and a search page, and not in the admin area.
    if ( $query->is_main_query() && $query->is_search() && ! is_admin() ) {
        $query->set( 'post_type', 'product' );
        // Optionally, set posts_per_page to match your archive settings
        $query->set( 'posts_per_page', 16 );
    }
}
add_action( 'pre_get_posts', 'trendylux_pre_get_posts_search_products' );



