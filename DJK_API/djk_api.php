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
require_once plugin_dir_path( __FILE__ ) . 'includes/api.php';


function wp_api_admin_assets() {
    $plugin_url = plugin_dir_url(__FILE__);
    wp_enqueue_style('wp-api-admin-style', $plugin_url . 'assets/style.css');
}
add_action('admin_enqueue_scripts', 'wp_api_admin_assets');
