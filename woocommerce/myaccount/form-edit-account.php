<?php
/**
 * Edit account form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-edit-account.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://woo.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.5.0
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_edit_account_form' ); ?>

<form class="woocommerce-EditAccountForm edit-account w-full md:max-w-3xl mx-auto" action="" method="post" <?php do_action( 'woocommerce_edit_account_form_tag' ); ?> >

	<?php do_action( 'woocommerce_edit_account_form_start' ); ?>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div class="form-control w-full">
            <label for="account_first_name" class="label">
                <span class="label-text font-semibold"><?php esc_html_e( 'First name', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></span>
            </label>
            <input type="text" class="input input-bordered w-full focus:outline-none focus:border-primary" name="account_first_name" id="account_first_name" autocomplete="given-name" value="<?php echo esc_attr( $user->first_name ); ?>" />
        </div>
        <div class="form-control w-full">
            <label for="account_last_name" class="label">
                <span class="label-text font-semibold"><?php esc_html_e( 'Last name', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></span>
            </label>
            <input type="text" class="input input-bordered w-full focus:outline-none focus:border-primary" name="account_last_name" id="account_last_name" autocomplete="family-name" value="<?php echo esc_attr( $user->last_name ); ?>" />
        </div>
    </div>

	<div class="form-control w-full mb-6">
		<label for="account_display_name" class="label">
            <span class="label-text font-semibold"><?php esc_html_e( 'Display name', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></span>
        </label>
		<input type="text" class="input input-bordered w-full focus:outline-none focus:border-primary" name="account_display_name" id="account_display_name" value="<?php echo esc_attr( $user->display_name ); ?>" />
	</div>

	<div class="form-control w-full mb-10">
		<label for="account_email" class="label">
            <span class="label-text font-semibold"><?php esc_html_e( 'Email address', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></span>
        </label>
		<input type="email" class="input input-bordered w-full focus:outline-none focus:border-primary" name="account_email" id="account_email" autocomplete="email" value="<?php echo esc_attr( $user->user_email ); ?>" />
	</div>

	<fieldset class="border border-base-300 p-3 md:p-6 rounded-box bg-base-100 shadow-sm w-full min-w-0">
		<legend class="text-lg font-bold px-2 text-primary"><?php esc_html_e( 'Password change', 'woocommerce' ); ?></legend>

		<div class="form-control w-full mb-4">
			<label for="password_current" class="label">
                <span class="label-text font-semibold">Mot de passe actuel</span>
            </label>
			<input type="password" class="input input-bordered w-full focus:outline-none focus:border-primary" name="password_current" id="password_current" autocomplete="off" />
		</div>
		<div class="form-control w-full mb-4">
			<label for="password_1" class="label">
                <span class="label-text font-semibold">Nouveau mot de passe</span>
            </label>
			<input type="password" class="input input-bordered w-full focus:outline-none focus:border-primary" name="password_1" id="password_1" autocomplete="off" />
		</div>
		<div class="form-control w-full mb-4">
			<label for="password_2" class="label">
                <span class="label-text font-semibold"><?php esc_html_e( 'Confirm new password', 'woocommerce' ); ?></span>
            </label>
			<input type="password" class="input input-bordered w-full focus:outline-none focus:border-primary" name="password_2" id="password_2" autocomplete="off" />
		</div>
	</fieldset>

	<?php do_action( 'woocommerce_edit_account_form' ); ?>

	<div class="mt-8 flex justify-end">
		<?php wp_nonce_field( 'save_account_details', 'save-account-details-nonce' ); ?>
		<button type="submit" class="btn btn-primary min-w-[200px] w-full" name="save_account_details" value="<?php esc_attr_e( 'Save changes', 'woocommerce' ); ?>"><?php esc_html_e( 'Save changes', 'woocommerce' ); ?></button>
		<input type="hidden" name="action" value="save_account_details" />
	</div>

	<?php do_action( 'woocommerce_edit_account_form_end' ); ?>
</form>

<?php do_action( 'woocommerce_after_edit_account_form' ); ?>