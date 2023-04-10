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

require_once plugin_dir_path(__FILE__) . 'square_configuration.php';

add_action( 'admin_init', 'wpep_authorize_with_square' );
add_action( 'admin_init', 'wpep_square_callback_success' );
add_action( 'admin_init', 'wpep_square_disconnect' );

function wpep_sanitize_request( $request ) {
	$sanitized = array();
	if(!is_array($request)) {
		$sanitized = sanitize_text_field($request);
	} else {
		foreach($request as $key => $to_sanitize) {
			if(is_array($to_sanitize)) {
				wpep_sanitize_request($to_sanitize);
			} else {
				$sanitized[$key] = sanitize_text_field($to_sanitize);
			}
		}
	}
	
	return $sanitized;
}

function wpep_authorize_with_square() {
	
	if ( ! empty( $_GET['wpep_prepare_connection_call'] ) ) {

		$urlIdentifiers                  = wpep_sanitize_request($_REQUEST);
		$urlIdentifiers['oauth_version'] = 2;
		$urlIdentifiers['random']        = wp_rand( 10, 9877 );
		unset( $urlIdentifiers['wpep_prepare_connection_call'] );
		$redirectUrl = add_query_arg( $urlIdentifiers, admin_url( $urlIdentifiers['wpep_admin_url'] ) );
		$redirectUrl = wp_nonce_url( $redirectUrl, 'connect_wpep_square', 'wpep_square_token_nonce' );
		$usf_state   = substr( str_shuffle( 'ABCDEFGHIJKLMNOPQRSTUVWXYZ' ), 1, 10 );

		$middleServerData = array(

			'redirect'      => urlencode( $redirectUrl ),
			'scope'         => urlencode( 'MERCHANT_PROFILE_READ PAYMENTS_READ PAYMENTS_WRITE INVENTORY_WRITE CUSTOMERS_READ CUSTOMERS_WRITE' ),
			'plug'          => WPEP_SQUARE_PLUGIN_NAME,
			'app_name'      => WPEP_SQUARE_APP_NAME,
			'oauth_version' => 2,
			'request_type'  => 'authorization',
			'usf_state'     => $usf_state,

		);

		$middleServerUrl = add_query_arg( $middleServerData, WPEP_MIDDLE_SERVER_URL );

		update_option( 'wpep_usf_state', $usf_state );

		$queryArg = array(

			'app_name'               => WPEP_SQUARE_APP_NAME,
			'wpep_disconnect_square' => 1,
			'wpep_disconnect_global' => 'true',

		);

		if ( isset( $_REQUEST['wpep_page_post'] ) ) {
			$wpep_page_post = sanitize_text_field( wp_unslash( $_REQUEST['wpep_page_post'] ) );
		}

		if ( isset( $wpep_page_post ) && ! empty( $wpep_page_post ) && 'global' === $wpep_page_post ) {

			$queryArg['wpep_disconnect_global'] = 'true';

			$queryArg      = array_merge( $urlIdentifiers, $queryArg );
			$disconnectUrl = admin_url( $urlIdentifiers['wpep_admin_url'] );
			$disconnectUrl = add_query_arg( $queryArg, $disconnectUrl );

			update_option( 'wpep_square_disconnect_url', $disconnectUrl );

		}

		if ( isset( $wpep_page_post ) && ! empty( $wpep_page_post ) && 'global' !== $wpep_page_post ) {

			$queryArg['wpep_disconnect_global'] = 'false';
			$queryArg['wpep_form_id']           = $wpep_page_post;

			$queryArg      = array_merge( $urlIdentifiers, $queryArg );
			$disconnectUrl = admin_url( $urlIdentifiers['wpep_admin_url'] );
			$disconnectUrl = add_query_arg( $queryArg, $disconnectUrl );
			update_post_meta( $queryArg['wpep_form_id'], 'wpep_square_disconnect_url', $disconnectUrl );

		}
		wp_redirect( $middleServerUrl );

	}

}

function wpep_square_callback_success() {

	if ( isset( $_REQUEST['access_token'] ) ) {
		$access_token = sanitize_text_field( wp_unslash( $_REQUEST['access_token'] ) );
	}

	if ( isset( $_REQUEST['token_type'] ) ) {
		$token_type = sanitize_text_field( wp_unslash( $_REQUEST['token_type'] ) );
	}

	if ( isset( $_REQUEST['wpep_square_token_nonce'] ) ) {
		$wpep_square_token_nonce = sanitize_text_field( wp_unslash( $_REQUEST['wpep_square_token_nonce'] ) );
	}

	if ( ! empty( $access_token ) and ! empty( $token_type ) and ! empty( $wpep_square_token_nonce ) and $token_type === 'bearer' ) {

		if ( function_exists( 'wp_verify_nonce' ) && ! wp_verify_nonce( $wpep_square_token_nonce, 'connect_wpep_square' ) ) {
			wp_die( 'Looks like the URL is malformed!' );
		}

		$usf_state = get_option( 'wpep_usf_state' );

		if ( $usf_state !== $_REQUEST['usf_state'] ) {
			wp_die( 'The request is not coming back from the same origin it was sent to. Try Later' );
		}

		$initialPage = 0;

		$apiClient     = wpep_setup_square_with_access_token( $access_token );
		$locations_api = new \SquareConnect\Api\LocationsApi( $apiClient );
		$locations     = $locations_api->listLocations()->getLocations();
		$all_locations = array();

		foreach ( $locations as $key => $location ) {

			$one_location = array(
				'location_id'   => $location->getId(),
				'location_name' => $location->getName(),
				'currency'      => $location->getCurrency(),
			);

			array_push( $all_locations, $one_location );

		}

		// getting currency from square account dynamically
		update_option( 'wpep_square_currency_new', $all_locations[0]['currency'] );

		if ( isset( $_REQUEST['wpep_page_post'] ) ) {
			$wpep_page_post = sanitize_text_field( wp_unslash( $_REQUEST['wpep_page_post'] ) );
		}

		if ( isset( $_REQUEST['refresh_token'] ) ) {
			$refresh_token = sanitize_text_field( wp_unslash( $_REQUEST['refresh_token'] ) );
		}

		if ( isset( $_REQUEST['expires_at'] ) ) {
			$expires_at = sanitize_text_field( wp_unslash( $_REQUEST['expires_at'] ) );
		}

		if ( isset( $wpep_page_post ) && ! empty( $wpep_page_post ) && 'global' !== $wpep_page_post ) {

			$current_post_id = $wpep_page_post;
			update_post_meta( $current_post_id, 'wpep_live_token_details_upgraded', wpep_sanitize_request($_REQUEST) );
			update_post_meta( $current_post_id, 'wpep_live_location_data', $all_locations );
			update_post_meta( $current_post_id, 'wpep_live_token_upgraded', sanitize_text_field( $access_token ) );
			update_post_meta( $current_post_id, 'wpep_live_token', sanitize_text_field( $access_token ) );
			update_post_meta( $current_post_id, 'wpep_square_btn_auth', 'true' );
			update_post_meta( $current_post_id, 'wpep_refresh_token', $refresh_token );
			update_post_meta( $current_post_id, 'wpep_token_expires_at', $expires_at );
			update_post_meta( $current_post_id, 'wpep_live_square_app_id', sanitize_text_field($_REQUEST['appid']) );
			update_post_meta( $current_post_id, 'wpep_post_square_currency_new', $all_locations[0]['currency'] );

			$queryArgs = array(
				'post'   => sanitize_text_field(wp_unslash($_REQUEST['post'])),
				'action' => sanitize_text_field(wp_unslash($_REQUEST['action'])),
			);

			$initialPage = add_query_arg( $queryArgs, admin_url( 'post.php' ) );
		}

		if ( isset( $_REQUEST['wpep_page_post'] ) && ! empty( $_REQUEST['wpep_page_post'] ) && sanitize_text_field($_REQUEST['wpep_page_post']) == 'global' ) {

			update_option( 'wpep_live_location_data', $all_locations );
			update_option( 'wpep_live_token_upgraded', sanitize_text_field( $access_token ) );
			update_option( 'wpep_live_token', sanitize_text_field( $access_token ) );
			update_option( 'wpep_square_btn_auth', 'true' );

			update_option( 'wpep_refresh_token', $refresh_token );
			update_option( 'wpep_token_expires_at', $expires_at );
			update_option( 'wpep_live_square_app_id', sanitize_text_field($_REQUEST['appid']) );

			$queryArgs = array(
				'page'      =>sanitize_text_field( wp_unslash( $_REQUEST['page'])),
				'post_type' => sanitize_text_field( wp_unslash( $_REQUEST['wpep_post_type']) ),
			);

			$initialPage = add_query_arg( $queryArgs, admin_url( 'edit.php' ) );

		}

		$initialPage = admin_url( 'admin.php?page=wpep-settings' );

		wp_redirect( $initialPage );

	}

}

function wpep_square_disconnect() {

	if ( ! empty( $_REQUEST['wpep_disconnect_square'] ) ) {

		if ( isset( $_REQUEST['wpep_disconnect_global'] ) ) {

			if ( 'true' === sanitize_text_field($_REQUEST['wpep_disconnect_global']) ) {

				$access_token = get_option( 'wpep_live_token_upgraded', false );
				wpep_revoke_access_token( $access_token, 'live' );

				delete_option( 'wpep_live_token_details_upgraded' );
				delete_option( 'wpep_live_token_upgraded' );
				delete_option( 'wpep_live_token' );
				delete_option( 'wpep_square_btn_auth' );
				delete_option( 'wpep_refresh_token' );
				delete_option( 'wpep_token_expires_at' );
				delete_option( 'wpep_live_location_data' );
				delete_option( 'wpep_square_currency_new' );

				if ( isset( $_REQUEST['page'] ) && isset( $_REQUEST['wpep_post_type'] ) ) {
					$queryArgs = array(
						'page'      => sanitize_text_field( wp_unslash( $_REQUEST['page'] ) ),
						'post_type' => sanitize_text_field( wp_unslash( $_REQUEST['wpep_post_type'] ) ),
					);
				}

				$initialPage = add_query_arg( $queryArgs, admin_url( 'edit.php' ) );

			}

			if ( 'false' === sanitize_text_field($_REQUEST['wpep_disconnect_global']) ) {

				$access_token = get_option( 'wpep_live_token_upgraded', false );
				wpep_revoke_access_token( $access_token, 'live' );

				$form_id = sanitize_text_field($_REQUEST['wpep_form_id']);
				delete_post_meta( $form_id, 'wpep_live_token_details_upgraded' );
				delete_post_meta( $form_id, 'wpep_live_location_data' );
				delete_post_meta( $form_id, 'wpep_live_token_upgraded' );
				delete_post_meta( $form_id, 'wpep_square_btn_auth' );
				delete_post_meta( $form_id, 'wpep_refresh_token' );
				delete_post_meta( $form_id, 'wpep_token_expires_at' );
				delete_post_meta( $form_id, 'wpep_live_square_app_id' );

				if ( isset( $_REQUEST['post'] ) && isset( $_REQUEST['action'] ) ) {
					$queryArgs = array(
						'post'   => sanitize_text_field( wp_unslash( $_REQUEST['post'] ) ),
						'action' => sanitize_text_field( wp_unslash( $_REQUEST['action'] ) ),
					);
				}
			

				$initialPage = add_query_arg( $queryArgs, admin_url( 'post.php' ) );
			}
		}

		$initialPage = admin_url( 'admin.php?page=wpep-settings' );

		wp_redirect( $initialPage );

	}

}


function wpep_revoke_access_token( $access_token, $sandbox ) {

	$response = wp_remote_post( 'https://connect.apiexperts.io/', array(
		'method'      => 'POST',
		'timeout'     => 45,
		'redirection' => 5,
		'httpversion' => '1.0',
		'blocking'    => true,
		'headers'     => array(),
		'body'        => array (
			'oauth_version' => '2',
			'request_type' => 'revoke_token',
			'app_name' => WPEP_SQUARE_APP_NAME,
			'sandbox_enabled' => $sandbox,
			'access_token' => $access_token
		),
		'cookies'     => array()
		)
	);
	 
	if ( is_wp_error( $response ) ) {
		$error_message = $response->get_error_message();
		echo "__(Something went wrong: $error_message)";
	} else {

	}
	
	
}
