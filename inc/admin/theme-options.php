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

        // --- SECTION 5b: TOP CATÉGORIES (Bento Grid) ---
        add_settings_section(
            'section_bento',
            '5b. Top Catégories (Bento Grid - 6 emplacements)',
            function() { echo '<p>Configurez les 6 blocs de la grille. L\'ordre détermine la taille (1=Haut Large, 2=Milieu Gauche, 3=Milieu Centre, 4=Milieu Droite Vertical, 5=Bas Gauche, 6=Bas Centre Large).</p>'; },
            'trendylux-home-settings'
        );

        for ( $i = 1; $i <= 6; $i++ ) {
            $label_suffix = '';
            switch ($i) {
                case 1: $label_suffix = ' (Haut Large)'; break;
                case 2: $label_suffix = ' (Milieu Gauche)'; break;
                case 3: $label_suffix = ' (Milieu Centre)'; break;
                case 4: $label_suffix = ' (Droite Vertical)'; break;
                case 5: $label_suffix = ' (Bas Gauche)'; break;
                case 6: $label_suffix = ' (Bas Centre Large)'; break;
            }
            
            $this->add_field( "bento_{$i}_slug",  "Bloc #{$i}{$label_suffix} - Slug Catégorie", 'section_bento', 'text' );
            $this->add_field( "bento_{$i}_title", "Bloc #{$i} - Titre Personnalisé", 'section_bento', 'text' );
            $this->add_field( "bento_{$i}_link",  "Bloc #{$i} - Lien Personnalisé (Optionnel)", 'section_bento', 'text' );
            $this->add_field( "bento_{$i}_image", "Bloc #{$i} - Image Personnalisée", 'section_bento', 'image' );
            // Espace visuel entre les blocs dans l'admin
             add_settings_field(
                "bento_sep_$i",
                '<hr>',
                function(){},
                'trendylux-home-settings',
                'section_bento'
            );
        }

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

        // --- SECTION 6b: TOP MARQUES (Grille) ---
        add_settings_section(
            'section_top_brands',
            '6b. Top Marques (Grille)',
            function() { echo '<p>Configurez jusqu\'à 12 marques à afficher dans la grille.</p>'; },
            'trendylux-home-settings'
        );

        for ( $i = 1; $i <= 12; $i++ ) {
            $this->add_field( "top_brand_{$i}_image", "Marque #{$i} - Image", 'section_top_brands', 'image' );
            $this->add_field( "top_brand_{$i}_name", "Marque #{$i} - Nom", 'section_top_brands', 'text' );
            $this->add_field( "top_brand_{$i}_link", "Marque #{$i} - Lien", 'section_top_brands', 'text' );
            // Petit séparateur visuel (astuce via un champ dummy ou juste l'espacement naturel)
        }

        // --- SECTION 6c: PUBLICITÉ SACS ---
        add_settings_section(
            'section_ads_bags',
            '6c. Encart Publicitaire (Sacs)',
            null,
            'trendylux-home-settings'
        );
        $this->add_field( 'ads_bags_image', 'Image', 'section_ads_bags', 'image' );
        $this->add_field( 'ads_bags_title', 'Titre', 'section_ads_bags', 'text', ['default' => 'Découvrir les Sacs'] );
        $this->add_field( 'ads_bags_link', 'Lien', 'section_ads_bags', 'text', ['default' => '/categorie-produit/femme/accessoires-femme/femme-sacs/'] );


        // --- SECTION 7: PIED DE PAGE (FOOTER) ---
        add_settings_section(
            'section_footer',
            '7. Pied de Page (Footer)',
            null,
            'trendylux-home-settings'
        );

        // Réassurance 1
        $this->add_field( 'reassurance_1_image', 'Réassurance 1: Icône', 'section_footer', 'image' );
        $this->add_field( 'reassurance_1_title', 'Réassurance 1: Titre', 'section_footer', 'text', ['default' => 'Paiement Protégé'] );
        $this->add_field( 'reassurance_1_subtitle', 'Réassurance 1: Sous-titre', 'section_footer', 'text', ['default' => 'Transactions 100% sécurisées'] );

        // Réassurance 2
        $this->add_field( 'reassurance_2_image', 'Réassurance 2: Icône', 'section_footer', 'image' );
        $this->add_field( 'reassurance_2_title', 'Réassurance 2: Titre', 'section_footer', 'text', ['default' => 'Livraison Gratuite'] );
        $this->add_field( 'reassurance_2_subtitle', 'Réassurance 2: Sous-titre', 'section_footer', 'text', ['default' => 'Dès 90€ d\'achat'] );

        // Réassurance 3
        $this->add_field( 'reassurance_3_image', 'Réassurance 3: Icône', 'section_footer', 'image' );
        $this->add_field( 'reassurance_3_title', 'Réassurance 3: Titre', 'section_footer', 'text', ['default' => 'Livraison Standard'] );
        $this->add_field( 'reassurance_3_subtitle', 'Réassurance 3: Sous-titre', 'section_footer', 'text', ['default' => '3 à 5 jours ouvrés'] );

        // Réassurance 4
        $this->add_field( 'reassurance_4_image', 'Réassurance 4: Icône', 'section_footer', 'image' );
        $this->add_field( 'reassurance_4_title', 'Réassurance 4: Titre', 'section_footer', 'text', ['default' => 'Satisfait ou Remboursé'] );
        $this->add_field( 'reassurance_4_subtitle', 'Réassurance 4: Sous-titre', 'section_footer', 'text', ['default' => 'Retours sous 14 jours'] );

        // Newsletter
        $this->add_field( 'footer_newsletter_text', 'Texte Newsletter', 'section_footer', 'textarea', ['default' => 'Inscrivez-vous à notre newsletter pour profiter de la livraison offerte sur votre première commande'] );

        // Mentions (* et **)
        $this->add_field( 'footer_disclaimer_1', 'Mention * (Texte)', 'section_footer', 'text', ['default' => '*: du 1er au 15 décembre hors promotions'] );
        $this->add_field( 'footer_disclaimer_2', 'Mention ** (Texte)', 'section_footer', 'text', ['default' => '**: livraison offerte sur votre 1ère commande pour l’inscription à la newsletter'] );

        // Crédits
        $this->add_field( 'footer_credits_1', 'Crédits Ligne 1', 'section_footer', 'text', ['default' => 'SAS Trendy Lux n\'est pas un distributeur officiel des marques vendues sur le site web trendylux.fr'] );
        $this->add_field( 'footer_credits_2', 'Crédits Ligne 2', 'section_footer', 'text', ['default' => 'Copyright © TRENDY LUX. Tous droits réservés. Tous les logos et marques déposées présents sur ce site appartiennent à leurs propriétaires respectifs.'] );
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
