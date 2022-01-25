<?php

class MetaShopAttributeTerm {
    public int $id;
    public string $name;
    public string $slug;
    public MetaShopTermMetas $metas;

    public function __construct($db_term_infos, string $parent_attribute_name) {

        require_once plugin_dir_path( __FILE__ ).'/class-metashop-term-metas.php';

        $this->id = $db_term_infos->term_id;
        $this->name = $db_term_infos->name;
        $this->slug = $db_term_infos->slug;
        $this->metas = new MetaShopTermMetas($this->id, $parent_attribute_name);
    }
}