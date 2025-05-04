<?php

/*
 *
 * @link              https://github.com/AdminL3/
 * @since             1.0.0
 * @package           djk_api
 *
 * @wordpress-plugin
 * Plugin Name:       djk_api
 * Plugin URI:        https://github.com/AdminL3/restful-snippets
 * Description:       DJK Plugin to integrate backend with frontend
 * Version:           1.0.0
 * Author:            Levi Blumenwitz
 * Author URI:        https://github.com/AdminL3/
 * License:           GPL-2.0+
 * Text Domain:       djk_api
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

require_once plugin_dir_path( __FILE__ ) . 'includes/menu.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/settings.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/render_snippet.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/receive_snippet.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/receive_locations.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/api.php';


function djk_api_admin_styles($hook) {
    // Check if we're on our plugin page
    if ($hook != 'toplevel_page_djk_api') {
        return;
    }
    
    // Get the plugin directory URL
    $plugin_url = plugin_dir_url(__FILE__);
    
    // Enqueue the stylesheet
    wp_enqueue_style('djk-api-admin-style', $plugin_url . 'assets/style.css', array(), '1.0.0');
}
add_action('admin_enqueue_scripts', 'djk_api_admin_styles');