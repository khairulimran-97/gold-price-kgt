<?php
// Exit if accessed directly
//if ( ! defined( 'ABSPATH' ) ) {
//	exit;
//}

// Define custom database table name
//global $wpdb;
//define( 'GPW_TABLE_NAME', $wpdb->prefix . 'gpw_prices' );

// Create custom database table on plugin activation
//function gpw_activate_plugin() {
  //  global $wpdb;

    //$table_name = GPW_TABLE_NAME;
    //$charset_collate = $wpdb->get_charset_collate();

    //$sql = "CREATE TABLE $table_name (
      //  id mediumint(9) NOT NULL AUTO_INCREMENT,
        // metal_name varchar(255) NOT NULL,
        // metal_price decimal(10,2) NOT NULL,
        // modified_date datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        // PRIMARY KEY  (id)
    // ) $charset_collate;";

    // require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    // dbDelta( $sql );
// }
// Delete custom database table on plugin deactivation
//function gpw_deactivate_plugin() {
	//global $wpdb;

	//$table_name = GPW_TABLE_NAME;

	//$sql = "DROP TABLE IF EXISTS $table_name;";

	//$wpdb->query( $sql );
//}
