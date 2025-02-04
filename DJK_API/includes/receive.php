<?php

add_action('rest_api_init', function () {
    register_rest_route('djk-api', '/update', array(
        'methods' => 'POST',
        'callback' => 'update_snippet',
        'permission_callback' => function () {
            return true;
        }
    ));
});
add_action('rest_api_init', function () {
    register_rest_route('djk-api', '/delete', array(
        'methods' => 'POST',
        'callback' => 'delete_snippet',
        'permission_callback' => function () {
            return true;
        }
    ));
});



function update_snippet($request)
{
    $headers = getallheaders();
    $provided_token = isset($headers['Authorization']) ? str_replace('Bearer ', '', $headers['Authorization']) : '';

    $stored_token = get_option('wp_api_token', '');

    if ($provided_token !== $stored_token) {
        return new WP_REST_Response(array('status' => 'error', 'message' => 'Unauthorized'), 401);
    }

    $params = $request->get_params();
    $content = $params['content'];

    if (!isset($content['name'])) {
        return new WP_REST_Response(array('status' => 'error', 'message' => 'Content name is missing'), 400);
    }
    
    update_option("snippet_{$content['name']}", $content);

    return array('status' => 'success', 'message' => 'Content updated successfully');
}


function delete_snippet($request)
{
    $headers = getallheaders();
    $provided_token = isset($headers['Authorization']) ? str_replace('Bearer ', '', $headers['Authorization']) : '';

    $stored_token = get_option('wp_api_token', '');

    if ($provided_token !== $stored_token) {
        return new WP_REST_Response(array('status' => 'error', 'message' => 'Unauthorized'), 401);
    }

    $params = $request->get_params();
    $snippet_id = $params['snippet_id'];

    delete_option("snippet_{$snippet_id}");

    return array('status' => 'success', 'message' => 'Content deleted successfully');
}
