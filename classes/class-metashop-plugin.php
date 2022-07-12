<?php

/**
 * Class MetaShopPlugin
 */
class MetaShopPlugin {
    public function __construct() {
        require_once plugin_dir_path( __FILE__ ).'product/class-metashop-product.php';
        require_once plugin_dir_path( __FILE__ ).'variation/class-metashop-variation.php';
        require_once plugin_dir_path( __FILE__ ).'api/class-metashop-infos.php';
        require_once plugin_dir_path( __FILE__ ).'order/class-metashop-order.php';
        add_filter('woocommerce_rest_prepare_product_object', array($this, 'get_custom_product_properties'), 10, 3);
        add_filter('woocommerce_rest_prepare_product_variation_object', array($this, 'get_custom_variations_properties'), 10, 3);
        add_action( 'rest_api_init', function () {
            register_rest_route( 'wc/v3', 'metashop-infos', array(
                'methods' => 'GET',
                'callback' => array($this, 'get_shop_infos'),
            ));
        });
        add_filter( "woocommerce_rest_prepare_shop_order_object", array( $this, "get_customized_product_order"), 10, 3 );
    }

    /** 
     * Method that mutate the product variations properties
     * @param  WC_Product $response The response object
     * @param  WP_REST_Request $request The request object
     * @return WC_Product overwritten => Add custom_options + replace initial short_description keys
    */
    public function get_custom_product_properties($response, $request){
        // Customize response data
        $new_product = new MetaShopProduct($response->data);

        $response->data['custom_options'] = $new_product->get_custom_options();
        $response->data['short_description'] = $new_product->get_short_description();
        $response->data['min_price'] = $new_product->min_price;
        $response->data['max_price'] = $new_product->max_price;
    
        return $response;
    }

    /** 
     * Method that mutate the product variations properties
     * @param  WC_Product_Variable $response The response object
     * @param  WP_REST_Request $request The request object
     * @return WC_Product_Variable overwritten => replace image by images key. Add thumbnail key
    */
    public function get_custom_variations_properties($response, $request){

        $new_variation = new MetaShopVariation($response->data);

        $response->data['images'] = $new_variation->get_variation_images();
        
        $response->data['thumbnail'] = $new_variation->get_thumbnail();
      
        unset($response->data['image']);
        return $response;
    }

    /** 
     * Method called on /wc/v3/metashop-infos API endpoint
     * @return MetaShopInfos instance
    */
    public function get_shop_infos() {
        return new MetashopInfos();
    }

    /**
     * Method called on on the order namespace
     * @param  WC_Order $response The response object
     * @param  WP_REST_Request $request The request object
     * @return WC_Order overwritten => Add invoice_id key
     */
    public function get_customized_product_order( $response, $request) {
        $new_order = new MetaShopOrder($response->data);
        $response->data['invoice_id'] = $new_order->get_invoice_id();
        return $response;
    }
}