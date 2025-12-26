<?php
/**
 * Register Custom Post Type for FAQ
 */
function trendylux_register_faq_cpt() {
    $labels = array(
        'name'                  => _x( 'Questions / Réponses', 'Post Type General Name', 'trendylux' ),
        'singular_name'         => _x( 'Question', 'Post Type Singular Name', 'trendylux' ),
        'menu_name'             => __( 'FAQ', 'trendylux' ),
        'name_admin_bar'        => __( 'FAQ', 'trendylux' ),
        'archives'              => __( 'Archives FAQ', 'trendylux' ),
        'attributes'            => __( 'Attributs', 'trendylux' ),
        'parent_item_colon'     => __( 'Parent :', 'trendylux' ),
        'all_items'             => __( 'Toutes les questions', 'trendylux' ),
        'add_new_item'          => __( 'Ajouter une nouvelle question', 'trendylux' ),
        'add_new'               => __( 'Ajouter', 'trendylux' ),
        'new_item'              => __( 'Nouvelle question', 'trendylux' ),
        'edit_item'             => __( 'Modifier la question', 'trendylux' ),
        'update_item'           => __( 'Mettre à jour', 'trendylux' ),
        'view_item'             => __( 'Voir la question', 'trendylux' ),
        'view_items'            => __( 'Voir les questions', 'trendylux' ),
        'search_items'          => __( 'Rechercher', 'trendylux' ),
        'not_found'             => __( 'Aucune question trouvée', 'trendylux' ),
        'not_found_in_trash'    => __( 'Aucune question trouvée dans la corbeille', 'trendylux' ),
        'featured_image'        => __( 'Image mise en avant', 'trendylux' ),
        'set_featured_image'    => __( 'Définir l\'image mise en avant', 'trendylux' ),
        'remove_featured_image' => __( 'Supprimer l\'image mise en avant', 'trendylux' ),
        'use_featured_image'    => __( 'Utiliser comme image mise en avant', 'trendylux' ),
        'insert_into_item'      => __( 'Insérer dans la question', 'trendylux' ),
        'uploaded_to_this_item' => __( 'Téléversé sur cette question', 'trendylux' ),
        'items_list'            => __( 'Liste des questions', 'trendylux' ),
        'items_list_navigation' => __( 'Navigation de la liste des questions', 'trendylux' ),
        'filter_items_list'     => __( 'Filtrer la liste des questions', 'trendylux' ),
    );
    $args = array(
        'label'                 => __( 'Question', 'trendylux' ),
        'description'           => __( 'FAQ Questions et Réponses', 'trendylux' ),
        'labels'                => $labels,
        'supports'              => array( 'title', 'editor', 'page-attributes' ), // title = Question, editor = Answer
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 20,
        'menu_icon'             => 'dashicons-format-chat',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => false, // Pas besoin d\'archive standard, on utilise un template de page custom
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'post',
        'show_in_rest'          => false, // Désactive Gutenberg (retour à l'éditeur classique)
    );
    register_post_type( 'faq', $args );
}
add_action( 'init', 'trendylux_register_faq_cpt', 0 );
