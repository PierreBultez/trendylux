<?php
/**
 * Template Name: Page Contact
 */

get_header();

// Récupération des options
$opts = get_option( 'trendylux_home_options', [] );
$phone = $opts['contact_phone'] ?? '06 52 19 62 15';
$email = $opts['contact_email'] ?? 'contact@trendylux.fr';
$address = $opts['contact_address'] ?? 'Trendy Lux 60 rue François 1er 75008 Paris';
?>

<div class="bg-base-200 py-12">
    <div class="container mx-auto px-4">
        
        <div class="text-center mb-12">
            <h1 class="text-4xl font-serif font-bold mb-4">Contactez-nous</h1>
            <p class="text-gray-600 max-w-2xl mx-auto">Une question sur une commande, un produit ou simplement envie de nous dire bonjour ? Notre équipe est à votre écoute.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 max-w-6xl mx-auto">
            
            <!-- Informations de contact -->
            <div class="lg:col-span-1 lg:h-full space-y-6">
                
                <!-- Carte Adresse -->
                <div class="card bg-base-100 shadow-xl">
                    <div class="card-body items-center text-center">
                        <div class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center text-primary mb-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                        </div>
                        <h2 class="card-title text-base uppercase tracking-wider">Adresse</h2>
                        <p class="text-gray-600 whitespace-pre-line"><?php echo esc_html($address); ?></p>
                    </div>
                </div>

                <!-- Carte Email -->
                <div class="card bg-base-100 shadow-xl">
                    <div class="card-body items-center text-center">
                        <div class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center text-primary mb-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                        </div>
                        <h2 class="card-title text-base uppercase tracking-wider">Email</h2>
                        <p class="text-gray-600">
                            <a href="mailto:<?php echo esc_attr($email); ?>" class="link link-hover"><?php echo esc_html($email); ?></a>
                        </p>
                    </div>
                </div>

                <!-- Carte Téléphone -->
                <div class="card bg-base-100 shadow-xl">
                    <div class="card-body items-center text-center">
                        <div class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center text-primary mb-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" /></svg>
                        </div>
                        <h2 class="card-title text-base uppercase tracking-wider">Téléphone</h2>
                        <p class="text-gray-600">
                            <a href="tel:<?php echo esc_attr(str_replace(' ', '', $phone)); ?>" class="link link-hover"><?php echo esc_html($phone); ?></a>
                        </p>
                    </div>
                </div>

            </div>

            <!-- Formulaire de contact -->
            <div class="lg:col-span-2 lg:h-full">
                <div class="card bg-base-100 shadow-xl h-full">
                    <div class="card-body">
                        <h2 class="card-title mb-6">Envoyez-nous un message</h2>
                        
                        <form id="trendylux-contact-form" class="space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="form-control">
                                    <label class="label"><span class="label-text">Nom complet *</span></label>
                                    <input type="text" name="name" class="input input-bordered w-full" required />
                                </div>
                                <div class="form-control">
                                    <label class="label"><span class="label-text">Email *</span></label>
                                    <input type="email" name="email" class="input input-bordered w-full" required />
                                </div>
                            </div>
                            
                            <div class="form-control">
                                <label class="label"><span class="label-text">Sujet</span></label>
                                <input type="text" name="subject" class="input input-bordered w-full" />
                            </div>

                            <div class="form-control">
                                <label class="label"><span class="label-text">Message *</span></label>
                                <textarea name="message" class="textarea textarea-bordered h-32 w-full" required></textarea>
                            </div>

                            <div id="contact-form-message" class="text-sm mt-2 hidden"></div>

                            <div class="form-control mt-6">
                                <button type="submit" class="btn btn-primary">Envoyer le message</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<?php get_footer(); ?>