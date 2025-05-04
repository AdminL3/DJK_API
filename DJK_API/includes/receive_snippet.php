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
    
    if (!empty($content['id'])) {
        if($content['action'] == 'delete') {
            delete_option("snippet_{$content['id']}");
            return array('status' => 'success', 'message' => 'Snippet deleted!');
        }
        update_option("snippet_{$content['id']}", $content);
        return array('status' => 'success', 'message' => 'Content received!');
    } else {
        return new WP_REST_Response(array('status' => 'error', 'message' => 'Invalid content'), 400);
    }
}