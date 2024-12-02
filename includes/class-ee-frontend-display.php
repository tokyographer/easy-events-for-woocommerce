<?php

class EE_Frontend_Display {
    public function __construct() {
        // Hook to display event details only once on the single product page.
        add_action( 'woocommerce_single_product_summary', [ $this, 'display_event_details' ], 25 );
    }

    /**
     * Display event details on the product page.
     */
    public function display_event_details() {
        global $product;

        // Ensure the product is of type "event".
        if ( $product && 'event' === $product->get_type() ) {
            // Retrieve event details from meta and taxonomy.
            $start_date = get_post_meta( $product->get_id(), '_event_start_date', true );
            $end_date   = get_post_meta( $product->get_id(), '_event_end_date', true );
            // Retrieve location terms directly using taxonomy
            $locations = wp_get_post_terms( $product->get_id(), 'event_location', ['fields' => 'names'] ); 

            // Output the event details.
            echo '<div class="event-details">';
            if ( $start_date ) {
                echo '<p>' . __( 'Start Date:', 'easy-events' ) . ' ' . esc_html( $start_date ) . '</p>';
            }
            if ( $end_date ) {
                echo '<p>' . __( 'End Date:', 'easy-events' ) . ' ' . esc_html( $end_date ) . '</p>';
            }
            if ( ! empty( $locations ) ) { // Check if location terms exist
                echo '<p>' . __( 'Location:', 'easy-events' ) . ' ' . esc_html( implode( ', ', $locations ) ) . '</p>';
            } else {
                echo '<p>' . __( 'Location:', 'easy-events' ) . ' ' . __( 'No location assigned', 'easy-events' ) . '</p>';
            }
            echo '</div>';
        }
    }
}