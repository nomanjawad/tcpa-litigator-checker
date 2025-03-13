<?php

/**
 * Register all actions and filters for the plugin
 *
 * @since      1.0.0
 *
 * @package    TCPA_Litigator_Checker
 * @subpackage TCPA_Litigator_Checker/includes
 */

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Registers all actions and filters for the plugin.
 *
 * This class manages:
 * - Maintaining an array of all registered actions and filters.
 * - Registering them with the WordPress API when `run()` is called.
 *
 * @since      1.0.0
 * @package    TCPA_Litigator_Checker
 * @subpackage TCPA_Litigator_Checker/includes
 */
class TCPA_Litigator_Checker_Loader
{

	/**
	 * The array of actions registered with WordPress.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      array    $actions    List of actions to register.
	 */
	protected $actions;

	/**
	 * The array of filters registered with WordPress.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      array    $filters    List of filters to register.
	 */
	protected $filters;

	/**
	 * Initialize collections for actions and filters.
	 *
	 * @since    1.0.0
	 */
	public function __construct()
	{
		$this->actions = [];
		$this->filters = [];
	}

	/**
	 * Add a new action to be registered with WordPress.
	 *
	 * @since    1.0.0
	 * @param    string    $hook          The WordPress action hook name.
	 * @param    object    $component     Reference to the class where the method is defined.
	 * @param    string    $callback      Method name in the component.
	 * @param    int       $priority      Priority level (default: 10).
	 * @param    int       $accepted_args Number of arguments passed (default: 1).
	 */
	public function add_action($hook, $component, $callback, $priority = 10, $accepted_args = 1)
	{
		$this->actions = $this->add_hook($this->actions, $hook, $component, $callback, $priority, $accepted_args);
	}

	/**
	 * Add a new filter to be registered with WordPress.
	 *
	 * @since    1.0.0
	 * @param    string    $hook          The WordPress filter hook name.
	 * @param    object    $component     Reference to the class where the method is defined.
	 * @param    string    $callback      Method name in the component.
	 * @param    int       $priority      Priority level (default: 10).
	 * @param    int       $accepted_args Number of arguments passed (default: 1).
	 */
	public function add_filter($hook, $component, $callback, $priority = 10, $accepted_args = 1)
	{
		$this->filters = $this->add_hook($this->filters, $hook, $component, $callback, $priority, $accepted_args);
	}

	/**
	 * Utility function to register actions and filters in a single collection.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @param    array     $hooks         The array of hooks to register.
	 * @param    string    $hook          The WordPress hook name.
	 * @param    object    $component     Reference to the class where the method is defined.
	 * @param    string    $callback      Method name in the component.
	 * @param    int       $priority      Priority level.
	 * @param    int       $accepted_args Number of arguments passed.
	 * @return   array                     The updated hooks array.
	 */
	private function add_hook($hooks, $hook, $component, $callback, $priority, $accepted_args)
	{
		$hooks[] = [
			'hook'          => $hook,
			'component'     => $component,
			'callback'      => $callback,
			'priority'      => $priority,
			'accepted_args' => $accepted_args
		];
		return $hooks;
	}

	/**
	 * Register the filters and actions with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run()
	{
		foreach ($this->filters as $hook) {
			add_filter($hook['hook'], [$hook['component'], $hook['callback']], $hook['priority'], $hook['accepted_args']);
		}

		foreach ($this->actions as $hook) {
			add_action($hook['hook'], [$hook['component'], $hook['callback']], $hook['priority'], $hook['accepted_args']);
		}
	}
}
