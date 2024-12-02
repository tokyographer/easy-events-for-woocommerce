<?php

class EE_Cart_Checkout {
    public function __construct() {
        add_action( 'woocommerce_cart_item_name', [ $this, 'display_event_data_on_cart' ], 10, 2 );
        add_action( 'woocommerce_checkout_cart_item_quantity', [ $this, 'display_event_data_on_cart' ], 10, 2 );
    }

    // Display event data on cart and checkout.
    public function display_event_data_on_cart( $item_name, $cart_item ) {
    static $processed_items = []; // Prevent duplication for the same cart item.

    if ( isset( $processed_items[ $cart_item['product_id'] ] ) ) {
        return $item_name; // Skip if already processed.
    }

    $processed_items[ $cart_item['product_id'] ] = true;

    if ( isset( $cart_item['event_start_date'] ) ) {
        $item_name .= '<p>' . __( 'Event Start Date', 'easy-events' ) . ': ' . esc_html( $cart_item['event_start_date'] ) . '</p>';
    }

    if ( isset( $cart_item['event_end_date'] ) ) {
        $item_name .= '<p>' . __( 'Event End Date', 'easy-events' ) . ': ' . esc_html( $cart_item['event_end_date'] ) . '</p>';
    }

    if ( isset( $cart_item['event_location'] ) && ! empty( $cart_item['event_location'] ) ) {
        $location = implode( ', ', $cart_item['event_location'] );
        $item_name .= '<p>' . __( 'Event Location', 'easy-events' ) . ': ' . esc_html( $location ) . '</p>';
    }

    return $item_name;
}
}

new EE_Cart_Checkout();