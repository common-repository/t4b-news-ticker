<?php
/*
 * Plugin Name:       T4B News Ticker
 * Plugin URI:        http://wordpress.org/plugins/t4b-news-ticker/
 * Description:       T4B News Ticker is a flexible and easy to use WordPress plugin that allow you to make horizontal News Ticker.
 * Version:           1.3.1
 * Requires at least: 5.2
 * Requires PHP:      7.4
 * Author:            Realwebcare
 * Author URI:        https://www.realwebcare.com/
 * Text Domain:       t4b-news-ticker
 * Domain Path:       /languages
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 */

/**
 * Main plugin file that initializes and manages the "T4B News Ticker" plugin.
 * @package T4B News Ticker v1.3.1 - 31 July, 2024
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
define('T4BNT_PLUGIN_PATH', plugin_dir_path( __FILE__ ));
define('T4BNT_AUF', __FILE__);

require_once ( T4BNT_PLUGIN_PATH . 'inc/ticker-admin.php' );

/* Internationalization */
if (!function_exists('t4bnt_textdomain')) {
	function t4bnt_textdomain() {
		$locale = apply_filters( 'plugin_locale', get_locale(), 't4b-news-ticker' );
		load_textdomain( 't4b-news-ticker', trailingslashit( WP_PLUGIN_DIR ) . 't4b-news-ticker/languages/t4b-news-ticker-' . $locale . '.mo' );
		load_plugin_textdomain( 't4b-news-ticker', FALSE, basename( dirname( __FILE__ ) ) . '/languages/' );
	}
}
add_action( 'init', 't4bnt_textdomain' );

/* Add plugin action links */
if (!function_exists('t4bnt_plugin_actions')) {
	function t4bnt_plugin_actions( $links ) {
        $create_ticker_url = esc_url(menu_page_url('t4bnt-settings', false));
        $create_ticker_url = wp_nonce_url($create_ticker_url, 't4bnt_create_ticker_action');

        $support_url = esc_url("https://wordpress.org/support/plugin/t4b-news-ticker");

        $links[] = '<a href="'. $create_ticker_url .'">'. esc_html__('Settings', 't4b-news-ticker') .'</a>';
        $links[] = '<a href="'. $support_url .'" target="_blank">'. esc_html__('Support', 't4b-news-ticker') .'</a>';
        return $links;
	}
}
add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 't4bnt_plugin_actions' );

/* Enqueue CSS & JS For Admin */
function t4bnt_admin_adding_style() {
	wp_enqueue_script('t4bnt-admin', plugins_url( 'assets/js/t4bnt_admin.js', __FILE__ ), array('jquery'), '1.3.1', true);
	wp_enqueue_style('t4bnt-admin-style', plugins_url( 'assets/css/t4bnt_admin.css', __FILE__ ), '', '1.3.1');
}
add_action( 'admin_enqueue_scripts', 't4bnt_admin_adding_style' );

/* Registering plugin activation hooks */
register_activation_hook( __FILE__, 't4bnt_set_activation_time' );

/* Enqueue front js and css files */
function t4bnt_enqueue_scripts() {
	$t4bnt_enable = t4bnt_get_option( 'ticker_news', 't4bnt_general', 'yes' );		
	$ticker_effect = t4bnt_get_option( 'ticker_effect', 't4bnt_general', 'scroll' );
	if($t4bnt_enable == 'on') {
		if($ticker_effect == 'scroll') {
			wp_register_script('liscroll', plugins_url( 'assets/js/jquery.liscroll.js', __FILE__ ), array('jquery'), '1.3.1', true);
			wp_enqueue_script('liscroll');
		} else {
			wp_register_script('ticker', plugins_url( 'assets/js/jquery.ticker.js', __FILE__ ), array('jquery'), '1.3.1', true);
			wp_enqueue_script('ticker');
		}
		if($ticker_effect == 'scroll') {
			wp_enqueue_style('t4bnewsticker', plugins_url( 'assets/css/t4bnewsticker.css', __FILE__ ), '', '1.3.1');
		} else {
			wp_enqueue_style('tickerstyle', plugins_url( 'assets/css/ticker-style.css', __FILE__ ), '', '1.3.1');
		}
	}
}
add_action( 'wp_enqueue_scripts', 't4bnt_enqueue_scripts' );