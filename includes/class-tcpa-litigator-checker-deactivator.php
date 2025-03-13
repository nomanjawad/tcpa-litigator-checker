<?php

/**
 * Fired during plugin deactivation.
 *
 * @since      1.0.0
 * @package    TCPA_Litigator_Checker
 * @subpackage TCPA_Litigator_Checker/includes
 */

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Handles actions performed during plugin deactivation.
 *
 * @since      1.0.0
 * @package    TCPA_Litigator_Checker
 * @subpackage TCPA_Litigator_Checker/includes
 */
class TCPA_Litigator_Checker_Deactivator
{

	/**
	 * Code that runs during plugin deactivation.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate()
	{
		// Flush rewrite rules (in case custom URLs were registered)
		flush_rewrite_rules();
	}
}
