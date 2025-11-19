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

        <?php wc_get_template('includes/product-filters.php'); ?>

        <!-- Barre d'outils (Filtres & Tri) -->
        <div class="flex flex-wrap justify-between items-center mb-6 pb-4 border-b border-gray-200">
            <div class="text-sm font-bold text-gray-500">
                <?php echo wc_get_loop_prop( 'total' ); ?> Articles
            </div>
            <div class="flex gap-4">
                <!-- Ici on laisse WooCommerce injecter le tri, on le stylisera via CSS/Theme.json -->
                <?php do_action( 'woocommerce_before_shop_loop' ); ?>
            </div>
        </div>

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
    </main>

<?php
get_footer();