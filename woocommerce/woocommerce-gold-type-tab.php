<?php
// Display custom fields in the "General" tab of the product edit page
add_action('woocommerce_product_options_general_product_data', 'add_custom_product_fields');

function add_custom_product_fields()
{
    global $product_object;

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
    $is_checked = get_post_meta( $variation->ID, '_mycheckbox', true );

    if ( $is_checked == 'yes' ) {
        $is_checked = 'checked';
        $display_style = 'block'; // Show the custom field
    } else {
        $is_checked = '';
        $display_style = 'none'; // Hide the custom field
    }

    ?>
    <label class="tips" data-tip="<?php esc_attr_e( 'This is my data tip', 'woocommerce' ); ?>">
        <?php esc_html_e( 'Checkbox:', 'woocommerce' ); ?>
        <input type="checkbox" class="checkbox variable_checkbox" name="_mycheckbox[<?php echo esc_attr( $loop ); ?>]"<?php echo $is_checked; ?>/>
    </label>

    <div class="cage_code_options_group options_group" style="display: <?php echo $display_style; ?>">
        <?php woocommerce_wp_text_input( array(
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
        )); ?>
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
    if ( ! empty( $_POST['_mycheckbox'] ) && ! empty( $_POST['_mycheckbox'][$i] ) ) {
        update_post_meta( $variation_id, '_mycheckbox', 'yes' );
    } else {
        update_post_meta( $variation_id, '_mycheckbox', 'no' );
    }
}
add_action( 'woocommerce_save_product_variation', 'action_woocommerce_save_product_variation', 10, 2 );

add_action( 'woocommerce_save_product_variation', 'rudr_save_fields', 10, 2 );

function rudr_save_fields( $variation_id, $loop ) {

	// Text Field
	$text_field = ! empty( $_POST[ '_nilai_kgt' ][ $loop ] ) ? $_POST[ '_nilai_kgt' ][ $loop ] : '';
	update_post_meta( $variation_id, '_nilai_kgt', sanitize_text_field( $text_field ) );

}