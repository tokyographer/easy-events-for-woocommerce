
<?php

class EE_Quick_Edit {
    public function __construct() {
        add_action( 'quick_edit_custom_box', [ $this, 'add_quick_edit_fields' ], 10, 2 );
        add_action( 'save_post', [ $this, 'save_quick_edit_fields' ] );

        // Register custom column
        add_filter( 'manage_edit-product_columns', [ $this, 'add_event_details_column' ] );
        add_action( 'manage_product_posts_custom_column', [ $this, 'populate_event_details_column' ], 10, 2 );
    }

    // Add custom column to WooCommerce product list
    public function add_event_details_column( $columns ) {
        $columns['event_details'] = __( 'Event Details', 'easy-events' );
        return $columns;
    }

    // Populate the custom column with data
    public function populate_event_details_column( $column, $post_id ) {
        if ( 'event_details' === $column ) {
            $start_date = get_post_meta( $post_id, '_event_start_date', true );
            $end_date = get_post_meta( $post_id, '_event_end_date', true );
            $location = get_post_meta( $post_id, '_event_location', true );

            $location_name = '';
            if ( $location ) {
                $term = get_term_by( 'slug', $location, 'event_location' );
                $location_name = $term ? $term->name : __( 'Unknown', 'easy-events' );
            }

            echo '<strong>' . __( 'Start:', 'easy-events' ) . '</strong> ' . esc_html( $start_date ) . '<br>';
            echo '<strong>' . __( 'End:', 'easy-events' ) . '</strong> ' . esc_html( $end_date ) . '<br>';
            echo '<strong>' . __( 'Location:', 'easy-events' ) . '</strong> ' . esc_html( $location_name );
        }
    }

    // Add custom fields to the Quick Edit form
    public function add_quick_edit_fields( $column_name, $post_type ) {
        // Use a static variable to ensure the fields are added only once
        static $added_fields = false;

        if ( $post_type === 'product' && ! $added_fields ) {
            $added_fields = true;
            ?>
            <fieldset class="inline-edit-col-right">
                <div class="inline-edit-col">
                    <?php
                    // Fields removed
                    ?>
                </div>
            </fieldset>
            <?php
        }
    }

    // Save the custom fields from Quick Edit
    public function save_quick_edit_fields( $post_id ) {
        if ( isset( $_POST['_event_start_date'] ) ) {
            update_post_meta( $post_id, '_event_start_date', sanitize_text_field( $_POST['_event_start_date'] ) );
        }
        if ( isset( $_POST['_event_end_date'] ) ) {
            update_post_meta( $post_id, '_event_end_date', sanitize_text_field( $_POST['_event_end_date'] ) );
        }
        if ( isset( $_POST['_event_location'] ) ) {
            $location_slug = sanitize_text_field( $_POST['_event_location'] );
            wp_set_object_terms( $post_id, $location_slug, 'event_location' );
        }
    }
}

new EE_Quick_Edit();
