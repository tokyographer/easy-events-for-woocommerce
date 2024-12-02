<?php

class EE_Event_Taxonomy {
    public function __construct() {
        // Register taxonomies during WordPress initialization
        add_action( 'init', [ $this, 'register_taxonomies' ] );
    }

    /**
     * Register taxonomies for WooCommerce products and events.
     */
    public function register_taxonomies() {
        // Ensure product categories are associated with the 'event' product type
        register_taxonomy_for_object_type( 'product_cat', 'event' );

        // Register the custom event_location taxonomy for product and event
        register_taxonomy( 'event_location', [ 'product', 'event' ], [
            'hierarchical'      => true,
            'labels'            => [
                'name'          => __( 'Event Locations', 'easy-events' ),
                'singular_name' => __( 'Event Location', 'easy-events' ),
                'search_items'  => __( 'Search Event Locations', 'easy-events' ),
                'all_items'     => __( 'All Event Locations', 'easy-events' ),
                'edit_item'     => __( 'Edit Event Location', 'easy-events' ),
                'update_item'   => __( 'Update Event Location', 'easy-events' ),
                'add_new_item'  => __( 'Add New Event Location', 'easy-events' ),
                'new_item_name' => __( 'New Event Location Name', 'easy-events' ),
                'menu_name'     => __( 'Event Locations', 'easy-events' ),
            ],
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => [ 'slug' => 'event-location' ],
        ] );

        // Debugging: Log associated taxonomies for the 'event' product type
        $this->log_event_taxonomies();
    }

    /**
     * Debugging: Log all taxonomies associated with the 'event' product type.
     */
    private function log_event_taxonomies() {
        $taxonomies = get_object_taxonomies( 'event', 'names' );
        error_log( 'Taxonomies for event: ' . print_r( $taxonomies, true ) );
    }
}

// Initialize the taxonomy class
new EE_Event_Taxonomy();