<?php
defined( 'ABSPATH' ) || exit;
do_action( 'woocommerce_before_cart' ); ?>

    <form class="woocommerce-cart-form" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">
        <div class="grid grid-cols-1 lg:grid-cols-3 lg:gap-12">
            <div class="lg:col-span-2">

                <?php do_action( 'woocommerce_before_cart_table' ); ?>

                <div class="overflow-x-auto">
                    <table class="shop_table shop_table_responsive cart woocommerce-cart-form__contents table w-full" cellspacing="0">
                        <thead>
                        <tr>
                            <th class="product-remove">&nbsp;</th>
                            <th class="product-thumbnail">&nbsp;</th>
                            <th class="product-name"><?php esc_html_e( 'Product', 'woocommerce' ); ?></th>
                            <th class="product-price"><?php esc_html_e( 'Price', 'woocommerce' ); ?></th>
                            <th class="product-quantity"><?php esc_html_e( 'Quantity', 'woocommerce' ); ?></th>
                            <th class="product-subtotal"><?php esc_html_e( 'Subtotal', 'woocommerce' ); ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php do_action( 'woocommerce_before_cart_contents' ); ?>
                        <?php
                        // Boucle PHP originale de WooCommerce pour afficher les produits...
                        // ... (tout le code de la boucle `foreach` reste ici) ...
                        foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
                            $_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
                            $product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );
                            $product_name = apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key );
                            if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
                                $product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
                                ?>
                                <tr class="woocommerce-cart-form__cart-item <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">
                                    <td class="product-remove"><?php echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf('<a href="%s" class="remove text-error hover:text-error/70" aria-label="%s" data-product_id="%s" data-product_sku="%s">&times;</a>', esc_url( wc_get_cart_remove_url( $cart_item_key ) ), esc_attr( sprintf( __( 'Remove %s from cart', 'woocommerce' ), wp_strip_all_tags( $product_name ) ) ), esc_attr( $product_id ), esc_attr( $_product->get_sku() ) ), $cart_item_key ); ?></td>
                                    <td class="product-thumbnail w-24"><?php $thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key ); if ( ! $product_permalink ) { echo $thumbnail; } else { printf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $thumbnail ); } ?></td>
                                    <td class="product-name" data-title="<?php esc_attr_e( 'Product', 'woocommerce' ); ?>"><?php if ( ! $product_permalink ) { echo wp_kses_post( $product_name . '&nbsp;' ); } else { echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s" class="link link-hover">%s</a>', esc_url( $product_permalink ), $_product->get_name() ), $cart_item, $cart_item_key ) ); } do_action( 'woocommerce_after_cart_item_name', $cart_item, $cart_item_key ); echo wc_get_formatted_cart_item_data( $cart_item ); if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $cart_item['quantity'] ) ) { echo wp_kses_post( apply_filters( 'woocommerce_cart_item_backorder_notification', '<p class="backorder_notification">' . esc_html__( 'Available on backorder', 'woocommerce' ) . '</p>', $product_id ) ); } ?></td>
                                    <td class="product-price" data-title="<?php esc_attr_e( 'Price', 'woocommerce' ); ?>"><?php echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key ); ?></td>
                                    <td class="product-quantity" data-title="<?php esc_attr_e( 'Quantity', 'woocommerce' ); ?>"><?php if ( $_product->is_sold_individually() ) { $min_quantity = 1; $max_quantity = 1; } else { $min_quantity = 0; $max_quantity = $_product->get_max_purchase_quantity(); } $product_quantity = woocommerce_quantity_input( array( 'input_name' => "cart[{$cart_item_key}][qty]", 'input_value' => $cart_item['quantity'], 'max_value' => $max_quantity, 'min_value' => $min_quantity, 'product_name' => $product_name, 'classes' => ['input', 'input-bordered', 'w-20'] ), $_product, false ); echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item ); ?></td>
                                    <td class="product-subtotal" data-title="<?php esc_attr_e( 'Subtotal', 'woocommerce' ); ?>"><?php echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); ?></td>
                                </tr>
                                <?php
                            }
                        }
                        ?>
                        <?php do_action( 'woocommerce_cart_contents' ); ?>
                        </tbody>
                    </table>
                </div>

                <div class="actions mt-4 flex justify-between items-center">
                    <?php if ( wc_coupons_enabled() ) { ?>
                        <div class="coupon join">
                            <input type="text" name="coupon_code" class="input input-bordered join-item" id="coupon_code" value="" placeholder="<?php esc_attr_e( 'Coupon code', 'woocommerce' ); ?>" />
                            <button type="submit" class="btn join-item" name="apply_coupon" value="<?php esc_attr_e( 'Apply coupon', 'woocommerce' ); ?>"><?php esc_attr_e( 'Apply coupon', 'woocommerce' ); ?></button>
                        </div>
                    <?php } ?>
                    <button type="submit" class="button btn btn-ghost" name="update_cart" value="<?php esc_attr_e( 'Update cart', 'woocommerce' ); ?>"><?php esc_html_e( 'Update cart', 'woocommerce' ); ?></button>
                    <?php do_action( 'woocommerce_cart_actions' ); ?>
                    <?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>
                </div>

                <?php do_action( 'woocommerce_after_cart_table' ); ?>
            </div>

            <div class="lg:col-span-1">
                <?php do_action( 'woocommerce_before_cart_collaterals' ); ?>
                <div class="cart-collaterals">
                    <?php do_action( 'woocommerce_cart_collaterals' ); ?>
                </div>
            </div>
        </div>
    </form>

<?php do_action( 'woocommerce_after_cart' ); ?>