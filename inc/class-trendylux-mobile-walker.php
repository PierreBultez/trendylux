<?php
class TRENDYLUX_Mobile_Walker extends Walker_Nav_Menu {

    public function start_lvl( &$output, $depth = 0, $args = null ) {
        // DaisyUI nested menu uses <ul>
        $output .= '<ul>';
    }

    public function end_lvl( &$output, $depth = 0, $args = null ) {
        $output .= '</ul>';
    }

    public function start_el( &$output, $data_object, $depth = 0, $args = null, $id = 0 ) {
        $menu_item = $data_object;
        $has_children = in_array('menu-item-has-children', $menu_item->classes);
        $title = apply_filters( 'the_title', $menu_item->title, $menu_item->ID );

        $output .= '<li>';

        if ( $has_children ) {
            // Collapsible menu using <details>
            $output .= '<details>';
            $output .= '<summary>' . $title . '</summary>';
            // The <ul> will be added by start_lvl
        } else {
            // Standard link
            $attributes  = ! empty( $menu_item->url ) ? ' href="' . esc_attr( $menu_item->url ) . '"' : '';
            $output .= '<a' . $attributes . '>' . $title . '</a>';
        }
    }

    public function end_el( &$output, $data_object, $depth = 0, $args = null ) {
        $menu_item = $data_object;
        $has_children = in_array('menu-item-has-children', $menu_item->classes);

        if ( $has_children ) {
            $output .= '</details>';
        }
        $output .= '</li>';
    }
}
