<?php

class EE_API_Integration {
    public function __construct() {
        // Hook to include event data and categories in product API responses
        add_filter('woocommerce_rest_prepare_product_object', [$this, 'add_event_and_category_data_to_product_api'], 10, 3);

        // Hook to include product categories and event organizers in order API responses
        add_filter('woocommerce_rest_prepare_shop_order_object', [$this, 'add_event_and_category_data_to_order_api'], 10, 3);

        // Hook to save event organizers as metadata for order items
        add_action('woocommerce_checkout_create_order_line_item', [$this, 'save_event_organizers_to_order_item'], 10, 4);
    }

    /**
     * Add event data and categories to product API response.
     */
    public function add_event_and_category_data_to_product_api($response, $product, $request) {
        $event_data = [
            'event_start_date' => esc_html($this->get_meta_data($product->get_id(), '_event_start_date')),
            'event_end_date'   => esc_html($this->get_meta_data($product->get_id(), '_event_end_date')),
            'event_location'   => $this->sanitize_array($this->get_taxonomy_terms($product->get_id(), 'event_location'))
        ];

        $categories = $this->sanitize_array($this->get_taxonomy_terms($product->get_id(), 'product_cat'));
        $organizers = $this->sanitize_array($this->get_taxonomy_terms($product->get_id(), 'event_organizer'));

        $response->data['event_data'] = $event_data;
        $response->data['categories'] = $categories;
        $response->data['event_organizers'] = $organizers;

        return $response;
    }

    /**
     * Add categories and event organizers to order API response.
     */
    public function add_event_and_category_data_to_order_api($response, $order, $request) {
        $line_items = $response->data['line_items'];

        foreach ($line_items as &$item) {
            $product_id = $item['product_id'];
            $categories = $this->sanitize_array($this->get_taxonomy_terms($product_id, 'product_cat'));
            $organizers = $this->sanitize_array($this->get_taxonomy_terms($product_id, 'event_organizer'));

            $item['categories'] = $categories;
            $item['event_organizers'] = $organizers;
        }

        $response->data['line_items'] = $line_items;
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
     * Get and sanitize metadata.
     */
    private function get_meta_data($product_id, $meta_key) {
        $meta_value = get_post_meta($product_id, $meta_key, true);
        return !empty($meta_value) ? sanitize_text_field($meta_value) : null;
    }

    /**
     * Retrieve taxonomy terms and handle errors.
     */
    private function get_taxonomy_terms($product_id, $taxonomy) {
        $terms = wp_get_post_terms($product_id, $taxonomy, ['fields' => 'names']);

        if (is_wp_error($terms)) {
            error_log('Error retrieving terms for taxonomy ' . esc_html($taxonomy) . ': ' . $terms->get_error_message());
            return [];
        }

        return !empty($terms) ? $terms : [];
    }

    /**
     * Sanitize an array of strings.
     */
    private function sanitize_array($array) {
        return array_map('sanitize_text_field', $array);
    }
}

// Add Event Organizers to Product API Response
function ee_add_event_organizers_to_api($response, $product, $request) {
    $terms = wp_get_post_terms($product->get_id(), 'event_organizer');

    if (!is_wp_error($terms) && !empty($terms)) {
        $response->data['event_organizers'] = array_map(function ($term) {
            return [
                'id' => intval($term->term_id),
                'name' => esc_html($term->name),
                'slug' => esc_html($term->slug),
                'description' => esc_html($term->description),
            ];
        }, $terms);
    } else {
        $response->data['event_organizers'] = [];
    }

    return $response;
}

add_filter('woocommerce_rest_prepare_product_object', 'ee_add_event_organizers_to_api', 10, 3);

// Initialize the class
new EE_API_Integration();
// Add custom taxonomies to WooCommerce REST API responses
add_filter('woocommerce_rest_prepare_product_object', 'add_custom_taxonomies_to_product_api', 10, 3);
function add_custom_taxonomies_to_product_api($response, $product, $request) {
    // Add event_location taxonomy data
    $event_locations = wp_get_post_terms($product->get_id(), 'event_location', ['fields' => 'names']);
    $response->data['event_location'] = !is_wp_error($event_locations) ? $event_locations : [];

    // Add event_organizers taxonomy data
    $event_organizers = wp_get_post_terms($product->get_id(), 'event_organizer', ['fields' => 'names']);
    $response->data['event_organizers'] = !is_wp_error($event_organizers) ? $event_organizers : [];

    // Add product_cat taxonomy data
    $product_categories = wp_get_post_terms($product->get_id(), 'product_cat', ['fields' => 'names']);
    $response->data['product_cat'] = !is_wp_error($product_categories) ? $product_categories : [];

    return $response;
}

// Save custom taxonomies during POST/PUT requests
add_action('woocommerce_rest_insert_product_object', 'save_custom_taxonomies_in_api', 10, 3);
function save_custom_taxonomies_in_api($product, $request, $creating) {
    if (isset($request['event_location'])) {
        wp_set_post_terms($product->get_id(), $request['event_location'], 'event_location');
    }
    if (isset($request['event_organizers'])) {
        wp_set_post_terms($product->get_id(), $request['event_organizers'], 'event_organizer');
    }
    if (isset($request['product_cat'])) {
        wp_set_post_terms($product->get_id(), $request['product_cat'], 'product_cat');
    }
}

// Add taxonomy schema to OPTIONS requests
add_filter('woocommerce_rest_product_schema', 'add_custom_taxonomy_schema_to_rest_api');
function add_custom_taxonomy_schema_to_rest_api($schema) {
    $schema['properties']['event_location'] = [
        'description' => __('Event locations assigned to the product.', 'text-domain'),
        'type'        => 'array',
        'items'       => [
            'type' => 'string',
        ],
        'context'     => ['view', 'edit'],
    ];
    $schema['properties']['event_organizers'] = [
        'description' => __('Event organizers assigned to the product.', 'text-domain'),
        'type'        => 'array',
        'items'       => [
            'type' => 'string',
        ],
        'context'     => ['view', 'edit'],
    ];
    $schema['properties']['product_cat'] = [
        'description' => __('Product categories assigned to the product.', 'text-domain'),
        'type'        => 'array',
        'items'       => [
            'type' => 'string',
        ],
        'context'     => ['view', 'edit'],
    ];
    return $schema;
}
