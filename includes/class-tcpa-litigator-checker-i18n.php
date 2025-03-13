<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://yourwebsite.com
 * @since      1.0.0
 *
 * @package    TCPA_Litigator_Checker
 * @subpackage TCPA_Litigator_Checker/includes
 */

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Handles internationalization for the plugin.
 *
 * This class loads the plugin's text domain to enable translations.
 *
 * @since      1.0.0
 * @package    TCPA_Litigator_Checker
 * @subpackage TCPA_Litigator_Checker/includes
 */
class TCPA_Litigator_Checker_i18n
{

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain()
	{
		load_plugin_textdomain(
			'tcpa-litigator-checker',
			false,
			dirname(dirname(plugin_basename(__FILE__))) . '/languages/'
		);
	}
}
