<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @since      1.0.0
 *
 * @package    TCPA_Litigator_Checker
 * @subpackage TCPA_Litigator_Checker/public
 */

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Handles public-facing functionality.
 *
 * This class is responsible for:
 * - Loading frontend CSS and JavaScript.
 * - Managing any public-facing functionality.
 *
 * @since      1.0.0
 * @package    TCPA_Litigator_Checker
 * @subpackage TCPA_Litigator_Checker/public
 */
class TCPA_Litigator_Checker_Public
{

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param    string    $plugin_name    The name of the plugin.
	 * @param    string    $version        The version of this plugin.
	 * @since    1.0.0
	 */
	public function __construct($plugin_name, $version)
	{
		$this->plugin_name = $plugin_name;
		$this->version = $version;

		add_action('wp_enqueue_scripts', [$this, 'enqueue_styles']);
		add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts']);
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles()
	{
		wp_enqueue_style(
			$this->plugin_name,
			plugin_dir_url(__FILE__) . 'css/tcpa-litigator-checker-public.css',
			[],
			$this->version,
			'all'
		);
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts()
	{
		wp_enqueue_script(
			$this->plugin_name,
			plugin_dir_url(__FILE__) . 'js/tcpa-litigator-checker-public.js',
			['jquery'],
			$this->version,
			true
		);

		// Localize script to pass AJAX URL and nonce
		wp_localize_script($this->plugin_name, 'tcpaChecker', [
			'ajax_url' => admin_url('admin-ajax.php'),
			'nonce' => wp_create_nonce('tcpa_checker_action'),
		]);
	}
}
