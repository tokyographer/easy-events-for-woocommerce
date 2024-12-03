<?php

class EE_Quick_Edit {
    public function __construct() {
        add_action( 'quick_edit_custom_box', [ $this, 'add_quick_edit_fields' ], 10, 2 );
        add_action( 'save_post', [ $this, 'save_quick_edit_fields' ] );

        // Register custom column
        add_filter( 'manage_edit-product_columns', [ $this, 'add_event_details_column' ] );
        add_action( 'manage_product_posts_custom_column', [ $this, 'populate_event_details_column' ], 10, 2 );
    }

    // Add fields to Quick Edit
    public function add_quick_edit_fields( $column_name, $post_type ) {
        if ( 'product' !== $post_type || 'event_details' !== $column_name ) {
            return;
        }
        ?>
        <fieldset class="inline-edit-col-left">
            <div class="inline-edit-col">
                <label>
                    <span class="title"><?php esc_html_e( 'Event Location', 'easy-events' ); ?></span>
                    <span class="input-text-wrap">
                        <input type="text" name="_event_location" class="inline-edit-event-location">
                    </span>
                </label>
            </div>
        </fieldset>
        <?php
    }

    // Save Quick Edit Fields
    public function save_quick_edit_fields( $post_id ) {
        if ( ! isset( $_POST['ee_quick_edit_nonce'] ) || ! wp_verify_nonce( $_POST['ee_quick_edit_nonce'], 'ee_quick_edit' ) ) {
            return;
        }
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE || wp_is_post_revision( $post_id ) ) {
            return;
        }
        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }
        if ( isset( $_POST['_event_location'] ) ) {
            $new_location = sanitize_text_field( $_POST['_event_location'] );
            $current_location = get_post_meta( $post_id, '_event_location', true );

            if ( $new_location !== $current_location ) {
                update_post_meta( $post_id, '_event_location', $new_location );
            }
        }
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
                $term = EE_Event_Taxonomy::get_event_location_term_cached( $location );
                $location_name = $term ? $term->name : __( 'Unknown', 'easy-events' );
            }

            echo '<strong>' . __( 'Start:', 'easy-events' ) . '</strong> ' . esc_html( $start_date ) . '<br>';
            echo '<strong>' . __( 'End:', 'easy-events' ) . '</strong> ' . esc_html( $end_date ) . '<br>';
            echo '<strong>' . __( 'Location:', 'easy-events' ) . '</strong> ' . esc_html( $location_name );
        }
    }
}