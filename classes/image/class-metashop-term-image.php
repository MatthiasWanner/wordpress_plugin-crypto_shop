<?php

/**
 * Class MetaShopTermImage. 
 * Manage custom metas image of a WC_Product class attribute's terms.
 */
class MetaShopTermImage {
    public string $id;
    public string $url;
    public string $name;

    /**
     * MetaShopTermImage constructor
     * @param int $id
     */
    public function __construct(int $image_id) {
        $this->id = $image_id;
        $image_infos = $this->get_db_image_infos();
        $this->url = $image_infos->guid;
        $this->name = $image_infos->post_name;
    }

    /**
     * Method to get the image infos from the database
     * @return object
     */
    private function get_db_image_infos() {
        global $wpdb;
        [$image_db_infos] = $wpdb->get_results( "
                                                SELECT guid, post_name
                                                FROM {$wpdb->prefix}posts
                                                WHERE ID = $this->id
                                                "
                                            );
        wp_reset_postdata();
        return $image_db_infos;
    }
}