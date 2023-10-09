<?php
/**
 * Plugin Name: Gold Price KGT Woocommerce
 * Plugin URI: https://lamanweb.my/
 * Description: A plugin to manage gold prices for Woocommerce
 * Version: 1.0
 * Author: Web Impian Sdn  Bhd
 * Author URI: https://lamanweb.my/
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Define plugin constants
define( 'GPW_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'GPW_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

// Include plugin files
require_once GPW_PLUGIN_DIR . 'includes/admin-page.php';
require_once GPW_PLUGIN_DIR . 'woocommerce/woocommerce-functions.php';
require_once GPW_PLUGIN_DIR . 'woocommerce/woocommerce-gold-type-tab.php';
require_once GPW_PLUGIN_DIR . 'woocommerce/woocommerce-goldprice.php';

// Register activation and deactivation hooks
register_activation_hook( __FILE__, 'gpw_activate_plugin' );
register_deactivation_hook( __FILE__, 'gpw_deactivate_plugin' );

// Enqueue CSS file
function gpw_enqueue_styles() {
	wp_enqueue_style( 'gpw-style', GPW_PLUGIN_URL . 'css/style.css' );
}
add_action( 'wp_enqueue_scripts', 'gpw_enqueue_styles' );


// Enqueue CSS file
function gpw_admin_enqueue_styles() {
	wp_enqueue_style( 'gpw-admin-style', GPW_PLUGIN_URL . 'css/style.css' );
}
add_action( 'admin_enqueue_scripts', 'gpw_admin_enqueue_styles' );

// Enqueue JavaScript file
function gpw_admin_enqueue_scripts() {
	wp_enqueue_script( 'gpw-admin-script', GPW_PLUGIN_URL . 'js/script.js', array( 'jquery' ), '1.0', true );
}
add_action( 'admin_enqueue_scripts', 'gpw_admin_enqueue_scripts' );

// Add settings link on plugin page
function gpw_add_settings_link($links) {
    $settings_link = '<a href="admin.php?page=gpw-settings">' . __( 'Harga Semasa' ) . '</a>';
    array_unshift($links, $settings_link);
    return $links;
}
$plugin = plugin_basename(__FILE__); 
add_filter("plugin_action_links_$plugin", 'gpw_add_settings_link');
