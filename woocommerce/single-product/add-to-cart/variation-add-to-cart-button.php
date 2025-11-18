<?php
/**
 * Template pour le bouton "Ajouter au panier" des produits variables.
 *
 * Ce template est appelé par une fonction personnalisée dans functions.php
 * pour remplacer le bouton par défaut de WooCommerce.
 */

defined( 'ABSPATH' ) || exit;

global $product;
?>
<div class="woocommerce-variation-add-to-cart variations_button">
	<?php do_action( 'woocommerce_before_add_to_cart_button' ); ?>

	<div class="flex items-center gap-4">
		<?php
		woocommerce_quantity_input(
			[
				// On s'assure que la valeur min est au moins 1.
				'min_value'   => apply_filters( 'woocommerce_quantity_input_min', $product->get_min_purchase_quantity(), $product ),
				'max_value'   => apply_filters( 'woocommerce_quantity_input_max', $product->get_max_purchase_quantity(), $product ),
				'input_value' => isset( $_POST['quantity'] ) ? wc_stock_amount( wp_unslash( $_POST['quantity'] ) ) : $product->get_min_purchase_quantity(),
			]
		);
		?>

		<button type="submit" class="single_add_to_cart_button button alt btn btn-primary flex-grow"><?php echo esc_html( $product->single_add_to_cart_text() ); ?></button>
	</div>

	<input type="hidden" name="add-to-cart" value="<?php echo absint( $product->get_id() ); ?>" />
	<input type="hidden" name="product_id" value="<?php echo absint( $product->get_id() ); ?>" />
	<input type="hidden" name="variation_id" class="variation_id" value="0" />

	<?php do_action( 'woocommerce_after_add_to_cart_button' ); ?>
</div>
