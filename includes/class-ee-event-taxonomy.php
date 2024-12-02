<?php

class EE_Event_Taxonomy {
    public function __construct() {
        // Hook to register the taxonomy during initialization.
        add_action( 'init', [ $this, 'register_event_location_taxonomy' ] );
    }

    /**
     * Register the Event Location taxonomy for WooCommerce products.
     */
    public function register_event_location_taxonomy() {
        $labels = [
            'name'                       => __( 'Event Locations', 'easy-events' ),
            'singular_name'              => __( 'Event Location', 'easy-events' ),
            'search_items'               => __( 'Search Event Locations', 'easy-events' ),
            'all_items'                  => __( 'All Event Locations', 'easy-events' ),
            'parent_item'                => __( 'Parent Event Location', 'easy-events' ),
            'parent_item_colon'          => __( 'Parent Event Location:', 'easy-events' ),
            'edit_item'                  => __( 'Edit Event Location', 'easy-events' ),
            'update_item'                => __( 'Update Event Location', 'easy-events' ),
            'add_new_item'               => __( 'Add New Event Location', 'easy-events' ),
            'new_item_name'              => __( 'New Event Location Name', 'easy-events' ),
            'menu_name'                  => __( 'Event Locations', 'easy-events' ),
        ];

        $args = [
            'hierarchical'          => true,  // Makes it behave like categories.
            'labels'                => $labels,
            'show_ui'               => true, // Enables UI in admin panel.
            'show_admin_column'     => true, // Display as a column in product admin list.
            'query_var'             => true, // Allows taxonomy queries.
            'rewrite'               => [ 'slug' => 'event-location' ],
        ];

        register_taxonomy( 'event_location', 'product', $args );
    }

    /**
     * Helper function to fetch Event Location terms.
     *
     * @param int $product_id The product ID.
     * @return array Array of term names.
     */
    public static function get_event_location_terms( $product_id ) {
        $terms = wp_get_post_terms( $product_id, 'event_location', [ 'fields' => 'names' ] );
        return !is_wp_error( $terms ) && !empty( $terms ) ? $terms : [];
    }
}

// Initialize the taxonomy.
new EE_Event_Taxonomy();