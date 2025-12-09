<!DOCTYPE html>
<html <?php language_attributes(); ?> data-theme="trendylux">
<head>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Mrs+Saint+Delafield&family=Noto+Sans:wght@400;700&family=Noto+Serif:wght@700&display=swap" rel="stylesheet">

    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <?php wp_head(); ?>
</head>

<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-653RV0LZWT"></script>
<script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'G-653RV0LZWT');
</script>

<body <?php body_class( 'font-sans bg-base-100 text-base-content' ); ?>>

<header
        class="bg-neutral text-neutral-content relative"
        x-data="{ openMenu: null }"
        @keydown.escape.window="openMenu = null"
>
    <!-- Logo (Overlay Absolute) -->
    <a href="<?php echo home_url(); ?>" class="absolute top-0 bottom-0 left-16 lg:left-4 z-50 flex items-center py-2 transition duration-300 logo-glow-gold">
        <img src="<?php echo get_template_directory_uri(); ?>/public/logo-trendy-lux.svg" alt="TrendyLux" class="h-full w-auto object-contain">
    </a>

    <!-- Section principale du header : Logo, Recherche, Icônes -->
    <div class="container mx-auto">
        <div class="flex justify-between items-center py-3">

            <!-- Navbar Start : Tagline uniquement (Logo en absolute) -->
            <div class="navbar-start">
                <!-- Mobile Hamburger Menu -->
                <div class="dropdown lg:hidden">
                    <div tabindex="0" role="button" class="btn btn-ghost btn-circle" aria-label="Menu">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" /></svg>
                    </div>
                    <div tabindex="0" class="dropdown-content z-[50] menu p-2 shadow bg-base-100 rounded-box w-[92vw] max-w-sm mt-3 fixed inset-x-4 top-28 h-[calc(100vh-140px)] overflow-y-auto border border-base-200 text-base-content !translate-x-0">
                        <!-- Barre de recherche Mobile -->
                        <div class="mb-4 p-2">
                            <form role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
                                <label class="input input-bordered flex items-center gap-2 w-full">
                                    <svg class="h-[1em] opacity-50" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                        <g stroke-linejoin="round" stroke-linecap="round" stroke-width="2.5" fill="none" stroke="currentColor">
                                            <circle cx="11" cy="11" r="8"></circle>
                                            <path d="m21 21-4.3-4.3"></path>
                                        </g>
                                    </svg>
                                    <input type="search" class="grow" placeholder="Rechercher" name="s" value="<?php echo get_search_query(); ?>" />
                                    <button type="submit" class="hidden" aria-label="Lancer la recherche"></button>
                                </label>
                            </form>
                        </div>
                        <?php
                        wp_nav_menu([
                            'theme_location' => 'primary_menu',
                            'container'      => false,
                            'menu_class'     => 'menu w-full',
                            'walker'         => new TRENDYLUX_Mobile_Walker(),
                        ]);
                        ?>
                    </div>
                </div>

                <span class="hidden xl:inline-block text-2xl text-primary -mb-1 text-glow-gold xl:ml-48" style="font-family: 'Mrs Saint Delafield', cursive;">Choose your style... Be trendy !</span>
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
                        <input type="search" class="grow text-primary-content" placeholder="Rechercher" name="s" value="<?php echo get_search_query(); ?>" />
                        <button type="submit" class="hidden" aria-label="Lancer la recherche"></button>
                    </label>
                </form>
            </div>

            <!-- Icônes (conservées et ajoutées) -->
            <div class="navbar-end">
                <div class="flex items-center gap-2">

                    <?php if ( is_user_logged_in() ) : ?>
                        <!-- CAS CONNECTÉ : Dropdown Menu -->
                        <div class="dropdown dropdown-end">
                            <!-- AJOUT DE 'overflow-visible' pour ne pas couper la pastille -->
                            <div tabindex="0" role="button" class="btn btn-ghost btn-circle overflow-visible" aria-label="Mon profil">
                                <div class="indicator">
                                    <!-- Heroicon: User -->
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                    </svg>

                                    <!-- Pastille positionnée en haut à droite -->
                                    <span class="badge badge-xs badge-primary indicator-item border border-base-100"></span>
                                </div>
                            </div>
                            <ul tabindex="0" class="menu menu-sm dropdown-content mt-3 z-[50] p-2 shadow-lg bg-base-100 rounded-box fixed inset-x-4 top-28 w-auto !translate-x-0 md:absolute md:inset-auto md:right-0 md:top-full md:w-52 border border-base-200 text-base-content">
                                <!-- AJOUT DE 'text-base-content' ci-dessus pour forcer le texte sombre -->

                                <li>
                                    <a href="<?php echo esc_url( wc_get_account_endpoint_url( 'dashboard' ) ); ?>" class="font-medium">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-2"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" /></svg>
                                        Tableau de bord
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo esc_url( wc_get_account_endpoint_url( 'orders' ) ); ?>" class="font-medium">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" /></svg>
                                        Mes commandes
                                    </a>
                                </li>
                                <li class="border-t border-base-200 mt-1 pt-1">
                                    <a href="<?php echo esc_url( wp_logout_url( home_url() ) ); ?>" class="text-error hover:bg-error/10 font-medium">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" /></svg>
                                        Déconnexion
                                    </a>
                                </li>
                            </ul>
                        </div>
                    <?php else : ?>
                        <!-- CAS DÉCONNECTÉ -->
                        <div class="tooltip tooltip-bottom" data-tip="Connexion">
                            <a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" class="btn btn-ghost btn-circle" aria-label="Se connecter">
                                <!-- Heroicon: User -->
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                </svg>
                            </a>
                        </div>
                    <?php endif; ?>

                    <!-- FAVORIS -->
                    <a href="<?php echo function_exists('tinv_url_wishlist_default') ? esc_url(tinv_url_wishlist_default()) : '#'; ?>" class="btn btn-ghost btn-circle" aria-label="Favoris">
                        <!-- Heroicon: Heart -->
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                        </svg>
                    </a>

                    <!-- PANIER (Mini Cart Dropdown) -->
                    <div class="dropdown dropdown-end">
                        <!-- Le bouton déclencheur (Icone) -->
                        <div tabindex="0" role="button" class="btn btn-ghost btn-circle overflow-visible" aria-label="Panier">
                            <div class="indicator">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                                </svg>

                                <?php
                                $count = WC()->cart->get_cart_contents_count();
                                // On affiche le badge seulement s'il y a des articles, ou on le laisse vide
                                if ( $count > 0 ) : ?>
                                    <span class="badge badge-sm badge-primary indicator-item text-primary-content border-none">
                                        <?php echo esc_html( $count ); ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Le contenu du Dropdown (Mini Cart) -->
                        <div tabindex="0" class="card card-compact dropdown-content bg-base-100 z-[50] mt-3 shadow-xl border border-base-200 text-base-content fixed inset-x-4 top-28 w-auto !translate-x-0 md:absolute md:inset-auto md:right-0 md:top-full md:w-96">
                            <div class="card-body">
                                <div class="widget_shopping_cart_content">
                                    <?php
                                    /**
                                     * Cette fonction charge le template 'cart/mini-cart.php'.
                                     * C'est indispensable pour que l'AJAX fonctionne.
                                     */
                                    woocommerce_mini_cart();
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- LANGUE (Ton icône personnalisée conservée) -->
                    <button class="btn btn-ghost btn-circle" aria-label="Langue">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 3 2" class="w-6 h-6 rounded shadow-sm">
                            <path fill="#002395" d="M0 0h3v2H0z"/>
                            <path fill="#fff" d="M1 0h2v2H1z"/>
                            <path fill="#ED2939" d="M2 0h1v2H2z"/>
                        </svg>
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

<div class="container mx-auto px-4 toast-container woocommerce-notices-wrapper">
    <?php wc_print_notices(); ?>
</div>
