<?php

class EE_Event_Taxonomy {
    private static $instance = null;

    /**
     * Private constructor to prevent direct instantiation
     */
    private function __construct() {
        // Hook to register taxonomies during initialization
        add_action('init', [$this, 'register_event_location_taxonomy']);
        add_action('init', [$this, 'register_event_organizers_taxonomy']);
    }

    /**
     * Get the single instance of the class.
     * 
     * @return EE_Event_Taxonomy
     */
    public static function get_instance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
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

        register_taxonomy('event_location', ['product'], $args);
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
            'hierarchical'          => true,
            'labels'                => $labels,
            'show_ui'               => true,
            'show_admin_column'     => true,
            'query_var'             => true,
            'rewrite'               => ['slug' => 'event-organizer'],
            'show_in_rest'          => true,
        ];

        register_taxonomy('event_organizer', ['product'], $args);
    }

    /**
     * Fetch Event Location term with caching.
     *
     * @param string $slug The slug of the term.
     * @return WP_Term|false The term object or false if not found.
     */
    public static function get_event_location_term_cached($slug) {
        $cache_key = 'event_location_' . $slug;
        $term = wp_cache_get($cache_key, 'event_locations');

        if (false === $term) {
            $term = get_term_by('slug', $slug, 'event_location');
            if ($term) {
                wp_cache_set($cache_key, $term, 'event_locations');
            }
        }

        return $term;
    }
}

// Initialize the taxonomy on the plugins_loaded action
add_action('plugins_loaded', function () {
    EE_Event_Taxonomy::get_instance();
});