<?php
defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_account_navigation' );
?>

    <nav class="woocommerce-MyAccount-navigation">
        <ul class="menu bg-base-100 w-full rounded-box shadow-lg border border-base-200 p-2 gap-1">
            <?php foreach ( wc_get_account_menu_items() as $endpoint => $label ) : ?>
                <li class="<?php echo wc_get_account_menu_item_classes( $endpoint ); ?>">
                    <?php
                    // On vérifie si c'est l'élément actif pour ajouter une classe spécifique si besoin,
                    // bien que DaisyUI gère souvent le focus, on veut forcer le style "Or"
                    $class = ( strpos( wc_get_account_menu_item_classes( $endpoint ), 'is-active' ) !== false ) ? 'active bg-primary text-primary-content font-bold' : 'hover:bg-base-200';
                    ?>
                    <a href="<?php echo esc_url( wc_get_account_endpoint_url( $endpoint ) ); ?>" class="<?php echo $class; ?>">
                        <?php echo esc_html( $label ); ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </nav>

<?php do_action( 'woocommerce_after_account_navigation' ); ?>