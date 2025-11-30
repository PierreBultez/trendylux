<?php
defined( 'ABSPATH' ) || exit;

$order = wc_get_order( $order_id );
if ( ! $order ) return;
?>

<section class="woocommerce-order-details">
    <h2 class="woocommerce-order-details__title text-2xl font-bold text-primary mb-6"><?php esc_html_e( 'Order details', 'woocommerce' ); ?></h2>

    <div class="overflow-x-auto">
        <table class="table table-zebra w-full woocommerce-table woocommerce-table--order-details shop_table order_details my-orders-table">
            <thead>
            <tr>
                <th class="woocommerce-table__product-name product-name"><?php esc_html_e( 'Product', 'woocommerce' ); ?></th>
                <th class="woocommerce-table__product-table product-total text-right"><?php esc_html_e( 'Total', 'woocommerce' ); ?></th>
            </tr>
            </thead>
            <tbody>
            <?php
            foreach ( $order->get_items() as $item_id => $item ) {
                $product = $item->get_product();
                wc_get_template(
                    'order/order-details-item.php',
                    array(
                        'order'              => $order,
                        'item_id'            => $item_id,
                        'item'               => $item,
                        'show_purchase_note' => $show_purchase_note,
                        'purchase_note'      => $product ? $product->get_purchase_note() : '',
                        'product'            => $product,
                    )
                );
            }
            ?>
            <?php do_action( 'woocommerce_order_items_table', $order ); ?>
            </tbody>
            <tfoot>
            <?php foreach ( $order->get_order_item_totals() as $key => $total ) : ?>
                <tr>
                    <th scope="row" class="font-bold"><?php echo esc_html( $total['label'] ); ?></th>
                    <td class="text-right text-primary font-bold"><?php echo ( 'payment_method' === $key ) ? esc_html( $total['value'] ) : wp_kses_post( $total['value'] ); ?></td>
                </tr>
            <?php endforeach; ?>
            </tfoot>
        </table>
    </div>

    <?php do_action( 'woocommerce_order_details_after_order_table', $order ); ?>

    <?php if ( $show_customer_details ) : ?>
        <?php wc_get_template( 'order/order-details-customer.php', array( 'order' => $order ) ); ?>
    <?php endif; ?>
</section>