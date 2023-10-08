<?php
/**
 * Set product price to 0 if empty
 */
function gpw_set_empty_price_to_zero( $price, $product ) {
    if ( empty( $price ) ) {
        $price = 0;
    }
    return $price;
}
add_filter( 'woocommerce_product_get_price', 'gpw_set_empty_price_to_zero', 10, 2 );
add_filter( 'woocommerce_product_variation_get_price', 'gpw_set_empty_price_to_zero', 10, 2 );

/**
 * Set variation price to 0 if empty
 */
function gpw_set_empty_variation_price_to_zero( $price, $variation, $product ) {
    if ( empty( $price ) ) {
        // Set price to 0
        $price = 0;

        // Set price to all attributes in the variation
        foreach ( $variation->get_variation_attributes() as $attribute_name => $attribute_value ) {
            $variation->set_price( $price, $attribute_name );
        }
    }
    return $price;
}
add_filter( 'woocommerce_variation_prices_price', 'gpw_set_empty_variation_price_to_zero', 10, 3 );