<?php
get_header();
?>

    <!-- Bannière catégorie (Optionnelle, style simple fond gris) -->
    <div class="bg-gray-100 py-8 mb-8">
        <div class="container mx-auto px-4 text-center">
            <?php woocommerce_breadcrumb(); ?>
            <?php if ( apply_filters( 'woocommerce_show_page_title', true ) ) : ?>
                <h1 class="text-2xl md:text-3xl font-black uppercase tracking-widest text-neutral mb-2"><?php woocommerce_page_title(); ?></h1>
            <?php endif; ?>

            <div class="text-sm text-gray-500 max-w-2xl mx-auto">
                <?php do_action( 'woocommerce_archive_description' ); ?>
            </div>
        </div>
    </div>

    <?php
    $current_category = get_queried_object();
    $sub_categories = get_terms([
        'taxonomy' => 'product_cat',
        'parent' => $current_category->term_id,
        'hide_empty' => false,
    ]);

    if ($sub_categories) {
        echo '<div class="container mx-auto px-4 mb-8">';
        echo '<div class="flex flex-wrap justify-center gap-4">';
        foreach ($sub_categories as $sub_category) {
            echo '<a href="' . get_term_link($sub_category) . '" class="btn btn-outline">' . $sub_category->name . '</a>';
        }
        echo '</div>';
        echo '</div>';
    }
    ?>


    <main class="container mx-auto px-2 md:px-4 pb-20">

        <!-- Barre d'outils (Filtres & Tri) -->
        <div class="flex flex-wrap justify-between items-center mb-6 pb-4 border-b border-gray-200">

            <!-- Côté gauche : Filtres -->
            <div>
                <?php
                $attribute_taxonomies = wc_get_attribute_taxonomies();
                $attributes = [];
                foreach ($attribute_taxonomies as $taxonomy) {
                    $attributes[] = 'pa_' . $taxonomy->attribute_name;
                }

                if ($attributes) {
                    $current_object = get_queried_object();
                    $current_term_id = 0;
                    $current_taxonomy = '';
                    $filter_object_ids = null;
    
                    if ( isset($current_object->term_id) && isset($current_object->taxonomy) ) {
                        $current_term_id = $current_object->term_id;
                        $current_taxonomy = $current_object->taxonomy;
                        
                        // Get all product IDs in this taxonomy term to filter available attributes
                         $product_ids_args = [
                            'post_type' => 'product',
                            'posts_per_page' => -1,
                            'fields' => 'ids',
                            'tax_query' => [
                                [
                                    'taxonomy' => $current_taxonomy,
                                    'field'    => 'term_id',
                                    'terms'    => $current_term_id,
                                    'include_children' => true, 
                                ]
                            ]
                        ];
                        $filter_object_ids = get_posts($product_ids_args);
                    }

                    echo '<form id="product-filters" class="flex flex-wrap gap-4 items-center">';

                    if ($current_term_id && $current_taxonomy) {
                        echo '<input type="hidden" name="current_term_id" value="' . esc_attr($current_term_id) . '">';
                        echo '<input type="hidden" name="current_taxonomy" value="' . esc_attr($current_taxonomy) . '">';
                    }

                    foreach ($attributes as $attribute) {
                        $term_args = ['taxonomy' => $attribute];
                        
                        // Only add object_ids if we are in a category context.
                        if (is_array($filter_object_ids)) {
                            if (empty($filter_object_ids)) {
                                $terms = []; 
                            } else {
                                $term_args['object_ids'] = $filter_object_ids;
                                $terms = get_terms($term_args);
                            }
                        } else {
                             $terms = get_terms($term_args);
                        }

                        if ($terms && !is_wp_error($terms)) {
                            echo '<div class="dropdown z-20">';
                            echo '<div tabindex="0" role="button" class="btn m-1">' . wc_attribute_label($attribute) . '</div>';
                            echo '<ul tabindex="0" class="dropdown-content z-[1] menu p-2 shadow bg-base-100 rounded-box w-52">';
                            foreach ($terms as $term) {
                                echo '<li><label class="label cursor-pointer"><span class="label-text">' . $term->name . '</span><input type="checkbox" name="' . $attribute . '[]" value="' . $term->slug . '" class="checkbox checkbox-primary" /></label></li>';
                            }
                            echo '</ul>';
                            echo '</div>';
                        }
                    }
                    echo '<button type="button" id="reset-filters" class="btn btn-dash btn-info hidden">Réinitialiser</button>';
                    echo '</form>';
                }
                ?>
            </div>

            <!-- Côté droit : Tri et Compteur -->
            <div class="flex items-center gap-5">
                <div class="woocommerce-result-count text-sm font-bold text-gray-500">
                    <?php woocommerce_result_count(); ?>
                </div>
                <div class="flex gap-4">
                    <?php woocommerce_catalog_ordering(); ?>
                </div>
            </div>
        </div>

        <div id="product-archive-container">
            <div class="products">
            <?php
            if ( woocommerce_product_loop() ) {

                // GRILLE PRODUITS : 2 colonnes mobile, 4 colonnes desktop, gap réduit
                echo '<ul class="grid grid-cols-2 md:grid-cols-4 gap-x-2 gap-y-8 md:gap-x-6 md:gap-y-10">';

                if ( wc_get_loop_prop( 'total' ) ) {
                    while ( have_posts() ) {
                        the_post();
                        do_action( 'woocommerce_shop_loop' );
                        wc_get_template_part( 'content', 'product' );
                    }
                }

                echo '</ul>';

                // Pagination
                echo '<div class="mt-12 flex justify-center">';
                do_action( 'woocommerce_after_shop_loop' );
                echo '</div>';

            } else {
                do_action( 'woocommerce_no_products_found' );
            }
            ?>
            </div>
        </div>
    </main>

<?php
get_footer();