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

    <main class="container mx-auto px-4 pb-20">

        <!-- Layout Grid -->
        <div class="flex flex-col lg:flex-row lg:gap-8">

            <!-- SIDEBAR FILTERS -->
            <aside class="w-full lg:w-1/4 flex-shrink-0 mb-8 lg:mb-0">
                 <!-- Mobile Filter Toggle -->
                <div class="lg:hidden mb-4">
                    <button type="button" class="btn btn-outline w-full flex items-center gap-2" onclick="document.getElementById('sidebar-filters').classList.toggle('hidden')">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" /></svg>
                        Filtrer les résultats
                    </button>
                </div>

                <div id="sidebar-filters" class="hidden lg:block bg-base-100 rounded-box p-4 border border-base-200">
                    <form id="product-filters" class="space-y-6">
                         <!-- Header & Reset -->
                         <div class="flex justify-between items-center border-b border-base-200 pb-4 mt-2">
                            <h2 class="font-bold text-lg uppercase tracking-wider">Filtres</h2>
                            <button type="button" id="reset-filters" class="text-xs text-error font-bold uppercase tracking-wide hover:underline hidden">Effacer tout</button>
                        </div>

                        <?php
                        // Get all product IDs for the current search to filter terms
                        $product_ids_args = [
                            'post_type' => 'product',
                            'posts_per_page' => -1,
                            'fields' => 'ids',
                            's' => get_search_query(),
                        ];
                        $filter_object_ids = get_posts($product_ids_args);

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
                            ?>
                            <div class="filter-group mb-6" x-data="{ expanded: false }">
                                <h3 class="font-bold mb-3 text-sm uppercase tracking-wide text-neutral"><?php echo esc_html__('Catégories', 'trendylux'); ?></h3>
                                <ul class="space-y-2">
                                    <?php 
                                    $count = 0;
                                    foreach ($matching_categories as $cat): 
                                        $count++;
                                        $isHidden = $count > 5;
                                    ?>
                                        <li <?php echo $isHidden ? 'x-show="expanded" x-cloak' : ''; ?> class="mb-1 transition-all duration-300">
                                            <label class="cursor-pointer flex items-center gap-3 group">
                                                <input type="checkbox" name="product_cat[]" value="<?php echo $cat->slug; ?>" class="checkbox checkbox-sm checkbox-primary rounded-sm" />
                                                <span class="text-sm text-gray-600 group-hover:text-primary transition-colors"><?php echo $cat->name; ?></span>
                                            </label>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                                <?php if ($count > 5): ?>
                                    <button type="button" @click="expanded = !expanded" class="text-xs font-bold text-primary mt-3 hover:underline focus:outline-none flex items-center gap-1">
                                        <span x-show="!expanded">Voir plus (+<?php echo $count - 5; ?>)</span>
                                        <span x-show="expanded" x-cloak>Voir moins</span>
                                    </button>
                                <?php endif; ?>
                            </div>
                            <div class="divider my-2 last:hidden"></div>
                            <?php
                        }

                        // 2. Attribute Filters
                        $attribute_taxonomies = wc_get_attribute_taxonomies();
                        $attributes = [];
                        foreach ($attribute_taxonomies as $taxonomy) {
                            $attributes[] = 'pa_' . $taxonomy->attribute_name;
                        }

                        if ($attributes) {
                            foreach ($attributes as $attribute) {
                                $term_args = ['taxonomy' => $attribute];
                                
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
                                    ?>
                                    <div class="filter-group mb-6" x-data="{ expanded: false }">
                                        <h3 class="font-bold mb-3 text-sm uppercase tracking-wide text-neutral"><?php echo wc_attribute_label($attribute); ?></h3>
                                        <ul class="space-y-2">
                                            <?php 
                                            $count = 0;
                                            foreach ($terms as $term): 
                                                $count++;
                                                $isHidden = $count > 5;
                                            ?>
                                                <li <?php echo $isHidden ? 'x-show="expanded" x-cloak' : ''; ?> class="mb-1 transition-all duration-300">
                                                    <label class="cursor-pointer flex items-center gap-3 group">
                                                        <input type="checkbox" name="<?php echo $attribute; ?>[]" value="<?php echo $term->slug; ?>" class="checkbox checkbox-sm checkbox-primary rounded-sm" />
                                                        <span class="text-sm text-gray-600 group-hover:text-primary transition-colors"><?php echo $term->name; ?></span>
                                                    </label>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                        <?php if ($count > 5): ?>
                                            <button type="button" @click="expanded = !expanded" class="text-xs font-bold text-primary mt-3 hover:underline focus:outline-none flex items-center gap-1">
                                                <span x-show="!expanded">Voir plus (+<?php echo $count - 5; ?>)</span>
                                                <span x-show="expanded" x-cloak>Voir moins</span>
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                    <div class="divider my-2 last:hidden"></div>
                                    <?php
                                }
                            }
                        }
                        ?>
                    </form>
                </div>
            </aside>

            <!-- MAIN CONTENT -->
            <div class="w-full lg:w-3/4">

                <!-- Toolbar (Sort + Count) -->
                <div class="flex flex-wrap justify-between items-center mb-6 pb-4 border-b border-gray-200">
                    <div class="woocommerce-result-count text-sm font-bold text-gray-500">
                        <?php woocommerce_result_count(); ?>
                    </div>
                    <div class="flex gap-4">
                        <?php woocommerce_catalog_ordering(); ?>
                    </div>
                </div>

                <div id="product-archive-container">
                    <div class="products">
                    <?php
                    if ( have_posts() ) {

                        // GRILLE PRODUITS : 1 colonnes mobile, 3 colonnes en mode grille sidebar (ou 4 si écran très large)
                        echo '<ul class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-3 gap-6">';

                        while ( have_posts() ) {
                            the_post();
                            do_action( 'woocommerce_shop_loop' );
                            wc_get_template_part( 'content', 'product' );
                        }

                        echo '</ul>';

                        // Pagination (adapted from previous search.php)
                        global $wp_query;
                        $total   = $wp_query->max_num_pages;
                        $current = max( 1, get_query_var( 'paged' ) );

                        if ( $total > 1 ) {
                            echo '<div class="mt-12 flex justify-center">';
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
                            echo '</div>';
                        }

                    } else {
                        do_action( 'woocommerce_no_products_found' );
                    }
                    ?>
                    </div>
                </div>
            </div>

        </div>
    </main>

<?php
get_footer();