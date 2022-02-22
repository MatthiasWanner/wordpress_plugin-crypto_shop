<?php

/**
 * Class instancied on /wc/v3/metashop-infos API endpoint
 * Used by MetaShopPlugin->get_shop_infos() method
 * @author Digital Copilote
 */
class MetashopInfos {
    public string $shop_name;
    public string $shop_description;
    public string $shop_logo;
    public string $shop_icon;
    public string $shop_notice;
    public string $shop_url;

    public function __construct() {
        $this->shop_name = get_bloginfo();
        $this->shop_description = get_bloginfo('description');
        $this->shop_logo = $this->get_site_logo_url();
        $this->shop_icon = get_site_icon_url();;
        $this->shop_notice = get_option('woocommerce_demo_store_notice');
        $this->shop_url = get_bloginfo('url');
    }

    /**
     * Method To get the site logo url directly
     *
     * @return string site logo's url
     */
    private function get_site_logo_url() {
        $custom_logo_id = get_theme_mod( 'custom_logo' );
        $image = wp_get_attachment_image_src( $custom_logo_id , 'full' );
        return $image[0];
    }
	
}