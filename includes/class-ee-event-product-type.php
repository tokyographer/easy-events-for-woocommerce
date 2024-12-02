<?php

class EE_Event_Product_Type {
    public function __construct() {
        // Add custom product type to the WooCommerce product selector
        add_filter( 'product_type_selector', [ $this, 'add_event_product_type' ] );

        // Add custom fields to the General tab in the product edit screen
        add_action( 'woocommerce_product_options_general_product_data', [ $this, 'add_event_fields' ] );

        // Save custom fields when the product is saved
        add_action( 'woocommerce_process_product_meta', [ $this, 'save_event_fields' ] );

        // Register product categories for the "event" product type
        add_action( 'init', [ $this, 'register_product_categories' ] );
    }

    /**
     * Add the custom "Event" product type to the WooCommerce product selector.
     *
     * @param array $types Existing product types.
     * @return array Modified product types.
     */
    public function add_event_product_type( $types ) {
        $types['event'] = __( 'Event', 'easy-events' );
        return $types;
    }

    /**
     * Register the "product_cat" taxonomy for the "event" product type.
     */
    public function register_product_categories() {
        register_taxonomy_for_object_type( 'product_cat', 'event' );
    }

    /**
     * Add custom fields to the WooCommerce product edit screen (General tab).
     */
    public function add_event_fields() {
        global $post;

        echo '<div class="options_group">';

        // Add Event Start Date field
        woocommerce_wp_text_input( [
            'id'          => '_event_start_date',
            'label'       => __( 'Event Start Date', 'easy-events' ),
            'type'        => 'date',
            'description' => __( 'Enter the start date of the event.', 'easy-events' ),
            'desc_tip'    => true,
        ] );

        // Add Event End Date field
        woocommerce_wp_text_input( [
            'id'          => '_event_end_date',
            'label'       => __( 'Event End Date', 'easy-events' ),
            'type'        => 'date',
            'description' => __( 'Enter the end date of the event.', 'easy-events' ),
            'desc_tip'    => true,
        ] );

        echo '</div>';
    }

    /**
     * Save custom fields for the WooCommerce product.
     *
     * @param int $post_id The ID of the product being saved.
     */
    public function save_event_fields( $post_id ) {
        // Save Event Start Date
        if ( isset( $_POST['_event_start_date'] ) ) {
            update_post_meta( $post_id, '_event_start_date', sanitize_text_field( $_POST['_event_start_date'] ) );
        }

        // Save Event End Date
        if ( isset( $_POST['_event_end_date'] ) ) {
            update_post_meta( $post_id, '_event_end_date', sanitize_text_field( $_POST['_event_end_date'] ) );
        }
    }
}

// Initialize the custom product type class
if ( class_exists( 'WooCommerce' ) ) {
    new EE_Event_Product_Type();
}