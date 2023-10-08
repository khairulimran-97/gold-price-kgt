<?php
/**
 * Plugin Name: Gold Price Woocommerce
 * Plugin URI: https://yourwebsite.com/
 * Description: A plugin to manage gold prices for Woocommerce
 * Version: 1.0
 * Author: Your Name
 * Author URI: https://yourwebsite.com/
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Define plugin constants
define( 'GPW_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'GPW_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

// Include plugin files
require_once GPW_PLUGIN_DIR . 'includes/functions.php';
require_once GPW_PLUGIN_DIR . 'includes/admin-page.php';
require_once GPW_PLUGIN_DIR . 'includes/shortcode.php';
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