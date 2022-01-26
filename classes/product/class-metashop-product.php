<?php

class MetaShopProduct {
    private $original_response;

    public function __construct($original_response) {
        require_once plugin_dir_path( __FILE__ ).'../attribute/class-metashop-attribute.php';
        $this->original_response = $original_response;
    }

    public function get_custom_options() {
        if ($this->original_response['type'] === "variable") {
            return $new_attributes = array_map(fn($att) => new MetaShopAttribute($att['id'], $att['options']), $this->original_response['attributes']);
        } else {
            return null;
        }
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
        $flags = PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY;
        $html_parse_regex = '/(<[a-z0-9=\-:." ^\/]+\/>)|(<[^\/]+>[^<\/]+<\/[a-z0-9]+>)|(<[a-z0-9=\-:." ^\/]+>)/';
        $exclude_parts_regex = '/<[\/]?(div|t([a-z]+))( ?(.)+]?)?>|(\n)|(\+\/-)/';
        $parts = preg_split( $html_parse_regex, $product_description, -1, $flags);
        $filtered_parts = array_values(array_filter($parts, fn($part) => !preg_match($exclude_parts_regex, $part)));
        return implode(' ', $filtered_parts);
    }

}