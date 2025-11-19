<?php
defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_customer_login_form' ); ?>

    <div class="container mx-auto px-4 py-12" id="customer_login">

        <!-- Grille : Si l'inscription est active, on affiche 2 colonnes, sinon 1 centrée -->
        <div class="grid grid-cols-1 <?php echo ( 'yes' === get_option( 'woocommerce_enable_myaccount_registration' ) ) ? 'lg:grid-cols-2' : 'lg:grid-cols-1 lg:max-w-lg lg:mx-auto'; ?> gap-8">

            <!-- LOGIN -->
            <div class="card bg-base-100 shadow-2xl border-t-4 border-primary">
                <div class="card-body">
                    <h2 class="card-title text-2xl mb-4"><?php esc_html_e( 'Login', 'woocommerce' ); ?></h2>

                    <form class="woocommerce-form woocommerce-form-login login" method="post">
                        <?php do_action( 'woocommerce_login_form_start' ); ?>

                        <div class="form-control w-full mb-4">
                            <label for="username" class="label"><span class="label-text"><?php esc_html_e( 'Username or email address', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></span></label>
                            <input type="text" class="input input-bordered w-full" name="username" id="username" autocomplete="username" value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>" />
                        </div>

                        <div class="form-control w-full mb-4">
                            <label for="password" class="label"><span class="label-text"><?php esc_html_e( 'Password', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></span></label>
                            <input class="input input-bordered w-full" type="password" name="password" id="password" autocomplete="current-password" />
                        </div>

                        <?php do_action( 'woocommerce_login_form' ); ?>

                        <div class="form-control mb-4">
                            <label class="label cursor-pointer justify-start gap-2">
                                <input class="checkbox checkbox-primary" name="rememberme" type="checkbox" id="rememberme" value="forever" />
                                <span class="label-text"><?php esc_html_e( 'Remember me', 'woocommerce' ); ?></span>
                            </label>
                        </div>

                        <div class="form-control mt-6">
                            <?php wp_nonce_field( 'woocommerce-login', 'woocommerce-login-nonce' ); ?>
                            <button type="submit" class="btn btn-primary w-full" name="login" value="<?php esc_attr_e( 'Log in', 'woocommerce' ); ?>"><?php esc_html_e( 'Log in', 'woocommerce' ); ?></button>
                        </div>

                        <p class="text-center mt-4 text-sm">
                            <a href="<?php echo esc_url( wp_lostpassword_url() ); ?>" class="link link-hover"><?php esc_html_e( 'Lost your password?', 'woocommerce' ); ?></a>
                        </p>

                        <?php do_action( 'woocommerce_login_form_end' ); ?>
                    </form>
                </div>
            </div>

            <!-- REGISTER (Affiché seulement si activé dans Woo) -->
            <?php if ( 'yes' === get_option( 'woocommerce_enable_myaccount_registration' ) ) : ?>

                <div class="card bg-base-200 shadow-xl">
                    <div class="card-body">
                        <h2 class="card-title text-2xl mb-4"><?php esc_html_e( 'Register', 'woocommerce' ); ?></h2>

                        <form method="post" class="woocommerce-form woocommerce-form-register register" <?php do_action( 'woocommerce_register_form_tag' ); ?> >
                            <?php do_action( 'woocommerce_register_form_start' ); ?>

                            <?php if ( 'no' === get_option( 'woocommerce_registration_generate_username' ) ) : ?>
                                <div class="form-control w-full mb-4">
                                    <label for="reg_username" class="label"><span class="label-text"><?php esc_html_e( 'Username', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></span></label>
                                    <input type="text" class="input input-bordered w-full" name="username" id="reg_username" autocomplete="username" value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>" />
                                </div>
                            <?php endif; ?>

                            <div class="form-control w-full mb-4">
                                <label for="reg_email" class="label"><span class="label-text"><?php esc_html_e( 'Email address', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></span></label>
                                <input type="email" class="input input-bordered w-full" name="email" id="reg_email" autocomplete="email" value="<?php echo ( ! empty( $_POST['email'] ) ) ? esc_attr( wp_unslash( $_POST['email'] ) ) : ''; ?>" />
                            </div>

                            <?php if ( 'no' === get_option( 'woocommerce_registration_generate_password' ) ) : ?>
                                <div class="form-control w-full mb-4">
                                    <label for="reg_password" class="label"><span class="label-text"><?php esc_html_e( 'Password', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></span></label>
                                    <input type="password" class="input input-bordered w-full" name="password" id="reg_password" autocomplete="new-password" />
                                </div>
                            <?php endif; ?>

                            <?php do_action( 'woocommerce_register_form' ); ?>

                            <div class="form-control mt-6">
                                <?php wp_nonce_field( 'woocommerce-register', 'woocommerce-register-nonce' ); ?>
                                <button type="submit" class="btn btn-outline btn-primary w-full" name="register" value="<?php esc_attr_e( 'Register', 'woocommerce' ); ?>"><?php esc_html_e( 'Register', 'woocommerce' ); ?></button>
                            </div>

                            <?php do_action( 'woocommerce_register_form_end' ); ?>
                        </form>
                    </div>
                </div>
            <?php endif; ?>

        </div>
    </div>
<?php do_action( 'woocommerce_after_customer_login_form' ); ?>