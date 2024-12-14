<?php
/**
 * Plugin Name: Easy Events for WooCommerce
 * Description: Adds a custom WooCommerce product type "Event" with dynamic features.
 * Version: 1.5
 * Author: Tokyographer
 * License: GPLv2 or later
 * Text Domain: easy-events
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

// Dynamically include all files from the `includes/` directory.
function ee_load_includes() {
    foreach ( glob( plugin_dir_path( __FILE__ ) . 'includes/*.php' ) as $file ) {
        if ( is_readable( $file ) ) {
            require_once $file;
        } else {
            error_log( 'Could not include file: ' . $file );
        }
    }
}
ee_load_includes();

// Initialize all classes after WooCommerce is loaded.
add_action( 'plugins_loaded', function () {
    if ( class_exists( 'WooCommerce' ) ) {
        // Initialize specific plugin classes.
        new EE_Admin_Columns(); // Admin product columns for event details.
        new EE_Cart_Checkout(); // Display event details in cart and checkout.
        new EE_Shortcodes(); // Shortcodes for events.
        new EE_API_Integration(); // Integrate event data into WooCommerce API.
        new EE_Frontend_Display(); // Frontend display for events.
        new EE_Quick_Edit(); // Quick Edit functionality for events.
        new EE_WooCommerce_Hooks(); // Initialize EE_WooCommerce_Hooks.
             // Initialize taxonomy and custom fields classes.
             new EE_Event_Taxonomy(); // Handles event taxonomy.
             new EE_Event_Fields(); // Handles custom event fields
    }
} );

// Enqueue the JavaScript file for the Quick Edit feature.
add_action( 'admin_enqueue_scripts', function () {
    wp_enqueue_script(
        'ee-quick-edit',
        plugin_dir_url( __FILE__ ) . 'assets/js/quick-edit.js',
        [ 'jquery' ],
        '1.0',
        true
    );
});

// Enqueue the CSS file for admin styles.
add_action( 'admin_enqueue_scripts', function () {
    wp_enqueue_style(
        'ee-admin-styles',
        plugin_dir_url( __FILE__ ) . 'assets/css/quick-edit.css',
        [],
        '1.0'
    );
});