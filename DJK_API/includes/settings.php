<?php

function djk_api_settings_page() {
    // Get the existing token from the database
    $api_token = get_option('wp_api_token', '');
    ?>
    <h1>DJK API Settings</h1>
    <p>Use the existing Token to access the API</p>
    <p>or generate a new one if your's has been compromised.</p>

    <form method="post" action="">
        <label for="api_token">Current API Token:</label>
        <input type="text" id="api_token" name="api_token" value="<?php echo esc_attr($api_token); ?>" readonly /><br>
        <input type="submit" name="generate_token" value="Generate New Token" />
    </form>
    <?php

    // Check if the form is submitted to generate a token
    if (isset($_POST['generate_token'])) {
        // Generate a new random token
        $new_token = bin2hex(random_bytes(16));
        update_option('wp_api_token', $new_token);

        // Reload the page to display the new token
        echo '<meta http-equiv="refresh" content="0">';
    }
}