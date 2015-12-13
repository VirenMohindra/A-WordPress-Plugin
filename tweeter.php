<?php
/*
   Plugin Name: Tweeter
   Plugin URI: http://wordpress.org/extend/plugins/tweeter/
   Version: 1.0
   Author: Viren Mohindra | http://virenmohindra.me/
   Description: Tweet specific highlights of your article post
   Text Domain: tweeter
   License: GPLv3
  */

$Tweeter_minimalRequiredPhpVersion = '5.0';

/**
 * Check the PHP version and give a useful error message if the user's version is less than the required version
 * @return boolean true if version check passed. If false, triggers an error which WP will handle, by displaying
 * an error message on the Admin page
 */
function Tweeter_noticePhpVersionWrong() {
    global $Tweeter_minimalRequiredPhpVersion;
    echo '<div class="updated fade">' .
      __('Error: plugin "Tweeter" requires a newer version of PHP to be running.',  'tweeter').
            '<br/>' . __('Minimal version of PHP required: ', 'tweeter') . '<strong>' . $Tweeter_minimalRequiredPhpVersion . '</strong>' .
            '<br/>' . __('Your server\'s PHP version: ', 'tweeter') . '<strong>' . phpversion() . '</strong>' .
         '</div>';
}


function Tweeter_PhpVersionCheck() {
    global $Tweeter_minimalRequiredPhpVersion;
    if (version_compare(phpversion(), $Tweeter_minimalRequiredPhpVersion) < 0) {
        add_action('admin_notices', 'Tweeter_noticePhpVersionWrong');
        return false;
    }
    return true;
}

/**
 * Initialize internationalization (i18n) for this plugin.
 * References:
 *      http://codex.wordpress.org/I18n_for_WordPress_Developers
 *      http://www.wdmac.com/how-to-create-a-po-language-translation#more-631
 * @return void
 */
function Tweeter_i18n_init() {
    $pluginDir = dirname(plugin_basename(__FILE__));
    load_plugin_textdomain('tweeter', false, $pluginDir . '/languages/');
}


//////////////////////////////////
// Run initialization
/////////////////////////////////

// Initialize i18n
add_action('plugins_loadedi','Tweeter_i18n_init');

// Run the version check.
// If it is successful, continue with initialization for this plugin
if (Tweeter_PhpVersionCheck()) {
    // Only load and run the init function if we know PHP version can parse it
    include_once('tweeter_init.php');
    Tweeter_init(__FILE__);
}
