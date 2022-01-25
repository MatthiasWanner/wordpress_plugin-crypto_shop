<?php
class MetaShopVariation {
    private $data;

    public function __construct($original_data) {

        require_once plugin_dir_path( __FILE__ ).'/../image/class-variation-image.php';
        $this->data = $original_data;
    }

    public function get_variation_images() {
        $images = [];
        $main_image_id = $this->data['image']['id'];

        if($main_image_id) {
            array_push($images, new VariationImage($main_image_id, 1));
        }

        [$strg_additionnal_image_ids] = get_post_meta($this->data['id'], '_wc_additional_variation_images');
        
        if(!empty($strg_additionnal_image_ids)){
            $additionnal_image_ids = explode(',', $strg_additionnal_image_ids);

            foreach ($additionnal_image_ids as $key=>$image_id){
                array_push($images, new VariationImage($image_id, $key + 2));
            }
        }
        return $images;
    }

    public function get_thumbnail() {
        $main_variation_image_id = $this->data['image']['id'];

        if($main_variation_image_id) {
            $main_variation_image = new VariationImage($main_variation_image_id);
            $main_variation_image->get_thumbnail();
        }
        return $main_variation_image;
    }
}