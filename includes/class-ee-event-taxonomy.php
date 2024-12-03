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
     * Fetch Event Location term with caching.
     *
     * @param string $slug The slug of the term.
     * @return WP_Term|false The term object or false if not found.
     */
    public static function get_event_location_term_cached( $slug ) {
        $cache_key = 'event_location_' . $slug;
        $term = wp_cache_get( $cache_key, 'event_locations' );

        if ( false === $term ) {
            $term = get_term_by( 'slug', $slug, 'event_location' );
            if ( $term ) {
                wp_cache_set( $cache_key, $term, 'event_locations' );
            }
        }

        return $term;
    }
}