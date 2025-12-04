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
    $current_object = get_queried_object();
    $display_categories = [];

    if ( is_product_category() ) {
        // Standard behavior for Category pages: Show children
        $display_categories = get_terms([
            'taxonomy' => 'product_cat',
            'parent' => $current_object->term_id,
            'hide_empty' => true,
        ]);
    } elseif ( is_tax(['product_brand', 'pa_marque']) ) {
        // Behavior for Brand pages: Show categories containing products from this brand
        // 1. Get IDs of products in this brand
        $product_ids = get_posts([
            'post_type' => 'product',
            'posts_per_page' => -1, // Limit to avoid memory issues on huge sites if necessary
            'fields' => 'ids',
            'tax_query' => [[
                'taxonomy' => $current_object->taxonomy,
                'field' => 'term_id',
                'terms' => $current_object->term_id,
            ]]
        ]);

        if ( !empty($product_ids) ) {
            // 2. Get categories associated with these products (Top Level only first)
            $display_categories = get_terms([
                'taxonomy' => 'product_cat',
                'object_ids' => $product_ids,
                'parent' => 0,
                'hide_empty' => true,
            ]);
            
            // Fallback: If no top-level categories found, try all levels
            if ( empty($display_categories) || is_wp_error($display_categories) ) {
                 $display_categories = get_terms([
                    'taxonomy' => 'product_cat',
                    'object_ids' => $product_ids,
                    'hide_empty' => true,
                ]);
            }
        }
    }

    if ( !empty($display_categories) && !is_wp_error($display_categories) ) {
        echo '<div class="container mx-auto px-4 mb-8">';
        
        // Desktop: Flex Wrap List (hidden on mobile)
        echo '<div class="hidden md:flex flex-wrap justify-center gap-4">';
        foreach ($display_categories as $category) {
            // Link Construction:
            // If we are on a Brand page (product_brand tax), we want the link to go to the Category page
            // BUT keep the Brand as a filter.
            $link = get_term_link($category);
            
            if ( is_tax('product_brand') ) {
                 $current_brand = get_queried_object();
                 // Append ?product_brand=slug
                 $link = add_query_arg( 'product_brand', $current_brand->slug, $link );
            } elseif ( is_tax('pa_marque') ) {
                 $current_brand = get_queried_object();
                 // Append ?pa_marque=slug
                 $link = add_query_arg( 'pa_marque', $current_brand->slug, $link );
            }

            echo '<a href="' . esc_url($link) . '" class="btn btn-outline">' . $category->name . '</a>';
        }
        echo '</div>';

        // Mobile: DaisyUI Dropdown (visible on mobile only)
        echo '<div class="md:hidden w-full">';
        echo '<div class="dropdown w-full">';
        echo '<div tabindex="0" role="button" class="btn btn-outline w-full justify-between mb-4">' . (is_product_category() ? 'Sous-catégories' : 'Catégories') . ' <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg></div>';
        echo '<ul tabindex="0" class="dropdown-content z-[9999] menu p-2 shadow bg-base-100 rounded-box w-full border border-base-200">';
        foreach ($display_categories as $category) {
             // Same logic for mobile
             $link = get_term_link($category);
             if ( is_tax('product_brand') ) {
                 $current_brand = get_queried_object();
                 $link = add_query_arg( 'product_brand', $current_brand->slug, $link );
             } elseif ( is_tax('pa_marque') ) {
                 $current_brand = get_queried_object();
                 $link = add_query_arg( 'pa_marque', $current_brand->slug, $link );
             }

             echo '<li><a href="' . esc_url($link) . '">' . $category->name . '</a></li>';
        }
        echo '</ul>';
        echo '</div>';
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

                        // PRESERVE URL PARAMETERS IN FILTER FORM (Hidden Inputs)
                        // This ensures that filters like ?product_brand=lacoste are passed to the AJAX handler
                        $preserved_params = ['product_brand', 'pa_marque'];
                        foreach ($preserved_params as $param) {
                            if ( isset($_GET[$param]) && !empty($_GET[$param]) ) {
                                echo '<input type="hidden" name="' . esc_attr($param) . '" value="' . esc_attr(sanitize_text_field($_GET[$param])) . '">';
                            }
                        }

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
                        
                        // Manually add product_brand taxonomy if it exists
                        if ( taxonomy_exists( 'product_brand' ) ) {
                            $attributes[] = 'product_brand';
                        }

                        foreach ($attribute_taxonomies as $taxonomy) {
                            $attributes[] = 'pa_' . $taxonomy->attribute_name;
                        }

                        // Custom Sort Order
                        $custom_order = ['product_brand', 'pa_marque', 'pa_genre', 'pa_taille', 'pa_couleur', 'pa_saison'];
                        
                        usort($attributes, function($a, $b) use ($custom_order) {
                            $pos_a = array_search($a, $custom_order);
                            $pos_b = array_search($b, $custom_order);

                            // If both are in the custom order list
                            if ($pos_a !== false && $pos_b !== false) {
                                return $pos_a - $pos_b;
                            }
                            
                            // If only $a is in the list, it comes first
                            if ($pos_a !== false) return -1;
                            
                            // If only $b is in the list, it comes first
                            if ($pos_b !== false) return 1;

                            // Otherwise, sort alphabetically
                            return strcmp($a, $b);
                        });

                        if ($attributes) :
                            $total_attributes = count($attributes);
                            $current_index = 0;

                            foreach ($attributes as $attribute) {
                                $current_index++;
                                
                                // HIDE LOGIC:
                                // 1. Hide if it is the current taxonomy
                                if ( $attribute === $current_taxonomy ) {
                                    continue;
                                }

                                // 2. Hide Brand filters if we are already in a Brand context
                                if ( $attribute === 'pa_marque' || $attribute === 'product_brand' ) {
                                    if ( isset($_GET['pa_marque']) || isset($_GET['product_brand']) ) {
                                        continue;
                                    }
                                }

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
                                    // Determine Label
                                    $label = wc_attribute_label($attribute);
                                    if ( $attribute === 'product_brand' ) {
                                        $tax_obj = get_taxonomy( 'product_brand' );
                                        $label = $tax_obj ? $tax_obj->label : 'Marques';
                                    }
                                    ?>
                                    <div class="filter-group" x-data="{ expanded: false }">
                                        <h3 class="font-bold mb-4 text-sm uppercase tracking-wide text-neutral"><?php echo esc_html($label); ?></h3>
                                        <ul class="space-y-3">
                                            <?php 
                                            $count = 0;
                                            foreach ($terms as $term): 
                                                $count++;
                                                $isHidden = $count > 5;
                                                
                                                // CHECK IF SELECTED IN URL
                                                $isChecked = false;
                                                if ( isset($_GET[$attribute]) ) {
                                                    $url_values = is_array($_GET[$attribute]) ? $_GET[$attribute] : explode(',', $_GET[$attribute]);
                                                    if ( in_array($term->slug, $url_values) ) {
                                                        $isChecked = true;
                                                    }
                                                }
                                            ?>
                                                <li <?php echo $isHidden ? 'x-show="expanded" x-cloak' : ''; ?> class="mb-1 transition-all duration-300">
                                                    <label class="cursor-pointer flex items-center gap-3 group">
                                                        <input type="checkbox" name="<?php echo $attribute; ?>[]" value="<?php echo $term->slug; ?>" class="checkbox checkbox-sm checkbox-primary rounded-sm" <?php checked($isChecked); ?> />
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