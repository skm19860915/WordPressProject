<?php

function wpep_single_transaction_report( $transaction_data, $post_id, $personal_information ) {

	$transaction_report = array(

		'post_title'  => sanitize_key( $transaction_data['transaction_id'] ),
		'post_type'   => 'wpep_free_reports',
		'post_status' => 'publish',
	);

	$transaction_report_id = wp_insert_post( $transaction_report );
	$payment_type          = get_option( 'wpep_free_form_type', true );

	update_post_meta( $transaction_report_id, 'wpep_first_name', $personal_information['first_name'] );
	update_post_meta( $transaction_report_id, 'wpep_last_name', $personal_information['last_name'] );
	update_post_meta( $transaction_report_id, 'wpep_email', $personal_information['email'] );
	update_post_meta( $transaction_report_id, 'wpep_square_charge_amount', $personal_information['amount'] );
	update_post_meta( $transaction_report_id, 'wpep_subscription_post_id', $post_id );
	update_post_meta( $transaction_report_id, 'wpep_transaction_status', $transaction_data['transaction_status'] );
	update_post_meta( $transaction_report_id, 'wpep_transaction_type', $payment_type );
	update_post_meta( $transaction_report_id, 'wpep_form_id', $personal_information['current_form_id'] );

}
