<?php

/**
 * Class MetaShopProduct.
 * Provide methods to extend original WC_Product API response.
 */
class MetaShopProduct {
    private $original_response;
    public $min_price;
    public $max_price;

    /**
     * MetaShopProduct constructor.
     * @param WC_Product $original_response
     */
    public function __construct($original_response) {
        require_once plugin_dir_path( __FILE__ ).'../attribute/class-metashop-attribute.php';
        $this->original_response = $original_response;

        if($original_response['type'] == 'variable') {
            $product = wc_get_product($original_response['id']);
            $this->min_price = $product->get_variation_price();
            $this->max_price = $product->get_variation_price('max');
        }
    }

    /**
     * Method to get custom product attributes formatted for the metashop store front.
     * @return null|array of MetaShopAttribute
     */
    public function get_custom_options() {
        if ($this->original_response['type'] === "variable") {
            return $new_attributes = array_map(fn($att) => new MetaShopAttribute($att['id'], $att['options']), $this->original_response['attributes']);
        } else {
            return null;
        }
    }

    /**
     * Method to get create short description based on full description if don't provided.
     * If a description exists, it will be truncated.
     * @return string
     */
    public function get_short_description() {
        if(!$this->original_response['short_description']) {
            return  $this->generate_short_description();
        } else {
            return $this->original_response['short_description'];
        }
    }

    /**
     * Method to generate short description based on full description.
     * html div and table tags are removed.
     * @return string
     */
    private function generate_short_description() {
        $product_description = $this->original_response['description'];
        $flags = PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY;
        $html_parse_regex = '/(<[a-z0-9=\-:." ^\/]+\/>)|(<[^\/]+>[^<\/]+<\/[a-z0-9]+>)|(<[a-z0-9=\-:." ^\/]+>)/';
        $exclude_parts_regex = '/<[\/]?(div|t([a-z]+))( ?(.)+]?)?>|(\n)|(\+\/-)/';
        $parts = preg_split( $html_parse_regex, $product_description, -1, $flags);
        $filtered_parts = array_values(array_filter($parts, fn($part) => !preg_match($exclude_parts_regex, $part)));
        return implode(' ', $filtered_parts);
    }

}