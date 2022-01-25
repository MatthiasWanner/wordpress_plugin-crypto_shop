<?php

class ProductImage {
    public int $id;
    public string $src;
    public string $name;
    public string $alt;
    public int $position;

    public function __construct(int $id, ?int $position = 0) {
        $this->id = $id;
        $this->alt = "product-image-{$id}";
        $this->get_media_infos($id);
        $this->position = $position;
    }

    private function get_media_infos(int $post_id) {
        $medias_infos = get_post($post_id);

        $this->src = $medias_infos->guid;
        $this->name = $medias_infos->post_name;
    }

    public function get_thumbnail_html() {
        return wp_get_attachment_image($this->id, 'woocommerce_thumbnail');
    }

    public function get_thumbnail() {
        $this->src = wp_get_attachment_image_url($this->id, 'woocommerce_thumbnail');
    }
}