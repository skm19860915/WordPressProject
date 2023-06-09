<?php
/**
 * BookYourTravel_Review_Helper class
 *
 * @package WordPress
 * @subpackage BookYourTravel
 * @since 1.0
 * @version 8.00
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class BookYourTravel_Review_Helper extends BookYourTravel_BaseSingleton {

	private $enable_reviews;
	private $review_custom_meta_fields;
	private $review_custom_meta_tabs;

	protected function __construct() {

		global $bookyourtravel_theme_globals;

		$this->enable_reviews = $bookyourtravel_theme_globals->enable_reviews();

        // our parent class might
        // contain shared code in its constructor
        parent::__construct();
    }

    public function init() {

		add_action( 'bookyourtravel_initialize_post_types', array( $this, 'initialize_post_type' ), 0);

		if ($this->enable_reviews) {
			add_action( 'admin_init', array( $this, 'review_admin_init' ) );
			add_action( 'bookyourtravel_initialize_ajax', array( $this, 'initialize_ajax' ), 0);
		}
	}

	function initialize_ajax() {
		add_action( 'wp_ajax_list_review_fields_request', array( $this, 'list_review_fields_request' ) );		
		add_action( 'wp_ajax_nopriv_list_review_fields_request', array( $this, 'list_review_fields_request' ) );		
		add_action( 'wp_ajax_review_ajax_request', array( $this, 'review_ajax_request' ) );
		add_action( 'wp_ajax_nopriv_review_ajax_request', array( $this, 'review_ajax_request' ) );
		add_action( 'wp_ajax_sync_reviews_ajax_request', array( $this, 'sync_reviews_ajax_request' ) );
	}

	function list_review_fields_request() {
		global $bookyourtravel_theme_of_custom;

		$response = array();

        if (isset($_REQUEST)) {
			$nonce = isset($_REQUEST['nonce']) ? wp_kses($_REQUEST['nonce'], array()) : '';
			$post_type = isset($_REQUEST['postType']) ? wp_kses($_REQUEST['postType'], array()) : '';
			$post_id = isset($_REQUEST['postId']) ? intval(wp_kses($_REQUEST['postId'], array())) : 0;

            if (wp_verify_nonce($nonce, 'bookyourtravel_nonce')) {
                if ($post_type && $post_id > 0) {
					$post_type_label = '';
					switch($post_type) {
						case 'accommodation' : $post_type_label = esc_html__('accommodation', 'bookyourtravel'); break;
						case 'tour' : $post_type_label = esc_html__('tour', 'bookyourtravel'); break;
						case 'car_rental' : $post_type_label = esc_html__('car rental', 'bookyourtravel'); break;
						case 'cruise' : $post_type_label = esc_html__('cruise', 'bookyourtravel'); break;
						default : $post_type_label = esc_html__('accommodation', 'bookyourtravel'); break;
					}

					$review_fields = $this->list_review_fields($post_type);
					
                    foreach ($review_fields as $field) {
                        $field_label = isset($field['label']) ? $field['label'] : '';
						$field_label = $bookyourtravel_theme_of_custom->get_translated_dynamic_string($bookyourtravel_theme_of_custom->get_option_id_context($post_type . '_review_fields') . ' ' . $field_label, $field_label);
						$field['label'] = $field_label;
					}

					$response['review_fields'] = $review_fields;
					$response['post_title'] = get_the_title($post_id);
					$response['post_type_label'] = $post_type_label;
                }
            }
		}
		
		echo json_encode($response);
		die();	
	}

	function review_ajax_request() {

		if ( isset($_REQUEST) ) {

			$likes = isset($_REQUEST['dislikes']) ? wp_kses($_REQUEST['likes'], array()) : '';
			$dislikes = isset($_REQUEST['dislikes']) ? wp_kses($_REQUEST['dislikes'], array()) : '';
			$reviewed_post_id = intval(wp_kses($_REQUEST['postId'], array()));
			$user_id = intval(wp_kses($_REQUEST['userId'], array()));
			$nonce = wp_kses($_REQUEST['nonce'], array());

			if ( wp_verify_nonce( $nonce, 'bookyourtravel_nonce' ) ) {

				// nonce passed ok
				$reviewed_post = get_post($reviewed_post_id);
				$review_fields = $this->list_review_fields($reviewed_post->post_type);
				$user_info = get_userdata($user_id);

				if ($reviewed_post != null && $user_info != null && count($review_fields) > 0) {

					$reviewed_post_title = get_the_title($reviewed_post_id);

					$review_post = array(
						'post_title'    => sprintf(esc_html__('Review of %s by %s [%s]', 'bookyourtravel'), $reviewed_post_title, $user_info->user_nicename, $user_id),
						'post_status'   => 'publish',
						'post_author'   => $user_id,
						'post_type' 	=> 'review',
						'post_date' => date('Y-m-d H:i:s')
					);

					// Insert the post into the database
					$review_post_id = wp_insert_post( $review_post );

					if( ! is_wp_error( $review_post_id ) ) {

						$new_score_sum = 0;
						foreach ($review_fields as $review_field) {
							$field_id = $review_field['id'];
							$field_value = isset($_REQUEST['reviewField_' . $field_id]) ? intval(wp_kses($_REQUEST['reviewField_' . $field_id], array())) : 0;
							echo ' field_id ' . $field_id . ' field_value ' . $field_value . "\n\r";
							$new_score_sum += $field_value;
							add_post_meta($review_post_id, $field_id, $field_value);
						}

						$review_score = floatval(get_post_meta($reviewed_post_id, 'review_score', true));
						$review_score = $review_score ? $review_score : 0;

						$review_sum_score = floatval(get_post_meta($reviewed_post_id, 'review_sum_score', true));
						$review_sum_score = $review_sum_score ? $review_sum_score : 0;

						$review_count = intval($this->get_reviews_count($reviewed_post_id));
						$review_count = $review_count ? $review_count : 0;
						$review_count++;

						$review_sum_score = $review_sum_score + $new_score_sum;
						$new_review_score = $new_score_sum / (count($review_fields) * 10);
						$review_score = ($review_score + $new_review_score) / $review_count;

						add_post_meta($review_post_id, 'review_likes', $likes);
						add_post_meta($review_post_id, 'review_dislikes', $dislikes);
						add_post_meta($review_post_id, 'review_post_id', $reviewed_post_id);

						update_post_meta($reviewed_post_id, 'review_sum_score', $review_sum_score);
						update_post_meta($reviewed_post_id, 'review_score', $review_score);
						update_post_meta($reviewed_post_id, 'review_count', $review_count);
					}

					echo json_encode($review_post_id);

					$this->sync_reviews();
				}
			} else {
				echo 'nonce fail';
			}
		}

		// Always die in functions echoing ajax content
		die();
	}

	function sync_reviews() {
		global $bookyourtravel_theme_globals;

		$enable_accommodations = $bookyourtravel_theme_globals->enable_accommodations();
		if ($enable_accommodations)
			$this->recalculate_review_scores('accommodation');

		$enable_tours = $bookyourtravel_theme_globals->enable_tours();
		if ($enable_tours)
			$this->recalculate_review_scores('tour');

		$enable_cruises = $bookyourtravel_theme_globals->enable_cruises();
		if ($enable_cruises)
			$this->recalculate_review_scores('cruise');

		$enable_car_rentals = $bookyourtravel_theme_globals->enable_car_rentals();
		if ($enable_car_rentals)
			$this->recalculate_review_scores('car_rental');
	}

	function sync_reviews_ajax_request() {

		global $bookyourtravel_theme_globals;

		if ( isset($_REQUEST) ) {
			$nonce = wp_kses($_REQUEST['nonce'], array());

			if ( wp_verify_nonce( $nonce, 'optionsframework-options' ) ) {

				$this->sync_reviews();

				echo '1';
			} else {
				echo '0';
			}
		}
		die();
	}

	function review_admin_init() {

		if ($this->enable_reviews) {

			$this->review_custom_meta_fields = array(
				array(
					'label'	=> esc_html__('Likes', 'bookyourtravel'),
					'desc'	=> esc_html__('What the user likes about the accommodation', 'bookyourtravel'),
					'id'	=> 'review_likes',
					'type'	=> 'textarea'
				),
				array(
					'label'	=> esc_html__('Dislikes', 'bookyourtravel'),
					'desc'	=> esc_html__('What the user dislikes about the accommodation', 'bookyourtravel'),
					'id'	=> 'review_dislikes',
					'type'	=> 'textarea'
				),
				array( // Post ID select box
					'label'	=> esc_html__('Reviewed item', 'bookyourtravel'), // <label>
					'desc'	=> '', // description
					'id'	=>  'review_post_id', // field id and name
					'type'	=> 'post_select', // type of field
					'post_type' => array('accommodation', 'tour', 'cruise', 'car_rental') // post types to display, options are prefixed with their post type
				),
				array('label'	=> esc_html__('Accommodation', 'bookyourtravel'),	'desc'	=> esc_html__('Accommodation rating', 'bookyourtravel'), 'id'	=> 'review_accommodation', 'type'	=> 'slider', 'min'	=> '0', 'max'	=> '10', 'step'	=> '1' ),
				array('label'	=> esc_html__('Cleanliness', 'bookyourtravel'),	'desc'	=> esc_html__('Cleanliness rating', 'bookyourtravel'), 'id'	=> 'review_cleanliness', 'type'	=> 'slider', 'min'	=> '0', 'max'	=> '10', 'step'	=> '1' ),
				array('label'	=> esc_html__('Comfort', 'bookyourtravel'),	'desc'	=> esc_html__('Comfort rating', 'bookyourtravel'), 'id'	=> 'review_comfort', 'type'	=> 'slider', 'min'	=> '0', 'max'	=> '10', 'step'	=> '1' ),
				array('label'	=> esc_html__('Customer service', 'bookyourtravel'),	'desc'	=> esc_html__('Customer service rating', 'bookyourtravel'), 'id'	=> 'review_customer_service', 'type'	=> 'slider', 'min'	=> '0', 'max'	=> '10', 'step'	=> '1' ),
				array('label'	=> esc_html__('Delivery', 'bookyourtravel'),	'desc'	=> esc_html__('Delivery rating', 'bookyourtravel'), 'id'	=> 'review_delivery', 'type'	=> 'slider', 'min'	=> '0', 'max'	=> '10', 'step'	=> '1' ),
				array('label'	=> esc_html__('Entertainment', 'bookyourtravel'),	'desc'	=> esc_html__('Entertainment rating', 'bookyourtravel'), 'id'	=> 'review_entertainment', 'type'	=> 'slider', 'min'	=> '0', 'max'	=> '10', 'step'	=> '1' ),
				array('label'	=> esc_html__('Guide', 'bookyourtravel'),	'desc'	=> esc_html__('Guide rating', 'bookyourtravel'), 'id'	=> 'review_guide', 'type'	=> 'slider', 'min'	=> '0', 'max'	=> '10', 'step'	=> '1' ),
				array('label'	=> esc_html__('Location', 'bookyourtravel'),	'desc'	=> esc_html__('Location rating', 'bookyourtravel'), 'id'	=> 'review_location', 'type'	=> 'slider', 'min'	=> '0', 'max'	=> '10', 'step'	=> '1' ),
				array('label'	=> esc_html__('Meals', 'bookyourtravel'),	'desc'	=> esc_html__('Meals rating', 'bookyourtravel'), 'id'	=> 'review_meals', 'type'	=> 'slider', 'min'	=> '0', 'max'	=> '10', 'step'	=> '1' ),
				array('label'	=> esc_html__('Overall', 'bookyourtravel'),	'desc'	=> esc_html__('Overall rating', 'bookyourtravel'), 'id'	=> 'review_overall', 'type'	=> 'slider', 'min'	=> '0', 'max'	=> '10', 'step'	=> '1' ),
				array('label'	=> esc_html__('Punctuality', 'bookyourtravel'),	'desc'	=> esc_html__('Punctuality rating', 'bookyourtravel'), 'id'	=> 'review_punctuality', 'type'	=> 'slider', 'min'	=> '0', 'max'	=> '10', 'step'	=> '1' ),
				array('label'	=> esc_html__('Program accuracy', 'bookyourtravel'),	'desc'	=> esc_html__('Program accuracy rating', 'bookyourtravel'), 'id'	=> 'review_program_accuracy', 'type'	=> 'slider', 'min'	=> '0', 'max'	=> '10', 'step'	=> '1' ),
				array('label'	=> esc_html__('Services', 'bookyourtravel'),	'desc'	=> esc_html__('Services rating', 'bookyourtravel'), 'id'	=> 'review_services', 'type'	=> 'slider', 'min'	=> '0', 'max'	=> '10', 'step'	=> '1' ),
				array('label'	=> esc_html__('Sleep quality', 'bookyourtravel'),	'desc'	=> esc_html__('Sleep quality rating', 'bookyourtravel'), 'id'	=> 'review_sleep_quality', 'type'	=> 'slider', 'min'	=> '0', 'max'	=> '10', 'step'	=> '1' ),
				array('label'	=> esc_html__('Speed', 'bookyourtravel'),	'desc'	=> esc_html__('Speed rating', 'bookyourtravel'), 'id'	=> 'review_speed', 'type'	=> 'slider', 'min'	=> '0', 'max'	=> '10', 'step'	=> '1' ),
				array('label'	=> esc_html__('Staff', 'bookyourtravel'),	'desc'	=> esc_html__('Staff rating', 'bookyourtravel'), 'id'	=> 'review_staff', 'type'	=> 'slider', 'min'	=> '0', 'max'	=> '10', 'step'	=> '1' ),
				array('label'	=> esc_html__('Transport', 'bookyourtravel'),	'desc'	=> esc_html__('Transport rating', 'bookyourtravel'), 'id'	=> 'review_transport', 'type'	=> 'slider', 'min'	=> '0', 'max'	=> '10', 'step'	=> '1' ),
				array('label'	=> esc_html__('Value for money', 'bookyourtravel'),	'desc'	=> esc_html__('Value for money rating', 'bookyourtravel'), 'id'	=> 'review_value_for_money', 'type'	=> 'slider', 'min'	=> '0', 'max'	=> '10', 'step'	=> '1' ),
			);
		}

		new Custom_Add_Meta_Box( 'review_custom_meta_fields', esc_html__('Extra information', 'bookyourtravel'), $this->review_custom_meta_fields, $this->review_custom_meta_tabs, 'review' );
	}

	function initialize_post_type() {

		global $bookyourtravel_theme_globals;
		$this->enable_reviews = $bookyourtravel_theme_globals->enable_reviews();

		if ($this->enable_reviews) {
			$this->register_reviews_post_type();
		}
	}

	function register_reviews_post_type() {

		$labels = array(
			'name'                => esc_html__( 'Reviews', 'bookyourtravel' ),
			'singular_name'       => esc_html__( 'Review', 'bookyourtravel' ),
			'menu_name'           => esc_html__( 'Reviews', 'bookyourtravel' ),
			'all_items'           => esc_html__( 'Reviews', 'bookyourtravel' ),
			'view_item'           => esc_html__( 'View Review', 'bookyourtravel' ),
			'add_new_item'        => esc_html__( 'Add New Review', 'bookyourtravel' ),
			'add_new'             => esc_html__( 'New Review', 'bookyourtravel' ),
			'edit_item'           => esc_html__( 'Edit Review', 'bookyourtravel' ),
			'update_item'         => esc_html__( 'Update Review', 'bookyourtravel' ),
			'search_items'        => esc_html__( 'Search reviews', 'bookyourtravel' ),
			'not_found'           => esc_html__( 'No reviews found', 'bookyourtravel' ),
			'not_found_in_trash'  => esc_html__( 'No reviews found in Trash', 'bookyourtravel' ),
		);
		$args = array(
			'label'               => esc_html__( 'Review', 'bookyourtravel' ),
			'description'         => esc_html__( 'Review information pages', 'bookyourtravel' ),
			'labels'              => $labels,
			'supports'            => array( 'title', 'author' ),
			'taxonomies'          => array( ),
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_nav_menus'   => true,
			'show_in_admin_bar'   => true,
			'can_export'          => true,
			'has_archive'         => false,
			'exclude_from_search' => true,
			'publicly_queryable'  => false,
			'capability_type'     => 'page',
			'rewrite' => false,
		);

		$args = apply_filters('bookyourtravel_register_post_type_review', $args);

		register_post_type( 'review', $args );
	}

	function recalculate_review_scores($post_type) {

		global $wpdb;

		$review_fields = $this->list_review_fields($post_type);
		$review_fields_count = count($review_fields);

		if ( $review_fields_count > 0 ) {

			$sql = "SELECT 	ID
					FROM 	$wpdb->posts as posts
					WHERE 	posts.post_type = '$post_type' AND
							posts.post_status = 'publish'";

			$posts = $wpdb->get_results($sql);

			foreach ($posts as $post) {

				$reviewed_post_id = $post->ID;

				$sql = "SELECT ID
						FROM $wpdb->posts as posts
						INNER JOIN $wpdb->postmeta as meta ON posts.ID = meta.post_id AND meta.meta_key = 'review_post_id' AND meta.meta_value=%d
						WHERE 	posts.post_type='review' AND
								posts.post_status='publish' ";

				$reviews = $wpdb->get_results($wpdb->prepare($sql, $reviewed_post_id));

				$score_sum = 0;
				$review_count = 0;
				$review_score = 0;

				foreach ($reviews as $review) {

					$review_id = $review->ID;

					foreach ($review_fields as $field) {
						$field_id = $field['id'];
						$field_value = get_post_meta($review_id, $field_id, true);
						$score_sum += intval($field_value);
					}

					$review_count += 1;
				}

				if ($review_count > 0 ) {

					$review_score = $score_sum / ($review_fields_count * 10 * $review_count);

					update_post_meta($reviewed_post_id, 'review_sum_score', $score_sum);
					update_post_meta($reviewed_post_id, 'review_score', $review_score);
					update_post_meta($reviewed_post_id, 'review_count', $review_count);
				}
			}
		}
	}

	function list_user_reviews($user_id, $paged = 0, $per_page = -1) {

		$args = array(
		   'post_type' => 'review',
		   'post_status'       => array('publish'),
		   'posts_per_page'    => $per_page,
		   'paged' 			=> $paged,		   
		   'author' => $user_id
		);
		
		$posts_query = new WP_Query($args);

		$reviews = array();

		if ($posts_query->have_posts() ) {
			while ( $posts_query->have_posts() ) {
				global $post;
				$posts_query->the_post();
				$reviews[] = $post;
			}
		}

		$results = array(
			'total' => $posts_query->found_posts,
			'results' => $reviews
		);		

		wp_reset_postdata();		
		
		return $results;
	}

	function list_reviews($post_id, $user_id = null) {

		$args = array(
		   'post_type' => 'review',
		   'post_status' => 'publish',
		   'posts_per_page' => -1,
		   'meta_query' => array(
			   array(
				   'key' => 'review_post_id',
				   'value' => $post_id,
				   'compare' => '=',
				   'type'    => 'CHAR',
			   ),
		   )
		);

		if ($user_id) {
			$args['author'] = $user_id;
		}

		return new WP_Query($args);
	}

	function get_reviews_count($post_id, $user_id = null) {
		$query = $this->list_reviews($post_id, $user_id);
		return $query->found_posts;
	}

	function list_review_fields($post_type, $visible_only = true) {

		global $default_tour_review_fields, $default_accommodation_review_fields, $default_cruise_review_fields, $default_car_rental_review_fields;

		$default_review_fields = array();

		if ($post_type == 'accommodation')
			$default_review_fields = $default_accommodation_review_fields;
		elseif ($post_type == 'tour')
			$default_review_fields = $default_tour_review_fields;
		elseif ($post_type == 'cruise')
			$default_review_fields = $default_cruise_review_fields;
		elseif ($post_type == 'car_rental')
			$default_review_fields = $default_car_rental_review_fields;

		$review_fields = of_get_option($post_type . '_review_fields');
		if (!is_array($review_fields) || count($review_fields) == 0)
			$review_fields = $default_review_fields;

		$fields = array();

		foreach ($review_fields as $review_field) {
			if (!$visible_only)
				$fields[] = $review_field;
			else {
				if (!isset($review_field['hide']) || !$review_field['hide'])
					$fields[] = $review_field;
			}
		}

		return $fields;
	}

	function sum_review_meta_values($post_id, $meta_key) {

		global $wpdb;

		$sql = $wpdb->prepare("SELECT sum(meta.meta_value)
			FROM $wpdb->postmeta as meta
			INNER JOIN $wpdb->postmeta as meta2 ON meta2.post_id = meta.post_id
			INNER JOIN $wpdb->posts as posts ON posts.ID = meta.post_id
			WHERE meta.meta_key = %s AND posts.post_type='review' AND posts.post_status='publish' AND meta2.meta_key = 'review_post_id' AND meta2.meta_value=%d", $meta_key, $post_id);

		return $wpdb->get_var($sql);
	}

	function sum_user_review_meta_values($review_id, $user_id, $post_type) {

		global $wpdb, $default_tour_review_fields, $default_accommodation_review_fields;

		$review_fields = $this->list_review_fields($post_type);
		$review_fields_str = "";
		foreach ($review_fields as $field_key => $field) {
			$review_fields_str .= "'" . $field['id'] . "', ";
		}
		$review_fields_str = rtrim($review_fields_str, ', ');

		$sql = $wpdb->prepare("SELECT sum(meta.meta_value)
			FROM $wpdb->postmeta as meta
			INNER JOIN $wpdb->posts as posts ON posts.ID = meta.post_id
			WHERE meta.meta_key IN ($review_fields_str) AND posts.post_type='review' AND posts.post_status='publish'
			AND posts.ID=%d AND posts.post_author=%d", $review_id, $user_id);

		return $wpdb->get_var($sql);
	}
}

global $bookyourtravel_review_helper;
// store the instance in a variable to be retrieved later and call init
$bookyourtravel_review_helper = BookYourTravel_Review_Helper::get_instance();
$bookyourtravel_review_helper->init();