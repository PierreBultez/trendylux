<?php
/**
 * My Account > Addresses
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/addresses.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woo.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 2.6.0
 */

defined( 'ABSPATH' ) || exit;

$customer_id = get_current_user_id();

if ( ! wc_ship_to_billing_address_only() && wc_shipping_enabled() ) {
	$get_addresses = array(
		'billing'  => __( 'Billing address', 'woocommerce' ),
		'shipping' => __( 'Shipping address', 'woocommerce' ),
	);
} else {
	$get_addresses = array(
		'billing' => __( 'Billing address', 'woocommerce' ),
	);
}

$old_addresses = array(
	'billing' => wc_get_account_endpoint_url( 'edit-address' ) . ':billing',
	'shipping' => wc_get_account_endpoint_url( 'edit-address' ) . ':shipping',
);
?>

<p class="mb-4">
	<?php echo apply_filters( 'woocommerce_my_account_my_address_description', esc_html__( 'The following addresses will be used on the checkout page by default.', 'woocommerce' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
</p>

<div class="grid md:grid-cols-2 gap-4">

	<?php foreach ( $get_addresses as $name => $address_title ) : ?>
	<div class="card bg-base-100 shadow-xl">
		<div class="card-body">
			<h2 class="card-title"><?php echo esc_html( $address_title ); ?></h2>
			<address>
				<?php
					$address = wc_get_account_formatted_address( $name );
					if ( ! $address ) {
						esc_html_e( 'You have not set up this type of address yet.', 'woocommerce' );
					} else {
						echo wp_kses_post( $address );
					}
				?>
			</address>
			<div class="card-actions justify-end">
				<a href="<?php echo esc_url( wc_get_endpoint_url( 'edit-address', $name ) ); ?>" class="edit btn btn-secondary">
					<?php
						if ( ! $address ) {
							esc_html_e( 'Add', 'woocommerce' );
						} else {
							esc_html_e( 'Edit', 'woocommerce' );
						}
					?>
				</a>
			</div>
		</div>
	</div>
	<?php endforeach; ?>

</div>
