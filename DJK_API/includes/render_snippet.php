<?php

function convert_to_html($content)
{
    if (!is_array($content)) {
        return "<span style='color: red;'>Fehler: Ungültige Datenstruktur.</span>";
    }
    if (!isset($content['id'])) {
        return "<span style='color: red;'>Fehler: Keine ID im Inhalt gefunden.</span>";
    }
    if ($content['id'] == 'hallen') {
        return render_hallen_snippet($content);
    }
    $mannschaft = htmlspecialchars($content['name'] ?? 'Unbekannt');
    $notiz = !empty($content['extra_notiz']) ? "<span style='color: #adadad;'>" . htmlspecialchars($content['extra_notiz']) . "</span>" : '';
    $hallen = get_option("snippet_hallen", [])['hallen'] ?? [];
    $hallen_namen = array_map(function ($halle) {
        return $halle['name'] ?? 'Unbekannt';
    }, $hallen);

    $date = [];
    if (!empty($content['trainingszeiten']) && is_array($content['trainingszeiten'])) {
        foreach ($content['trainingszeiten'] as $training) {
            if (!is_array($training)) {
                $date[] = "<span style='color: red;'>Fehler: Ungültige Trainingszeit-Daten.</span>";
                continue;
            }
            $line = "<strong>";
            $line .= htmlspecialchars($training['tag'] ?? 'Unbekannt') . " | ";
            $line .= htmlspecialchars($training['startzeit'] ?? '??:??') . " - " . htmlspecialchars($training['endzeit'] ?? '??:??');
            $line .= "</strong>";
            $halle = "Halle nicht angegeben";
            if (isset($training['hallenid']) && array_key_exists($training['hallenid'], $hallen_namen)) {
                $halle = $hallen_namen[$training['hallenid']];
            }
            $line .= " | <a href='https://djksbm.org/hauptverein-neu/hallen#{$halle}'; style='color: #3baa19';>{$halle}</a>";
            if (!empty($training['extra_notiz'])) {
                $line .= " | " . htmlspecialchars($training['extra_notiz']);
            }
            $date[] = $line;
        }
    } else {
        $date[] = "<span style='color: #adadad;'>Noch keine Trainingszeiten vorhanden</span>";
    }
    $geschlecht = htmlspecialchars($content['geschlecht'] ?? 'Gemischt');
    $trainer = '';
    if (!empty($content['vornamen_trainer']) && is_array($content['vornamen_trainer'])) {
        $trainer = htmlspecialchars(implode(', ', $content['vornamen_trainer']));
    } else {
        $trainer = "<span style='color: red;'>Keine Trainerdaten vorhanden</span>";
    }
    $jahrgang = empty($content['jahrgänge']) ? '' : htmlspecialchars($content['jahrgänge'] . " | ");

    $html = "<h2 style='color: #3baa19;'>{$mannschaft}</h2>";
    $html .= "<span style='color: #adadad;'>{$jahrgang}{$geschlecht}</span><br>";
    $html .= "Trainer:<strong> {$trainer}</strong><br><br>";
    $html .= implode("<br>", $date) . "<br>";
    $html .= $notiz;

    return $html;
}

function render_hallen_snippet($content)
{
    if (!is_array($content) || !isset($content['hallen'])) {
        return "<span style='color: red;'>Fehler: Ungültige Hallen-Daten.</span>";
    }
    $storage = $content['storage_location'] ?? '';
    $hallen = $content['hallen'];
    $html = "<h2 style='color: #3baa19;'>Hallen</h2>";
    if (empty($hallen)) {
        return "<span style='color: #adadad;'>Keine Hallen gefunden</span>";
    }
    foreach ($hallen as $halle) {
        if (!is_array($halle)) {
            $html .= "<span style='color: red;'>Fehler: Ungültige Hallen-Information.</span><br>";
            continue;
        }
        $name = htmlspecialchars($halle['name'] ?? 'Unbekannt');
        $adresse = htmlspecialchars($halle['address'] ?? 'Unbekannt');
        $images = $halle['images'] ?? [];
        $html .= "<strong id='{$name}'>{$name}</strong><br>";
        $html .= "{$adresse}<br><br>";
        $html .= "<span style='color: #adadad;'>" . htmlspecialchars($halle['description'] ?? '') . "</span><br>";
        if (!empty($images) && is_array($images)) {
            $html .= "<div style='display: flex; flex-wrap: wrap; gap: 10px;'>";
            foreach ($images as $image) {
                $image_url = $storage . '/' . $image;
                $image_url = esc_url($image_url);
                $image_url = str_replace('\/', '/', $image_url);
                if ($image_url) {
                    $html .= "<img src='{$image_url}' alt='{$name}' style='max-height: 30vh; width: auto;'>";
                }
            }
            $html .= "</div>";
        }
        $html .= "<br>";
    }
    return $html;
}
function render_snippet($atts)
{
    $atts = shortcode_atts(array(
        'id' => '',
    ), $atts);
    $snippet_id = $atts['id'];
    if (empty($snippet_id)) {
        return "<span style='color: red;'>Fehler: Keine Snippet-ID angegeben.</span>";
    }
    // get from database
    $content = get_option("snippet_{$snippet_id}", "{$snippet_id}");

    if (is_array($content)) {
        return convert_to_html($content);
    } else {
        return "<span style='color: red;'>Fehler: Kein Inhalt für das Snippet gefunden.</span>";
    }
}

add_shortcode('snippet', 'render_snippet');
