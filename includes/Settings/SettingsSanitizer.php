<?php
/**
 * Settings sanitization.
 *
 * @package AlternatePro\Elements
 */

namespace AlternatePro\Elements\Settings;

defined( 'ABSPATH' ) || exit;

/**
 * Sanitizes plugin settings through allowlists.
 */
final class SettingsSanitizer {
	/**
	 * Allowed module keys.
	 *
	 * @return string[]
	 */
	public function allowed_modules() {
		return array(
			'header_footer',
		);
	}

	/**
	 * Allowed widget keys.
	 *
	 * @return string[]
	 */
	public function allowed_widgets() {
		return array(
			'site_logo',
			'site_title',
			'nav_menu',
			'search_form',
			'hero_section',
			'call_to_action',
			'image_box',
			'image_carousel',
			'media_carousel',
			'slides',
			'icon_box',
			'team_member',
			'testimonial_carousel',
			'posts',
			'breadcrumbs',
		);
	}

	/**
	 * Sanitize settings.
	 *
	 * @param mixed $settings Raw settings.
	 * @return array<string,mixed>
	 */
	public function sanitize( $settings ) {
		$settings = is_array( $settings ) ? $settings : array();

		return array(
			'modules' => $this->sanitize_enabled_map(
				isset( $settings['modules'] ) ? $settings['modules'] : array(),
				$this->allowed_modules()
			),
			'widgets' => $this->sanitize_enabled_map(
				isset( $settings['widgets'] ) ? $settings['widgets'] : array(),
				$this->allowed_widgets()
			),
		);
	}

	/**
	 * Sanitize an enabled map against allowed keys.
	 *
	 * @param mixed    $values Raw values.
	 * @param string[] $allowed Allowed keys.
	 * @return array<string,bool>
	 */
	private function sanitize_enabled_map( $values, array $allowed ) {
		$values = is_array( $values ) ? $values : array();
		$clean  = array();

		foreach ( $allowed as $key ) {
			$clean[ $key ] = ! empty( $values[ $key ] );
		}

		return $clean;
	}
}
