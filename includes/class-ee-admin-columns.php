<?php

class EE_Admin_Columns {
    public function __construct() {
        // Hook to add custom columns
        add_filter('manage_edit-product_columns', [$this, 'add_columns'], 15);
        add_action('manage_product_posts_custom_column', [$this, 'render_columns'], 10, 2);
    }

    /**
     * Add custom columns to the WooCommerce product list table.
     *
     * @param array $columns Existing columns.
     * @return array Modified columns.
     */
    public function add_columns( $columns ) {
        // Remove redundant columns
        unset( $columns['event_details'] ); // Consolidated field
        unset( $columns['event_locations'] ); // Duplicate column name
    
        // Add only necessary columns
        $columns['event_start_date'] = esc_html__( 'Start Date', 'easy-events' );
        $columns['event_end_date'] = esc_html__( 'End Date', 'easy-events' );
        $columns['location'] = esc_html__( 'Location', 'easy-events' ); // Consolidate event_location and location
    
        return $columns;
    }

    /**
     * Render custom column content.
     *
     * @param string $column Column name.
     * @param int $post_id Product ID.
     */
    public function render_columns( $column, $post_id ) {
        switch ( $column ) {
            case 'event_start_date':
                $start_date = get_post_meta( $post_id, '_event_start_date', true );
                $start_date = $this->validate_date( $start_date ) ? $start_date : esc_html__( 'Not set', 'easy-events' );
                echo esc_html( $start_date );
                break;
    
            case 'event_end_date':
                $end_date = get_post_meta( $post_id, '_event_end_date', true );
                $end_date = $this->validate_date( $end_date ) ? $end_date : esc_html__( 'Not set', 'easy-events' );
                echo esc_html( $end_date );
                break;
    
            case 'location':
                $terms = wp_get_post_terms( $post_id, 'event_location', ['fields' => 'names'] );
                if ( is_wp_error( $terms ) ) {
                    echo esc_html__( 'Error retrieving location', 'easy-events' );
                } else {
                    echo esc_html( ! empty( $terms ) ? implode( ', ', $terms ) : esc_html__( 'No location assigned', 'easy-events' ) );
                }
                break;
        }
    }

    /**
     * Validate the date format (YYYY-MM-DD).
     *
     * @param string $date Date string.
     * @return bool True if valid, false otherwise.
     */
    private function validate_date( $date ) {
        return preg_match( '/^\d{4}-\d{2}-\d{2}$/', $date );
    }
}

// Initialize the admin columns if WooCommerce is active.
if ( class_exists( 'WooCommerce' ) ) {
    new EE_Admin_Columns();
}