<?php

class EE_REGISTER_ATTRIBUTES {

    public function __construct() {
        // Register the action on init
        add_action('init', [$this, 'register_event_attributes']);
    }

    public function register_event_attributes() {
        if (!function_exists('wc_create_attribute')) {
            return;
        }

        // Register Event Start Date attribute
        if (!taxonomy_exists('pa_event_start_date')) {
            wc_create_attribute([
                'name'         => 'Event Start Date',
                'slug'         => 'event_start_date',
                'type'         => 'text',
                'order_by'     => 'menu_order',
                'has_archives' => false,
            ]);
        }

        // Register Event End Date attribute
        if (!taxonomy_exists('pa_event_end_date')) {
            wc_create_attribute([
                'name'         => 'Event End Date',
                'slug'         => 'event_end_date',
                'type'         => 'text',
                'order_by'     => 'menu_order',
                'has_archives' => false,
            ]);
        }
    }
}

// Instantiate the class
new EE_REGISTER_ATTRIBUTES();