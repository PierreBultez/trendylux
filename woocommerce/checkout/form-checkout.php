<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
do_action( 'woocommerce_before_checkout_form', $checkout );
if ( ! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in() ) {
    echo esc_html( apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'woocommerce' ) ) );
    return;
}
?>
    <div class="mb-8">
        <h1 class="text-4xl font-serif font-bold text-primary"><?php the_title(); ?></h1>
    </div>
    <form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data">
        <div class="grid grid-cols-1 lg:grid-cols-2 lg:gap-12">
            <div class="col-1">
                <?php if ( $checkout->get_checkout_fields() ) : ?>
                    <?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>
                    <div id="customer_details">
                        <div><?php do_action( 'woocommerce_checkout_billing' ); ?></div>
                        <div><?php do_action( 'woocommerce_checkout_shipping' ); ?></div>
                    </div>
                    <?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>
                <?php endif; ?>
            </div>
            <div class="col-2">
                <div class="bg-base-200 p-8 rounded-box">
                    <h3 id="order_review_heading" class="text-2xl font-bold mb-4"><?php esc_html_e( 'Your order', 'woocommerce' ); ?></h3>
                    <?php do_action( 'woocommerce_checkout_before_order_review' ); ?>
                    <div id="order_review" class="woocommerce-checkout-review-order">
                        <?php do_action( 'woocommerce_checkout_order_review' ); ?>
                    </div>
                    <?php do_action( 'woocommerce_checkout_after_order_review' ); ?>
                </div>
            </div>
        </div>
    </form>
<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>