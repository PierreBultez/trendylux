<?php
/**
 * WooCommerce Notice Template - Error
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

    <div class="woocommerce-error my-4"
         role="alert"
         x-data="{ show: true }"
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
        <div class="alert alert-error alert-soft shadow-lg rounded-lg">
            <!-- Icône d'erreur -->
            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span class="font-semibold"><?php echo wc_kses_notice( $notice['notice'] ); ?></span>
        </div>
    </div>

<?php endforeach; ?>