<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$id_suffix = wp_unique_id();
?>
<form class="trendylux-ordering" method="get">
	<?php if ( $use_label ) : ?>
		<label for="woocommerce-orderby-<?php echo esc_attr( $id_suffix ); ?>"><?php echo esc_html__( 'Sort by', 'woocommerce' ); ?></label>
	<?php endif; ?>
	<select name="orderby" class="orderby select select-bordered" <?php if ( $use_label ) : ?> id="woocommerce-orderby-<?php echo esc_attr( $id_suffix ); ?>" <?php else : ?> aria-label="<?php esc_attr_e( 'Shop order', 'woocommerce' ); ?>" <?php endif; ?> >
		<?php foreach ( $catalog_orderby_options as $id => $name ) : ?>
			<option value="<?php echo esc_attr( $id ); ?>" <?php selected( $orderby, $id ); ?>><?php echo esc_html( $name ); ?></option>
		<?php endforeach; ?>
	</select>
	<input type="hidden" name="paged" value="1" />
	<?php wc_query_string_form_fields( null, array( 'orderby', 'submit', 'paged', 'product-page' ) ); ?>
</form>
