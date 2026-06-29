<?php
/**
 * Main plugin controller.
 *
 * Instantiated once in the bootstrap and never again. All dependencies are
 * wired here and passed as constructor arguments — no globals, no singletons.
 *
 * @package AJR_My_Plugin
 * @since   1.0.0
 */

namespace AJR\MyPlugin\Core;

use AJR\MyPlugin\Admin\Settings_Page;

defined( 'ABSPATH' ) || exit;

/**
 * Wires all plugin classes together and calls their init methods.
 *
 * @since 1.0.0
 */
class Plugin {

	/**
	 * Settings page instance.
	 *
	 * @since 1.0.0
	 * @var Settings_Page
	 */
	private Settings_Page $settings_page;

	/**
	 * Constructor — instantiate all dependencies.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->settings_page = new Settings_Page();
	}

	/**
	 * Register all hooks by calling init() on each dependency.
	 *
	 * @since  1.0.0
	 * @return void
	 */
	public function init(): void {
		$this->settings_page->init();

		// Wire additional classes here as the plugin grows.
		// $this->some_feature = new Feature\My_Feature( $this->settings_page );
		// $this->some_feature->init();
	}
}
