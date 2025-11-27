<?php
/**
 * The template for displaying search results pages.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package TrendyLux
 */

get_header();
?>

    <!-- Bannière catégorie (Style simple fond gris pour la recherche) -->
    <div class="bg-gray-100 py-8 mb-8">
        <div class="container mx-auto px-4 text-center">
            <?php woocommerce_breadcrumb(); ?>
            <h1 class="text-2xl md:text-3xl font-black uppercase tracking-widest text-neutral mb-2">
                <?php printf( esc_html__( 'Résultats pour "%s"', 'trendylux' ), '<span>' . get_search_query() . '</span>' ); ?>
            </h1>
            <div class="text-sm text-gray-500 max-w-2xl mx-auto">
                <?php echo esc_html__( 'Découvrez les produits correspondant à votre recherche.', 'trendylux' ); ?>
            </div>
        </div>
    </div>

    <main class="container mx-auto px-2 md:px-4 pb-20">

        <!-- Barre d'outils (Filtres & Tri) -->
        <div class="flex flex-wrap justify-between items-center mb-6 pb-4 border-b border-gray-200">

            <!-- Côté gauche : Filtres -->
            <div>
                <?php
                // Get all product IDs for the current search to filter terms
                $product_ids_args = [
                    'post_type' => 'product',
                    'posts_per_page' => -1,
                    'fields' => 'ids',
                    's' => get_search_query(), // Filtrer par la recherche actuelle
                ];
                $filter_object_ids = get_posts($product_ids_args);

                echo '<form id="product-filters" class="flex flex-wrap gap-4 items-center">';

                // 1. Categories Filter
                $cat_args = [
                    'taxonomy' => 'product_cat',
                    'hide_empty' => true,
                ];
                 if (is_array($filter_object_ids)) {
                    if (empty($filter_object_ids)) {
                         $matching_categories = [];
                    } else {
                        $cat_args['object_ids'] = $filter_object_ids;
                        $matching_categories = get_terms($cat_args);
                    }
                } else {
                    $matching_categories = get_terms($cat_args);
                }

                if (!empty($matching_categories) && !is_wp_error($matching_categories)) {
                    echo '<div class="dropdown z-20">';
                    echo '<div tabindex="0" role="button" class="btn m-1">' . esc_html__('Catégories', 'trendylux') . '</div>';
                    echo '<ul tabindex="0" class="dropdown-content z-[1] menu p-2 shadow bg-base-100 rounded-box w-52">';
                    foreach ($matching_categories as $cat) {
                        echo '<li><label class="label cursor-pointer"><span class="label-text">' . $cat->name . '</span><input type="checkbox" name="product_cat[]" value="' . $cat->slug . '" class="checkbox checkbox-primary" /></label></li>';
                    }
                    echo '</ul>';
                    echo '</div>';
                }

                // 2. Attribute Filters
                $attribute_taxonomies = wc_get_attribute_taxonomies();
                $attributes = [];
                foreach ($attribute_taxonomies as $taxonomy) {
                    $attributes[] = 'pa_' . $taxonomy->attribute_name;
                }

                if ($attributes) {
                    // Note: Pas de current_category_id ici car on est en recherche globale

                    foreach ($attributes as $attribute) {
                        $term_args = ['taxonomy' => $attribute];
                        
                        // Only add object_ids if we have search results
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
                }
                
                echo '<button type="button" id="reset-filters" class="btn btn-dash btn-info hidden">Réinitialiser</button>';
                echo '</form>';
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
            if ( have_posts() ) {

                // GRILLE PRODUITS : 2 colonnes mobile, 4 colonnes desktop, gap réduit
                echo '<ul class="grid grid-cols-2 md:grid-cols-4 gap-x-2 gap-y-8 md:gap-x-6 md:gap-y-10">';

                while ( have_posts() ) {
                    the_post();
                    do_action( 'woocommerce_shop_loop' );
                    wc_get_template_part( 'content', 'product' );
                }

                echo '</ul>';

                // Pagination
                echo '<div class="mt-12 flex justify-center">';
                
                // Use standard pagination logic or the loop/pagination.php if compatible. 
                // Since functions.php uses wc_get_template('loop/pagination.php'), we should try to match that or use paginate_links manually as before but wrapped to look similar.
                // Actually, let's try to use the standard WooCommerce pagination if possible, or the manual one from the previous search.php but styled.
                // The previous search.php had a manual 'join' styled pagination. Let's keep that for consistency with the previous search design, 
                // OR better, align with archive-product.php which calls do_action('woocommerce_after_shop_loop').
                
                // Let's stick to the manual one from the old search.php to ensure it works with the main loop, 
                // BUT adapted to the style of the AJAX return in functions.php.
                
                 global $wp_query;
                $total   = $wp_query->max_num_pages;
                $current = max( 1, get_query_var( 'paged' ) );

                if ( $total > 1 ) {
                    $pages = paginate_links( array(
                        'base'      => str_replace( 999999999, '%#%', esc_url( get_pagenum_link( 999999999 ) ) ),
                        'format'    => '?paged=%#%',
                        'current'   => $current,
                        'total'     => $total,
                        'prev_text' => '«',
                        'next_text' => '»',
                        'type'      => 'array',
                        'end_size'  => 3,
                        'mid_size'  => 3,
                    ) );

                    if ( is_array( $pages ) ) {
                        echo '<div class="join">';
                        foreach ( $pages as $page ) {
                            $page = str_replace( 'page-numbers', 'join-item btn', $page );
                            
                            if ( strpos( $page, 'current' ) !== false ) {
                                $page = str_replace( 'join-item btn', 'join-item btn btn-active btn-primary', $page );
                            }
                            
                            if ( strpos( $page, 'dots' ) !== false ) {
                                $page = str_replace( 'join-item btn', 'join-item btn btn-disabled', $page );
                            }

                            echo $page;
                        }
                        echo '</div>';
                    }
                }

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