<?php get_header(); ?>

<main>
    <!-- 1. HERO SECTION (Full Viewport minus Header approx) -->
    <!-- On ajuste la hauteur pour que le bas de cette section corresponde au bas de l'écran -->
    <!-- Header est approx 80-100px. On vise calc(100vh - 100px) pour la zone principale -->
    <div class="flex flex-col h-[calc(100vh-64px)] md:h-[calc(100vh-80px)] mb-12 p-4 gap-4">

        <!-- Bandeau Promo Horizontal (Fixe) -->
        <div class="relative py-4 flex-none w-full bg-primary text-primary-content flex flex-col items-center justify-center shadow-md rounded-box overflow-hidden">
            <!-- Image de fond subtile pour la texture -->
            <img src="<?php echo get_template_directory_uri(); ?>/public/hero-promo/AdobeStock_607063407_Preview.jpeg" class="absolute inset-0 w-full h-full object-cover opacity-40 mix-blend-overlay" alt="">
            
            <div class="relative z-10 flex flex-col items-center text-center gap-1">
                <span class="text-base md:text-lg font-bold uppercase tracking-wider text-white drop-shadow-md">Ouverture officielle</span>
                <span class="text-sm md:text-base font-bold uppercase tracking-wider text-white drop-shadow-md">
                    -10% sur tout le site avec le code promo 
                    <span class="bg-white text-primary px-2 py-1 rounded mx-1 font-serif font-black text-sm md:text-lg shadow-sm">VIP10</span>*
                </span>
                <span class="text-sm md:text-base font-bold uppercase tracking-wider text-white drop-shadow-md">Livraison offerte**</span>
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
            <a href="<?php echo site_url('/collab-makai'); ?>" class="group relative block">
                <div class="hero h-96 rounded-box overflow-hidden">
                    <img src="<?php echo get_template_directory_uri(); ?>/public/makai.jpeg" alt="Collab Makaï x Trendy Lux" class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105">
                    <div class="hero-content text-center text-neutral-content">
                        <div class="bg-black/30 backdrop-blur-sm p-4 rounded-box">
                            <h2 class="text-5xl font-bold font-serif uppercase text-primary">Collab's</h2>
                        </div>
                    </div>
                </div>
            </a>
            <a href="/categorie-produit/ventes-flash/" class="group relative block">
                <div class="hero h-96 rounded-box overflow-hidden">
                    <img src="<?php echo get_template_directory_uri(); ?>/public/home-flash-sales.jpeg" alt="Ventes Flash" class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105">
                    <div class="hero-content text-center text-neutral-content">
                        <div class="bg-black/30 backdrop-blur-sm p-4 rounded-box">
                            <h2 class="text-5xl font-bold font-serif uppercase text-primary">Ventes Flash</h2>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <!-- 3. Grille de Catégories Secondaires -->
    <div class="container mx-auto py-12 px-4 max-w-7xl">
        <h2 class="text-3xl font-bold text-center mb-8 uppercase font-serif">Top Catégories</h2>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 auto-rows-[250px]">
            <?php
            // DÉFINITION MANUELLE DES CATÉGORIES (SLUGS + TITRES PERSONNALISÉS)
            // Format: 'slug' => 'Titre personnalisé' (ou null pour utiliser le nom de la catégorie par défaut)
            $category_config = [
                'femme'             => 'Tout l\'univers pour dames',            // #0: Grande largeur haut
                'chaussures-femme'  => 'Les chaussures de Madame',            // #1: Large gauche
                'homme'             => 'L\'univers des hommes',            // #2: Carré centre
                'chaussures-homme'  => 'Sneakers & Chaussures homme',   // #3: Vertical droite
                'accessoires-femme' => 'Accessoires Femme',      // #4: Carré bas gauche
                'accessoires-homme' => 'Accessoires Homme',                    // #5: Large bas centre (Titre par défaut)
            ];

            // On récupère les objets catégories correspondants
            $target_slugs = array_keys($category_config);
            $bento_cats = [];
            
            foreach ($target_slugs as $slug) {
                $term = get_term_by('slug', $slug, 'product_cat');
                if ($term) {
                    $bento_cats[] = $term;
                }
            }

            // Fallback: Si on a moins de 6 catégories valides, on complète avec des catégories populaires
            if (count($bento_cats) < 6) {
                $needed = 6 - count($bento_cats);
                // On exclut ceux déjà trouvés
                $exclude_ids = array_map(function($t) { return $t->term_id; }, $bento_cats);
                
                $extras = get_terms([
                    'taxonomy'   => 'product_cat',
                    'number'     => $needed,
                    'exclude'    => $exclude_ids,
                    'hide_empty' => true,
                    'orderby'    => 'count',
                    'order'      => 'DESC'
                ]);
                
                if (!is_wp_error($extras) && !empty($extras)) {
                    $bento_cats = array_merge($bento_cats, $extras);
                }
            }

            if ( ! empty( $bento_cats ) ) :
                $i = 0;
                foreach ( $bento_cats as $cat ) :
                    if ($i >= 6) break; // Sécurité max 6 items

                    $thumbnail_id = get_term_meta( $cat->term_id, 'thumbnail_id', true );
                    $image_url = wp_get_attachment_url( $thumbnail_id );
                    if ( ! $image_url ) {
                        $image_url = wc_placeholder_img_src();
                    }
                    $cat_link = get_term_link( $cat );

                    // Détermination du nom à afficher
                    $display_name = $cat->name;
                    if ( isset($category_config[$cat->slug]) && !empty($category_config[$cat->slug]) ) {
                        $display_name = $category_config[$cat->slug];
                    }

                    // Bento Grid Classes based on 4-column layout
                    $bento_class = 'md:col-span-1 md:row-span-1'; // Default
                    
                    switch ($i) {
                        case 0:
                            $bento_class = 'md:col-span-4 md:row-span-1'; // Top Full Width
                            break;
                        case 1:
                            $bento_class = 'md:col-span-2 md:row-span-1'; // Middle Left Wide
                            break;
                        case 2:
                            $bento_class = 'md:col-span-1 md:row-span-1'; // Middle Center Square
                            break;
                        case 3:
                            $bento_class = 'md:col-span-1 md:row-span-2'; // Right Vertical Tall
                            break;
                        case 4:
                            $bento_class = 'md:col-span-1 md:row-span-1'; // Bottom Left Square
                            break;
                        case 5:
                            $bento_class = 'md:col-span-2 md:row-span-1'; // Bottom Center Wide
                            break;
                    }
                    ?>
                    <a href="<?php echo esc_url( $cat_link ); ?>" class="group relative block overflow-hidden rounded-box <?php echo $bento_class; ?> h-full">
                        <img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( $display_name ); ?>" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent flex items-end p-6 transition-colors">
                            <h3 class="text-white font-bold text-2xl uppercase drop-shadow-md transform translate-y-2 group-hover:translate-y-0 transition-transform duration-300"><?php echo esc_html( $display_name ); ?></h3>
                        </div>
                    </a>
                <?php 
                $i++;
                endforeach;
            else :
                echo '<p class="col-span-full text-center">Aucune catégorie trouvée.</p>';
            endif;
            ?>
        </div>
    </div>

    <!-- 4. Section "Carte Cadeau" -->
    <div class="container mx-auto py-12 px-4">
        <div class="hero rounded-box overflow-hidden min-h-[500px] relative group shadow-2xl">
            <img src="<?php echo get_template_directory_uri(); ?>/public/gift-card-section.jpg" alt="Carte Cadeau Trendy Lux" class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" />
            <div class="hero-overlay bg-gradient-to-r from-black/60 via-black/30 to-transparent"></div>
            <div class="hero-content text-neutral-content text-center md:text-left w-full justify-start px-6 md:px-16">
                <div class="max-w-xl bg-black/40 backdrop-blur-sm p-8 rounded-box border border-white/10">
                    <h2 class="mb-6 text-4xl md:text-6xl font-bold font-serif text-white uppercase drop-shadow-lg leading-tight">
                        Le Plaisir <br><span class="text-primary">D'offrir</span>
                    </h2>
                    <p class="mb-8 text-lg md:text-xl font-light tracking-wider text-gray-100 border-l-4 border-primary pl-4">
                        Faites plaisir à coup sûr avec la Carte Cadeau Trendy Lux. Laissez-les choisir leur style parmi nos collections exclusives.
                    </p>
                    <a href="/produit/carte-cadeau-exclusive-trendy-lux/" class="btn btn-primary btn-lg rounded-full px-8 text-lg shadow-[0_0_30px_rgba(212,175,55,0.4)] border-white/20 hover:scale-105 transition-transform">
                        Acheter une Carte Cadeau
                    </a>
                </div>
            </div>
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
