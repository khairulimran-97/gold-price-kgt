<?php

// Define a global variable to store the API data
global $api_data;

// Function to fetch data from the API and set the global variable
function fetch_api_data() {
    global $api_data;

    // Make an HTTP request to the API
    $api_url = 'https://kgt.com.my/rest/api.php';
    $api_response = wp_safe_remote_get( $api_url );

    // Check if the API request was successful
    if ( ! is_wp_error( $api_response ) && wp_remote_retrieve_response_code( $api_response ) === 200 ) {
        // Parse the API response as JSON
        $api_data = json_decode( wp_remote_retrieve_body( $api_response ), true );
    }
}

// Call the function to fetch API data (you can call this at the start of your code)
fetch_api_data();

// Function to maybe alter product price excluding tax
function maybe_alter_product_price_excluding_tax( $price, $qty, $product ){
    global $api_data;

    // Check if $product is a variation
    if ( $product->is_type( 'variation' ) ) {

        // Get the value of $jenis_produk from the parent product
        $jenis_produk = get_post_meta( $product->get_parent_id(), '_jenis_produk', true );
        $nilai_kgt = get_post_meta( $product->get_id(), '_nilai_kgt', true );
    } else {
        // Get the value of $jenis_produk from the current product
        $jenis_produk = get_post_meta( $product->get_id(), '_jenis_produk', true );
        $nilai_kgt = get_post_meta( $product->get_id(), '_nilai_kgt', true );
    }
    $price = 0;
    // Check if API data is available
    if ( isset( $api_data ) ) {
        // Check the value of $jenis_produk and set the price accordingly
        if ( $jenis_produk === 'dinar' ) {
            // Set the price based on harga_nilai
            $price = $api_data['harga_nilai'] * $nilai_kgt;
        } elseif ( $jenis_produk === 'wafer' ) {
            // Set the price based on harga_wafer
            $price = $api_data['harga_wafer'];
        }
    }

    return $price;
}

function maybe_alter_product_price( $price, $product ){

    global $api_data;

    // Check if $product is a variation
    if ( $product->is_type( 'variation' ) ) {
        $jenis_produk = get_post_meta( $product->get_parent_id(), '_jenis_produk', true );
        $nilai_kgt = get_post_meta( $product->get_id(), '_nilai_kgt', true );
    } else {
        // Get the value of $jenis_produk from the current product
        $jenis_produk = get_post_meta( $product->get_id(), '_jenis_produk', true );
        $nilai_kgt = get_post_meta( $product->get_id(), '_nilai_kgt', true );
    }
    $price = 0;
    // Check if API data is available
    if ( isset( $api_data ) ) {
        // Check the value of $jenis_produk and set the price accordingly
        if ( $jenis_produk === 'dinar' ) {
            // Set the price based on harga_nilai
            $price = $api_data['harga_nilai'] * $nilai_kgt;
        } elseif ( $jenis_produk === 'wafer' ) {
            // Set the price based on harga_wafer
            $price = $api_data['harga_wafer'];
        }
    }

    return $price;
}

function maybe_alter_cart_item_data( $cart_item_data, $product_id, $variation_id ){
    global $api_data;
    $product = wc_get_product( $product_id );

    if ( $product->is_type( 'variation' ) ) {
        $jenis_produk = get_post_meta( $product->get_parent_id(), '_jenis_produk', true );
        $nilai_kgt = get_post_meta( $product->get_id(), '_nilai_kgt', true );
    } else {
        $jenis_produk = get_post_meta( $product->get_id(), '_jenis_produk', true );
        $nilai_kgt = get_post_meta( $product->get_id(), '_nilai_kgt', true );
    }
    $price = 0;

    // Check if API data is available
    if ( isset( $api_data ) ) {
        // Check the value of $jenis_produk and set the price accordingly
        if ( $jenis_produk === 'dinar' ) {
            // Set the price based on harga_nilai
            $price = $api_data['harga_nilai'] * $nilai_kgt;
        } elseif ( $jenis_produk === 'wafer' ) {
            // Set the price based on harga_wafer
            $price = $api_data['harga_wafer'];
        }
    }

    $cart_item_data['altered_price'] = $price;
    return $cart_item_data;
}


function maybe_alter_calculate_totals( $cart_obj ) {

    if ( is_admin() && ! defined( 'DOING_AJAX' ) )
        return;

    foreach ( $cart_obj->get_cart() as $key=>$value ) {

        if ( isset( $value['altered_price'] ) ) {
            $value['data']->set_price( $value['altered_price'] );
        }
    }
}

add_filter( 'woocommerce_product_get_price', 'maybe_alter_product_price', 99, 2 );
add_filter( 'woocommerce_get_price_excluding_tax', 'maybe_alter_product_price_excluding_tax', 99, 3 );
add_filter( 'woocommerce_variation_prices_price', 'maybe_alter_product_price', 99, 2 );
add_filter( 'woocommerce_variation_prices_regular_price', 'maybe_alter_product_price', 99, 2 );
add_filter( 'woocommerce_add_cart_item_data', 'maybe_alter_cart_item_data', 99, 3 );
add_action( 'woocommerce_before_calculate_totals', 'maybe_alter_calculate_totals', 99, 1 );