# Easy Events for WooCommerce

**Contributors:** [tokyographer](https://github.com/tokyographer)  
**Author:** Anthony T.  
**Tags:** WooCommerce, Events, Products, API Integration, Taxonomy  
**Requires at least:** 5.6  
**Tested up to:** 6.3  
**Stable tag:** 1.0.0  
**Requires PHP:** 7.4  
**License:** GPLv2 or later  
**License URI:** https://www.gnu.org/licenses/gpl-2.0.html  

Easily manage and display event-related data within WooCommerce. Create events as custom product types, assign locations, and extend the WooCommerce API to include event-specific metadata and categories.

---

## Description

**Easy Events for WooCommerce** enables you to transform WooCommerce into a flexible event management system. This plugin allows you to create events as products, associate metadata like start and end dates, assign event locations, and integrate event data with WooCommerce API responses.

### Key Features:
- **Custom Event Product Type**:
  - Create and manage events as WooCommerce products.
  - Includes dedicated fields for event start date, end date, and location.
  
- **Event Location Taxonomy**:
  - A hierarchical taxonomy (`event_location`) to classify event locations.
  - Supports the association of `event_location` with both standard WooCommerce products and event products.

- **WooCommerce API Enhancements**:
  - Includes event metadata (start date, end date, location) in the WooCommerce product API response.
  - Adds product categories (`product_cat`) and event data to order line items in the WooCommerce API.

- **Seamless Integration**:
  - Compatible with WooCommerce product categories, enabling category assignment for event products.

- **Admin UI Enhancements**:
  - Event-specific columns (start date, end date, and location) in the product list table.
  - Customizable fields for event details in the WooCommerce product editor.

---

## Installation

1. Upload the `easy-events-for-woocommerce` folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Configure your event product types and start managing events within WooCommerce.

---

## Usage

1. **Create an Event**:
   - Navigate to WooCommerce → Products → Add New.
   - Select `Event` as the product type and enter event-specific metadata.

2. **Assign Event Locations**:
   - Use the `Event Location` taxonomy to classify and organize your events.

3. **Check API Responses**:
   - Query the WooCommerce REST API to retrieve event data and product categories:
     - **Product API Endpoint**: `/wp-json/wc/v3/products/<product_id>`
     - **Order API Endpoint**: `/wp-json/wc/v3/orders/<order_id>`

---

## Changelog

### 1.0.0
- Initial release.
- Added custom event product type with metadata fields for start and end dates.
- Introduced `event_location` taxonomy for event classification.
- Enhanced WooCommerce API responses for products and orders to include event data and categories.
- Integrated event-specific columns into the WooCommerce admin product list table.

---

## Upgrade Notice

### 1.0.0
- Ensure WooCommerce is installed and activated before upgrading.

---

## Frequently Asked Questions

### Q: Does this plugin work with existing WooCommerce products?
A: Yes, the plugin adds event-specific functionality but does not interfere with standard WooCommerce product types.

### Q: Can I customize event data fields?
A: The plugin provides start date, end date, and location fields by default. You can extend the plugin for additional custom fields.

### Q: How do I query event data in the API?
A: Use WooCommerce's REST API. Event-specific metadata is included in the product and order API responses.

---

## License

This plugin is open-source and licensed under GPLv2. See the LICENSE file for details.