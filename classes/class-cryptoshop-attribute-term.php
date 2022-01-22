<?php

class CryptoShopAttributeTerm {
    public int $id;
    public string $name;
    public string $slug;
    public CryptoShopTermMetas $metas;

    public function __construct($db_term_infos, string $parent_attribute_name) {

        require_once('class-cryptoshop-term-metas.php');

        $this->id = $db_term_infos->term_id;
        $this->name = $db_term_infos->name;
        $this->slug = $db_term_infos->slug;
        $this->metas = new CryptoShopTermMetas($this->id, $parent_attribute_name);
    }
}