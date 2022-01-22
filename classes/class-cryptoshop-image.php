<?php
class CryptoShopImage {
    public string $id;
    public string $url;
    public string $name;

    public function __construct(int $image_id) {
        $this->id = $image_id;
        $image_infos = $this->get_db_image_infos();
        $this->url = $image_infos->guid;
        $this->name = $image_infos->post_name;
    }

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