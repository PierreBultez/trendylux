<?php
defined( 'ABSPATH' ) || exit;

global $product;

if ( ! $product->is_purchasable() ) {
    return;
}

if ( $product->is_in_stock() ) : ?>

    <?php do_action( 'woocommerce_before_add_to_cart_form' ); ?>

    <form class="cart flex items-center gap-4"
          action="<?php echo esc_url( apply_filters( 'woocommerce_add_to_cart_form_action', $product->get_permalink() ) ); ?>"
          method="post" enctype='multipart/form-data'>

        <?php do_action( 'woocommerce_before_add_to_cart_button' ); ?>

        <?php
        woocommerce_quantity_input(
            [
                'min_value'   => apply_filters( 'woocommerce_quantity_input_min', $product->get_min_purchase_quantity(), $product ),
                'max_value'   => apply_filters( 'woocommerce_quantity_input_max', $product->get_max_purchase_quantity(), $product ),
                'input_value' => isset( $_POST['quantity'] ) ? wc_stock_amount( wp_unslash( $_POST['quantity'] ) ) : $product->get_min_purchase_quantity(),
            ]
        );
        ?>

        <button type="submit" name="add-to-cart" value="<?php echo esc_attr( $product->get_id() ); ?>" class="single_add_to_cart_button button alt btn btn-primary flex-grow">
            <?php echo esc_html( $product->single_add_to_cart_text() ); ?>
        </button>

        <?php do_action( 'woocommerce_after_add_to_cart_button' ); ?>
    </form>

    <div class="paypal-4x-info mt-3 text-sm flex items-center gap-2 text-base-content/70">
        <span>Payer en 4x sans frais avec</span>
        <img src="<?php echo esc_url( get_template_directory_uri() . '/public/checkout/paypal.svg' ); ?>" alt="PayPal" class="h-4 w-auto">
    </div>

    <?php do_action( 'woocommerce_after_add_to_cart_form' ); ?>

<?php endif; ?>