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
    // API URL
    $api_url = 'https://kgt.com.my/rest/api.php';

    // Fetch data from the API
    $response = wp_remote_get($api_url);

    // Check if the request was successful
    if (is_array($response) && !is_wp_error($response)) {
        // Parse JSON response
        $data = json_decode(wp_remote_retrieve_body($response));

        if ($data) {
            // Extract data from the JSON response
            $harga_nilai = $data->harga_nilai;
            $harga_nilai_beli = $data->harga_nilai_beli;
            $harga_wafer = $data->harga_wafer;
            $harga_wafer_beli = $data->harga_wafer_beli;
            ?>
            <div class="wrap">
                <h1>Gold Price Woocommerce</h1>
                <h2>Metal Prices</h2>
                <table class="wp-list-table widefat fixed striped">
                    <thead>
                        <tr>
                            <th>Metal Name</th>
                            <th>Metal Price (RM)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Harga Nilai</td>
                            <td>RM <?php echo esc_html($harga_nilai); ?></td>
                        </tr>
                        <tr>
                            <td>Harga Nilai Beli</td>
                            <td>RM <?php echo esc_html($harga_nilai_beli); ?></td>
                        </tr>
                        <tr>
                            <td>Harga Wafer</td>
                            <td>RM <?php echo esc_html($harga_wafer); ?></td>
                        </tr>
                        <tr>
                            <td>Harga Wafer Beli</td>
                            <td>RM <?php echo esc_html($harga_wafer_beli); ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <?php
        } else {
            // Handle JSON parsing error
            echo '<div class="notice notice-error"><p>Unable to parse JSON response from the API.</p></div>';
        }
    } else {
        // Handle API request error
        echo '<div class="notice notice-error"><p>Failed to fetch data from the API.</p></div>';
    }
}
