<?php
class TRENDYLUX_Nav_Walker extends Walker_Nav_Menu {

    private bool $is_mega_menu = false;
    private int $current_item_id = 0;
    private string $current_parent_title = '';

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

            // Ajustement : Pour Homme, on masque la liste (hidden). Pour les autres, affichage normal.
            $is_homme = stripos( $this->current_parent_title, 'univers homme' ) !== false && stripos( $this->current_parent_title, 'femme' ) === false;
            $ul_class = $is_homme ? 'hidden' : 'grid grid-cols-1 gap-x-8 gap-y-2 w-full text-sm';

            $output .= '<ul class="' . $ul_class . '">';
        } else {
            $output .= '<ul class="p-2 bg-base-100 rounded-box">';
        }
    }

    public function end_lvl( &$output, $depth = 0, $args = null ): void
    {
        if ( $depth === 0 && $this->is_mega_menu ) {
            $output .= '</ul>';

            // Injection du Bento Grid pour la catégorie Homme
            if ( stripos( $this->current_parent_title, 'univers homme' ) !== false && stripos( $this->current_parent_title, 'femme' ) === false ) {
                $img_base = get_template_directory_uri() . '/public/mega-menu/homme/';
                
                $output .= '<div class="flex-grow grid grid-cols-4 grid-rows-2 gap-4">';
                
                // Image 1 : Grande image verticale à gauche (2 colonnes, 2 rangées)
                $output .= '<div class="col-span-2 row-span-2 relative rounded-box overflow-hidden group shadow-lg">';
                $output .= '<img src="' . $img_base . 'homme-1.jpg" alt="Mode Homme" class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">';
                $output .= '<div class="absolute inset-0 bg-black/20 group-hover:bg-black/10 transition-colors"></div>';
                $output .= '<div class="absolute bottom-4 left-4 text-white font-bold text-xl shadow-black drop-shadow-md">Vêtements</div>';
                $output .= '</div>';

                // Image 2 : Image horizontale en haut à droite (2 colonnes, 1 rangée)
                $output .= '<div class="col-span-2 relative rounded-box overflow-hidden group shadow-lg">';
                $output .= '<img src="' . $img_base . 'homme-2.webp" alt="Streetwear" class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">';
                 $output .= '<div class="absolute inset-0 bg-black/20 group-hover:bg-black/10 transition-colors"></div>';
                $output .= '<div class="absolute bottom-3 left-3 text-white font-bold text-lg drop-shadow-md">Chaussures et sneakers</div>';
                $output .= '</div>';

                // Image 3 : Image horizontale en bas à droite (2 colonnes, 1 rangée)
                $output .= '<div class="col-span-2 relative rounded-box overflow-hidden group shadow-lg">';
                $output .= '<img src="' . $img_base . 'homme-3.jpg" alt="Accessoires" class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">';
                 $output .= '<div class="absolute inset-0 bg-black/20 group-hover:bg-black/10 transition-colors"></div>';
                $output .= '<div class="absolute bottom-3 left-3 text-white font-bold text-lg drop-shadow-md">Accessoires</div>';
                $output .= '</div>';

                $output .= '</div>'; // Fin Grid
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
            } else {
                // Ici, on gère les classes pour les liens simples
                if (strtoupper($menu_item->title) === 'PROMOS') {
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

        $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $menu_item, $depth, $args );
    }

    public function end_el( &$output, $data_object, $depth = 0, $args = null ): void
    {
        $output .= '</li>';
    }
}