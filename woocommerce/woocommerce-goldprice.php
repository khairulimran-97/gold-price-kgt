<?php

function maybe_alter_product_price_excluding_tax( $price, $qty, $product ){

    if ( $product->is_type( 'variation' ) ) {
        $metal_price = get_post_meta( $product->get_parent_id(), 'gpw_metal_price', true );
    } else {
        $metal_price = get_post_meta( $product->get_id(), 'gpw_metal_price', true );
    }
        $price = $product->get_weight() * $metal_price;
    return $price;
}

function maybe_alter_product_price( $price, $product ){

    if ( $product->is_type( 'variation' ) ) {
        $metal_price = get_post_meta( $product->get_parent_id(), 'gpw_metal_price', true );
    } else {
        $metal_price = get_post_meta( $product->get_id(), 'gpw_metal_price', true );
    }
        $price = $product->get_weight() * $metal_price;
    return $price;
}

function maybe_alter_cart_item_data( $cart_item_data, $product_id, $variation_id ){

    $product = wc_get_product( $product_id );
    if ( $product->is_type( 'variation' ) ) {
        $metal_price = get_post_meta( $product->get_parent_id(), 'gpw_metal_price', true );
    } else {
        $metal_price = get_post_meta( $product_id, 'gpw_metal_price', true );
    }
    $price = $product->get_weight() * $metal_price;
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

/**
 * @snippet       Display Weight @ Cart & Checkout - WooCommerce
 * @how-to        Get CustomizeWoo.com FREE
 * @author        Rodolfo Melogli
 * @compatible    WC 3.9
 * @donate $9     https://businessbloomer.com/bloomer-armada/
 */
  
add_action( 'woocommerce_before_checkout_form', 'bbloomer_print_cart_weight' );
add_action( 'woocommerce_before_cart', 'bbloomer_print_cart_weight' );
  
function bbloomer_print_cart_weight() {
   $notice = 'Your cart weight is: ' . WC()->cart->get_cart_contents_weight() . get_option( 'woocommerce_weight_unit' );
   if ( is_cart() ) {
      wc_print_notice( $notice, 'notice' );
   } else {
      wc_add_notice( $notice, 'notice' );
   }
}