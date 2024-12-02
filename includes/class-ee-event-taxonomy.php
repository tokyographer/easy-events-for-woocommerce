<?php

if ( ! class_exists( 'EE_Event_Taxonomy' ) ) {

    class EE_Event_Taxonomy {
        public function __construct() {
            // Hook to register taxonomies with priority 20
            add_action( 'init', [ $this, 'register_taxonomies' ], 20 );

            // Debugging association of product_cat with event
            add_action( 'init', [ $this, 'ensure_product_cat_association' ], 20 );
        }

        /**
         * Register taxonomies for WooCommerce products and events.
         */
        public function register_taxonomies() {
            // Register the custom event_location taxonomy
            register_taxonomy( 'event_location', [ 'product', 'event' ], [
                'hierarchical'      => true,
                'labels'            => [
                    'name'              => __( 'Event Locations', 'easy-events' ),
                    'singular_name'     => __( 'Event Location', 'easy-events' ),
                    'search_items'      => __( 'Search Event Locations', 'easy-events' ),
                    'all_items'         => __( 'All Event Locations', 'easy-events' ),
                    'edit_item'         => __( 'Edit Event Location', 'easy-events' ),
                    'update_item'       => __( 'Update Event Location', 'easy-events' ),
                    'add_new_item'      => __( 'Add New Event Location', 'easy-events' ),
                    'new_item_name'     => __( 'New Event Location Name', 'easy-events' ),
                    'menu_name'         => __( 'Event Locations', 'easy-events' ),
                ],
                'show_ui'           => true,
                'show_admin_column' => true,
                'query_var'         => true,
                'rewrite'           => [ 'slug' => 'event-location' ],
            ] );

            // Log the custom taxonomy registration
            error_log( 'Custom taxonomy event_location registered.' );
        }

        /**
         * Ensure product categories (product_cat) are associated with the event product type.
         */
        public function ensure_product_cat_association() {
            if ( taxonomy_exists( 'product_cat' ) ) {
                register_taxonomy_for_object_type( 'product_cat', 'event' );
                error_log( 'product_cat successfully associated with event.' );
            } else {
                error_log( 'product_cat taxonomy does not exist.' );
            }
        }
    }

    // Initialize the taxonomy class
    new EE_Event_Taxonomy();
}