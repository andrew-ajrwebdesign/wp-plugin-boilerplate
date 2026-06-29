<?php
/**
 * Plugin Name:  AJR My Plugin
 * Plugin URI:   https://github.com/andrew-ajrwebdesign/wp-plugin-boilerplate
 * Description:  Plugin description.
 * Version:      1.0.0
 * Author:       AJR Web Design
 * Author URI:   https://ajrwebdesign.com
 * License:      GPL-2.0-or-later
 * License URI:  https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:  ajr-my-plugin
 * Domain Path:  /languages
 *
 * @package AJR_My_Plugin
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'AJR_MY_PLUGIN_VERSION', '1.0.0' );
define( 'AJR_MY_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'AJR_MY_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

require_once AJR_MY_PLUGIN_PATH . 'vendor/autoload.php';

add_action(
	'plugins_loaded',
	function () {
		load_plugin_textdomain( 'ajr-my-plugin', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );

		( new AJR\MyPlugin\Core\Plugin() )->init();
	}
);

register_activation_hook(
	__FILE__,
	function () {
		// Set default options, create tables, flush rewrite rules.
		flush_rewrite_rules();
	}
);

register_deactivation_hook(
	__FILE__,
	function () {
		flush_rewrite_rules();
	}
);
