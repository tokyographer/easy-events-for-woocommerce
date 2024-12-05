<?php

class EE_PRODUCT_IMPORT_EXPORT {

    public function __construct() {
        // Hook to add fields to the importer mapping options
        add_filter('woocommerce_product_importer_mapping_options', [$this, 'add_event_fields_to_mapping']);

        // Hook to map fields during product import
        add_filter('woocommerce_product_importer_parsed_data', [$this, 'map_event_fields_during_import'], 10, 2);
    }

    public function add_event_fields_to_mapping($options) {
        $options['event_start_date'] = 'Event Start Date';
        $options['event_end_date'] = 'Event End Date';
        $options['event_organizer'] = 'Event Organizer';
        $options['event_location'] = 'Event Location';
        return $options;
    }

    public function map_event_fields_during_import($parsed_data, $imported_data) {
        if (!empty($imported_data['event_start_date'])) {
            $parsed_data['meta_data'][] = [
                'key'   => '_event_start_date',
                'value' => $imported_data['event_start_date']
            ];
        }
        if (!empty($imported_data['event_end_date'])) {
            $parsed_data['meta_data'][] = [
                'key'   => '_event_end_date',
                'value' => $imported_data['event_end_date']
            ];
        }
        if (!empty($imported_data['event_organizer'])) {
            $parsed_data['meta_data'][] = [
                'key'   => '_event_organizer',
                'value' => $imported_data['event_organizer']
            ];
        }
        if (!empty($imported_data['event_location'])) {
            $parsed_data['meta_data'][] = [
                'key'   => '_event_location',
                'value' => $imported_data['event_location']
            ];
        }
        return $parsed_data;
    }
}

// Instantiate the class
new EE_PRODUCT_IMPORT_EXPORT();