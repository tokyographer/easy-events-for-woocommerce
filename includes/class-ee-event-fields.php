<?php

class EE_Event_Fields {

    private static $instance = null;

    /**
     * Singleton pattern to ensure the class is only instantiated once.
     */
    public static function get_instance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        // Ensure hooks are not duplicated
        add_action('woocommerce_product_options_general_product_data', [$this, 'add_event_fields']);
        add_action('woocommerce_process_product_meta', [$this, 'save_event_fields']);
    }

    /**
     * Add event fields to the product edit screen.
     */
    public function add_event_fields() {
        global $post;

        // Prevent duplicate rendering using a custom action
        if (did_action('ee_event_fields_rendered') > 0) {
            return;
        }
        do_action('ee_event_fields_rendered');

        echo '<div class="options_group">';

        // Event Start Date
        woocommerce_wp_text_input([
            'id'          => '_event_start_date',
            'label'       => __('Event Start Date', 'easy-events'),
            'placeholder' => 'YYYY-MM-DD',
            'type'        => 'date',
            'description' => __('The start date of the event.', 'easy-events'),
            'desc_tip'    => true,
        ]);

        // Event End Date
        woocommerce_wp_text_input([
            'id'          => '_event_end_date',
            'label'       => __('Event End Date', 'easy-events'),
            'placeholder' => 'YYYY-MM-DD',
            'type'        => 'date',
            'description' => __('The end date of the event.', 'easy-events'),
            'desc_tip'    => true,
        ]);

        // Event Location (Taxonomy Dropdown)
        $event_locations = wp_get_post_terms($post->ID, 'event_location', ['fields' => 'ids']);
        $event_locations = is_array($event_locations) ? $event_locations : [];
        woocommerce_wp_select([
            'id'          => 'event_location',
            'label'       => __('Event Location', 'easy-events'),
            'options'     => $this->get_taxonomy_terms('event_location'),
            'description' => __('Select the location of the event.', 'easy-events'),
            'value'       => reset($event_locations),
        ]);

        // Event Organizer (Taxonomy Dropdown)
        $event_organizers = wp_get_post_terms($post->ID, 'event_organizer', ['fields' => 'ids']);
        $event_organizers = is_array($event_organizers) ? $event_organizers : [];
        woocommerce_wp_select([
            'id'          => 'event_organizer',
            'label'       => __('Event Organizer', 'easy-events'),
            'options'     => $this->get_taxonomy_terms('event_organizer'),
            'description' => __('Select the organizer of the event.', 'easy-events'),
            'value'       => reset($event_organizers),
        ]);

        echo '</div>';
    }

    /**
     * Save event fields when the product is updated.
     */
    public function save_event_fields($post_id) {
        // Validate and save Event Start Date
        if (isset($_POST['_event_start_date'])) {
            $start_date = sanitize_text_field($_POST['_event_start_date']);
            if (!$this->is_valid_date($start_date)) {
                wc_add_notice(__('Invalid Event Start Date. Please use YYYY-MM-DD format.', 'easy-events'), 'error');
            } else {
                update_post_meta($post_id, '_event_start_date', $start_date);
            }
        }

        // Validate and save Event End Date
        if (isset($_POST['_event_end_date'])) {
            $end_date = sanitize_text_field($_POST['_event_end_date']);
            if (!$this->is_valid_date($end_date)) {
                wc_add_notice(__('Invalid Event End Date. Please use YYYY-MM-DD format.', 'easy-events'), 'error');
            } else {
                update_post_meta($post_id, '_event_end_date', $end_date);
            }
        }

        // Validate Date Range
        if (!empty($start_date) && !empty($end_date) && strtotime($start_date) > strtotime($end_date)) {
            wc_add_notice(__('Event Start Date must be before Event End Date.', 'easy-events'), 'error');
        }

        // Save Event Location as a taxonomy term
        if (isset($_POST['event_location'])) {
            wp_set_post_terms($post_id, intval($_POST['event_location']), 'event_location');
        }

        // Save Event Organizer as a taxonomy term
        if (isset($_POST['event_organizer'])) {
            wp_set_post_terms($post_id, intval($_POST['event_organizer']), 'event_organizer');
        }
    }

    /**
     * Get terms for a taxonomy as an array for dropdowns.
     */
    private function get_taxonomy_terms($taxonomy) {
        $terms = get_terms(['taxonomy' => $taxonomy, 'hide_empty' => false]);
        $options = ['' => __('Select', 'easy-events')];

        foreach ($terms as $term) {
            $options[$term->term_id] = $term->name;
        }

        return $options;
    }

    /**
     * Validate date format (YYYY-MM-DD).
     */
    private function is_valid_date($date) {
        $format = 'Y-m-d';
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) === $date;
    }
}

// Use the singleton instance
EE_Event_Fields::get_instance();