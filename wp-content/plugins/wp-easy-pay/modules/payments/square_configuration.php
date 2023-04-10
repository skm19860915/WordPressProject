<?php
/**
 * WP EASY PAY
 *
 * PHP version 7
 *
 * @category Wordpress_Plugin
 * @package  WP_Easy_Pay
 * @author   Author <contact@apiexperts.io>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     http://wpeasypay.com/
 */

 require_once WPEP_ROOT_PATH . 'assets/lib/square-sdk/autoload.php';

function wpep_setup_square_with_access_token( $wpep_access_token ) {

	$apiConfig = new \SquareConnect\Configuration();
	$apiConfig->setHost( 'https://connect.squareup.com' );
	$apiConfig->setAccessToken( $wpep_access_token );

	$apiClient = new \SquareConnect\ApiClient( $apiConfig );

	return $apiClient;
}