# Easy Events for WooCommerce

**Contributors:** Tokyographer  
**Tags:** WooCommerce, events, custom fields, API integration  
**Requires at least:** 5.6  
**Tested up to:** 6.3  
**Requires PHP:** 7.4  
**Stable tag:** 1.5.1  
**License:** GPLv2 or later  
**License URI:** https://www.gnu.org/licenses/gpl-2.0.html  
**Author Website:** [https://github.com/tokyographer](https://github.com/tokyographer)  

## Description

**Easy Events for WooCommerce** transforms WooCommerce into a powerful event management tool by adding support for event-related custom fields, taxonomies, and seamless integration with the REST API.  

### Features:
- **Event Custom Fields**: Add start date, end date, and location fields to WooCommerce products.
- **Custom Taxonomies**:
  - **Event Locations**: Assign hierarchical locations to products.
  - **Event Organizers**: Organize products by event organizers.
- **REST API Enhancements**:
  - Include custom fields and taxonomy data in WooCommerce product and order REST API responses.
- **Seamless Admin Experience**:
  - Fully integrated into the WooCommerce product data interface.
  - Support for dynamic dropdowns based on registered taxonomies.
- **Optimized Codebase**:
  - Simplified and refactored code for better maintainability.

---

## Installation

1. Upload the plugin files to the `/wp-content/plugins/easy-events-for-woocommerce` directory or install the plugin directly through the WordPress plugins screen.
2. Activate the plugin through the 'Plugins' screen in WordPress.
3. Ensure WooCommerce is active.
4. Configure taxonomies and custom fields via the WooCommerce product edit screen.

---

## Changelog

### 1.5.1
**Minor Update**
- Refined REST API integration for enhanced stability.
- Fixed "Invalid Taxonomy" error in specific scenarios.
- Improved support for dynamic dropdowns in the admin interface.
- Additional code refactoring for performance improvements.

### 1.5
- Added REST API integration for event-related data in products and orders.
- Introduced custom fields for event start date, end date, and location.

### 1.4
- Introduced custom taxonomies for event locations and organizers.

---

## Upgrade Notice

**1.5.1**  
This update includes bug fixes and minor enhancements for the REST API and admin interface. Recommended for all users.

---

## Frequently Asked Questions

### How do I assign an event location or organizer to a product?
Use the **Product Categories** and **Event Organizers** panels on the WooCommerce product edit screen.

### Can I access event data via the WooCommerce REST API?
Yes! Event start date, end date, location, and taxonomy data are included in product and order REST API responses.

---

## Support

For support, visit the [GitHub repository](https://github.com/tokyographer).