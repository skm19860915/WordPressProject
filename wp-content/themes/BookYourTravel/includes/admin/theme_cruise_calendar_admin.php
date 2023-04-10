<?php
/*
*******************************************************************************
************************** LOAD THE BASE CLASS ********************************
*******************************************************************************
* The WP_List_Table class isn't automatically available to plugins,
* so we need to check if it's available and load it if necessary.
*******************************************************************************
*/
if(!class_exists('WP_List_Table')) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class BookYourTravel_Cruise_Calendar_Admin extends BookYourTravel_BaseSingleton {

	private $enable_cruises;

	protected function __construct() {

		global $bookyourtravel_theme_globals;

		$this->enable_cruises = $bookyourtravel_theme_globals->enable_cruises();

		// our parent class might
		// contain shared code in its constructor
		parent::__construct();
	}

    public function init() {

		if ($this->enable_cruises) {
            add_action( 'admin_menu' , array( $this, 'cruise_calendar_admin_page' ) );
			add_filter( 'set-screen-option', array( $this, 'cruise_calendar_set_screen_options' ), 11, 3);
			add_action( 'admin_head', array( $this, 'cruise_calendar_admin_head' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts_styles' ) );
		}
	}

	function cruise_calendar_admin_page() {
		$hook = add_submenu_page('edit.php?post_type=cruise', esc_html__('Sync Cruise Calendars', 'bookyourtravel'), esc_html__('Sync calendars', 'bookyourtravel'), 'edit_posts', basename(__FILE__), array($this, 'cruise_calendar_admin_display' ));
        add_action( "load-$hook", array($this,  'cruise_calendar_add_screen_options' ));            
	}

	function cruise_calendar_add_screen_options() {
        global $wp_cruise_calendar_table;

		$option = 'per_page';
        $args = array(
            'label' => esc_html__('Sync cruise calendars', 'bookyourtravel'),
            'default' => 50,
            'option' => 'cruise_calendar_entries_per_page'
        );
        add_screen_option( $option, $args );
        
	}

	function cruise_calendar_admin_display() {

        global $bookyourtravel_cruise_helper, $bookyourtravel_cabin_type_helper, $bookyourtravel_theme_globals;

		echo '<div class="wrap">';
        echo '<h2>' . esc_html__('Sync cruise calendars', 'bookyourtravel') . '</h2>';
        echo '<p>' . esc_html__('Please use the interface below to export or import your ical calendars.', 'bookyourtravel') . '</p>';
        echo '<p>' . esc_html__('Please note that calendars are not synced in real time across different channel managers and must be done manually.', 'bookyourtravel') . '</p>';
        echo '<p>' . esc_html__('Please note that if you are using WooCommerce, only bookings with associated Orders marked as Completed will be exported.', 'bookyourtravel') . '</p>';
        
        $screen = get_current_screen();
        $option = $screen->get_option('per_page', 'option');

        $user_id = get_current_user_id();
        $per_page = get_user_meta($user_id, $option, true);

        if ( empty ( $per_page) || $per_page < 1 ) {
            $per_page = $screen->get_option( 'per_page', 'default' );
        }

        $page = isset($_REQUEST['paged']) ? $_REQUEST['paged'] : 0;
        $orderby = isset($_REQUEST['orderby']) ? $_REQUEST['orderby'] : 'title';
        $order = isset($_REQUEST['order']) ? $_REQUEST['order'] : 'asc';

        $cruise_results = $bookyourtravel_cruise_helper->list_cruises($page, $per_page, $orderby, $order);

        $admin_table = new WP_List_Table();
        $total_items = $cruise_results['total']; //return the total number of affected rows

		$total_pages = ceil($total_items / $per_page);
		$admin_table->set_pagination_args( array(
			"total_items" => $total_items,
			"total_pages" => $total_pages,
			"per_page" => $per_page,
        ) );
        
        $href = admin_url( 'edit.php?post_type=cruise&amp;page=theme_cruise_calendar_admin.php');

        $id_href = $order == 'asc' ? $href . '&amp;orderby=Id&amp;order=desc' : $href . '&amp;orderby=Id&amp;order=asc';
        $title_href = $order == 'asc' ? $href . '&amp;orderby=title&amp;order=desc' : $href . '&amp;orderby=title&amp;order=asc';
        ?>
        <table class="wp-list-table widefat fixed striped table-view-list">
        <thead>
        <tr>
            <th scope="col" id="Id" class="manage-column column-Id column-primary sortable asc">
                <a href="<?php echo esc_url($id_href); ?>">
                    <span><?php esc_html_e("Id", "bookyourtravel"); ?></span>
                </a>
            </th>
            <th scope="col" id="CruiseName" class="manage-column column-CruiseName sortable asc">
                <a href="<?php echo $title_href; ?>">
                    <span><?php esc_html_e("Cruise", "bookyourtravel"); ?></span>
                </a>
            </th>
            <th scope="col" id="ExportSchedules" class="manage-column column-ExportSchedules">
                <span><?php esc_html_e("Export schedules", "bookyourtravel"); ?></span>
            </th>
            <th scope="col" id="ExportBookings" class="manage-column column-ExportBookings">
                <span><?php esc_html_e("Export bookings", "bookyourtravel"); ?></span>
            </th>            
        </tr>
        </thead>        
        <tbody>
        <?php
        if (count($cruise_results) > 0 && $cruise_results['total'] > 0) {
            foreach ($cruise_results['results'] as $cruise_result) {
                $cruise_obj = new BookYourTravel_Cruise(intval($cruise_result->ID));
                $cabin_type_ids = $cruise_obj->get_cabin_types();
            ?>
            <tr>
                <td><?php echo esc_attr($cruise_result->ID); ?></td>
                <td><?php echo esc_attr($cruise_result->post_title); ?></td>
                <td>
                    <?php
                    for ($i = 0; $i < count($cabin_type_ids); $i++) {
                        $cabin_type_id = $cabin_type_ids[$i];
                        $cabin_type_obj = new BookYourTravel_Cabin_Type(intval($cabin_type_id));
                        if ($cabin_type_obj) { 
                            $schedule_results = $bookyourtravel_cruise_helper->list_cruise_schedules(null, 0, 'Id', 'ASC', 0, 0, 0, $cruise_result->ID, $cabin_type_id);
                            ?>
                            <span class="ics-title"><?php echo esc_html($cabin_type_obj->get_title()); ?></span>
                            <?php if ($schedule_results["total"] > 0) { ?>
                            <a class="ics-url" target="_blank" href="<?php echo esc_url(get_site_url()); ?>?ics=1&amp;type=cruise_schedules&amp;cruise_id=<?php echo esc_attr($cruise_result->ID); ?>&amp;cabin_type_id=<?php echo esc_attr($cabin_type_id); ?>"><?php echo get_site_url(); ?>?ics=1&amp;type=cruise_schedules&amp;cruise_id=<?php echo esc_attr($cruise_result->ID); ?>&amp;cabin_type_id=<?php echo esc_attr($cabin_type_id); ?></a>
                            <a class="ics-download" target="_blank" href="<?php echo esc_url(get_site_url()); ?>?ics=1&amp;type=cruise_schedules&amp;cruise_id=<?php echo esc_attr($cruise_result->ID); ?>&amp;cabin_type_id=<?php echo esc_attr($cabin_type_id); ?>"><?php esc_html_e("Download schedules", "bookyourtravel"); ?></a>
                            <?php
                            } else {
                                _e("Nothing to export", "bookyourtravel");
                            }
                        }
                    }
                ?>
                </td>
                <td>
                <?php
                    for ($i = 0; $i < count($cabin_type_ids); $i++) {
                        $cabin_type_id = $cabin_type_ids[$i];
                        $cabin_type_obj = new BookYourTravel_Cabin_Type(intval($cabin_type_id));
                        if ($cabin_type_obj) { 
                            $booking_results = $bookyourtravel_cruise_helper->list_cruise_bookings(null, 0, 'Id', 'ASC', null, 0, null, $cruise_result->ID, $cabin_type_id);

                            $completed_bookings = array();
                            foreach ($booking_results['results'] as $booking) {
                                if ((!$bookyourtravel_theme_globals->use_woocommerce_for_checkout() && !isset($booking->woo_status)) || strtolower($booking->woo_status) == 'completed') {
                                    $completed_bookings[] = $booking;
                                }
                            }
                            ?>
                            <span class="ics-title"><?php echo esc_html($cabin_type_obj->get_title()); ?></span>
                            <?php if (count($completed_bookings) > 0) { ?>
                            <a class="ics-url" target="_blank" href="<?php echo esc_url(get_site_url()); ?>?ics=1&amp;type=cruise_bookings&amp;cruise_id=<?php echo esc_attr($cruise_result->ID); ?>&amp;cabin_type_id=<?php echo esc_attr($cabin_type_id); ?>"><?php echo get_site_url(); ?>?ics=1&amp;type=cruise_bookings&amp;cruise_id=<?php echo esc_attr($cruise_result->ID); ?>&amp;cabin_type_id=<?php echo esc_attr($cabin_type_id); ?></a>
                            <a class="ics-download" target="_blank" href="<?php echo esc_url(get_site_url()); ?>?ics=1&amp;type=cruise_bookings&amp;cruise_id=<?php echo esc_attr($cruise_result->ID); ?>&amp;cabin_type_id=<?php echo esc_attr($cabin_type_id); ?>"><?php esc_html_e("Download bookings", "bookyourtravel"); ?></a>
                            <?php
                            } else {
                                _e("Nothing to export", "bookyourtravel");
                            }
                        }
                    } ?>
                </td>
            </tr>
            <?php
            }
        }

        ?>
        </tbody>
        </table>

        <div class="tablenav bottom">
        <?php $admin_table->pagination('bottom'); ?>
        </div>
        <?php
	}

	function cruise_calendar_set_screen_options($status, $option, $value) {
		if ( 'cruise_calendar_entries_per_page' == $option ) {
			return $value;
		}
	}

	function cruise_calendar_admin_head() {
		$page = ( isset($_GET['page'] ) ) ? esc_attr( $_GET['page'] ) : false;
		if( 'theme_cruise_calendar_admin.php' != $page ) {
			return;
		}
	}

	function enqueue_admin_scripts_styles() {

		$page = ( isset($_GET['page'] ) ) ? esc_attr( $_GET['page'] ) : false;
		if ('theme_cruise_calendar_admin.php' != $page) {
			return;
		}

		$date_format = get_option('date_format');

		wp_enqueue_script( 'bookyourtravel-admin-script', BookYourTravel_Theme_Utils::get_file_uri ('/js/admin/admin.js'), array('jquery'), BOOKYOURTRAVEL_VERSION);
	}
}

global $cruise_calendar_admin;
$cruise_calendar_admin = BookYourTravel_Cruise_Calendar_Admin::get_instance();
