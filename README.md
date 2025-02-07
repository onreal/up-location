# Up Location Plugin

## Description
The **Up Location Plugin** adds a button to WordPress that allows users to share their location. It saves the location data in cookies and user meta, making it accessible for logged-in users. The plugin also provides a shortcode to display the stored location.

## Features
- Adds a customizable button to get the user's location.
- Stores latitude and longitude in cookies and user meta.
- Provides a shortcode `[up_location]` to display the saved location.
- AJAX-based location storage with nonce verification.
- Customization options for button appearance.

## Installation
1. Upload the plugin folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the "Plugins" menu in WordPress.
3. (Optional) Configure settings under `Settings > Up Location`.

## Usage
### Display the Location Button
The button is automatically added to the footer. Alternatively, you can use the shortcode:
```html
[up_location_display_button]
```

### Display Stored User Location
Use the following shortcode to show the latitude and longitude:
```html
[up_location]
```

## Customization
You can customize the button's appearance using the plugin settings:
- **Color**
- **Size**
- **Text**
- **Position (absolute, relative, fixed)**
- **Border styling**
- **Icon**

## AJAX Integration
The plugin uses AJAX for storing user location. The script `up-location.js` is enqueued and handles location retrieval and submission via:
- `wp_ajax_store_user_location`
- `wp_ajax_nopriv_store_user_location`

## Hooks & Actions
- `wp_enqueue_scripts` - Enqueues required scripts.
- `init` - Registers shortcodes.
- `wp_footer` - Displays the location button.
- `wp_ajax_store_user_location` - Handles AJAX location storage.
- `heartbeat_send` - Sends location data periodically.

## License
This plugin is open-source and released under the **MIT** license.
