<?php

/**
 * WP EASY PAY
 * PHP version 7
 * Plugin Name: WP Easy Pay (FREE)
 * Plugin URI: https://wpeasypay.com/demo/
 * Description: Easily collect payments for Simple Payment or donations online
 * without coding it yourself or hiring a developer. Skip setting up a complex shopping cart system.
 * Author: WP Easy Pay
 * Author URI: https://wpeasypay.com/
 * Version: 3.2.7
 * Text Domain: wp_easy_pay
 * License: GPLv2 or later
 *
 * @category Wordpress_Plugin
 * @package  WP_Easy_Pay
 * @author   Author <contact@apiexperts.io>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     http://wpeasypay.com/
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'WPEP_ROOT_URL', plugin_dir_url( __FILE__ ) );
define( 'WPEP_ROOT_PATH', plugin_dir_path( __FILE__ ) );

if ( ! function_exists( 'wpep_add_viewport_meta_tag' ) ) {

	function wpep_add_viewport_meta_tag() {
		 printf('<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />');
		 
	}
}
	add_action( 'wp_head', 'wpep_add_viewport_meta_tag', '1' );

	define( 'WPEP_SQUARE_PLUGIN_NAME', 'WP_EASY_PAY' );
	define( 'WPEP_SQUARE_APP_NAME', 'WP_EASY_PAY_SQUARE_APP' );
	define( 'WPEP_MIDDLE_SERVER_URL', 'https://connect.apiexperts.io' );
	require_once __DIR__ . '/wpep_free_plugin_setup.php';
	require_once WPEP_ROOT_PATH . 'modules/payments/square_authorization.php';
	require_once WPEP_ROOT_PATH . 'modules/payments/square_payments_free.php';
	require_once WPEP_ROOT_PATH . 'modules/render_forms/form_render_shortcode_free.php';
	add_action( 'admin_enqueue_scripts', 'wpep_include_stylesheets' );
	add_action( 'admin_enqueue_scripts', 'wpep_include_scripts_easy_pay_type_only' );

function wpep_include_stylesheets() {

	if ( isset( $_REQUEST['page'] ) && sanitize_text_field($_REQUEST['page']) == 'wpep_payment_form' || isset( $_REQUEST['page'] ) && sanitize_text_field($_REQUEST['page']) == 'wpep-pro-features' || isset( $_REQUEST['page'] ) && sanitize_text_field($_REQUEST['page'] )  == 'wpep-settings' || isset( $_REQUEST['post_type'] ) && sanitize_text_field($_REQUEST['post_type'] ) == 'wpep_free_reports' ) {
		
		wp_enqueue_style( 'wpep_backend_style_free', plugin_dir_url(__FILE__).'assets/backend/css/wpep_backend_styles.css', '3.0.0' );
	}

	wp_enqueue_style( 'wpep_backend_hide_stuff',plugin_dir_url(__FILE__).'assets/backend/css/hide_stuff.css', '3.0.0' );
}

function wpep_include_scripts_easy_pay_type_only() {

	if ( isset( $_REQUEST['page'] ) && sanitize_text_field($_REQUEST['page']) == 'wpep_payment_form' || isset( $_REQUEST['page'] ) && sanitize_text_field($_REQUEST['page']) == 'wpep-pro-features' || isset( $_REQUEST['page'] ) && sanitize_text_field($_REQUEST['page']) == 'wpep-settings' || isset( $_REQUEST['post_type'] ) && sanitize_text_field($_REQUEST['post_type']) == 'wpep_free_reports' ) {
		wp_enqueue_script(
			'wpep_backend_script_free',
			plugin_dir_url(__FILE__).'assets/backend/js/wpep_backend_scripts_free.js',
			array(),
			'3.0.0',
			true
		);
		wp_enqueue_script(
			'wpep_jscolor_script',
			plugin_dir_url(__FILE__).'assets/backend/js/jscolor.js',
			array(),
			'1.0',
			true
		);
	}

}

