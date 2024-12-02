<?php

class EE_API_Integration {
    public function __construct() {
        // Hook the API function to include event data and categories in API responses
        add_filter( 'woocommerce_rest_prepare_product_object', [ $this, 'add_event_and_category_data_to_api' ], 10, 3 );
    }

    /**
     * Add event data and product categories to WooCommerce REST API responses.
     *
     * @param WP_REST_Response $response The original API response.
     * @param WC_Product $product The WooCommerce product object.
     * @param WP_REST_Request $request The current request object.
     * @return WP_REST_Response The modified API response.
     */
    public function add_event_and_category_data_to_api( $response, $product, $request ) {
        // Initialize the event data array
        $event_data = [
            'event_start_date' => $this->get_meta_data( $product->get_id(), '_event_start_date' ),
            'event_end_date'   => $this->get_meta_data( $product->get_id(), '_event_end_date' ),
            'event_location'   => $this->get_taxonomy_terms( $product->get_id(), 'event_location' ),
        ];

        // Fetch product categories and add them at the root level of the response
        $product_categories = $this->get_taxonomy_terms( $product->get_id(), 'product_cat' );

        // Add event data to the API response
        $response->data['event_data'] = $event_data;

        // Log category debugging info
    error_log( 'Product Categories for Product ID ' . $product->get_id() . ': ' . print_r( $product_categories, true ) );


        // Add product categories to the API response
        $response->data['categories'] = $product_categories;

        // Debugging: Log the full response data for testing
        error_log( 'API Response for Product ID ' . $product->get_id() . ': ' . print_r( $response->data, true ) );

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
        $terms = wp_get_post_terms( $product_id, $taxonomy, ['fields' => 'names'] );

        if ( is_wp_error( $terms ) ) {
            error_log( 'Error retrieving terms for taxonomy ' . $taxonomy . ': ' . $terms->get_error_message() );
            return [];
        }

        return ! empty( $terms ) ? $terms : [];
    }
}