<?php
/**
 * Pagination - Show numbered pagination for catalog pages
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.3.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$total   = isset( $total ) ? $total : wc_get_loop_prop( 'total_pages' );
$current = isset( $current ) ? $current : wc_get_loop_prop( 'current_page' );
$base    = isset( $base ) ? $base : esc_url_raw( str_replace( 999999999, '%#%', remove_query_arg( 'add-to-cart', get_pagenum_link( 999999999, false ) ) ) );
$format  = isset( $format ) ? $format : '';

if ( $total <= 1 ) {
	return;
}
?>
<div class="join">
	<?php
	$pages = paginate_links(
		apply_filters(
			'woocommerce_pagination_args',
			array( // WPCS: XSS ok.
				'base'      => $base,
				'format'    => $format,
				'add_args'  => false,
				'current'   => max( 1, $current ),
				'total'     => $total,
				'prev_text' => '«',
				'next_text' => '»',
				'type'      => 'array',
				'end_size'  => 3,
				'mid_size'  => 3,
			)
		)
	);

	if ( is_array( $pages ) ) {
		foreach ( $pages as $page ) {
			$page = str_replace( 'page-numbers', 'join-item btn', $page );
            
            if ( strpos( $page, 'current' ) !== false ) {
				// Active state: add btn-active and btn-primary (Gold)
                $page = str_replace( 'join-item btn', 'join-item btn btn-active btn-primary', $page );
            }
            
            if ( strpos( $page, 'dots' ) !== false ) {
				// Disabled state for dots
                $page = str_replace( 'join-item btn', 'join-item btn btn-disabled', $page );
            }

			echo $page;
		}
	}
	?>
</div>
