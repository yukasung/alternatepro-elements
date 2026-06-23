<?php
/**
 * Settings repository.
 *
 * @package AlternatePro\Elements
 */

namespace AlternatePro\Elements\Settings;

defined( 'ABSPATH' ) || exit;

/**
 * Reads and writes plugin settings.
 */
final class SettingsRepository {
	/**
	 * Main settings option.
	 */
	public const OPTION = 'apro_elements_settings';

	/**
	 * Sanitizer.
	 *
	 * @var SettingsSanitizer
	 */
	private $sanitizer;

	/**
	 * Constructor.
	 *
	 * @param SettingsSanitizer|null $sanitizer Sanitizer.
	 */
	public function __construct( ?SettingsSanitizer $sanitizer = null ) {
		$this->sanitizer = $sanitizer ? $sanitizer : new SettingsSanitizer();
	}

	/**
	 * Get default settings.
	 *
	 * @return array<string,mixed>
	 */
	public function defaults() {
		return array(
			'modules' => array(
				'header_footer' => true,
			),
			'widgets' => array(
				'site_logo'            => true,
				'site_title'           => true,
				'nav_menu'             => false,
				'search_form'          => true,
				'hero_section'         => true,
				'call_to_action'       => true,
				'image_box'            => true,
				'image_carousel'       => true,
				'slides'               => true,
				'icon_box'             => true,
				'team_member'          => true,
				'testimonial_carousel' => false,
				'posts'                => false,
				'breadcrumbs'          => true,
			),
		);
	}

	/**
	 * Ensure settings exist.
	 *
	 * @return void
	 */
	public function ensure_defaults() {
		if ( false === get_option( self::OPTION, false ) ) {
			add_option( self::OPTION, $this->defaults(), '', false );

			return;
		}

		$this->save( $this->get() );
	}

	/**
	 * Get merged settings.
	 *
	 * @return array<string,mixed>
	 */
	public function get() {
		$stored = get_option( self::OPTION, array() );
		$merged = $this->merge_recursive_distinct( $this->defaults(), is_array( $stored ) ? $stored : array() );

		return $this->sanitizer->sanitize( $merged );
	}

	/**
	 * Save settings.
	 *
	 * @param mixed $settings Raw settings.
	 * @return bool
	 */
	public function save( $settings ) {
		return update_option( self::OPTION, $this->sanitizer->sanitize( $settings ), false );
	}

	/**
	 * Check whether a module is enabled.
	 *
	 * @param string $module Module key.
	 * @return bool
	 */
	public function is_module_enabled( $module ) {
		$settings = $this->get();
		$module   = sanitize_key( $module );

		return ! empty( $settings['modules'][ $module ] );
	}

	/**
	 * Check whether a widget is enabled.
	 *
	 * @param string $widget Widget key.
	 * @return bool
	 */
	public function is_widget_enabled( $widget ) {
		$settings = $this->get();
		$widget   = sanitize_key( $widget );

		return ! empty( $settings['widgets'][ $widget ] );
	}

	/**
	 * Merge stored settings into defaults while preserving default keys.
	 *
	 * @param array<string,mixed> $defaults Default settings.
	 * @param array<string,mixed> $stored Stored settings.
	 * @return array<string,mixed>
	 */
	private function merge_recursive_distinct( array $defaults, array $stored ) {
		foreach ( $stored as $key => $value ) {
			if ( is_array( $value ) && isset( $defaults[ $key ] ) && is_array( $defaults[ $key ] ) ) {
				$defaults[ $key ] = $this->merge_recursive_distinct( $defaults[ $key ], $value );
				continue;
			}

			if ( array_key_exists( $key, $defaults ) ) {
				$defaults[ $key ] = $value;
			}
		}

		return $defaults;
	}
}
