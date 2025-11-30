<?php
if ( ! defined( 'ABSPATH' ) ) exit;

get_header(); ?>

    <div class="container mx-auto pt-4 pb-12 px-4 md:px-8">

        <div class="text-xs text-gray-500 mb-6 uppercase tracking-wide">
            <?php woocommerce_breadcrumb(); ?>
        </div>

        <?php while ( have_posts() ) : ?>
            <?php the_post(); ?>

            <div id="product-<?php the_ID(); ?>" <?php wc_product_class( '', $product ); ?>>

                <div class="grid grid-cols-1 md:grid-cols-12 gap-8 lg:gap-16">

                    <!-- MODIFIÉ : Ajout de rounded-box et overflow-hidden -->
                    <div class="md:col-span-7 product-gallery-wrapper">
                        <?php
                        do_action( 'woocommerce_before_single_product_summary' );
                        ?>
                    </div>

                    <div class="md:col-span-5 relative">
                        <div class="sticky top-24">

                            <div class="mb-6 border-b border-gray-100 pb-6">
                                <h1 class="text-2xl md:text-3xl font-black uppercase tracking-wide text-neutral mb-2"><?php the_title(); ?></h1>

                                <div class="flex items-baseline gap-4">
                                    <p class="text-xl md:text-2xl font-bold text-primary">
                                        <?php echo $product->get_price_html(); ?>
                                    </p>
                                </div>

                                <!-- AJOUT : Affichage des étoiles d'avis -->
                                <div class="mt-2">
                                    <?php trendylux_display_star_rating(); ?>
                                </div>

                                <div class="mt-4 text-sm text-gray-600 prose max-w-none">
                                    <?php the_excerpt(); ?>
                                </div>
                            </div>

                            <!-- Ce bloc avait déjà 'rounded-box', c'est parfait ! -->
                            <div class="bg-gray-100 p-6 rounded-box mb-6">
                                <?php
                                woocommerce_template_single_add_to_cart();
                                ?>
                            </div>

                            <div class="flex flex-wrap justify-between gap-4 text-xs text-gray-500 border-t border-gray-200 pt-4">
                                <div class="flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    <span>Livraison 3 à 5 jours</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                                    <span>Retours Gratuits</span>
                                </div>
                                <div class="flex items-center gap-2 trendylux-wishlist-btn">
                                    <?php echo do_shortcode('[ti_wishlists_addtowishlist]'); ?>
                                </div>
                            </div>

                            <!-- SUPPRIMÉ : L'action qui affichait les onglets ici -->

                        </div>
                    </div>
                </div>

                <!-- AJOUT : Nouvelle section pour la description, les informations et les avis -->
                <div class="mt-16 space-y-12">
                    <?php
                    global $product;
                    $has_attributes = $product && ( $product->has_attributes() || apply_filters( 'wc_product_enable_dimensions_display', $product->has_weight() || $product->has_dimensions() ) );
                    $has_reviews = $product && $product->get_review_count() > 0;
                    ?>

                    <!-- Grille pour les Avis et les Informations complémentaires -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 lg:gap-16">
                        <div id="reviews" class="prose max-w-none">
                            <h2 class="text-2xl font-bold mb-4 uppercase tracking-wide">Avis</h2>
                            <?php comments_template(); ?>
                        </div>

                        <?php if ( $has_attributes ) : ?>
                            <div class="prose max-w-none">
                                <h2 class="text-2xl font-bold mb-4 uppercase tracking-wide">Informations complémentaires</h2>
                                <?php wc_get_template( 'single-product/tabs/additional-information.php' ); ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Description pleine largeur -->
                    <?php
                    global $post;
                    if ( $post->post_content ) :
                    ?>
                        <div class="prose max-w-none">
                            <h2 class="text-2xl font-bold mb-4 uppercase tracking-wide">Description</h2>
                            <?php wc_get_template( 'single-product/tabs/description.php' ); ?>
                        </div>
                    <?php endif; ?>
                </div>

            </div>

        <?php endwhile; ?>

        <?php
        /**
         * LES PRODUITS SIMILAIRES VONT MAINTENANT S'AFFICHER ICI, EN PLEINE LARGEUR.
         */
        do_action( 'woocommerce_after_main_content' );
        ?>

    </div>

<?php
get_footer();