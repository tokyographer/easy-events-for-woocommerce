<?php

class EE_Shortcodes {
    public function __construct() {
        add_shortcode( 'events_by_location', [ $this, 'render_events_by_location' ] );
    }

    public function render_events_by_location( $atts ) {
        $atts = shortcode_atts( [ 'location' => '' ], $atts, 'events_by_location' );
        $query = new WP_Query( [
            'post_type'      => 'product',
            'tax_query'      => [
                [
                    'taxonomy' => 'event_location',
                    'field'    => 'slug',
                    'terms'    => $atts['location'],
                ],
            ],
            'meta_query'     => [
                [
                    'key'     => '_event_start_date',
                    'compare' => 'EXISTS',
                ],
            ],
            'orderby'        => 'meta_value',
            'order'          => 'ASC',
        ] );

        ob_start();
        if ( $query->have_posts() ) {
            echo '<ul class="events-list">';
            while ( $query->have_posts() ) {
                $query->the_post();
                echo '<li>';
                echo '<a href="' . get_permalink() . '">' . get_the_title() . '</a>';
                echo '</li>';
            }
            echo '</ul>';
        } else {
            echo '<p>' . __( 'No events found for this location.', 'easy-events' ) . '</p>';
        }
        wp_reset_postdata();
        return ob_get_clean();
    }
}