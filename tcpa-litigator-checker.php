<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @since             1.0.0
 * @package           TCPA_Litigator_Checker
 *
 * @wordpress-plugin
 * Plugin Name:       TCPA Litigator Checker
 * Description:       A plugin to check if a phone number is associated with a known TCPA litigator. Using the api form TCPA Litigator List.
 * Version:           1.0.0
 * Author:            Noman E Jawad
 * Author URI:        https://www.nomanjawad.dev/
 * License:           GPL-2.0+
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       tcpa-litigator-checker
 * Domain Path:       /languages
 */

// Prevent direct access to the file
if (!defined('WPINC')) {
	die;
}

/**
 * Define Plugin Constants
 */
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
 *
 * @since 1.0.0
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
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_tcpa_litigator_checker()
{
	$plugin = new TCPA_Litigator_Checker();
	$plugin->run();
}
run_tcpa_litigator_checker();


define('TCPA_LITIGATOR_CHECKER_VERSION', get_latest_github_version());

function get_latest_github_version()
{
	$github_api_url = 'https://api.github.com/repos/nomanjawad/tcpa-litigator-checker/releases/latest';

	$response = wp_remote_get($github_api_url, [
		'headers' => ['User-Agent' => 'WordPress']
	]);

	if (is_wp_error($response)) {
		return '1.0.0'; // Fallback to a default version if API fails
	}

	$release_data = json_decode(wp_remote_retrieve_body($response), true);
	return $release_data['tag_name'] ?? '1.0.0'; // Example: "v1.2.0"
}
