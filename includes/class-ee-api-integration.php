<?php

class EE_API_Integration {

    public function __construct() {
        add_filter('woocommerce_rest_prepare_product_object', array($this, 'enhance_product_api_response'), 10, 3);
        add_filter('woocommerce_rest_prepare_shop_order_object', array($this, 'enhance_order_api_response'), 10, 3);
    }

    public function enhance_product_api_response($response, $product, $request) {
        $product_id = $product->get_id();
        $response->data['event_data'] = array(
            'event_start_date' => $this->get_event_start_date($product_id),
            'event_end_date' => $this->get_event_end_date($product_id),
            'event_location' => $this->get_event_location($product_id),
            'categories' => $this->get_taxonomy_terms($product_id, 'product_cat'),
            'event_organizers' => $this->get_taxonomy_terms($product_id, 'event_organizer')
        );
        return $response;
    }

    public function enhance_order_api_response($response, $order, $request) {
        // Add wpml_language metadata to the response
        $order_id = $order->get_id();
        $response->data['wpml_language'] = get_post_meta($order_id, 'wpml_language', true);

        // Enhance line items with event data
        foreach ($response->data['line_items'] as &$item) {
            $product_id = $item['product_id'];
            $item['event_data'] = array(
                'event_start_date' => $this->get_event_start_date($product_id),
                'event_end_date' => $this->get_event_end_date($product_id),
                'event_location' => $this->get_event_location($product_id),
                'categories' => $this->get_taxonomy_terms($product_id, 'product_cat'),
                'event_organizers' => $this->get_taxonomy_terms($product_id, 'event_organizer')
            );
        }
        return $response;
    }

    private function get_event_start_date($product_id) {
        $start_date = get_post_meta($product_id, 'Event Start Date', true);
        return $start_date ? $start_date : '';
    }

    private function get_event_end_date($product_id) {
        $end_date = get_post_meta($product_id, 'Event End Date', true);
        return $end_date ? $end_date : '';
    }

    private function get_event_location($product_id) {
        $location = get_post_meta($product_id, 'Event Location', true);
        return $location ? $location : '';
    }

    private function get_taxonomy_terms($product_id, $taxonomy) {
        $terms = wp_get_post_terms($product_id, $taxonomy);
        $term_names = array();
        foreach ($terms as $term) {
            $term_names[] = $term->name;
        }
        return $term_names;
    }
}

new EE_API_Integration();