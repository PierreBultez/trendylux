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

<main class="container mx-auto px-4 pb-20">
    <h1 class="text-3xl font-black uppercase tracking-widest text-neutral mb-8 mt-5">
        <?php printf( esc_html__( 'Résultats de recherche pour "%s"', 'trendylux' ), '<span>' . get_search_query() . '</span>' ); ?>
    </h1>

    <?php if ( have_posts() ) : ?>

        <div class="flex flex-wrap justify-end gap-5 items-center mb-6 pb-4 border-b border-gray-200">
            <div class="woocommerce-result-count text-sm font-bold text-gray-500">
                <?php woocommerce_result_count(); ?>
            </div>
            <div class="flex gap-4">
                <?php woocommerce_catalog_ordering(); ?>
            </div>
        </div>

        <div class="products-grid">
            <ul class="grid grid-cols-2 md:grid-cols-4 gap-x-2 gap-y-8 md:gap-x-6 md:gap-y-10">
                <?php while ( have_posts() ) : the_post(); ?>
                    <?php wc_get_template_part( 'content', 'product' ); ?>
                <?php endwhile; ?>
            </ul>
        </div>

        <div class="mt-12 flex justify-center">
            <div class="join">
                <?php
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
                    }
                }
                ?>
            </div>
        </div>

    <?php else : ?>
        <p class="woocommerce-info"><?php esc_html_e( 'Aucun produit trouvé correspondant à votre recherche.', 'trendylux' ); ?></p>
    <?php endif; ?>

</main>

<?php
get_footer();
