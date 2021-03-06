<?php

/**
 * Class MetaShopAttribute.
 * 
 * Provide product attribute infos with related terms and metas
 */
class MetaShopAttribute {

    public int $id;
    public string $name;
    public $label;
    public array $terms; // have to type in array of term objects
    
    /**
     * MetaShopAttribute constructor
     * @param int $attribute_id
     * @param array $options to filter results according to product options
     */
    public function __construct(int $attribute_id, ?array $options)
    {
        require_once plugin_dir_path( __FILE__ ).'/class-attribute-db-infos.php';
        require_once plugin_dir_path( __FILE__ ).'../term/class-metashop-attribute-term.php';

        $wc_attribute_taxonomy = new AttributeDbInfos($attribute_id);
        $this->id = $wc_attribute_taxonomy->id;
        $this->name = $wc_attribute_taxonomy->name;
        $this->label = $wc_attribute_taxonomy->label;
        $this->terms = $this->get_terms($options);
    }

    /**
     * Method to retrieve attribute's terms ids from the database
     * 
     * @return array of numbers
     */
    private function get_term_ids() {
        global $wpdb;
        $pa_taxonomy = "pa_{$this->name}";
        $wc_attribute_terms_ids_from_db = $wpdb->get_results( "
                                                SELECT term_id 
                                                FROM {$wpdb->prefix}term_taxonomy
                                                WHERE taxonomy = '$pa_taxonomy'
                                                "
                                        );
        wp_reset_postdata();

        return array_map(fn($result) => $result->term_id, $wc_attribute_terms_ids_from_db);
    }

    /**
     * Method to retrieve terms infos from db
     * 
     * @return array of term objects
     */
    private function get_terms_from_db() {
        $strg_terms_ids = implode(",", $this->get_term_ids());
        global $wpdb;
        $wc_attribute_terms_from_db = $wpdb->get_results( "
                                                SELECT *
                                                FROM {$wpdb->prefix}terms
                                                WHERE term_id IN ($strg_terms_ids)
                                                "
                                        );
        wp_reset_postdata();
        return $wc_attribute_terms_from_db;
    }

    /**
     * Method to get formated attribute terms
     * 
     * @return array of MetaShopAttributeTerm objects
     */
    public function get_terms(?array $filter_options) {
        
        $wc_attribute_terms_from_db = !empty($filter_options) ? 
                                        array_filter($this->get_terms_from_db(), fn($db_term) => in_array($db_term->name, $filter_options))
                                        : 
                                        $this->get_terms_from_db();
        
        return array_values(array_map(fn($term) => new MetaShopAttributeTerm($term, $this->name), $wc_attribute_terms_from_db));
    }
}