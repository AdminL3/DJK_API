<?php

add_action('rest_api_init', function () {
    register_rest_route('djk_api', '/get', array(
        'methods' => 'GET',
        'callback' => 'get',
        'permission_callback' => function () {
            return true;
        }
    ));
});

function get($content) {
    $name = $content["name"];

    $stored_data = get_option("snippet_{$name}", '');
    if (empty($stored_data)) {
        return "This snippet does not exist.";
    }
    $data = json_decode($stored_data, true);
	return $data;
}
 