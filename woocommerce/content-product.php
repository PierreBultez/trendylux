<?php
// Assure que le fichier n'est pas appelé directement
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

global $product;

// S'assure que les données du produit sont disponibles
if ( empty( $product ) || ! $product->is_visible() ) {
    return;
}
?>
<div class="card bg-base-100 shadow-xl transition-transform duration-300 hover:-translate-y-2">
    <a href="<?php the_permalink(); ?>" class="group block">
        <figure class="relative">
            <?php echo woocommerce_get_product_thumbnail(); // Affiche l'image du produit ?>

            <?php if ( $product->is_on_sale() ) : ?>
                <div class="badge badge-error absolute top-4 right-4 font-bold">PROMO</div>
            <?php endif; ?>
        </figure>
        <div class="card-body p-4 text-center">
            <?php
            // Pour la marque, on affiche la première catégorie en attendant un vrai système de marques
            $category_list = wc_get_product_category_list( $product->get_id(), ', ', '<p class="text-xs text-base-content/60 mb-1">', '</p>' );
            echo $category_list;
            ?>
            <h3 class="card-title text-sm font-bold truncate justify-center">
                <?php the_title(); ?>
            </h3>
            <p class="text-lg font-bold text-primary mt-2">
                <?php echo $product->get_price_html(); // Gère le prix simple et le prix en promo ?>
            </p>
        </div>
    </a>
    <div class="card-actions justify-center pb-4">
        <?php woocommerce_template_loop_add_to_cart(); // Affiche le bouton "Ajouter au panier" ?>
    </div>
</div>
