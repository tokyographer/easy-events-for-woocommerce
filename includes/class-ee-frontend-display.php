
<?php

class EE_Frontend_Display {

    public function __construct() {
        add_filter('the_content', [$this, 'display_event_dates']);
    }

    /**
     * Display Event Start Date and End Date on the single event page.
     */
    public function display_event_dates($content) {
        if (get_post_type() === 'event') {
            $start_date = get_post_meta(get_the_ID(), '_event_start_date', true);
            $end_date = get_post_meta(get_the_ID(), '_event_end_date', true);

            if ($start_date || $end_date) {
                $content .= '<div class="event-dates">';
                if ($start_date) {
                    $content .= '<p><strong>' . __('Start Date', 'easy-events') . ':</strong> ' . esc_html($start_date) . '</p>';
                }
                if ($end_date) {
                    $content .= '<p><strong>' . __('End Date', 'easy-events') . ':</strong> ' . esc_html($end_date) . '</p>';
                }
                $content .= '</div>';
            }
        }

        return $content;
    }
}
