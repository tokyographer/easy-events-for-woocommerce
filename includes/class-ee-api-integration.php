<?php

class EE_API_Integration {
    public function __construct() {
        // Hook to include event data and categories in product API responses
        add_filter( 'woocommerce_rest_prepare_product_object', [ $this, 'add_event_and_category_data_to_product_api' ], 10, 3 );

        // Hook to include product categories in order API responses
        add_filter( 'woocommerce_rest_prepare_shop_order_object', [ $this, 'add_event_and_category_data_to_order_api' ], 10, 3 );
    }

    /**
     * Add event data and product categories to WooCommerce REST API product responses.
     *
     * @param WP_REST_Response $response The original API response.
     * @param WC_Product $product The WooCommerce product object.
     * @param WP_REST_Request $request The current request object.
     * @return WP_REST_Response The modified API response.
     */
    public function add_event_and_category_data_to_product_api( $response, $product, $request ) {
        // Fetch event data
        $event_data = [
            'event_start_date' => $this->get_meta_data( $product->get_id(), '_event_start_date' ),
            'event_end_date'   => $this->get_meta_data( $product->get_id(), '_event_end_date' ),
            'event_location'   => $this->get_taxonomy_terms( $product->get_id(), 'event_location' ),
        ];

        // Fetch product categories
        $product_categories = $this->get_taxonomy_terms( $product->get_id(), 'product_cat' );

        // Debug: Log product categories
        error_log( 'Product API: Categories for Product ID ' . $product->get_id() . ': ' . print_r( $product_categories, true ) );

        // Add event data and categories to the API response
        $response->data['event_data'] = $event_data;
        $response->data['categories'] = $product_categories;

        return $response;
    }

    /**
     * Add product categories to WooCommerce REST API order line items.
     *
     * @param WP_REST_Response $response The original API response.
     * @param WC_Order $order The WooCommerce order object.
     * @param WP_REST_Request $request The current request object.
     * @return WP_REST_Response The modified API response.
     */
    public function add_event_and_category_data_to_order_api( $response, $order, $request ) {
        $line_items = $response->data['line_items'];

        // Iterate over line items and append product categories
        foreach ( $line_items as &$item ) {
            $product_id = $item['product_id'];

            // Fetch product categories
            $categories = $this->get_taxonomy_terms( $product_id, 'product_cat' );

            // Debugging
            error_log( 'Order API: Categories for Product ID ' . $product_id . ': ' . print_r( $categories, true ) );

            // Add categories to the line item
            $item['categories'] = ! empty( $categories ) ? $categories : [];
        }

        // Update the response with modified line items
        $response->data['line_items'] = $line_items;

        return $response;
    }

    /**
     * Retrieve metadata with validation.
     *
     * @param int $product_id The product ID.
     * @param string $meta_key The meta key to retrieve.
     * @return mixed|null The meta value, or null if not found.
     */
    private function get_meta_data( $product_id, $meta_key ) {
        $meta_value = get_post_meta( $product_id, $meta_key, true );
        return ! empty( $meta_value ) ? $meta_value : null;
    }

    /**
     * Retrieve taxonomy terms with error handling.
     *
     * @param int $product_id The product ID.
     * @param string $taxonomy The taxonomy slug.
     * @return array The list of taxonomy term names, or an empty array if none found.
     */
    private function get_taxonomy_terms( $product_id, $taxonomy ) {
        // Fetch terms for the specified taxonomy
        $terms = wp_get_post_terms( $product_id, $taxonomy, [ 'fields' => 'names' ] );

        if ( is_wp_error( $terms ) ) {
            error_log( 'Error retrieving terms for taxonomy ' . $taxonomy . ': ' . $terms->get_error_message() );
            return [];
        }

        return ! empty( $terms ) ? $terms : [];
    }
}

// Add Event Organizers taxonomy to WooCommerce REST API response
function ee_add_event_organizers_to_api($response, $product, $request) {
    // Get the event organizers terms for the product
    $terms = wp_get_post_terms($product->get_id(), 'event_organizer');
    
    if (!is_wp_error($terms) && !empty($terms)) {
        $response->data['event_organizers'] = array_map(function($term) {
            return [
                'id' => $term->term_id,
                'name' => $term->name,
                'slug' => $term->slug,
                'description' => $term->description,
            ];
        }, $terms);
    } else {
        $response->data['event_organizers'] = [];
    }

    return $response;
}

add_filter('woocommerce_rest_prepare_product_object', 'ee_add_event_organizers_to_api', 10, 3);
