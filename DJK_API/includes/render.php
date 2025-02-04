<?php

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
    $html = "<h2 style='color: #3baa19;'>{$mannschaftsname}</h2>";
    $html .= "<span style='color: #adadad;'>{$jahrgang} | {$geschlecht}</span><br>";
    $html .= "Trainer:<strong> {$trainer}</strong><br><br>";
    $html .= implode("<br>", $date) . "<br>";
    
    if ($content["extra-notiz"] != "") {
        $html .= $content["extra-notiz"];
    }
    
    return $html;
}


function render_snippet($atts) {
    $atts = shortcode_atts(array(
        'id' => '',
    ), $atts);
    $snippet_id = $atts['id'];
    // get from database
    $content = get_option("snippet_{$snippet_id}", "{$snippet_id}");

    return convert_to_html($content);
}

add_shortcode('snippet', 'render_snippet');
