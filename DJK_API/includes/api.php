<?php

add_action('rest_api_init', function () {
    register_rest_route('djk_api', '/get', array(
        'methods' => 'POST',
        'callback' => 'get',
        'permission_callback' => function () {
            return true;
        }
    ));
});

function get($content) {
    $name = $content["name"];
    $stored_data = get_option("snippet_{$name}", "{$name}");
    
    if (empty($stored_data)) {
        return new WP_REST_Response("This snippet does not exist.", 200);
    }
    
    // Encode with JSON_UNESCAPED_UNICODE to keep special characters intact
    $encoded_data = wp_json_encode($stored_data, JSON_UNESCAPED_UNICODE);
    $response_data = json_decode($encoded_data);
    
    return new WP_REST_Response($response_data, 200);
}
