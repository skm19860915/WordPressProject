<?php

if ( isset( $_POST ) && ! empty( $_POST ) ) {
	if( !isset($_POST['wpep_payment_form_nonce']) || !wp_verify_nonce( $_POST['wpep_payment_form_nonce'], 'paymentForm' ) ) {
		exit(_e('Something went wrong!', 'wp_easy_pay'));
	}
	$wpep_free_notify_email              = sanitize_email($_POST['wpep_free_notify_email']);
	$wpep_free_btn_text                  = sanitize_text_field($_POST['wpep_free_btn_text']);
	$wpep_free_popup_btn_text            = sanitize_text_field($_POST['wpep_free_popup_btn_text']);
	$wpep_free_amount                    = sanitize_text_field($_POST['wpep_free_amount']);
	$wpep_free_success_url               = esc_url_raw($_POST['wpep_free_success_url']);
	$wpep_free_organization_name         = sanitize_text_field($_POST['wpep_free_org_name']);
	$wpep_theme_color                    = sanitize_text_field($_POST['wpep_form_theme_color']);
	$wpep_display_type                   = sanitize_text_field(@$_POST['wpep_free_form_display_type']);
	$wpep_redirection_on_success         = sanitize_text_field($_POST['wpep_redirection_on_success']);
	$wpep_free_redirection_in_secs       = sanitize_text_field($_POST['wpep_free_redirection_in_secs']);
	$wpep_free_payment_success_btn_label = sanitize_text_field($_POST['wpep_free_payment_success_btn_label']);
	$wpep_free_payment_success_message   = sanitize_text_field($_POST['wpep_free_payment_success_message'] );

	if ( isset( $_POST['wpep_btn_theme'] ) ) {
		$wpep_btn_theme = sanitize_text_field( $_POST['wpep_btn_theme'] );
	}

	if ( isset( $_POST['wpep_show_shadow'] ) ) {
		$wpep_show_shadow = sanitize_text_field( $_POST['wpep_show_shadow'] );
	}

	if ( ! isset( $_POST['wpep_free_user_set_amount'] ) ) {
		$wpep_free_user_set_amount = 'off';
	} else {
		$wpep_free_user_set_amount = sanitize_text_field( $_POST['wpep_free_user_set_amount'] );
	}
	
	$wpep_free_form_type = sanitize_text_field ( $_POST['wpep_free_form_type'] );

	update_option( 'wpep_free_notify_email', $wpep_free_notify_email );
	update_option( 'wpep_free_btn_text', $wpep_free_btn_text );
	update_option( 'wpep_free_popup_btn_text', $wpep_free_popup_btn_text );
	update_option( 'wpep_free_amount', $wpep_free_amount );
	update_option( 'wpep_free_success_url', $wpep_free_success_url );
	update_option( 'wpep_free_org_name', $wpep_free_organization_name );
	update_option( 'wpep_free_user_set_amount', $wpep_free_user_set_amount );
	update_option( 'wpep_free_form_type', $wpep_free_form_type );
	update_option( 'wpep_form_theme_color', $wpep_theme_color );
	update_option( 'wpep_free_form_display_type', $wpep_display_type );
	update_option( 'wpep_redirection_on_success', $wpep_redirection_on_success );
	update_option( 'wpep_free_redirection_in_secs', $wpep_free_redirection_in_secs );
	update_option( 'wpep_free_payment_success_btn_label', $wpep_free_payment_success_btn_label );
	update_option( 'wpep_free_payment_success_message', $wpep_free_payment_success_message );

	if ( isset( $wpep_btn_theme ) ) {
		update_option( 'wpep_btn_theme', $wpep_btn_theme );
	} else {
		update_option( 'wpep_btn_theme', '' );
	}

	if ( isset( $wpep_show_shadow ) ) {
		update_option( 'wpep_show_shadow', $wpep_show_shadow );
	} else {
		update_option( 'wpep_show_shadow', '' );
	}
}

	$wpep_form_theme_color               = ! empty( get_option( 'wpep_form_theme_color' ) ) ? get_option( 'wpep_form_theme_color' ) : '#5d97ff';
	$wpep_redirection_on_success         = get_option( 'wpep_redirection_on_success', false );
	$wpep_free_redirection_in_secs       = get_option( 'wpep_free_redirection_in_secs', false );
	$wpep_free_payment_success_btn_label = get_option( 'wpep_free_payment_success_btn_label', false );
	$wpep_free_payment_success_message   = get_option( 'wpep_free_payment_success_message', false );
	$wpep_btn_theme                      = get_option( 'wpep_btn_theme', false );
	$wpep_show_shadow                    = get_option( 'wpep_show_shadow', false );
?>

<?php
if ( $wpep_btn_theme == 'on' ) {
	$btn_theme_class = 'class= "wpep-btn wpep-btn-primary wpep-popup-btn" ' . 'style="background-color:#' . get_option( 'wpep_form_theme_color', true ) . '"';
} else {
	$btn_theme_class = 'class= "wpep-popup-btn" ';
}
?>

<div class="wpep_free_payment_form_page wpep_container">
	
	<div class="wpep_row">

		<div class="wpep_col-12">
			
			<a href="https://squareup.com/signup?lang_code=en" class="free-signup" target="_blank">
					
				<img src="<?php echo esc_url(WPEP_ROOT_URL . '/assets/backend/img/signup.png'); ?>" class="wpep-img-responsive" alt="">
		
			</a>

			<div class="sep25px">&nbsp;</div>

		</div>

	</div>

	<div class="wpep_row">

		<div class="wpep_col-12">
			
			<form action="#" method="POST" class="wpep-form" enctype="multipart/form-data">
				
				<div class="wpep-form-group">
					<label><?php echo __('Shortcode:','wp_easy_pay'); ?></label>
					<input type="text" class="wpep-form-control" value="[wpep_form]" readonly>
				</div>

				<div class="wpep-form-group">
					<label><?php echo __('Display Type','wp_easy_pay'); ?>&nbsp;&nbsp;&nbsp;&nbsp;</label>
					<input type="radio" class="" name="wpep_free_form_display_type" id="popup_check" value="popup" <?php echo get_option( 'wpep_free_form_display_type' ) == 'popup' ? 'checked' : ''; ?>>&nbsp;<label for="popup_check"><?php echo __('Pop Up','wp_easy_pay'); ?></label>&nbsp;&nbsp;&nbsp;&nbsp;
					<input type="radio" class="" name="wpep_free_form_display_type" id="on_page_check" value="on_page" <?php echo get_option( 'wpep_free_form_display_type' ) == 'on_page' ? 'checked' : ''; ?>>&nbsp;<label for="on_page_check"><?php echo __('On Page','wp_easy_pay'); ?></label>&nbsp;&nbsp;&nbsp;&nbsp;
				</div>

				<div class="wpep-form-group">
					<label><?php echo __('Pop Up Button Text:','wp_easy_pay'); ?></label>
					<input type="text" class="wpep-form-control" Placeholder="Please enter the popup button text" name="wpep_free_popup_btn_text" value="<?php
					if ( get_option( 'wpep_free_popup_btn_text' ) != '' ) :
						echo esc_attr(get_option( 'wpep_free_popup_btn_text' ));
					else :
						echo esc_attr('Popup');
					endif;
					?>" >
				</div>

				<div class="wpep-form-group">
					<label><?php echo __('Notification Email:','wp_easy_pay'); ?></label>
					<input type="text" class="wpep-form-control" Placeholder="Please enter your email" name="wpep_free_notify_email" value="<?php echo get_option( 'wpep_free_notify_email' ); ?>" >
				</div>

				<div class="wpep-form-group">
					<label>Type:&nbsp;&nbsp;&nbsp;&nbsp;</label>
					<input type="radio" class="" name="wpep_free_form_type" id="simple-check" value="simple" <?php echo get_option( 'wpep_free_form_type' ) == 'simple' ? 'checked' : ''; ?>>&nbsp;<label for="simple-check"><?php echo __('Simple','wp_easy_pay')?></label>&nbsp;&nbsp;&nbsp;&nbsp;
					<input type="radio" class="" name="wpep_free_form_type" id="donation-check" value="donation" <?php echo get_option( 'wpep_free_form_type' ) == 'donation' ? 'checked' : ''; ?>>&nbsp;<label for="donation-check"><?php _e('Donation','wp_easy_pay');?></label>&nbsp;&nbsp;&nbsp;&nbsp;
					<input type="radio" class="" name="wpep_free_form_type" disabled>&nbsp;<label style="opacity:0.8"><?php echo __('subscription','wp_easy_pay')?></label>&nbsp;&nbsp; -- <span class="wpep-info"><a href="#"><?php echo __('You can process Subscription/Recurring payments with','wp_easy_pay'); ?> <strong><?php echo __('Pro Version','wp_easy_pay'); ?></strong></a></span>
				</div>

				<div class="wpep-form-group" id="donation-depended-1">
					<label><?php echo __('Organization Name:','wp_easy_pay'); ?></label>
					<input type="text" class="wpep-form-control" Placeholder="Please enter organization name" name="wpep_free_org_name" value="<?php echo get_option( 'wpep_free_org_name' ); ?>" >
				</div>

				<div class="wpep-form-group" id="donation-depended-2">  
					<label><?php echo __('User set donation:','wp_easy_pay'); ?>&nbsp;&nbsp;&nbsp;&nbsp;</label>
					<input type="checkbox" class="" name="wpep_free_user_set_amount" id="user-donation" value="on" <?php echo in_array( get_option( 'wpep_free_user_set_amount' ), array( 'on', 'yes' ) ) ? 'checked' : ''; ?> >
				</div>


				<div class="wpep-form-group" id="donation-depended-3">
					<label><?php echo __('Amount:','wp_easy_pay'); ?></label>
					<input type="text" class="wpep-form-control" Placeholder="Please enter amount to capture" name="wpep_free_amount" value="<?php echo get_option( 'wpep_free_amount' ); ?>" >
				</div>

			   
				<div class="wpep-form-group">
					<label><?php echo __('Form theme color:','wp_easy_pay'); ?></label>
					<input class="wpep-form-control jscolor" value="<?php echo esc_attr($wpep_form_theme_color); ?>" name="wpep_form_theme_color" />
				</div>

				<div class="wpep-form-group">
					<label><?php echo __('Pay Button Text:','wp_easy_pay'); ?></label>
					<input type="text" class="wpep-form-control" Placeholder="'<?php _e('Please enter the submit button text','wp_easy_pay'); ?>" name="wpep_free_btn_text" value="<?php echo get_option( 'wpep_free_btn_text' ); ?>" >
				</div>
										
				<div class="wpep-form-group">

				<label class="lbltitle"><?php echo __('Button Style','wp_easy_pay'); ?></label> <br>
				<label><input type="checkbox" name="wpep_btn_theme" id="formType2" 
				<?php
				if ( $wpep_btn_theme == 'on' ) {
					echo "checked='checked'";
				}
				?>
				 /> <?php echo __('Use theme default popup button style','wp_easy_pay'); ?></label>
				</div>
				

				<div class="wpep-form-group">

				<label class="lbltitle"><?php echo __('Activate Shadow','wp_easy_pay'); ?></label> <br>

				<label><input type="checkbox" name="wpep_show_shadow" id="formType2" 
				<?php
				if ( $wpep_show_shadow == 'on' ) {
					echo 'checked';}
				?>
				 /> <?php echo __('Show form shadow','wp_easy_pay'); ?></label>
				</div>
			
				<div class="wpep-form-group">
					<label><?php echo __('Redirection on Success:','wp_easy_pay'); ?></label>
					<select class="wpep-form-control" Placeholder="<?php _e('Please enter successful payment url','wp_easy_pay') ?>" name="wpep_redirection_on_success">
						<option value="yes" 
						<?php
						if ( $wpep_redirection_on_success == 'yes' ) {
							echo 'selected'; }
						?>
						><?php _e('Yes','wp_easy_pay'); ?></option>
						<option value="no" 
						<?php
						if ( $wpep_redirection_on_success == 'no' ) {
							echo 'selected'; }
						?>
						><?php echo __('No','wp_easy_pay'); ?></option>
					</select>
				</div>

				<div class="wpep-form-group">
					<label><?php echo __('Redirection in Seconds:','wp_easy_pay'); ?></label>
					<input type="text" class="wpep-form-control" Placeholder="5" name="wpep_free_redirection_in_secs" value="<?php echo esc_attr($wpep_free_redirection_in_secs); ?>" >
				</div>
				<div class="wpep-form-group">
					<label><?php echo __('Payment Success Button Label:','wp_easy_pay')?></label>
					<input type="text" class="wpep-form-control" Placeholder="<?php _e('Redirect Now','wp_easy_pay'); ?>" name="wpep_free_payment_success_btn_label" value="<?php
					if ( get_option( 'wpep_free_payment_success_btn_label' ) != '' ) :
						$success_btn_label = trim( get_option( 'wpep_free_payment_success_btn_label' ) );
						echo $success_btn_label;
					else :
						echo 'Redirect Now';
					endif;
					?>">
				</div>

				<div class="wpep-form-group">
					<label><?php echo __('Payment Success URL:','wp_easy_pay')?></label>
					<input type="text" class="wpep-form-control" Placeholder="<?php _e('Please enter successful payment url', 'wp_easy_pay'); ?>" name="wpep_free_success_url" value="<?php echo get_option( 'wpep_free_success_url' ); ?>" >
				</div>

				<div class="wpep-form-group">
					<label><?php echo __('Payment Success Message:','wp_easy_pay'); ?></label>
					<textarea type="text" class="wpep-form-control" Placeholder="<?php _e('Payment Success Message' , 'wp_easy_pay')?>" name="wpep_free_payment_success_message"><?php echo esc_attr($wpep_free_payment_success_message); ?></textarea>
				</div>
				<div class="wpep-form-group">
					<input type="hidden" name="wpep_payment_form_nonce" value="<?php echo wp_create_nonce('paymentForm'); ?>" >
				</div>

				<div class="wpep-form-group">
					<input type="submit" name="wpep_free_submit" class="wpep-btn wpep-btn-primary wpep-btn-square" value="Save Setting">
				</div>

			</form>

		</div>

	</div>

</div>
