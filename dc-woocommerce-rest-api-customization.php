<?php /* 
Plugin Name: Digital Copilote Woocommerce REST API Customization
Plugin URI:  
Description: Woocommerce REST API response customization
Version: 1.0 
Author: Matthias Wanner
Author URI: https://github.com/MatthiasWanner
License: GPLv2 or later */ 

add_filter('woocommerce_rest_prepare_product_object', 'get_custom_attributes', 10, 3);

function get_custom_attributes($data, $product, $request) {
    $data->data['custom'] = "test";
    return $data;
}