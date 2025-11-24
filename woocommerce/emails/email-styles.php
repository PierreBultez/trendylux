<?php
/**
 * Email Styles
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/email-styles.php.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates\Emails
 * @version 7.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Load colors.
$bg              = '#f3f4f6'; // Base-300 equivalent
$body            = '#ffffff'; // Base-100
$base            = '#f3f4f6';
$base_text       = '#1f2937'; // Base-content
$text            = '#1f2937'; // Base-content
$bg_darkter      = '#e5e7eb';

// TRENDYLUX COLORS
$primary         = '#D4AF37'; // GOLD
$primary_text    = '#ffffff';
$secondary       = '#000000'; // Neutral/Black

$link_color      = $primary;

?>
/* <style> */
@import url('https://fonts.googleapis.com/css2?family=Noto+Sans:wght@400;700&family=Noto+Serif:wght@400;700&display=swap');

body {
	padding: 0;
	margin: 0;
    height: 100%;
    width: 100%;
    background-color: <?php echo esc_attr( $bg ); ?>;
    font-family: "Noto Sans", Helvetica, Arial, sans-serif; /* Noto Sans for body */
}

#wrapper {
	background-color: <?php echo esc_attr( $bg ); ?>;
	margin: 0;
	padding: 70px 0 70px 0;
	-webkit-text-size-adjust: none !important;
	width: 100%;
}

#template_container {
	box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1) !important; /* Shadow-lg */
	background-color: <?php echo esc_attr( $body ); ?>;
	border: 1px solid <?php echo esc_attr( $bg_darkter ); ?>;
	border-radius: 12px !important; /* Rounded-box */
    overflow: hidden;
}

#template_header {
	background-color: <?php echo esc_attr( $secondary ); ?>; /* Noir */
	border-radius: 12px 12px 0 0 !important;
	color: <?php echo esc_attr( $primary ); ?>;
	border-bottom: 4px solid <?php echo esc_attr( $primary ); ?>; /* Bordure Or */
	font-weight: bold;
	line-height: 100%;
	vertical-align: middle;
	font-family: "Noto Serif", Georgia, serif; /* Noto Serif for header/title */
}

#template_header h1,
#template_header h1 a {
	color: <?php echo esc_attr( $primary ); ?>;
    background-color: inherit;
    text-transform: uppercase;
    letter-spacing: 2px;
    font-size: 24px;
    font-weight: 900;
    text-align: center;
    text-decoration: none;
    text-shadow: 0 1px 0 #000; /* Légère ombre pour lisibilité */
    font-family: "Noto Serif", Georgia, serif; /* Noto Serif for titles */
}

#template_footer td {
	padding: 0;
	-webkit-border-radius: 6px;
}

#template_footer #credit {
	border: 0;
	color: #6b7280; /* Gray-500 */
	font-family: "Noto Sans", Helvetica, Arial, sans-serif; /* Noto Sans for footer text */
	font-size: 12px;
	line-height: 150%;
	text-align: center;
	padding: 24px 0;
}

#template_footer #credit a {
	color: <?php echo esc_attr( $primary ); ?>;
    text-decoration: none;
    font-weight: bold;
}

#body_content {
	background-color: <?php echo esc_attr( $body ); ?>;
}

#body_content table td {
	padding: 48px 48px 32px;
}

#body_content table td td {
	padding: 12px;
}

#body_content table td th {
	padding: 12px;
}

#body_content td ul.wc-item-meta {
	font-size: small;
	margin: 1em 0 0;
	padding: 0;
	list-style: none;
}

#body_content td ul.wc-item-meta li {
	margin: 0.5em 0 0;
	padding: 0;
}

#body_content td ul.wc-item-meta li p {
	margin: 0;
}

h1 {
	color: <?php echo esc_attr( $base_text ); ?>;
	font-family: "Noto Serif", Georgia, serif; /* Noto Serif for titles */
	font-size: 30px;
	font-weight: 300;
	line-height: 150%;
	margin: 0;
	text-align: <?php echo is_rtl() ? 'right' : 'left'; ?>;
	text-shadow: 0 1px 0 <?php echo esc_attr( $base ); ?>;
}

h2 {
	color: <?php echo esc_attr( $base_text ); ?>;
	display: block;
	font-family: "Noto Serif", Georgia, serif; /* Noto Serif for titles */
	font-size: 18px;
	font-weight: bold;
	line-height: 130%;
	margin: 0 0 18px;
	text-align: <?php echo is_rtl() ? 'right' : 'left'; ?>;
}

h3 {
	color: <?php echo esc_attr( $base_text ); ?>;
	display: block;
	font-family: "Noto Serif", Georgia, serif; /* Noto Serif for titles */
	font-size: 16px;
	font-weight: bold;
	line-height: 130%;
	margin: 16px 0 8px;
	text-align: <?php echo is_rtl() ? 'right' : 'left'; ?>;
}

a {
	color: <?php echo esc_attr( $link_color ); ?>;
	font-weight: normal;
	text-decoration: underline;
}

img {
	border: none;
	display: inline-block;
	font-size: 14px;
	font-weight: bold;
	height: auto;
	outline: none;
	text-decoration: none;
	text-transform: capitalize;
	vertical-align: middle;
	margin-right: 10px;
	max-width: 100%;
}

/* Custom Button Style (Imite btn btn-primary) */
.link-button {
    background-color: <?php echo esc_attr( $primary ); ?>;
    color: <?php echo esc_attr( $primary_text ); ?> !important;
    border-radius: 8px;
    display: inline-block;
    font-size: 16px;
    font-weight: bold;
    line-height: 50px;
    text-align: center;
    text-decoration: none;
    width: auto;
    min-width: 200px;
    -webkit-text-size-adjust: none;
}

.link-button:hover {
    background-color: #bfa030; /* Darker gold */
}

/* Table styles */
th {
    color: <?php echo esc_attr( $base_text ); ?>;
    font-weight: bold;
    text-align: left;
    border-bottom: 2px solid <?php echo esc_attr( $bg_darkter ); ?>;
}

td {
    color: <?php echo esc_attr( $text ); ?>;
    border-bottom: 1px solid <?php echo esc_attr( $bg_darkter ); ?>;
}

.text {
    color: <?php echo esc_attr( $text ); ?>;
    font-family: "Noto Sans", Helvetica, Arial, sans-serif; /* Noto Sans for general text */
}

.link {
    color: <?php echo esc_attr( $link_color ); ?>;
}
<?php
