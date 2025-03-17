<?php

/**
 * Plugin Name:       TCPA Litigator Checker
 * Description:       A plugin to check if a phone number is associated with a known TCPA litigator. Using the API from TCPA Litigator List.
 * Version:           1.2.1
 * Author:            Noman E Jawad
 * Author URI:        https://www.nomanjawad.dev/
 * License:           GPL-2.0+
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       tcpa-litigator-checker
 * Domain Path:       /languages
 */

if (!defined('WPINC')) {
	die;
}

// Define Plugin Constants
define('TCPA_LITIGATOR_CHECKER_VERSION', '1.2.1'); // Manually updated version
define('TCPA_LITIGATOR_CHECKER_DIR', plugin_dir_path(__FILE__));
define('TCPA_LITIGATOR_CHECKER_URL', plugin_dir_url(__FILE__));

/**
 * The code that runs during plugin activation.
 */
function activate_tcpa_litigator_checker()
{
	require_once TCPA_LITIGATOR_CHECKER_DIR . 'includes/class-tcpa-litigator-checker-activator.php';
	TCPA_Litigator_Checker_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 */
function deactivate_tcpa_litigator_checker()
{
	require_once TCPA_LITIGATOR_CHECKER_DIR . 'includes/class-tcpa-litigator-checker-deactivator.php';
	TCPA_Litigator_Checker_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_tcpa_litigator_checker');
register_deactivation_hook(__FILE__, 'deactivate_tcpa_litigator_checker');

/**
 * Include the core plugin class file that initializes the plugin.
 */
require_once TCPA_LITIGATOR_CHECKER_DIR . 'includes/class-tcpa-litigator-checker.php';

/**
 * Add Settings link to the plugin page
 */
function tcpa_litigator_checker_add_settings_link($links)
{
	$settings_link = '<a href="options-general.php?page=tcpa-litigator-checker">Settings</a>';
	array_push($links, $settings_link);
	return $links;
}

add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'tcpa_litigator_checker_add_settings_link');

/**
 * Begins execution of the plugin.
 */
function run_tcpa_litigator_checker()
{
	$plugin = new TCPA_Litigator_Checker();
	$plugin->run();
}
run_tcpa_litigator_checker();
