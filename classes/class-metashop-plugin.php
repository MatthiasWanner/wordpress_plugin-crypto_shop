<?php
class MetaShopPlugin {
    public function __construct() {
        require_once plugin_dir_path( __FILE__ ).'attribute/class-metashop-attribute.php';
        require_once plugin_dir_path( __FILE__ ).'product/class-metashop-product.php';
        require_once plugin_dir_path( __FILE__ ).'variation/class-metashop-variation.php';
        add_filter('woocommerce_rest_prepare_product_object', array($this, 'get_custom_product_properties'), 10, 3);
        add_filter('woocommerce_rest_prepare_product_variation_object', array($this, 'get_custom_variations_properties'), 10, 3);
    }

    public function get_custom_product_properties($response, $request){
        // Customize response data
        $new_product = new MetaShopProduct($response->data);
        $product_attributes = $response->data['attributes'];

        $response->data['short_description'] = $new_product->get_short_description();
    
        if ($response->data['type'] === "variable") {
            $new_attributes = array_map(fn($att) => new MetaShopAttribute($att['id'], $att['options']), $product_attributes);
            $response->data['custom_options'] = $new_attributes;
        } else {
            $response->data['custom_options'] = null;
        }
    
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