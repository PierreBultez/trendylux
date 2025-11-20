<?php
/**
 * Thankyou page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/thankyou.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 8.1.0
 *
 * @var WC_Order $order
 */

defined( 'ABSPATH' ) || exit;
?>

<div class="container mx-auto my-12">
    <?php
	if ( $order ) :
		do_action( 'woocommerce_before_thankyou', $order->get_id() );
	?>
        <?php if ( $order->has_status( 'failed' ) ) : ?>
            <div class="alert alert-error shadow-lg">
                <div>
                    <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <span><?php esc_html_e( 'Unfortunately your order cannot be processed as the originating bank/merchant has declined your transaction. Please attempt your purchase again.', 'woocommerce' ); ?></span>
                </div>
            </div>
            <div class="card-actions justify-end">
                <a href="<?php echo esc_url( $order->get_checkout_payment_url() ); ?>" class="btn btn-primary"><?php esc_html_e( 'Pay', 'woocommerce' ); ?></a>
                <?php if ( is_user_logged_in() ) : ?>
                    <a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" class="btn btn-secondary"><?php esc_html_e( 'My account', 'woocommerce' ); ?></a>
                <?php endif; ?>
            </div>
        <?php else : ?>
            <?php wc_get_template( 'checkout/order-received.php', array( 'order' => $order ) ); ?>
            <div class="flex flex-wrap -mx-4">
                <div class="w-full lg:w-1/2 px-4">
                    <div class="card bg-base-100 shadow-xl mb-8">
                        <div class="card-body">
                            <div class="flex justify-between py-2">
                                <span><?php esc_html_e( 'Order number:', 'woocommerce' ); ?></span>
                                <strong><?php echo $order->get_order_number(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></strong>
                            </div>
                            <div class="flex justify-between py-2">
                                <span><?php esc_html_e( 'Date:', 'woocommerce' ); ?></span>
                                <strong><?php echo wc_format_datetime( $order->get_date_created() ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></strong>
                            </div>
                            <?php if ( is_user_logged_in() && $order->get_user_id() === get_current_user_id() && $order->get_billing_email() ) : ?>
                            <div class="flex justify-between py-2">
                                <span><?php esc_html_e( 'Email:', 'woocommerce' ); ?></span>
                                <strong><?php echo $order->get_billing_email(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></strong>
                            </div>
                            <?php endif; ?>
                            <div class="flex justify-between py-2">
                                <span><?php esc_html_e( 'Total:', 'woocommerce' ); ?></span>
                                <strong><?php echo $order->get_formatted_order_total(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></strong>
                            </div>
                            <?php if ( $order->get_payment_method_title() ) : ?>
                            <div class="flex justify-between py-2">
                                <span><?php esc_html_e( 'Payment method:', 'woocommerce' ); ?></span>
                                <strong><?php echo wp_kses_post( $order->get_payment_method_title() ); ?></strong>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="w-full lg:w-1/2 px-4">
                    <div class="card bg-base-100 shadow-xl mb-8">
                        <div class="card-body">
                            <?php do_action( 'woocommerce_thankyou_' . $order->get_payment_method(), $order->get_id() ); ?>
                            <?php do_action( 'woocommerce_thankyou', $order->get_id() ); ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    <?php else : ?>
        <?php wc_get_template( 'checkout/order-received.php', array( 'order' => false ) ); ?>
    <?php endif; ?>
</div>

