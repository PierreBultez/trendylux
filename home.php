<?php get_header(); ?>

<div class="bg-base-200 py-12 min-h-screen">
    <div class="container mx-auto px-4">
        
        <header class="text-center mb-12">
            <h1 class="text-4xl md:text-5xl font-serif font-bold text-primary mb-4">
                <?php 
                // Si une page est assignée comme page des articles, on utilise son titre, sinon "L'actu Trendy" par défaut
                $page_for_posts_id = get_option('page_for_posts');
                echo $page_for_posts_id ? get_the_title($page_for_posts_id) : "L'actu Trendy"; 
                ?>
            </h1>
            <p class="text-neutral-content/70 max-w-2xl mx-auto">
                Découvrez les dernières tendances, nos conseils mode et l'actualité de la boutique.
            </p>
        </header>

        <?php if ( have_posts() ) : ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php while ( have_posts() ) : the_post(); ?>
                    
                    <article id="post-<?php the_ID(); ?>" <?php post_class('card card-compact bg-base-100 shadow-xl hover:shadow-2xl transition-all duration-300 h-full border border-base-300'); ?>>
                        
                        <?php if ( has_post_thumbnail() ) : ?>
                            <figure class="relative h-56 overflow-hidden">
                                <a href="<?php the_permalink(); ?>" class="w-full h-full block group">
                                    <?php the_post_thumbnail('medium_large', [
                                        'class' => 'w-full h-full object-cover transition-transform duration-500 group-hover:scale-110'
                                    ]); ?>
                                </a>
                            </figure>
                        <?php endif; ?>
                        
                        <div class="card-body">
                            <div class="text-xs text-gray-500 mb-2 uppercase tracking-wide">
                                <?php echo get_the_date(); ?> 
                                <?php 
                                $categories = get_the_category();
                                if ( ! empty( $categories ) ) {
                                    echo ' &bull; ' . esc_html( $categories[0]->name );
                                }
                                ?>
                            </div>
                            
                            <h2 class="card-title text-primary font-serif text-2xl mb-2">
                                <a href="<?php the_permalink(); ?>" class="hover:underline">
                                    <?php the_title(); ?>
                                </a>
                            </h2>
                            
                            <div class="text-base-content/80 mb-4 line-clamp-3 flex-grow">
                                <?php the_excerpt(); ?>
                            </div>
                            
                            <div class="card-actions justify-end mt-auto">
                                <a href="<?php the_permalink(); ?>" class="btn btn-primary btn-outline btn-sm group">
                                    Lire l'article
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transition-transform group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </article>

                <?php endwhile; ?>
            </div>

            <div class="mt-12 flex justify-center">
                <?php
                the_posts_pagination( array(
                    'mid_size'  => 2,
                    'prev_text' => __( 'Précédent', 'trendylux' ),
                    'next_text' => __( 'Suivant', 'trendylux' ),
                    'class'     => 'join' // On essaie d'appliquer une classe parente, mais WP génère sa propre structure.
                ) );
                ?>
                <!-- Note: Le style de pagination WP devra peut-être être ajusté en CSS global ou via un filtre pour coller parfaitement aux classes DaisyUI (join-item btn) -->
            </div>

        <?php else : ?>
            <div class="alert alert-info">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="stroke-current shrink-0 w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span>Aucun article trouvé pour le moment.</span>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php get_footer(); ?>