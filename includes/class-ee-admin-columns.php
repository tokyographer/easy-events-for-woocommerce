<?php

class EE_Admin_Columns {

    public function __construct() {
        add_filter('manage_edit-product_columns', [$this, 'add_event_columns'], 20);
        add_action('manage_product_posts_custom_column', [$this, 'populate_event_columns'], 10, 2);
        add_filter('manage_edit-product_sortable_columns', [$this, 'make_event_columns_sortable']);
        add_action('pre_get_posts', [$this, 'sort_event_columns']);
    }

    public function add_event_columns($columns) {
        $columns['event_start_date'] = __('Event Start Date', 'easy-events');
        $columns['event_end_date'] = __('Event End Date', 'easy-events');
        $columns['event_location'] = __('Event Location', 'easy-events');
        $columns['event_organizer'] = __('Event Organizer', 'easy-events');
        return $columns;
    }

    public function populate_event_columns($column, $post_id) {
        switch ($column) {
            case 'event_start_date':
                echo esc_html(get_post_meta($post_id, '_event_start_date', true) ?: __('N/A', 'easy-events'));
                break;

            case 'event_end_date':
                echo esc_html(get_post_meta($post_id, '_event_end_date', true) ?: __('N/A', 'easy-events'));
                break;

            case 'event_location':
                $locations = wp_get_post_terms($post_id, 'event_location', ['fields' => 'names']);
                echo esc_html(implode(', ', $locations) ?: __('None', 'easy-events'));
                break;

            case 'event_organizer':
                $organizers = wp_get_post_terms($post_id, 'event_organizer', ['fields' => 'names']);
                echo esc_html(implode(', ', $organizers) ?: __('None', 'easy-events'));
                break;
        }
    }

    public function make_event_columns_sortable($sortable_columns) {
        $sortable_columns['event_start_date'] = 'event_start_date';
        $sortable_columns['event_end_date'] = 'event_end_date';
        $sortable_columns['event_location'] = 'event_location';
        $sortable_columns['event_organizer'] = 'event_organizer';
        return $sortable_columns;
    }

    public function sort_event_columns($query) {
        if (!is_admin() || !$query->is_main_query()) {
            return;
        }

        $orderby = $query->get('orderby');

        if ('event_start_date' === $orderby) {
            $query->set('meta_key', '_event_start_date');
            $query->set('orderby', 'meta_value');
        }

        if ('event_end_date' === $orderby) {
            $query->set('meta_key', '_event_end_date');
            $query->set('orderby', 'meta_value');
        }

        if ('event_location' === $orderby) {
            $query->set('orderby', 'name');
            $query->set('tax_query', [
                [
                    'taxonomy' => 'event_location',
                    'field'    => 'slug',
                    'operator' => 'EXISTS',
                ],
            ]);
        }

        if ('event_organizer' === $orderby) {
            $query->set('orderby', 'name');
            $query->set('tax_query', [
                [
                    'taxonomy' => 'event_organizer',
                    'field'    => 'slug',
                    'operator' => 'EXISTS',
                ],
            ]);
        }
    }
}