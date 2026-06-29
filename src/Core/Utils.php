<?php
/**
 * Static utility helpers shared across the plugin.
 *
 * @package AJR_My_Plugin
 */

namespace AJR\MyPlugin\Core;

defined( 'ABSPATH' ) || exit;

/**
 * Stateless helper methods used throughout the plugin.
 */
class Utils {

	/**
	 * Masks all but the last 4 characters of a sensitive string.
	 *
	 * @param string $value The string to mask.
	 * @return string
	 */
	public static function mask_sensitive_text( string $value ): string {
		$length = strlen( $value );

		if ( $length <= 4 ) {
			return str_repeat( '*', $length );
		}

		return str_repeat( '*', $length - 4 ) . substr( $value, -4 );
	}

	/**
	 * Strips HTML tags, collapses whitespace, and trims a string.
	 *
	 * @param string $text Raw text or HTML.
	 * @return string
	 */
	public static function clean_plain_text( string $text ): string {
		$text = wp_strip_all_tags( $text );
		$text = preg_replace( '/\s+/', ' ', $text );
		return trim( $text );
	}
}
