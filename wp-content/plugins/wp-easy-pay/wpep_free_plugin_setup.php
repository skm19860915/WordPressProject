<?php
add_action( 'init', 'wpep_create_reports_post_type' );

if ( ! function_exists( 'wpep_add_submenu' ) ) {

	function wpep_add_submenu() {
		$documentation_link = 'https://wpeasypay.com/documentation/';
		$support_link       = 'https://wpeasypay.com/support/';
		$icon_url           = WPEP_ROOT_URL . 'assets/backend/img/square-logo.png';
		$reports_link       = admin_url() . '/edit.php?post_type=wpep_free_reports';
		add_menu_page( 'WP EASY PAY', 'WP EASY PAY', '', 'wp_easy_pay', 'wpep_render_free_payment_form_page', $icon_url, 5 );
		add_submenu_page( 'wp_easy_pay', __('Payment Form','wp_easy_pay'), __('Payment Form','wp_easy_pay'), 'manage_options', 'wpep_payment_form', 'wpep_render_free_payment_form_page' );
		add_submenu_page( 'wp_easy_pay', __('Square Connect','wp_easy_pay'), __('Square Connect','wp_easy_pay'), 'manage_options', 'wpep-settings', 'wpep_render_global_settings_page' );
		add_submenu_page( 'wp_easy_pay', __('Pro Features','wp_easy_pay'), __('Pro Features','wp_easy_pay'), 'manage_options', 'wpep-pro-features', 'wpep_render_pro_feature_page' );
		add_submenu_page( 'wp_easy_pay', __('Documentation' ,'wp_easy_pay'), __('Documentation' ,'wp_easy_pay'), 'manage_options', $documentation_link );
		add_submenu_page( 'wp_easy_pay', __('Reports','wp_easy_pay'), __('Reports','wp_easy_pay'), 'manage_options', $reports_link );
	}
}

add_action( 'admin_menu', 'wpep_add_submenu' );


function wpep_adding_css_for_reports_free() {
	if ( get_post_type( get_the_ID() ) == 'wpep_free_reports' ) {
		// if is true

		
		wp_enqueue_style( 'wpep_backend_style_free_reports', plugin_dir_url(__FILE__).'wpep_backend_styles.css', '3.0.0' );
	}
}
add_action( 'admin_enqueue_scripts', 'wpep_adding_css_for_reports_free' );

function wpep_render_free_payment_form_page() {

	 require_once plugin_dir_path(__FILE__).'views/backend/free_payment_form_page.php';
}

function wpep_render_global_settings_page() {
	require_once plugin_dir_path(__FILE__).'views/backend/global_settings_page.php';
}

function wpep_render_pro_feature_page() {
	require_once plugin_dir_path(__FILE__).'views/backend/pro_feature_3_0.php';
}


function wpep_create_connect_url( $origin ) {


	if (isset( $_SERVER['REQUEST_URI'] )) {
		$URI_REQUESTED = esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) );
	}
	

	/* Fetch GET parameters from URI */
	$parts = parse_url( $URI_REQUESTED );
	parse_str( $parts['query'], $url_identifiers );

	/* Fetch Admin URL */
	$slash_exploded = explode( '/', $URI_REQUESTED );

	$question_mark_exploded                          = explode( '?', $slash_exploded[2] );
	$url_identifiers['wpep_admin_url']               = $question_mark_exploded[0];
	$url_identifiers['wpep_post_type']               = 'wp_easy_pay';
	$url_identifiers['wpep_prepare_connection_call'] = true;

	if ( $origin == 'individual_form' ) {

		if ( isset($_GET['post']) ) {
			$post = sanitize_text_field( wp_unslash( $_GET['post'] ) );
		}

		if ( isset( $post ) && ! empty( $post ) ) {

			$url_identifiers['wpep_page_post'] = $post;

		}
	}

	if ( $origin == 'global' ) {

		$url_identifiers['wpep_page_post'] = 'global';

	}

	 $connection_url = add_query_arg( $url_identifiers, $url_identifiers['wpep_admin_url'] );
	return $connection_url;

}

function wpep_create_reports_post_type() {
	$labels = array(
		'name'                  => _x( 'Reports', 'Post Type General Name', 'wp_easy_pay' ),
		'singular_name'         => _x( 'Reports', 'Post Type Singular Name', 'wp_easy_pay' ),
		'menu_name'             => __( 'Reports', 'wp_easy_pay' ),
		'name_admin_bar'        => __( 'Post Type', 'wp_easy_pay' ),
		'archives'              => __( 'Item Archives', 'wp_easy_pay' ),
		'attributes'            => __( 'Item Attributes', 'wp_easy_pay' ),
		'parent_item_colon'     => __( 'Parent Item:', 'wp_easy_pay' ),
		'all_items'             => __( 'Reports', 'wp_easy_pay' ),
		'add_new_item'          => __( 'Build Report', 'wp_easy_pay' ),
		'add_new'               => __( 'Build Report', 'wp_easy_pay' ),
		'new_item'              => __( 'New Item', 'wp_easy_pay' ),
		'edit_item'             => __( 'Edit Item', 'wp_easy_pay' ),
		'update_item'           => __( 'Update Item', 'wp_easy_pay' ),
		'view_item'             => __( 'View Item', 'wp_easy_pay' ),
		'view_items'            => __( 'View Items', 'wp_easy_pay' ),
		'search_items'          => __( 'Search Item', 'wp_easy_pay' ),
		'not_found'             => __( 'Not found', 'wp_easy_pay' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'wp_easy_pay' ),
		'featured_image'        => __( 'Featured Image', 'wp_easy_pay' ),
		'set_featured_image'    => __( 'Set featured image', 'wp_easy_pay' ),
		'remove_featured_image' => __( 'Remove featured image', 'wp_easy_pay' ),
		'use_featured_image'    => __( 'Use as featured image', 'wp_easy_pay' ),
		'insert_into_item'      => __( 'Insert into item', 'wp_easy_pay' ),
		'uploaded_to_this_item' => __( 'Uploaded to this item', 'wp_easy_pay' ),
		'items_list'            => __( 'Items list', 'wp_easy_pay' ),
		'items_list_navigation' => __( 'Items list navigation', 'wp_easy_pay' ),
		'filter_items_list'     => __( 'Filter items list', 'wp_easy_pay' ),
	);

	$args = array(

		'label'               => __( 'Reports', 'wp_easy_pay' ),
		'description'         => __( 'Post Type Description', 'wp_easy_pay' ),
		'labels'              => $labels,
		'hierarchical'        => false,
		'public'              => true,
		'supports'            => false,
		'show_ui'             => true,
		'show_in_menu'        => false,
		'menu_position'       => 5,
		'show_in_admin_bar'   => true,
		'show_in_nav_menus'   => true,
		'can_export'          => true,
		'has_archive'         => true,
		'exclude_from_search' => false,
		'publicly_queryable'  => true,
		'capability_type'     => 'post',

	);

	register_post_type( 'wpep_free_reports', $args );

}

function wpep_modify_column_names_reports( $columns ) {
	unset( $columns['date'] );
	unset( $columns['title'] );
	$columns['post_id'] = __( 'ID' );
	$columns['paid_by'] = __( 'Paid By' );
	$columns['type']    = __( 'Type' );
	$columns['date']    = __( 'Date' );
	$columns['actions'] = __( 'Action' );

	return $columns;
}
add_filter( 'manage_wpep_free_reports_posts_columns', 'wpep_modify_column_names_reports' );

function wpep_add_columns_data_reports( $column, $postId ) {

	$first_name       = get_post_meta( $postId, 'wpep_first_name', true );
	$last_name        = get_post_meta( $postId, 'wpep_last_name', true );
	$email            = get_post_meta( $postId, 'wpep_email', true );
	$transaction_type = get_post_meta( $postId, 'wpep_transaction_type', true );

	switch ( $column ) {

		case 'post_id':
			 printf("<a href='%s' class='wpep-blue' title='Details'>#%u</a>", esc_url( get_edit_post_link( $postId ) ),$postId);
			break;
		case 'type':
			 printf( "<span class='%s'>%s</span>", esc_attr( $transaction_type ), esc_attr( str_replace( '_', ' ', $transaction_type )) );
			break;
		case 'paid_by':
			 printf("%s %s",esc_html( $first_name ),esc_html( $last_name ));
			break;
		case 'actions':
			 printf("<a href='%s' class='deleteIcon' title='Delete report'> %s </a>",esc_url( get_delete_post_link( $postId ) ), __( 'Delete', 'wp_easy_pay' ));
			break;
	}

}
add_action( 'manage_wpep_free_reports_posts_custom_column', 'wpep_add_columns_data_reports', 9, 2 );

function wpep_add_reports_metabox() {
	add_meta_box(
		'wporg_box_id',
		'Payment Details',
		'wpep_render_free_reports_meta_html',
		'wpep_free_reports'
	);
}
add_action( 'admin_init', 'wpep_add_reports_metabox' );

function wpep_render_free_reports_meta_html( $post ) {

	require_once plugin_dir_path(__FILE__).'views/backend/reports_view_page.php';
}
