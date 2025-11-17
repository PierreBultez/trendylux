<?php
/**
 * Checkout Payment Section
 *
 * @version 8.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! is_ajax() ) {
    do_action( 'woocommerce_review_order_before_payment' );
}
?>
    <div id="payment" class="woocommerce-checkout-payment bg-base-200/50 rounded-lg p-6 lg:p-8 mt-8">
        <?php if ( WC()->cart->needs_payment() ) : ?>
            <ul class="wc_payment_methods payment_methods methods space-y-4">
                <?php
                if ( ! empty( $available_gateways ) ) {
                    foreach ( $available_gateways as $gateway ) {
                        wc_get_template( 'checkout/payment-method.php', array( 'gateway' => $gateway ) );
                    }
                } else {
                    echo '<li class="woocommerce-notice woocommerce-notice--info woocommerce-info alert alert-info">' . apply_filters( 'woocommerce_no_available_payment_methods_message', WC()->customer->get_billing_country() ? esc_html__( 'Désolé, aucun mode de paiement n\'est disponible dans votre région. Veuillez nous contacter si vous avez besoin d\'aide ou souhaitez convenir d\'un autre arrangement.', 'woocommerce' ) : esc_html__( 'Veuillez renseigner vos informations ci-dessus pour consulter les modes de paiement disponibles.', 'woocommerce' ) ) . '</li>';
                }
                ?>
            </ul>
        <?php endif; ?>
        <div class="form-row place-order mt-8">
            <noscript>
                <?php esc_html_e( 'Since your browser does not support JavaScript, or it is disabled, please ensure you click the Update Totals button before placing your order. You may be charged more than the amount stated above if you fail to do so.', 'woocommerce' ); ?>
                <br/><button type="submit" class="button alt" name="woocommerce_checkout_update_totals" value="<?php esc_attr_e( 'Update totals', 'woocommerce' ); ?>"><?php esc_html_e( 'Update totals', 'woocommerce' ); ?></button>
            </noscript>

            <?php wc_get_template( 'checkout/terms.php' ); ?>

            <?php do_action( 'woocommerce_review_order_before_submit' ); ?>

            <?php echo apply_filters( 'woocommerce_checkout_place_order_button', '<button type="submit" class="btn btn-primary btn-block" id="place_order" value="' . esc_attr( $order_button_text ) . '" data-value="' . esc_attr( $order_button_text ) . '">' . esc_html( $order_button_text ) . '</button>' ); // @codingStandardsIgnoreLine ?>

            <?php do_action( 'woocommerce_review_order_after_submit' ); ?>

            <?php wp_nonce_field( 'woocommerce-process_checkout', 'woocommerce-checkout-nonce' ); ?>
        </div>
    </div>
<?php
if ( ! is_ajax() ) {
    do_action( 'woocommerce_review_order_after_payment' );
}