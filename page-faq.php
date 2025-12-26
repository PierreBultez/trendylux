<?php
/**
 * Template Name: Page FAQ
 *
 * This template matches a page with slug 'faq' or can be selected manually.
 */

get_header(); ?>

<main class="container mx-auto py-12 px-4">
    
    <!-- Header de la page (Titre et Intro) -->
    <header class="mb-12 text-center">
        <h1 class="text-4xl md:text-5xl font-serif font-bold text-primary mb-6">
            <?php echo get_the_title() ? get_the_title() : 'Foire Aux Questions'; ?>
        </h1>
        <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
            <div class="prose max-w-3xl mx-auto text-neutral/70">
                <?php the_content(); ?>
            </div>
        <?php endwhile; endif; ?>
    </header>

    <!-- Section Accordéon FAQ -->
    <section class="max-w-4xl mx-auto">
        <div class="join join-vertical w-full bg-base-100 border border-base-300 rounded-box shadow-sm">

            <?php
            // 1. On tente de récupérer les questions dynamiques (CPT 'faq')
            $faq_args = array(
                'post_type'      => 'faq',
                'posts_per_page' => -1, // Tout afficher
                'orderby'        => 'menu_order title',
                'order'          => 'ASC',
            );
            $faq_query = new WP_Query( $faq_args );

            if ( $faq_query->have_posts() ) :
                // --- MODE DYNAMIQUE ---
                while ( $faq_query->have_posts() ) : $faq_query->the_post();
                    ?>
                    <div class="collapse collapse-arrow join-item border-b border-base-300 last:border-none">
                        <input type="radio" name="faq-accordion" /> 
                        <div class="collapse-title text-lg font-medium hover:text-primary transition-colors">
                            <?php the_title(); ?>
                        </div>
                        <div class="collapse-content text-base-content/80"> 
                            <div class="prose prose-sm max-w-none pt-2 pb-4">
                                <?php the_content(); ?>
                            </div>
                        </div>
                    </div>
                    <?php
                endwhile;
                wp_reset_postdata();

            else :
                // --- MODE DÉMO (Contenu statique si aucune question n'est créée) ---
                $demo_faqs = [
                    [
                        'q' => 'Quels sont les délais de livraison ?',
                        'a' => 'Nos délais de livraison standard sont de 3 à 5 jours ouvrés en France métropolitaine.'
                    ],
                    [
                        'q' => 'Quels sont les délais pour effectuer un retour ?',
                        'a' => 'Vous disposez de 14 jours pour nous retourner un article.'
                    ],
                    [
                        'q' => 'Les produits sont-ils authentiques ?',
                        'a' => 'Absolument. Trendy Lux garantit l\'authenticité de tous les articles vendus. Nous travaillons avec des distributeurs officiels pour vous offrir le meilleur de la mode.'
                    ],
                    [
                        'q' => 'Quels moyens de paiement acceptez-vous ?',
                        'a' => 'Nous acceptons les cartes bancaires (Visa, Mastercard, CB), PayPal ainsi que le paiement en 4x sans frais via Paypal.'
                    ],
                    [
                        'q' => 'Comment contacter le service client ?',
                        'a' => 'Notre équipe est disponible du lundi au vendredi de 9h à 18h. Vous pouvez nous joindre via le formulaire de contact, par email à contact@trendylux.fr ou par téléphone au 06 52 19 62 15.'
                    ]
                ];

                foreach ( $demo_faqs as $index => $item ) :
                    ?>
                    <div class="collapse collapse-arrow join-item border-b border-base-300 last:border-none">
                        <input type="radio" name="faq-accordion" <?php echo $index === 0 ? 'checked="checked"' : ''; ?> /> 
                        <div class="collapse-title text-lg font-medium hover:text-primary transition-colors">
                            <?php echo esc_html( $item['q'] ); ?>
                        </div>
                        <div class="collapse-content text-base-content/80"> 
                            <div class="prose prose-sm max-w-none pt-2 pb-4">
                                <p><?php echo esc_html( $item['a'] ); ?></p>
                            </div>
                        </div>
                    </div>
                    <?php
                endforeach;

            endif;
            ?>

        </div>
        
        <!-- Petit encart de contact en bas -->
        <div class="text-center mt-12">
            <p class="text-neutral/60 mb-4">Vous ne trouvez pas la réponse à votre question ?</p>
            <a href="<?php echo home_url('/contact/'); ?>" class="btn btn-primary btn-outline">Contactez-nous</a>
        </div>

    </section>
</main>

<?php get_footer(); ?>
