<?php

/**
 * Fired during plugin activation.
 *
 * @since      1.0.0
 * @package    TCPA_Litigator_Checker
 * @subpackage TCPA_Litigator_Checker/includes
 */

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Handles actions performed during plugin activation.
 *
 * @since      1.0.0
 * @package    TCPA_Litigator_Checker
 * @subpackage TCPA_Litigator_Checker/includes
 */
class TCPA_Litigator_Checker_Activator
{

	/**
	 * Code that runs during plugin activation.
	 *
	 * @since    1.0.0
	 */
	public static function activate()
	{
		// Set default API options if they are not set
		if (get_option('tcpa_checker_api_url') === false) {
			update_option('tcpa_checker_api_url', '');
		}

		if (get_option('tcpa_checker_api_key') === false) {
			update_option('tcpa_checker_api_key', '');
		}

		// Flush rewrite rules to prevent potential permalink issues
		flush_rewrite_rules();
	}
}
