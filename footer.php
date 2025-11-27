<footer class="bg-[var(--color-neutral)] text-neutral-content border-t border-[var(--color-primary)] relative">
    
    <!-- Section Réassurance -->
    <div class="container mx-auto px-4 py-8 border-neutral-focus">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
            <!-- ... Le contenu de la réassurance reste inchangé ... -->
            <div class="flex flex-col items-center">
                <svg class="h-8 w-8 text-primary mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H4a3 3 0 00-3 3v8a3 3 0 003 3z" /></svg>
                <h3 class="font-bold">Paiement Protégé</h3>
                <p class="text-xs text-neutral-content/70">Transactions 100% sécurisées</p>
            </div>
            <div class="flex flex-col items-center">
                <svg class="h-8 w-8 text-primary mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" /></svg>
                <h3 class="font-bold">Livraison Gratuite</h3>
                <p class="text-xs text-neutral-content/70">Dès 90€ d'achat</p>
            </div>
            <div class="flex flex-col items-center">
                <svg class="h-8 w-8 text-primary mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                <h3 class="font-bold">Livraison Standard</h3>
                <p class="text-xs text-neutral-content/70">3 à 5 jours ouvrés</p>
            </div>
            <div class="flex flex-col items-center">
                <svg class="h-8 w-8 text-primary mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                <h3 class="font-bold">Satisfait ou Remboursé</h3>
                <p class="text-xs text-neutral-content/70">Retours sous 14 jours</p>
            </div>
        </div>
    </div>

    <!-- Section principale du Footer -->
    <div class="bg-neutral text-neutral-content border-t border-[var(--color-primary)]">
        <div class="container mx-auto p-10 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-8">
            <aside class="md:col-span-2 flex flex-col justify-center">
                <div class="flex flex-row items-center gap-24 mb-2">
                    <a href="<?php echo home_url(); ?>" class="logo-glow-gold transition duration-300 shrink-0">
                        <img src="<?php echo get_template_directory_uri(); ?>/public/logo-trendy-lux.svg" alt="TrendyLux" class="h-32 w-auto object-contain">
                    </a>
                    <div class="relative">
                        <p class="text-4xl text-primary -rotate-12 transform origin-bottom-left text-glow-gold leading-tight" style="font-family: 'Mrs Saint Delafield', cursive;">
                            Choose your style … <br>
                            <span class="ml-8">Be Trendy</span>
                        </p>
                    </div>
                </div>
            </aside>
            <nav class="flex flex-col gap-2">
                <header class="footer-title text-primary opacity-100">Catégories</header>
                <a class="link link-hover" href="/categorie-produit/homme/">Univers Homme</a>
                <a class="link link-hover" href="/categorie-produit/femme/">Univers Femme</a>
                <a class="link link-hover" href="/categorie-produit/homme/chaussures-homme/">Chaussures Homme</a>
                <a class="link link-hover" href="/categorie-produit/homme/chaussures-femme/">Chaussures Femme</a>
            </nav>
            <nav class="flex flex-col gap-2">
                <header class="footer-title text-primary opacity-100">Informations</header>
                <a class="link link-hover" href="/mentions-legales/">Mentions légales</a>
                <a class="link link-hover" href="/politique-en-matiere-de-confidentialite/">Confidentialité</a>
                <a class="link link-hover" href="/conditions-generales-de-vente/">Conditions Générales de Vente</a>
                <a class="link link-hover" href="/politique-en-matiere-de-remboursements-et-de-retours/">Retours et remboursements</a>
            </nav>
            <nav class="flex flex-col gap-2">
                <header class="footer-title text-primary opacity-100">Pratique</header>
                <a class="link link-hover" href="/guide-des-tailles/">Guide des tailles</a>
                <header class="footer-title text-primary opacity-100 mt-4">Réseaux Sociaux</header>
                                <div class="flex align-center justify-start gap-6">
                    <!-- Facebook -->
                    <a href="https://www.facebook.com/profile.php?id=61583318666684&locale=fr_FR" target="_blank" rel="noopener" class="social-icon-gold">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: #000000; stroke: none">
                            <title>Facebook</title>
                            <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path>
                        </svg>
                    </a>
                    <!-- Instagram -->
                    <a href="https://instagram.com/trendylux_boutiqueofficielle" target="_blank" rel="noopener" class="social-icon-gold">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                            <title>Instagram</title>
                            <rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect>
                            <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path>
                            <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line>
                        </svg>
                    </a>
                </div>
            </nav>
        </div>
    </div>

    <!-- Section Newsletter (reste sur le même fond) -->
    <div class="bg-neutral text-neutral-content border-t border-[var(--color-primary)]">
        <div class="container mx-auto px-4 py-8 flex flex-col md:flex-row justify-around items-center">
            <h3 class="font-bold text-lg mb-4 md:mb-0">Inscrivez-vous à notre newsletter pour profiter de la livraison offerte sur votre première commande</h3>
            <div class="form-control">
                <form id="newsletter-form" class="join relative">
                    <input type="email" name="email" placeholder="votre.email@exemple.com" class="input input-bordered join-item text-base-content" required />
                    <button type="submit" class="btn btn-primary join-item">S'inscrire</button>
                </form>
                <div id="newsletter-message" class="text-xs mt-2 absolute"></div>
            </div>
        </div>
    </div>
    <!-- Mentions Légales pour les promotions -->
    <div class="bg-neutral text-neutral-content border-t border-[var(--color-primary)]">
        <div class="container mx-auto px-4 py-4 text-xs text-neutral-content/60">
            <p class="mb-1">*: du 1er au 15 décembre</p>
            <p>**: livraison offerte sur votre 1ère commande pour l’inscription à la newsletter</p>
        </div>
    </div>
</footer>

    <!-- Mentions Légales et Copyright -->
    <div class="bg-neutral py-4 px-4 text-center text-xs text-neutral-content/50">
        <p class="mb-1">SAS Trendy Lux n'est pas un distributeur officiel des marques vendues sur le site web trendylux.fr</p>
        <p>Copyright © TRENDY LUX. Tous droits réservés. Tous les logos et marques déposées présents sur ce site appartiennent à leurs propriétaires respectifs.</p>
    </div>

<?php wp_footer(); ?>
</body>
</html>
