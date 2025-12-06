<?php
/**
 * Gestion des options du thème (Page d'accueil)
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Trendylux_Theme_Options {

    private $option_name = 'trendylux_home_options';

    public function __construct() {
        add_action( 'admin_menu', [ $this, 'add_plugin_page' ] );
        add_action( 'admin_init', [ $this, 'page_init' ] );
        add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_admin_assets' ] );
    }

    public function add_plugin_page() {
        add_menu_page(
            'Page d\'Accueil',
            'Page d\'Accueil',
            'manage_options',
            'trendylux-home-settings',
            [ $this, 'create_admin_page' ],
            'dashicons-store',
            2
        );
    }

    public function create_admin_page() {
        ?>
        <div class="wrap">
            <h1>Configuration de la Page d\'Accueil</h1>
            <form method="post" action="options.php">
                <?php
                settings_fields( 'trendylux_home_group' );
                do_settings_sections( 'trendylux-home-settings' );
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }

    public function enqueue_admin_assets( $hook ) {
        if ( 'toplevel_page_trendylux-home-settings' !== $hook ) {
            return;
        }
        wp_enqueue_media();
        wp_enqueue_script( 'trendylux-admin-js', get_template_directory_uri() . '/inc/admin/js/media-uploader.js', [ 'jquery' ], '1.0.0', true );
    }

    public function page_init() {
        register_setting(
            'trendylux_home_group',
            $this->option_name,
            [ $this, 'sanitize' ]
        );

        // --- SECTION 1: BANDEAU PROMO ---
        add_settings_section(
            'section_hero_promo',
            '1. Bandeau Promo (Haut)',
            null,
            'trendylux-home-settings'
        );

        $this->add_field( 'promo_text_1', 'Texte Ligne 1', 'section_hero_promo', 'text', ['default' => 'Ouverture officielle'] );
        $this->add_field( 'promo_text_2_prefix', 'Texte Ligne 2 (Avant code)', 'section_hero_promo', 'text', ['default' => '-10% sur tout le site avec le code promo'] );
        $this->add_field( 'promo_code', 'Code Promo', 'section_hero_promo', 'text', ['default' => 'VIP10'] );
        $this->add_field( 'promo_text_3', 'Texte Ligne 3', 'section_hero_promo', 'text', ['default' => 'Livraison offerte**'] );
        $this->add_field( 'promo_bg_image', 'Image de fond', 'section_hero_promo', 'image' );

        // --- SECTION 2: HERO CENTRAL ---
        add_settings_section(
            'section_hero_main',
            '2. Hero Central',
            null,
            'trendylux-home-settings'
        );

        $this->add_field( 'hero_title', 'Titre Principal', 'section_hero_main', 'text', ['default' => 'Trendy Lux'] );
        $this->add_field( 'hero_subtitle', 'Sous-titre', 'section_hero_main', 'text', ['default' => 'Élégance Intemporelle'] );
        $this->add_field( 'hero_btn_text', 'Texte Bouton', 'section_hero_main', 'text', ['default' => 'Découvrir'] );
        $this->add_field( 'hero_btn_url', 'Lien Bouton (URL)', 'section_hero_main', 'text', ['default' => '/boutique/'] );
        $this->add_field( 'hero_main_image', 'Image Centrale', 'section_hero_main', 'image' );

        // --- SECTION 3: SLIDERS ---
        add_settings_section(
            'section_hero_sliders',
            '3. Sliders Latéraux',
            null,
            'trendylux-home-settings'
        );
        $this->add_field( 'hero_slider_ids', 'Images du Slider', 'section_hero_sliders', 'gallery' );

        // --- SECTION 4: SLIDER MARQUES ---
        add_settings_section(
            'section_brand_slider',
            '4. Défilement Marques',
            null,
            'trendylux-home-settings'
        );
        $this->add_field( 'brand_slider_title', 'Titre', 'section_brand_slider', 'text', ['default' => 'Choisis parmi les plus grandes Marques...'] );
        $this->add_field( 'brand_slider_ids', 'Logos Marques', 'section_brand_slider', 'gallery' );

        // --- SECTION 5: BLOCS MARKETING ---
        add_settings_section(
            'section_marketing',
            '5. Blocs Marketing (Grands)',
            null,
            'trendylux-home-settings'
        );
        // Bloc 1
        $this->add_field( 'block_1_title', 'Bloc 1: Titre', 'section_marketing', 'text', ['default' => 'Collab\'s'] );
        $this->add_field( 'block_1_url', 'Bloc 1: Lien', 'section_marketing', 'text' );
        $this->add_field( 'block_1_image', 'Bloc 1: Image', 'section_marketing', 'image' );
        // Bloc 2
        $this->add_field( 'block_2_title', 'Bloc 2: Titre', 'section_marketing', 'text', ['default' => 'Ventes Flash'] );
        $this->add_field( 'block_2_url', 'Bloc 2: Lien', 'section_marketing', 'text' );
        $this->add_field( 'block_2_image', 'Bloc 2: Image', 'section_marketing', 'image' );

        // --- SECTION 6: CARTE CADEAU ---
        add_settings_section(
            'section_gift_card',
            '6. Carte Cadeau',
            null,
            'trendylux-home-settings'
        );
        $this->add_field( 'gift_title', 'Titre', 'section_gift_card', 'text', ['default' => 'Le Plaisir <br><span class="text-primary">D\'offrir</span>'] );
        $this->add_field( 'gift_text', 'Description', 'section_gift_card', 'textarea', ['default' => 'Faites plaisir à coup sûr avec la Carte Cadeau Trendy Lux...'] );
        $this->add_field( 'gift_btn_text', 'Texte Bouton', 'section_gift_card', 'text', ['default' => 'Acheter'] );
        $this->add_field( 'gift_btn_url', 'Lien Bouton', 'section_gift_card', 'text', ['default' => '/produit/carte-cadeau-exclusive-trendy-lux/'] );
        $this->add_field( 'gift_image', 'Image de fond', 'section_gift_card', 'image' );
    }

    /**
     * Helper pour ajouter un champ
     */
    private function add_field( $id, $title, $section, $type = 'text', $args = [] ) {
        add_settings_field(
            $id,
            $title,
            [ $this, 'render_field' ],
            'trendylux-home-settings',
            $section,
            array_merge( $args, [ 'id' => $id, 'type' => $type ] )
        );
    }

    public function sanitize( $input ) {
        // Nettoyage basique
        $new_input = [];
        if( is_array( $input ) ) {
            foreach( $input as $key => $val ) {
                if ( strpos($key, 'url') !== false ) {
                    $new_input[$key] = esc_url_raw( $val );
                } elseif ( strpos($key, 'ids') !== false || strpos($key, 'image') !== false ) {
                    $new_input[$key] = sanitize_text_field( $val );
                } else {
                    // Allow HTML for titles/texts (e.g. spans)
                    $new_input[$key] = wp_kses_post( $val );
                }
            }
        }
        return $new_input;
    }

    public function render_field( $args ) {
        $options = get_option( $this->option_name );
        $id      = $args['id'];
        $default = $args['default'] ?? '';
        $val     = $options[$id] ?? $default;
        $type    = $args['type'];

        switch ( $type ) {
            case 'text':
                printf(
                    '<input type="text" id="%s" name="%s[%s]" value="%s" class="regular-text" />',
                    $id,
                    $this->option_name,
                    $id,
                    esc_attr( $val )
                );
                break;

            case 'textarea':
                printf(
                    '<textarea id="%s" name="%s[%s]" class="large-text" rows="5">%s</textarea>',
                    $id,
                    $this->option_name,
                    $id,
                    esc_textarea( $val )
                );
                break;

            case 'image':
                // Preview
                $preview = '';
                if ( $val ) {
                    $img_url = wp_get_attachment_url( $val );
                    if ( $img_url ) {
                        $preview = '<img src="' . esc_url( $img_url ) . '" style="max-width:150px; height:auto; margin-top:10px; display:block;">';
                    }
                }
                printf(
                    '<input type="hidden" id="%s" name="%s[%s]" value="%s" />
                     <button type="button" class="button trendylux-upload-btn" data-target="%s" data-preview="preview-%s" data-multiple="false">Choisir Image</button>
                     <button type="button" class="button trendylux-clear-btn" data-target="%s" data-preview="preview-%s">Supprimer</button>
                     <div id="preview-%s">%s</div>',
                    $id, $this->option_name, $id, esc_attr( $val ),
                    $id, $id,
                    $id, $id,
                    $id, $preview
                );
                break;

            case 'gallery':
                // Gallery (Comma separated IDs)
                $preview = '';
                if ( $val ) {
                    $ids = explode( ',', $val );
                    foreach ( $ids as $att_id ) {
                        $img_src = wp_get_attachment_image_src( $att_id, 'thumbnail' );
                        if ( $img_src ) {
                            $preview .= '<div style="display:inline-block; margin:5px;"><img src="' . esc_url( $img_src[0] ) . '" style="max-width:80px; border:1px solid #ddd;"></div>';
                        }
                    }
                }
                printf(
                    '<input type="hidden" id="%s" name="%s[%s]" value="%s" />
                     <button type="button" class="button trendylux-upload-btn" data-target="%s" data-preview="preview-%s" data-multiple="true">Gérer la Galerie</button>
                     <button type="button" class="button trendylux-clear-btn" data-target="%s" data-preview="preview-%s">Vider</button>
                     <div id="preview-%s" style="margin-top:10px;">%s</div>',
                    $id, $this->option_name, $id, esc_attr( $val ),
                    $id, $id,
                    $id, $id,
                    $id, $preview
                );
                break;
        }
    }
}

if ( is_admin() ) {
    new Trendylux_Theme_Options();
}
