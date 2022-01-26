<?php

class MetaShopProduct {
    private $original_response;

    public function __construct($original_response) {
        $this->original_response = $original_response;
    }

    public function get_short_description() {
        if(!$this->original_response['short_description']) {
            return  $this->generate_short_description();
        } else {
            return $this->original_response['short_description'];
        }
    }

    private function generate_short_description() {
        $product_description = $this->original_response['description'];
        $short_description = "Tu n'as pas rempli de description courte pour ce produit 😞 ";
        return $short_description;
    }

}