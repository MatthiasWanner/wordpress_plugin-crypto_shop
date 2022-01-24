<?php
class CryptoShopPlugin {
    public function __construct() {
        require_once('class-cryptoshop-attribute.php');
        add_filter('woocommerce_rest_prepare_product_object', array($this, 'get_custom_options'), 10, 3);
    }

    public function get_custom_options($response, $request){
        // Customize response data
        $product_attributes = $response->data['attributes'];
    
        if ($response->data['type'] === "variable") {
            $new_attributes = array_map(fn($att) => new CryptoShopAttribute($att['id'], $att['options']), $product_attributes);
            $response->data['custom_options'] = $new_attributes;
        } else {
            $response->data['custom_options'] = null;
        }
    
        return $response;
    }
}