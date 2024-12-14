<?php

class EE_REGISTER_ATTRIBUTES {

    public function __construct() {
        // Register the action on init
        add_action('init', [$this, 'register_event_attributes']);
    }

    public function register_event_attributes() {
        // Map Event Start Date to core product meta key
        if (!metadata_exists('post', null, '_event_start_date')) {
            register_post_meta('product', '_event_start_date', [
                'type'              => 'string',
                'description'       => __('Event Start Date', 'easy-events'),
                'single'            => true,
                'show_in_rest'      => true,
                'sanitize_callback' => 'sanitize_text_field',
            ]);
        }

        // Map Event End Date to core product meta key
        if (!metadata_exists('post', null, '_event_end_date')) {
            register_post_meta('product', '_event_end_date', [
                'type'              => 'string',
                'description'       => __('Event End Date', 'easy-events'),
                'single'            => true,
                'show_in_rest'      => true,
                'sanitize_callback' => 'sanitize_text_field',
            ]);
        }
    }
}

new EE_REGISTER_ATTRIBUTES();
