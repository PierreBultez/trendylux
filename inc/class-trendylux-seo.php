<?php
/**
 * Class TrendyLux_SEO
 *
 * Manages SEO meta tags for the TrendyLux theme, including title, description,
 * Open Graph, Twitter Cards, and canonical URLs.
 */
class TrendyLux_SEO {

    // Global SEO settings for the homepage
    private string $home_seo_title = 'Trendy Lux : Mode de Marque & Accessoires Tendance';
    private string $home_seo_description = 'Découvrez Trendy Lux, votre boutique française de mode et accessoires de marque. Les dernières tendances luxe au meilleur prix. Livraison rapide et sécurisée.';
    private string $home_seo_keywords = 'Vêtements de Marque, Luxe Accessible, Trendy Lux, Mode, Accessoires, Tendance, Boutique Française, Meilleur Prix, Livraison Rapide, Livraison Sécurisée';

    public function __construct() {
        add_action( 'wp_head', [ $this, 'add_meta_tags' ], 1 );
        add_filter( 'document_title_parts', [ $this, 'filter_document_title_parts' ], 100 ); // High priority to override others
    }

    /**
     * Filters the document title parts for the homepage.
     */
    public function filter_document_title_parts( array $title ): array {
        if ( is_front_page() || is_home() ) {
            $title['title'] = $this->home_seo_title;
            // Unset other parts to ensure only the custom title is used
            unset($title['site']);
            unset($title['tagline']);
        }
        return $title;
    }

    /**
     * Adds various SEO meta tags to the wp_head.
     */
    public function add_meta_tags(): void {
        $this->add_description_meta();
        $this->add_keywords_meta(); // Add keywords meta tag
        $this->add_canonical_url();
        $this->add_open_graph_tags();
        $this->add_twitter_card_tags();
    }

    /**
     * Generates and outputs the meta description tag.
     */
    private function add_description_meta(): void {
        $description = '';

        if ( is_front_page() || is_home() ) {
            $description = $this->home_seo_description;
        } elseif ( is_singular() ) {
            $post = get_post();
            if ( $post && ! empty( $post->post_excerpt ) ) {
                $description = wp_strip_all_tags( $post->post_excerpt );
            } elseif ( $post ) {
                $description = wp_trim_words( wp_strip_all_tags( $post->post_content ), 25 );
            }
        } elseif ( is_category() || is_tag() || is_tax() ) {
            $term = get_queried_object();
            if ( $term && ! empty( $term->description ) ) {
                $description = wp_strip_all_tags( $term->description );
            }
        }

        // Fallback for archives or if no specific description is found
        if ( empty( $description ) && is_archive() ) {
             $description = sprintf( __( 'Browse our %s archives on %s', 'trendylux' ), post_type_archive_title( '', false ), get_bloginfo('name') );
        }

        $description = apply_filters( 'trendylux_seo_description', $description );
        $description = esc_attr( wp_trim_words( $description, 25, '...' ) ); // Ensure it's trimmed and escaped

        if ( ! empty( $description ) ) {
            echo '<meta name="description" content="' . $description . '" />' . "\n";
        }
    }

    /**
     * Generates and outputs the meta keywords tag.
     * While less impactful for SEO, some clients still request it.
     */
    private function add_keywords_meta(): void {
        $keywords = '';

        if ( is_front_page() || is_home() ) {
            $keywords = $this->home_seo_keywords;
        } elseif ( is_singular() ) {
            $post_keywords = get_the_terms( get_the_ID(), 'post_tag' ); // Assuming 'post_tag' for keywords
            if ( ! empty( $post_keywords ) && ! is_wp_error( $post_keywords ) ) {
                $keywords = implode( ', ', wp_list_pluck( $post_keywords, 'name' ) );
            }
        } elseif ( is_category() || is_tag() || is_tax() ) {
            $term = get_queried_object();
            if ( $term ) {
                // If term description is used as keywords, or specific meta for term keywords
                // For now, keeping it simple; can be extended to use a custom field for term keywords.
                $keywords = $term->name;
            }
        }
        
        $keywords = apply_filters( 'trendylux_seo_keywords', $keywords );
        $keywords = esc_attr( $keywords );

        if ( ! empty( $keywords ) ) {
            echo '<meta name="keywords" content="' . $keywords . '" />' . "\n";
        }
    }

    /**
     * Generates and outputs the canonical URL.
     */
    private function add_canonical_url(): void {
        $canonical_url = '';

        if ( is_singular() ) {
            $canonical_url = get_permalink();
        } elseif ( is_front_page() ) {
            $canonical_url = home_url( '/' );
        } elseif ( is_post_type_archive() ) {
            $canonical_url = get_post_type_archive_link( get_post_type() );
        } elseif ( is_category() || is_tag() || is_tax() ) {
            $canonical_url = get_term_link( get_queried_object() );
        } elseif ( is_archive() ) {
            $canonical_url = get_day_link( get_the_time( 'Y' ), get_the_time( 'm' ), get_the_time( 'd' ) );
        }

        $canonical_url = apply_filters( 'trendylux_seo_canonical_url', $canonical_url );
        
        if ( ! empty( $canonical_url ) ) {
            echo '<link rel="canonical" href="' . esc_url( $canonical_url ) . '" />' . "\n";
        }
    }

    /**
     * Generates and outputs Open Graph meta tags for social sharing.
     */
    private function add_open_graph_tags(): void {
        global $post;

        $og_type        = 'website';
        $og_title       = is_front_page() || is_home() ? $this->home_seo_title : wp_get_document_title();
        $og_description = is_front_page() || is_home() ? $this->home_seo_description : '';
        $og_url         = esc_url( home_url( '/' ) );
        $og_image       = '';
        $og_site_name   = get_bloginfo( 'name' );

        if ( is_singular() && $post ) {
            $og_type        = 'article';
            $og_url         = get_permalink( $post->ID );
            $og_description = $this->get_meta_description( $post );
            if ( has_post_thumbnail( $post->ID ) ) {
                $og_image = get_the_post_thumbnail_url( $post->ID, 'large' );
            }
        } elseif ( is_home() || is_front_page() ) {
            // Already handled above
        } elseif ( is_category() || is_tag() || is_tax() ) {
            $term = get_queried_object();
            if ( $term ) {
                $og_type        = 'object';
                $og_title       = single_term_title( '', false ) . ' - ' . $og_site_name;
                $og_description = ! empty( $term->description ) ? wp_strip_all_tags( $term->description ) : '';
                $og_url         = get_term_link( $term );
            }
        }

        // Fallback for image if no specific image is found
        if ( empty( $og_image ) ) {
            $custom_logo_id = get_theme_mod( 'custom_logo' );
            if ( $custom_logo_id ) {
                $og_image = wp_get_attachment_image_url( $custom_logo_id, 'full' );
            }
        }

        $og_title       = esc_attr( wp_trim_words( $og_title, 20, '...' ) );
        $og_description = esc_attr( wp_trim_words( $og_description, 25, '...' ) );

        echo '<meta property="og:site_name" content="' . esc_attr( $og_site_name ) . '" />' . "\n";
        echo '<meta property="og:type" content="' . esc_attr( $og_type ) . '" />' . "\n";
        echo '<meta property="og:title" content="' . $og_title . '" />' . "\n";
        echo '<meta property="og:description" content="' . $og_description . '" />' . "\n";
        echo '<meta property="og:url" content="' . esc_url( $og_url ) . '" />' . "\n";
        if ( ! empty( $og_image ) ) {
            echo '<meta property="og:image" content="' . esc_url( $og_image ) . '" />' . "\n";
        }
    }

    /**
     * Generates and outputs Twitter Card meta tags.
     */
    private function add_twitter_card_tags(): void {
        global $post;

        $twitter_card_type        = 'summary_large_image'; // or 'summary'
        $twitter_title            = is_front_page() || is_home() ? $this->home_seo_title : wp_get_document_title();
        $twitter_description      = is_front_page() || is_home() ? $this->home_seo_description : '';
        $twitter_image            = '';
        $twitter_site             = ''; // Consider adding a specific Twitter handle if known, e.g., @YourSiteHandle

        if ( is_singular() && $post ) {
            $twitter_description = $this->get_meta_description( $post );
            if ( has_post_thumbnail( $post->ID ) ) {
                $twitter_image = get_the_post_thumbnail_url( $post->ID, 'large' );
            }
        } elseif ( is_home() || is_front_page() ) {
            // Already handled above
        } elseif ( is_category() || is_tag() || is_tax() ) {
            $term = get_queried_object();
            if ( $term ) {
                $twitter_title       = single_term_title( '', false ) . ' - ' . get_bloginfo( 'name' );
                $twitter_description = ! empty( $term->description ) ? wp_strip_all_tags( $term->description ) : '';
            }
        }

        // Fallback for image, similar to Open Graph
        if ( empty( $twitter_image ) ) {
            $custom_logo_id = get_theme_mod( 'custom_logo' );
            if ( $custom_logo_id ) {
                $twitter_image = wp_get_attachment_image_url( $custom_logo_id, 'full' );
            }
        }

        $twitter_title       = esc_attr( wp_trim_words( $twitter_title, 20, '...' ) );
        $twitter_description = esc_attr( wp_trim_words( $twitter_description, 25, '...' ) );

        echo '<meta name="twitter:card" content="' . esc_attr( $twitter_card_type ) . '" />' . "\n";
        echo '<meta name="twitter:title" content="' . $twitter_title . '" />' . "\n";
        echo '<meta name="twitter:description" content="' . $twitter_description . '" />' . "\n";
        if ( ! empty( $twitter_image ) ) {
            echo '<meta name="twitter:image" content="' . esc_url( $twitter_image ) . '" />' . "\n";
        }
        if ( ! empty( $twitter_site ) ) {
            echo '<meta name="twitter:site" content="' . esc_attr( $twitter_site ) . '" />' . "\n";
        }
    }

    /**
     * Helper to get meta description from post content or excerpt.
     */
    private function get_meta_description( $post ): string {
        $description = '';
        if ( $post ) {
            if ( ! empty( $post->post_excerpt ) ) {
                $description = wp_strip_all_tags( $post->post_excerpt );
            } else {
                $description = wp_trim_words( wp_strip_all_tags( $post->post_content ), 25 );
            }
        }
        return $description;
    }
}

// Initialize the SEO class
new TrendyLux_SEO();
