<?php
// Display custom fields in the "General" tab of the product edit page
add_action('woocommerce_product_options_general_product_data', 'add_custom_product_fields');


add_filter( 'product_type_options', 'add_enable_api_price_product_option' );
function add_enable_api_price_product_option( $product_type_options ) {
    $product_type_options['enable_api_price'] = array(
        'id'            => '_enable_api_price',
        'wrapper_class' => 'show_if_simple',
        'label'         => __( 'Enable API Price', 'woocommerce' ),
        'description'   => __( 'Enable this option to use API pricing for the product.', 'woocommerce' ),
        'default'       => 'no'
    );

    return $product_type_options;
}

add_action( 'woocommerce_process_product_meta_simple', 'save_enable_api_price_option_fields'  );
function save_enable_api_price_option_fields( $post_id ) {
    $enable_api_price = isset( $_POST['_enable_api_price'] ) ? 'yes' : 'no';
    update_post_meta( $post_id, '_enable_api_price', $enable_api_price );
}

function add_custom_product_fields()
{
    global $product_object;

    echo '<table style="display:inline-block; margin-left:5px; margin-top:10px;">';
    echo '<tr style="display:inline-block;"><th>Denominasi </th><th>Dinar</th></tr>';
    echo '<tr style="display:inline-block;"><td>1/4 = </td><td><strong>0.25</strong></td></tr>';
    echo '<tr style="display:inline-block;"><td>1/2 = </td><td><b>0.5</b></td></tr>';
    //echo '<tr style="display:inline-block;"><td>1/8 = </td><td><b>0.125</b></td></tr>';
    echo '</table>';


    


    echo '<div class="product_custom_fields">';    

    // Text field
    woocommerce_wp_text_input(
        array(
            'id' => '_nilai_kgt',
            'label' => __('Nilai Produk', 'woocommerce'),
            'placeholder' => '',
            'desc_tip' => 'true',
            'type' => 'number',
            'custom_attributes' => array( 'step' => 'any', 'min' => '0' ),
            'description' => __('Masukkan nilai produk seperti 1,2,3,1/2 atau 1/4', 'woocommerce')
        )
    );   

    // Select field
    woocommerce_wp_select(
        array(
            'id' => '_jenis_produk',
            'label' => __('Jenis Produk KGT', 'woocommerce'),
            'options' => array(
                'dinar' => __('Dinar', 'woocommerce'),
                'wafer' => __('Wafer', 'woocommerce'),
            ),
            'desc_tip' => 'true',
            'description' => __('Select an option.', 'woocommerce')
        )
    );

    echo '</div>';

    // Fetch current value of _enable_api_price
    $enable_api_price_value = get_post_meta($product_object->get_id(), '_enable_api_price', true);

    ?>
    <script type="text/javascript">
        jQuery(function($){
            var enableApiPriceCheckbox = $('#_enable_api_price');

            // Initial state based on database value
            toggleCustomFields(enableApiPriceCheckbox.prop('checked'));

            // Toggle fields on checkbox change
            enableApiPriceCheckbox.change(function(){
                toggleCustomFields($(this).prop('checked'));
            });

            function toggleCustomFields(show){
                if(show){
                    $('.product_custom_fields').show();
                } else {
                    $('.product_custom_fields').hide();
                }
            }
        });
    </script>
    <?php
}


// Save custom fields when the product is saved
add_action('woocommerce_process_product_meta', 'save_custom_product_fields');

function save_custom_product_fields($product_id)
{
    // Save text field
    $nilai_kgt = isset($_POST['_nilai_kgt']) ? sanitize_text_field($_POST['_nilai_kgt']) : '';
    update_post_meta($product_id, '_nilai_kgt', $nilai_kgt);

    // Save select field
    $jenis_produk = isset($_POST['_jenis_produk']) ? sanitize_text_field($_POST['_jenis_produk']) : '';
    update_post_meta($product_id, '_jenis_produk', $jenis_produk);
}

//Produk variation/////////////////

// Add checkbox and custom field
function action_woocommerce_variation_options( $loop, $variation_data, $variation ) {
    $is_checked = get_post_meta( $variation->ID, '_enable_api_price', true );

    if ( $is_checked == 'yes' ) {
        $is_checked = 'checked';
        $display_style = 'block'; // Show the custom field
    } else {
        $is_checked = '';
        $display_style = 'none'; // Hide the custom field
    }

    ?>
    <label class="tips" data-tip="<?php esc_attr_e( 'Using price from api gold price', 'woocommerce' ); ?>">
        <?php esc_html_e( 'Enable API Price?', 'woocommerce' ); ?>
        <input type="checkbox" class="checkbox variable_checkbox" name="_enable_api_price[<?php echo esc_attr( $loop ); ?>]"<?php echo $is_checked; ?>/>
    </label>

    <div class="cage_code_options_group options_group" style="display: <?php echo $display_style; ?>">
        <?php 
        
        woocommerce_wp_text_input( array(
            'id'            => '_nilai_kgt[' . $loop . ']',
            'class'         => 'short',
            'label'         => __( 'Nilai Produk', 'woocommerce' ),
            'placeholder'   => 'Type here...',
			'desc_tip'      => 'true',
            'type' => 'number',
            'custom_attributes' => array( 'step' => 'any', 'min' => '0' ),
            'wrapper_class' => 'form-field form-row form-row-first',
            'description' => __('Masukkan nilai produk seperti 1,2,3,1/2 atau 1/4', 'woocommerce'),
            'value'         => get_post_meta( $variation->ID, '_nilai_kgt', true )
        ));
        
        // Select field
        woocommerce_wp_select( array(
            'id' => '_jenis_produk[' . $loop . ']',
            'label' => __('Jenis Produk KGT', 'woocommerce'),
            'wrapper_class' => 'form-field form-row form-row-last',
            'options' => array(
                'dinar' => __('Dinar', 'woocommerce'),
                'wafer' => __('Wafer', 'woocommerce'),
            ),
            'desc_tip' => 'true',
            'description' => __('Select an option.', 'woocommerce'),
            'value'         => get_post_meta( $variation->ID, '_jenis_produk', true )
        ));
        
        ?>
    </div>

    <script>
        jQuery(document).ready(function($) {
            // Add an event listener to the checkbox
            $('input.variable_checkbox').on('change', function() {
                var display_style = $(this).is(':checked') ? 'block' : 'none';
                $(this).closest('div').find('.cage_code_options_group').css('display', display_style);
            });

            // Trigger the change event to initialize the field's visibility
            $('input.variable_checkbox').trigger('change');
        });
    </script>
    <?php
}
add_action( 'woocommerce_variation_options', 'action_woocommerce_variation_options', 10, 3);


// Save checkbox
function action_woocommerce_save_product_variation( $variation_id, $i ) {
    if ( ! empty( $_POST['_enable_api_price'] ) && ! empty( $_POST['_enable_api_price'][$i] ) ) {
        update_post_meta( $variation_id, '_enable_api_price', 'yes' );
    } else {
        update_post_meta( $variation_id, '_enable_api_price', 'no' );
    }
}
add_action( 'woocommerce_save_product_variation', 'action_woocommerce_save_product_variation', 10, 2 );

add_action( 'woocommerce_save_product_variation', 'rudr_save_fields', 10, 2 );

function rudr_save_fields( $variation_id, $loop ) {

	// Text Field
	$text_field = ! empty( $_POST[ '_nilai_kgt' ][ $loop ] ) ? $_POST[ '_nilai_kgt' ][ $loop ] : '';
	update_post_meta( $variation_id, '_nilai_kgt', sanitize_text_field( $text_field ) );
    
    $text_field = ! empty( $_POST[ '_jenis_produk' ][ $loop ] ) ? $_POST[ '_jenis_produk' ][ $loop ] : '';
	update_post_meta( $variation_id, '_jenis_produk', sanitize_text_field( $text_field ) );

}