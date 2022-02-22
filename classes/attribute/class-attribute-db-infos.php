<?php

/**
 * Class AttributeDbInfos.
 * 
 * Used to retrieve the attribute infos from the database.
 */
class AttributeDbInfos {
    public int $id;
    public string $name;
    public string $label;

    /**
     * AttributeDbInfos constructor
     * @param int $attribute_id
     */
    public function __construct(int $attribute_id) {
        $wc_attribute_taxonomy = $this->get_attribute_infos_from_db($attribute_id);
        $this->id = $wc_attribute_taxonomy->attribute_id;
        $this->name = $wc_attribute_taxonomy->attribute_name;
        $this->label = $wc_attribute_taxonomy->attribute_label;
    }

    /**
     * Method to retrieve the attribute infos from the database
     * @param int $attribute_id
     * @return object
     */
    private function get_attribute_infos_from_db(int $attribute_id) {
        global $wpdb;
        [$wc_attribute_taxonomy_infos] = $wpdb->get_results( "
                                                SELECT attribute_label, attribute_name, attribute_id
                                                FROM {$wpdb->prefix}woocommerce_attribute_taxonomies
                                                WHERE attribute_id = $attribute_id
                                                "
                                        );
        wp_reset_postdata();
        return $wc_attribute_taxonomy_infos;
    }

}