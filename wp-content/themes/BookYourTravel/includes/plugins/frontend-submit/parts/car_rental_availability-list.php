<?php
/**
 * Car rental availability frontend submit list template part
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package WordPress
 * @subpackage BookYourTravel
 * @since 1.0
 * @version 8.00
 */

global $bookyourtravel_car_rental_helper, $bookyourtravel_theme_globals;
global $fs_table_extra_fields, $fs_table_extra_field_values, $fs_submit_url;
global $current_author_id, $booking_user_id, $is_partner_page;

$submit_user_car_rental_availabilities_url = $bookyourtravel_theme_globals->get_submit_user_car_rental_availabilities_url();
$submit_user_car_rental_availabilities_url_with_arg = $submit_user_car_rental_availabilities_url;
$submit_user_car_rental_availabilities_url_with_arg = add_query_arg( 'fesid', '', $submit_user_car_rental_availabilities_url_with_arg);

$fs_submit_url = $submit_user_car_rental_availabilities_url_with_arg;

$fs_table_extra_fields = $bookyourtravel_car_rental_helper->get_car_rental_availability_fields();
$fs_table_extra_field_values = $bookyourtravel_car_rental_helper->list_car_rental_availabilities(0, null, 0, 'Id', 'ASC', $current_author_id);
?>
<div class="table-holder">
	<table class="fs_content_list">
	<?php get_template_part('includes/plugins/frontend-submit/parts/list-table', 'head'); ?>
	<?php get_template_part('includes/plugins/frontend-submit/parts/list-table', 'body'); ?>
	</table>
</div>