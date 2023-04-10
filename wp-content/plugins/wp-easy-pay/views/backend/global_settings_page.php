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
	wp_enqueue_script( 'wpep_backend_script_free', plugin_dir_url(__FILE__).'js/wpep_backend_scripts_free.js', array(), '3.0.0', true );

if ( isset( $_POST ) && ! empty( $_POST ) ) {
if( !isset($_POST['wpep_square_form_nonce']) || !wp_verify_nonce( $_POST['wpep_square_form_nonce'], 'SquareConnectForm' ) ) {
		exit(_e('Something went wrong!', 'wp_easy_pay'));
	}
	$payment_mode            = 0;
	$wpep_square_google_pay  = 0;
	$wpep_square_apple_pay   = 0;
	$wpep_square_master_pass = 0;

	$app_id                  = sanitize_text_field($_POST['wpep_square_test_app_id_global']);
	$test_token              = sanitize_text_field( $_POST['wpep_square_test_token_global'] );
	$location_id_test        = sanitize_text_field( $_POST['wpep_square_test_location_id_global'] );
	$wpep_email_notification = sanitize_email( $_POST['wpep_email_notification'] );


	if ( isset( $_POST['wpep_square_google_pay'] ) ) {
		$wpep_square_google_pay = (int)( sanitize_text_field($_POST['wpep_square_google_pay']) );
	}

	if ( isset( $_POST['wpep_square_apple_pay'] ) ) {
		$wpep_square_apple_pay = (int)( sanitize_text_field($_POST['wpep_square_apple_pay']) );
	}

	if ( isset( $_POST['wpep_square_master_pass'] ) ) {
		$wpep_square_master_pass = (int)( sanitize_text_field($_POST['wpep_square_master_pass']) );
	}

	if ( isset( $_POST['wpep_square_payment_mode_global'] ) ) {

		$payment_mode = sanitize_text_field( $_POST['wpep_square_payment_mode_global'] );
	}

	if ( isset( $_POST['wpep_square_location_id'] ) ) {
		$location_id = sanitize_text_field( $_POST['wpep_square_location_id'] );
		update_option( 'wpep_square_location_id', $location_id );
	}

	if ( isset( $_POST['wpep_square_currency_test'] ) ) {
		$currency = sanitize_text_field( $_POST['wpep_square_currency_test'] );
		update_option( 'wpep_square_currency_test', $currency );
	}

	update_option( 'wpep_square_test_app_id_global', $app_id );
	update_option( 'wpep_square_test_token_global', $test_token );
	update_option( 'wpep_square_test_location_id_global', $location_id_test );
	update_option( 'wpep_square_payment_mode_global', $payment_mode );

	update_option( 'wpep_square_google_pay', $wpep_square_google_pay );
	update_option( 'wpep_square_apple_pay', $wpep_square_apple_pay );
	update_option( 'wpep_square_master_pass', $wpep_square_master_pass );
	update_option( 'wpep_email_notification', $wpep_email_notification );

}

	$wpep_square_payment_mode_global = get_option( 'wpep_square_payment_mode_global', true );
	$wpep_square_google_pay          = get_option( 'wpep_square_google_pay', true );
	$wpep_email_notification         = get_option( 'wpep_email_notification', false );

if ( empty( $wpep_email_notification ) && false !== $wpep_email_notification ) {

	$current_user            = wp_get_current_user();
	$wpep_email_notification = $current_user->user_email;

}

	$wpep_square_connect_url = wpep_create_connect_url( 'global' );

?>

<form class="wpeasyPay-form" method="post" action="#">
  <div class="contentWrap wpeasyPay">
	<div class="contentHeader">
	  <h3 class="blocktitle"><?php echo __('Square Connect','wp_easy_pay')?></h3>
	  <div class="swtichWrap">
		<input type="checkbox" id="on-off" name="wpep_square_payment_mode_global" class="switch-input" 
		<?php
		if ( $wpep_square_payment_mode_global != '0' ) {
			 ?>'checked'<?php 
		}
		?>
	   />
		<label for="on-off" class="switch-label">
		  <span class="toggle--on toggle--option wpep_global_mode_switch" data-mode="live"><?php echo __('Live Payment','wp_easy_pay'); ?></span>
		  <span class="toggle--off toggle--option wpep_global_mode_switch" data-mode="test"><?php echo __('Test Payment','wp_easy_pay'); ?></span>
		</label>
	  </div>
	</div>
	<div class="contentBlock">
	  <div class="squareSettings">
		<div class="settingBlock">
		  <label><?php echo __('Notifications Email','wp_easy_pay'); ?></label>
		  <input type="text" class="form-control" name="wpep_email_notification" value="<?php echo sanitize_email( $wpep_email_notification ); ?>" placeholder="<?php _e('abc@domain.com','wp_easy_pay'); ?>">
		</div>
	  </div>

	  <div class="paymentView" id="wpep_spmgt">
		<div class="testPayment">
		  <div class="testPaymentBlock">

			<div class="wpeasyPay__body">
			  <div class="form-group">
				<label><?php echo __('Test Application ID:','wp_easy_pay'); ?></label>
				<input type="text" class="form-control" placeholder="<?php  _e('Please Enter test application id','wp_easy_pay'); ?>"
				  name="wpep_square_test_app_id_global"
				  value="<?php echo get_option( 'wpep_square_test_app_id_global' ); ?>" />
			  </div>

			  <div class="form-group">
				<label><?php echo __('Test Token:','wp_easy_pay'); ?></label>
				<input type="text" class="form-control" placeholder="<?php  _e('Please Enter test test token','wp_easy_pay'); ?>"
				  name="wpep_square_test_token_global"
				  value="<?php echo get_option( 'wpep_square_test_token_global' ); ?>" />

			  </div>
			   <div class="form-group">
			
					<input type="hidden" name="wpep_square_form_nonce" value="<?php echo wp_create_nonce('SquareConnectForm'); ?>" >
				
			  </div>
			  <div class="form-group">
				<label><?php echo __('Test Location ID:','wp_easy_pay'); ?></label>
				<input type="text" class="form-control" placeholder="<?php  _e('Please Enter test location id','wp_easy_pay'); ?>"
				  name="wpep_square_test_location_id_global"
				  value="<?php echo get_option( 'wpep_square_test_location_id_global' ); ?>" />
			  </div>


			  <div class="form-group">
				<label><?php echo __('Country Currency','wp_easy_pay'); ?></label>
				<select name="wpep_square_currency_test" class="form-control">
				  <option value=""><?php echo __('--Select Currency--','wp_easy_pay'); ?></option>
				  <option value="USD" 
				  <?php
					if ( ! empty( get_option( 'wpep_square_currency_test' ) ) &&  'USD'  == get_option( 'wpep_square_currency_test' ) ) :
						echo "selected='selected'";
endif;
					?>
					><?php echo __('USD','wp_easy_pay'); ?></option>
				  <option value="CAD" 
				  <?php
					if ( ! empty( get_option( 'wpep_square_currency_test' ) ) && 'CAD'  == get_option( 'wpep_square_currency_test' ) ) :
						echo "selected='selected'";
endif;
					?>
					 ><?php echo __('CAD','wp_easy_pay'); ?></option>
				  <option value="AUD" 
				  <?php
					if ( ! empty( get_option( 'wpep_square_currency_test' ) ) &&  'AUD'  == get_option( 'wpep_square_currency_test' ) ) :
						echo "selected='selected'";
endif;
					?>
					 ><?php echo __('AUD','wp_easy_pay'); ?></option>
				  <option value="JPY" 
				  <?php
					if ( ! empty( get_option( 'wpep_square_currency_test' ) ) &&   'JPY' == get_option( 'wpep_square_currency_test' ) ) :
						echo "selected='selected'";
endif;
					?>
					 ><?php echo __('JPY','wp_easy_pay'); ?></option>
				  <option value="GBP" 
				  <?php
					if ( ! empty( get_option( 'wpep_square_currency_test' ) ) &&  'GBP' == get_option( 'wpep_square_currency_test' ) ) :
						echo "selected='selected'";
endif;
					?>
					 ><?php echo __('GBP','wp_easy_pay'); ?></option>
				  <option value="EUR" 
				  <?php
					if ( ! empty( get_option( 'wpep_square_currency_test' ) ) &&   'EUR' == get_option( 'wpep_square_currency_test' ) ) :
						echo "selected='selected'";
endif;
					?>
					 ><?php echo __('EUR','wp_easy_pay'); ?></option>
				</select>
			  </div>

			  <div class="btnSettings">
				<button type="submit" class="btn btn-primary"><?php echo __(' Save Settings','wp_easy_pay'); ?> </button>
			  </div>
			</div>



		  </div>
		  <div class="tutorialVideo">
			<iframe width="100%" height="295" src="https://www.youtube.com/embed/9QPJTprZeVc" frameborder="0"
			  allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
		  </div>
		</div>

	  </div>
	  <div class="livePayment paymentView" id="wpep_spmgl">
		<?php

		$wpep_square_live_token = get_option( 'wpep_live_token' );


		if ( $wpep_square_live_token == false ) {

			?>

		<div class="squareConnect">
		  <div class="squareConnectwrap">
			<h2><?php echo __('Connect your square account now!','wp_easy_pay'); ?></h2>

			<?php
			if ( isset( $_GET['type'] ) && 'bad_request.missing_parameter' == sanitize_text_field($_GET['type'] )) {
				?>

				<p style="color: red;"> <?php echo __('You have denied WP EASY PAY the permission to access your Square account. Please connect again to and click allow to complete OAuth.','wp_easy_pay'); ?> </p>

				<?php
			}
			?>


			<a href="<?php echo esc_url($wpep_square_connect_url); ?>" class="btn btn-primary btn-square"><?php echo __('Connect Square','wp_easy_pay'); ?></a>
			<a class="connectSquarePop" href="https://wpeasypay.com/documentation/#global-settings-live-mode" target="_blank">
		
		<?php echo __('How to Connect Your Live Square Account.','wp_easy_pay'); ?>
		
			</a>

		  </div>
		</div>

			<?php

		} else {
			?>

		<div class="squareConnected">
		  <h3 class="titleSquare"><?php echo __('Square is Connected','wp_easy_pay'); ?> <i class="fa fa-check-square" aria-hidden="true"></i></h3>
		  <div class="wpeasyPay__body">

			<div class="form-group">
			  <label><?php echo __('Location:','wp_easy_pay'); ?></label>
			  <select class="form-control" name="wpep_square_location_id">
				<option><?php echo __('Select Location','wp_easy_pay'); ?></option>

				<?php

				$saved_location_id = get_option( 'wpep_square_location_id', false );
				if ( ! empty( $saved_location_id ) ) {
					update_option( 'wpep_square_location_id', $saved_location_id );
				}

				$saved_form_live_locations = get_option( 'wpep_live_locations', false );

				if ( ! empty( $saved_form_live_locations ) ) {
					update_option( 'wpep_live_location_data', $saved_form_live_locations );
					$all_locations = get_option( 'wpep_live_location_data', false );
					// getting currency from square account dynamically
					update_option( 'wpep_square_currency_new', $all_locations[0]->currency_code );
					foreach ( $all_locations as $location ) {

						if ( $saved_location_id !== false ) {
							if ( $saved_location_id == $location->id ) {
								$selected = __('selected','wp_easy_pay');
							} else {
								$selected = '';
							}
						} ?>
						<option value="<?php echo esc_attr( $location->id ); ?>" <?php echo esc_attr($selected); ?>><?php echo esc_attr($location->location_details->nickname ); ?></option>

					<?php }
				} else {

					$all_locations = get_option( 'wpep_live_location_data', false );
					foreach ( $all_locations as $location ) {

						if ( $saved_location_id !== false ) {

							if ( $saved_location_id == $location['location_id'] ) {

								$selected =  __('selected','wp_easy_pay');

							} else {

								$selected = '';
							}
						} ?>
						<option value="<?php echo esc_attr($location['location_id']); ?>" <?php echo esc_attr($selected); ?>><?php echo esc_attr($location['location_name']); ?></option>

					<?php }
				}

				?>

			  </select>
			</div>

			  <?php
				if ( '' != get_option( 'wpep_square_currency_new' ) ) {
					?>
				<div class="form-group">
				  <label><?php echo __('Country Currency','wp_easy_pay'); ?></label>
				  <select name="wpep_square_currency_new" class="form-control" disabled="disabled">
					  <option value="USD" 
					  <?php
						if ( ! empty( get_option( 'wpep_square_currency_new' ) ) && 'USD' == get_option( 'wpep_square_currency_new' ) ) :
							echo "selected='selected'";
endif;
						?>
						><?php echo __('USD','wp_easy_pay')?></option>
					  <option value="CAD" 
					  <?php
						if ( ! empty( get_option( 'wpep_square_currency_new' ) ) &&  'CAD'  == get_option( 'wpep_square_currency_new' ) ) :
							echo "selected='selected'";
endif;
						?>
						 ><?php echo __('CAD','wp_easy_pay')?></option>
					  <option value="AUD" 
					  <?php
						if ( ! empty( get_option( 'wpep_square_currency_new' ) ) &&  'AUD' == get_option( 'wpep_square_currency_new' ) ) :
							echo "selected='selected'";
endif;
						?>
						 ><?php echo __('AUD','wp_easy_pay')?></option>
					  <option value="JPY" 
					  <?php
						if ( ! empty( get_option( 'wpep_square_currency_new' ) ) && 'JPY' == get_option( 'wpep_square_currency_new' ) ) :
							echo "selected='selected'";
endif;
						?>
						 ><?php echo __('JPY','wp_easy_pay')?></option>
					  <option value="GBP" 
					  <?php
						if ( ! empty( get_option( 'wpep_square_currency_new' ) ) && 'GBP' == get_option( 'wpep_square_currency_new' ) ) :
							echo "selected='selected'";
endif;
						?>
						 ><?php echo __('GBP','wp_easy_pay')?></option>
					  <option value="EUR" 
					  <?php
						if ( ! empty( get_option( 'wpep_square_currency_new' ) ) &&  'EUR' == get_option( 'wpep_square_currency_new' ) ) :
							echo "selected='selected'";
endif;
						?>
						 ><?php echo __('EUR','wp_easy_pay'); ?></option>
				  </select>
				</div>
				<?php } ?>

		  </div>

				
		<div class="paymentint">
		</div>

		
		<div class="btnFooter d-btn">

			<?php

			  $disconnectUrl = get_option( 'wpep_square_disconnect_url', false );



			$queryArg = array(

				'app_name'               => WPEP_SQUARE_APP_NAME,
				'wpep_disconnect_square' => 1,
				'wpep_disconnect_global' => 'true',

			);

			$queryArg['wpep_disconnect_global'] = 'true';


			$disconnectUrl = admin_url( 'admin.php' );

			$disconnectUrl = add_query_arg( $queryArg, $disconnectUrl );
			?>
		  <button type="submit" class="btn btn-primary"> <?php _e('Save Settings','wp_easy_pay'); ?> </button>
		  <a href="<?php echo esc_url($disconnectUrl); ?>" class="btn btnDiconnect"><?php echo __('Disconnect
			Square','wp_easy_pay'); ?></a>

		</div>

			<?php
		}
		?>
	  </div>



	</div>
</form>
</div>