<!DOCTYPE html>
<html <?php language_attributes(); ?> data-theme="trendylux">
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:wght@400;700&family=Noto+Serif:wght@700&display=swap" rel="stylesheet">

    <?php wp_head(); ?>
</head>
<body <?php body_class( 'font-sans bg-base-100 text-base-content' ); ?>>

<header
        class="bg-neutral text-neutral-content"
        x-data="{ openMenu: null }"
        @keydown.escape.window="openMenu = null"
        @click.outside="openMenu = null"
>
    <div class="container mx-auto">
        <div class="navbar">
            <div class="navbar-start">
                <a href="<?php echo home_url(); ?>" class="btn btn-ghost text-2xl font-serif text-primary uppercase tracking-wider">TrendyLux</a>
            </div>

            <!-- Menu pour grand écran -->
            <div class="navbar-center hidden lg:flex">
                <?php
                wp_nav_menu([
                        'theme_location' => 'primary_menu',
                        'container'      => false,
                        'menu_class'     => 'menu menu-horizontal px-1 font-bold text-sm',
                        'walker'         => new TRENDYLUX_Nav_Walker(),
                ]);
                ?>
            </div>

            <div class="navbar-end">
                <button class="btn btn-ghost btn-circle" aria-label="Recherche">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                </button>
                <button class="btn btn-ghost btn-circle" aria-label="Favoris">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" /></svg>
                </button>
                <div class="dropdown dropdown-end">
                    <div tabindex="0" role="button" class="btn btn-ghost btn-circle" aria-label="Panier">
                        <div class="indicator">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                            <span class="badge badge-sm badge-primary indicator-item">8</span>
                        </div>
                    </div>
                    <div tabindex="0" class="mt-3 z-[1] card card-compact dropdown-content w-52 bg-neutral text-neutral-content shadow">
                        <div class="card-body">
                            <span class="font-bold text-lg">8 Articles</span>
                            <span class="text-info">Sous-total: 999€</span>
                            <div class="card-actions">
                                <button class="btn btn-primary btn-block">Voir le panier</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
