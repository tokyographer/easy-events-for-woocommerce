# Easy Events for WooCommerce

**Contributors:** Tokyographer  
**Tags:** WooCommerce, Events, Product Type, Custom Product  
**Requires at least:** 5.6  
**Tested up to:** 6.7  
**Stable tag:** 1.6  
**Requires PHP:** 7.2  
**License:** GPLv2 or later  
**License URI:** https://www.gnu.org/licenses/gpl-2.0.html  

## Description

Easy Events for WooCommerce adds a custom WooCommerce product type **"Event"** to manage events as products in your store. This plugin integrates dynamic features like custom taxonomies for event locations and organizers, quick edit tools, and API enhancements to allow for better event data management.

### Features

- **Custom Product Type**: Add and manage events as WooCommerce products.
- **Dynamic Event Features**:
  - Event-specific fields like start and end dates, location, and organizers.
  - API integration for events in product and order data.
- **Quick Edit Tools**: Easily update event details directly from the WooCommerce product list.
- **REST API Enhancements**: Include event metadata in WooCommerce REST API endpoints.
- **Custom Taxonomies**:
  - Event Locations
  - Event Organizers

View the **source code** and contribute on [GitHub](https://github.com/tokyographer/easy-events-for-woocommerce).

## Installation

1. Upload the plugin files to the `/wp-content/plugins/easy-events-for-woocommerce` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the "Plugins" screen in WordPress.
3. Configure the plugin settings in WooCommerce.

## Frequently Asked Questions

### How do I create an event?
Create a product in WooCommerce and select "Event" as the product type. Add event-specific details like start and end dates, location, and organizer.

### Are events supported in the WooCommerce API?
Yes, events include custom metadata fields in WooCommerce REST API responses for both products and orders.

### Where can I find the plugin's GitHub page?
The plugin's source code is hosted on GitHub: [Easy Events for WooCommerce GitHub](https://github.com/tokyographer/easy-events-for-woocommerce).

## Changelog

### 1.6
- Added compatibility with WooCommerce 9.4.
- Fixed API issues for order endpoints missing event organizer and product category data.
- Improved performance for taxonomy term retrieval in API responses.
- Updated documentation and GitHub link.

### 1.5
- Added REST API support for event data.
- Introduced Quick Edit functionality for event fields.
- Enhanced admin styles for event management.

### 1.0
- Initial release with event product type and basic features.

## Upgrade Notice

### 1.6
Upgrade to this version for improved REST API functionality and compatibility with the latest WooCommerce.

## License

This plugin is licensed under the GPLv2 or later. See the [GNU General Public License](https://www.gnu.org/licenses/gpl-2.0.html) for more details.