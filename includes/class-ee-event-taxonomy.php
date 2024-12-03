<?php

class EE_Event_Taxonomy {
    public function __construct() {
        // Hook to register taxonomies during initialization.
        add_action('init', [$this, 'register_event_location_taxonomy']);
        add_action('init', [$this, 'register_event_organizers_taxonomy']);
    }

    /**
     * Register the Event Location taxonomy for WooCommerce products.
     */
    public function register_event_location_taxonomy() {
        $labels = [
            'name'                       => __('Event Locations', 'easy-events'),
            'singular_name'              => __('Event Location', 'easy-events'),
            'search_items'               => __('Search Event Locations', 'easy-events'),
            'all_items'                  => __('All Event Locations', 'easy-events'),
            'parent_item'                => __('Parent Event Location', 'easy-events'),
            'parent_item_colon'          => __('Parent Event Location:', 'easy-events'),
            'edit_item'                  => __('Edit Event Location', 'easy-events'),
            'update_item'                => __('Update Event Location', 'easy-events'),
            'add_new_item'               => __('Add New Event Location', 'easy-events'),
            'new_item_name'              => __('New Event Location Name', 'easy-events'),
            'menu_name'                  => __('Event Locations', 'easy-events'),
        ];

        $args = [
            'hierarchical'          => true,
            'labels'                => $labels,
            'show_ui'               => true,
            'show_admin_column'     => true,
            'query_var'             => true,
            'rewrite'               => ['slug' => 'event-location'],
            'show_in_rest'          => true,
        ];

        register_taxonomy('event_location', 'product', $args);
    }

    /**
     * Register the Event Organizers taxonomy for WooCommerce products.
     */
    public function register_event_organizers_taxonomy() {
        $labels = [
            'name'                       => __('Event Organizers', 'easy-events'),
            'singular_name'              => __('Event Organizer', 'easy-events'),
            'search_items'               => __('Search Event Organizers', 'easy-events'),
            'all_items'                  => __('All Event Organizers', 'easy-events'),
            'parent_item'                => __('Parent Event Organizer', 'easy-events'),
            'parent_item_colon'          => __('Parent Event Organizer:', 'easy-events'),
            'edit_item'                  => __('Edit Event Organizer', 'easy-events'),
            'update_item'                => __('Update Event Organizer', 'easy-events'),
            'add_new_item'               => __('Add New Event Organizer', 'easy-events'),
            'new_item_name'              => __('New Event Organizer Name', 'easy-events'),
            'menu_name'                  => __('Event Organizers', 'easy-events'),
        ];

        $args = [
            'hierarchical'      => true,
            'labels'            => $labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => ['slug' => 'event-organizer'],
            'show_in_rest'      => true,
        ];

        register_taxonomy('event_organizer', 'product', $args);
    }
}