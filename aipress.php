<?php
/*
Plugin Name: AIPress
Description: This is a plugin that uses OpenAI's GPT-3 and chatGPT models to generate AI-powered content on your WordPress site.
Version: 1.2.2
Author: Kemal YAZICI - PluginPress
Author URI: https://pluginpress.net
License: GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: aipress
*/

// If this file calls directly, abort.

if ( ! defined( 'WPINC' ) ) {
    die;
}
add_action( 'admin_enqueue_scripts', 'load_admin_scripts');
function load_admin_scripts() {
    // Do NOT load it on every admin page, create some logic to only load on your pages
    wp_enqueue_editor();
}
// Basic Defines
$actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
define('AIPRESS_VS','1.2.2');
define('AIPRESS_URL',plugin_dir_url(__FILE__));
define('AIPRESS_FILE', __FILE__);
define('AIPRESS_ROOT',plugin_dir_path(__FILE__));
define('AIPRESS_WP_ROOT',ABSPATH );
define('AIPRESS_NOW_URL', $actual_link);

function aipress_plugin_activate()
{
    // Perform plugin activation tasks here
    // ...

    // Reset the permalinks
    flush_rewrite_rules();
}

register_activation_hook( __FILE__, 'aipress_plugin_activate' );


// Admin Menu
include AIPRESS_ROOT.'source/helpers/menu.php';

//Scripts
include AIPRESS_ROOT.'source/helpers/scripts.php';

//Styles
include AIPRESS_ROOT.'source/helpers/styles.php';

// Apis
include AIPRESS_ROOT.'source/helpers/apis.php';