<?php
get_header();
?>

    <main class="container mx-auto py-12 px-4">
        <header class="woocommerce-products-header mb-8">
            <?php if ( apply_filters( 'woocommerce_show_page_title', true ) ) : ?>
                <h1 class="text-4xl font-serif font-bold text-primary woocommerce-products-header__title page-title"><?php woocommerce_page_title(); ?></h1>
            <?php endif; ?>

            <?php do_action( 'woocommerce_archive_description' ); ?>
        </header>

        <?php
        if ( woocommerce_product_loop() ) {
            do_action( 'woocommerce_before_shop_loop' ); // Affiche le tri et le nombre de résultats

            woocommerce_product_loop_start(); // Ouvre la balise <ul> par défaut

            if ( wc_get_loop_prop( 'total' ) ) {
                while ( have_posts() ) {
                    the_post();
                    do_action( 'woocommerce_shop_loop' );
                    wc_get_template_part( 'content', 'product' ); // Appelle notre fichier content-product.php
                }
            }

            woocommerce_product_loop_end(); // Ferme la balise </ul>

            do_action( 'woocommerce_after_shop_loop' ); // Affiche la pagination
        } else {
            do_action( 'woocommerce_no_products_found' );
        }
        ?>
    </main>

<?php
get_footer();
