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

// Load translations on the `init` action
add_action('init', function () {
    load_plugin_textdomain('easy-events', false, dirname(plugin_basename(__FILE__)) . '/languages');
});

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
        // Singleton Classes
        if ( class_exists( 'EE_Event_Fields' ) ) {
            EE_Event_Fields::get_instance();
        }
        if ( class_exists( 'EE_Event_Taxonomy' ) ) {
            EE_Event_Taxonomy::get_instance();
        }

        // Non-Singleton Classes
        if ( class_exists( 'EE_Admin_Columns' ) ) {
            new EE_Admin_Columns();
        }
        if ( class_exists( 'EE_API_Integration' ) ) {
            new EE_API_Integration(); // Changed to direct instantiation
        }
        if ( class_exists( 'EE_Shortcodes' ) ) {
            new EE_Shortcodes();
        }
        if ( class_exists( 'EE_Cart_Checkout' ) ) {
            new EE_Cart_Checkout();
        }
        if ( class_exists( 'EE_Frontend_Display' ) ) {
            new EE_Frontend_Display();
        }
        if ( class_exists( 'EE_Quick_Edit' ) ) {
            new EE_Quick_Edit();
        }
    } else {
        add_action( 'admin_notices', function () {
            echo '<div class="error"><p>' . esc_html__( 'Easy Events for WooCommerce requires WooCommerce to be active.', 'easy-events' ) . '</p></div>';
        });
    }
});

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