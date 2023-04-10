<?php
/**
 * Cruise booking frontend submit list template part
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package WordPress
 * @subpackage BookYourTravel
 * @since 1.0
 * @version 8.00
 */

global $bookyourtravel_cruise_helper, $bookyourtravel_theme_globals;
global $fs_table_extra_fields, $fs_table_extra_field_values, $fs_submit_url, $fs_table_extra_field_type, $fs_table_list_args;
global $current_author_id, $booking_user_id, $is_partner_page;

$submit_user_cruise_bookings_url = $bookyourtravel_theme_globals->get_submit_user_cruise_bookings_url();
$submit_user_cruise_bookings_url_with_arg = $submit_user_cruise_bookings_url;
$submit_user_cruise_bookings_url_with_arg = add_query_arg( 'fesid', '', $submit_user_cruise_bookings_url_with_arg);

$fs_submit_url = $submit_user_cruise_bookings_url_with_arg;

$posts_per_page = isset($fs_table_list_args['posts_per_page']) ? $fs_table_list_args['posts_per_page'] : 12;
$paged = isset($fs_table_list_args['paged']) ? $fs_table_list_args['paged'] : 12;

$fs_table_extra_fields = $bookyourtravel_cruise_helper->get_cruise_booking_fields();
$fs_table_extra_field_values = $bookyourtravel_cruise_helper->list_cruise_bookings($paged, $posts_per_page, 'Id', 'ASC', null, $booking_user_id, $is_partner_page ? $current_author_id : null);
$fs_table_extra_field_type = "bookings";

$current_url = BookYourTravel_Theme_Utils::get_current_page_url_no_query();
$booking_id = isset($_REQUEST["bid"]) ? intval($_REQUEST["bid"]) : 0;
$holder_class = 'cards-holder';
if ($is_partner_page) {
    $holder_class = 'table-holder';
}
?>
<div class="<?php echo esc_attr($holder_class); ?>">
    <?php if ($booking_id == 0) { ?>
        <?php if ($is_partner_page) { ?>
            <table class="fs_content_list">
            <?php
                get_template_part('includes/plugins/frontend-submit/parts/list-table', 'head');
                get_template_part('includes/plugins/frontend-submit/parts/list-table', 'body');
            ?>
            </table>
        <?php
        } else {
        ?>
        <div class="deals">
           <?php get_template_part('includes/plugins/frontend-submit/parts/list-cards', 'body'); ?>
        </div>
        <?php } ?>
    <?php } else { ?>
    <table class="fs_content_single">
        <?php get_template_part('includes/plugins/frontend-submit/parts/single-table', 'body'); ?>
    </table>
    <a class="list-link gradient-button" href="<?php echo esc_url($current_url); ?>"><?php echo esc_html__("&laquo; Back to list", "bookyourtravel"); ?></a>
    <?php } ?>
</div>
