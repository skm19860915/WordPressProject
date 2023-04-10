<?php

add_action( 'wp_ajax_nopriv_wpep_free_payment_request', 'wpep_free_payment_request' );
add_action( 'wp_ajax_wpep_free_payment_request', 'wpep_free_payment_request' );

function wpep_free_payment_request() {


	$current_form_id = 1;

	wpep_square_refresh_token();
	$liveMode = get_option( 'wpep_square_payment_mode_global', true );

    

	$apiConfig = new \SquareConnect\Configuration();

	if ( 'on' === $liveMode ) {
		$currency    = get_option( 'wpep_square_currency_new', true );
		$accessToken = get_option( 'wpep_live_token_upgraded' );
		$apiConfig->setHost( 'https://connect.squareup.com' );
		$apiConfig->setAccessToken( $accessToken );
		$location_id = get_option( 'wpep_square_location_id', true );

	} else {
		$currency    = get_option( 'wpep_square_currency_test', true );
		$accessToken = get_option( 'wpep_square_test_token_global' );
		$apiConfig->setHost( 'https://connect.squareupsandbox.com' );
		$apiConfig->setAccessToken( $accessToken );
		$location_id = get_option( 'wpep_square_test_location_id_global', true );
	}

	$api_client   = new \SquareConnect\ApiClient( $apiConfig );
	$payments_api = new \SquareConnect\Api\PaymentsApi( $api_client );

	if ( isset( $_POST['nonce'] ) ) {
		$nonce = sanitize_text_field( wp_unslash( $_POST['nonce'] ) );
	}

	if ( isset( $_POST['amount'] ) ) {
		$amount = (int)( wp_unslash( sanitize_text_field($_POST['amount'] )) );
	}

	if ( isset( $_POST['verification_token'] ) ) {
		$verification_token = sanitize_text_field( wp_unslash( $_POST['verification_token'] ) );
	}

	if ( $currency == 'JPY' ) {

		$amount = $amount / 100;
	}

	$body = new \SquareConnect\Model\CreatePaymentRequest();

	$amountMoney = new \SquareConnect\Model\Money();
	   
	$amountMoney->setAmount( $amount );
	$amountMoney->setCurrency( $currency );
	$body->setSourceId( $nonce );
	$body->setAmountMoney( $amountMoney );
	$body->setLocationId( $location_id );
	$body->setIdempotencyKey( uniqid() );
	$body->setVerificationToken( $verification_token );

	if ( isset( $_POST['first_name'] ) ) {
		$first_name = sanitize_text_field( wp_unslash( $_POST['first_name'] ) );
	}

	if ( isset( $_POST['last_name'] ) ) {
		$last_name = sanitize_text_field( wp_unslash( $_POST['last_name'] ) );
	}

	if ( isset( $_POST['email'] ) ) {
		$email = sanitize_email( wp_unslash( $_POST['email'] ) );
	}
	$order_note = $first_name . ' ' . $last_name . ' ' . $email;
	$body->setNote( $order_note );

	try {
	
		$result             = $payments_api->createPayment( $body );
		$transaction_id     = $result->getPayment()->getId();
		$transaction_status = $result->getPayment()->getStatus();
		$transaction_data   = array(
			'transaction_id'     => $transaction_id,
			'transaction_status' => $transaction_status,
		);

		if ( isset( $_POST['first_name'] ) ) {
			$first_name = sanitize_text_field( wp_unslash( $_POST['first_name'] ) );
		}

		if ( isset( $_POST['last_name'] ) ) {
			$last_name = sanitize_text_field( wp_unslash( $_POST['last_name'] ) );
		}

		if ( isset( $_POST['email'] ) ) {
			$email = sanitize_email( wp_unslash( $_POST['email'] ) );
		}

		if ( isset( $_POST['amount'] ) ) {
			$amount = (int)( wp_unslash( sanitize_text_field($_POST['amount'] )) );
		}

		$personal_information = array(

			'first_name'      => $first_name,
			'last_name'       => $last_name,
			'email'           => $email,
			'amount'          => $amount,
			'current_form_id' => $current_form_id,
		);
	
		require_once WPEP_ROOT_PATH . 'modules/reports/transaction_report.php';
		wpep_single_transaction_report( $transaction_data, $current_form_id, $personal_information );
		$notification_email = get_option( 'wpep_free_notify_email', true );

		$headers  = 'From: ' . get_bloginfo( 'name' ) . " <no-reply@wpeasypay.com> \r\n";
		$headers .= "Reply-To: no-reply@wpeasypay.com \r\n";
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-Type: text/html; charset=UTF-8\r\n";
		$headers  = array( 'Content-Type: text/html; charset=UTF-8' );
		$subject  = 'New Payment';

		$message  = 'First Name: ' . $first_name . '<br/><br/>';
		$message .= 'Last Name: ' . $last_name . '<br/><br/>';
		$message .= 'Email Address: ' . $email . '<br><br/>';
		$message .= 'Amount: ' . ( $amount / 100 ) . ' ' . $currency . '<br/><br/>';
		$message .= 'Transaction ID: ' . $transaction_id;

		wp_mail( $notification_email, $subject, $message, $headers );

		wp_die( 'success' );

	} catch ( \SquareConnect\ApiException $e ) {
	
		wp_die( wp_json_encode( $e->getResponseBody()->errors[0] ) );

	}

}

function wpep_square_refresh_token() {

	$live_token           = get_option( 'wpep_live_token', false );
	$refresh_access_token = get_option( 'wpep_refresh_token', false );
	$expires_at           = get_option( 'wpep_token_expires_at', false );
	$type                 = 'global';

	$expiry_status = wpep_check_give_square_expiry( $expires_at );

	if ( 'expired' === $expiry_status ) {

		$oauth_connect_url = WPEP_MIDDLE_SERVER_URL;

		$args_renew = array(

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

		if ( 'global' == $type ) {

			update_option( 'wpep_live_token_upgraded', sanitize_text_field( $oauth_response_body->access_token ) );
			update_option( 'wpep_refresh_token', $oauth_response_body->refresh_token );
			update_option( 'wpep_token_expires_at', $oauth_response_body->expires_at );

		}

		if ( 'specific' == $type ) {

			update_post_meta( $current_form_id, 'wpep_live_token_upgraded', sanitize_text_field( $oauth_response_body->access_token ) );
			update_post_meta( $current_form_id, 'wpep_refresh_token', $oauth_response_body->refresh_token );
			update_post_meta( $current_form_id, 'wpep_token_expires_at', $oauth_response_body->expires_at );

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
