<?php
/**
 * Review order table - Version customisée pour TRENDYLUX
 * Affiche les miniatures des produits et utilise Flexbox au lieu d'un tableau.
 *
 * @version 5.2.0
 */

defined( 'ABSPATH' ) || exit;
?>

<!-- On remplace la <table> par une structure de divs plus flexible -->
<div class="woocommerce-checkout-review-order-table space-y-4 divide-y divide-base-300">

    <!-- Boucle sur les produits du panier -->
    <?php
    do_action( 'woocommerce_review_order_before_cart_contents' );

    foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
        $_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );

        if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_checkout_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
            ?>
            <div class="flex items-center gap-4 py-4 first:pt-0" key="<?php echo esc_attr( $cart_item_key ); ?>">

                <!-- 1. La Miniature du Produit -->
                <div class="w-16 h-16 shrink-0">
                    <?php
                    $thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );
                    echo $thumbnail; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                    ?>
                </div>

                <!-- 2. Nom du produit et Quantité -->
                <div class="grow">
                    <div class="font-semibold text-base-content">
                        <?php echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) ) . '&nbsp;'; ?>
                        <?php echo apply_filters( 'woocommerce_checkout_cart_item_quantity', ' <strong class="product-quantity">' . sprintf( '&times;%s', $cart_item['quantity'] ) . '</strong>', $cart_item, $cart_item_key ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                    </div>
                    <?php echo wc_get_formatted_cart_item_data( $cart_item ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                </div>

                <!-- 3. Sous-total de la ligne -->
                <div class="ml-auto font-medium text-base-content">
                    <?php echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                </div>
            </div>
            <?php
        }
    }

    do_action( 'woocommerce_review_order_after_cart_contents' );
    ?>

    <!-- Pied de page avec les totaux -->
    <div class="totals space-y-2 pt-4">

        <div class="cart-subtotal flex justify-between">
            <span><?php esc_html_e( 'Subtotal', 'woocommerce' ); ?></span>
            <span><?php wc_cart_totals_subtotal_html(); ?></span>
        </div>

        <?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
            <div class="cart-discount coupon-<?php echo esc_attr( sanitize_title( $code ) ); ?> flex justify-between text-success">
                <span><?php wc_cart_totals_coupon_label( $coupon ); ?></span>
                <span><?php wc_cart_totals_coupon_html( $coupon ); ?></span>
            </div>
        <?php endforeach; ?>

        <?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>
            <?php do_action( 'woocommerce_review_order_before_shipping' ); ?>
            <div class="shipping flex justify-between">
                <span><?php esc_html_e( 'Shipping', 'woocommerce' ); ?></span>
                <div class="text-right"><?php wc_cart_totals_shipping_html(); ?></div>
            </div>
            <?php do_action( 'woocommerce_review_order_after_shipping' ); ?>
        <?php endif; ?>

        <?php foreach ( WC()->cart->get_fees() as $fee ) : ?>
            <div class="fee flex justify-between">
                <span><?php echo esc_html( $fee->name ); ?></span>
                <span><?php wc_cart_totals_fee_html( $fee ); ?></span>
            </div>
        <?php endforeach; ?>

        <?php if ( wc_tax_enabled() && ! WC()->cart->display_prices_including_tax() ) : /* ... taxes ... */ endif; ?>

        <?php do_action( 'woocommerce_review_order_before_order_total' ); ?>

        <div class="order-total flex justify-between font-bold text-lg border-t border-base-300 pt-4 mt-4">
            <span><?php esc_html_e( 'Total', 'woocommerce' ); ?></span>
            <span><?php wc_cart_totals_order_total_html(); ?></span>
        </div>

        <?php do_action( 'woocommerce_review_order_after_order_total' ); ?>

    </div>
</div>