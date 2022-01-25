<?php

class ProductImage {
    public int $id;
    public string $src;
    public string $name;
    public string $alt;

    public function __construct(int $id) {
        $this->id = $id;
        $this->src = wp_get_attachment_url($id);
    }
}