<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Add admin menu item for plugin settings
function gpw_add_admin_menu() {
	add_menu_page(
		'Gold Price Woocommerce',
		'Gold Price Woocommerce',
		'manage_options',
		'gpw-settings',
		'gpw_settings_page',
		'dashicons-money-alt',
		30
	);
}
add_action( 'admin_menu', 'gpw_add_admin_menu' );

// Display plugin settings page
function gpw_settings_page() {
    global $wpdb;

    $table_name = GPW_TABLE_NAME;

    // Handle form submission to update metal price
    if ( isset( $_POST['gpw_update_submit'] ) ) {
        $metal_id = absint( $_POST['gpw_metal_id'] );
        $metal_price = sanitize_text_field( $_POST['gpw_metal_price'] );

        if ( empty( $metal_price ) ) {
            echo '<div class="notice notice-error"><p>Please enter a valid price.</p></div>';
        } else {
            $data = array(
                'metal_price' => $metal_price,
                'modified_date' => current_time( 'mysql' ),
            );
            $where = array(
                'id' => $metal_id,
            );
            $wpdb->update( $table_name, $data, $where );

            $metal_name = $wpdb->get_var( $wpdb->prepare( "SELECT metal_name FROM $table_name WHERE id = %d", $metal_id ) );
            echo '<div class="notice notice-success"><p>Metal price for ' . esc_html( $metal_name ) . ' updated to RM' . esc_html( $metal_price ) . '.</p></div>';
        }
    }

    // Handle form submission to delete metal price
    if ( isset( $_POST['gpw_delete_submit'] ) ) {
        $metal_id = absint( $_POST['gpw_metal_id'] );

        // Display confirmation popup
        echo '<div id="gpw-delete-popup" class="gpw-popup">
                <div class="gpw-popup-content">
                    <h3>Are you sure you want to delete this metal price?</h3>
                    <p>This action cannot be undone. The data will be lost forever.</p>
                    <form method="post">
                        <input type="hidden" name="gpw_metal_id" value="' . esc_attr( $metal_id ) . '" />
                        <button type="submit" name="gpw_delete_confirm_submit" class="button-primary">Delete</button>
                        <button type="button" class="button-secondary gpw-popup-close">Cancel</button>
                    </form>
                </div>
            </div>';
    }

    // Handle form submission to confirm delete metal price
    if ( isset( $_POST['gpw_delete_confirm_submit'] ) ) {
        $metal_id = absint( $_POST['gpw_metal_id'] );

        $where = array(
            'id' => $metal_id,
        );
        $wpdb->delete( $table_name, $where );

        $metal_name = $wpdb->get_var( $wpdb->prepare( "SELECT metal_name FROM $table_name WHERE id = %d", $metal_id ) );
        echo '<div class="notice notice-success"><p>Metal price for ' . esc_html( $metal_name ) . ' deleted.</p></div>';
    }

	// Handle form submission to add new metal price
    if ( isset( $_POST['gpw_add_submit'] ) ) {
        $metal_name = sanitize_text_field( $_POST['gpw_metal_name'] );
        $metal_price = sanitize_text_field( $_POST['gpw_metal_price'] );

        if ( empty( $metal_name ) || empty( $metal_price ) ) {
            echo '<div class="notice notice-error"><p>Please fill in all fields.</p></div>';
        } else {
            $data = array(
                'metal_name' => $metal_name,
                'metal_price' => $metal_price,
                'modified_date' => current_time( 'mysql' ),
            );
            $wpdb->insert( $table_name, $data );

            echo '<div class="notice notice-success"><p>Metal price for ' . esc_html( $metal_name ) . ' added successfully.</p></div>';
        }
    }

    // Display metal prices table
    $results = $wpdb->get_results( "SELECT * FROM $table_name" );
    ?>
    <div class="wrap">
        <h1>Gold Price Woocommerce</h1>
        <h2>Metal Prices</h2>
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th>Metal Name</th>
                    <th>Metal Price (RM)</th>
                    <th>Last Modified</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ( $results as $result ) : ?>
                    <tr>
                        <td><?php echo esc_html( $result->metal_name ); ?></td>
                        <td>
                            <form method="post">
                                <input type="hidden" name="gpw_metal_id" value="<?php echo esc_attr( $result->id ); ?>" />
                                RM <input type="text" name="gpw_metal_price" value="<?php echo esc_attr( $result->metal_price ); ?>" class="regular-text" />
                        </td>
                        <td><?php echo esc_html( $result->modified_date ); ?></td>
                        <td>
                            <div class="button-wrapper">
                                <button type="submit" name="gpw_update_submit" class="button-primary">Update</button>
                                <form method="post">
                                    <input type="hidden" name="gpw_metal_id" value="<?php echo esc_attr( $result->id ); ?>" />
                                    <?php submit_button( 'Delete', 'delete', 'gpw_delete_submit', false ); ?>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <h2>Add New Metal Price</h2>
        <form method="post">
            <table class="form-table">
                <tbody>
                    <tr>
                        <th scope="row"><label for="gpw_metal_name">Metal Name</label></th>
                        <td><input type="text" name="gpw_metal_name" id="gpw_metal_name" class="regular-text" /></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="gpw_metal_price">Metal Price (RM)</label></th>
                        <td><input type="text" name="gpw_metal_price" id="gpw_metal_price" class="regular-text" /></td>
                    </tr>
                </tbody>
            </table>
            <?php submit_button( 'Add', 'primary', 'gpw_add_submit' ); ?>
        </form>
    </div>
    <?php
}