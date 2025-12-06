<?php
/**
 * The template for displaying comments
 *
 * This is the template that displays the area of the page that contains both the current comments
 * and the comment form.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package TrendyLux
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if ( post_password_required() ) {
    return;
}
?>

<div id="comments" class="comments-area pt-8 border-t border-base-200">

    <?php
    // You can start editing here -- including this comment!
    if ( have_comments() ) :
        ?>
        <h2 class="comments-title text-2xl font-serif font-bold text-primary mb-8">
            <?php
            $trendylux_comment_count = get_comments_number();
            if ( '1' === $trendylux_comment_count ) {
                printf(
                    /* translators: 1: title. */
                    esc_html__( 'Une réflexion sur &ldquo;%1$s&rdquo;', 'trendylux' ),
                    '<span>' . get_the_title() . '</span>'
                );
            } else {
                printf( 
                    /* translators: 1: comment count number, 2: title. */
                    esc_html( _nx( '%1$s réflexion sur &ldquo;%2$s&rdquo;', '%1$s réflexions sur &ldquo;%2$s&rdquo;', $trendylux_comment_count, 'comments title', 'trendylux' ) ),
                    number_format_i18n( $trendylux_comment_count ),
                    '<span>' . get_the_title() . '</span>'
                );
            }
            ?>
        </h2>

        <?php the_comments_navigation(); ?>

        <ul class="comment-list space-y-6 list-none m-0 p-0">
            <?php
            wp_list_comments(
                array(
                    'style'      => 'ul',
                    'short_ping' => true,
                    'avatar_size'=> 64,
                    'callback'   => 'trendylux_comment_callback',
                )
            );
            ?>
        </ul>

        <?php
        the_comments_navigation();

        // If comments are closed and there are comments, let's leave a little note, shall we?
        if ( ! comments_open() ) :
            ?>
            <p class="no-comments alert alert-warning mt-8"><?php esc_html_e( 'Les commentaires sont fermés.', 'trendylux' ); ?></p>
            <?php
        endif;

    endif; // Check for have_comments().

    // Formulaire de commentaire
    // Le style des champs est déjà géré globalement via functions.php (filtre comment_form_default_fields)
    // On ajuste juste les conteneurs et le bouton
    comment_form( array(
        'class_submit'  => 'btn btn-primary mt-4 w-full md:w-auto',
        'title_reply'   => '<span class="text-2xl font-serif font-bold text-primary block mb-4">' . __( 'Laisser un commentaire', 'trendylux' ) . '</span>',
        'title_reply_before' => '<div id="reply-title" class="comment-reply-title">',
        'title_reply_after'  => '</div>',
        'comment_notes_before' => '<p class="comment-notes text-sm text-gray-500 mb-4">' . 
            __( 'Votre adresse e-mail ne sera pas publiée. Les champs obligatoires sont indiqués avec *', 'trendylux' ) . 
            '</p>',
        'class_container' => 'comment-respond bg-base-100 p-6 md:p-10 rounded-box shadow-lg border border-base-200 mt-12',
    ) );
    ?>

</div><!-- #comments -->
