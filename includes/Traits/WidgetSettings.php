<?php
/**
 * Shared widget setting helpers.
 *
 * @package AlternatePro\Elements
 */

namespace AlternatePro\Elements\Traits;

defined( 'ABSPATH' ) || exit;

/**
 * Provides reusable Elementor widget setting parsing helpers.
 */
trait WidgetSettings {
	/**
	 * Sanitize a choice setting.
	 *
	 * @param mixed    $value Raw value.
	 * @param string[] $allowed Allowed values.
	 * @param string   $fallback Fallback value.
	 * @return string
	 */
	protected function sanitize_choice( $value, array $allowed, $fallback ) {
		$value = sanitize_key( (string) $value );

		return in_array( $value, $allowed, true ) ? $value : $fallback;
	}

	/**
	 * Get an Elementor switcher setting.
	 *
	 * @param array<string,mixed> $settings Widget settings.
	 * @param string              $key Setting key.
	 * @param bool                $fallback Fallback value.
	 * @return bool
	 */
	protected function get_switcher_value( array $settings, $key, $fallback = false ) {
		if ( ! isset( $settings[ $key ] ) ) {
			return $fallback;
		}

		return 'yes' === $settings[ $key ];
	}

	/**
	 * Get a numeric Elementor setting.
	 *
	 * @param array<string,mixed> $settings Widget settings.
	 * @param string              $key Setting key.
	 * @param int                 $fallback Fallback value.
	 * @param int                 $min Minimum value.
	 * @param int                 $max Maximum value.
	 * @return int
	 */
	protected function get_numeric_setting( array $settings, $key, $fallback, $min, $max ) {
		$value = isset( $settings[ $key ] ) ? $settings[ $key ] : null;

		if ( is_array( $value ) && isset( $value['size'] ) ) {
			$value = $value['size'];
		}

		if ( ! is_numeric( $value ) ) {
			$value = $fallback;
		}

		return max( $min, min( $max, absint( $value ) ) );
	}
}
