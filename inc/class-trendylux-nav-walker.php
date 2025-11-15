<?php
class TRENDYLUX_Nav_Walker extends Walker_Nav_Menu {

    private bool $is_mega_menu = false;
    private int $current_item_id = 0;

    public function start_lvl( &$output, $depth = 0, $args = null ): void
    {
        if ( $depth === 0 && $this->is_mega_menu ) {
            $output .= '<div x-cloak x-show="openMenu === \'menu-item-' . $this->current_item_id . '\'" @mouseenter="openMenu = \'menu-item-' . $this->current_item_id . '\'" @mouseleave="openMenu = null" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-2" class="absolute left-1/2 -translate-x-1/2 top-full z-10 mt-3 w-screen max-w-4xl overflow-hidden rounded-box bg-neutral text-neutral-content shadow-lg ring-1 ring-black/5" style="display: none;">';
            $output .= '<div class="p-4"><ul class="grid grid-cols-2 gap-x-8 gap-y-4">';
        } else {
            $output .= '<ul class="p-2 bg-base-100 rounded-box">';
        }
    }

    public function end_lvl( &$output, $depth = 0, $args = null ): void
    {
        if ( $depth === 0 && $this->is_mega_menu ) {
            $output .= '</ul></div></div>';
        } else {
            $output .= '</ul>';
        }
    }

    public function start_el( &$output, $data_object, $depth = 0, $args = null, $id = 0 ): void
    {
        $menu_item = $data_object;
        $this->current_item_id = $menu_item->ID;

        $has_children = in_array('menu-item-has-children', $menu_item->classes);

        // Gère la balise <li> parente
        if ($depth === 0 && $has_children) {
            $this->is_mega_menu = true;
            $output .= '<li class="relative">';
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
                    $a_classes[] = 'text-error';
                    $a_classes[] = 'hover:text-error/80';
                } else {
                    $a_classes[] = 'hover:text-primary';
                }
            }
        } else if ($this->is_mega_menu) { // Liens dans le méga-menu
            $a_classes = ['block', 'p-2', 'hover:bg-base-100/10', 'rounded-field'];
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