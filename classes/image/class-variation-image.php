<?php
require_once plugin_dir_path( __FILE__ ).'class-product-image.php';

/**
 * Class VariationImage. 
 * 
 * Extends ProductImage.
 */
class VariationImage extends ProductImage {
    public function __construct($image_id, ?int $position = 0) {
        parent::__construct($image_id, $position);
    }
}