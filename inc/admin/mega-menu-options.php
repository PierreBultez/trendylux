<?php
/**
 * Gestion des options du Mega Menu
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Trendylux_Mega_Menu_Options {

    private $option_name = 'trendylux_mega_menu_options';

    public function __construct() {
        add_action( 'admin_menu', [ $this, 'add_plugin_page' ] );
        add_action( 'admin_init', [ $this, 'page_init' ] );
        add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_admin_assets' ] );
    }

    public function add_plugin_page(): void
    {
        add_menu_page(
            'Méga Menu',
            'Méga Menu',
            'manage_options',
            'trendylux-mega-menu-settings',
            [ $this, 'create_admin_page' ],
            'dashicons-menu',
            3
        );
    }

    public function create_admin_page() {
        ?>
        <div class="wrap trendylux-mega-menu-page">
            <h1>Configuration du Méga Menu</h1>
            <p>Configurez ici les images et les liens affichés dans le méga menu pour chaque catégorie principale.</p>
            <div class="notice notice-info inline">
                <p><strong>Recommandations générales :</strong> Privilégiez le format <strong>.webp</strong> pour la performance (sinon .jpg). Évitez le format .png qui est souvent trop lourd. Veillez à compresser vos images.</p>
            </div>
            <form method="post" action="options.php">
                <?php
                settings_fields( 'trendylux_mega_menu_group' );
                do_settings_sections( 'trendylux-mega-menu-settings' );
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }

    public function enqueue_admin_assets( $hook ) {
        if ( 'toplevel_page_trendylux-mega-menu-settings' !== $hook ) {
            return;
        }
        wp_enqueue_media();
        wp_enqueue_script( 'trendylux-admin-js', get_template_directory_uri() . '/inc/admin/js/media-uploader.js', [ 'jquery' ], '1.0.0', true );
        wp_enqueue_style( 'trendylux-mega-menu-admin-css', get_template_directory_uri() . '/inc/admin/css/mega-menu-admin.css', [], '1.0.0' );
    }

    public function page_init() {
        register_setting(
            'trendylux_mega_menu_group',
            $this->option_name,
            [ $this, 'sanitize' ]
        );

        // --- DEFINITION DES SECTIONS ---
        $sections = [
            'homme'    => 'Univers Homme',
            'femme'    => 'Univers Femme',
            'shoes_h'  => 'Chaussures Homme',
            'shoes_f'  => 'Chaussures Femme',
            'acc_h'    => 'Accessoires Homme',
            'acc_f'    => 'Accessoires Femme',
            'luxe'     => 'Luxe et Créateurs',
        ];

        // Groupe 1 : Grands formats
        $large_format_keys = ['homme', 'femme', 'luxe'];

        foreach ( $sections as $key => $label ) {
            add_settings_section(
                "section_{$key}",
                "Menu : $label",
                null,
                'trendylux-mega-menu-settings'
            );

            // Détermination des dimensions recommandées
            $is_large = in_array($key, $large_format_keys);
            
            $dim_vertical = $is_large ? '1200x900px' : '920x920px';
            $dim_horizontal = $is_large ? '440x1200px' : '440x900px';

            // Bloc 1 (Grande Image Gauche - Vertical)
            $this->add_block_fields($key, 1, 'Bloc Gauche (Vertical)', "section_{$key}", $dim_vertical);
            
            // Bloc 2 (Haut Droite - Horizontal)
            $this->add_block_fields($key, 2, 'Bloc Haut Droite (Horizontal)', "section_{$key}", $dim_horizontal);
            
            // Bloc 3 (Bas Droite - Horizontal)
            $this->add_block_fields($key, 3, 'Bloc Bas Droite (Horizontal)', "section_{$key}", $dim_horizontal);
        }
    }

    private function add_block_fields($section_key, $block_num, $block_label, $section_id, $rec_dim = '') {
        // Titre, URL, Image
        $this->add_field( "{$section_key}_block_{$block_num}_title", "$block_label - Titre", $section_id, 'text' );
        $this->add_field( "{$section_key}_block_{$block_num}_url", "$block_label - Lien (URL)", $section_id, 'text' );
        
        $desc = $rec_dim ? "Recommandé : <strong>$rec_dim</strong>. Format : .webp ou .jpg." : '';
        $this->add_field( "{$section_key}_block_{$block_num}_image", "$block_label - Image", $section_id, 'image', ['description' => $desc] );
    }

    private function add_field( $id, $title, $section, $type = 'text', $args = [] ) {
        add_settings_field(
            $id,
            $title,
            [ $this, 'render_field' ],
            'trendylux-mega-menu-settings',
            $section,
            array_merge( $args, [ 'id' => $id, 'type' => $type ] )
        );
    }

    public function sanitize( $input ) {
        $new_input = [];
        if( is_array( $input ) ) {
            foreach( $input as $key => $val ) {
                if ( strpos($key, 'url') !== false ) {
                    $new_input[$key] = esc_url_raw( $val );
                } elseif ( strpos($key, 'image') !== false ) {
                    $new_input[$key] = sanitize_text_field( $val );
                } else {
                    $new_input[$key] = sanitize_text_field( $val );
                }
            }
        }
        return $new_input;
    }

    public function render_field( $args ) {
        $options = get_option( $this->option_name );
        $id      = $args['id'];
        $val     = $options[$id] ?? '';
        $type    = $args['type'];
        $desc    = $args['description'] ?? '';

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

            case 'image':
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
                if ($desc) {
                    echo '<p class="description" style="margin-top: 5px;">' . $desc . '</p>';
                }
                break;
        }
    }
}

if ( is_admin() ) {
    new Trendylux_Mega_Menu_Options();
}