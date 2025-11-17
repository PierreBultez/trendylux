<?php
/**
 * WooCommerce Notice Template - Success (Version finale avec animation fonctionnelle)
 *
 * @version 3.9.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! $notices ) {
    return;
}

foreach ( $notices as $notice ) : ?>

    <div class="woocommerce-message my-4"
         role="alert"
         x-data="{ show: false }"
         x-init="$nextTick(function() { show = true }); setTimeout(function() { show = false }, 3000)"
         x-show="show"
         x-transition:enter="transition ease-out duration-500"
         x-transition:enter-start="opacity-0 transform scale-95"
         x-transition:enter-end="opacity-100 transform scale-100"
         x-transition:leave="transition ease-in duration-500"
         x-transition:leave-start="opacity-100 transform scale-100"
         x-transition:leave-end="opacity-0 transform scale-95"
        <?php echo wc_get_notice_data_attr( $notice ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
    >
        <div class="alert alert-success alert-soft shadow-lg rounded-lg border border-neutral-focus">
            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6 text-primary" fill="none" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span class="font-semibold"><?php echo wc_kses_notice( $notice['notice'] ); ?></span>
        </div>
    </div>

<?php endforeach; ?>