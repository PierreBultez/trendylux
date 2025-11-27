<?php
/**
 * Script de mise à jour massive des statuts de destockage.
 * 
 * Utilisation :
 * - Nettoyer les produits marqués "destockage" (par défaut) :
 *   wp eval-file wp-content/themes/trendylux/update-destock.php
 * 
 * - Vérifier TOUS les produits (plus long, pour trouver des oublis) :
 *   wp eval-file wp-content/themes/trendylux/update-destock.php --all
 */

if ( ! defined( 'WP_CLI' ) || ! WP_CLI ) {
    echo "Ce script doit être lancé via WP-CLI.\n";
    exit;
}

WP_CLI::line( '------------------------------------------------' );

WP_CLI::line( 'Mode : SCAN COMPLET (Tous les produits publiés)' );

WP_CLI::line( '------------------------------------------------' );

// 1. Construction de la requête
$query_args = array(
    'post_type'      => 'product',
    'post_status'    => 'publish',
    'posts_per_page' => -1,
    'fields'         => 'ids',
);

WP_CLI::line( 'Récupération des produits...' );

$query = new WP_Query( $query_args );
$product_ids = $query->posts;
$count = count( $product_ids );

WP_CLI::line( sprintf( '%d produits trouvés à traiter.', $count ) );

if ( $count === 0 ) {
    WP_CLI::success( 'Aucun produit à traiter.' );
    exit;
}

$progress = \WP_CLI\Utils\make_progress_bar( 'Traitement des produits', $count );

$updated = 0;
$errors = 0;

foreach ( $product_ids as $product_id ) {
    try {
        // Appel direct de votre fonction définie dans functions.php
        if ( function_exists( 'trendylux_update_destock_status' ) ) {
            trendylux_update_destock_status( $product_id );
            $updated++;
        } else {
            WP_CLI::error( 'La fonction trendylux_update_destock_status n\'existe pas. Vérifiez functions.php.' );
            exit;
        }
    } catch ( Exception $e ) {
        WP_CLI::warning( sprintf( 'Erreur sur le produit %d : %s', $product_id, $e->getMessage() ) );
        $errors++;
    }

    $progress->tick();
    
    // Petit nettoyage mémoire tous les 100 items
    if ( $updated % 100 === 0 ) {
        if ( function_exists( 'wp_cache_flush' ) ) {
            wp_cache_flush();
        }
    }
}

$progress->finish();

WP_CLI::success( sprintf( 'Terminé ! %d produits vérifiés et mis à jour. %d erreurs.', $updated, $errors ) );

