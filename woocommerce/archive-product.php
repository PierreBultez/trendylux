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


    <main class="container mx-auto px-4 pb-20">
        
        <!-- Layout Grid -->
        <div class="flex flex-col lg:flex-row lg:gap-8">

            <!-- SIDEBAR FILTERS -->
            <aside class="w-full lg:w-1/4 flex-shrink-0 mb-8 lg:mb-0">
                
                <!-- Mobile Filter Toggle -->
                <div class="lg:hidden mb-4">
                    <button type="button" class="btn btn-outline w-full flex items-center gap-2" onclick="document.getElementById('sidebar-filters').classList.toggle('hidden')">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" /></svg>
                        Filtrer les produits
                    </button>
                </div>

                <div id="sidebar-filters" class="hidden lg:block bg-base-100 rounded-box p-4 border border-base-200">
                    <form id="product-filters">
                        
                        <!-- Header & Reset -->
                        <div class="flex justify-between items-center border-b border-base-200 pb-4 mb-6 mt-2">
                            <h2 class="font-bold text-lg uppercase tracking-wider">Filtres</h2>
                            <button type="button" id="reset-filters" class="text-xs text-error font-bold uppercase tracking-wide hover:underline hidden">Effacer tout</button>
                        </div>

                        <?php
                        $current_object = get_queried_object();
                        $current_term_id = 0;
                        $current_taxonomy = '';
                        $filter_object_ids = null;

                        if ( isset($current_object->term_id) && isset($current_object->taxonomy) ) {
                            $current_term_id = $current_object->term_id;
                            $current_taxonomy = $current_object->taxonomy;
                            echo '<input type="hidden" name="current_term_id" value="' . esc_attr($current_term_id) . '">';
                            echo '<input type="hidden" name="current_taxonomy" value="' . esc_attr($current_taxonomy) . '">';
                            
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

                        $attribute_taxonomies = wc_get_attribute_taxonomies();
                        $attributes = [];
                        foreach ($attribute_taxonomies as $taxonomy) {
                            $attributes[] = 'pa_' . $taxonomy->attribute_name;
                        }

                        if ($attributes) :
                            $total_attributes = count($attributes);
                            $current_index = 0;

                            foreach ($attributes as $attribute) {
                                $current_index++;
                                $term_args = ['taxonomy' => $attribute, 'hide_empty' => true];
                                
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
                                    <div class="filter-group" x-data="{ expanded: false }">
                                        <h3 class="font-bold mb-4 text-sm uppercase tracking-wide text-neutral"><?php echo wc_attribute_label($attribute); ?></h3>
                                        <ul class="space-y-3">
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
                        endif;
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
                    if ( woocommerce_product_loop() ) {

                        // GRILLE PRODUITS : 1 colonnes mobile, 3 colonnes desktop, gap confortable
                        echo '<ul class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-3 gap-6">';

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
            </div>
            
        </div> <!-- End Grid -->
    </main>

<?php
get_footer();