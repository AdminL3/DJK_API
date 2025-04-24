<?php

function djk_api_settings_page() {
    // Process form submissions
    handle_form_submissions();
    
    // Display the token section
    display_token_section();
    
    // Display snippets table
    display_snippets_table();
}

function handle_form_submissions() {
    // Check if the form is submitted to generate a token
    if (isset($_POST['generate_token'])) {
        // Generate a new random token
        $new_token = bin2hex(random_bytes(16));
        update_option('wp_api_token', $new_token);
        
        // Reload the page to display the new token
        echo '<meta http-equiv="refresh" content="0">';
    }
    
    // Check if a delete request was submitted
    if (isset($_POST['delete_snippet']) && isset($_POST['snippet_id'])) {
        $snippet_id = sanitize_text_field($_POST['snippet_id']);
        delete_option('snippet_' . $snippet_id);
        
        // Reload the page to refresh the table
        echo '<meta http-equiv="refresh" content="0">';
    }
}

function display_token_section() {
    // Get the existing token from the database
    $api_token = get_option('wp_api_token', '');
    ?>
    <h1>DJK API Settings</h1>
    <p>Use the existing Token to access the API or generate a new one if yours has been compromised.</p>

    <form method="post" action="">
        <label for="api_token">Current API Token:</label>
        <input type="text" id="api_token" name="api_token" value="<?php echo esc_attr($api_token); ?>" readonly />
        <input type="submit" name="generate_token" value="Generate New Token" />
    </form>
    <?php
}

function display_snippets_table() {
    global $wpdb;
    $results = $wpdb->get_results("SELECT option_name, option_value FROM {$wpdb->options} WHERE option_name LIKE 'snippet_%'");

    if (empty($results)) {
        echo '<p>No snippet options found in the database.</p>';
        return;
    }
    
    echo '<br><hr>';
    echo '<h2>Snippet Options</h2>';
    echo '<table>';
    echo '<tr>
            <th>Snippet ID</th>
            <th>Team Name</th>
            <th>Trainers</th>
            <th>Actions</th>
            <th>Full Data</th>
          </tr>';
    
    foreach ($results as $row) {
        // Extract the ID from the option_name
        $snippet_id = str_replace('snippet_', '', $row->option_name);
        
        // Unserialize the value
        $value = maybe_unserialize($row->option_value);
        
        echo '<tr>';
        echo '<td>' . esc_html($snippet_id) . '</td>';
        
        if (is_array($value)) {
            // Display name in its own column
            echo '<td>' . esc_html($value['name'] ?? 'N/A') . '</td>';
            
            // Display trainers in their own column
            if (!empty($value['vornamen_trainer']) && is_array($value['vornamen_trainer'])) {
                echo '<td>' . esc_html(implode(', ', $value['vornamen_trainer'])) . '</td>';
            } else {
                echo '<td>No trainers listed</td>';
            }
            
            // Add Delete button
            echo '<td>
                <form method="post" action="" onsubmit="return confirm(\'Are you sure you want to delete this snippet?\');">
                    <input type="hidden" name="snippet_id" value="' . esc_attr($snippet_id) . '">
                    <input type="submit" name="delete_snippet" value="Delete" class="button-delete">
                </form>
            </td>';
            
            // Display full JSON data in the last column
            echo '<td><pre>' . esc_html(json_encode($value, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) . '</pre></td>';
        } else {
            // Handle non-array values
            echo '<td colspan="4">Invalid data format</td>';
        }
        
        echo '</tr>';
    }
    echo '</table>';
}