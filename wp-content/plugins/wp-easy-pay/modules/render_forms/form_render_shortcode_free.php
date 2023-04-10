<?php

function wpep_render_free_payment_form( $atts ) {
 
	if ( isset( $_SERVER['HTTPS'] ) && 'on' === $_SERVER['HTTPS'] ) {

		if ( ! is_admin() ) {

			ob_start();
			require WPEP_ROOT_PATH . '/views/frontend/wpep_free/free_parent_view.php';
			return ob_get_clean();

		}
	}

	if ( ! isset( $_SERVER['HTTPS'] ) || 'on' !== $_SERVER['HTTPS'] ) {

		ob_start();

		require WPEP_ROOT_PATH . '/views/frontend/no-ssl.php';
		return ob_get_clean();

	}

}

add_action( 'init', 'wpep_register_free_shortcode' );

function wpep_register_free_shortcode() {

	add_shortcode( 'wpep_form', 'wpep_render_free_payment_form' );
}
