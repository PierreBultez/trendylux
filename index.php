<?php get_header(); ?>

    <main class="container mx-auto py-12 px-4">
        <?php if ( have_posts() ) : ?>
            <?php while ( have_posts() ) : the_post(); ?>
                <h1 class="text-4xl font-serif font-bold text-primary mb-4"><?php the_title(); ?></h1>
                <div class="prose lg:prose-xl max-w-none">
                    <?php the_content(); ?>
                </div>
            <?php endwhile; ?>
        <?php endif; ?>
    </main>

<?php get_footer(); ?>