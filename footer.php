<?php
$options = get_option( 'trendylux_home_options' );

// Helper simple pour r&eacute;cup&eacute;rer une option ou une valeur par d&eacute;faut
$get_opt = function($key, $default = '') use ($options) {
    return !empty($options[$key]) ? $options[$key] : $default;
};
?>
<footer class="bg-[var(--color-neutral)] text-neutral-content border-t border-[var(--color-primary)] relative pb-5">
    
    <!-- Section R&eacute;assurance -->
    <div class="container mx-auto px-4 py-8 border-neutral-focus">
        <div class="grid grid-cols-1 md:grid-cols-5 gap-8 text-center">
            
            <!-- Bloc 1 -->
            <?php
            $r1_img   = $get_opt('reassurance_1_image');
            $r1_title = $get_opt('reassurance_1_title', 'Paiement Prot&eacute;g&eacute;');
            $r1_sub   = $get_opt('reassurance_1_subtitle', 'Transactions 100% s&eacute;curis&eacute;es');
            ?>
            <div class="flex flex-col items-center">
                <?php if ( $r1_img ) : ?>
                    <img src="<?php echo esc_url(wp_get_attachment_image_url($r1_img, 'full')); ?>" class="h-8 w-auto mb-2 object-contain" alt="<?php echo esc_attr($r1_title); ?>">
                <?php else : ?>
                    <svg class="h-8 w-8 text-primary mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H4a3 3 0 00-3 3v8a3 3 0 003 3z" /></svg>
                <?php endif; ?>
                <h3 class="font-bold"><?php echo wp_kses_post($r1_title); ?></h3>
                <p class="text-xs text-neutral-content/70"><?php echo wp_kses_post($r1_sub); ?></p>
            </div>

            <!-- Bloc 2 -->
            <?php
            $r2_img   = $get_opt('reassurance_2_image');
            $r2_title = $get_opt('reassurance_2_title', 'Livraison Gratuite');
            $r2_sub   = $get_opt('reassurance_2_subtitle', 'D&egrave;s 90€ d\'achat');
            ?>
            <div class="flex flex-col items-center">
                <?php if ( $r2_img ) : ?>
                    <img src="<?php echo esc_url(wp_get_attachment_image_url($r2_img, 'full')); ?>" class="h-8 w-auto mb-2 object-contain" alt="<?php echo esc_attr($r2_title); ?>">
                <?php else : ?>
                    <svg class="h-8 w-8 text-primary mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" /></svg>
                <?php endif; ?>
                <h3 class="font-bold"><?php echo wp_kses_post($r2_title); ?></h3>
                <p class="text-xs text-neutral-content/70"><?php echo wp_kses_post($r2_sub); ?></p>
            </div>

            <!-- Bloc 3 -->
            <?php
            $r3_img   = $get_opt('reassurance_3_image');
            $r3_title = $get_opt('reassurance_3_title', 'Livraison Standard');
            $r3_sub   = $get_opt('reassurance_3_subtitle', '3 &agrave; 5 jours ouvr&eacute;s');
            ?>
            <div class="flex flex-col items-center">
                <?php if ( $r3_img ) : ?>
                    <img src="<?php echo esc_url(wp_get_attachment_image_url($r3_img, 'full')); ?>" class="h-8 w-auto mb-2 object-contain" alt="<?php echo esc_attr($r3_title); ?>">
                <?php else : ?>
                    <svg class="h-8 w-8 text-primary mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                <?php endif; ?>
                <h3 class="font-bold"><?php echo wp_kses_post($r3_title); ?></h3>
                <p class="text-xs text-neutral-content/70"><?php echo wp_kses_post($r3_sub); ?></p>
            </div>

            <!-- Bloc 4 -->
            <?php
            $r4_img   = $get_opt('reassurance_4_image');
            $r4_title = $get_opt('reassurance_4_title', 'Satisfait ou Rembours&eacute;');
            $r4_sub   = $get_opt('reassurance_4_subtitle', 'Retours sous 14 jours');
            ?>
            <div class="flex flex-col items-center">
                <?php if ( $r4_img ) : ?>
                    <img src="<?php echo esc_url(wp_get_attachment_image_url($r4_img, 'full')); ?>" class="h-8 w-auto mb-2 object-contain" alt="<?php echo esc_attr($r4_title); ?>">
                <?php else : ?>
                    <svg class="h-8 w-8 text-primary mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                <?php endif; ?>
                <h3 class="font-bold"><?php echo wp_kses_post($r4_title); ?></h3>
                <p class="text-xs text-neutral-content/70"><?php echo wp_kses_post($r4_sub); ?></p>
            </div>

            <!-- Bloc 5 : CONTACT -->
            <a href="<?php echo home_url('/contact/'); ?>" class="flex flex-col items-center group hover:text-primary transition-colors">
                <svg class="h-8 w-8 text-primary mb-2 group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
                <h3 class="font-bold">Service Client</h3>
                <p class="text-xs text-neutral-content/70">&Agrave; votre &eacute;coute</p>
            </a>

        </div>
    </div>

    <!-- Section principale du Footer -->
    <div class="bg-neutral text-neutral-content border-t border-[var(--color-primary)]">
        <div class="container mx-auto p-10 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-8">
            <aside class="md:col-span-2 flex flex-col justify-center">
                <div class="flex flex-col items-center gap-y-4 mb-2 md:flex-row md:items-center md:gap-24">
                    <?php
                    // Logo Footer
                    $footer_logo_id = $get_opt('footer_logo');
                    $footer_logo_src = $footer_logo_id ? wp_get_attachment_image_url($footer_logo_id, 'full') : get_template_directory_uri() . '/public/logo-trendy-lux.svg';
                    
                    // Tagline Footer
                    $footer_tagline = $get_opt('footer_tagline', 'Choose your style … <br><span class="ml-8">Be Trendy</span>');
                    ?>
                    <a href="<?php echo home_url(); ?>" class="logo-glow-gold transition duration-300 shrink-0">
                        <img src="<?php echo esc_url($footer_logo_src); ?>" alt="TrendyLux" class="h-32 w-auto object-contain">
                    </a>
                    <div class="relative hidden md:block">
                        <p class="md:text-xl lg:text-2xl xl:text-4xl text-primary -rotate-12 transform origin-bottom-left text-glow-gold leading-tight" style="font-family: 'Mrs Saint Delafield', cursive;">
                            <?php echo wp_kses_post($footer_tagline); ?>
                        </p>
                    </div>
                </div>
            </aside>
            <nav class="flex flex-col gap-2">
                <header class="footer-title text-primary opacity-100 text-3xl md:text-xl">Cat&eacute;gories</header>
                <a class="link link-hover text-xl md:text-sm" href="/categorie-produit/homme/">Univers Homme</a>
                <a class="link link-hover text-xl md:text-sm" href="/categorie-produit/femme/">Univers Femme</a>
                <a class="link link-hover text-xl md:text-sm" href="/categorie-produit/homme/chaussures-homme/">Chaussures Homme</a>
                <a class="link link-hover text-xl md:text-sm" href="/categorie-produit/homme/chaussures-femme/">Chaussures Femme</a>
            </nav>
            <nav class="flex flex-col gap-2">
                <header class="footer-title text-primary opacity-100 text-3xl md:text-xl">Informations</header>
                <a class="link link-hover text-xl md:text-sm" href="/mentions-legales/">Mentions l&eacute;gales</a>
                <a class="link link-hover text-xl md:text-sm" href="/politique-en-matiere-de-confidentialite/">Confidentialit&eacute;</a>
                <a class="link link-hover text-xl md:text-sm" href="/conditions-generales-de-vente/">CGV</a>
                <a class="link link-hover text-xl md:text-sm" href="/politique-en-matiere-de-remboursements-et-de-retours/">Retours</a>
            </nav>
            <nav class="flex flex-col gap-2">
                <header class="footer-title text-primary opacity-100 text-3xl md:text-xl">Pratique</header>
                <a class="link link-hover text-xl md:text-sm" href="/guide-des-tailles/">Guide des tailles</a>
                <a class="link link-hover text-xl md:text-sm" href="<?php echo get_post_type_archive_link( 'post' ); ?>">L'actu Trendy</a>
                <header class="footer-title text-primary opacity-100 mt-4 text-3xl md:text-xl">R&eacute;seaux</header>
                                <div class="flex align-center justify-center md:justify-start gap-24 mt-6 md:gap-6 md:mt-0">
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

    <!-- Section Newsletter (reste sur le m&ame fond) -->
    <div class="bg-neutral text-neutral-content border-t border-[var(--color-primary)]">
        <div class="container mx-auto px-4 py-8 flex flex-col md:flex-row justify-around items-center">
            <h3 class="font-bold text-lg mb-4 md:mb-0"><?php echo wp_kses_post( $get_opt('footer_newsletter_text', 'Inscrivez-vous &agrave; notre newsletter pour profiter de la livraison offerte sur votre premi&egrave;re commande') ); ?></h3>
            <div class="form-control">
                <form id="newsletter-form" class="join relative js-newsletter-form">
                    <input type="email" name="email" placeholder="votre.email@exemple.com" class="input input-bordered join-item text-base-content" required />
                    <button type="submit" class="btn btn-primary join-item">S'inscrire</button>
                </form>
                <div id="newsletter-message" class="text-xs mt-2 absolute js-newsletter-message"></div>
            </div>
        </div>
    </div>
    <!-- Mentions L&eacute;gales pour les promotions -->
    <div class="bg-neutral text-neutral-content border-t border-[var(--color-primary)] md:flex md:justify-between md:items-center md:pt-5">
        <div class="px-4 py-4 md:ms-20 text-xs text-neutral-content/60">
            <p class="mb-1"><?php echo wp_kses_post( $get_opt('footer_disclaimer_1', '*: du 1er au 15 d&eacute;cembre hors promotions') ); ?></p>
            <p><?php echo wp_kses_post( $get_opt('footer_disclaimer_2', '**: livraison offerte sur votre 1&egrave;re commande pour l&rsquo;inscription &agrave; la newsletter') ); ?></p>
        </div>

<!--        <div class="flex flex-wrap gap-4 items-center justify-center md:me-20">-->
        <div class="grid grid-cols-2 gap-2 md:gap-4 md:max-w-[400px] md:me-20 p-1 md:p-0">
            <!-- TrustBox widget - Review Collector -->
            <a href="https://fr.trustpilot.com/review/trendylux.fr" target="_blank" rel="noopener noreferrer"
               class="group flex items-center justify-center gap-3 p-3 bg-gray-50 border border-gray-100 rounded-lg hover:bg-gray-100 transition-colors duration-200 w-full h-full">

                <div class="bg-[#00b67a] p-1.5 rounded-full flex-shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                    </svg>
                </div>

                <div class="flex flex-col">
                    <span class="text-sm font-bold text-neutral group-hover:text-black">Trustpilot</span>

                    <div class="flex items-center gap-0.5">
                        <div class="flex bg-[#00b67a] text-white px-1 py-0.5 text-[10px] font-bold rounded-sm">
                            3.7 / 5
                        </div>
                        <span class="text-xs text-gray-500 ml-1">Moyen</span>
                    </div>
                </div>
            </a>
            <!-- End TrustBox widget -->

            <!-- Google widget - Review Collector -->
            <a href="https://g.page/r/CZ_mrbWDD3T_EBI/review" target="_blank" rel="noopener noreferrer"
               class="group flex items-center justify-center gap-3 p-3 bg-gray-50 border border-gray-100 rounded-lg hover:bg-gray-100 transition-colors duration-200 w-full h-full">

                <div class="bg-white p-1.5 rounded-full flex-shrink-0 shadow-sm border border-gray-100">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 48 48">
                        <path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"/>
                        <path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"/>
                        <path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"/>
                        <path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"/>
                    </svg>
                </div>

                <div class="flex flex-col">
                    <span class="text-sm font-bold text-neutral group-hover:text-black">Google</span>

                    <div class="flex items-center gap-0.5">
                        <div class="flex text-yellow-500">
                            <svg class="w-3 h-3 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            <svg class="w-3 h-3 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            <svg class="w-3 h-3 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            <svg class="w-3 h-3 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            <svg class="w-3 h-3 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        </div>
                        <span class="text-xs text-gray-500 ml-1">5.0 / 5</span>
                    </div>
                </div>
            </a>
            <!-- End Google widget -->
        </div>

    </div>
</footer>

    <!-- Mentions L&eacute;gales et Copyright -->
    <div class="bg-neutral py-4 px-4 text-center text-xs text-neutral-content/50">
        <p class="mb-1"><?php echo wp_kses_post( $get_opt('footer_credits_1', 'SAS Trendy Lux n\'est pas un distributeur officiel des marques vendues sur le site web trendylux.fr') ); ?></p>
        <p><?php echo wp_kses_post( $get_opt('footer_credits_2', 'Copyright © TRENDY LUX. Tous droits r&eacute;serv&eacute;s. Tous les logos et marques d&eacute;pos&eacute;es pr&eacute;sents sur ce site appartiennent &agrave; leurs propri&eacute;taires respectifs.') ); ?></p>
    </div>

<?php wp_footer(); ?>
</body>
</html>