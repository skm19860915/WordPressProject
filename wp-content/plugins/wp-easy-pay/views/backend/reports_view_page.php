<?php

	$current_post_id = get_the_ID();

	$firstname              = get_post_meta( $current_post_id, 'wpep_first_name', true );
	$lastname               = get_post_meta( $current_post_id, 'wpep_last_name', true );
	$email                  = get_post_meta( $current_post_id, 'wpep_email', true );
	$charge_amount          = get_post_meta( $current_post_id, 'wpep_square_charge_amount', true );
	$transaction_status     = get_post_meta( $current_post_id, 'wpep_transaction_status', true );
	$transaction_id         = get_the_title( $current_post_id );
	$transaction_type       = get_post_meta( $current_post_id, 'wpep_transaction_type', true );
	$form_id                = get_post_meta( $current_post_id, 'wpep_form_id', true );
	$form_values            = get_post_meta( $current_post_id, 'wpep_form_values', true );
	$wpep_transaction_error = get_post_meta( $current_post_id, 'wpep_transaction_error', true );
	$wpep_refund_id         = get_post_meta( $current_post_id, 'wpep_square_refund_id', true );
?>
<div class="reportDetailsContainer">
  <div class="reportDetails" style="margin: 20px 0">
	  <table style="width:100%">
		<tbody>
		  <tr>
			<th><?php echo __('Payment type','wp_easy_pay'); ?></th>
			<td><?php echo esc_attr( $transaction_type ); ?></td>
		  </tr>
		  <tr>
			<th><?php echo __('Transaction ID','wp_easy_pay'); ?></th>
			<td><?php echo get_the_title(); ?></td>
		  </tr>
		  <tr>
			<th><?php echo __('Payments Amount','wp_easy_pay'); ?></th>
			<td><?php echo esc_attr($charge_amount / 100); ?></td>
		  </tr>
		  <tr>
			<th><?php echo __('Payments Status','wp_easy_pay'); ?></th>
			<td><?php echo esc_attr($transaction_status); ?></td>
		  </tr>
	

		  <?php
			if ( isset( $wpep_transaction_error ) && ! empty( $wpep_transaction_error ) ) {
				?>
		  <tr>
			<th><?php echo __('Payment Error','wp_easy_pay'); ?></th>
			<td><?php print_r( $wpep_transaction_error ); ?></td>
		  </tr>
				<?php
			}
			?>

		  <tr>
			<th><?php echo __('WPEP Form','wp_easy_pay'); ?></th>
			<td><a  target="_blank" href="<?php echo get_edit_post_link( $form_id ); ?>"> <?php echo __('click here ','wp_easy_pay'); ?></a></td>
		  </tr>
		  <tr>
			<th><?php echo __('User Name','wp_easy_pay'); ?></th>
			<td><?php echo esc_attr($firstname . ' ' . $lastname); ?></td>
		  </tr>
		  <tr>
			<th><?php echo __('User Email','wp_easy_pay'); ?></th>
			<td><?php echo esc_attr($email); ?></td>
		  </tr>
		</tbody>
	  </table>
	</div>
  </div>
