<?php
/**
 * Edit address form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-edit-address.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://woo.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.6.0
 */

defined( 'ABSPATH' ) || exit;

$page_title = ( 'billing' === $load_address ) ? __( 'Billing address', 'woocommerce' ) : __( 'Shipping address', 'woocommerce' );

do_action( 'woocommerce_before_edit_account_address_form' ); ?>

<?php if ( ! $load_address ) : ?>
	<?php wc_get_template( 'myaccount/lost-password.php' ); ?>
<?php else : ?>

	<form method="post" class="form-control w-full max-w-lg">

		<h3 class="text-xl font-bold mb-4"><?php echo apply_filters( 'woocommerce_my_account_edit_address_title', $page_title, $load_address ); ?></h3>

		<div class="woocommerce-address-fields">
			<?php do_action( "woocommerce_before_add_to_cart_form" ); ?>

			<div class="woocommerce-address-fields__field-wrapper space-y-4">
				<?php
				foreach ( $address_fields as $key => $field ) {
					$field['class'][] = 'input input-bordered w-full';
					if ( isset( $field['label'] ) && $field['type'] !== 'checkbox' ) {
						echo '<label class="label">' . $field['label'] . '</label>';
					}
					woocommerce_form_field( $key, $field, wc_get_post_data_by_key( $key, $field['value'] ) );
				}
				?>
			</div>

			<?php do_action( 'woocommerce_after_add_to_cart_form' ); ?>

			<div class="mt-6">
				<button type="submit" class="btn btn-primary" name="save_address" value="<?php esc_attr_e( 'Save address', 'woocommerce' ); ?>">
					<?php esc_html_e( 'Save address', 'woocommerce' ); ?>
				</button>
				<?php wp_nonce_field( 'woocommerce-edit_address', 'woocommerce-edit-address-nonce' ); ?>
				<input type="hidden" name="action" value="edit_address" />
			</div>
		</div>

	</form>

<?php endif; ?>

<?php do_action( 'woocommerce_after_edit_account_address_form' ); ?>