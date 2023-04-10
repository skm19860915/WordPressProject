<?php


function wpep_print_credit_card_fields_free() {
	ob_start();

	if ( ! isset( $wpep_current_form_id ) ) {
		$wpep_current_form_id = 1; // free form
	}
	?>

	<div id="form-container">
		<div class="form-group form-control-wrap cred-card-wrap">
			<div class="CardIcon">
				<div class="CardIcon-inner">
					<div class="CardIcon-front">
						<img src="<?php echo esc_url( WPEP_ROOT_URL . 'assets/frontend/img/card-front.jpg' ); ?>" alt="Avatar"
							width="20">
					</div>
					<div class="CardIcon-back">
						<img src="<?php echo esc_url( WPEP_ROOT_URL . 'assets/frontend/img/card-back.jpg' ); ?>" alt="Avatar"
							width="20">
					</div>
				</div>
			</div>
			<div class="form-control-1 input-card" id="sq-card-number"></div>
	
			<div class="cred">
				<div class="form-control-1 input-date" id="sq-expiration-date"></div>
				<div class="form-control-1 input-ccv abc" id="sq-cvv"></div>
			</div>
		   
		</div>
		<div class="form-group form-control-wrap pcode">
			<div class="form-control-1 input-postal" id="sq-postal-code"></div>
		</div>


		<div class="selection" id="showPayment">
			<div class="otherpInput">

				<input class="form-control text-center customPayment" id="wpep_user_defined_amount" name="wpep_user_defined_amount" value="" type="number" step="1" min="1" max="999" />

			</div>
		</div>


		<div class="btnGroup ifSingle">
			<button type="button" id="sq-creditcard" class="wpep-free-form-submit-btn float-right wpep-disabled"><?php echo esc_html( get_option( 'wpep_free_btn_text' ) ); ?>
				<span>
					<b id="dosign" style="display: none;"><?php _e('$','wp_easy_pay');?></b><small id="amount_display_<?php echo esc_html( $wpep_current_form_id ); ?>" class="display"></small>
					<input type="hidden" name="wpep-selected-amount" value="">
				</span>
	</button>            
		</div>
	</div>

	<?php
	ob_end_flush();
}
