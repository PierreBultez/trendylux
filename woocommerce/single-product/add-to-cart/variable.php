<?php
/**
 * Template pour les produits variables
 *
 * Surchargé pour utiliser les composants DaisyUI pour les menus de sélection.
 */

defined( 'ABSPATH' ) || exit;

global $product;

$attribute_keys  = array_keys( $attributes );
$variations_json = wp_json_encode( $available_variations );
$variations_attr = function_exists( 'wc_esc_json' ) ? wc_esc_json( $variations_json ) : _wp_specialchars( $variations_json, ENT_QUOTES, 'UTF-8', true );

do_action( 'woocommerce_before_add_to_cart_form' ); ?>

<form class="variations_form cart" action="<?php echo esc_url( apply_filters( 'woocommerce_add_to_cart_form_action', $product->get_permalink() ) ); ?>" method="post" enctype='multipart/form-data' data-product_id="<?php echo absint( $product->get_id() ); ?>" data-product_variations="<?php echo $variations_attr; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>">
	<?php do_action( 'woocommerce_before_variations_form' ); ?>

	<?php if ( empty( $available_variations ) && false !== $available_variations ) : ?>
		<p class="stock out-of-stock"><?php echo esc_html( apply_filters( 'woocommerce_out_of_stock_message', __( 'This product is currently out of stock and unavailable.', 'woocommerce' ) ) ); ?></p>
	<?php else : ?>
        <!-- Remplacement de la table par des divs et des selects DaisyUI -->
		<div class="variations space-y-4 mb-6">
			<?php foreach ( $attributes as $attribute_name => $options ) : ?>
				<div class="form-control w-full">
                    <label for="<?php echo esc_attr( sanitize_title( $attribute_name ) ); ?>" class="label">
                        <span class="label-text"><?php echo wc_attribute_label( $attribute_name ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
                    </label>
					<?php
					wc_dropdown_variation_attribute_options(
						[
							'options'   => $options,
							'attribute' => $attribute_name,
							'product'   => $product,
                            'class'     => 'select select-bordered w-full', // Ajout des classes DaisyUI
						]
					);
					?>
				</div>
			<?php endforeach; ?>
		</div>

		<div class="single_variation_wrap">
			<?php
				/**
				 * Hook: woocommerce_before_single_variation.
				 */
				do_action( 'woocommerce_before_single_variation' );

				/**
				 * Hook: woocommerce_single_variation. Used to output the cart button and placeholder for variation data.
				 * @hooked woocommerce_single_variation - 10 Empty div for variation data.
				 * @hooked woocommerce_single_variation_add_to_cart_button - 20 Qty and cart button.
				 */
				do_action( 'woocommerce_single_variation' );

				/**
				 * Hook: woocommerce_after_single_variation.
				 */
				do_action( 'woocommerce_after_single_variation' );
			?>
		</div>
	<?php endif; ?>

	<?php do_action( 'woocommerce_after_variations_form' ); ?>
</form>

<?php
do_action( 'woocommerce_after_add_to_cart_form' );
