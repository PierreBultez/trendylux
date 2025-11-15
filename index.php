<?php get_header(); ?>

    <main>
        <!-- 1. Section Hero -->
        <div class="hero min-h-[60vh] bg-cover bg-center" style="background-image: url(https://picsum.photos/id/122/1600/900);">
            <div class="hero-overlay bg-black/60"></div>
            <div class="hero-content text-center text-neutral-content">
                <div class="max-w-md">
                    <h1 class="mb-5 text-5xl font-bold font-serif uppercase">Best Sellers</h1>
                    <p class="mb-5 font-bold uppercase">Autumn / Winter 25</p>
                    <button class="btn btn-primary btn-outline">Voir le top ventes</button>
                </div>
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

        <!-- 4. Carousel de Produits (Top Ventes) -->
        <div class="container mx-auto py-12 px-4">
            <h2 class="text-3xl font-bold text-center mb-8 uppercase font-serif">Top Ventes</h2>
            <div class="carousel carousel-center w-full p-4 space-x-4 bg-neutral rounded-box">
                <?php for ($i = 0; $i < 10; $i++): ?>
                    <div class="carousel-item w-64">
                        <div class="card bg-base-100 shadow-xl">
                            <figure><img src="https://picsum.photos/id/<?php echo 200 + $i; ?>/400/300" alt="Produit" /></figure>
                            <div class="card-body">
                                <h3 class="card-title text-sm">Nom du produit</h3>
                                <p class="text-lg font-bold">99.99€</p>
                                <div class="card-actions justify-end">
                                    <button class="btn btn-primary btn-sm">Acheter</button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endfor; ?>
            </div>
        </div>

    </main>

<?php get_footer(); ?>