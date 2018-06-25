<?php
/*

Plugin Name: Data Estate Connecter	 
Description: The Data Estate Connecter (DEC) plugin integrates your WordPress site with the Data Estate API gain access to various Estate content. The API supports accessing ATDW’s tourism data as long as you have a valid ATDW distributor API Key.
Author: Data Estate
Author URI: http://www.dataestate.com.au
Version: 1.6
License:           GPL-2.0+
License URI:       http://www.gnu.org/licenses/gpl-2.0.txt

Settings: link to the settings page. 
*/

$siteurl = get_option('siteurl');
define('DEC_FOLDER', dirname(plugin_basename(__FILE__)));
define('DEC_URL', $siteurl . '/wp-content/plugins/' . DEC_FOLDER);
define('DEC_FILE_PATH', dirname(__FILE__));
define('DEC_DIR_NAME', basename(DEC_FILE_PATH));
// this is the table prefix
global $wpdb, $table_prefix;
define('DEC_TABLE_DETAILS', $table_prefix . 'dec_details');

register_activation_hook(__FILE__, 'dec_install');
register_deactivation_hook(__FILE__, 'dec_deactivate');
register_uninstall_hook(__FILE__, 'dec_uninstall');

function dec_install() {		
	global $wpdb;
	// Create USERS table
	$api_details = DEC_TABLE_DETAILS;
	if ($wpdb->get_var("show tables like '$api_details'") != $api_details){
		$sql0  = "CREATE TABLE IF NOT EXISTS `" . $api_details . "` ( ";
		$sql0 .= "  `id`  int(11)   NOT NULL auto_increment, ";
		$sql0 .= "  `api_base_url` text NOT NULL, ";
		$sql0 .= "  `api_end_point` text NOT NULL, ";
		$sql0 .= "  `api_key` text NOT NULL, ";
		$sql0 .= "  `type` text NOT NULL, ";
		$sql0 .= "  `main_estate_id` text NOT NULL, ";
		$sql0 .= "  PRIMARY KEY `id` (`id`) ";
		$sql0 .= ") ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ; ";
		#We need to include this file so we have access to the dbDelta function below (which is used to create the table)
		require_once(ABSPATH . '/wp-admin/upgrade-functions.php');
		dbDelta($sql0);
		$sql = "INSERT INTO `".DEC_TABLE_DETAILS."`(`api_base_url`,`api_end_point`,`api_key`,`main_estate_id`,`type`) 
				VALUES ('http://api-uat.dataestate.net/v2','estates/data/','','','de')";
		$da_value=$wpdb->query($wpdb->prepare($sql, array($api_end_point,$api_key)));
		//TODO... Refactor this
		$sql = "INSERT INTO `".DEC_TABLE_DETAILS."`(`api_base_url`,`api_end_point`,`api_key`,`main_estate_id`,`type`) 
				VALUES ('maps.googleapis.com','maps/api/js','','','google')";
		$da_value=$wpdb->query($wpdb->prepare($sql, [$gmap_api_endpoint, $gmap_key]));
	}
}
function dec_deactivate() {
	global $wpdb;
	$api_details = DEC_TABLE_DETAILS;
	$del_albums = "DROP TABLE IF EXISTS `".$api_details. "`";
	$wpdb->query($wpdb->prepare($del_albums));
}

function dec_uninstall() {     
}
//Add settings to menu
function my_plugin_menu() {
	add_options_page( 
		'DEC Settings',
		'DEC Config',
		'manage_options',
		'dec_details_page.php',
		'dec_details_page'
	);
}
//Add settings to plugin
function add_setting_links ($links) {
	$mylinks = array(
	'<a href="' . admin_url( 'options-general.php?page=dec_details_page.php').'">Settings</a>',
	);
	return array_merge( $links, $mylinks );
}

function dec_details_page() {
	require_once 'dec_details_page.php';  
}
function register_search_widget() {
	global $wpdb;
	$api_info = $wpdb->get_results("SELECT api_base_url, api_end_point, api_key from `".DEC_TABLE_DETAILS."` WHERE id=1", ARRAY_A);
	if (count($api_info) > 0) {
		wp_register_script('dec-search-widget', $api_info[0]["api_base_url"].'/Widget/search2?api_key='.$api_info[0]["api_key"].'&callback=init');
		wp_register_script('dec-search-widget-txa', $api_info[0]["api_base_url"].'/Widget/search2?api_key='.$api_info[0]["api_key"].'&callback=init&txa_widget=true');
	}
}
function register_map_clusterer() {
	global $wpdb;
	wp_register_script('dec-map-clusterer', DEC_URL.'/js/markerclusterer.js');
}
function register_google_map() {
	global $wpdb;
	$api_info = $wpdb->get_results("SELECT api_base_url, api_end_point, api_key from `".DEC_TABLE_DETAILS."` WHERE id=2", ARRAY_A);
	if (count($api_info) > 0) {
		wp_register_script('dec-google-map', 'https://'.$api_info[0]["api_base_url"].'/'.$api_info[0]["api_end_point"].'?key='.$api_info[0]["api_key"].'&callback=initMap');
	}
}
function register_material_font() {
	wp_register_style('google-material-font', 'https://fonts.googleapis.com/icon?family=Material+Icons');
}
/******Styles******/
wp_register_style( 'shortcode-style', DEC_URL . '/css/shortcode-style.css' );
wp_enqueue_style( 'shortcode-style' );
/******Actions*****/
add_action('admin_menu', 'my_plugin_menu' );
add_action('wp_enqueue_scripts', 'register_search_widget');
add_action('wp_enqueue_scripts', 'register_google_map');
add_action('wp_enqueue_scripts', 'register_map_clusterer');
add_action('wp_enqueue_scripts', 'register_material_font');
/******Filters*****/
add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'add_setting_links');
/******ShortCodes****/
add_shortcode('dec-name', 'dec_name');
add_shortcode('dec-description', 'dec_description');
add_shortcode('dec-address', 'dec_address');
add_shortcode('dec-attributes', 'dec_attributes');
add_shortcode('dec-phone', 'dec_phone');
add_shortcode('dec-email', 'dec_email');
add_shortcode('dec-url', 'dec_url');
add_shortcode('dec-images', 'dec_images');
add_shortcode('dec-gallery', 'dec_gallery');
add_shortcode('dec-subtypes', 'dec_subtypes');
add_shortcode('dec-category', 'dec_category');
add_shortcode('dec-location', 'dec_location');
add_shortcode('dec-star-rating', 'dec_star_rating');
add_shortcode('dec-event-date', 'dec_event_date');
add_shortcode('dec-txa-button', 'dec_txa_button');
add_shortcode('dec-rate', 'dec_rate');
add_shortcode('dec-rooms', 'dec_rooms');
add_shortcode('dec-awards', 'dec_awards');
add_shortcode('atdw-beacon', 'atdw_beacon');
/** Widget related shortcodes **/
add_shortcode('dec-widget', 'dec_widget');
add_shortcode('dec-map-widget', 'dec_map_widget');
add_shortcode('dec-assets', 'dec_assets');
add_shortcode('dec-estates', 'dec_estates');
add_shortcode('dec-awarded-estates', 'dec_awarded_estates');
add_shortcode('dec-condition', 'dec_condition');
add_shortcode('dec-ifnot-empty', 'dec_ifnot_empty');
// add_shortcode('dec-search-categories', 'dec_search_cats');
require_once 'de_api.php';
require_once 'functions.php';


