
<?php

class EE_WooCommerce_Hooks {
    public function __construct() {
        add_filter( 'woocommerce_add_cart_item_data', [ $this, 'add_event_data_to_cart' ], 10, 2 );
        add_filter( 'woocommerce_get_item_data', [ $this, 'display_event_data_in_cart' ], 10, 2 );
        add_action( 'woocommerce_checkout_create_order_line_item', [ $this, 'save_event_data_to_order' ], 10, 4 );
        add_filter( 'woocommerce_rest_prepare_product_object', [ $this, 'add_event_data_to_api' ], 10, 3 );
    }

    // Add sanitized event data to cart item.
    public function add_event_data_to_cart( $cart_item_data, $product_id ) {
        $cart_item_data['event_start_date'] = sanitize_text_field( get_post_meta( $product_id, '_event_start_date', true ) );
        $cart_item_data['event_end_date']   = sanitize_text_field( get_post_meta( $product_id, '_event_end_date', true ) );
        
        // Retrieve sanitized event location taxonomy terms.
        $event_locations = wp_get_post_terms( $product_id, 'event_location', ['fields' => 'names'] );
        if ( ! is_wp_error( $event_locations ) ) {
            $cart_item_data['event_locations'] = array_map( 'sanitize_text_field', $event_locations );
        }

        return $cart_item_data;
    }

    // Display escaped event data in the cart.
    public function display_event_data_in_cart( $item_data, $cart_item ) {
        if ( isset( $cart_item['event_start_date'] ) ) {
            $item_data[] = array(
                'name'  => __( 'Event Start Date', 'easy-events' ),
                'value' => esc_html( $cart_item['event_start_date'] ),
            );
        }

        if ( isset( $cart_item['event_end_date'] ) ) {
            $item_data[] = array(
                'name'  => __( 'Event End Date', 'easy-events' ),
                'value' => esc_html( $cart_item['event_end_date'] ),
            );
        }

        if ( isset( $cart_item['event_locations'] ) ) {
            $item_data[] = array(
                'name'  => __( 'Event Locations', 'easy-events' ),
                'value' => esc_html( implode( ', ', $cart_item['event_locations'] ) ),
            );
        }

        return $item_data;
    }

    // Save sanitized event data to order line item.
    public function save_event_data_to_order( $item, $cart_item_key, $values, $order ) {
        if ( isset( $values['event_start_date'] ) ) {
            $item->add_meta_data( '_event_start_date', sanitize_text_field( $values['event_start_date'] ), true );
        }

        if ( isset( $values['event_end_date'] ) ) {
            $item->add_meta_data( '_event_end_date', sanitize_text_field( $values['event_end_date'] ), true );
        }

        if ( isset( $values['event_locations'] ) ) {
            $item->add_meta_data( '_event_locations', array_map( 'sanitize_text_field', $values['event_locations'] ), true );
        }
    }

    // Add escaped event data to the REST API response.
    public function add_event_data_to_api( $response, $post, $request ) {
        $response->data['event_start_date'] = esc_html( get_post_meta( $post->ID, '_event_start_date', true ) );
        $response->data['event_end_date']   = esc_html( get_post_meta( $post->ID, '_event_end_date', true ) );

        $event_locations = wp_get_post_terms( $post->ID, 'event_location', [ 'fields' => 'names' ] );
        if ( ! is_wp_error( $event_locations ) ) {
            $response->data['event_locations'] = array_map( 'esc_html', $event_locations );
        }

        return $response;
    }
}
