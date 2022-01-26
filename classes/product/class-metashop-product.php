<?php

class MetaShopProduct {
    private $original_response;

    public function __construct($original_response) {
        $this->original_response = $original_response;
    }

    public function get_short_description() {
        if(!$this->original_response['short_description']) {
            return "Il faut remplir la short_description";
        } else {
            return $this->original_response['short_description'];
        }
    }

}