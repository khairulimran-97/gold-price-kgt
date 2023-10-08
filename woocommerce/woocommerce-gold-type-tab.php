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