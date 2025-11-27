<?php

use WP_CLI\Utils;
/**
 * Script de mise à jour MASSIVE des prix.
 *
 * LOGIQUE :
 * - Si Prix Régulier < 50€  => +30% (x 1.30)
 * - Si Prix Régulier >= 50€ => +22% (x 1.22)
 *
 * UTILISATION :
 * 
 * 1. SIMULATION (Recommandé pour vérifier) :
 *    wp eval-file wp-content/themes/trendylux/update-prices-mass.php
 * 
 * 2. APPLICATION RÉELLE (Irréversible) :
 *    wp eval-file wp-content/themes/trendylux/update-prices-mass.php --run
 */

if ( ! defined( 'WP_CLI' ) || ! WP_CLI ) {
    echo "Ce script doit être lancé via WP-CLI.\n";
    exit;
}

// Vérification du mode : Simulation ou Réel
$is_dry_run = true;
// On vérifie si l'argument "run" est présent dans les arguments passés au script
if ( isset( $args ) && ( in_array( 'run', $args ) || in_array( '--run', $args ) ) ) {
    $is_dry_run = false;
}

WP_CLI::line( '------------------------------------------------' );
if ( $is_dry_run ) {
    WP_CLI::line( 'MODE : SIMULATION (Aucun changement ne sera enregistré)' );
    WP_CLI::line( 'Pour appliquer les changements, lancez :' );
    WP_CLI::line( 'wp eval-file wp-content/themes/trendylux/update-prices-mass.php run' );
} else {
    WP_CLI::warning( 'MODE : APPLICATION RÉELLE' );
    WP_CLI::warning( 'Attention, les prix vont être modifiés définitivement.' );
    WP_CLI::warning( '3 secondes pour annuler (CTRL+C)...' );
    sleep(3);
}
WP_CLI::line( '------------------------------------------------' );

// Récupération de tous les produits (Parents uniquement, on descendra dans les variations après)
$query_args = array(
    'post_type'      => 'product',
    'post_status'    => 'publish',
    'posts_per_page' => -1,
    'fields'         => 'ids',
);

WP_CLI::line( 'Récupération du catalogue...' );
$query = new WP_Query( $query_args );
$product_ids = $query->posts;
$count = count( $product_ids );

WP_CLI::line( sprintf( '%d produits parents trouvés.', $count ) );

$progress = \WP_CLI\Utils\make_progress_bar( 'Traitement', $count );
$updated_count = 0;
$preview_data = []; // Pour afficher un tableau d'exemple en mode dry-run

foreach ( $product_ids as $parent_id ) {
    $product = wc_get_product( $parent_id );
    if ( ! $product ) {
        $progress->tick();
        continue;
    }

    // Liste des objets à traiter (soit le produit simple, soit ses variations)
    $products_to_process = [];

    if ( $product->is_type( 'variable' ) ) {
        $children_ids = $product->get_children();
        foreach ( $children_ids as $child_id ) {
            $child = wc_get_product( $child_id );
            if ( $child ) $products_to_process[] = $child;
        }
    } else {
        $products_to_process[] = $product;
    }

    // Traitement de chaque entité (Simple ou Variation)
    foreach ( $products_to_process as $item ) {
        $old_price = (float) $item->get_regular_price();

        // Sécurité : on ne touche pas aux prix nuls ou vides
        if ( $old_price <= 0 ) continue;

        // --- LOGIQUE DE PRIX ---
        $multiplier = ($old_price < 50) ? 1.30 : 1.22;
        $new_price = round( $old_price * $multiplier, 2 );

        if ( $is_dry_run ) {
            // En mode simulation, on stocke juste pour affichage
            if ( count($preview_data) < 20 ) { // On affiche que les 20 premiers
                $preview_data[] = [
                    'ID' => $item->get_id(),
                    'Name' => substr($item->get_name(), 0, 30) . '...',
                    'Old' => $old_price . '€',
                    'New' => $new_price . '€',
                    'Rule' => ($multiplier == 1.30) ? '+30%' : '+22%'
                ];
            }
        } else {
            // En mode réel, on sauvegarde
            try {
                $item->set_regular_price( $new_price );
                // On laisse WooCommerce recalculer le prix actif (_price) lors de la sauvegarde
                // (Cela gère correctement le cas où un sale_price existe)
                $item->save();
                $updated_count++;
            } catch ( Exception $e ) {
                WP_CLI::warning( 'Erreur ID ' . $item->get_id() . ': ' . $e->getMessage() );
            }
        }
    }

    // Nettoyage mémoire périodique
    if ( $parent_id % 50 === 0 ) {
        if ( function_exists( 'wp_cache_flush' ) ) wp_cache_flush();
    }

    $progress->tick();
}

$progress->finish();

if ( $is_dry_run ) {
    WP_CLI::line( "\n--- APERÇU DES CHANGEMENTS (20 premiers) ---" );
    
    // En-tête du tableau
    WP_CLI::line( sprintf( "%s %s %s %s %s", 'ID', 'PRODUIT', 'ANCIEN', 'NOUVEAU', 'REGLE' ) );
    WP_CLI::line( str_repeat( '-', 90 ) );

    foreach ( $preview_data as $item ) {
        WP_CLI::line( sprintf( 
            "%-8s %-40s %-12s %-12s %-10s", 
            $item['ID'], 
            $item['Name'], 
            $item['Old'], 
            $item['New'], 
            $item['Rule']
        ) );
    }
    
    WP_CLI::line( str_repeat( '-', 90 ) );
    WP_CLI::success( "Simulation terminée. Pour appliquer, ajoutez --run" );
} else {
    WP_CLI::success( sprintf( "Mise à jour terminée ! %d prix modifiés (déclinaisons incluses).", $updated_count ) );
}
