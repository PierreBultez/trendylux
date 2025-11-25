<?php
if ( ! defined( 'ABSPATH' ) ) exit;

global $product;

if ( empty( $product ) || ! $product->is_visible() ) return;

// On récupère la première catégorie comme "Marque"
$brand = '';
$terms = get_the_terms( $product->get_id(), 'product_cat' );
if ( $terms && ! is_wp_error( $terms ) ) {
    $brand = $terms[0]->name;
}
?>

<!-- MODIFIÉ : Ajout de rounded-box et overflow-hidden pour appliquer le radius à l'image -->
<li <?php wc_product_class( 'group relative flex flex-col h-full bg-base-100 shadow-lg hover:shadow-2xl transition-shadow duration-300 rounded-box overflow-hidden', $product ); ?>>

    <a href="<?php the_permalink(); ?>" class="block w-full h-full flex flex-col">

        <!-- Le 'overflow-hidden' du parent va maintenant arrondir les coins de l'image -->
        <div class="relative w-full aspect-[3/4] bg-gray-100">

            <div class="absolute top-2 left-2 z-10 flex flex-col gap-1">
                <?php
                $regular_price = (float) $product->get_regular_price();
                $sale_price    = (float) $product->get_sale_price();

                if ( $product->is_on_sale() && $regular_price > 0 && $sale_price > 0 && $sale_price < $regular_price ) :
                    $discount = round( ( ( $regular_price - $sale_price ) / $regular_price ) * 100 );
                    ?>
                    <!-- MODIFIÉ : Utilisation de la classe 'badge' pour hériter du bon radius -->
                    <span class="badge badge-error text-white text-[10px] font-bold uppercase tracking-wider border-0">
                        -<?php echo $discount; ?>%
                    </span>
                <?php endif; ?>

                <?php if ( $product->is_featured() ) : ?>
                    <span class="badge bg-black text-white text-[10px] font-bold uppercase tracking-wider border-0">TOP VENTES</span>
                <?php endif; ?>

                <?php if ( has_term( 'destockage', 'product_tag', $product->get_id() ) ) : ?>
                    <span class="badge badge-warning text-white text-[10px] font-bold uppercase tracking-wider border-0">DERNIÈRE CHANCE -15% SUR CERTAINES TAILLES</span>
                <?php endif; ?>
            </div>

            <button class="absolute top-2 right-2 z-10 p-2 text-gray-400 hover:text-red-500 transition-colors" aria-label="Ajouter aux favoris">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" /></svg>
            </button>

            <?php
            $image_id = $product->get_image_id();
            if ( $image_id ) {
                echo wp_get_attachment_image( $image_id, 'woocommerce_thumbnail', false, [
                        'class' => 'w-full h-full object-cover object-top transition-transform duration-500 group-hover:scale-105'
                ] );
            } else {
                echo wc_placeholder_img( 'woocommerce_thumbnail', ['class' => 'w-full h-full object-cover object-center'] );
            }
            ?>
        </div>

        <div class="pt-3 pb-4 px-4 text-left flex flex-col flex-grow">

            <div class="text-xs font-black uppercase tracking-wide text-neutral mb-1">
                <?php echo esc_html( $brand ); ?>
            </div>

            <h2 class="text-sm text-gray-600 font-normal leading-tight min-h-[2.5em] line-clamp-2 mb-2 group-hover:text-black transition-colors">
                <?php the_title(); ?>
            </h2>

            <div class="flex items-center gap-2 mt-auto">
                <?php if ( $product->is_on_sale() && $sale_price > 0 ) : ?>
                    <span class="text-sm font-bold text-error">
                        <?php echo wc_price( $sale_price ); ?>
                    </span>
                    <span class="text-xs text-gray-400 line-through">
                        <?php echo wc_price( $regular_price ); ?>
                    </span>
                <?php else : ?>
                    <span class="text-sm font-bold text-neutral">
                        <?php echo $product->get_price_html(); ?>
                    </span>
                <?php endif; ?>
            </div>
        </div>
    </a>
</li>