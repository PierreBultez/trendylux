<?php
/**
 * Template pour afficher les avis sur la page produit.
 *
 * Surchargé pour utiliser une grille de cartes (DaisyUI/Tailwind) au lieu d'une liste.
 */

defined( 'ABSPATH' ) || exit;

global $product;

// Ne rien afficher si les commentaires sont fermés
if ( ! comments_open() ) {
	return;
}

?>
<div id="reviews" class="comments-area">
	<div id="comments">
		<?php
		$comments = get_comments( [
			'post_id' => $product->get_id(),
			'status'  => 'approve',
			'type'    => 'review',
			'orderby' => 'comment_date',
			'order'   => 'DESC',
		] );

		if ( $comments ) : ?>
			<!-- Grille pour afficher les avis -->
			<div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-16">
				<?php foreach ( $comments as $comment ) : ?>
					<div class="comment-card flex flex-col p-6 bg-base-200 rounded-box shadow">
						<div class="flex items-center mb-3">
							<?php echo get_avatar( $comment, 48, '', '', ['class' => 'rounded-full mr-4'] ); ?>
							<div>
								<h4 class="font-bold text-lg m-0"><?php comment_author( $comment ); ?></h4>
								<time class="text-xs opacity-60" datetime="<?php echo get_comment_date( 'c', $comment ); ?>">
									<?php echo get_comment_date( wc_date_format(), $comment ); ?>
								</time>
							</div>
						</div>

						<?php
						$rating = get_comment_meta( $comment->comment_ID, 'rating', true );
						if ( $rating && wc_review_ratings_enabled() ) :
							?>
							<div class="rating rating-sm mb-3" role="img" aria-label="<?php printf( esc_html__( 'Rated %d out of 5', 'woocommerce' ), $rating ); ?>">
								<?php for ( $i = 1; $i <= 5; $i++ ) {
									$color_class = ( $i <= $rating ) ? 'bg-primary' : 'bg-gray-300';
									echo '<div class="mask mask-star-2 h-4 w-4 ' . $color_class . '"></div>';
								} ?>
							</div>
						<?php endif; ?>

						<div class="prose prose-sm max-w-none opacity-80">
							<?php comment_text( $comment ); ?>
						</div>
					</div>
				<?php endforeach; ?>
			</div>

		<?php else : ?>
			<p class="woocommerce-noreviews"><?php esc_html_e( 'There are no reviews yet.', 'woocommerce' ); ?></p>
		<?php endif; ?>
	</div>

	<?php if ( get_option( 'woocommerce_review_rating_verification_required' ) === 'no' || wc_customer_bought_product( '', get_current_user_id(), $product->get_id() ) ) : ?>
		<div id="review_form_wrapper" class="mt-12">
			<div id="review_form">
				<?php
				comment_form(
					apply_filters(
						'woocommerce_product_review_comment_form_args',
						[
							'title_reply'         => $comments ? esc_html__( 'Add a review', 'woocommerce' ) : sprintf( esc_html__( 'Be the first to review &ldquo;%s&rdquo;', 'woocommerce' ), get_the_title() ),
							'title_reply_to'      => esc_html__( 'Leave a Reply to %s', 'woocommerce' ),
							'title_reply_class'   => 'comment-reply-title text-2xl font-bold mb-8 uppercase tracking-wide',
							'comment_notes_after' => '',
							'label_submit'        => esc_html__( 'Submit', 'woocommerce' ),
							'class_submit'        => 'btn btn-primary mt-8', // Ajout des classes DaisyUI et de la marge
							'logged_in_as'        => '',
							'comment_field'       => '', // Le champ de commentaire est géré par notre filtre dans functions.php
						]
					)
				);
				?>
			</div>
		</div>
	<?php else : ?>
		<p class="woocommerce-verification-required"><?php esc_html_e( 'Only logged in customers who have purchased this product may leave a review.', 'woocommerce' ); ?></p>
	<?php endif; ?>

	<div class="clear"></div>
</div>
