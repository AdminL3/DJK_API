<?php

function wp_api_menu() {
    add_menu_page('DJK API', 'DJK API', 'manage_options', 'djk_api', 'djk_api_settings_page');
}
add_action('admin_menu', 'wp_api_menu');
