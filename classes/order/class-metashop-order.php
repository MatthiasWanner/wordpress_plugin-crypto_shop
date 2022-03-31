<?php

/**
 * Class MetaShopOrder.
 * Provide methods to extend original WC_Order API response.
 */
class MetaShopOrder 
{
    private $original_response;

    public function __construct($original_response) 
    {
        $this->original_response = $original_response;
    }

    /**
     * Recover the linked invoice id from the order comments
     * @return string representing the invoice id or null if not found
     */
    public function get_invoice_id()
    {
        $comments = $this->get_order_comments($this->original_response['id']);
        [$invoice_id_comment] = array_filter($comments, fn($i) => preg_match('/^Invoice ID/', $i));
        [$_, $invoice_id] = preg_split('/\n/', $invoice_id_comment);
        return $invoice_id;
    }

    /**
     * Recover the order comments (string content only)
     * @param  int $order_id The order id
     * @return array of all comments content
     */
    private function get_order_comments(int $order_id)
    {
        global $wpdb;

        $comments_table = $wpdb->prefix . 'comments';
        $results = $wpdb->get_results("
            SELECT comment_content
            FROM $comments_table
            WHERE  `comment_post_ID` = $order_id
            AND  `comment_type` LIKE  'order_note'
        ");
        wp_reset_postdata();
        
        $comments = array_map(fn($r) => $r->comment_content, $results);
        return $comments;
    }
}