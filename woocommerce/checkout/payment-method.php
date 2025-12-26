<?php
/**
 * Output a single payment method
 *
 * @version 7.0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
<li class="wc_payment_method payment_method_<?php echo esc_attr( $gateway->id ); ?> bg-base-100 p-4 rounded-lg border border-base-300 transition-all hover:border-primary">

    <label class="flex items-center cursor-pointer text-base-content" for="payment_method_<?php echo esc_attr( $gateway->id ); ?>">
        <input
                id="payment_method_<?php echo esc_attr( $gateway->id ); ?>"
                type="radio"
                class="radio radio-primary mr-4"
                name="payment_method"
                value="<?php echo esc_attr( $gateway->id ); ?>"
                <?php checked( $gateway->chosen, true ); ?>
                data-order_button_text="<?php echo esc_attr( $gateway->order_button_text ); ?>"
        />
        <span class="font-semibold"><?php echo $gateway->get_title(); /* phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped */ ?></span>
        <div class="ml-auto flex items-center gap-6">
            <?php echo $gateway->get_icon(); /* phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped */ ?>
            <?php if ( strpos( strtolower( $gateway->get_title() ), 'carte bancaire' ) !== false || $gateway->id === 'stripe' ) : ?>
                <img src="<?php echo esc_url( get_template_directory_uri() . '/public/checkout/visa.svg' ); ?>" alt="logo Visa" class="h-6 w-auto">
                <img src="<?php echo esc_url( get_template_directory_uri() . '/public/checkout/mastercard.svg' ); ?>" alt="logo Mastercard" class="h-6 w-auto">
            <?php endif; ?>
        </div>
    </label>

    <?php if ( $gateway->has_fields() || $gateway->get_description() ) : ?>
        <div class="payment_box payment_method_<?php echo esc_attr( $gateway->id ); ?> bg-base-200/50 mt-4 p-4 rounded-md" <?php if ( ! $gateway->chosen ) : ?>style="display:none;"<?php endif; ?>>
            <?php $gateway->payment_fields(); ?>
        </div>
    <?php endif; ?>
</li>