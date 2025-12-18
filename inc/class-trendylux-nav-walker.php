<?php
class TRENDYLUX_Nav_Walker extends Walker_Nav_Menu {

    private bool $is_mega_menu = false;
    private int $current_item_id = 0;
    private string $current_parent_title = '';

    /**
     * Helper pour récupérer les données du mega menu (avec fallback sur les valeurs par défaut hardcodées)
     */
    private function get_mega_menu_data( string $section_key, array $defaults ): array {
        $options = get_option( 'trendylux_mega_menu_options' );
        $data = [];

        for ($i = 1; $i <= 3; $i++) {
            // Image
            $img_id = $options["{$section_key}_block_{$i}_image"] ?? '';
            $img_url = $img_id ? wp_get_attachment_url($img_id) : ($defaults[$i]['img'] ?? '');
            
            // Title
            $title = $options["{$section_key}_block_{$i}_title"] ?? '';
            $title = $title ?: ($defaults[$i]['title'] ?? '');

            // URL
            $url = $options["{$section_key}_block_{$i}_url"] ?? '';
            $url = $url ?: ($defaults[$i]['url'] ?? '#');

            $data[$i] = [
                'img'   => $img_url,
                'title' => $title,
                'url'   => $url
            ];
        }
        return $data;
    }

    private function render_brands_mega_menu( int $item_id ): string
    {
        $output = '<div x-cloak x-show="openMenu === \'menu-item-' . $item_id . '\'" 
            @mouseenter="openMenu = \'menu-item-' . $item_id . '\'" 
            @mouseleave="openMenu = null" 
            x-transition
            class="auto-cols-auto z-50 absolute top-full left-1/2 -translate-x-1/2 z-10 w-screen max-w-7xl overflow-hidden rounded-box bg-neutral text-neutral-content shadow-lg ring-1 ring-black/5"
            style="display: none;">';
        
        $output .= '<div class="p-6 flex gap-8 min-h-[500px]">';
        $output .= '<ul class="w-full">'; // Full width container

        $brands = get_terms([
            'taxonomy'   => 'product_brand',
            'hide_empty' => false,
            'orderby'    => 'name',
            'order'      => 'ASC'
        ]);

        if ( ! empty( $brands ) && ! is_wp_error( $brands ) ) {
            // Grouper par première lettre
            $grouped = [];
            foreach ( $brands as $brand ) {
                $first_letter = strtoupper( mb_substr( $brand->name, 0, 1 ) );
                $grouped[$first_letter][] = $brand;
            }

            $output .= '<div class="flex-grow px-8 pb-8 overflow-y-auto max-h-[600px]">';
            $output .= '<div class="columns-2 md:columns-4 lg:columns-5 gap-8 space-y-8">';
            
            foreach ( $grouped as $letter => $terms ) {
                $output .= '<div class="break-inside-avoid">';
                $output .= '<h3 class="font-serif font-bold text-2xl text-primary border-b border-primary/30 mb-4 pb-2">' . $letter . '</h3>';
                $output .= '<ul class="space-y-2">';
                foreach ( $terms as $term ) {
                    $link = get_term_link( $term );
                    if ( ! is_wp_error( $link ) ) {
                        $output .= '<li><a href="' . esc_url( $link ) . '" class="block text-neutral-content/80 hover:text-white hover:translate-x-1 transition-all duration-200 text-sm">' . esc_html( $term->name ) . '</a></li>';
                    }
                }
                $output .= '</ul>';
                $output .= '</div>';
            }
            
            $output .= '</div>'; // Fin columns
            $output .= '</div>'; // Fin scroll container
        } else {
            $output .= '<div class="p-8 w-full text-center text-lg">Aucune marque disponible pour le moment.</div>';
        }

        $output .= '</ul>';
        $output .= '</div>'; // Fin flex container
        $output .= '</div>'; // Fin mega menu wrapper

        return $output;
    }

    public function start_lvl( &$output, $depth = 0, $args = null ): void
    {
        if ( $depth === 0 && $this->is_mega_menu ) {
            $output .= '<div x-cloak x-show="openMenu === \'menu-item-' . $this->current_item_id . '\'" 
                @mouseenter="openMenu = \'menu-item-' . $this->current_item_id . '\'" 
                @mouseleave="openMenu = null" 
                x-transition
                class="auto-cols-auto z-50 absolute top-full left-1/2 -translate-x-1/2 z-10 w-screen max-w-7xl overflow-hidden rounded-box bg-neutral text-neutral-content shadow-lg ring-1 ring-black/5"
                style="display: none;">';
            
            $output .= '<div class="p-6 flex gap-8 min-h-[500px]">';

            // Ajustement : Pour Univers Homme ou Univers Femme, on masque la liste (hidden).
            $is_homme = stripos( $this->current_parent_title, 'univers homme' ) !== false;
            $is_femme = stripos( $this->current_parent_title, 'univers femme' ) !== false;
            $is_chaussures_homme = stripos( $this->current_parent_title, 'chaussures homme' ) !== false;
            $is_chaussures_femme = stripos( $this->current_parent_title, 'chaussures femme' ) !== false;
            $is_accessoires_homme = stripos( $this->current_parent_title, 'accessoires homme' ) !== false;
            $is_accessoires_femme = stripos( $this->current_parent_title, 'accessoires femme' ) !== false;
            $is_luxe_createurs = stripos( $this->current_parent_title, 'luxe et créateurs' ) !== false;

            $is_bento_menu = $is_homme || $is_femme || $is_luxe_createurs;
            
            if ( $is_bento_menu ) {
                $ul_class = 'hidden';
            } elseif ( $is_chaussures_homme ) {
                 $ul_class = 'w-1/5 min-w-[200px] flex-shrink-0 grid grid-cols-1 gap-x-8 gap-y-2 text-sm border-r border-base-200 pr-6';
            } elseif ( $is_chaussures_femme ) {
                $ul_class = 'w-1/5 min-w-[200px] flex-shrink-0 grid grid-cols-1 gap-x-8 gap-y-2 text-sm border-r border-base-200 pr-6';
            } elseif ( $is_accessoires_homme ) {
                $ul_class = 'w-1/5 min-w-[200px] flex-shrink-0 grid grid-cols-1 gap-x-8 gap-y-2 text-sm border-r border-base-200 pr-6';
            } elseif ( $is_accessoires_femme ) {
                $ul_class = 'w-1/5 min-w-[200px] flex-shrink-0 grid grid-cols-1 gap-x-8 gap-y-2 text-sm border-r border-base-200 pr-6';
            } elseif ( $is_luxe_createurs ) {
                $ul_class = 'w-1/5 min-w-[200px] flex-shrink-0 grid grid-cols-1 gap-x-8 gap-y-2 text-sm border-r border-base-200 pr-6';
            } else {
                $ul_class = 'grid grid-cols-1 gap-x-8 gap-y-2 w-full text-sm';
            }

            $output .= '<ul class="' . $ul_class . '">';
        } else {
            $output .= '<ul class="p-2 bg-base-100 rounded-box">';
        }
    }

    public function end_lvl( &$output, $depth = 0, $args = null ): void
    {
        if ( $depth === 0 && $this->is_mega_menu ) {
            $output .= '</ul>';
            
            // Configuration des clés et valeurs par défaut
            $section_key = '';
            $defaults = [];
            $img_base = get_template_directory_uri() . '/public/mega-menu/';

            if (stripos( $this->current_parent_title, 'univers homme' ) !== false) {
                $section_key = 'homme';
                $defaults = [
                    1 => ['img' => $img_base . 'homme/vetements.webp', 'title' => 'Vêtements', 'url' => home_url( '/categorie-produit/homme/vetements-homme/' )],
                    2 => ['img' => $img_base . 'homme/sneakers.webp', 'title' => 'Chaussures et sneakers', 'url' => home_url( '/categorie-produit/homme/chaussures-homme/' )],
                    3 => ['img' => $img_base . 'homme/accessoires.jpeg', 'title' => 'Accessoires', 'url' => home_url( '/categorie-produit/homme/accessoires-homme/' )],
                ];
            } elseif (stripos( $this->current_parent_title, 'univers femme' ) !== false) {
                $section_key = 'femme';
                $defaults = [
                    1 => ['img' => $img_base . 'femme/vetements.webp', 'title' => 'Vêtements', 'url' => home_url( '/categorie-produit/femme/vetements-femme/' )],
                    2 => ['img' => $img_base . 'femme/sneakers.jpg', 'title' => 'Chaussures et sneakers', 'url' => home_url( '/categorie-produit/femme/chaussures-femme/' )],
                    3 => ['img' => $img_base . 'femme/accessoires.jpg', 'title' => 'Accessoires', 'url' => home_url( '/categorie-produit/femme/accessoires-femme/' )],
                ];
            } elseif (stripos( $this->current_parent_title, 'chaussures homme' ) !== false) {
                $section_key = 'shoes_h';
                $defaults = [
                    1 => ['img' => $img_base . 'chaussures-homme/chaussures-homme-3.jpeg', 'title' => 'Ville', 'url' => home_url( '/categorie-produit/homme/chaussures-homme/homme-schuhe-derbies/' )],
                    2 => ['img' => $img_base . 'chaussures-homme/chaussures-homme-2.jpg', 'title' => 'Toutes les chaussures', 'url' => home_url( '/categorie-produit/homme/chaussures-homme/' )],
                    3 => ['img' => $img_base . 'chaussures-homme/chaussures-homme-1.jpg', 'title' => 'Baskets', 'url' => home_url( '/categorie-produit/homme/chaussures-homme/homme-baskets/' )],
                ];
            } elseif (stripos( $this->current_parent_title, 'chaussures femme' ) !== false) {
                $section_key = 'shoes_f';
                $defaults = [
                    1 => ['img' => $img_base . 'chaussures-femme/chaussures-femme-1.jpg', 'title' => 'Bottes', 'url' => home_url( '/categorie-produit/femme/chaussures-femme/femme-bottes/' )],
                    2 => ['img' => $img_base . 'chaussures-femme/chaussures-femme-4.jpg', 'title' => 'Toutes les chaussures', 'url' => home_url( '/categorie-produit/femme/chaussures-femme/' )],
                    3 => ['img' => $img_base . 'chaussures-femme/chaussures-femme-3.jpg', 'title' => 'Sneakers', 'url' => home_url( '/categorie-produit/femme/chaussures-femme/femme-baskets/' )],
                ];
            } elseif (stripos( $this->current_parent_title, 'accessoires homme' ) !== false) {
                $section_key = 'acc_h';
                $defaults = [
                    1 => ['img' => $img_base . 'accessoires-homme/accessoires-homme-3.jpg', 'title' => 'Sacs', 'url' => home_url( '/categorie-produit/homme/accessoires-homme/homme-sacs/' )],
                    2 => ['img' => $img_base . 'accessoires-homme/accessoires-homme-1.jpg', 'title' => 'Bonnets et casquettes', 'url' => home_url( '/categorie-produit/homme/accessoires-homme/homme-chapeaux/' )],
                    3 => ['img' => $img_base . 'accessoires-homme/accessoires-homme-2.jpg', 'title' => 'Ceintures', 'url' => home_url( '/categorie-produit/homme/accessoires-homme/homme-ceintures/' )],
                ];
            } elseif (stripos( $this->current_parent_title, 'accessoires femme' ) !== false) {
                $section_key = 'acc_f';
                $defaults = [
                    1 => ['img' => $img_base . 'accessoires-femme/accessoires-femme-1.jpg', 'title' => 'Chapeaux, bonnets et casquettes', 'url' => home_url( '/categorie-produit/femme/accessoires-femme/femme-chapeaux/' )],
                    2 => ['img' => $img_base . 'accessoires-femme/accessoires-femme-2.jpg', 'title' => 'Sacs', 'url' => home_url( '/categorie-produit/femme/accessoires-femme/femme-sacs/' )],
                    3 => ['img' => $img_base . 'accessoires-femme/accessoires-femme-3.jpg', 'title' => 'Ceintures', 'url' => home_url( '/categorie-produit/femme/accessoires-femme/femme-ceintures/' )],
                ];
            } elseif (stripos( $this->current_parent_title, 'luxe et créateurs' ) !== false) {
                $section_key = 'luxe';
                $defaults = [
                    1 => ['img' => $img_base . 'luxe/luxe-createurs-1.webp', 'title' => 'Vêtements', 'url' => home_url( '/categorie-produit/luxe-createurs/luxe-createurs-vetements/' )],
                    2 => ['img' => $img_base . 'luxe/luxe-createurs-4.jpg', 'title' => 'Chaussures', 'url' => home_url( '/categorie-produit/luxe-createurs/luxe-createurs-chaussures/' )],
                    3 => ['img' => $img_base . 'luxe/luxe-createurs-5.jpg', 'title' => 'Accessoires', 'url' => home_url( '/categorie-produit/luxe-createurs/luxe-createurs-accessoires/' )],
                ];
            }

            // Génération du HTML si une section correspondante est trouvée
            if ($section_key) {
                $blocks = $this->get_mega_menu_data($section_key, $defaults);
                
                $output .= '<div class="flex-grow grid grid-cols-4 grid-rows-2 gap-4">';
                
                // Block 1 (Gauche Vertical)
                $output .= sprintf(
                    '<a href="%s" class="col-span-2 row-span-2 relative rounded-box overflow-hidden group shadow-lg block">
                        <img src="%s" alt="%s" class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                        <div class="absolute inset-0 bg-black/20 group-hover:bg-black/10 transition-colors"></div>
                        <div class="absolute bottom-4 left-4 text-white font-bold text-xl shadow-black drop-shadow-md">%s</div>
                    </a>',
                    esc_url($blocks[1]['url']),
                    esc_url($blocks[1]['img']),
                    esc_attr($blocks[1]['title']),
                    esc_html($blocks[1]['title'])
                );

                // Block 2 (Haut Droite)
                $output .= sprintf(
                    '<a href="%s" class="col-span-2 relative rounded-box overflow-hidden group shadow-lg block">
                        <img src="%s" alt="%s" class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                        <div class="absolute inset-0 bg-black/20 group-hover:bg-black/10 transition-colors"></div>
                        <div class="absolute bottom-3 left-3 text-white font-bold text-lg drop-shadow-md">%s</div>
                    </a>',
                    esc_url($blocks[2]['url']),
                    esc_url($blocks[2]['img']),
                    esc_attr($blocks[2]['title']),
                    esc_html($blocks[2]['title'])
                );

                // Block 3 (Bas Droite)
                $output .= sprintf(
                    '<a href="%s" class="col-span-2 relative rounded-box overflow-hidden group shadow-lg block">
                        <img src="%s" alt="%s" class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                        <div class="absolute inset-0 bg-black/20 group-hover:bg-black/10 transition-colors"></div>
                        <div class="absolute bottom-3 left-3 text-white font-bold text-lg drop-shadow-md">%s</div>
                    </a>',
                    esc_url($blocks[3]['url']),
                    esc_url($blocks[3]['img']),
                    esc_attr($blocks[3]['title']),
                    esc_html($blocks[3]['title'])
                );

                $output .= '</div>';
            }

            $output .= '</div></div>'; // Fin Flex + Fin Mega Menu Wrapper
        } else {
            $output .= '</ul>';
        }
    }

    public function start_el( &$output, $data_object, $depth = 0, $args = null, $id = 0 ): void
    {
        $menu_item = $data_object;
        $this->current_item_id = $menu_item->ID;

        // On vérifie que $menu_item->classes est bien un tableau avant de l'utiliser.
        $has_children = ( is_array($menu_item->classes) && in_array('menu-item-has-children', $menu_item->classes) );

        // Gère la balise <li> parente
        if ($depth === 0) {
            $this->current_parent_title = $menu_item->title; // Capture du titre
            
            if ($has_children) {
                $this->is_mega_menu = true;
                $output .= '<li class="static">';
            } else {
                $this->is_mega_menu = false;
                $output .= '<li>';
            }
        } else {
            $this->is_mega_menu = ($depth > 0 && $this->is_mega_menu);
            $output .= '<li>';
        }

        $attributes = ! empty( $menu_item->url ) ? ' href="' . esc_attr( $menu_item->url ) . '"' : '';
        $a_classes = []; // On initialise un tableau pour les classes

        if ($depth === 0) { // Liens de premier niveau
            if ($has_children) {
                $attributes .= ' @mouseenter="openMenu = \'menu-item-' . $menu_item->ID . '\'"';
                $attributes .= ' :class="{ \'text-primary\': openMenu === \'menu-item-' . $menu_item->ID . '\' }"';
                if (stripos($menu_item->title, 'luxe et créateurs') !== false) {
                    $a_classes[] = 'menu-luxe-glow'; // Custom class for glow
                }
            } else {
                // Ici, on gère les classes pour les liens simples
                if (strtoupper($menu_item->title) === 'PROMOS -15%') {
                    $a_classes[] = 'btn btn-dash btn-error';
                } else {
                    $a_classes[] = 'hover:text-primary';
                }
            }
        } else if ($this->is_mega_menu) { // Liens dans le méga-menu
            $a_classes = ['block', 'p-2', 'hover:bg-neutral-focus', 'rounded-btn', 'text-neutral-content/80', 'hover:text-white', 'transition-colors'];
        }

        // On assemble l'attribut class s'il y a des classes à ajouter
        if ( !empty($a_classes) ) {
            $attributes .= ' class="' . implode(' ', $a_classes) . '"';
        }

        $item_output = $args->before;
        $item_output .= '<a' . $attributes . '>';
        $item_output .= $args->link_before . apply_filters( 'the_title', $menu_item->title, $menu_item->ID ) . $args->link_after;
        $item_output .= '</a>';
        $item_output .= $args->after;

        // MODIFICATION: Inject Top Marques Mega Menu manually because start_lvl is skipped for items without children
        if ( $depth === 0 && strtoupper($menu_item->title) === 'TOP MARQUES' ) {
            $item_output .= $this->render_brands_mega_menu( $menu_item->ID );
        }

        $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $menu_item, $depth, $args );
    }

    public function end_el( &$output, $data_object, $depth = 0, $args = null ): void
    {
        $output .= '</li>';
    }
}