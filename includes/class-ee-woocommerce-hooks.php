<?php

class EE_WooCommerce_Hooks {
    public function __construct() {
        add_filter( 'woocommerce_add_cart_item_data', [ $this, 'add_event_data_to_cart' ], 10, 2 );
        add_filter( 'woocommerce_get_item_data', [ $this, 'display_event_data_in_cart' ], 10, 2 );
        add_action( 'woocommerce_checkout_create_order_line_item', [ $this, 'save_event_data_to_order' ], 10, 4 );
        add_filter( 'woocommerce_rest_prepare_product_object', [ $this, 'add_event_data_to_api' ], 10, 3 );
    }

    // Add event data to cart item.
    public function add_event_data_to_cart( $cart_item_data, $product_id ) {
        $cart_item_data['event_start_date'] = get_post_meta( $product_id, '_event_start_date', true );
        $cart_item_data['event_end_date']   = get_post_meta( $product_id, '_event_end_date', true );
    
        // Retrieve event location taxonomy terms.
        $event_locations = wp_get_post_terms( $product_id, 'event_location', ['fields' => 'names'] );
    
        if ( ! is_wp_error( $event_locations ) && ! empty( $event_locations ) ) {
            $cart_item_data['event_location'] = $event_locations;
        } else {
            $cart_item_data['event_location'] = [];
        }
    
        // Debugging: Log the cart item data to validate location.
        error_log( 'Cart Item Data: ' . print_r( $cart_item_data, true ) );
    
        return $cart_item_data;
    }

    // Display event data in cart and checkout.
    public function display_event_data_in_cart( $item_data, $cart_item ) {
        static $processed_items = []; // Prevent duplicates.

        $cart_item_key = md5( json_encode( $cart_item ) );
        if ( in_array( $cart_item_key, $processed_items ) ) {
            return $item_data;
        }
        $processed_items[] = $cart_item_key;

        if ( isset( $cart_item['event_start_date'] ) ) {
            $item_data[] = [
                'name'  => __( 'Event Start Date', 'easy-events' ),
                'value' => esc_html( $cart_item['event_start_date'] ),
            ];
        }

        if ( isset( $cart_item['event_end_date'] ) ) {
            $item_data[] = [
                'name'  => __( 'Event End Date', 'easy-events' ),
                'value' => esc_html( $cart_item['event_end_date'] ),
            ];
        }

        if ( isset( $cart_item['event_location'] ) && ! empty( $cart_item['event_location'] ) ) {
            $location = implode( ', ', $cart_item['event_location'] );
            $item_data[] = [
                'name'  => __( 'Event Location', 'easy-events' ),
                'value' => esc_html( $location ),
            ];
        }

        return $item_data;
    }

    // Save event data in order metadata.
    public function save_event_data_to_order( $item, $cart_item_key, $values, $order ) {
        static $processed_items = []; // Prevent duplicate processing.
    
        if ( in_array( $cart_item_key, $processed_items ) ) {
            return; // Skip if already processed.
        }
    
        $processed_items[] = $cart_item_key;
    
        if ( isset( $values['event_start_date'] ) ) {
            $item->add_meta_data( __( 'Event Start Date', 'easy-events' ), $values['event_start_date'] );
        }
    
        if ( isset( $values['event_end_date'] ) ) {
            $item->add_meta_data( __( 'Event End Date', 'easy-events' ), $values['event_end_date'] );
        }
    
        if ( isset( $values['event_location'] ) && ! empty( $values['event_location'] ) ) {
            $item->add_meta_data( __( 'Event Location', 'easy-events' ), implode( ', ', $values['event_location'] ) );
        }
    }

    // Add event data to API response.
    public function add_event_data_to_api( $response, $product, $request ) {
        $event_data = [
            'event_start_date' => get_post_meta( $product->get_id(), '_event_start_date', true ),
            'event_end_date'   => get_post_meta( $product->get_id(), '_event_end_date', true ),
            'event_location'   => wp_get_post_terms( $product->get_id(), 'event_location', ['fields' => 'names'] ),
        ];

        // Debugging.
        error_log( 'API Event Data: ' . print_r( $event_data, true ) );

        $response->data['event_data'] = $event_data;
        return $response;
    }

    // Helper function to retrieve event location taxonomy terms.
    private function get_event_location_terms( $product_id ) {
        $event_locations = wp_get_post_terms( $product_id, 'event_location', ['fields' => 'names'] );

        if ( is_wp_error( $event_locations ) ) {
            error_log( 'Error retrieving event locations: ' . $event_locations->get_error_message() );
            return [];
        }

        return ! empty( $event_locations ) ? $event_locations : [];
    }
}

new EE_WooCommerce_Hooks();