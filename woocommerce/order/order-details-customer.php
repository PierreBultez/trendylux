<?php
/**
 * Order Customer Details
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/order/order-details-customer.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 8.7.0
 */

defined( 'ABSPATH' ) || exit;

$show_shipping = ! wc_ship_to_billing_address_only() && $order->needs_shipping_address();
?>
<section class="mt-8">
	<div class="flex flex-wrap -mx-4">
		<div class="w-full <?php if ( $show_shipping ) echo 'lg:w-1/2'; ?> px-4">
			<h2 class="text-lg font-bold mb-4"><?php esc_html_e( 'Billing address', 'woocommerce' ); ?></h2>
			<address class="not-italic">
				<?php echo wp_kses_post( $order->get_formatted_billing_address( esc_html__( 'N/A', 'woocommerce' ) ) ); ?>
			</address>
		</div>
		<?php if ( $show_shipping ) : ?>
		<div class="w-full lg:w-1/2 px-4">
			<h2 class="text-lg font-bold mb-4"><?php esc_html_e( 'Shipping address', 'woocommerce' ); ?></h2>
			<address class="not-italic">
				<?php echo wp_kses_post( $order->get_formatted_shipping_address( esc_html__( 'N/A', 'woocommerce' ) ) ); ?>
			</address>
		</div>
		<?php endif; ?>
	</div>
</section>

