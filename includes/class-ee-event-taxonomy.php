<?php

class EE_Event_Taxonomy {
    public function __construct() {
        // Hook to register the taxonomies during WordPress initialization
        add_action( 'init', [ $this, 'register_taxonomies' ], 20 ); // Priority 20 ensures it runs after WooCommerce
    }

    /**
     * Register taxonomies for WooCommerce products and events.
     */
    public function register_taxonomies() {
        // Check if product_cat exists and associate it with the event product type
        if ( taxonomy_exists( 'product_cat' ) ) {
            register_taxonomy_for_object_type( 'product_cat', 'event' );
            error_log( 'product_cat exists and has been associated with event.' );
        } else {
            error_log( 'product_cat taxonomy does not exist.' );
        }

        // Register the custom event_location taxonomy for product and event
        register_taxonomy( 'event_location', [ 'product', 'event' ], [
            'hierarchical'      => true,
            'labels'            => [
                'name'          => __( 'Event Locations', 'easy-events' ),
                'singular_name' => __( 'Event Location', 'easy-events' ),
                'search_items'  => __( 'Search Event Locations', 'easy-events' ),
                'all_items'     => __( 'All Event Locations', 'easy-events' ),
                'parent_item'   => __( 'Parent Event Location', 'easy-events' ),
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

        // Debugging: Log associated taxonomies for the event product type
        $this->log_event_taxonomies();
    }

    /**
     * Debugging: Log all taxonomies associated with the 'event' product type.
     */
    private function log_event_taxonomies() {
        // Log all taxonomies associated with the event product type
        $taxonomies = get_object_taxonomies( 'event', 'names' );
        error_log( 'Taxonomies for event: ' . print_r( $taxonomies, true ) );

        // Log details about the product_cat taxonomy
        $product_cat = get_taxonomy( 'product_cat' );
        if ( $product_cat ) {
            error_log( 'product_cat object types: ' . print_r( $product_cat->object_type, true ) );
        } else {
            error_log( 'product_cat taxonomy is missing or not registered.' );
        }
    }
}

// Initialize the taxonomy class
new EE_Event_Taxonomy();