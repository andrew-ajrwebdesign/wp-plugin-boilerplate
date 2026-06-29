<?php
/**
 * Unit tests for Settings_Page.
 *
 * @package AJR_My_Plugin
 */

namespace AJR\MyPlugin\Tests\Unit\Admin;

use AJR\MyPlugin\Admin\Settings_Page;
use WP_Mock\Tools\TestCase;
use WP_Mock;

/**
 * Tests for the Settings_Page class.
 *
 * @since 1.0.0
 */
class Settings_PageTest extends TestCase {

	/**
	 * Confirms that init() registers the three expected admin hooks.
	 *
	 * @since 1.0.0
	 */
	public function test_init_registers_expected_hooks(): void {
		$page = new Settings_Page();

		WP_Mock::expectActionAdded( 'admin_menu', [ $page, 'register_page' ] );
		WP_Mock::expectActionAdded( 'admin_init', [ $page, 'register_settings' ] );
		WP_Mock::expectActionAdded( 'admin_enqueue_scripts', [ $page, 'enqueue_assets' ] );

		$page->init();

		$this->assertHooksAdded();
	}

	/**
	 * Confirms that get_option() returns the fallback when the key is absent.
	 *
	 * @since 1.0.0
	 */
	public function test_get_option_returns_fallback_when_key_missing(): void {
		WP_Mock::userFunction( 'get_option' )
			->once()
			->with( 'ajr_my_plugin_settings', [] )
			->andReturn( [] );

		$result = Settings_Page::get_option( 'missing_key', 'default_value' );

		$this->assertSame( 'default_value', $result );
	}

	/**
	 * Confirms that sanitize() passes example_field through sanitize_text_field().
	 *
	 * @since 1.0.0
	 */
	public function test_sanitize_cleans_example_field(): void {
		WP_Mock::userFunction( 'sanitize_text_field' )
			->once()
			->with( 'raw input' )
			->andReturn( 'raw input' );

		$page   = new Settings_Page();
		$result = $page->sanitize( [ 'example_field' => 'raw input' ] );

		$this->assertArrayHasKey( 'example_field', $result );
	}
}
