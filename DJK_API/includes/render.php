<?php

function render_snippet($atts) {
    $atts = shortcode_atts(array(
        'id' => '',
    ), $atts);
    $snippet_id = $atts['id'];
    // get from database
    $content = get_option("snippet_{$snippet_id}", "{$snippet_id}");
    return $content;
}

add_shortcode('snippet', 'render_snippet');
