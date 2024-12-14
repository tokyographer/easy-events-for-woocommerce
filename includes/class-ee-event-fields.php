<?php

class EE_Event_Fields {
    public function __construct() {
        // Hook to display custom fields on the single product page for all products.
        add_action('woocommerce_before_single_product_summary', [$this, 'display_event_fields']);
        // Hook to add the fields to the product edit screen.
        add_action('woocommerce_product_options_general_product_data', [$this, 'add_event_fields']);
        // Hook to save the custom fields data.
        add_action('woocommerce_process_product_meta', [$this, 'save_event_fields']);
    }

    /**
     * Display event fields: Start Date, End Date.
     */
    public function display_event_fields() {
        global $product;

        // Display the start and end date fields for all product types.
        $start_date = get_post_meta($product->get_id(), '_event_start_date', true);
        $end_date = get_post_meta($product->get_id(), '_event_end_date', true);

        if ($start_date || $end_date) {
            echo '<p><strong>Start Date:</strong> ' . esc_html($start_date) . '</p>';
            echo '<p><strong>End Date:</strong> ' . esc_html($end_date) . '</p>';
        }
    }

    /**
     * Add custom fields to the product edit screen.
     */
    public function add_event_fields() {
        echo '<div class="options_group">';

        woocommerce_wp_text_input([
            'id'          => '_event_start_date',
            'label'       => __('Event Start Date', 'easy-events'),
            'placeholder' => 'YYYY-MM-DD',
            'type'        => 'date',
            'desc_tip'    => true,
            'description' => __('The start date of the event.', 'easy-events'),
        ]);

        woocommerce_wp_text_input([
            'id'          => '_event_end_date',
            'label'       => __('Event End Date', 'easy-events'),
            'placeholder' => 'YYYY-MM-DD',
            'type'        => 'date',
            'desc_tip'    => true,
            'description' => __('The end date of the event.', 'easy-events'),
        ]);

        echo '</div>';
    }

    /**
     * Save the custom fields data.
     */
    public function save_event_fields($post_id) {
        if (isset($_POST['_event_start_date'])) {
            update_post_meta($post_id, '_event_start_date', sanitize_text_field($_POST['_event_start_date']));
        }

        if (isset($_POST['_event_end_date'])) {
            update_post_meta($post_id, '_event_end_date', sanitize_text_field($_POST['_event_end_date']));
        }
    }
}