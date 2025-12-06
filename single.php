<?php get_header(); ?>

<div class="bg-base-200 min-h-screen py-8 md:py-12">
    <div class="container mx-auto px-4">
        
        <!-- Breadcrumbs (facultatif mais recommandé) -->
        <div class="text-sm breadcrumbs mb-6 text-neutral-content/70">
            <ul>
                <li><a href="<?php echo home_url(); ?>">Accueil</a></li>
                <li><a href="<?php echo get_post_type_archive_link( 'post' ); ?>"><?php echo get_the_title( get_option('page_for_posts', true) ) ?: "L'actu Trendy"; ?></a></li>
                <li><span class="text-primary"><?php the_title(); ?></span></li>
            </ul>
        </div>

        <?php while ( have_posts() ) : the_post(); ?>
            
            <article id="post-<?php the_ID(); ?>" <?php post_class('bg-base-100 rounded-box shadow-xl overflow-hidden'); ?>>
                
                <!-- Header de l'article -->
                <?php if ( has_post_thumbnail() ) : ?>
                    <div class="w-full h-64 md:h-96 relative">
                        <?php the_post_thumbnail('full', ['class' => 'w-full h-full object-cover']); ?>
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                        <div class="absolute bottom-0 left-0 p-6 md:p-10 text-white">
                             <div class="flex items-center gap-4 text-sm md:text-base mb-2 opacity-90">
                                <span><?php echo get_the_date(); ?></span>
                                <span>&bull;</span>
                                <span class="uppercase tracking-widest"><?php the_author(); ?></span>
                            </div>
                            <h1 class="text-3xl md:text-5xl font-serif font-bold leading-tight drop-shadow-lg">
                                <?php the_title(); ?>
                            </h1>
                        </div>
                    </div>
                <?php else : ?>
                    <div class="p-6 md:p-10 pb-0">
                         <div class="flex items-center gap-4 text-sm md:text-base mb-2 text-gray-500">
                            <span><?php echo get_the_date(); ?></span>
                            <span>&bull;</span>
                            <span><?php the_author(); ?></span>
                        </div>
                        <h1 class="text-3xl md:text-5xl font-serif font-bold text-primary leading-tight">
                            <?php the_title(); ?>
                        </h1>
                        <div class="divider my-6"></div>
                    </div>
                <?php endif; ?>

                <!-- Contenu -->
                <div class="p-6 md:p-12">
                    <div class="prose prose-lg max-w-none prose-headings:font-serif prose-headings:text-primary prose-a:text-primary hover:prose-a:text-primary-focus prose-img:rounded-xl">
                        <?php the_content(); ?>
                    </div>

                    <!-- Tags -->
                    <?php if(has_tag()): ?>
                        <div class="mt-8 pt-8 border-t border-base-200">
                            <div class="flex flex-wrap gap-2">
                                <?php the_tags('<span class="badge badge-outline">', '</span> <span class="badge badge-outline">', '</span>'); ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Navigation Précédent / Suivant -->
                <div class="bg-base-200 p-6 flex justify-between items-center border-t border-base-300">
                    <div class="text-left">
                        <?php previous_post_link( '%link', '<span class="btn btn-ghost btn-sm gap-2">← Précédent</span>' ); ?>
                    </div>
                    <div class="text-right">
                        <?php next_post_link( '%link', '<span class="btn btn-ghost btn-sm gap-2">Suivant →</span>' ); ?>
                    </div>
                </div>

            </article>

            <!-- Section Commentaires (si activés) -->
            <?php if ( comments_open() || get_comments_number() ) : ?>
                <div class="mt-12 bg-base-100 rounded-box shadow-xl p-6 md:p-10">
                    <?php comments_template(); ?>
                </div>
            <?php endif; ?>

        <?php endwhile; ?>
    </div>
</div>

<?php get_footer(); ?>