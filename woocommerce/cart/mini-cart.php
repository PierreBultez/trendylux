<?php
/**
 * Mini-cart
 * Chemin: wp-content/themes/trendylux/woocommerce/cart/mini-cart.php
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_mini_cart' ); ?>

<div class="p-4 w-full"> <!-- Ajout de w-full -->

    <?php if ( ! WC()->cart->is_empty() ) : ?>

        <!-- Liste scrollable : on ajoute w-full et on retire les paddings par défaut qui pourraient gêner -->
        <ul class="woocommerce-mini-cart cart_list product_list_widget <?php echo esc_attr( $args['list_class'] ); ?> flex flex-col gap-4 max-h-96 overflow-y-auto w-full pr-2">
            <?php
            do_action( 'woocommerce_before_mini_cart_contents' );

            foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
                $_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
                $product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

                if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_widget_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
                    $product_name      = apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key );
                    $thumbnail         = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image('thumbnail', ['class' => 'rounded-md w-16 h-16 object-cover border border-base-200']), $cart_item, $cart_item_key );
                    $product_price     = apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );
                    $product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
                    ?>

                    <!-- Item Panier -->
                    <li class="woocommerce-mini-cart-item <?php echo esc_attr( apply_filters( 'woocommerce_mini_cart_item_class', 'mini_cart_item flex gap-4 relative group w-full', $cart_item, $cart_item_key ) ); ?>">

                        <!-- Image -->
                        <?php if ( ! empty( $thumbnail ) ) : ?>
                            <a href="<?php echo esc_url( $product_permalink ); ?>" class="shrink-0">
                                <?php echo $thumbnail; ?>
                            </a>
                        <?php endif; ?>

                        <!-- Infos : min-w-0 est CRUCIAL pour empêcher le flex de déborder -->
                        <div class="flex-grow flex flex-col justify-center min-w-0 pr-6">
                            <a href="<?php echo esc_url( $product_permalink ); ?>" class="font-bold text-sm text-base-content hover:text-primary mb-1 truncate block">
                                <?php echo $product_name; ?>
                            </a>

                            <div class="text-sm text-base-content/70">
                                <?php echo apply_filters( 'woocommerce_widget_cart_item_quantity', '<span class="quantity">' . sprintf( '%s &times; %s', $cart_item['quantity'], $product_price ) . '</span>', $cart_item, $cart_item_key ); ?>
                            </div>

                            <div class="text-xs text-base-content/50 mt-1">
                                <?php echo wc_get_formatted_cart_item_data( $cart_item ); ?>
                            </div>
                        </div>

                        <!-- Bouton Supprimer : Positionné à l'intérieur (top-0 right-0) -->
                        <?php
                        echo apply_filters(
                            'woocommerce_cart_item_remove_link',
                            sprintf(
                                '<a href="%s" class="remove remove_from_cart_button btn btn-xs btn-circle btn-ghost text-base-content/40 hover:text-error hover:bg-base-200 absolute top-0 right-0 z-10 transition-all" aria-label="%s" data-product_id="%s" data-cart_item_key="%s" data-product_sku="%s">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                                </a>',
                                esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
                                esc_attr__( 'Remove this item', 'woocommerce' ),
                                esc_attr( $product_id ),
                                esc_attr( $cart_item_key ),
                                esc_attr( $_product->get_sku() )
                            ),
                            $cart_item_key
                        );
                        ?>
                    </li>
                    <?php
                }
            }
            do_action( 'woocommerce_mini_cart_contents' );
            ?>
        </ul>

        <!-- Total et Boutons -->
        <div class="mt-4 pt-4 border-t border-base-200">
            <div class="woocommerce-mini-cart__total total flex justify-between items-center mb-4">
                <span class="text-base-content/70 font-medium uppercase text-xs tracking-wider"><?php esc_html_e( 'Subtotal', 'woocommerce' ); ?></span>
                <span class="text-lg font-bold text-primary">
                    <?php echo WC()->cart->get_cart_subtotal(); ?>
                </span>
            </div>

            <?php do_action( 'woocommerce_widget_shopping_cart_before_buttons' ); ?>

            <div class="woocommerce-mini-cart__buttons buttons grid grid-cols-2 gap-3">
                <a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="btn btn-outline btn-primary btn-sm">
                    <?php esc_html_e( 'View cart', 'woocommerce' ); ?>
                </a>
                <a href="<?php echo esc_url( wc_get_checkout_url() ); ?>" class="btn btn-primary btn-sm">
                    <?php esc_html_e( 'Checkout', 'woocommerce' ); ?>
                </a>
            </div>

            <?php do_action( 'woocommerce_widget_shopping_cart_after_buttons' ); ?>
        </div>

    <?php else : ?>

        <!-- Panier vide -->
        <div class="text-center py-8 px-4">
            <div class="text-base-content/20 mb-3">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" class="w-16 h-16 mx-auto">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                </svg>
            </div>
            <p class="text-base-content font-medium mb-4"><?php esc_html_e( 'No products in the cart.', 'woocommerce' ); ?></p>
            <a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="btn btn-primary btn-sm btn-wide">
                Découvrir la boutique
            </a>
        </div>

    <?php endif; ?>

    <?php do_action( 'woocommerce_after_mini_cart' ); ?>
</div>