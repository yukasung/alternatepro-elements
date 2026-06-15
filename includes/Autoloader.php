<?php
/**
 * PSR-4 style autoloader for AlternatePro Elements.
 *
 * @package AlternatePro\Elements
 */

namespace AlternatePro\Elements;

defined( 'ABSPATH' ) || exit;

/**
 * Loads plugin classes from the includes directory.
 */
final class Autoloader {
	/**
	 * Namespace prefix.
	 */
	private const PREFIX = 'AlternatePro\\Elements\\';

	/**
	 * Register the autoloader.
	 *
	 * @return void
	 */
	public static function register() {
		spl_autoload_register( array( __CLASS__, 'autoload' ) );
	}

	/**
	 * Autoload a class.
	 *
	 * @param string $class_name Class name.
	 * @return void
	 */
	private static function autoload( $class_name ) {
		if ( 0 !== strpos( $class_name, self::PREFIX ) ) {
			return;
		}

		$relative = substr( $class_name, strlen( self::PREFIX ) );
		$file     = self::class_to_file( $relative );

		if ( $file && file_exists( $file ) ) {
			require_once $file;
		}
	}

	/**
	 * Resolve a relative class name to a file path.
	 *
	 * @param string $relative Relative class name.
	 * @return string
	 */
	private static function class_to_file( $relative ) {
		$parts = explode( '\\', $relative );

		if ( isset( $parts[0], $parts[1] ) && 'Modules' === $parts[0] ) {
			$module    = self::kebab_case( $parts[1] );
			$remaining = array_slice( $parts, 2 );

			return APRO_ELEMENTS_PATH . 'includes/modules/' . $module . '/' . implode( '/', $remaining ) . '.php';
		}

		return APRO_ELEMENTS_PATH . 'includes/' . str_replace( '\\', '/', $relative ) . '.php';
	}

	/**
	 * Convert a class segment to a module folder name.
	 *
	 * @param string $value Class segment.
	 * @return string
	 */
	private static function kebab_case( $value ) {
		$value = preg_replace( '/(?<!^)[A-Z]/', '-$0', $value );

		return strtolower( $value );
	}
}
