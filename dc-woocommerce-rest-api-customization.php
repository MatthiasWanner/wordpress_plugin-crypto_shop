<?php /* 
Plugin Name: Digital Copilote Woocommerce REST API Customization
Plugin URI:  
Description: Woocommerce REST API response customization
Version: 1.0 
Author: Matthias Wanner
Author URI: https://github.com/MatthiasWanner
License: GPLv2 or later */ 

add_filter('woocommerce_rest_prepare_product_object', 'get_custom_attributes', 10, 3);

function convert_db_term_into_object($attribute_term, $attribute_name) {
    global $wpdb;
    $term_metas_from_db =  $wpdb->get_results( "
                                            SELECT meta_key, meta_value
                                            FROM {$wpdb->prefix}termmeta
                                            WHERE term_id = $attribute_term->term_id
                                            "
                                        );

    $term_metas = new stdClass();
    foreach($term_metas_from_db as $term_meta_from_db) {
        switch($term_meta_from_db){
            case ($term_meta_from_db->meta_key == "pa_{$attribute_name}_swatches_id_type"):
                $term_metas->type = $term_meta_from_db->meta_value;
                break;
            case ($term_meta_from_db->meta_key == "pa_{$attribute_name}_swatches_id_color"):
                $term_metas->color = $term_meta_from_db->meta_value;
                break;
            case ($term_meta_from_db->meta_key == "pa_{$attribute_name}_swatches_id_photo"):
                $imageId = $term_meta_from_db->meta_value;
                $term_metas->image = $imageId;
                break;
            default:
                break;
        }
    }

    if ($term_metas->type == 'photo') {
        $ims_datas_from_db =  $wpdb->get_results( "
                                            SELECT guid, post_name
                                            FROM {$wpdb->prefix}posts
                                            WHERE ID = $imageId
                                            "
                                        );
        
        $term_image_metas = new stdClass();
        $term_image_metas->id = $imageId;
        $term_image_metas->url = $ims_datas_from_db[0]->guid;
        $term_image_metas->name = $ims_datas_from_db[0]->post_name;
        $term_metas->image = $term_image_metas;
    } 

    wp_reset_postdata();

    $attribute_term_object = new stdClass();
    $attribute_term_object->id = $attribute_term->term_id;
    $attribute_term_object->name = $attribute_term->name;
    $attribute_term_object->slug = $attribute_term->slug;
    $attribute_term_object->metas = $term_metas->type == -1 ? null : $term_metas;
    return $attribute_term_object;
}

function get_attribute_terms($attribute_name, $filter) {
    global $wpdb;
    $pa_taxonomy = "pa_{$attribute_name}";
    $wc_attribute_terms_ids_from_db = $wpdb->get_results( "
                                            SELECT term_id 
                                            FROM {$wpdb->prefix}term_taxonomy
                                            WHERE taxonomy = '$pa_taxonomy'
                                            "
                                    );
    wp_reset_postdata();

    $term_ids = array_map(fn($result) => $result->term_id, $wc_attribute_terms_ids_from_db);
    $strg_terms_ids = implode(",", $term_ids);

    $wc_attribute_terms_from_db = $wpdb->get_results( "
                                            SELECT *
                                            FROM {$wpdb->prefix}terms
                                            WHERE term_id IN ($strg_terms_ids)
                                            "
                                    );
    wp_reset_postdata();

    if (!empty($filter)) {
        $wc_attribute_terms_from_db = array_filter($wc_attribute_terms_from_db, fn($term) => in_array($term->name, $filter));
    }
    
    $attribute_terms = array_map(fn($arr) => convert_db_term_into_object($arr, $attribute_name), $wc_attribute_terms_from_db);
    
    return array_values($attribute_terms);
};

function get_attribute_infos($attribute) {
    global $wpdb;
    $id = $attribute['id'];
    $wc_attribute_taxonomy = $wpdb->get_results( "
                                            SELECT attribute_label, attribute_name, attribute_id
                                            FROM {$wpdb->prefix}woocommerce_attribute_taxonomies
                                            WHERE attribute_id = $id
                                            "
                                    );
    wp_reset_postdata();

    $attribute_infos = new stdClass();
    $attribute_infos->id = $wc_attribute_taxonomy[0]->attribute_id;
    $attribute_infos->name = $wc_attribute_taxonomy[0]->attribute_name;
    $attribute_infos->label = $wc_attribute_taxonomy[0]->attribute_label;
    $attribute_infos->terms = get_attribute_terms($wc_attribute_taxonomy[0]->attribute_name, $attribute['options']);
    return $attribute_infos;
}

function get_custom_attributes($response, $request){
    // Customize response data
    $product_attributes = $response->data['attributes'];

    if ($response->data["type"] === "variable") {
        $new_attributes = array_map('get_attribute_infos', $product_attributes);
        $response->data["custom_options"] = $new_attributes;
    } else {
        $response->data["custom_options"] = null;
    }

    return $response;
};