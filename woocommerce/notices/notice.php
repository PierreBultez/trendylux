<?php
/**
 * WooCommerce Notice Template - Notice/Info
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! $notices ) {
    return;
}

foreach ( $notices as $notice ) : ?>

    <div class="toast toast-end z-9999 mb-4 mr-4"
         role="alert"
         x-data="{ show: false }"
         x-init="setTimeout(function() { show = true }); setTimeout(function() { show = false }, 4000)"
         x-show="show"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-x-10"
         x-transition:enter-end="opacity-100 translate-x-0"
         x-transition:leave="transition ease-in duration-300"
         x-transition:leave-start="opacity-100 translate-x-0"
         x-transition:leave-end="opacity-0 translate-x-10"
         @transitionend="if(!show) $el.remove()"
            <?php echo wc_get_notice_data_attr( $notice ); ?>
    >
        <div class="alert alert-info text-white shadow-lg">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="stroke-current shrink-0 w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span><?php echo wc_kses_notice( $notice['notice'] ); ?></span>
        </div>
    </div>

<?php endforeach; ?>