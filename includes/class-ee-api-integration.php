<?php

class EE_API_Integration {

    /**
     * Constructor: Hook into WooCommerce REST API actions and filters.
     */
    public function __construct() {
        add_filter('woocommerce_rest_prepare_product_object', [$this, 'add_event_data_to_product_response'], 10, 3);
        add_filter('woocommerce_rest_prepare_order_object', [$this, 'add_event_data_to_order_response'], 10, 3);
    }

    /**
     * Add event data to WooCommerce product API response.
     *
     * @param WP_REST_Response $response The product API response.
     * @param WC_Product       $product  The WooCommerce product object.
     * @param WP_REST_Request  $request  The API request object.
     * @return WP_REST_Response
     */
    public function add_event_data_to_product_response($response, $product, $request) {
        // Fetch custom meta fields
        $event_start_date = get_post_meta($product->get_id(), '_event_start_date', true);
        $event_end_date   = get_post_meta($product->get_id(), '_event_end_date', true);

        // Fetch taxonomy terms
        $event_location = wp_get_post_terms($product->get_id(), 'event_location', ['fields' => 'names']);
        $event_organizer = wp_get_post_terms($product->get_id(), 'event_organizer', ['fields' => 'names']);
        $product_categories = wp_get_post_terms($product->get_id(), 'product_cat', ['fields' => 'names']);

        // Add data to response
        $response->data['event_start_date'] = $event_start_date ?: null;
        $response->data['event_end_date']   = $event_end_date ?: null;
        $response->data['event_location']   = is_array($event_location) ? $event_location : [];
        $response->data['event_organizer']  = is_array($event_organizer) ? $event_organizer : [];
        $response->data['product_categories'] = is_array($product_categories) ? $product_categories : [];

        return $response;
    }

    /**
     * Add event data to WooCommerce order API response.
     *
     * @param WP_REST_Response $response The order API response.
     * @param WC_Order         $order    The WooCommerce order object.
     * @param WP_REST_Request  $request  The API request object.
     * @return WP_REST_Response
     */
    public function add_event_data_to_order_response($response, $order, $request) {
        foreach ($response->data['line_items'] as &$item) {
            $product_id = $item['product_id'];

            // Validate product_id
            if (!$product_id) {
                error_log('Missing product_id for order ID: ' . $order->get_id());
                continue;
            }

            // Fetch custom meta fields
            $event_start_date = get_post_meta($product_id, '_event_start_date', true);
            $event_end_date   = get_post_meta($product_id, '_event_end_date', true);

            // Fetch taxonomy terms
            $event_location = wp_get_post_terms($product_id, 'event_location', ['fields' => 'names']);
            $event_organizer = wp_get_post_terms($product_id, 'event_organizer', ['fields' => 'names']);
            $product_categories = wp_get_post_terms($product_id, 'product_cat', ['fields' => 'names']);

            // Debugging: Log taxonomy retrieval
            error_log('Order ID: ' . $order->get_id() . ' | Product ID: ' . $product_id);
            error_log('Event Start Date: ' . $event_start_date);
            error_log('Event End Date: ' . $event_end_date);
            error_log('Event Location: ' . print_r($event_location, true));
            error_log('Event Organizer: ' . print_r($event_organizer, true));
            error_log('Product Categories: ' . print_r($product_categories, true));

            // Fallback to meta_data for additional fields
            if (!empty($item['meta_data'])) {
                foreach ($item['meta_data'] as $meta) {
                    if ($meta['key'] === 'Event Start Date' && !$event_start_date) {
                        $event_start_date = $meta['value'];
                    }
                    if ($meta['key'] === 'Event End Date' && !$event_end_date) {
                        $event_end_date = $meta['value'];
                    }
                    if ($meta['key'] === 'Event Location' && empty($event_location)) {
                        $event_location = [$meta['value']];
                    }
                }
            }

            // Add event-related data to the line items in the order response
            $item['event_data'] = [
                'event_start_date'   => $event_start_date ?: null,
                'event_end_date'     => $event_end_date ?: null,
                'event_location'     => is_array($event_location) ? $event_location : [],
                'event_organizer'    => is_array($event_organizer) ? $event_organizer : [],
                'product_categories' => is_array($product_categories) ? $product_categories : [],
            ];
        }

        return $response;
    }
}

// Initialize the class
new EE_API_Integration();