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
 * Description:       A plugin to check if a phone number is associated with a known TCPA litigator. Using the API from TCPA Litigator List.
 * Version:           1.2.1
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
$plugin_data = get_file_data(__FILE__, ['Version' => 'Version']);
define('TCPA_LITIGATOR_CHECKER_VERSION', $plugin_data['Version']);
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
