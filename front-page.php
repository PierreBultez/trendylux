<?php get_header(); ?>

<main>
    <!-- 1. HERO SECTION (Full Viewport minus Header approx) -->
    <!-- On ajuste la hauteur pour que le bas de cette section corresponde au bas de l'écran -->
    <!-- Header est approx 80-100px. On vise calc(100vh - 100px) pour la zone principale -->
    <div class="flex flex-col h-[calc(100vh-64px)] md:h-[calc(100vh-80px)] mb-12 p-4 gap-4">

        <!-- Bandeau Promo Horizontal (Fixe) -->
        <div class="relative h-12 md:h-16 flex-none w-full bg-primary text-primary-content flex items-center justify-center shadow-md rounded-box overflow-hidden">
            <!-- Image de fond subtile pour la texture -->
            <img src="<?php echo get_template_directory_uri(); ?>/public/hero-promo/AdobeStock_607063407_Preview.jpeg" class="absolute inset-0 w-full h-full object-cover opacity-40 mix-blend-overlay" alt="">
            
            <div class="relative z-10 flex flex-row items-center gap-2 text-center">
                <span class="text-xs md:text-base font-bold uppercase tracking-wider text-white drop-shadow-md">
                    Ouverture officielle, -10% sur tout le site avec le code promo 
                    <span class="bg-white text-primary px-2 py-1 rounded mx-1 font-serif font-black text-sm md:text-lg shadow-sm">VIP10</span>
                </span>
            </div>
        </div>

        <!-- Grille Hero (Reste de la hauteur) -->
        <div class="flex-grow relative grid grid-cols-[1fr_2fr_1fr] md:grid-cols-[1fr_2fr_1fr] gap-4 overflow-hidden">
            
            <?php
            // Logique de récupération des images slider
            $slider_dir = get_template_directory() . '/public/hero-slider/';
            $slider_url = get_template_directory_uri() . '/public/hero-slider/';
            $all_files = glob( $slider_dir . '*.{jpg,jpeg,png,webp}', GLOB_BRACE );
            
            if ( ! $all_files ) $all_files = [];
            $filenames = array_map( 'basename', $all_files );
            if ( ! empty($filenames) ) shuffle( $filenames );

            // Fonction pour assurer assez d'images pour le scroll
            $ensure_loop = function($arr) {
                if (empty($arr)) return [];
                // Minimum 4 pour la hauteur
                while(count($arr) < 4) $arr = array_merge($arr, $arr); 
                // Doubler pour le loop infini
                return array_merge($arr, $arr); 
            };

            $half = ceil( count( $filenames ) / 2 );
            $left_base = array_slice( $filenames, 0, $half );
            $right_base = array_slice( $filenames, $half );

            $images_left = $ensure_loop($left_base);
            $images_right = $ensure_loop($right_base);
            ?>

            <!-- Colonne Gauche : Défilement VERS LE HAUT -->
            <div class="relative overflow-hidden h-full hidden md:block">
                <div class="animate-scroll-up w-full">
                    <?php foreach($images_left as $img): ?>
                        <div class="w-full aspect-[3/4] relative rounded-box overflow-hidden mb-4">
                            <img src="<?php echo esc_url( $slider_url . $img ); ?>" class="w-full h-full object-cover block" alt="Mode">
                            <div class="absolute inset-0 bg-black/20"></div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
             <!-- Version mobile colonne gauche -->
             <div class="relative overflow-hidden h-full md:hidden rounded-box">
                 <?php if (!empty($images_left)): ?>
                    <img src="<?php echo esc_url( $slider_url . $images_left[0] ); ?>" class="w-full h-full object-cover opacity-50" alt="">
                 <?php endif; ?>
             </div>


            <!-- Colonne Centrale : Image Fixe + CTA -->
            <div class="relative h-full w-full overflow-hidden group flex items-end justify-center pb-20 rounded-box">
                <img src="<?php echo get_template_directory_uri(); ?>/public/hero.jpg" class="absolute inset-0 w-full h-full object-cover transition-transform duration-[10s] group-hover:scale-105" alt="Nouvelle Collection">
                
                <!-- Overlay Gradient -->
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-black/30"></div>

                <!-- Contenu Central -->
                <div class="relative z-10 text-center px-4 max-w-2xl mx-auto">
                    <h1 class="text-5xl md:text-7xl lg:text-8xl font-serif font-bold text-white mb-2 uppercase tracking-tighter drop-shadow-2xl">
                        Trendy Lux
                    </h1>
                    <p class="text-xl md:text-2xl text-gray-200 mb-8 font-light tracking-[0.2em] uppercase border-t border-b border-white/30 py-2 inline-block">
                        Élégance Intemporelle
                    </p>
                    <div>
                        <a href="<?php echo get_permalink( wc_get_page_id( 'shop' ) ); ?>" class="btn btn-primary btn-lg rounded-full px-10 text-lg shadow-[0_0_30px_rgba(212,175,55,0.4)] border-white/20 hover:scale-105 transition-transform">
                            Découvrir
                        </a>
                    </div>
                </div>
            </div>

            <!-- Colonne Droite : Défilement VERS LE BAS -->
            <div class="relative overflow-hidden h-full hidden md:block">
                <div class="animate-scroll-down w-full -translate-y-1/2"> 
                     <?php foreach($images_right as $img): ?>
                        <div class="w-full aspect-[3/4] relative rounded-box overflow-hidden mb-4">
                            <img src="<?php echo esc_url( $slider_url . $img ); ?>" class="w-full h-full object-cover block" alt="Accessoires">
                            <div class="absolute inset-0 bg-black/20"></div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <!-- Version mobile colonne droite -->
             <div class="relative overflow-hidden h-full md:hidden rounded-box">
                 <?php if (!empty($images_right)): ?>
                    <img src="<?php echo esc_url( $slider_url . $images_right[0] ); ?>" class="w-full h-full object-cover opacity-50" alt="">
                 <?php endif; ?>
             </div>

        </div>
    </div>

    <!-- 1b. Slider Marques (Marquee) -->
    <div class="bg-white border-y border-base-200 overflow-hidden py-8 relative z-10">
        <h2 class="text-center text-xl md:text-2xl font-serif uppercase tracking-widest mb-8 px-4">
            Choisis parmi les plus grandes Marques et affirme ton style … <span class="text-primary block md:inline mt-2 md:mt-0" style="font-family: 'Mrs Saint Delafield', cursive; text-transform: none; font-size: 1.5em;">Be Trendy</span>
        </h2>
        <div class="animate-marquee flex items-center">
            <?php 
            $brand_dir = get_template_directory() . '/public/brand-slider/';
            $brand_url = get_template_directory_uri() . '/public/brand-slider/';
            $brand_files = glob( $brand_dir . '*.svg' );
            
            if ( ! $brand_files ) $brand_files = [];
            $brand_filenames = array_map( 'basename', $brand_files );
            
            if ( ! empty( $brand_filenames ) ) {
                // Mélanger pour l'aléatoire à chaque chargement
                shuffle( $brand_filenames );
                
                // Doubler la liste pour la boucle infinie
                $display_brands = array_merge( $brand_filenames, $brand_filenames );
                
                foreach ( $display_brands as $brand_file ): ?>
                    <img 
                        src="<?php echo esc_url( $brand_url . $brand_file ); ?>" 
                        alt="<?php echo esc_attr( pathinfo( $brand_file, PATHINFO_FILENAME ) ); ?>" 
                        class="h-10 md:h-14 w-auto mx-8 md:mx-12 object-contain opacity-80 hover:opacity-100 transition-opacity"
                    >
                <?php endforeach; 
            }
            ?>
        </div>
    </div>

    <!-- 2. Blocs Catégories Principales -->
    <div class="container mx-auto py-12 px-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <a href="#" class="group relative block">
                <div class="hero h-96 rounded-box overflow-hidden">
                    <img src="https://picsum.photos/id/1025/800/600" alt="Nouveautés" class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105">
                    <div class="hero-overlay bg-black/40 group-hover:bg-black/50 transition-colors"></div>
                    <div class="hero-content text-center text-neutral-content">
                        <h2 class="text-4xl font-bold font-serif uppercase">Nouveautés</h2>
                    </div>
                </div>
            </a>
            <a href="#" class="group relative block">
                <div class="hero h-96 rounded-box overflow-hidden">
                    <img src="https://picsum.photos/id/237/800/600" alt="Promos" class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105">
                    <div class="hero-overlay bg-red-800/40 group-hover:bg-red-800/50 transition-colors"></div>
                    <div class="hero-content text-center text-neutral-content">
                        <div>
                            <h2 class="text-4xl font-bold font-serif uppercase">Promos</h2>
                            <p class="text-2xl font-bold">Jusqu'à -50%</p>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <!-- 3. Grille de Catégories Secondaires -->
    <div class="container mx-auto py-12 px-4">
        <h2 class="text-3xl font-bold text-center mb-8 uppercase font-serif">Top Catégories</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <?php for ($i = 0; $i < 8; $i++): ?>
                <a href="#" class="group relative block">
                    <img src="https://picsum.photos/id/<?php echo 100 + $i; ?>/400/400" alt="Catégorie" class="w-full h-full object-cover rounded-box">
                    <div class="absolute inset-0 bg-black/40 rounded-box flex items-center justify-center group-hover:bg-black/20 transition-colors">
                        <h3 class="text-white font-bold text-xl uppercase">Catégorie <?php echo $i + 1; ?></h3>
                    </div>
                </a>
            <?php endfor; ?>
        </div>
    </div>

    <!-- 4. Section "Les Styles Du Moment" -->
    <div class="container mx-auto py-12 px-4">
        <h2 class="text-3xl font-bold text-center mb-8 uppercase font-serif">Les Styles Du Moment</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Style 1 -->
            <a href="#" class="group card card-compact image-full shadow-xl">
                <figure class="overflow-hidden">
                    <img src="https://picsum.photos/id/1084/800/600" alt="Style 1" class="transition-transform duration-500 group-hover:scale-110" />
                </figure>
                <div class="card-body justify-end items-center text-center">
                    <div class="bg-black/30 backdrop-blur-sm p-4 rounded-box mb-10">
                        <h3 class="card-title text-3xl font-serif uppercase">Streetwear</h3>
                        <div class="card-actions mt-4 justify-center">
                            <button class="btn btn-primary btn-outline btn-sm">Découvrir</button>
                        </div>
                    </div>
                </div>
            </a>
            <!-- Style 2 -->
            <a href="#" class="group card card-compact image-full shadow-xl">
                <figure class="overflow-hidden">
                    <img src="https://picsum.photos/id/155/800/600" alt="Style 2" class="transition-transform duration-500 group-hover:scale-110" />
                </figure>
                <div class="card-body justify-end items-center text-center">
                    <div class="bg-black/30 backdrop-blur-sm p-4 rounded-box mb-10">
                        <h3 class="card-title text-3xl font-serif uppercase">Casual Chic</h3>
                        <div class="card-actions mt-4 justify-center">
                            <button class="btn btn-primary btn-outline btn-sm">Découvrir</button>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <!-- 5. Carousel de Produits (Top Ventes) -->
    <div class="container mx-auto py-12 px-4">
        <h2 class="text-3xl font-bold text-center mb-8 uppercase font-serif">Top Ventes</h2>
        <div class="carousel carousel-center w-full p-4 space-x-4 bg-base-200 rounded-box">
            <?php for ($i = 0; $i < 10; $i++): ?>
                <div class="carousel-item w-64 md:w-72">
                    <div class="card bg-base-100 shadow-xl transition-transform duration-300 hover:-translate-y-2">
                        <a href="#" class="group block">
                            <figure class="relative">
                                <img src="https://picsum.photos/id/<?php echo 200 + $i; ?>/400/300" alt="Produit" class="transition-opacity duration-300 group-hover:opacity-80" />
                                <?php if ($i % 3 == 0): // On ajoute un badge sur certains produits pour l'exemple ?>
                                    <div class="badge badge-error absolute top-4 right-4 font-bold">-30%</div>
                                <?php endif; ?>
                            </figure>
                            <div class="card-body p-4">
                                <p class="text-xs text-base-content/60 mb-1">Marque</p>
                                <h3 class="card-title text-sm font-bold truncate">
                                    Nom du produit qui peut être assez long
                                </h3>
                                <p class="text-lg font-bold text-primary mt-2">99.99€</p>
                            </div>
                        </a>
                    </div>
                </div>
            <?php endfor; ?>
        </div>
    </div>

</main>

<?php get_footer(); ?>
