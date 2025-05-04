<?php

function convert_to_html($content)
{
    $mannschaft = htmlspecialchars($content['name'] ?? 'Unbekannt');
    $notiz = !empty($content['extra_notiz']) ? "<span style='color: #adadad;'>" . htmlspecialchars($content['extra_notiz']) . "</span>" : '';
    $hallen = get_option("snippet_hallen", [])['hallen'] ?? [];
    $hallen_namen = array_map(function($halle) {
        return $halle['name'] ?? 'Unbekannt';
    }, $hallen);
    // return json_encode($content['trainingszeiten']);
    if (!empty($content['trainingszeiten']) && is_array($content['trainingszeiten'])) {
        foreach ($content['trainingszeiten'] as $training) {
            $line = "<strong>";
            $line .= htmlspecialchars($training['tag'] ?? 'Unbekannt') . " | ";
            $line .= htmlspecialchars($training['startzeit'] ?? '??:??') . " - " . htmlspecialchars($training['endzeit'] ?? '??:??');
            $line .= "</strong>";
            $halle = "Halle nicht angegeben";
            if (isset($training['hallenid']) && array_key_exists($training['hallenid'], $hallen_namen)) {
                $halle = $hallen_namen[$training['hallenid']];
            }
            $line .= " | <a href='https://djksbm.org/hallen/{$halle}'; style='color: #3baa19';>{$halle}</a>";
            if (!empty($training['extra_notiz'])) {
                $line .= " | " . htmlspecialchars($training['extra_notiz']);
            }
            $date[] = $line;
        }
    } else {
        $date[] = "<span style='color: #adadad;'>Noch keine Trainingszeiten vorhanden</span>";
    }
    $geschlecht = htmlspecialchars($content['geschlecht'] ?? 'Gemischt');
    $trainer = htmlspecialchars(implode(', ', $content['vornamen_trainer']));
    $jahrgang = empty($content['jahrgänge']) ? '' : htmlspecialchars($content['jahrgänge'] . " | ");
    
    $html = "<h2 style='color: #3baa19;'>{$mannschaft}</h2>";
    $html .= "<span style='color: #adadad;'>{$jahrgang}{$geschlecht}</span><br>";
    $html .= "Trainer:<strong> {$trainer}</strong><br><br>";
    $html .= implode("<br>", $date) . "<br>";
    $html .= $notiz;
    
    return $html;
}


function render_snippet($atts) {
    $atts = shortcode_atts(array(
        'id' => '',
    ), $atts);
    $snippet_id = $atts['id'];
    // get from database
    $content = get_option("snippet_{$snippet_id}", "{$snippet_id}");

    if (is_array($content)) {
        return convert_to_html($content);
    } else {
        return "";
    }
}

add_shortcode('snippet', 'render_snippet');
