<?php

class EE_Event_Product_Type {
    public function __construct() {
        add_filter( 'product_type_selector', [ $this, 'add_event_product_type' ] );
        add_action( 'woocommerce_product_options_general_product_data', [ $this, 'add_event_fields' ] );
        add_action( 'woocommerce_process_product_meta', [ $this, 'save_event_fields' ] );
    }

    public function add_event_product_type( $types ) {
        $types['event'] = __( 'Event', 'easy-events' );
        return $types;
    }

    public function add_event_fields() {
        global $post;

        echo '<div class="options_group">';

        woocommerce_wp_text_input( [
            'id'          => '_event_start_date',
            'label'       => __( 'Event Start Date', 'easy-events' ),
            'type'        => 'date',
            'description' => __( 'Enter the start date of the event.', 'easy-events' ),
            'desc_tip'    => true,
        ] );

        woocommerce_wp_text_input( [
            'id'          => '_event_end_date',
            'label'       => __( 'Event End Date', 'easy-events' ),
            'type'        => 'date',
            'description' => __( 'Enter the end date of the event.', 'easy-events' ),
            'desc_tip'    => true,
        ] );

        woocommerce_wp_select( [
            'id'      => '_event_location',
            'label'   => __( 'Event Location', 'easy-events' ),
            'options' => $this->get_event_locations(),
            'value'   => $this->get_selected_location( $post->ID ), // Use helper function to get selected location
            'description' => __( 'Select the location of the event.', 'easy-events' ),
        ] );

        echo '</div>';
    }

    public function save_event_fields( $post_id ) {
        if ( isset( $_POST['_event_start_date'] ) ) {
            update_post_meta( $post_id, '_event_start_date', sanitize_text_field( $_POST['_event_start_date'] ) );
        }

        if ( isset( $_POST['_event_end_date'] ) ) {
            update_post_meta( $post_id, '_event_end_date', sanitize_text_field( $_POST['_event_end_date'] ) );
        }

        if ( isset( $_POST['_event_location'] ) ) {
            $location_slug = sanitize_text_field( $_POST['_event_location'] );
            $term = get_term_by( 'slug', $location_slug, 'event_location' );
            if ( $term ) {
                // Sync taxonomy and meta
                wp_set_object_terms( $post_id, $term->term_id, 'event_location' );
                update_post_meta( $post_id, '_event_location', $location_slug );
            } else {
                error_log( 'Invalid event location slug: ' . $location_slug );
            }
        }
    }

    private function get_event_locations() {
        $locations = get_terms( [
            'taxonomy'   => 'event_location',
            'hide_empty' => false,
        ] );

        $options = [ '' => __( 'Select a location', 'easy-events' ) ];
        if ( ! is_wp_error( $locations ) && ! empty( $locations ) ) {
            foreach ( $locations as $location ) {
                $options[ $location->slug ] = $location->name;
            }
        } else {
            error_log( 'No event locations found or an error occurred.' );
        }

        return $options;
    }

    private function get_selected_location( $post_id ) {
        $terms = wp_get_post_terms( $post_id, 'event_location' );
        if ( ! is_wp_error( $terms ) && ! empty( $terms ) ) {
            return $terms[0]->slug;
        }
        return '';
    }
}