<?php

class EE_API_Integration {
    public function __construct() {
        // Hook to enhance product and order API responses
        add_filter('woocommerce_rest_prepare_product_object', [$this, 'enhance_product_api_response'], 10, 3);
        add_filter('woocommerce_rest_prepare_shop_order_object', [$this, 'enhance_order_api_response'], 10, 3);

        // Hook to save event organizers as metadata for order items
        add_action('woocommerce_checkout_create_order_line_item', [$this, 'save_event_organizers_to_order_item'], 10, 4);

        // Hook to add taxonomy schema to the REST API
        add_filter('woocommerce_rest_product_schema', [$this, 'add_custom_taxonomy_schema']);
    }

    /**
     * Enhance product API response with custom fields.
     */
    public function enhance_product_api_response($response, $product, $request) {
        $response->data['event_data'] = [
            'event_start_date' => esc_html($this->get_meta_data($product->get_id(), '_event_start_date')),
            'event_end_date'   => esc_html($this->get_meta_data($product->get_id(), '_event_end_date')),
            'event_location'   => $this->sanitize_array($this->get_taxonomy_terms($product->get_id(), 'event_location')),
        ];

        $response->data['categories'] = $this->sanitize_array($this->get_taxonomy_terms($product->get_id(), 'product_cat'));
        $response->data['event_organizers'] = $this->sanitize_array($this->get_taxonomy_terms($product->get_id(), 'event_organizer'));

        return $response;
    }

    /**
     * Enhance order API response with custom fields for line items.
     */
    public function enhance_order_api_response($response, $order, $request) {
        foreach ($response->data['line_items'] as &$item) {
            $product_id = $item['product_id'];
            $item['categories'] = $this->sanitize_array($this->get_taxonomy_terms($product_id, 'product_cat'));
            $item['event_organizers'] = $this->sanitize_array($this->get_taxonomy_terms($product_id, 'event_organizer'));
        }

        return $response;
    }

    /**
     * Save event organizers to order line item metadata.
     */
    public function save_event_organizers_to_order_item($item, $cart_item_key, $values, $order) {
        $product_id = $item->get_product_id();
        $organizers = $this->sanitize_array($this->get_taxonomy_terms($product_id, 'event_organizer'));

        if (!empty($organizers)) {
            $item->add_meta_data('event_organizers', $organizers);
        }
    }

    /**
     * Add custom taxonomy schema to WooCommerce REST API.
     */
    public function add_custom_taxonomy_schema($schema) {
        $schema['properties']['event_data'] = [
            'description' => __('Event-specific data such as start and end dates, and location.', 'text-domain'),
            'type'        => 'object',
            'properties'  => [
                'event_start_date' => ['type' => 'string', 'description' => __('Start date of the event.', 'text-domain')],
                'event_end_date'   => ['type' => 'string', 'description' => __('End date of the event.', 'text-domain')],
                'event_location'   => ['type' => 'array', 'items' => ['type' => 'string']],
            ],
            'context'     => ['view', 'edit'],
        ];

        $schema['properties']['categories'] = [
            'description' => __('Product categories.', 'text-domain'),
            'type'        => 'array',
            'items'       => ['type' => 'string'],
            'context'     => ['view', 'edit'],
        ];

        $schema['properties']['event_organizers'] = [
            'description' => __('Event organizers.', 'text-domain'),
            'type'        => 'array',
            'items'       => ['type' => 'string'],
            'context'     => ['view', 'edit'],
        ];

        return $schema;
    }

    /**
     * Retrieve sanitized metadata.
     */
    private function get_meta_data($product_id, $meta_key) {
        $meta_value = get_post_meta($product_id, $meta_key, true);
        return !empty($meta_value) ? sanitize_text_field($meta_value) : null;
    }

    /**
     * Retrieve and sanitize taxonomy terms.
     */
    private function get_taxonomy_terms($product_id, $taxonomy) {
        $terms = wp_get_post_terms($product_id, $taxonomy, ['fields' => 'names']);
        return is_wp_error($terms) ? [] : $terms;
    }

    /**
     * Sanitize an array of strings.
     */
    private function sanitize_array($array) {
        return array_map('sanitize_text_field', $array);
    }
}

// Initialize the integration class
new EE_API_Integration();

/**
 * Save custom taxonomies during API updates.
 */
add_action('woocommerce_rest_insert_product_object', function ($product, $request, $creating) {
    if (isset($request['event_location'])) {
        $validated_terms = array_map('sanitize_text_field', (array) $request['event_location']);
        wp_set_post_terms($product->get_id(), $validated_terms, 'event_location');
    }

    if (isset($request['event_organizers'])) {
        $validated_terms = array_map('sanitize_text_field', (array) $request['event_organizers']);
        wp_set_post_terms($product->get_id(), $validated_terms, 'event_organizer');
    }

    if (isset($request['product_cat'])) {
        $validated_terms = array_map('sanitize_text_field', (array) $request['product_cat']);
        wp_set_post_terms($product->get_id(), $validated_terms, 'product_cat');
    }
}, 10, 3);

/**
 * Add event attributes to products.
 */
function ee_add_event_attributes_to_products($response, $object, $request) {
    // Add Event Start Date
    $start_date = wc_get_product_terms($object->get_id(), 'pa_event_start_date', ['fields' => 'names']);
    if (!empty($start_date)) {
        $response->data['event_start_date'] = $start_date[0];
    } else {
        $response->data['event_start_date'] = null;
    }

    // Add Event End Date
    $end_date = wc_get_product_terms($object->get_id(), 'pa_event_end_date', ['fields' => 'names']);
    if (!empty($end_date)) {
        $response->data['event_end_date'] = $end_date[0];
    } else {
        $response->data['event_end_date'] = null;
    }

    return $response;
}
add_filter('woocommerce_rest_prepare_product_object', 'ee_add_event_attributes_to_products', 10, 3);

/**
 * Add event attributes to orders.
 */
function ee_add_event_attributes_to_orders($response, $object, $request) {
    $line_items = $response->data['line_items'];
    foreach ($line_items as &$item) {
        $product = wc_get_product($item['product_id']);
        if ($product) {
            // Add Event Start Date
            $start_date = wc_get_product_terms($product->get_id(), 'pa_event_start_date', ['fields' => 'names']);
            $item['event_start_date'] = !empty($start_date) ? $start_date[0] : null;

            // Add Event End Date
            $end_date = wc_get_product_terms($product->get_id(), 'pa_event_end_date', ['fields' => 'names']);
            $item['event_end_date'] = !empty($end_date) ? $end_date[0] : null;
        }
    }
    $response->data['line_items'] = $line_items;

    return $response;
}
add_filter('woocommerce_rest_prepare_order_object', 'ee_add_event_attributes_to_orders', 10, 3);