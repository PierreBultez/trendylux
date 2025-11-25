<?php
/*
Template Name: Collab Makai
*/
get_header();
?>

<main class="bg-base-100 overflow-x-hidden">

    <!-- 1. HERO SECTION -->
    <div class="hero min-h-[70vh] relative">
        <img src="<?php echo get_template_directory_uri(); ?>/public/makai.jpeg" alt="Makaï Design x Trendy Lux" class="absolute inset-0 w-full h-full object-cover opacity-40" />
        <div class="absolute inset-0 bg-gradient-to-b from-black/80 via-black/50 to-base-100"></div>
        
        <div class="hero-content text-center text-neutral-content relative z-10">
            <div class="max-w-4xl">
                <div class="mb-4 inline-block border-2 border-primary px-4 py-1 rounded-full text-primary font-bold tracking-widest uppercase text-sm animate-pulse">
                    Collaboration Exclusive
                </div>
                <h1 class="mb-6 text-5xl md:text-8xl font-serif font-bold text-white uppercase tracking-tighter drop-shadow-2xl">
                    Trendy Lux <br> <span class="text-primary font-light">x</span> <br> Makaï
                </h1>
                <p class="mb-8 text-xl md:text-2xl font-light tracking-[0.3em] text-gray-200 uppercase">
                    L'Art de la Casquette • Made in Sud
                </p>
                <a href="#precommande" class="btn btn-primary btn-outline rounded-full px-10 text-lg hover:scale-105 transition-transform">
                    Découvrir l'édition limitée
                </a>
            </div>
        </div>
    </div>

    <!-- 2. PRESENTATION MAKAI -->
    <div class="container mx-auto py-20 px-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-16 items-center">
            <!-- Texte -->
            <div class="relative space-y-8">
                <div>
                    <h2 class="text-primary text-sm font-bold uppercase tracking-widest mb-2">Une Histoire de Passion</h2>
                    <h3 class="text-4xl font-serif font-bold mb-6">Makaï Design : L'Excellence Française</h3>
                    <p class="text-base-content/80 text-lg leading-relaxed">
                        Ancrée dans le Gard à Pont-Saint-Esprit, <span class="font-bold text-base-content">Makaï Design</span> est bien plus qu'une marque de casquettes. Fondée en 2010 par <span class="font-bold">Jérémy Grimaud</span>, elle incarne la fusion parfaite entre la fierté régionale et les codes du streetwear urbain.
                    </p>
                </div>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div class="flex gap-4 items-start">
                        <div class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center flex-shrink-0 text-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7" /></svg>
                        </div>
                        <div>
                            <h4 class="font-bold uppercase text-sm mb-1">Savoir-Faire</h4>
                            <p class="text-sm text-base-content/70">Finitions fait main et personnalisation haut de gamme.</p>
                        </div>
                    </div>
                    <div class="flex gap-4 items-start">
                        <div class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center flex-shrink-0 text-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                        </div>
                        <div>
                            <h4 class="font-bold uppercase text-sm mb-1">Made in Sud</h4>
                            <p class="text-sm text-base-content/70">"Cocoricooooo" ! Une identité forte du Sud de la France.</p>
                        </div>
                    </div>
                </div>

                <div class="bg-base-200 p-6 rounded-box italic text-center border-l-4 border-primary">
                    "Makaï ne se contente pas de vendre des couvre-chefs ; l'entreprise positionne la casquette comme un objet de design et d'expression personnelle."
                </div>

                <!-- Badge Flottant -->
                <div class="absolute -rotate-8 left-8 bg-white text-black p-4 rounded-lg shadow-lg max-w-xs">
                    <p class="font-bold text-xs uppercase mb-1 text-primary">Validé par les stars</p>
                    <p class="text-sm">Porté par Booba, Soprano, Inès Reg, Bob Sinclar...</p>
                </div>

            </div>

            <!-- Visuel -->
            <div class="group">
                <div class="absolute -inset-4 bg-primary/20 rounded-xl rotate-3 transition-transform duration-500 group-hover:rotate-6"></div>
                <img src="<?php echo get_template_directory_uri(); ?>/public/makai-2.jpeg" alt="Casquette Makaï" class="relative rounded-xl shadow-2xl w-full object-cover aspect-[4/5] grayscale group-hover:grayscale-0 transition-all duration-700">
            </div>
        </div>
    </div>

    <!-- 3. LE PRODUIT (Section Sombre) -->
    <div id="precommande" class="bg-neutral text-neutral-content py-20 relative overflow-hidden">
        <!-- Elements décoratifs -->
        <div class="absolute top-0 right-0 w-64 h-64 bg-primary/10 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 left-0 w-96 h-96 bg-primary/5 rounded-full blur-3xl"></div>

        <div class="container mx-auto px-4 relative z-10 text-center">
            <h2 class="text-4xl md:text-6xl font-serif font-bold text-primary mb-4">L'Édition Limitée</h2>
            <p class="text-xl uppercase tracking-widest mb-12 text-gray-400">Trendy Lux <span class="text-white">x</span> Makaï</p>

            <div class="card bg-base-100/10 backdrop-blur-md border border-white/10 max-w-3xl mx-auto overflow-hidden shadow-2xl">
                <div class="card-body items-center text-center p-8 md:p-12">
                    
                    <!-- Placeholder pour la future casquette (Icone en attendant le visuel produit final) -->
                    <div class="w-32 h-32 mb-6 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-24 h-24 text-primary animate-bounce">
                            <path d="M11.645 20.91l-.007-.003-.022-.012a15.247 15.247 0 01-.383-.218 25.18 25.18 0 01-4.244-3.17C4.688 15.36 2.25 12.174 2.25 8.25 2.25 5.322 4.714 3 7.688 3A5.5 5.5 0 0112 5.052 5.5 5.5 0 0116.313 3c2.973 0 5.437 2.322 5.437 5.25 0 3.925-2.438 7.111-4.739 9.256a25.175 25.175 0 01-4.244 3.17 15.247 15.247 0 01-.383.219l-.022.012-.007.004-.003.001a.752.752 0 01-.704 0l-.003-.001z" />
                        </svg>
                    </div>

                    <h3 class="text-2xl md:text-3xl font-bold text-white mb-4">Casquette Collector 2025</h3>
                    <p class="text-gray-300 mb-8 max-w-lg mx-auto">
                        Une pièce unique fusionnant le luxe accessible de Trendy Lux et le design audacieux de Makaï. Finitions or, broderie 3D, et matériaux premium.
                    </p>

                    <div class="alert bg-primary/20 border-primary text-primary-content mb-8">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="stroke-current shrink-0 w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span class="font-bold text-lg text-secondary">A venir … disponible en pré commande envoie nous un mail pour être sûr de recevoir la tienne Stock limité</span>
                    </div>

                    <a href="mailto:contact@trendylux.com?subject=Pré-commande Casquette Makai x TrendyLux" class="btn btn-primary btn-lg w-full md:w-auto gap-3 shadow-[0_0_20px_rgba(212,175,55,0.5)]">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                        Réserver ma casquette
                    </a>
                </div>
            </div>
        </div>
    </div>

</main>

<?php get_footer(); ?>
