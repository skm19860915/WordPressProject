<?php
/**
 * Tour schedule frontend submit list template part
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package WordPress
 * @subpackage BookYourTravel
 * @since 1.0
 * @version 8.00
 */

global $bookyourtravel_tour_helper, $bookyourtravel_theme_globals;
global $fs_table_extra_fields, $fs_table_extra_field_values, $fs_submit_url;
global $current_author_id, $booking_user_id, $is_partner_page;

$submit_user_tour_schedules_url = $bookyourtravel_theme_globals->get_submit_user_tour_schedules_url();
$submit_user_tour_schedules_url_with_arg = $submit_user_tour_schedules_url;
$submit_user_tour_schedules_url_with_arg = add_query_arg( 'fesid', '', $submit_user_tour_schedules_url_with_arg);

$fs_submit_url = $submit_user_tour_schedules_url_with_arg;

$fs_table_extra_fields = $bookyourtravel_tour_helper->get_tour_schedule_fields();
$fs_table_extra_field_values = $bookyourtravel_tour_helper->list_tour_schedules(null, 0, 'Id', 'ASC', 0, 0, 0, 0, '', $current_author_id);
?>
<div class="table-holder">
	<table class="fs_content_list">
	<?php get_template_part('includes/plugins/frontend-submit/parts/list-table', 'head'); ?>
	<?php get_template_part('includes/plugins/frontend-submit/parts/list-table', 'body'); ?>
	</table>
</div>
