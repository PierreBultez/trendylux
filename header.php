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
        class="bg-neutral text-neutral-content relative"
        x-data="{ openMenu: null }"
        @keydown.escape.window="openMenu = null"
>
    <!-- Section principale du header : Logo, Recherche, Icônes -->
    <div class="container mx-auto">
        <div class="flex justify-between items-center py-3">

            <!-- Logo (conservé de l'original) -->
            <div class="navbar-start">
                <a href="<?php echo home_url(); ?>" class="text-2xl font-serif text-primary uppercase tracking-wider transition-opacity hover:opacity-80">TrendyLux</a>
            </div>

            <!-- Barre de recherche (nouvel élément) -->
            <div class="navbar-center flex-grow max-w-xl hidden lg:block">
                <form class="w-xl" role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
                    <label class="input input-bordered flex items-center gap-2 w-full">
                        <svg class="h-[1em] opacity-50" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <g
                                    stroke-linejoin="round"
                                    stroke-linecap="round"
                                    stroke-width="2.5"
                                    fill="none"
                                    stroke="currentColor"
                            >
                                <circle cx="11" cy="11" r="8"></circle>
                                <path d="m21 21-4.3-4.3"></path>
                            </g>
                        </svg>
                        <input type="search" class="grow" placeholder="Rechercher" name="s" value="<?php echo get_search_query(); ?>" />
                        <button type="submit" class="hidden" aria-label="Lancer la recherche"></button>
                    </label>
                </form>
            </div>

            <!-- Icônes (conservées et ajoutées) -->
            <div class="navbar-end">
                <div class="flex items-center">
                    <a href="<?php echo wc_get_page_permalink( 'myaccount' ); ?>" class="btn btn-ghost btn-circle" aria-label="Mon compte">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                        </svg>
                    </a>
                    <button class="btn btn-ghost btn-circle" aria-label="Favoris">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" /></svg>
                    </button>
                    <a href="<?php echo wc_get_cart_url(); ?>" role="button" class="btn btn-ghost btn-circle" aria-label="Panier">
                        <div class="indicator">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                            <?php if ( function_exists('WC') && WC()->cart && WC()->cart->get_cart_contents_count() > 0 ) : ?>
                                <span class="badge badge-sm badge-primary indicator-item"><?php echo WC()->cart->get_cart_contents_count(); ?></span>
                            <?php endif; ?>
                        </div>
                    </a>
                     <button class="btn btn-ghost btn-circle" aria-label="Langue">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 3 2" class="w-6 h-6"><path fill="#002395" d="M0 0h3v2H0z"/><path fill="#fff" d="M1 0h2v2H1z"/><path fill="#ED2939" d="M2 0h1v2H2z"/></svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Menu de navigation (déplacé) -->
    <div class="border-t border-secondary">
        <div class="container mx-auto">
            <div class="navbar-center hidden lg:flex justify-center">
                 <?php
                 wp_nav_menu([
                         'theme_location' => 'primary_menu',
                         'container'      => false,
                         'menu_class'     => 'menu menu-horizontal px-1 font-bold text-sm',
                         'items_wrap'     => '<ul id="%1$s" class="%2$s" @mouseleave="openMenu = null">%3$s</ul>',
                         'walker'         => new TRENDYLUX_Nav_Walker(),
                 ]);
                ?>
            </div>
        </div>
    </div>
</header>
