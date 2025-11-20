<?php
/**
 * "Order received" message.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/order-received.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 8.8.0
 *
 * @var WC_Order|false $order
 */

defined( 'ABSPATH' ) || exit;
?>

<div role="alert" class="alert alert-success alert-dash shadow-lg mb-8">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 stroke-current" fill="none" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
    </svg>
    <span>
        <?php
        /**
         * Filter the message shown after a checkout is complete.
         *
         * @since 2.2.0
         *
         * @param string         $message The message.
         * @param WC_Order|false $order   The order created during checkout, or false if order data is not available.
         */
        $message = apply_filters(
                'woocommerce_thankyou_order_received_text',
                esc_html( __( 'Thank you. Your order has been received.', 'woocommerce' ) ),
                $order
        );

        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        echo $message;
        ?>
    </span>
</div>
