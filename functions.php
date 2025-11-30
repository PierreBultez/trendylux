<?php

require_once get_template_directory() . '/inc/class-trendylux-nav-walker.php';
require_once get_template_directory() . '/inc/class-trendylux-mobile-walker.php';

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
        'gallery_thumbnail_image_width' => 100,
        'thumbnail_image_width'         => 800,
        'woocommerce_thumbnail'         => 600,
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

        if (is_post_type_archive('product') || is_tax() || is_search()) {
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

        if ((is_post_type_archive('product') || is_tax() || is_search()) && isset($manifest['src/filters.js'])) {
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
    
    // Localize script for AJAX (applies to both dev and prod handles if they share the name or we target both)
    wp_localize_script('trendylux-main-js', 'trendylux_ajax', [
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce'    => wp_create_nonce('trendylux_newsletter_nonce')
    ]);
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
function trendylux_checkout_field_args( $args, $key, $value ): array
{
    // Classes pour le conteneur du champ. Ajout de 'mb-4' pour l'espacement vertical.
    $args['class'][] = 'form-control w-full mb-6';

    // Classes pour le label
    $args['label_class'] = array('label');

    // Classes pour l'input lui-même
    // focus:outline-none supprime la bordure noire par défaut du navigateur
    // focus:border-primary applique la couleur dorée au focus
    $args['input_class'] = array('input', 'input-bordered', 'w-full', 'focus:outline-none', 'focus:border-primary');

    // Adapter les classes pour les types de champs spécifiques
    if ( in_array( $args['type'], ['select', 'country', 'state'] ) ) {
        $args['input_class'] = array('select', 'select-bordered', 'w-full', 'focus:outline-none', 'focus:border-primary');
    }

    if ( 'textarea' === $args['type'] ) {
        $args['input_class'] = array('textarea', 'textarea-bordered', 'w-full', 'h-24', 'focus:outline-none', 'focus:border-primary');
    }

    if ( 'checkbox' === $args['type'] ) {
        $args['input_class'] = array('checkbox', 'checkbox-primary');
        $args['label_class'] = array('label', 'cursor-pointer', 'justify-start', 'gap-3');
    }

    // Enveloppe le texte du label dans un span avec la classe DaisyUI.
    // On retire la logique qui ajoutait un deuxième astérisque.
    if ( isset($args['label']) && $args['label'] ) {
        $args['label'] = '<span class="label-text font-semibold">' . $args['label'] . '</span>';
    }

    return $args;
}
add_filter( 'woocommerce_form_field_args', 'trendylux_checkout_field_args', 10, 3 );

/**
 * Déplace le formulaire de code promo de la page de paiement.
 * On le retire de sa position par défaut (en haut) pour le réinsérer
 * juste avant la section des moyens de paiement.
 */
//function trendylux_move_checkout_coupon_form(): void
//{
//    // 1. On le "décroche" de son emplacement d'origine.
//    remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10 );
//
//    // 2. On le "raccroche" juste avant le bloc de paiement.
//    add_action( 'woocommerce_after_checkout_form', 'woocommerce_checkout_coupon_form', 10 );
//}
//add_action( 'init', 'trendylux_move_checkout_coupon_form' );

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

function trendylux_show_destock_badge_single(): void
{
    global $product;
    if ( ! $product ) return;

    if ( has_term( 'destockage', 'product_tag', $product->get_id() ) ) {
    // On le place en absolute. Si un badge promo existe déjà (souvent top-0 left-0 ou similaire),
    // on essaie de le décaler un peu (top-12 ou top-14).
    // Le z-index doit être élevé.
            echo '<div class="hidden md:block badge badge-dash badge-warning mb-5 p-5 font-bold z-10">Dernière chance</div>';    }
}
    // On le hook avec une priorité qui le place probablement au début du conteneur images
add_action( 'woocommerce_before_single_product_summary', 'trendylux_show_destock_badge_single', 9 );

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
    // Support backward compatibility or specific category_id if still sent
    $category_id = $data['category_id'] ?? null;
    
    // New generic taxonomy support
    $current_term_id = $data['current_term_id'] ?? $category_id;
    $current_taxonomy = $data['current_taxonomy'] ?? ($category_id ? 'product_cat' : '');

    $search_query = $data['search'] ?? '';
    $page_url = $data['page_url'] ?? '';

    $tax_query = ['relation' => 'AND'];

    if ($current_term_id && $current_taxonomy) {
        $tax_query[] = [
            'taxonomy' => $current_taxonomy,
            'field'    => 'term_id',
            'terms'    => $current_term_id,
        ];
    }

    foreach ($filters as $key => $value) {
        if (empty($value)) {
            continue;
        }

        $clean_key = str_replace('[]', '', $key);

        if ($clean_key === 'product_cat') {
            $tax_query[] = [
                'taxonomy' => 'product_cat',
                'field'    => 'slug',
                'terms'    => $value,
                'operator' => 'IN',
            ];
        } elseif (strpos($clean_key, 'pa_') === 0) {
             $tax_query[] = [
                'taxonomy' => $clean_key,
                'field'    => 'slug',
                'terms'    => $value,
                'operator' => 'IN',
            ];
        }
    }
    
    $paged = isset($data['page']) ? intval($data['page']) : 1;
    $orderby_value = $data['orderby'] ?? 'menu_order';

    $args = [
        'post_type'      => 'product',
        'posts_per_page' => 16,
        'paged'          => $paged,
        'tax_query'      => $tax_query,
    ];

    if (!empty($search_query)) {
        $args['s'] = sanitize_text_field($search_query);
    }

    switch ($orderby_value) {
        case 'date':
            $args['orderby'] = 'date';
            $args['order']   = 'DESC';
            break;
        case 'price':
            $args['meta_key'] = '_price';
            $args['orderby']  = 'meta_value_num';
            $args['order']    = 'ASC';
            break;
        case 'price-desc':
            $args['meta_key'] = '_price';
            $args['orderby']  = 'meta_value_num';
            $args['order']    = 'DESC';
            break;
        case 'popularity':
            $args['meta_key'] = 'total_sales';
            $args['orderby']  = 'meta_value_num';
            $args['order']    = 'DESC';
            break;
        case 'rating':
            $args['meta_key'] = '_wc_average_rating';
            $args['orderby']  = 'meta_value_num';
            $args['order']    = 'DESC';
            break;
        case 'title':
            $args['orderby'] = 'title';
            $args['order']   = 'ASC';
            break;
        case 'menu_order':
        default:
            $args['orderby'] = 'menu_order title';
            $args['order']   = 'ASC';
            break;
    }

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
        echo '<ul class="products grid grid-cols-2 md:grid-cols-3 lg:grid-cols-3 gap-6">';
        while ($query->have_posts()) {
            $query->the_post();
            wc_get_template_part('content', 'product');
        }
        echo '</ul>';

        echo '<div class="mt-12 flex justify-center">';
        $base = $page_url ? $page_url . 'page/%#%/' : esc_url_raw( str_replace( 999999999, '%#%', remove_query_arg( 'add-to-cart', get_pagenum_link( 999999999, false ) ) ) );
        wc_get_template( 'loop/pagination.php', array(
             'total' => $query->max_num_pages,
             'current' => $paged,
             'base'    => $base,
        ));
        echo '</div>';
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


// =========================================================================
// == PERSONNALISATION DE LA PAGE PANIER (cart.php)
// =========================================================================

/**
 * Affiche l'adresse de livraison sur une ligne séparée dans le tableau des totaux du panier.
 * Ceci est accroché après la ligne des méthodes de livraison.
 */
function trendylux_display_shipping_destination_row() {
    if ( ! is_cart() || ! WC()->cart->needs_shipping() || ! WC()->cart->show_shipping() ) {
        return;
    }

    $packages = WC()->cart->get_shipping_packages();

    foreach ( $packages as $i => $package ) {
        if ( isset( $package['destination'] ) ) {
            $formatted_destination = WC()->countries->get_formatted_address( $package['destination'], ', ' );

            if ( $formatted_destination ) {
                ?>
                <tr class="shipping-destination">
                    <td colspan="2" class="text-sm pt-2">
                        <?php
                        echo 'Livraison à <strong>' . esc_html( $formatted_destination ) . '</strong>';
                        ?>
                    </td>
                </tr>
                <?php
            }
        }
    }
}
add_action( 'woocommerce_cart_totals_after_shipping', 'trendylux_display_shipping_destination_row' );

/**
 * Remove "Downloads" link from My Account menu.
 *
 * @param array $items
 * @return array
 */
function trendylux_remove_my_account_downloads_link( $items ) {
    unset( $items['downloads'] );
    return $items;
}
add_filter( 'woocommerce_account_menu_items', 'trendylux_remove_my_account_downloads_link' );

/**
 * Allow SVG uploads
 */
function trendylux_allow_svg_uploads($mimes) {
    $mimes['svg'] = 'image/svg+xml';
    return $mimes;
}
add_filter('upload_mimes', 'trendylux_allow_svg_uploads');

/**
 * ==============================================================================
 * GESTION DESTOCKAGE AUTOMATIQUE (STOCK = 1)
 * ==============================================================================
 */

// 1. MARQUAGE : Ajouter/Retirer le tag "Dernière Chance" sur le PARENT
// On écoute plus d'événements pour être sûr de ne rien rater
add_action('woocommerce_product_set_stock', 'trendylux_update_destock_status');
add_action('woocommerce_variation_set_stock', 'trendylux_update_destock_status');
add_action('save_post_product', 'trendylux_update_destock_status');
add_action('woocommerce_save_product_variation', 'trendylux_update_destock_status');

/**
 * Helper: Vérifie si un produit est éligible à la promo "Dernière Chance".
 * Conditions :
 * 1. Le stock est exactement de 1.
 * 2. C'est la SEULE déclinaison restante (pour les produits variables).
 */
function trendylux_is_last_chance_product($product) {
    if ( ! $product || $product->get_stock_quantity() != 1 ) {
        return false;
    }

    // Si c'est un produit simple, c'est bon (le stock est à 1)
    if ( $product->is_type('simple') ) {
        return true;
    }

    // Si c'est une variation, on doit vérifier ses "frères et sœurs"
    if ( $product->is_type('variation') ) {
        $parent_id = $product->get_parent_id();
        $parent = wc_get_product( $parent_id );
        
        if ( ! $parent ) return false;

        // On compte combien de variations ont du stock
        $children = $parent->get_children();
        $variations_in_stock = 0;
        
        foreach ( $children as $child_id ) {
            // Si on dépasse 1, on arrête tout de suite : pas de promo
            if ( $variations_in_stock > 1 ) return false; 
            
            $child = wc_get_product( $child_id );
            // On considère une variation comme "présente" si elle a du stock > 0
            if ( $child && $child->get_stock_quantity() > 0 ) {
                $variations_in_stock++;
            }
        }
        
        // La promo ne s'applique que s'il reste EXACTEMENT 1 déclinaison (celle-ci)
        return ( $variations_in_stock === 1 );
    }

    return false;
}

function trendylux_update_destock_status($product_id): void
{
    // Évite les boucles infinies ou les autosaves
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    
    // Récupération de l'objet produit
    $product = wc_get_product($product_id);
    if (!$product) return;

    // Si c'est une variation, on remonte au parent pour traiter l'ensemble
    // (C'est important pour vérifier la condition "Seule variation restante")
    $parent_id = $product->get_parent_id();
    if ($parent_id) {
        $parent_product = wc_get_product($parent_id);
    } else {
        $parent_product = $product;
        $parent_id = $product->get_id();
    }

    $has_last_item = false;

    // --- TRAITEMENT DES PRIX (Sauvegarde en BDD) ---

    if ($parent_product->is_type('variable')) {
        $children = $parent_product->get_children();
        
        // On doit d'abord déterminer si le PARENT est éligible au tag
        // Pour cela, on vérifie s'il y a une variation "gagnante"
        
        foreach ($children as $child_id) {
             $child = wc_get_product($child_id);
             if (!$child) continue;

             // Vérifie la condition stricte : Stock=1 ET seule déclinaison restante
             if (trendylux_is_last_chance_product($child)) {
                 $has_last_item = true;

                 // CALCUL ET SAUVEGARDE DU PRIX PROMO
                 $regular_price = (float) $child->get_regular_price();
                 if ($regular_price > 0) {
                     $sale_price = $regular_price * 0.85; // -15%
                     
                     // On ne sauvegarde que si ça change pour éviter de surcharger la BDD
                     if ((float)$child->get_sale_price() !== $sale_price) {
                         $child->set_sale_price((string)$sale_price);
                         $child->set_price((string)$sale_price); // Important: met à jour le prix actif
                         $child->save();
                     }
                 }

             } else {
                 // NETTOYAGE : Si ce n'est PAS un article dernière chance, on retire la promo
                 // (Seulement si une promo est définie, pour ne pas écraser d'autres promos manuelles si besoin, 
                 // mais ici on part du principe que le script gère ce type de promo).
                 // Pour être prudent : on retire le prix promo s'il correspond à notre calcul ou s'il existe tout court.
                 if ($child->get_sale_price()) {
                     $child->set_sale_price('');
                     $child->set_price($child->get_regular_price());
                     $child->save();
                 }
             }
        }

    } else {
        // Produit Simple
        if (trendylux_is_last_chance_product($parent_product)) {
            $has_last_item = true;
            
            $regular_price = (float) $parent_product->get_regular_price();
            if ($regular_price > 0) {
                $sale_price = $regular_price * 0.85;
                
                if ((float)$parent_product->get_sale_price() !== $sale_price) {
                    $parent_product->set_sale_price((string)$sale_price);
                    $parent_product->set_price((string)$sale_price);
                    $parent_product->save();
                }
            }
        } else {
            // Nettoyage produit simple
            if ($parent_product->get_sale_price()) {
                $parent_product->set_sale_price('');
                $parent_product->set_price($parent_product->get_regular_price());
                $parent_product->save();
            }
        }
    }

    // --- GESTION DU TAG SUR LE PARENT ---
    
    $term_slug = 'destockage';
    if (!term_exists($term_slug, 'product_tag')) {
        wp_insert_term('Dernière Chance', 'product_tag', array('slug' => $term_slug));
    }

    if ($has_last_item) {
        if (!has_term($term_slug, 'product_tag', $parent_id)) {
            wp_set_object_terms($parent_id, $term_slug, 'product_tag', true);
        }
    } else {
        if (has_term($term_slug, 'product_tag', $parent_id)) {
            wp_remove_object_terms($parent_id, $term_slug, 'product_tag');
        }
    }
}

// 2. PRIX : Appliquer -15% dans le panier (OBSOLÈTE : Le prix est maintenant stocké en base via update_destock_status)
// add_action('woocommerce_before_calculate_totals', 'trendylux_apply_last_item_discount', 10, 1);


// 3. DISPLAY (Panier) : Badge textuel
//add_filter('woocommerce_cart_item_name', 'trendylux_add_discount_badge_cart', 10, 3);
//
//function trendylux_add_discount_badge_cart($name, $cart_item, $cart_item_key) {
//    $product = $cart_item['data'];
//    if (trendylux_is_last_chance_product($product)) {
//        $name .= ' <span style="color:#e74c3c; font-size:0.85em; font-weight:bold;">(Dernière pièce : -15% appliqués !)</span>';
//    }
//    return $name;
//}

// 4. DISPLAY (Liste produits) : Badge visuel sur l'image
add_action('woocommerce_before_shop_loop_item_title', 'trendylux_show_last_chance_badge', 10);

function trendylux_show_last_chance_badge(): void
{
    global $product;
    if (has_term('destockage', 'product_tag', $product->get_id())) {
        echo '<div class="absolute top-2 right-2 z-10 badge badge-error font-bold shadow-md">Dernière chance</div>';
    }
}

// 5. DISPLAY (Fiche Produit) : Script JS pour gérer le prix dynamique
add_action('wp_footer', 'trendylux_product_page_last_chance_script');

function trendylux_product_page_last_chance_script(): void
{
    if (!is_product()) return;

    global $product;
    
    // On construit une liste JS des variations qui sont en stock = 1
    // NOUVELLE LOGIQUE : Seulement si c'est la SEULE variation restante
    $last_chance_variations = [];
    
    if ($product->is_type('variable')) {
        $variations = $product->get_available_variations();
        
        // 1. Compter combien de variations sont réellement en stock > 0
        $variations_in_stock = 0;
        $potential_last_id = null;
        
        foreach ($variations as $variation) {
            // get_available_variations filtre déjà beaucoup, mais on vérifie le stock réel
            $var_obj = wc_get_product($variation['variation_id']);
            if ($var_obj && $var_obj->get_stock_quantity() > 0) {
                $variations_in_stock++;
                $potential_last_id = $variation['variation_id'];
            }
        }

        // 2. Si le compteur est à 1, on vérifie si cette variation unique a un stock de 1
        if ($variations_in_stock === 1 && $potential_last_id) {
            $var_obj = wc_get_product($potential_last_id);
            if ($var_obj && $var_obj->get_stock_quantity() == 1) {
                $last_chance_variations[] = $potential_last_id;
            }
        }

    } elseif ($product->get_stock_quantity() == 1) {
        // Produit simple
        $last_chance_variations[] = $product->get_id();
    }

    // Si aucune variation concernée, on n'injecte pas de JS inutile
    if (empty($last_chance_variations)) return;
    ?>
    <script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function() {
        const lastChanceIds = <?php echo json_encode($last_chance_variations); ?>;
        const $form = jQuery('form.variations_form');

        $form.on('found_variation', function(event, variation) {
            const priceContainer = document.querySelector('.price'); // Sélecteur standard Woo
            const singleVarWrap = document.querySelector('.woocommerce-variation-price');
            
            // On cherche où afficher le message (soit prix principal, soit prix variation)
            let targetPrice = singleVarWrap && singleVarWrap.innerHTML !== "" ? singleVarWrap : priceContainer;

            // Nettoyage des anciens messages
            const existingMsg = document.querySelector('.trendylux-last-chance-msg');
            if(existingMsg) existingMsg.remove();

            if (lastChanceIds.includes(variation.variation_id)) {
                // Calcul du prix remisé (simulation visuelle)
                const originalPrice = variation.display_price;
                const discountedPrice = (originalPrice * 0.85).toFixed(2);
                
                // On injecte un message visuel fort
                const msg = document.createElement('div');
                msg.className = 'trendylux-last-chance-msg alert alert-error mt-2 py-2 text-sm w-full';
                msg.innerHTML = `
                    <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                    <span><b>Dernière pièce !</b> <br/>Une remise de 15% est appliquée.</span>
                `;
                
                // Insertion après le bouton d'ajout au panier ou le prix
                const addToCartBtn = document.querySelector('.single_add_to_cart_button');
                if(addToCartBtn) {
                    addToCartBtn.parentNode.insertBefore(msg, addToCartBtn);
                }
            }
        });

        // Réinitialisation quand on désélectionne
        $form.on('reset_data', function() {
            const existingMsg = document.querySelector('.trendylux-last-chance-msg');
            if(existingMsg) existingMsg.remove();
        });
    });
    </script>
    <?php
}

//function trendylux_show_destock_badge_after_add_to_cart_mobile(): void
//{
//    global $product;
//    if ( ! $product ) return;
//
//    if ( has_term( 'destockage', 'product_tag', $product->get_id() ) ) {
//        // Only show on mobile, hidden on desktop
//        echo '<div class="md:hidden badge badge-dash badge-warning mt-5 mb-5 p-5 font-bold z-10 text-center w-full">Dernière chance</div>';
//    }
//}
//add_action( 'woocommerce_after_add_to_cart_form', 'trendylux_show_destock_badge_after_add_to_cart_mobile', 10 );

// 5. DISPLAY (Panier/Mini-Panier) : Afficher le prix remisé visuellement dans la colonne prix
add_filter( 'woocommerce_cart_item_price', 'trendylux_display_discounted_price_in_cart', 10, 3 );

function trendylux_display_discounted_price_in_cart( $price, $cart_item, $cart_item_key ) {
    // WooCommerce gère automatiquement l'affichage du prix barré si un sale_price est défini en BDD.
    // Cette fonction n'est plus nécessaire pour le calcul, mais on peut la garder si on veut surcharger le formatage.
    // Pour l'instant, on retourne le prix standard.
    return $price;
}

/**
 * AJAX Handler for Brevo Newsletter Subscription
 */
function trendylux_subscribe_newsletter() {
    // 1. Vérification du nonce pour la sécurité
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'trendylux_newsletter_nonce')) {
        wp_send_json_error('Erreur de sécurité. Veuillez recharger la page.');
        wp_die();
    }

    // 2. Validation de l'email
    $email = isset($_POST['email']) ? sanitize_email($_POST['email']) : '';
    if (!is_email($email)) {
        wp_send_json_error('Adresse email invalide.');
        wp_die();
    }

    // 3. Récupération des clés API (définies dans wp-config.php de préférence)
    $api_key = defined('BREVO_API_KEY') ? BREVO_API_KEY : '';
    $list_id = defined('BREVO_LIST_ID') ? (int)BREVO_LIST_ID : 0;

    if (empty($api_key) || empty($list_id)) {
        wp_send_json_error('Erreur de configuration du service newsletter.');
        wp_die();
    }

    // 3b. Génération du code promo unique
    $promo_code = trendylux_create_welcome_coupon($email);

    // 4. Appel à l'API Brevo v3
    $url = 'https://api.brevo.com/v3/contacts';
    
    $payload = [
        'email' => $email,
        'listIds' => [$list_id],
        'updateEnabled' => true, // Met à jour si le contact existe déjà
    ];

    // On n'envoie l'attribut que si un code a été généré
    if ($promo_code) {
        $payload['attributes'] = [
            'CODE_PROMO_BIENVENUE' => $promo_code
        ];
    }

    $body = json_encode($payload);

    $response = wp_remote_post($url, [
        'headers' => [
            'api-key' => $api_key,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ],
        'body' => $body,
        'timeout' => 15
    ]);

    if (is_wp_error($response)) {
        wp_send_json_error('Erreur de connexion au service newsletter.');
        wp_die();
    }

    $status_code = wp_remote_retrieve_response_code($response);
    $response_body = json_decode(wp_remote_retrieve_body($response), true);

    // 201 = Créé, 204 = Mis à jour (selon endpoint, ici souvent 201 ou 204)
    // Brevo renvoie parfois 400 si l'utilisateur est déjà dans la liste mais sans updateEnabled
    if ($status_code >= 200 && $status_code < 300) {
        wp_send_json_success('Merci ! Votre inscription est validée. Vous allez recevoir votre code promo par email.');
    } else {
        // Gestion des erreurs spécifiques Brevo
        $error_msg = isset($response_body['message']) ? $response_body['message'] : 'Une erreur est survenue.';
        
        // Traduction user-friendly de certaines erreurs courantes
        if (strpos($error_msg, 'Contact already exist') !== false) {
            wp_send_json_success('Vous êtes déjà inscrit à notre newsletter !');
        } else {
            wp_send_json_error('Erreur : ' . $error_msg);
        }
    }

    wp_die();
}
add_action('wp_ajax_nopriv_subscribe_newsletter', 'trendylux_subscribe_newsletter');
add_action('wp_ajax_subscribe_newsletter', 'trendylux_subscribe_newsletter');

/**
 * Crée un code promo unique pour la livraison gratuite (limité à 1 usage par cet email)
 */
function trendylux_create_welcome_coupon(string $email): ?string
{
    // Vérifier si l'email a déjà un code attribué (optionnel, pour éviter les doublons)
    // Pour simplifier ici, on en génère un nouveau à chaque fois ou on laisse WooCommerce gérer.
    // On génère un suffixe aléatoire de 6 caractères
    $code = 'WELCOME-' . strtoupper(wp_generate_password(6, false));

    // Création du coupon WooCommerce
    $coupon = new WC_Coupon();
    $coupon->set_code($code);
    $coupon->set_description('Offre de bienvenue - Livraison offerte pour ' . $email);
    $coupon->set_free_shipping(true); // Active la livraison gratuite
    $coupon->set_usage_limit(1); // Utilisable 1 seule fois au total
    $coupon->set_usage_limit_per_user(1); // Utilisable 1 seule fois par utilisateur
    $coupon->set_email_restrictions([$email]); // Restreint à cet email uniquement
    
    // Sauvegarde
    $coupon->save();

    return $code;
}