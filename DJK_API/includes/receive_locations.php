<?php

add_action('rest_api_init', function () {
    register_rest_route('djk-api', '/locations', array(
        'methods' => 'POST',
        'callback' => 'save_locations',
        'permission_callback' => function () {
            return true;
        }
    ));
});

function save_locations($request)
{
    $headers = getallheaders();
    $provided_token = isset($headers['Authorization']) ? str_replace('Bearer ', '', $headers['Authorization']) : '';

    $stored_token = get_option('wp_api_token', '');

    if ($provided_token !== $stored_token) {
        return new WP_REST_Response(array('status' => 'error', 'message' => 'Unauthorized'), 401);
    }

    $params = $request->get_params();
    $content = $params['content'];

    update_option("snippet_hallen", $content);

    return array('status' => 'success', 'message' => 'Locations updated successfully');
}