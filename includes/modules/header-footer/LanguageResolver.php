<?php
/**
 * Multilingual language detection.
 *
 * @package AlternatePro\Elements
 */

namespace AlternatePro\Elements\Modules\HeaderFooter;

use AlternatePro\Elements\Helpers;

defined( 'ABSPATH' ) || exit;

/**
 * Resolves current and admin-selectable languages without hard dependencies.
 */
final class LanguageResolver {
	/**
	 * Current request language cache.
	 *
	 * @var string|null
	 */
	private $current = null;

	/**
	 * Get current language.
	 *
	 * @return string
	 */
	public function get_current_language() {
		if ( null !== $this->current ) {
			return $this->current;
		}

		$this->current = $this->detect_current_language();

		return $this->current;
	}

	/**
	 * Get language options for admin controls.
	 *
	 * @return array<string,string>
	 */
	public function get_admin_options() {
		$options = array(
			'all' => __( 'All Languages', 'alternatepro-elements' ),
			'th'  => __( 'Thai', 'alternatepro-elements' ),
			'en'  => __( 'English', 'alternatepro-elements' ),
		);

		foreach ( $this->get_detected_languages() as $code => $label ) {
			$options[ $code ] = $label;
		}

		return $options;
	}

	/**
	 * Resolve the display label for a language code.
	 *
	 * @param string $code Language code.
	 * @return string
	 */
	public function get_label( $code ) {
		$code    = sanitize_key( $code );
		$options = $this->get_admin_options();

		return isset( $options[ $code ] ) ? $options[ $code ] : strtoupper( $code );
	}

	/**
	 * Detect the current language.
	 *
	 * @return string
	 */
	private function detect_current_language() {
		if ( function_exists( 'pll_current_language' ) ) {
			$language = pll_current_language( 'slug' );

			if ( $language ) {
				return sanitize_key( $language );
			}
		}

		if ( has_filter( 'wpml_current_language' ) ) {
			$language = apply_filters( 'wpml_current_language', null );

			if ( $language ) {
				return sanitize_key( $language );
			}
		}

		if ( function_exists( 'trp_get_current_language' ) ) {
			$language = trp_get_current_language();

			if ( $language ) {
				return sanitize_key( $language );
			}
		}

		if ( isset( $GLOBALS['TRP_LANGUAGE'] ) && is_string( $GLOBALS['TRP_LANGUAGE'] ) ) {
			return sanitize_key( $GLOBALS['TRP_LANGUAGE'] );
		}

		$path = Helpers::current_path();

		if ( preg_match( '#^/([a-z]{2,3})(?:/|$)#i', $path, $matches ) ) {
			return sanitize_key( $matches[1] );
		}

		return 'all';
	}

	/**
	 * Get language list from active multilingual plugins when available.
	 *
	 * @return array<string,string>
	 */
	private function get_detected_languages() {
		$languages = array();

		if ( function_exists( 'pll_languages_list' ) ) {
			$polylang = pll_languages_list( array( 'fields' => 'slug' ) );

			foreach ( (array) $polylang as $code ) {
				$code               = sanitize_key( $code );
				$languages[ $code ] = strtoupper( $code );
			}
		}

		if ( has_filter( 'wpml_active_languages' ) ) {
			$wpml = apply_filters( 'wpml_active_languages', null, array( 'skip_missing' => 0 ) );

			foreach ( (array) $wpml as $code => $language ) {
				$code = sanitize_key( $code );

				if ( is_array( $language ) && ! empty( $language['native_name'] ) ) {
					$languages[ $code ] = sanitize_text_field( $language['native_name'] );
				} else {
					$languages[ $code ] = strtoupper( $code );
				}
			}
		}

		$trp_settings = get_option( 'trp_settings' );

		if ( is_array( $trp_settings ) && ! empty( $trp_settings['translation-languages'] ) ) {
			foreach ( (array) $trp_settings['translation-languages'] as $language ) {
				$code = sanitize_key( $language );

				if ( $code ) {
					$languages[ $code ] = strtoupper( $code );
				}
			}
		}

		return $languages;
	}
}
