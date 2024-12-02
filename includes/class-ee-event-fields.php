<?php
class EE_Event_Fields {
    public function __construct() {
        // Hook to display event fields on the single product page.
        add_action( 'woocommerce_before_single_product_summary', [ $this, 'display_event_fields' ] );
    }

    /**
     * Display event fields: Start Date, End Date, and Location.
     */
    public function display_event_fields() {
        global $product;

        // Ensure the product is of type 'event'.
        if ( 'event' === $product->get_type() ) {
            $start_date = get_post_meta( $product->get_id(), '_event_start_date', true );
            $end_date   = get_post_meta( $product->get_id(), '_event_end_date', true );

            // Retrieve event location terms.
            $event_locations = wp_get_post_terms( $product->get_id(), 'event_location', ['fields' => 'names'] );

            // Display event fields.
            if ( $start_date ) {
                echo '<p>' . esc_html__( 'Start Date:', 'easy-events' ) . ' ' . esc_html( $start_date ) . '</p>';
            }

            if ( $end_date ) {
                echo '<p>' . esc_html__( 'End Date:', 'easy-events' ) . ' ' . esc_html( $end_date ) . '</p>';
            }

            if ( ! empty( $event_locations ) ) {
                echo '<p>' . esc_html__( 'Location:', 'easy-events' ) . ' ' . esc_html( implode( ', ', $event_locations ) ) . '</p>';
            } else {
                echo '<p>' . esc_html__( 'Location:', 'easy-events' ) . ' ' . esc_html__( 'No location assigned', 'easy-events' ) . '</p>';
            }
        }
    }
}