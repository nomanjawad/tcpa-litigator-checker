<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @since      1.0.0
 *
 * @package    TCPA_Litigator_Checker
 * @subpackage TCPA_Litigator_Checker/admin
 */

if (!defined('ABSPATH')) {
	exit;
}

/**
 * The admin-specific functionality of the plugin.
 *
 * This class is responsible for:
 * - Enqueueing styles and scripts in the admin panel.
 * - Adding the plugin settings menu.
 * - Registering settings for API key management.
 *
 * @since      1.0.0
 * @package    TCPA_Litigator_Checker
 * @subpackage TCPA_Litigator_Checker/admin
 */
class TCPA_Litigator_Checker_Admin
{

	/**
	 * The plugin name.
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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version           The version of this plugin.
	 * @since    1.0.0
	 */
	public function __construct($plugin_name, $version)
	{
		$this->plugin_name = $plugin_name;
		$this->version = $version;

		add_action('admin_menu', [$this, 'add_plugin_menu']);
		add_action('admin_init', [$this, 'register_plugin_settings']);
		add_action('admin_enqueue_scripts', [$this, 'enqueue_styles']);
		add_action('admin_enqueue_scripts', [$this, 'enqueue_scripts']);

		// ADD ADMIN NOTICE FOR UPDATES
		add_action('admin_notices', [$this, 'tcpa_checker_admin_update_notice']);
	}

	/**
	 * Add settings page to WordPress admin menu.
	 *
	 * @since    1.0.0
	 */
	public function add_plugin_menu()
	{
		add_options_page(
			'TCPA Litigator Checker Settings', // Page title
			'TCPA Checker', // Menu item name
			'manage_options', // Capability
			'tcpa-litigator-checker', // Menu slug
			[$this, 'render_settings_page'] // Function to render the page
		);
	}

	/**
	 * Register plugin settings.
	 *
	 * @since    1.0.0
	 */
	public function register_plugin_settings()
	{
		// Api Settings
		register_setting('tcpa_checker_settings_group', 'tcpa_checker_api_url');
		register_setting('tcpa_checker_settings_group', 'tcpa_checker_api_key');

		// Style Settings
		register_setting('tcpa_checker_settings_group', 'tcpa_checker_button_color');
		register_setting('tcpa_checker_settings_group', 'tcpa_checker_button_font_color');
		register_setting('tcpa_checker_settings_group', 'tcpa_checker_label_font_size');
	}

	/**
	 * Render the settings page in the admin panel.
	 *
	 * @since    1.0.0
	 */
	public function render_settings_page()
	{
?>
		<div class="wrap">
			<h1>TCPA Litigator Checker Settings</h1>
			<form method="post" action="options.php">
				<?php settings_fields('tcpa_checker_settings_group'); ?>
				<?php do_settings_sections('tcpa_checker_settings_group'); ?>
				<table class="form-table">
					<!-- Api Settings -->
					<tr valign="top">
						<th scope="row">API URL</th>
						<td><input type="text" name="tcpa_checker_api_url" value="<?php echo esc_attr(get_option('tcpa_checker_api_url')); ?>" style="width: 400px;"></td>
					</tr>
					<tr valign="top">
						<th scope="row">API Key</th>
						<td><input type="password" name="tcpa_checker_api_key" value="<?php echo esc_attr(get_option('tcpa_checker_api_key')); ?>" style="width: 400px;"></td>
					</tr>

					<!-- Style Settings -->
					<tr valign="top">
						<th scope="row">Check Button Color</th>
						<td><input type="color" name="tcpa_checker_button_color" value="<?php echo esc_attr(get_option('tcpa_checker_button_color', '#007bff')); ?>"></td>
					</tr>
					<tr valign="top">
						<th scope="row">Check Button Font Color</th>
						<td><input type="color" name="tcpa_checker_button_font_color" value="<?php echo esc_attr(get_option('tcpa_checker_button_font_color', '#007bff')); ?>"></td>
					</tr>
					<tr valign="top">
						<th scope="row">Label Font Size (px)</th>
						<td><input type="number" name="tcpa_checker_label_font_size" value="<?php echo esc_attr(get_option('tcpa_checker_label_font_size', '14')); ?>" min="10" max="30"> px</td>
					</tr>
					<tr valign="top">
						<th scope="row">Shortcode To Display The Form</th>
						<td>[tcpa-checker-form]</td>
					</tr>
				</table>
				<?php submit_button(); ?>
			</form>

			<!-- Display entered values for testing -->
			<!-- Display entered values for testing -->
			<!-- <h2>Testing Output</h2>
			<p><strong>Saved API URL:</strong> <?php //echo esc_html(get_option('tcpa_checker_api_url')); 
												?></p>
			<p><strong>Saved API Key:</strong> <?php //echo esc_html(get_option('tcpa_checker_api_key')); 
												?></p> -->

			<div class="notice notice-success">
				<p><strong>New Feature:</strong> This is a test update from GitHub!</p>
			</div>

		</div>

<?php
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles()
	{
		wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/tcpa-litigator-checker-admin.css', [], $this->version, 'all');
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts()
	{
		wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/tcpa-litigator-checker-admin.js', ['jquery'], $this->version, true);
	}

	/**
	 * Show admin notice when an update is available
	 */
	public function tcpa_checker_admin_update_notice()
	{
		// Get current plugin version
		$plugin_data = get_plugin_data(WP_PLUGIN_DIR . '/tcpa-litigator-checker/tcpa-litigator-checker.php');
		$current_version = $plugin_data['Version'];

		// Get the defined version in the plugin
		$latest_version = TCPA_LITIGATOR_CHECKER_VERSION;

		// Check if the installed version is older
		if (version_compare($current_version, $latest_version, '<')) {
			echo '<div class="notice notice-warning"><p><strong>TCPA Litigator Checker</strong>: A new update is available! Please update to the latest version.</p></div>';
		}
	}
}
