<?php
/**
 * Runs only when WordPress uninstalls the plugin (Plugins → Delete).
 * Deactivation hook does NOT run here — this is permanent cleanup only.
 *
 * @package AJR_My_Plugin
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

// Delete plugin options.
delete_option( 'ajr_my_plugin_settings' );

// If the plugin created custom database tables, drop them here:
// global $wpdb;
// $wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}ajr_my_plugin_example" );
