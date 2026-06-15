<?php
/**
 * Shared helper methods.
 *
 * @package AlternatePro\Elements
 */

namespace AlternatePro\Elements;

defined( 'ABSPATH' ) || exit;

/**
 * Small utility collection.
 */
final class Helpers {
	/**
	 * Get the current request path without query string.
	 *
	 * @return string
	 */
	public static function current_path() {
		$request_uri = isset( $_SERVER['REQUEST_URI'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '/';
		$path        = wp_parse_url( $request_uri, PHP_URL_PATH );

		if ( ! is_string( $path ) || '' === $path ) {
			return '/';
		}

		return '/' . ltrim( $path, '/' );
	}

	/**
	 * Convert a comma/newline separated ID list to integers.
	 *
	 * @param mixed $value Raw value.
	 * @return int[]
	 */
	public static function parse_id_list( $value ) {
		if ( is_array( $value ) ) {
			$value = implode( ',', $value );
		}

		$value = (string) $value;
		$bits  = preg_split( '/[\s,]+/', $value );
		$ids   = array();

		foreach ( (array) $bits as $bit ) {
			$id = absint( $bit );

			if ( $id > 0 ) {
				$ids[] = $id;
			}
		}

		return array_values( array_unique( $ids ) );
	}

	/**
	 * Convert a comma/newline separated string list to sanitized tokens.
	 *
	 * @param mixed $value Raw value.
	 * @return string[]
	 */
	public static function parse_string_list( $value ) {
		if ( is_array( $value ) ) {
			$value = implode( ',', $value );
		}

		$bits  = preg_split( '/[\r\n,]+/', (string) $value );
		$items = array();

		foreach ( (array) $bits as $bit ) {
			$bit = trim( sanitize_text_field( $bit ) );

			if ( '' !== $bit ) {
				$items[] = $bit;
			}
		}

		return array_values( array_unique( $items ) );
	}

	/**
	 * Prevent direct construction.
	 */
	private function __construct() {}
}
