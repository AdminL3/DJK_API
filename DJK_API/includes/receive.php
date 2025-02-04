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

function convert_to_html($content)
{
    $mannschaft = htmlspecialchars($content['name'] ?? 'Unbekannt');
    $jahrgang = is_array($content['jahrgänge'] ?? null) ? implode(', ', $content['jahrgänge']) : 'Keine Angabe';
    $geschlecht = is_array($content['geschlecht'] ?? null) ? implode(', ', $content['geschlecht']) : 'Keine Angabe';
    $trainer = is_array($content['vornamen_trainer'] ?? null) ? implode(', ', $content['vornamen_trainer']) : 'Keine Trainer vorhanden';
    
    // Mannschaftsname formatieren
    $mannschaftsname = ucfirst(str_replace("w", " | Weiblich", str_replace("H", "Herren ", $mannschaft)));
    
    // Trainingszeiten formatieren
    $date = [];
    if (!empty($content['trainingszeiten']) && is_array($content['trainingszeiten'])) {
        foreach ($content['trainingszeiten'] as $training) {
            $line = "<strong>" . htmlspecialchars($training['tag'] ?? 'Unbekannt') . ":</strong> ";
            $line .= htmlspecialchars($training['startzeit'] ?? '??:??') . " - " . htmlspecialchars($training['endzeit'] ?? '??:??');
            $line .= " (Halle " . htmlspecialchars($training['hallenid'] ?? '??') . ")";
            if (!empty($training['extra_notiz'])) {
                $line .= " - <em>" . htmlspecialchars($training['extra_notiz']) . "</em>";
            }
            $date[] = $line;
        }
    } else {
        $date[] = "<span style='color: #adadad;'>Noch keine Trainingszeiten vorhanden</span>";
    }
    
    // HTML generieren
    $html = "<h2 color: #3baa19;">{$mannschaftsname}</h2>";
    $html .= "<span style='color: #adadad;'>{$jahrgang} | {$geschlecht}</span><br>";
    $html .= "Trainer:<strong> {$trainer}</strong><br><br>";
    $html .= implode("<br>", $date) . "<br>";
    
    if ($content["extra-notiz"] != "") {
        $html .= $content["extra-notiz"];
    }
    
    return $html;
}



function update_snippet($request)
{
    $headers = getallheaders();
    $provided_token = isset($headers['Authorization']) ? str_replace('Bearer ', '', $headers['Authorization']) : '';

    $stored_token = get_option('wp_api_token', '');

    if ($provided_token !== $stored_token) {
        return new WP_REST_Response(array('status' => 'error', 'message' => 'Unauthorized'), 401);
    }

    $params = $request->get_params();
    $snippet_id = $params['snippet_id'];
    $new_content = convert_to_html($params['content']);
    
    update_option("snippet_{$snippet_id}", $new_content);

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
