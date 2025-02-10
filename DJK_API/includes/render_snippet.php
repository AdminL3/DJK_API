<?php

function convert_to_html($content)
{
    $mannschaft = htmlspecialchars($content['name'] ?? 'Unbekannt');
    $jahrgang = is_array($content['jahrgänge'] ?? null) ? implode(', ', $content['jahrgänge']) : 'Keine Angabe';
    $geschlecht = is_array($content['geschlecht'] ?? null) ? implode(', ', $content['geschlecht']) : 'Keine Angabe';
    $trainer = is_array($content['vornamen_trainer'] ?? null) ? implode(', ', $content['vornamen_trainer']) : 'Keine Trainer vorhanden';
    $notiz = !empty($content['extra_notiz']) ? "<span style='color: #adadad;'>" . htmlspecialchars($content['extra_notiz']) . "</span>" : '';
    // Mannschaftsname formatieren
    $mannschaftsname = ucfirst(str_replace("w", " | Weiblich", str_replace("H", "Herren ", $mannschaft)));
    $hallen = array("Peslmüller", "Guardini", "FvE", "HvP", "usw.");
    // Trainingszeiten formatieren
    $date = [];
    if (!empty($content['trainingszeiten']) && is_array($content['trainingszeiten'])) {
        foreach ($content['trainingszeiten'] as $training) {
            $line = "<strong>";
            $line .= htmlspecialchars($training['tag'] ?? 'Unbekannt') . " | ";
            $line .= htmlspecialchars($training['startzeit'] ?? '??:??') . " - " . htmlspecialchars($training['endzeit'] ?? '??:??');
            $line .= "</strong>";
            $halle = "Halle nicht angegeben";
            if (isset($training['hallenid']) && array_key_exists($training['hallenid'], $hallen)) {
                $halle = $hallen[$training['hallenid']];
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

    // HTML generieren
    $html = "<h2 style='color: #3baa19;'>{$mannschaftsname}</h2>";
    $html .= "<span style='color: #adadad;'>{$jahrgang} | {$geschlecht}</span><br>";
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

    return convert_to_html($content);
}

add_shortcode('snippet', 'render_snippet');
