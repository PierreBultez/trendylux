<?php
/**
 * My Account page
 */

defined( 'ABSPATH' ) || exit;
?>

<div class="container mx-auto px-4 py-10">
    <div class="flex flex-col lg:flex-row gap-8">

        <!-- Sidebar Navigation (1/4 width sur large) -->
        <div class="w-full lg:w-1/4">
            <?php
            /**
             * My Account navigation.
             * @since 2.6.0
             */
            do_action( 'woocommerce_account_navigation' );
            ?>
        </div>

        <!-- Contenu Principal (3/4 width sur large) -->
        <div class="w-full lg:w-3/4">
            <div class="woocommerce-MyAccount-content card bg-base-100 shadow-xl border border-base-200">
                <div class="card-body">
                    <?php
                    /**
                     * My Account content.
                     * @since 2.6.0
                     */
                    do_action( 'woocommerce_account_content' );
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>