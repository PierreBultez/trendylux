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

            // Ajustement : Pour Univers Homme ou Univers Femme, on masque la liste (hidden).
            $is_homme = stripos( $this->current_parent_title, 'univers homme' ) !== false;
            $is_femme = stripos( $this->current_parent_title, 'univers femme' ) !== false;
            $is_chaussures_homme = stripos( $this->current_parent_title, 'chaussures homme' ) !== false;
            $is_chaussures_femme = stripos( $this->current_parent_title, 'chaussures femme' ) !== false;
            $is_accessoires_homme = stripos( $this->current_parent_title, 'accessoires homme' ) !== false;
            $is_accessoires_femme = stripos( $this->current_parent_title, 'accessoires femme' ) !== false;
            $is_luxe_createurs = stripos( $this->current_parent_title, "luxe et créateurs" ) !== false;

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

            // Injection du Bento Grid pour la catégorie Univers Homme
            if ( stripos( $this->current_parent_title, 'univers homme' ) !== false ) {
                $img_base = get_template_directory_uri() . '/public/mega-menu/homme/';
                
                $output .= '<div class="flex-grow grid grid-cols-4 grid-rows-2 gap-4">';
                
                // Image 1 : Grande image verticale à gauche
                $output .= '<a href="' . home_url( '/categorie-produit/homme/vetements-homme/' ) . '" class="col-span-2 row-span-2 relative rounded-box overflow-hidden group shadow-lg block">';
                $output .= '<img src="' . $img_base . 'vetements.webp" alt="Mode Homme" class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">';
                $output .= '<div class="absolute inset-0 bg-black/20 group-hover:bg-black/10 transition-colors"></div>';
                $output .= '<div class="absolute bottom-4 left-4 text-white font-bold text-xl shadow-black drop-shadow-md">Vêtements</div>';
                $output .= '</a>';

                // Image 2 : Image horizontale en haut à droite
                $output .= '<a href="' . home_url( '/categorie-produit/homme/chaussures-homme/' ) . '" class="col-span-2 relative rounded-box overflow-hidden group shadow-lg block">';
                $output .= '<img src="' . $img_base . 'sneakers.jpg" alt="Streetwear" class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">';
                $output .= '<div class="absolute inset-0 bg-black/20 group-hover:bg-black/10 transition-colors"></div>';
                $output .= '<div class="absolute bottom-3 left-3 text-white font-bold text-lg drop-shadow-md">Chaussures et sneakers</div>';
                $output .= '</a>';

                // Image 3 : Image horizontale en bas à droite
                $output .= '<a href="' . home_url( '/categorie-produit/homme/accessoires-homme/' ) . '" class="col-span-2 relative rounded-box overflow-hidden group shadow-lg block">';
                $output .= '<img src="' . $img_base . 'accessoires.jpeg" alt="Accessoires" class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">';
                $output .= '<div class="absolute inset-0 bg-black/20 group-hover:bg-black/10 transition-colors"></div>';
                $output .= '<div class="absolute bottom-3 left-3 text-white font-bold text-lg drop-shadow-md">Accessoires</div>';
                $output .= '</a>';

                $output .= '</div>'; // Fin Grid Homme
            }

            // Injection du Bento Grid pour la catégorie Univers Femme
            if ( stripos( $this->current_parent_title, 'univers femme' ) !== false ) {
                $img_base = get_template_directory_uri() . '/public/mega-menu/femme/';
                
                $output .= '<div class="flex-grow grid grid-cols-4 grid-rows-2 gap-4">';
                
                // Image 1 : Grande image verticale à gauche
                $output .= '<a href="' . home_url( '/categorie-produit/femme/vetements-femme/' ) . '" class="col-span-2 row-span-2 relative rounded-box overflow-hidden group shadow-lg block">';
                $output .= '<img src="' . $img_base . 'vetements.webp" alt="Mode Femme" class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">';
                $output .= '<div class="absolute inset-0 bg-black/20 group-hover:bg-black/10 transition-colors"></div>';
                $output .= '<div class="absolute bottom-4 left-4 text-white font-bold text-xl shadow-black drop-shadow-md">Vêtements</div>';
                $output .= '</a>';

                // Image 2 : Image horizontale en haut à droite
                $output .= '<a href="' . home_url( '/categorie-produit/femme/chaussures-femme/' ) . '" class="col-span-2 relative rounded-box overflow-hidden group shadow-lg block">';
                $output .= '<img src="' . $img_base . 'sneakers.jpg" alt="Chaussures" class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">';
                $output .= '<div class="absolute inset-0 bg-black/20 group-hover:bg-black/10 transition-colors"></div>';
                $output .= '<div class="absolute bottom-3 left-3 text-white font-bold text-lg drop-shadow-md">Chaussures et sneakers</div>';
                $output .= '</a>';

                // Image 3 : Image horizontale en bas à droite
                $output .= '<a href="' . home_url( '/categorie-produit/femme/accessoires-femme/' ) . '" class="col-span-2 relative rounded-box overflow-hidden group shadow-lg block">';
                $output .= '<img src="' . $img_base . 'accessoires.jpg" alt="Accessoires" class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">';
                $output .= '<div class="absolute inset-0 bg-black/20 group-hover:bg-black/10 transition-colors"></div>';
                $output .= '<div class="absolute bottom-3 left-3 text-white font-bold text-lg drop-shadow-md">Accessoires</div>';
                $output .= '</a>';

                $output .= '</div>'; // Fin Grid Femme
            }

            // Injection du Bento Grid pour Chaussures Homme
            if ( stripos( $this->current_parent_title, 'chaussures homme' ) !== false ) {
                $img_base = get_template_directory_uri() . '/public/mega-menu/chaussures-homme/';
                
                $output .= '<div class="flex-grow grid grid-cols-4 grid-rows-2 gap-4">';
                
                // Image 1 : Grande image verticale à gauche
                $output .= '<a href="' . home_url( '/categorie-produit/homme/chaussures-homme/homme-schuhe-derbies/' ) . '" class="col-span-2 row-span-2 relative rounded-box overflow-hidden group shadow-lg block">';
                $output .= '<img src="' . $img_base . 'chaussures-homme-3.jpeg" alt="Sneakers" class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">';
                $output .= '<div class="absolute inset-0 bg-black/20 group-hover:bg-black/10 transition-colors"></div>';
                $output .= '<div class="absolute bottom-4 left-4 text-white font-bold text-xl shadow-black drop-shadow-md">Ville</div>';
                $output .= '</a>';

                // Image 2 : Image horizontale en haut à droite
                $output .= '<a href="' . home_url( '/categorie-produit/homme/chaussures-homme/' ) . '" class="col-span-2 relative rounded-box overflow-hidden group shadow-lg block">';
                $output .= '<img src="' . $img_base . 'chaussures-homme-2.jpg" alt="Chaussures de ville" class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">';
                $output .= '<div class="absolute inset-0 bg-black/20 group-hover:bg-black/10 transition-colors"></div>';
                $output .= '<div class="absolute bottom-3 left-3 text-white font-bold text-lg drop-shadow-md">Toutes les chaussures</div>';
                $output .= '</a>';

                // Image 3 : Image horizontale en bas à droite
                $output .= '<a href="' . home_url( '/categorie-produit/homme/chaussures-homme/homme-baskets/' ) . '" class="col-span-2 relative rounded-box overflow-hidden group shadow-lg block">';
                $output .= '<img src="' . $img_base . 'chaussures-homme-1.jpg" alt="Sport & Running" class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">';
                $output .= '<div class="absolute inset-0 bg-black/20 group-hover:bg-black/10 transition-colors"></div>';
                $output .= '<div class="absolute bottom-3 left-3 text-white font-bold text-lg drop-shadow-md">Baskets</div>';
                $output .= '</a>';

                $output .= '</div>'; // Fin Grid Chaussures Homme
            }

            // Injection du Bento Grid pour Chaussures Femme
            if ( stripos( $this->current_parent_title, 'chaussures femme' ) !== false ) {
                $img_base = get_template_directory_uri() . '/public/mega-menu/chaussures-femme/';

                $output .= '<div class="flex-grow grid grid-cols-4 grid-rows-2 gap-4">';

                // Image 1 : Grande image verticale à gauche
                $output .= '<a href="' . home_url( '/categorie-produit/femme/chaussures-femme/femme-bottes/' ) . '" class="col-span-2 row-span-2 relative rounded-box overflow-hidden group shadow-lg block">';
                $output .= '<img src="' . $img_base . 'chaussures-femme-1.jpg" alt="chaussure de femme" class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">';
                $output .= '<div class="absolute inset-0 bg-black/20 group-hover:bg-black/10 transition-colors"></div>';
                $output .= '<div class="absolute bottom-4 left-4 text-white font-bold text-xl shadow-black drop-shadow-md">Bottes</div>';
                $output .= '</a>';

                // Image 2 : Image horizontale en haut à droite
                $output .= '<a href="' . home_url( '/categorie-produit/femme/chaussures-femme/' ) . '" class="col-span-2 relative rounded-box overflow-hidden group shadow-lg block">';
                $output .= '<img src="' . $img_base . 'chaussures-femme-4.jpg" alt="chaussure basse pour femme" class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">';
                $output .= '<div class="absolute inset-0 bg-black/20 group-hover:bg-black/10 transition-colors"></div>';
                $output .= '<div class="absolute bottom-3 left-3 text-white font-bold text-lg drop-shadow-md">Toutes les chaussures</div>';
                $output .= '</a>';

                // Image 3 : Image horizontale en bas à droite
                $output .= '<a href="' . home_url( '/categorie-produit/femme/chaussures-femme/femme-baskets/' ) . '" class="col-span-2 relative rounded-box overflow-hidden group shadow-lg block">';
                $output .= '<img src="' . $img_base . 'chaussures-femme-3.jpg" alt="basket pour femme" class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">';
                $output .= '<div class="absolute inset-0 bg-black/20 group-hover:bg-black/10 transition-colors"></div>';
                $output .= '<div class="absolute bottom-3 left-3 text-white font-bold text-lg drop-shadow-md">Sneakers</div>';
                $output .= '</a>';

                $output .= '</div>'; // Fin Grid Chaussures Femme
            }

            // Injection du Bento Grid pour Accessoires Homme
            if ( stripos( $this->current_parent_title, 'accessoires homme' ) !== false ) {
                $img_base = get_template_directory_uri() . '/public/mega-menu/accessoires-homme/';

                $output .= '<div class="flex-grow grid grid-cols-4 grid-rows-2 gap-4">';

                // Image 1 : Grande image verticale à gauche
                $output .= '<a href="' . home_url( '/categorie-produit/homme/accessoires-homme/homme-sacs/' ) . '" class="col-span-2 row-span-2 relative rounded-box overflow-hidden group shadow-lg block">';
                $output .= '<img src="' . $img_base . 'accessoires-homme-3.jpg" alt="sac à dos homme" class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">';
                $output .= '<div class="absolute inset-0 bg-black/20 group-hover:bg-black/10 transition-colors"></div>';
                $output .= '<div class="absolute bottom-4 left-4 text-white font-bold text-xl shadow-black drop-shadow-md">Sacs</div>';
                $output .= '</a>';

                // Image 2 : Image horizontale en haut à droite
                $output .= '<a href="' . home_url( '/categorie-produit/homme/accessoires-homme/homme-chapeaux/' ) . '" class="col-span-2 relative rounded-box overflow-hidden group shadow-lg block">';
                $output .= '<img src="' . $img_base . 'accessoires-homme-1.jpg" alt="mannequin portant une casquette" class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">';
                $output .= '<div class="absolute inset-0 bg-black/20 group-hover:bg-black/10 transition-colors"></div>';
                $output .= '<div class="absolute bottom-3 left-3 text-white font-bold text-lg drop-shadow-md">Bonnets et casquettes</div>';
                $output .= '</a>';

                // Image 3 : Image horizontale en bas à droite
                $output .= '<a href="' . home_url( '/categorie-produit/homme/accessoires-homme/homme-ceintures/' ) . '" class="col-span-2 relative rounded-box overflow-hidden group shadow-lg block">';
                $output .= '<img src="' . $img_base . 'accessoires-homme-2.jpg" alt="ceinture en cuir" class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">';
                $output .= '<div class="absolute inset-0 bg-black/20 group-hover:bg-black/10 transition-colors"></div>';
                $output .= '<div class="absolute bottom-3 left-3 text-white font-bold text-lg drop-shadow-md">Ceintures</div>';
                $output .= '</a>';

                $output .= '</div>'; // Fin Grid Accessoires Homme
            }

            // Injection du Bento Grid pour Accessoires Femme
            if ( stripos( $this->current_parent_title, 'accessoires femme' ) !== false ) {
                $img_base = get_template_directory_uri() . '/public/mega-menu/accessoires-femme/';

                $output .= '<div class="flex-grow grid grid-cols-4 grid-rows-2 gap-4">';

                // Image 1 : Grande image verticale à gauche
                $output .= '<a href="' . home_url( '/categorie-produit/femme/accessoires-femme/femme-chapeaux/' ) . '" class="col-span-2 row-span-2 relative rounded-box overflow-hidden group shadow-lg block">';
                $output .= '<img src="' . $img_base . 'accessoires-femme-1.jpg" alt="femme portant un bonnet" class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">';
                $output .= '<div class="absolute inset-0 bg-black/20 group-hover:bg-black/10 transition-colors"></div>';
                $output .= '<div class="absolute bottom-4 left-4 text-white font-bold text-xl shadow-black drop-shadow-md">Chapeaux, bonnets et casquettes</div>';
                $output .= '</a>';

                // Image 2 : Image horizontale en haut à droite
                $output .= '<a href="' . home_url( '/categorie-produit/femme/accessoires-femme/femme-sacs/' ) . '" class="col-span-2 relative rounded-box overflow-hidden group shadow-lg block">';
                $output .= '<img src="' . $img_base . 'accessoires-femme-2.jpg" alt="petit sac à main de femme" class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">';
                $output .= '<div class="absolute inset-0 bg-black/20 group-hover:bg-black/10 transition-colors"></div>';
                $output .= '<div class="absolute bottom-3 left-3 text-white font-bold text-lg drop-shadow-md">Sacs</div>';
                $output .= '</a>';

                // Image 3 : Image horizontale en bas à droite
                $output .= '<a href="' . home_url( '/categorie-produit/femme/accessoires-femme/femme-ceintures/' ) . '" class="col-span-2 relative rounded-box overflow-hidden group shadow-lg block">';
                $output .= '<img src="' . $img_base . 'accessoires-femme-3.jpg" alt="ceinture en cuir pour femme" class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">';
                $output .= '<div class="absolute inset-0 bg-black/20 group-hover:bg-black/10 transition-colors"></div>';
                $output .= '<div class="absolute bottom-3 left-3 text-white font-bold text-lg drop-shadow-md">Ceintures</div>';
                $output .= '</a>';

                $output .= '</div>'; // Fin Grid Accessoires Homme
            }

            // Injection du Bento Grid pour la catégorie Luxe & Createurs
            if ( stripos( $this->current_parent_title, 'luxe et créateurs' ) !== false ) {
                $img_base = get_template_directory_uri() . '/public/mega-menu/luxe/';

                $output .= '<div class="flex-grow grid grid-cols-4 grid-rows-2 gap-4">';

                // Image 1 : Grande image verticale à gauche
                $output .= '<a href="' . home_url( '/categorie-produit/luxe-createurs/' ) . '" class="col-span-2 row-span-2 relative rounded-box overflow-hidden group shadow-lg block">';
                $output .= '<img src="' . $img_base . 'luxe-createurs-1.webp" alt="Femme en vetements de luxe" class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">';
                $output .= '<div class="absolute inset-0 bg-black/20 group-hover:bg-black/10 transition-colors"></div>';
                $output .= '<div class="absolute bottom-4 left-4 text-white font-bold text-xl shadow-black drop-shadow-md">Vêtements</div>';
                $output .= '</a>';

                // Image 2 : Image horizontale en haut à droite
                $output .= '<a href="' . home_url( '/categorie-produit/luxe-createurs/' ) . '" class="col-span-2 relative rounded-box overflow-hidden group shadow-lg block">';
                $output .= '<img src="' . $img_base . 'luxe-createurs-3.webp" alt="casquette de luxe" class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">';
                $output .= '<div class="absolute inset-0 bg-black/20 group-hover:bg-black/10 transition-colors"></div>';
                $output .= '<div class="absolute bottom-3 left-3 text-white font-bold text-lg drop-shadow-md">Chaussures</div>';
                $output .= '</a>';

                // Image 3 : Image horizontale en bas à droite
                $output .= '<a href="' . home_url( '/categorie-produit/luxe-createurs/' ) . '" class="col-span-2 relative rounded-box overflow-hidden group shadow-lg block">';
                $output .= '<img src="' . $img_base . 'luxe-createurs-2.jpg" alt="chaussure de luxe" class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">';
                $output .= '<div class="absolute inset-0 bg-black/20 group-hover:bg-black/10 transition-colors"></div>';
                $output .= '<div class="absolute bottom-3 left-3 text-white font-bold text-lg drop-shadow-md">Accessoires</div>';
                $output .= '</a>';

                $output .= '</div>'; // Fin Grid Luxe & créateurs
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

        $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $menu_item, $depth, $args );
    }

    public function end_el( &$output, $data_object, $depth = 0, $args = null ): void
    {
        $output .= '</li>';
    }
}