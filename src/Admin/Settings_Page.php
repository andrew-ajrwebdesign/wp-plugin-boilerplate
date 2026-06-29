<?php
/**
 * Plugin settings page (Settings → AJR My Plugin).
 *
 * @package AJR_My_Plugin
 */

namespace AJR\MyPlugin\Admin;

defined( 'ABSPATH' ) || exit;

/**
 * Registers and renders the plugin settings page.
 */
class Settings_Page {

	private const OPTION_KEY   = 'ajr_my_plugin_settings';
	private const MENU_SLUG    = 'ajr-my-plugin';
	private const NONCE_ACTION = 'ajr_my_plugin_save';

	// -------------------------------------------------------------------------
	// Registration
	// -------------------------------------------------------------------------

	/**
	 * Registers all WordPress hooks for this class.
	 *
	 * @return void
	 */
	public function init(): void {
		add_action( 'admin_menu', [ $this, 'register_page' ] );
		add_action( 'admin_init', [ $this, 'register_settings' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_assets' ] );
	}

	/**
	 * Adds the settings page to the Settings menu.
	 *
	 * @return void
	 */
	public function register_page(): void {
		add_options_page(
			esc_html__( 'AJR My Plugin', 'ajr-my-plugin' ),
			esc_html__( 'AJR My Plugin', 'ajr-my-plugin' ),
			'manage_options',
			self::MENU_SLUG,
			[ $this, 'render' ]
		);
	}

	/**
	 * Registers settings, sections, and fields with the WordPress Settings API.
	 *
	 * @return void
	 */
	public function register_settings(): void {
		register_setting(
			self::OPTION_KEY . '_group',
			self::OPTION_KEY,
			[ $this, 'sanitize' ]
		);

		add_settings_section(
			'ajr_my_plugin_general',
			esc_html__( 'General', 'ajr-my-plugin' ),
			null,
			self::MENU_SLUG
		);

		add_settings_field(
			'example_field',
			esc_html__( 'Example Field', 'ajr-my-plugin' ),
			[ $this, 'render_example_field' ],
			self::MENU_SLUG,
			'ajr_my_plugin_general'
		);
	}

	/**
	 * Enqueues admin assets only on this plugin's settings page.
	 *
	 * @param string $hook The current admin page hook suffix.
	 * @return void
	 */
	public function enqueue_assets( string $hook ): void {
		if ( 'settings_page_' . self::MENU_SLUG !== $hook ) {
			return;
		}

		wp_enqueue_style(
			'ajr-my-plugin-admin',
			AJR_MY_PLUGIN_URL . 'assets/css/admin.css',
			[],
			AJR_MY_PLUGIN_VERSION
		);

		wp_enqueue_script(
			'ajr-my-plugin-admin',
			AJR_MY_PLUGIN_URL . 'assets/js/admin.js',
			[],
			AJR_MY_PLUGIN_VERSION,
			true
		);
	}

	// -------------------------------------------------------------------------
	// Field renderers
	// -------------------------------------------------------------------------

	/**
	 * Renders the example text field.
	 *
	 * @return void
	 */
	public function render_example_field(): void {
		$value = self::get_option( 'example_field', '' );
		printf(
			'<input type="text" name="%s[example_field]" value="%s" class="regular-text">
			<p class="description">%s</p>',
			esc_attr( self::OPTION_KEY ),
			esc_attr( $value ),
			esc_html__( 'An example text field. Replace or remove this.', 'ajr-my-plugin' )
		);
	}

	// -------------------------------------------------------------------------
	// Page render
	// -------------------------------------------------------------------------

	/**
	 * Renders the settings page HTML.
	 *
	 * @return void
	 */
	public function render(): void {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'AJR My Plugin', 'ajr-my-plugin' ); ?></h1>

			<form method="post" action="options.php">
				<?php
				settings_fields( self::OPTION_KEY . '_group' );
				do_settings_sections( self::MENU_SLUG );
				submit_button();
				?>
			</form>
		</div>
		<?php
	}

	// -------------------------------------------------------------------------
	// Sanitize
	// -------------------------------------------------------------------------

	/**
	 * Sanitizes submitted settings values before saving to the database.
	 *
	 * @param array $input Raw input from the settings form.
	 * @return array
	 */
	public function sanitize( array $input ): array {
		$clean = [];

		$clean['example_field'] = sanitize_text_field( $input['example_field'] ?? '' );

		return $clean;
	}

	// -------------------------------------------------------------------------
	// Helpers
	// -------------------------------------------------------------------------

	/**
	 * Returns a single setting value with a fallback.
	 *
	 * @param string $key      Option key.
	 * @param mixed  $fallback Value to return if key is not set.
	 * @return mixed
	 */
	public static function get_option( string $key, $fallback = null ) {
		$options = get_option( self::OPTION_KEY, [] );
		return $options[ $key ] ?? $fallback;
	}
}
