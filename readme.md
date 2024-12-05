Here is the updated readme.md in markdown format with the version updated to 1.3.1:

# Easy Events for WooCommerce

**Contributors:** [tokyographer](https://github.com/tokyographer)  
**Author:** Anthony T.  
**Tags:** WooCommerce, Events, Products, API Integration, Taxonomy  
**Requires at least:** 5.6  
**Tested up to:** 6.3  
**Stable tag:** 1.3.1  
**Requires PHP:** 7.4  
**License:** GPLv2 or later  
**License URI:** https://www.gnu.org/licenses/gpl-2.0.html  

---

## Description

Transform WooCommerce into a complete event management system. With **Easy Events for WooCommerce**, you can create and manage events directly as WooCommerce products, complete with dedicated fields for metadata such as event dates and locations. Fully integrated with WooCommerce's admin and API functionalities.

### Features

- **Custom Product Type**:  
  Add events as WooCommerce products with a dedicated `Event` product type.

- **Event Metadata**:  
  Easily manage start and end dates, and assign locations using the `event_location` and `event_organizers` taxonomies.

- **Product Import/Export Enhancements**:  
  - Map and import/export event-specific metadata (`event_start_date`, `event_end_date`, `event_organizer`, and `event_location`) in WooCommerce's built-in import/export functionality.

- **API Integration**:  
  - Extend WooCommerce API for products and orders to include event-specific data.
  - Retrieve categories, event metadata, and locations in WooCommerce REST API responses.

- **Admin Panel Enhancements**:  
  - Dropdown selector for `event_location` in the product editor.
  - Event-specific columns (start date, end date, location) in the product list table.
  - Selection of organizers per event.

---

## Installation

1. Upload the plugin folder to `/wp-content/plugins/`.
2. Activate the plugin via the **Plugins** menu in WordPress.
3. Configure your event products and start assigning event locations.

---

## Usage

1. **Creating an Event Product**:  
   - Navigate to WooCommerce → Products → Add New.
   - Select `Event` as the product type and enter the event metadata (start date, end date, and location).

2. **Assigning Event Locations**:  
   - Use the `Event Location` taxonomy dropdown to classify your events.

3. **Using Product Import/Export**:  
   - During import or export of products, map the fields `Event Start Date`, `Event End Date`, `Event Organizer`, and `Event Location`.

4. **Using WooCommerce API**:  
   - Fetch event and product details using WooCommerce REST API:
     - **Product API**: `/wp-json/wc/v3/products/<product_id>`
     - **Order API**: `/wp-json/wc/v3/orders/<order_id>`

---

## Changelog

### 1.3.1
- Updated the plugin to add seamless support for WooCommerce product import/export mappings.

### 1.2.0
- Added product import/export support for event-specific metadata:
  - `Event Start Date`
  - `Event End Date`
  - `Event Organizer`
  - `Event Location`

### 1.0.0
- Production release.
- Introduced custom product type: Event.
- Added event metadata fields (start date, end date, location).
- Integrated `event_location` taxonomy for events.
- Enhanced WooCommerce API to include event and category data in products and orders.
- Admin UI enhancements: product columns for event data, location dropdown.

---

## Frequently Asked Questions

### Q: Can I add custom metadata fields for events?  
A: The plugin provides fields for event dates, organizers, and locations. You can extend it with custom code to add more fields.

### Q: Does this plugin affect standard WooCommerce products?  
A: No, it only adds functionality for the `Event` product type.

### Q: How do I retrieve event data via the WooCommerce API?  
A: Event metadata is automatically included in the WooCommerce API for products and orders.

---

## License

This plugin is licensed under GPLv2 or later. See the [LICENSE](https://www.gnu.org/licenses/gpl-2.0.html) file for details.

---

## Contributors

**Anthony T.**  
GitHub: [tokyographer](https://github.com/tokyographer)

This reflects the updated version and the feature details. Let me know if you need further modifications!