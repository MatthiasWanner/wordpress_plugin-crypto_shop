<?php

/**
 * Class MetaShopAttributeTerm
 * 
 * Instancied in an array of variable product's attribute terms.
 */
class MetaShopAttributeTerm {
    public int $id;
    public string $name;
    public string $slug;
    public $metas = null;
    private string $parented_attribute_name;

    /**
     * MetaShopAttributeTerm constructor
     * @param object $db_term_infos
     * @param string $parented_attribute_name
     */
    public function __construct($db_term_infos, string $parent_attribute_name) {

        require_once plugin_dir_path( __FILE__ ).'/class-metashop-term-metas.php';

        $this->id = $db_term_infos->term_id;
        $this->parented_attribute_name = $parent_attribute_name;
        $this->name = $db_term_infos->name;
        $this->slug = $db_term_infos->slug;
        $this->metas = $this->get_term_metas();
    }

    /**
     * Method to retrieve the term metas from the database
     * @return null|MetaShopTermMetas
     */
    private function get_term_metas() {
        $metas = new MetaShopTermMetas($this->id, $this->parented_attribute_name);

        if($metas->type === '-1') {
            return null;
        } else {
            return $metas;
        }
    }
}