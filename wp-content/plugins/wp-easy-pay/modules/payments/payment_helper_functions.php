<?php

function wpep_square_refresh_token( $expires_at, $refresh_access_token, $type, $current_form_id ) {

	$expiry_status = wpep_check_give_square_expiry( $expires_at );

	if ( 'expired' === $expiry_status ) {

		 $oauth_connect_url = WPEP_MIDDLE_SERVER_URL;

		 $args_renew = array (

			 'body'    => array(
				 'request_type'  => 'renew_token',
				 'refresh_token' => $refresh_access_token,
				 'oauth_version' => 2,
				 'app_name'      => WPEP_SQUARE_APP_NAME,
			 ),
			 'timeout' => 45,
		 );

		 $oauth_response      = wp_remote_post( $oauth_connect_url, $args_renew );
		 $oauth_response_body = json_decode( $oauth_response['body'] );

		 if ( 'global' === $type ) {
			 update_option( 'wpep_live_token_upgraded', sanitize_text_field( $oauth_response_body->access_token ) );
			 update_option( 'wpep_refresh_token', sanitize_text_field( $oauth_response_body->refresh_token ) );
			 update_option( 'wpep_token_expires_at', sanitize_text_field( $oauth_response_body->expires_at ) );

			 echo esc_html( $type );
		 }

		 if ( 'specific' === $type ) {
			 update_post_meta( $current_form_id, 'wpep_live_token_upgraded', sanitize_text_field( $oauth_response_body->access_token ) );
			 update_post_meta( $current_form_id, 'wpep_refresh_token', sanitize_text_field( $oauth_response_body->refresh_token ) );
			 update_post_meta( $current_form_id, 'wpep_token_expires_at', sanitize_text_field( $oauth_response_body->expires_at ) );
		 }

	}

}


function wpep_check_give_square_expiry( $expires_at ) {

	$date_time    = explode( 'T', $expires_at );
	$date_time[1] = str_replace( 'Z', '', $date_time[1] );
	$expires_at   = strtotime( $date_time[0] . ' ' . $date_time[1] );
	$today        = strtotime( 'now' );

	if ( $today >= $expires_at ) {

		return 'expired';

	} else {

		return 'active';

	}

}
