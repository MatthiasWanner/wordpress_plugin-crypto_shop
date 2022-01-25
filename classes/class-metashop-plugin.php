<?php
class MetaShopPlugin {
    public function __construct() {
        require_once plugin_dir_path( __FILE__ ).'attribute/class-metashop-attribute.php';
        add_filter('woocommerce_rest_prepare_product_object', array($this, 'get_custom_product_options'), 10, 3);
        add_filter('woocommerce_rest_prepare_product_variation_object', array($this, 'get_custom_variations_properties'), 10, 3);
    }

    public function get_custom_product_options($response, $request){
        // Customize response data
        $product_attributes = $response->data['attributes'];
    
        if ($response->data['type'] === "variable") {
            $new_attributes = array_map(fn($att) => new MetaShopAttribute($att['id'], $att['options']), $product_attributes);
            $response->data['custom_options'] = $new_attributes;
        } else {
            $response->data['custom_options'] = null;
        }
    
        return $response;
    }

    public function get_custom_variations_properties($response, $request){
        
        $response->data['images'] = 'test';
      
        unset($response->data['image']);
        return $response;
    }
}