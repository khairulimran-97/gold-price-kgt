<?php
/**
 * Add new tab to product data section
 */

 // Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


function gpw_add_product_data_tab( $tabs ) {
    $tabs['gpw_gold_type'] = array(
        'label' => __( 'Gold Type', 'gpw' ),
        'target' => 'gpw_gold_type_data',
        'class' => array( 'show_if_simple', 'show_if_variable' ),
    );
    return $tabs;
}
add_filter( 'woocommerce_product_data_tabs', 'gpw_add_product_data_tab' );

/**
 * Add content to new tab in product data section
 */
function gpw_add_product_data_tab_content() {
    global $post, $wpdb;

    // Get metal names and prices
    $table_name = GPW_TABLE_NAME;
    $results = $wpdb->get_results( "SELECT * FROM $table_name" );

    // Get existing meta values
    $gpw_metal_name = get_post_meta( $post->ID, 'gpw_metal_name', true );
    $gpw_metal_price = get_post_meta( $post->ID, 'gpw_metal_price', true );

    ?>
    <div id="gpw_gold_type_data" class="panel woocommerce_options_panel">
        <div class="options_group">
            <p class="form-field">
                <label for="gpw_metal_name"><?php _e( 'Metal Name', 'gpw' ); ?></label>
                <select name="gpw_metal_name" id="gpw_metal_name">
                    <option value=""><?php _e( 'Not Set', 'gpw' ); ?></option>
                    <?php foreach ( $results as $result ) : ?>
                        <option value="<?php echo esc_attr( $result->metal_name ); ?>" <?php selected( $gpw_metal_name, $result->metal_name ); ?>><?php echo esc_html( $result->metal_name ) . ' (RM' . esc_html( $result->metal_price ) . ')'; ?></option>
                    <?php endforeach; ?>
                </select>
            </p>
        </div>
    </div>
    <?php
}
add_action( 'woocommerce_product_data_panels', 'gpw_add_product_data_tab_content' );

/**
 * Save meta data for new tab in product data section
 */
function gpw_save_product_data_tab_content( $post_id ) {
    $gpw_metal_name = isset( $_POST['gpw_metal_name'] ) ? $_POST['gpw_metal_name'] : '';
    $table_name = GPW_TABLE_NAME;
    global $wpdb;
    $metal_price = $wpdb->get_var( $wpdb->prepare( "SELECT metal_price FROM $table_name WHERE metal_name = %s", $gpw_metal_name ) );
    update_post_meta( $post_id, 'gpw_metal_name', $gpw_metal_name );
    update_post_meta( $post_id, 'gpw_metal_price', $metal_price );
}
add_action( 'woocommerce_process_product_meta', 'gpw_save_product_data_tab_content' );

/**
 * Display metal price in Additional Information section on single product page
 */
function gpw_display_metal_price_in_additional_info() {
    global $product;

    $gpw_metal_price = get_post_meta( $product->get_id(), 'gpw_metal_price', true );

    if ( $gpw_metal_price ) {
        echo '<tr class="woocommerce-product-attributes-item woocommerce-product-attributes-item--gpw-metal-price">
            <th class="woocommerce-product-attributes-item__label">' . __( 'Metal Price', 'gpw' ) . '</th>
            <td class="woocommerce-product-attributes-item__value">' . wc_price( $gpw_metal_price ) . '</td>
        </tr>';
    }
}
add_action( 'woocommerce_product_additional_information', 'gpw_display_metal_price_in_additional_info' );






