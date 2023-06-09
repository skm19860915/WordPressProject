<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package WordPress
 * @subpackage BookYourTravel
 * @since 1.0
 * @version 8.00
 */

get_header();
get_template_part('byt', 'header');
BookYourTravel_Theme_Controls::the_breadcrumbs();
get_sidebar('under-header');

global $bookyourtravel_theme_globals;
$contact_page_url = $bookyourtravel_theme_globals->get_contact_page_url();
$home_url = home_url('/');

$allowed_tags = array();
$allowed_tags['a'] = array('class' => array(), 'rel' => array(), 'style' => array(), 'id' => array(), 'href' => array(), 'title' => array());
?>
		<div class="row">
			<section class="error-page">
				<div class="error-type">
					<h1><?php esc_html_e( '404', 'bookyourtravel'); ?></h1>
					<p><?php esc_html_e( 'Page not found', 'bookyourtravel'); ?></p>
				</div>
				<div class="error-content">
					<h2><?php esc_html_e( 'Whoops, you are in the middle of nowhere.', 'bookyourtravel'); ?></h2>
					<p><strong><?php esc_html_e( 'Don\'t worry. You\'ve probably made a wrong turn somewhwere.', 'bookyourtravel'); ?></strong></p>
					<ul>
						<li><?php esc_html_e( 'If you typed in the address, check your spelling. Could just be a typo.', 'bookyourtravel'); ?></li>
						<li><?php echo sprintf(wp_kses(__('If you followed a link, it\'s probably broken. Please <a href="%s">contact us</a> and we\'ll fix it.', 'bookyourtravel'), $allowed_tags), esc_url($contact_page_url)); ?></li>
						<li><?php echo sprintf(wp_kses(__( 'If you\'re not sure what you\'re looking for, go back to <a href="%s">homepage</a>.', 'bookyourtravel'), $allowed_tags), esc_url($home_url)); ?></li>
					</ul>
				</div>
			</section>
		</div>
<?php 
get_template_part('byt', 'footer');
get_footer();