<?php
class MetaShopPlugin {
    public function __construct() {
        require_once plugin_dir_path( __FILE__ ).'product/class-metashop-product.php';
        require_once plugin_dir_path( __FILE__ ).'variation/class-metashop-variation.php';
        add_filter('woocommerce_rest_prepare_product_object', array($this, 'get_custom_product_properties'), 10, 3);
        add_filter('woocommerce_rest_prepare_product_variation_object', array($this, 'get_custom_variations_properties'), 10, 3);
    }

    public function get_custom_product_properties($response, $request){
        // Customize response data
        $new_product = new MetaShopProduct($response->data);

        $response->data['custom_options'] = $new_product->get_custom_options();
        $response->data['short_description'] = $new_product->get_short_description();
    
        return $response;
    }

    public function get_custom_variations_properties($response, $request){

        $new_variation = new MetaShopVariation($response->data);

        $response->data['images'] = $new_variation->get_variation_images();
        
        $response->data['thumbnail'] = $new_variation->get_thumbnail();
      
        unset($response->data['image']);
        return $response;
    }
}