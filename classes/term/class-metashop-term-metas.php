<?php

/**
 * Class MetaShopTermMetas.
 * 
 * Retrieve Custome term metas set in database. Using WooCommerce Variation Swatches and Photos plugin.
 */
class MetaShopTermMetas {
    public ?string $type = null; // TODO: search for precise type 'color' | 'photo'
    public ?string $color = null;
    public $image = null; // In reality "string" | MetaShopTermImage

    /**
     * MetaShopTermMetas constructor
     * @param int $term_id
     * @param string $parented_attribute_name
     */
    public function __construct(int $term_id, $parent_attribute_name) {
        require_once plugin_dir_path( __FILE__ ).'/class-term-db-infos.php';
        require_once plugin_dir_path( __FILE__ ).'../image/class-metashop-term-image.php';

        $db_term_metas = $this->get_db_term_metas($term_id);

        $this->assign_metas_infos($db_term_metas, $parent_attribute_name);
        $this->get_image_infos_if_necessary();
    }

    /**
     * Method to retrieve the term metas from the database
     * @param int $term_id
     * @return array of term metas
     */
    private function get_db_term_metas(int $term_id) {
        global $wpdb;
        $term_metas_from_db =  $wpdb->get_results( "
                                            SELECT meta_key, meta_value
                                            FROM {$wpdb->prefix}termmeta
                                            WHERE term_id = $term_id
                                            "
                                        );
        wp_reset_postdata();
        return $term_metas_from_db; 
    }

    /**
     * Method to assign the metas infos to the object
     * @param array $term_metas_from_db
     * @param string $attribute_name . Parent attribute name
     */
    private function assign_metas_infos(array $term_metas_from_db, string $attribute_name) {
        foreach ($term_metas_from_db as $term_meta) {
            switch ($term_meta->meta_key) {
                case "pa_{$attribute_name}_swatches_id_type":
                    $this->type = $term_meta->meta_value;
                    break;
                case "pa_{$attribute_name}_swatches_id_color":
                    $this->color = $term_meta->meta_value;
                    break;
                case "pa_{$attribute_name}_swatches_id_photo":
                    $this->image = $term_meta->meta_value;
                    break;
                default:
                    break;
            }
        }
    }

    /**
     * Method to retrieve and assign the image infos if necessary
     */
    private function get_image_infos_if_necessary() {
        if ($this->type == 'photo') {
            $image_infos = new MetaShopTermImage($this->image);
            $this->image = $image_infos;   
        } 
    }

} 