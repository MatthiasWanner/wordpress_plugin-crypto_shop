<?php
/**
 * Class ProductImage. 
 * 
 * Used to retrieve the product's image infos used by store front.
 */
class ProductImage {
    public int $id;
    public string $src;
    public string $name;
    public string $alt;
    public int $position;

    /**
     * ProductImage constructor
     * @param int $image_id
     * @param int $position (optionnal). Default 0
     */
    public function __construct(int $id, ?int $position = 0) {
        $this->id = $id;
        $this->alt = "product-image-{$id}";
        $this->get_media_infos($id);
        $this->position = $position;
    }

    /**
     * Method to get the image infos from the database
     * @param int $image_id
     */
    private function get_media_infos(int $post_id) {
        $medias_infos = get_post($post_id);

        $this->src = $medias_infos->guid;
        $this->name = $medias_infos->post_name;
    }

    /**
     * Method to get the image html code
     * @return string
     */
    public function get_thumbnail_html() {
        return wp_get_attachment_image($this->id, 'woocommerce_thumbnail');
    }

    /**
     * Method to get the image url directly in woocommerce thumbnail format
     * @return string
     */
    public function get_thumbnail() {
        $this->src = wp_get_attachment_image_url($this->id, 'woocommerce_thumbnail');
    }
}