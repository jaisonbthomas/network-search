<?php
/**
 * Network Search - WordPress Plugin
 *
 * A lightweight WordPress plugin for searching pincodes, cities, and delivery zones
 * via AJAX-powered live search. Supports CSV import with flexible column mapping.
 *
 * @package NetworkSearch
 * @version 1.0.0
 * @author Radweber | Jaison B Thomas
 * @license GPL-2.0+
 *
 * Plugin Name: Network Search
 * Plugin URI: https://github.com/jaisonbthomas/network-search
 * Description: Upload a CSV (Pincode, City, State, Zone, Vendor, OFD TAT, Location) and add a live search via [pincode_checker]. Clean search bar + results table UI.
 * Version: 1.0.0
 * Author: Radweber | Jaison B Thomas
 * License: GPL-2.0+
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: network-search
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('PFL_VERSION', '1.0.0');
define('PFL_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('PFL_PLUGIN_URL', plugin_dir_url(__FILE__));
define('PFL_PLUGIN_BASENAME', plugin_basename(__FILE__));

// Include the main plugin class
require_once PFL_PLUGIN_DIR . 'includes/class-network-search.php';

// Initialize the plugin
Pincode_Finder_Lite::instance();
