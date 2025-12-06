<?php
/**
 * Checkout coupon form - Version customisée pour TRENDYLUX
 * Affiche un champ de saisie toujours visible, stylisé avec DaisyUI.
 *
 * @version 7.0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

if ( ! wc_coupons_enabled() ) {
    return;
}

?>
<!-- On retire complètement l'ancien système de "toggle" -->
<form class="checkout_coupon woocommerce-form-coupon my-6 !block w-full md:w-1/3" method="post">

    <p class="label-text mb-5 mt-10"><?php esc_html_e( 'Si vous possédez un code promo, veuillez l\'appliquer ci-dessous.', 'woocommerce' ); ?></p>

    <div class="join w-full">
        <input
                type="text"
                name="coupon_code"
                class="input input-bordered join-item w-full"
                placeholder="<?php esc_attr_e( 'Coupon code', 'woocommerce' ); ?>"
                id="coupon_code"
                value=""
        />
        <button
                type="submit"
                class="btn btn-secondary join-item"
                name="apply_coupon"
                value="<?php esc_attr_e( 'Apply coupon', 'woocommerce' ); ?>"
        >
            Appliquer
        </button>
    </div>

</form>

<div class="w-full md:w-1/3 mb-8">
    <p class="label-text mb-2 mt-4"><?php esc_html_e( 'Livraison offerte sur votre 1ère commande pour l’inscription à la newsletter', 'woocommerce' ); ?></p>
    <div class="form-control">
        <form class="join w-full js-newsletter-form">
            <input type="email" name="email" placeholder="votre.email@exemple.com" class="input input-bordered join-item w-full" required />
            <button type="submit" class="btn btn-primary join-item">S'inscrire</button>
        </form>
        <div class="text-xs mt-2 absolute js-newsletter-message"></div>
    </div>
</div>